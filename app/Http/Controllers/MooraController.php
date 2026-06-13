<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Rating;
use App\Models\Result;
use App\Models\ProcessHistory; // <-- Import model riwayat baru
use Illuminate\Http\Request;  // <-- Import Request untuk menangkap input form
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class MooraController extends Controller
{
    // ============================================================
    //  PROSES MOORA (Hitung Otomatis & Update Hasil Utama)
    // ============================================================
    public function process()
    {
        $criteria = Criterion::orderBy('order')->get();
        $alternatives = Alternative::orderBy('code')->get();
        $ratings = Rating::with(['alternative','criterion'])->get();

        // ======================
        // MATRIX X (Nilai Awal)
        // ======================
        $X = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $crit) {
                $r = $ratings
                    ->where('alternative_id', $alt->id)
                    ->where('criterion_id', $crit->id)
                    ->first();
                $X[$ai][$ci] = $r ? (float) $r->value : 0.0;
            }
        }

        // ======================
        // NORMALISASI
        // ======================
        $denominator = [];
        foreach ($criteria as $ci => $crit) {
            $sumSq = 0.0;
            foreach ($alternatives as $ai => $alt) {
                $sumSq += pow($X[$ai][$ci], 2);
            }
            $denominator[$ci] = sqrt($sumSq) ?: 1;
        }

        $R = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $crit) {
                $R[$ai][$ci] = $X[$ai][$ci] / $denominator[$ci];
            }
        }

        // ======================
        // NORMALISASI TERBOBOT
        // ======================
        $weights = $criteria->pluck('weight')->toArray();
        $V = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $crit) {
                $V[$ai][$ci] = $R[$ai][$ci] * (float) $weights[$ci];
            }
        }

        // ======================
        // HITUNG Yi
        // ======================
        $results = [];
        foreach ($alternatives as $ai => $alt) {
            $sumBenefit = 0.0;
            $sumCost = 0.0;

            foreach ($criteria as $ci => $crit) {
                if ($crit->type === 'benefit') {
                    $sumBenefit += $V[$ai][$ci];
                } else {
                    $sumCost += $V[$ai][$ci];
                }
            }

            $yi = $sumBenefit - $sumCost;

            $results[] = [
                'alternative_id' => $alt->id,
                'code' => $alt->code,
                'name' => $alt->name,
                'yi' => $yi,
                'sumBenefit' => $sumBenefit, 
                'sumCost' => $sumCost,       
                'detail' => [
                    'X' => $X[$ai],
                    'R' => $R[$ai],
                    'V' => $V[$ai]
                ]
            ];
        }

        // ======================
        // RANKING
        // ======================
        usort($results, function($a, $b) {
            return $b['yi'] <=> $a['yi'];
        });

        // ============================================================
        // PERBAIKAN: Menggunakan updateOrCreate agar tidak duplikat
        // ============================================================
        foreach ($results as $i => $r) {
            Result::updateOrCreate(
                ['alternative_id' => $r['alternative_id']], 
                [
                    'yi' => round($r['yi'], 6),
                    'rank' => $i + 1,
                    'details' => $r['detail'] 
                ]
            );
        }

        return view('moora.result', compact(
            'criteria','alternatives','X','R','V','results','denominator'
        ));
    }

    // ============================================================
    //  SIMPAN RIWAYAT PROSES (Sesi Hasil Logika MOORA ke JSON)
    // ============================================================
    public function storeHistory(Request $request)
    {
        $request->validate([
            'process_name' => 'required|string|max:255',
        ]);

        $criteria = Criterion::orderBy('order')->get();
        $alternatives = Alternative::orderBy('code')->get();
        $ratings = Rating::all();

        // 1. Matriks Awal (X)
        $X = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $crit) {
                $r = $ratings->where('alternative_id', $alt->id)->where('criterion_id', $crit->id)->first();
                $X[$ai][$ci] = $r ? (float) $r->value : 0.0;
            }
        }

        // 2. Pembagi Normalisasi
        $denominator = [];
        foreach ($criteria as $ci => $crit) {
            $sumSq = 0.0;
            foreach ($alternatives as $ai => $alt) { $sumSq += pow($X[$ai][$ci], 2); }
            $denominator[$ci] = sqrt($sumSq) ?: 1;
        }

        // 3. Normalisasi Terbobot (V)
        $R = []; $V = [];
        $weights = $criteria->pluck('weight')->toArray();
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $crit) {
                $R[$ai][$ci] = $X[$ai][$ci] / $denominator[$ci];
                $V[$ai][$ci] = $R[$ai][$ci] * (float) $weights[$ci];
            }
        }

        // 4. Perhitungan Nilai Akhir Optimasi (Yi)
        $results = [];
        foreach ($alternatives as $ai => $alt) {
            $sumBenefit = 0.0; $sumCost = 0.0;
            foreach ($criteria as $ci => $crit) {
                if ($crit->type === 'benefit') { $sumBenefit += $V[$ai][$ci]; } 
                else { $sumCost += $V[$ai][$ci]; }
            }
            $results[] = [
                'code' => $alt->code,
                'name' => $alt->name,
                'yi' => $sumBenefit - $sumCost
            ];
        }

        // Urutkan Array secara Descending berdasarkan Nilai Yi terbobot tertinggi
        usort($results, function($a, $b) { return $b['yi'] <=> $a['yi']; });

        // Transformasi ke struktur format data snapshot JSON
        $historyData = [];
        foreach ($results as $index => $res) {
            $historyData[] = [
                'rank' => $index + 1,
                'code' => $res['code'],
                'name' => $res['name'],
                'yi' => round($res['yi'], 6)
            ];
        }

        // Simpan Record Log Baru ke Database
        ProcessHistory::create([
            'process_name' => $request->process_name,
            'process_date' => now(),
            'results_data' => $historyData 
        ]);

        return redirect()->route('history.index')->with('success', 'Data riwayat kalkulasi sistem berhasil dibekukan!');
    }

    // ============================================================
    //  TAMPILKAN HASIL TERSIMPAN
    // ============================================================
    public function showStored()
    {
        $stored = Result::with('alternative')->orderBy('rank')->get();

        $criteria = Criterion::orderBy('order')->get();
        $alternatives = Alternative::orderBy('code')->get();
        $ratings = Rating::all();

        $X = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $c) {
                $r = $ratings
                    ->where('alternative_id', $alt->id)
                    ->where('criterion_id', $c->id)
                    ->first();

                $X[$ai][$ci] = $r ? (float) $r->value : 0.0;
            }
        }

        $den = [];
        foreach ($criteria as $ci => $c) {
            $sum = 0.0;
            foreach ($alternatives as $ai => $alt) {
                $sum += pow($X[$ai][$ci], 2);
            }
            $den[$ci] = sqrt($sum) ?: 1;
        }

        $R = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $c) {
                $R[$ai][$ci] = $X[$ai][$ci] / $den[$ci];
            }
        }

        $weights = $criteria->pluck('weight')->toArray();
        $V = [];
        foreach ($alternatives as $ai => $alt) {
            foreach ($criteria as $ci => $c) {
                $V[$ai][$ci] = $R[$ai][$ci] * $weights[$ci];
            }
        }

        return view('moora.stored', compact(
            'stored','criteria','V'
        ));
    }

    // ============================================================
    //  EXPORT PDF
    // ============================================================
    public function exportPDF()
    {
        setlocale(LC_TIME, 'id_ID.UTF-8');
        \Carbon\Carbon::setLocale('id');
        $stored = Result::with('alternative')->orderBy('rank')->get();
        $criteria = Criterion::all();
        $ratings = Rating::all();

        // Antisipasi jika data kosong
        if ($stored->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada data hasil perhitungan.');
        }

        $winnerResult = $stored->first();
        $winnerId = $winnerResult->alternative_id;

        $denominators = [];
        foreach ($criteria as $c) {
            $sum = 0.0;
            foreach ($ratings->where('criterion_id', $c->id) as $r) {
                $sum += pow($r->value, 2);
            }
            $denominators[$c->id] = sqrt($sum) ?: 1;
        }

        $totalBenefit = 0.0;
        $totalCost = 0.0;

        $benefitContrib = []; 
        $costContrib = [];    

        foreach ($criteria as $c) {
            $r = $ratings
                ->where('alternative_id', $winnerId)
                ->where('criterion_id', $c->id)
                ->first();

            $value = $r ? (float) $r->value : 0.0;
            $normalized = $value / $denominators[$c->id];
            $weighted = $normalized * $c->weight;

            if ($c->type === 'benefit') {
                $totalBenefit += $weighted;
                $benefitContrib[$c->name] = $weighted;
            } else {
                $totalCost += $weighted;
                $costContrib[$c->name] = $weighted;
            }
        }

        // Antisipasi jika array kosong untuk menghindari eror max/min
        $topBenefit = !empty($benefitContrib) ? array_search(max($benefitContrib), $benefitContrib) : '-';
        $bestCost   = !empty($costContrib) ? array_search(min($costContrib), $costContrib) : '-';

        return Pdf::loadView('moora.pdf', [
            'stored' => $stored,
            'winner' => [
                'name' => $winnerResult->alternative->name ?? 'Tidak Diketahui',
                'yi' => $winnerResult->yi
            ],
            'totalBenefit' => $totalBenefit,
            'totalCost' => $totalCost,
            'topBenefit' => $topBenefit,
            'bestCost' => $bestCost,
            'tanggalCetak' => now()->translatedFormat('d F Y')
        ])->stream('hasil_moora.pdf');
    }
}

