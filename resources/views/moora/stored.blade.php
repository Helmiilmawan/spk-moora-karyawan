@extends('layouts.app')

@section('content')

<style>
    /* Animasi Soft Fade */
    .animate-row {
        animation: fadeInUp 0.4s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover Row */
    table tbody tr:hover {
        background: rgba(0, 123, 255, 0.08) !important;
        transition: 0.2s;
    }

    .card {
        border-radius: 18px !important;
        border: 1px solid #dee2e6 !important; 
        background: #ffffff !important; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        transition: .25s ease;
    }

    .alert {
        background-color: #ffffff !important; 
        box-shadow: none !important;
        border: 1px solid #0d6efd;
        color: #212529 !important; 
    }
    .alert-info {
        background-color: #d1ecf1 !important;
        border-color: #bee5eb !important;
        color: #0c5460 !important;
    }

    .rank-1 {
        background: #e8ffed !important;
        border-left: 5px solid #009b24 !important;
        font-weight: 700;
    }
    
    .text-success { color: #198754 !important; }
    .text-danger { color: #dc3545 !important; }
    
</style>

<div class="container-fluid">

    <div class="card shadow-sm border-0 p-3 mb-4 rounded-4">
        <h3 class="fw-bold d-flex align-items-center gap-2 m-0">
            <i class="bi bi-archive text-primary"></i> Hasil Perhitungan Metode MOORA
        </h3>
        <p class="text-muted ms-1 mb-0">Riwayat perhitungan MOORA yang telah disimpan sebelumnya.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-4 p-3">
        
 {{-- ================= FORM SIMPAN RIWAYAT (BARU) ================= --}}
<div class="card p-3 mb-4 border border-primary animate-row">
    <div class="fw-semibold mb-2 text-dark">
        <i class="bi bi-cloud-arrow-up-fill text-primary"></i> Simpan Hasil Perhitungan ke Riwayat Sesi
    </div>
    <form action="{{ route('moora.storeHistory') }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="process_name" class="form-control" placeholder="Masukkan nama riwayat (Contoh: Seleksi Karyawan Periode Juni 2026)" required>
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-save"></i> Simpan Ke Riwayat
            </button>
        </div>
    </form>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('moora.export.pdf') }}" target="_blank" 
       class="btn btn-danger shadow-sm px-4 rounded-3">
        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
    </a>
</div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Alternatif</th>
                        <th>Nilai Yi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($stored as $s)
                        <tr class="animate-row {{ $s->rank == 1 ? 'rank-1' : '' }}">
                            <td>
                                <span class="badge {{ $s->rank == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $s->rank }}
                                </span>
                            </td>
                            <td class="fw-semibold">
                                {{ $s->alternative->code }} - {{ $s->alternative->name }}
                            </td>
                            <td>{{ number_format($s->yi, 3) }}</td> 
                        </tr>
                    @endforeach
                </tbody>
            </table>

@if(isset($resultsArr) && count($resultsArr) > 0)

@php
    $winner = $resultsArr[0];
    $winIndex = $winner['index'];

    // Ambil baris V pemenang (Nilai Ternormalisasi Terbobot)
    $winnerV = $V[$winIndex]; 
    $totalBenefit = 0;
    $totalCost = 0;

    // Pisahkan dan hitung total Benefit dan Cost
    $benefits = [];
    $costs = [];
    foreach($criteria as $ci => $c){
        if ($c->type == 'benefit') {
            $benefits[$c->name] = $winnerV[$ci];
            $totalBenefit += $winnerV[$ci];
        } else {
            $costs[$c->name] = $winnerV[$ci];
            $totalCost += $winnerV[$ci]; 
        }
    }

    arsort($benefits);
    $topBenefit = array_key_first($benefits);

    asort($costs);
    $bestCost = array_key_first($costs);
@endphp

<div class="alert alert-primary mt-4 rounded-4 animate-row">
    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
        <i class="bi bi-trophy-fill text-warning"></i> Analisis Hasil Perangkingan
    </h5>
    
    <p>
        Berdasarkan perhitungan Metode MOORA, Alternatif Terbaik yang terpilih adalah 
        <strong class="text-success">{{ $winner['name'] }}</strong> 
        dengan nilai optimasi tertinggi sebesar <strong>{{ number_format($winner['yi'], 3) }}</strong>.
    </p>

    <div class="my-3 border-top border-bottom py-2">
        <p class="mb-1">
            <i class="bi bi-graph-up-arrow text-success"></i> 
            Total Benefit (Keuntungan) yang diperoleh alternatif ini adalah: 
            <strong class="text-success">{{ number_format($totalBenefit, 3) }}</strong>.
        </p>
        <p class="mb-0">
            <i class="bi bi-graph-down-arrow text-danger"></i> 
            Total Cost (Beban/Kerugian) yang dimiliki alternatif ini adalah: 
            <strong class="text-danger">{{ number_format($totalCost, 3) }}</strong>.
        </p>
    </div>

    <p>
        Peringkat ini sangat didukung oleh performa pada kriteria <strong>Benefit</strong> yaitu <strong>{{ $topBenefit }}</strong>, 
        yang memberikan sumbangan nilai bobot terbesar dalam total Benefit. 
    </p>

    <p class="mb-0">
        Selain itu, dari sisi kriteria <strong>Cost</strong>, alternatif ini menunjukkan keunggulan pada kriteria <strong>{{ $bestCost }}</strong>, 
        karena kriteria ini memberikan beban (nilai cost terbobot) yang paling kecil
        sehingga berhasil meminimalisir pengurangan nilai hasil akhir (Yi).
    </p>
</div>

@endif


        </div>
    </div>
</div>

@endsection