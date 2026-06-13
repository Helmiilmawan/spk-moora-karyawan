Berikut adalah perbaikan kode untuk file view **Dashboard**. Warnanya telah disesuaikan sepenuhnya agar menggunakan skema *semi-dark solid premium* (`#1e293b` dan `#3b82f6`) serta mengikuti format **rata tengah (center)** seperti halaman data yang sudah kita perbarui sebelumnya:

```html
@extends('layouts.app')

@section('content')
<div class="container py-2">

    {{-- HEADER UTAMA RATA TENGAH --}}
    <h3 class="mb-4 text-white text-center fw-bold">
        <i class="bi bi-speedometer2 text-primary"></i> SISTEM PENDUKUNG KEPUTUSAN - METODE MOORA
    </h3>

    {{-- Informasi MOORA Semi-Dark Rata Tengah --}}
    <div class="mb-4 p-4 text-center" style="background: #1e293b; border-radius: 16px; border: 1px solid #334155; box-shadow: 0 8px 20px rgba(0,0,0,0.35);">
        <h4 class="fw-bold mb-2 text-white">
            <i class="bi bi-info-circle text-primary"></i> Apa Itu Metode MOORA?
        </h4>
        <p class="text-light mb-0 mx-auto" style="opacity: 0.9; max-width: 800px;">
            <b>MOORA (Multi-Objective Optimization by Ratio Analysis)</b> adalah metode pengambilan keputusan multi-kriteria 
            untuk menentukan alternatif terbaik berdasarkan beberapa kriteria secara objektif.
        </p>
    </div>

    {{-- Kartu Statistik Berwarna Senada --}}
    <div class="row mb-4 justify-content-center">
        @foreach ([
            'Total Alternatif' => ['value' => $totalAlternatif, 'icon' => 'bi-archive'],
            'Total Kriteria' => ['value' => $totalKriteria, 'icon' => 'bi-list-stars'],
            'Total Penilaian' => ['value' => $totalPenilaian, 'icon' => 'bi-check2-square']
        ] as $title => $data)
        <div class="col-md-4 mb-3">
            <div class="stat-card text-center" style="background: #1e293b; border-radius: 18px; padding:22px; color:#fff; box-shadow:0 8px 25px rgba(0,0,0,0.35); border:1px solid #334155; border-top: 4px solid #3b82f6;">
                <i class="bi {{ $data['icon'] }} h1 d-block mb-1 text-primary"></i>
                <small class="text-light" style="opacity: 0.8;">{{ $title }}</small>
                <h2 class="fw-bold m-0 mt-1 text-white">{{ $data['value'] }}</h2>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Grafik Batang --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0" style="background: #1e293b; border-radius:16px; border: 1px solid #334155;">
                <div class="card-header text-white fw-bold text-center" style="background: rgba(0, 0, 0, 0.2); border-bottom: 1px solid #334155;">
                    <i class="bi bi-graph-up-arrow text-primary"></i> Grafik Ranking MOORA
                </div>
                <div class="card-body">
                    <div style="height:420px">
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const canvas = document.getElementById('rankingChart');
    const ctx = canvas.getContext('2d');
    const rankingData = @json($ranking);

    if (!rankingData.length) return;

    // LABEL: Alternatif + Yi
    const labels = rankingData.map(item => [
        item.alternative,
        'Yi = ' + Number(item.nilai_yi).toFixed(4)
    ]);

    const dataYi = rankingData.map(item => item.nilai_yi);

    /* PLUGIN CUSTOM: RANK DI ATAS BAR */
    const rankLabelPlugin = {
        id: 'rankLabel',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 13px sans-serif';
            ctx.textAlign = 'center';

            chart.getDatasetMeta(0).data.forEach((bar, index) => {
                ctx.fillText(
                    (index + 1),
                    bar.x,
                    bar.y - 8
                );
            });

            ctx.restore();
        }
    };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Yi',
                data: dataYi,
                backgroundColor: '#3b82f6', // Menggunakan solid blue utama
                hoverBackgroundColor: '#2563eb', // Gelap sedikit saat disorot mouse
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    labels: { color: '#fff' }
                },
                tooltip: {
                    backgroundColor: '#0f172a', 
                    titleColor: '#fff',
                    bodyColor: '#cbd5e1',
                    callbacks: {
                        title: (items) => rankingData[items[0].dataIndex].alternative,
                        label: (item) => [
                            'Ranking : ' + (item.dataIndex + 1),
                            'Nilai Yi : ' + item.raw.toFixed(4)
                        ]
                    }
                }
            },

            scales: {
                x: {
                    grid: {
                        color: '#334155' 
                    },
                    ticks: {
                        color: '#fff',
                        padding: 10,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    grid: {
                        color: '#334155'
                    },
                    ticks: { color: '#fff' }
                }
            }
        },
        plugins: [rankLabelPlugin]
    });
});
</script>
@endpush