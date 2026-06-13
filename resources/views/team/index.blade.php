@extends('layouts.app')

@section('content')

<style>
    .team-card {
        border: 1px solid #334155;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        background: #1e293b; /* Menyesuaikan warna bar solid */
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        position: relative;
        max-width: 400px; /* Membatasi lebar maksimal card agar tetap proporsional */
        margin: 0 auto;
    }

    .team-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--role-color);
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        border-color: var(--role-color);
    }

    .team-img-wrapper {
        position: relative;
        display: inline-block;
        margin-top: 1.5rem;
    }

    .team-img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #334155;
        transition: all 0.35s ease;
    }

    .team-card:hover .team-img {
        transform: scale(1.05);
        border-color: var(--role-color);
        box-shadow: 0 0 15px var(--role-color);
    }
    
    .member-name {
        color: #ffffff;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .member-nim {
        color: #94a3b8 !important;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }
</style>

<div class="container py-2 text-center">

    <h2 class="fw-bold mb-2 text-black">Profil Pengembang Aplikasi</h2>
    <p class="text-dark mb-5" style="opacity: 0.8; font-size: 1.05rem;">
        Anggota mandiri yang merancang dan membangun Sistem Pendukung Keputusan Metode MOORA.
    </p>

    {{-- justify-content-center disematkan agar card berada di tengah --}}
    <div class="row justify-content-center">

        @foreach($team as $member)
        <div class="col-md-5 col-sm-8">  
            <div class="card team-card p-3 h-100" style="--role-color: {{ $member['color'] }};">

                <div class="team-img-wrapper">
                    <img src="{{ $member['foto'] }}" class="team-img mx-auto" alt="Foto {{ $member['nama'] }}">
                </div>

                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="member-name mt-3 mb-1">{{ $member['nama'] }}</h5>
                        <p class="member-nim mb-4">NIM. {{ $member['nim'] }}</p>
                    </div>

                    <div>
                        <span class="badge d-inline-block px-4 py-2 rounded-pill shadow-sm" 
                              style="background-color: {{ $member['color'] }}; color: #fff; font-weight: 500; font-size: 0.825rem; letter-spacing: 0.3px;">
                            {{ $member['role'] }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
        @endforeach

    </div>

</div>

@endsection