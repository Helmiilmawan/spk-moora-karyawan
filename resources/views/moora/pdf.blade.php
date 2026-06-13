<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Perhitungan metode MOORA</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            text-align: center;
            color: #000;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background: #eee;
            padding: 8px;
            text-align: center;
        }
        td {
            padding: 8px;
            text-align: center;
        }

        /* Analisis Box */
        .analysis-box {
            text-align: left;
            border: 1px solid #000;
            padding: 15px;
            background-color: #ffffff;
            margin-top: 20px;
        }
        .analysis-title {
            color: #000;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .text-success { color: #008000; font-weight: bold; }
        .text-danger { color: #cc0000; font-weight: bold; }
        .fw-bold, strong { font-weight: bold; }
        .divider {
            border-top: 1px solid #000;
            margin: 10px 0;
            padding: 5px 0;
        }
    </style>
</head>
<body>

    <h2>Laporan Hasil Perhitungan dengan Metode MOORA</h2>

<p style="text-align:center; margin-top:-5px;">
    Tanggal Cetak: {{ $tanggalCetak }}
</p>
    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>Alternatif</th>
                <th>Hasil Perhitungan (Yi)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stored as $s)
                <tr>
                    <td>{{ $s->rank }}</td>
                    <td>{{ $s->alternative->code }} - {{ $s->alternative->name }}</td>
                    <td>{{ number_format($s->yi, 6) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($stored) > 0)
        <div class="analysis-box">
            <h3 class="analysis-title fw-bold">Analisis Alasan Peringkat</h3>
            
            <p>
                Berdasarkan hasil perhitungan, Alternatif Terbaik (Ranking 1) adalah 
                <span class="text-success">{{ $winner['name'] }}</span> 
                dengan nilai optimasi tertinggi sebesar <strong>{{ number_format($winner['yi'],6) }}</strong>.
            </p>

            <div class="divider">
                <p style="margin: 0 0 5px 0;">
                    &bull; Total Benefit (Keuntungan) yang diperoleh alternatif ini adalah: 
                    <span class="text-success">{{ number_format($totalBenefit, 4) }}</span>.
                </p>
                <p style="margin: 0;">
                    &bull; Total Cost (Beban/Kerugian) yang dimiliki alternatif ini adalah: 
                    <span class="text-danger">{{ number_format($totalCost, 4) }}</span>.
                </p>
            </div>
            
            <p>
                Peringkat ini sangat didukung oleh performa unggul pada kriteria <strong>Benefit</strong> yaitu 
                <strong class="fw-bold">{{ $topBenefit }}</strong>, 
                yang memberikan kontribusi nilai bobot <strong>terbesar</strong> dalam total Benefit.
            </p>

            <p style="margin-bottom: 0;">
                Dari sisi kriteria <strong>Cost</strong>, alternatif ini menunjukkan keunggulan pada kriteria 
                <strong class="fw-bold">{{ $bestCost }}</strong>, 
                karena kriteria ini memberikan beban (nilai cost terbobot) yang <strong>paling kecil</strong>, 
                sehingga efektif meminimalisir pengurangan pada nilai hasil akhir (Yi).
            </p>
        </div>
    @endif

</body>
</html>
