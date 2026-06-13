<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================
        // Data Statistik Dashboard
        // =========================
        $totalAlternatif = Alternative::count();
        $totalKriteria = Criterion::count();
        $totalPenilaian = Rating::count();

        $filledAlternatif = Rating::select('alternative_id')->distinct()->count();
        $filledKriteria = Rating::select('criterion_id')->distinct()->count();

        $lastUpdates = Rating::with(['alternative', 'criterion'])
                             ->orderBy('updated_at', 'desc')
                             ->take(5)
                             ->get();

        // =========================
        // Ranking MOORA
        // =========================
        $criteria = Criterion::all();
        $alternatives = Alternative::all();
        $ranking = [];

        if (!$criteria->isEmpty() && !$alternatives->isEmpty()) {

            // Ambil semua rating dan index berdasarkan alternative_id & criterion_id
            $allRatings = Rating::select('alternative_id', 'criterion_id', 'value')->get();
            $ratingIndex = [];
            foreach ($allRatings as $r) {
                $ratingIndex[$r->alternative_id][$r->criterion_id] = $r->value;
            }

            // Hitung denominator (sqrt sum of squares) per kriteria
         $denominators = [];
foreach ($criteria as $c) {
    $sumSq = Rating::where('criterion_id', $c->id)->sum(DB::raw('value * value'));
    // Ubah 0 menjadi 1 sebagai fallback (atau lebih aman: cek sebelum dibagi)
    $denominators[$c->id] = $sumSq > 0 ? sqrt($sumSq) : 1; 
}

            // Hitung nilai optimasi Yi per alternatif
            foreach ($alternatives as $alt) {
                $yi = 0.0;
                $is_complete = true;

                foreach ($criteria as $c) {
                    $value = $ratingIndex[$alt->id][$c->id] ?? null;

                    if ($value === null) {
                        $is_complete = false;
                        continue;
                    }

                    $denom = $denominators[$c->id];
                    $normalisasi = $value / $denom;
                    $nilaiBobot = $normalisasi * $c->weight;

                    if (strtolower($c->type) === 'cost') {
                        $nilaiBobot *= -1;
                    }

                    $yi += $nilaiBobot;
                }

                $ranking[] = [
                    'alternative' => $alt->name,
                    'nilai_yi' => round($yi, 6),
                    'is_complete' => $is_complete,
                ];
            }

            // Urutkan dari nilai Yi tertinggi
            usort($ranking, function($a, $b) {
                return $b['nilai_yi'] <=> $a['nilai_yi'];
            });

            // Pastikan array murni untuk Blade @json
            $ranking = array_values($ranking);
        }

        // =========================
        // Kirim data ke view
        // =========================
        
        return view('dashboard', compact(
            'totalAlternatif',
            'totalKriteria',
            'totalPenilaian',
            'filledAlternatif',
            'filledKriteria',
            'lastUpdates',
            'ranking'
        ));
    }
}
