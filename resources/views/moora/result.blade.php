@extends('layouts.app')

@section('content')

<style>
.animate-row{animation:fadeInUp .4s ease-in-out}
@keyframes fadeInUp{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
table tbody tr:hover{background:rgba(0,123,255,.08)!important;transition:.2s}
.card{border-radius:18px!important;background:#fff!important;box-shadow:0 4px 15px rgba(0,0,0,.08)}
.section-title{font-weight:700;letter-spacing:.5px;display:flex;align-items:center;gap:6px}
.winning-row{background:#e8ffed!important;font-weight:700!important;border-left:5px solid #00a025!important}
.alert-blue{background:#e2f4ff;border-color:#b3d9ff;color:#004085}
/* Style untuk tombol rumus */
.formula-toggle-btn {
    font-size: 0.85rem;
    padding: 0.25rem 0.5rem;
}
</style>

<div class="container-fluid">

{{-- ================= HEADER ================= --}}
<{{-- ================= HEADER ================= --}}
<div class="mb-4 card p-3">
    <h3 class="fw-bold section-title m-0">
        <i class="bi bi-graph-up-arrow text-primary"></i> Hasil Proses MOORA
    </h3>
    <p class="text-muted ms-1 mb-0">Perhitungan lengkap metode MOORA</p>
</div>


{{-- ================= MATRICS X ================= --}}
<div class="card p-3 mb-4">
    <div class="fw-semibold mb-2 text-black">
        <i class="bi bi-table"></i> Matriks Keputusan (X)
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-primary">
            <tr>
                <th>Alternatif</th>
                @foreach($criteria as $c)
                    <th>{{ $c->code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($alternatives as $ai => $alt)
                <tr class="animate-row">
                    <td class="fw-semibold">{{ $alt->code }} - {{ $alt->name }}</td>
                    @foreach($criteria as $ci => $c)
                        <td>{{ $X[$ai][$ci] }}</td> 
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ================= PENJELASAN NORMALISASI ================= --}}
<div class="card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="fw-semibold text-black">
            <i class="bi bi-calculator"></i> Proses Normalisasi MOORA
        </div>
        {{-- TOMBOL LIHAT RUMUS NORMALISASI (R) --}}
        <button class="btn btn-outline-info btn-sm formula-toggle-btn" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#collapseRumusR" 
                aria-expanded="false" 
                aria-controls="collapseRumusR"
                data-rumus-toggle-info>
            <i class="bi bi-eye"></i> Lihat Rumus
        </button>
    </div>

    {{-- RUMUS NORMALISASI (R) - DISEM BUNYIKAN SECARA DEFAULT --}}
    <div class="collapse" id="collapseRumusR">
        <div class="alert alert-info mt-3 rounded-4">
            <b>Rumus Normalisasi (MOORA):</b><br>
            R<sub>ij</sub> = X<sub>ij</sub> / √( Σ X<sub>ij</sub><sup>2</sup> )
            <div class="alert alert-info rounded-4 mt-2">
        <b>Keterangan :</b>
        <ul class="mb-0">
            <li>
                <b>R<sub>ij</sub></b> :
                nilai hasil <b>normalisasi</b> alternatif ke-i pada kriteria ke-j.
            </li>
            <li>
                <b>X<sub>ij</sub></b> :
                nilai awal (input) alternatif ke-i pada kriteria ke-j
                pada matriks keputusan.
            </li>
            <li>
                <b>i</b> :
                indeks alternatif (A1, A2, A3, dan seterusnya).
            </li>
            <li>
                <b>j</b> :
                indeks kriteria (C1, C2, C3, dan seterusnya).
            </li>
            <li>
                <b>√( Σ X<sub>ij</sub><sup>2</sup> )</b> :
                akar dari jumlah kuadrat seluruh nilai alternatif
                pada kriteria ke-j sebagai penyebut normalisasi.
            </li>
        </ul>
    </div>

            <hr class="my-2">
            Artinya, setiap nilai alternatif dibagi dengan <b>akar jumlah kuadrat</b>
            dari seluruh nilai pada kriteria yang sama.
        </div>
    </div>
    
{{-- ================= HITUNG MANUAL SEMUA ALTERNATIF & KRITERIA ================= --}}
<div class="card p-3 mb-3 border border-success">

    <div class="fw-semibold mb-2 text-black">
        <i class="bi bi-pencil-square"></i>
        Perhitungan Manual Normalisasi
    </div>

    @foreach($criteria as $ci => $c)
        @php
            $sum = 0;
            foreach($alternatives as $ai => $a){
                $sum += pow($X[$ai][$ci], 2);
            }
            $penyebut = sqrt($sum);
            $collapseId = 'collapseManual' . $c->code; 
        @endphp

        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="fw-semibold text-primary">
                🔹 {{ $c->code }} – {{ $c->name }}
            </div>
            
            {{-- TOMBOL LIHAT PERHITUNGAN MANUAL PER KRITERIA --}}
            <button class="btn btn-outline-success btn-sm formula-toggle-btn" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#{{ $collapseId }}" 
                    aria-expanded="false" 
                    aria-controls="{{ $collapseId }}"
                    data-rumus-toggle-manual>
                <i class="bi bi-eye"></i> Lihat Langkah
            </button>
        </div>
        <div class="collapse" id="{{ $collapseId }}">
            {{-- PENYEBUT --}}
            <div class="alert alert-light rounded-3 py-1 px-2 my-2 text-center">
                √(
                @foreach($alternatives as $ai => $a)
                    {{ $X[$ai][$ci] }}²{{ !$loop->last ? ' + ' : '' }}
                @endforeach
                ) = <b>{{ number_format($penyebut, 3) }}</b>
            </div>

            {{-- TABEL NORMALISASI --}}
            <table class="table table-bordered text-center mb-3">
                <thead class="table-success">
                    <tr>
                        <th>Alt</th>
                        <th>X<sub>ij</sub></th>
                        <th>X<sub>ij</sub> / √Σx²</th>
                        <th>R<sub>ij</sub></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatives as $ai => $alt)
                        <tr class="animate-row">
                            <td class="fw-semibold">{{ $alt->code }}</td>
                            <td>{{ $X[$ai][$ci] }}</td>
                            <td>{{ $X[$ai][$ci] }} / {{ number_format($penyebut, 3) }}</td>
                            <td class="fw-semibold text-success">
                                {{ number_format($R[$ai][$ci], 3) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach


    <div class="alert alert-blue rounded-4 mt-3">
        <i class="bi bi-check-circle-fill"></i>
        Hasil normalisasi <b>R<sub>ij</sub></b> di atas selanjutnya digunakan
        pada tahap pembobotan dan perhitungan nilai akhir <b>Yi</b>.
    </div>

</div>
    
        </tbody>
    </table>

    {{-- ================= R ================= --}}
    <div class="fw-semibold text-black mb-2">
        2️⃣ Hasil Normalisasi (R)
    </div>

    <table class="table table-bordered text-center mb-4">
        <thead class="table-primary">
            <tr>
                <th>Alternatif</th>
                @foreach($criteria as $c)
                    <th>{{ $c->code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($alternatives as $ai => $alt)
                <tr class="animate-row">
                    <td class="fw-semibold">{{ $alt->code }}</td>
                    @foreach($criteria as $ci => $c)
                        <td>{{ number_format($R[$ai][$ci], 3) }}</td> 
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= W ================= --}}
    <div class="fw-semibold text-black mb-2">
        3️⃣ Bobot Kriteria (W)
    </div>

    <table class="table table-bordered text-center mb-4">
        <thead class="table-secondary">
            <tr>
                @foreach($criteria as $c)
                    <th>{{ $c->code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr class="animate-row">
                @foreach($criteria as $c)
                    <td class="fw-semibold">{{ number_format($c->weight, 2) }}</td> 
                @endforeach
            </tr>
        </tbody>
    </table>

    {{-- ================= V ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="fw-semibold text-black">
            4️⃣ Nilai Terbobot (V = R × W)
        </div>
        {{-- TOMBOL LIHAT RUMUS NILAI TERBOBOT (V) --}}
        <button class="btn btn-outline-info btn-sm formula-toggle-btn" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#collapseRumusV" 
                aria-expanded="false" 
                aria-controls="collapseRumusV"
                data-rumus-toggle-info>
            <i class="bi bi-eye"></i> Lihat Rumus
        </button>
    </div>

    {{-- RUMUS NILAI TERBOBOT (V) - DISEM BUNYIKAN SECARA DEFAULT --}}
    <div class="collapse" id="collapseRumusV">
        <div class="alert alert-info rounded-4 mb-3">
            <b>Rumus Nilai Terbobot:</b><br>
            V<sub>ij</sub> = R<sub>ij</sub> × W<sub>j</sub>

            <hr class="my-2">

            <b>Keterangan:</b>
            <ul class="mb-0">
                <li>
                    <b>V<sub>ij</sub></b> :
                    nilai normalisasi yang telah dikalikan dengan bobot kriteria.
                </li>
                <li>
                    <b>R<sub>ij</sub></b> :
                    nilai normalisasi alternatif ke-i pada kriteria ke-j.
                </li>
                <li>
                    <b>W<sub>j</sub></b> :
                    bobot kepentingan kriteria ke-j.
                </li>
            </ul>

            <hr class="my-2">

            Nilai terbobot digunakan untuk menunjukkan
            kontribusi setiap kriteria terhadap nilai akhir alternatif.
        </div>
    </div>


    <table class="table table-bordered text-center">
        <thead class="table-success">
            <tr>
                <th>Alternatif</th>
                @foreach($criteria as $c)
                    <th>{{ $c->code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($alternatives as $ai => $alt)
                <tr class="animate-row">
                    <td class="fw-semibold">{{ $alt->code }}</td>
                    @foreach($criteria as $ci => $c)
                        <td>{{ number_format($V[$ai][$ci], 3) }}</td> 
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ================= RANKING ================= --}}
<div class="card p-3 mb-4">

    {{-- JUDUL DAN TOMBOL RUMUS NILAI YI --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="fw-semibold text-dark">
            <i class="bi bi-trophy-fill"></i> Nilai Yi & Perankingan
        </div>
        
        <div class="d-flex gap-2">
            {{-- TOMBOL LIHAT RUMUS NILAI YI (Matematika) --}}
            <button class="btn btn-outline-info btn-sm formula-toggle-btn" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseRumusYi" 
                    aria-expanded="false" 
                    aria-controls="collapseRumusYi"
                    data-rumus-toggle-info>
                <i class="bi bi-eye"></i> Lihat Rumus
            </button>

            {{-- TOMBOL LIHAT KONSEP YI (Konsep Maks/Min) --}}
            <button class="btn btn-outline-secondary btn-sm formula-toggle-btn" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseKonsepYi" 
                    aria-expanded="false" 
                    aria-controls="collapseKonsepYi"
                    data-rumus-toggle-konsep>
                <i class="bi bi-lightbulb"></i> Lihat Konsep Yi
            </button>
        </div>
    </div>
    <div class="collapse" id="collapseRumusYi">
        <div class="alert alert-info rounded-4 mb-3">

            <b>Menentukan nilai Yi menggunakan persamaan metode MOORA:</b>

            <div class="mt-2 fw-semibold">
                Y<sub>i</sub> =
                <span style="font-size:15px;">
                    ∑<sub>j=1</sub><sup>g</sup> w<sub>j</sub>x<sub>ij</sub>
                    −
                    ∑<sub>j=g+1</sub><sup>n</sup> w<sub>j</sub>x<sub>ij</sub>
                </span>
            </div>

            <hr class="my-2">
    <b>Keterangan :</b>
    <ul class="mb-0">
        <li>
            <b>Y<sub>i</sub></b> :
            nilai optimasi akhir alternatif ke-i yang digunakan
            sebagai dasar dalam proses perankingan.
        </li>

        <li>
            <b>i</b> :
            indeks alternatif (A1, A2, A3, dan seterusnya).
        </li>

        <li>
            <b>j</b> :
            indeks kriteria (C1, C2, C3, dan seterusnya).
        </li>

        <li>
            <b>w<sub>j</sub></b> :
            bobot kepentingan kriteria ke-j
            yang menunjukkan tingkat prioritas setiap kriteria.
        </li>

        <li>
            <b>x<sub>ij</sub></b> :
            nilai normalisasi alternatif ke-i pada kriteria ke-j
            (nilai <b>R<sub>ij</sub></b> yang telah diproses sebelumnya).
        </li>

        <li>
            <b>∑<sub>j=1</sub><sup>g</sup> w<sub>j</sub>x<sub>ij</sub></b> :
            jumlah seluruh nilai terbobot dari
            <b>kriteria bertipe benefit</b>
            (semakin besar nilainya semakin baik).
        </li>

        <li>
            <b>∑<sub>j=g+1</sub><sup>n</sup> w<sub>j</sub>x<sub>ij</sub></b> :
            jumlah seluruh nilai terbobot dari
            <b>kriteria bertipe cost</b>
            (semakin kecil nilainya semakin baik).
        </li>

        <li>
            <b>g</b> :
            jumlah kriteria yang bertipe <b>benefit</b>.
        </li>

        <li>
            <b>n</b> :
            jumlah seluruh kriteria yang digunakan
            dalam pengambilan keputusan.
        </li>
    </ul>

        </div>
    </div>
    
    {{-- AMBIL KODE KRITERIA DINAMIS --}}
    @php
        $benefitCodes = $criteria->where('type','benefit')->pluck('code')->implode(' + ');
        $costCodes  = $criteria->where('type','cost')->pluck('code')->implode(' + ');
    @endphp

<div class="alert alert-light rounded-4 mb-3">
    <b>Penentuan Ranking:</b><br>
    Alternatif diranking berdasarkan nilai <b>Y<sub>i</sub></b>
    dari yang terbesar hingga yang terkecil.
    Alternatif dengan nilai <b>Y<sub>i</sub></b> tertinggi
    merupakan alternatif terbaik.
</div>
    <div class="collapse" id="collapseKonsepYi">
        <ul class="mb-3">
            <li>
                <b>Maksimum</b> =
                jumlah nilai terbobot (<b>V</b>) dari
                <b>kriteria bernilai benefit</b>.
            </li>
            <li>
                <b>Minimum</b> =
                jumlah nilai terbobot (<b>V</b>) dari
                <b>kriteria bernilai cost</b>.
            </li>
            <li>
                <b>Yi (Hasil Akhir)</b> =
                <b>Maksimum − Minimum</b>.
            </li>
        </ul>
    </div>
    
    {{-- TABEL PERANGKINGAN --}}
<table class="table table-bordered text-center">
    <thead class="table-secondary">
        <tr>
            <th width="8%" class="text-center align-middle">
                Ranking
            </th>

            <th width="25%" class="text-center align-middle">
                Alternatif
            </th>

            <th class="text-center align-middle">
                Maksimum
                <br>
                <small class="text-success d-block">
                    ({{ $benefitCodes }})
                </small>
            </th>

            <th class="text-center align-middle">
                Minimum
                <br>
                <small class="text-danger d-block">
                    ({{ $costCodes }})
                </small>
            </th>

            <th class="text-center align-middle">
                Yi
                <br>
                <small class="text-primary d-block">
                    (Max − Min)
                </small>
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach($results as $idx => $r)
            <tr class="animate-row {{ $idx==0 ? 'winning-row' : '' }}">
                <td class="align-middle">
                    <span class="badge {{ $idx==0 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $idx + 1 }}
                    </span>
                </td>

                <td class="fw-semibold align-middle">
                    {{ $r['code'] }} - {{ $r['name'] }}
                </td>

                <td class="align-middle">
                    {{-- Sum Benefit: 3 angka di belakang koma (sebelumnya 6) --}}
                    {{ number_format($r['sumBenefit'], 3) }} 
                </td>

                <td class="align-middle">
                    {{-- Sum Cost: 3 angka di belakang koma (sebelumnya 6) --}}
                    {{ number_format($r['sumCost'], 3) }} 
                </td>

                <td class="fw-semibold text-dark align-middle">
                    {{-- Hasil Akhir Yi: 3 angka di belakang koma (sebelumnya 6) --}}
                    {{ number_format($r['yi'], 3) }} 
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- KESIMPULAN --}}
@if(!empty($results) && count($results) > 0)
<div class="alert alert-blue mt-3 rounded-4">
    <i class="bi bi-check-circle-fill"></i>
    <b>Kesimpulan:</b><br>

    Berdasarkan hasil perhitungan menggunakan metode
    <b>MOORA</b>

    @if(isset($studiKasus))
        yang bertujuan untuk
        <b>{{ $studiKasus->tujuan }}</b>,
    @endif

    maka diperoleh bahwa alternatif
    <strong>{{ $results[0]['name'] }}</strong>
    merupakan alternatif terbaik karena memiliki nilai
    <b>Yi</b> tertinggi
    dibandingkan alternatif lainnya, sehingga paling sesuai
    dengan tujuan studi kasus yang telah ditetapkan.
</div>
@else
<div class="alert alert-warning mt-3 rounded-4">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <b>Perhatian:</b>
    Data belum lengkap atau proses MOORA belum dijalankan.
</div>
@endif



</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fungsi untuk tombol Informasi (Normalisasi, Terbobot, Rumus Yi)
    document.querySelectorAll('[data-rumus-toggle-info]').forEach(btn => {
        const targetId = btn.getAttribute('data-bs-target');
        const targetEl = document.querySelector(targetId);

        if (targetEl) {
            targetEl.addEventListener('shown.bs.collapse', () => {
                btn.innerHTML = '<i class="bi bi-eye-slash"></i> Tutup Rumus';
                btn.classList.remove('btn-outline-info');
                btn.classList.add('btn-info', 'text-white');
            });

            targetEl.addEventListener('hidden.bs.collapse', () => {
                btn.innerHTML = '<i class="bi bi-eye"></i> Lihat Rumus';
                btn.classList.remove('btn-info', 'text-white');
                btn.classList.add('btn-outline-info');
            });
        }
    });

    // Fungsi untuk tombol Konsep Yi (Maks/Min)
    document.querySelectorAll('[data-rumus-toggle-konsep]').forEach(btn => {
        const targetId = btn.getAttribute('data-bs-target');
        const targetEl = document.querySelector(targetId);

        if (targetEl) {
            targetEl.addEventListener('shown.bs.collapse', () => {
                btn.innerHTML = '<i class="bi bi-eye-slash"></i> Tutup Konsep';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-secondary', 'text-white');
            });

            targetEl.addEventListener('hidden.bs.collapse', () => {
                btn.innerHTML = '<i class="bi bi-lightbulb"></i> Lihat Konsep Yi';
                btn.classList.remove('btn-secondary', 'text-white');
                btn.classList.add('btn-outline-secondary');
            });
        }
    });

    // Fungsi untuk tombol Perhitungan Manual Normalisasi (Langkah/Manual)
    document.querySelectorAll('[data-rumus-toggle-manual]').forEach(btn => {
        const targetId = btn.getAttribute('data-bs-target');
        const targetEl = document.querySelector(targetId);

        if (targetEl) {
            targetEl.addEventListener('shown.bs.collapse', () => {
                btn.innerHTML = '<i class="bi bi-eye-slash"></i> Tutup Langkah';
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success', 'text-white');
            });

            targetEl.addEventListener('hidden.bs.collapse', () => {
                btn.innerHTML = '<i class="bi bi-eye"></i> Lihat Langkah';
                btn.classList.remove('btn-success', 'text-white');
                btn.classList.add('btn-outline-success');
            });
        }
    });
});
</script>

@endsection