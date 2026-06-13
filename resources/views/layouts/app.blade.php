<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK MOORA</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: url('/images/bg-moora.jpg') center/cover fixed no-repeat;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            animation: fadeIn .4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================================ */
        /* PERBAIKAN DI SINI: Warna Navbar Solid (Tidak Transparan)     */
        /* ============================================================ */
        .navbar-glass {
            background: #1e293b !important; /* Warna Biru Gelap Solid/Slate */
            border-bottom: 2px solid #334155;
            box-shadow: 0 4px 20px rgba(0,0,0,.3);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.35rem;
            color: #fff !important;
            letter-spacing: 1.2px;
            text-shadow: 0 0 10px rgba(255,255,255,.3);
        }

        .nav-link {
            color: #cbd5e1 !important; /* Warna teks abu terang saat normal */
            padding: 9px 14px !important;
            margin: 0 4px;
            border-radius: 12px;
            font-weight: 500;
            transition: .25s ease;
        }

        .nav-link:hover {
            background: #334155; /* Highlight box gelap saat hover */
            transform: translateY(-2px);
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(0,0,0,.2);
        }

        .nav-link.active {
            background: #3b82f6 !important; /* Warna Biru Aksen Aktif */
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59,130,246,.4);
        }

        .alert-success {
            border-radius: 14px;
            background: rgba(0,255,120,.25);
            border: 1px solid rgba(255,255,255,.3);
            color: #fff;
            backdrop-filter: blur(10px);
            font-weight: 500;
        }

        .card-glass {
            background: rgba(255,255,255,.35);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
            border: 1px solid rgba(255,255,255,.4);
            color: #000;
        }

        h1, h2, h3, h4, h5, p, label, span {
            text-shadow: 1px 1px 4px rgba(0,0,0,.55);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="bi bi-stars me-2"></i> SPK MOORA
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('alternatives*') ? 'active' : '' }}"
                       href="{{ route('alternatives.index') }}">
                        <i class="bi bi-people-fill me-1"></i> Alternatif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('criteria*') ? 'active' : '' }}"
                       href="{{ route('criteria.index') }}">
                        <i class="bi bi-list-task me-1"></i> Kriteria
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('ratings*') ? 'active' : '' }}"
                       href="{{ route('ratings.index') }}">
                        <i class="bi bi-clipboard-check me-1"></i> Nilai
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('moora/process') ? 'active' : '' }}"
                       href="{{ route('moora.process') }}">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Proses
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('moora/results') ? 'active' : '' }}"
                       href="{{ route('moora.results') }}">
                        <i class="bi bi-trophy-fill me-1"></i> Hasil
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('history*') ? 'active' : '' }}"
                       href="{{ route('history.index') }}">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Proses
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('team') ? 'active' : '' }}"
                       href="{{ route('team.index') }}">
                        <i class="bi bi-person-badge me-1"></i> Profil
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div style="padding-top:95px"></div>

<main class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

@stack('scripts')

</body>
</html>