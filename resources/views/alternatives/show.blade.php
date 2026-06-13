@extends('layouts.app')

@section('content')

<div class="container">

   {{-- HEADER --}}
<div class="header-card mb-4">
    <h4 class="fw-bold mb-1 text-dark">
        <i class="bi bi-eye-fill text-primary me-2"></i> Detail Alternatif
    </h4>
    <small class="text-dark">Informasi lengkap mengenai alternatif yang dipilih.</small>
</div>

    {{-- CARD UTAMA --}}
    <div class="card detail-card p-5">

        <div class="row g-5">
            
            {{-- KOLOM KIRI: GAMBAR --}}
            <div class="col-md-5 text-center">
                <div class="image-container">
                    @if($alternative->image && \Illuminate\Support\Facades\Storage::disk('public')->exists('alternatives/'.$alternative->image))
                        <img src="{{ asset('storage/alternatives/'.$alternative->image) }}"
                             alt="Gambar {{ $alternative->name }}"
                             class="detail-image">
                    @else
                        <img src="https://via.placeholder.com/400x300/f8f9fa?text=Gambar+Tidak+Tersedia"
                             alt="Gambar tidak tersedia"
                             class="detail-image no-image">
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN: DETAIL TEKS --}}
            <div class="col-md-7 border-start ps-5">
                
                {{-- Nama dan Kode --}}
                <h2 class="fw-bolder mb-2 text-dark">{{ $alternative->name }}</h2>
                <span class="badge badge-code">{{ $alternative->code }}</span>

                <hr class="my-4">

                <div class="row">
                    {{-- Deskripsi --}}
                    <div class="col-md-12 mb-4">
                        <h5 class="fw-semibold text-primary-dark mb-2"></h5>
                        <p class="fs-6 line-height-18 detail-text-content">
                            {{ $alternative->description ?? 'Tidak ada deskripsi rinci yang tersedia untuk alternatif ini.' }}
                        </p>
                    </div>
                </div>
                
                {{-- Aksi --}}
                <div class="mt-5 d-flex gap-3">
                    <a href="{{ route('alternatives.index') }}" class="btn btn-secondary-custom px-4">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Daftar
                    </a>

                    <a href="{{ route('alternatives.edit', $alternative) }}" 
                       class="btn btn-primary-custom px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Alternatif
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection

<style>
.header-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 20px 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,.05);
    border: none;
}

.detail-card {
    background: #ffffff;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,.15);
    transition: transform 0.3s ease;
}

.detail-card:hover {
    transform: translateY(-3px);
}

/* WARNA TULISAN DIHITAMKAN */
.text-primary-dark {
    color: #000000 !important; 
}

.detail-text-content {
    color: #343a40 !important; 
}

/* ------------------------------------- */
/* IMAGE & MEDIA */
/* ------------------------------------- */
.image-container {
    padding: 10px;
    background: #f0f4f8; 
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
    display: inline-block;
    transition: all 0.4s ease;
}

.image-container:hover {
    box-shadow: 0 12px 30px rgba(0,0,0,.3);
    transform: scale(1.02);
}

.detail-image {
    width: 100%;
    max-height: 350px; 
    object-fit: cover;
    border-radius: 15px;
    display: block;
    transition: transform 0.4s ease;
}

.detail-image.no-image {
    max-height: 250px;
    object-fit: contain;
    padding: 20px;
}

/* ------------------------------------- */
/* BADGE & TEXT */
/* ------------------------------------- */
.badge-code {
    background: linear-gradient(135deg, #0d6efd, #4b8ffc);
    color: #fff;
    padding: 8px 15px;
    border-radius: 25px;
    font-size: .9rem;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(0, 110, 255, 0.3);
}

.line-height-18 {
    line-height: 1.8;
}

/* ------------------------------------- */
/* BUTTONS */
/* ------------------------------------- */
.btn-primary-custom {
    background: linear-gradient(45deg, #0d6efd, #4b8ffc);
    border: none;
    color: white !important;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4);
    transform: translateY(-2px);
    background: linear-gradient(45deg, #0056b3, #0d6efd);
    color: white !important;
}

.btn-secondary-custom {
    background: #6c757d;
    border: none;
    color: white;
    font-weight: 500;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-secondary-custom:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,.2);
}
.border-start {
    border-left: 1px solid #e0e0e0 !important;
}
</style>