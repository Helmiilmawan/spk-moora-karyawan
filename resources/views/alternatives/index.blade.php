@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="card header-card mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-list-check text-primary me-1"></i> Data Alternatif
            </h4>
            <small class="text-muted">
                Daftar kandidat yang akan diproses menggunakan metode MOORA
            </small>
        </div>
        <a href="{{ route('alternatives.create') }}" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm"> 
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </a>
    </div>
</div>

{{-- TABEL --}}
<div class="card table-card">
    <div class="table-responsive">
        {{-- PERBAIKAN: Menambahkan class text-center agar seluruh isi data, termasuk nama kandidat, sejajar di tengah --}}
        <table class="table align-middle mb-0 custom-table text-center">
            <thead>
                <tr>
                    <th style="width:120px;">Kode</th>
                    <th>Nama Kandidat</th>
                    <th style="width:150px;">Nilai Kriteria</th>
                    <th style="width:180px;">Info / Gambar</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($alternatives as $a)
                <tr class="table-row">

                    {{-- Kode --}}
                    <td>
                        <span class="badge badge-code">
                            {{ $a->code }}
                        </span>
                    </td>

                    {{-- Nama Kandidat (Otomatis rata tengah mengikuti induk <table>) --}}
                    <td>
                        <div class="fw-semibold text-dark">
                            {{ $a->name }}
                        </div>
                    </td>

                    {{-- Jumlah Nilai Kriteria --}}
                    <td>
                        @if($a->ratings_count > 0)
                            <span class="badge bg-success px-3 py-2">
                                {{ $a->ratings_count }} Nilai
                            </span>
                        @else
                            <span class="badge bg-danger px-3 py-2">
                                Belum Dinilai
                            </span>
                        @endif
                    </td>

                    {{-- Gambar --}}
                    <td>
                        @if($a->image && Storage::disk('public')->exists('alternatives/'.$a->image))
                            <div class="image-wrapper">
                                <img src="{{ asset('storage/alternatives/'.$a->image) }}" alt="{{ $a->name }}">
                            </div>
                        @else
                            <span class="text-muted small no-image-placeholder">
                                Tidak Ada Gambar
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">

                            <a href="{{ route('alternatives.show', $a) }}"
                               class="btn btn-action btn-detail"
                               title="Lihat Detail">
                                <i class="bi bi-search"></i>
                            </a>

                            <a href="{{ route('alternatives.edit', $a) }}"
                               class="btn btn-action btn-edit"
                               title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('alternatives.destroy', $a) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-action btn-delete"
                                        title="Hapus Data">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state-cell">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        Data alternatif belum tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- STYLE (Dipindahkan ke dalam endsection agar layouting template Blade berjalan sempurna) --}}
<style>
/* CARD & SHADOW */
.header-card, .table-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 20px 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,.08);
    border: none;
}

/* HEADER BUTTON */
.btn-sm { 
    font-size: .9rem !important;
    padding: .35rem 1rem !important;
    font-weight: 600;
}

.table-responsive {
    overflow-x: auto; 
}

thead {
    background: linear-gradient(135deg, #0d6efd, #4b8ffc); 
    color: white;
    border-radius: 15px 15px 0 0;
    border-bottom: 5px solid #0056b3;  
}

thead th {
    font-weight: 700;
    border: none;
    padding: 1rem 1rem;
    vertical-align: middle;
    border-right: 2px solid rgba(255, 255, 255, 0.3); 
}

thead th:last-child {
    border-right: none; 
}

.custom-table > :not(caption) > * > * {
    padding: .85rem 1rem;
}

.table-row {
    transition: all .3s cubic-bezier(0.25, 0.46, 0.46, 0.94);
    border-bottom: 1px solid #e0e0e0; 
}

.table-row:last-child {
    border-bottom: none; 
}

.table-row:hover {
    background: #f7faff;
    transform: scale(1.005);
    box-shadow: 0 4px 12px rgba(0,0,0,.04);
}

/* BADGE CODE */
.badge-code {
    background: linear-gradient(135deg, #6c757d, #949ca4);
    color: #fff;
    padding: 7px 16px;
    border-radius: 25px;
    font-size: .8rem;
    font-weight: 600;
    min-width: 80px;
    display: inline-block;
}

/* IMAGE BOUNDS */
.image-wrapper {
    width: 90px;
    height: 65px;
    border-radius: 10px;
    overflow: hidden;
    margin: auto;
    border: 3px solid #f0f0f0;
    box-shadow: 0 4px 8px rgba(0,0,0,.08);
}

.image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}

.image-wrapper:hover img {
    transform: scale(1.1);
}

.no-image-placeholder {
    display: block;
    padding: 5px 0;
    font-style: italic;
    color: #b0b0b0 !important;
}

/* ACTION BUTTONS */
.btn-action {
    width: 40px;
    height: 40px;
    padding: 0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
    transition: all .25s ease;
    border: none !important;
    color: #fff;
}

.btn-detail { background: #17a2b8; }
.btn-edit { background: #ffc107; color: #333 !important; }
.btn-delete { background: #dc3545; }

.btn-action:hover {
    transform: translateY(-3px);
    opacity: 1;
    box-shadow: 0 6px 15px rgba(0,0,0,.25);
}

.btn-edit:hover {
    color: #333 !important;
}

/* EMPTY STATE CELL */
.empty-state-cell {
    background: #f8f9fa;
    font-style: italic;
    font-size: 1.05rem;
    color: #888;
    padding: 40px 20px !important;
}
</style>

@endsection