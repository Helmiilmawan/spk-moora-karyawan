@extends('layouts.app')

@section('content')

<div class="card header-card mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-sliders text-primary me-1"></i> Data Kriteria
            </h4>
            <small class="text-muted">
                Kriteria (faktor-faktor penilaian) yang akan menjadi dasar pengambilan keputusan.
            </small>
        </div>
        <a href="{{ route('criteria.create') }}" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </a>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        {{-- PERBAIKAN: Menambahkan class text-center pada tag table agar isinya seragam di tengah --}}
        <table class="table align-middle mb-0 custom-table text-center">
            <thead>
                <tr>
                    <th style="width: 120px;">Kode</th>
                    <th>Nama Kriteria</th>
                    <th style="width: 140px;">Tipe</th>
                    <th style="width: 120px;">Bobot</th>
                    <th style="width: 180px; border-right: none;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($criteria as $c)
                <tr class="table-row">
                    {{-- Kode --}}
                    <td>
                        <span class="badge badge-code">{{ $c->code }}</span>
                    </td>

                    {{-- Nama Kriteria --}}
                    <td>
                        <div class="fw-semibold text-dark">{{ $c->name }}</div>
                    </td>

                    {{-- Tipe (Benefit/Cost) --}}
                    <td>
                        @if(strtolower($c->type) == 'cost')
                            <span class="badge badge-type badge-cost">
                                <i class="bi bi-arrow-down-right me-1"></i> {{ strtoupper($c->type) }}
                            </span>
                        @else
                            <span class="badge badge-type badge-benefit">
                                <i class="bi bi-arrow-up-right me-1"></i> {{ strtoupper($c->type) }}
                            </span>
                        @endif
                    </td>
                    
                    {{-- Bobot --}}
                    <td class="fw-semibold">
                        {{ number_format($c->weight, 2, '.', '') }}
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            
                            {{-- Tombol Edit --}}
                            <a href="{{ route('criteria.edit', $c->id) }}" 
                               class="btn btn-action btn-edit" title="Edit Kriteria">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            {{-- Form Hapus --}}
                            <form action="{{ route('criteria.destroy', $c->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria {{ $c->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" title="Hapus Kriteria">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state-cell">
                        <i class="bi bi-exclamation-circle me-2"></i> Data kriteria belum tersedia. Silakan tambahkan data baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

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
    border-right: none !important; 
}

/* TABLE BODY & ROW HOVER */
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

/* BADGE TYPE (Benefit/Cost) */
.badge-type {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: .85rem;
    font-weight: 600;
    min-width: 90px;
    display: inline-block;
}

.badge-benefit {
    background: linear-gradient(135deg, #198754, #4CAF50) !important;
}

.badge-cost {
    background: linear-gradient(135deg, #dc3545, #FF5733) !important;
}

/* ACTION BUTTONS */
.btn-action {
    width: 36px;
    height: 36px;
    padding: 0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0,0,0,.15);
    transition: all .25s ease;
    border: none !important;
    color: #fff;
}

.btn-edit { background: #ffc107; color: #333 !important; } 
.btn-delete { background: #dc3545; }

.btn-action:hover {
    transform: translateY(-2px);
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