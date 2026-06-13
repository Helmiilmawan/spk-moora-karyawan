@extends('layouts.app')

@section('content')
<div class="container py-2">

    {{-- HEADER UTAMA RATA TENGAH (TEMA PUTIH) --}}
    <div class="mb-4 animate-fade text-center">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-pencil-square text-primary"></i> Edit Kriteria
        </h3>
        <p class="text-muted">Perbarui data kriteria dan sub kriteria penilaian.</p>
    </div>

    {{-- KARTU PUTIH CLEAN ELEGAN --}}
    <div class="card shadow-sm border-0 rounded-4 glass-card animate-up mx-auto" style="max-width: 800px; background: #ffffff;">
        <div class="card-body p-4">

            <form action="{{ route('criteria.update', $criterion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-tag-fill text-primary"></i> Kode Kriteria
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3 bg-light text-muted" value="{{ $criterion->code }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-card-text text-success"></i> Nama Kriteria
                        </label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-3" value="{{ $criterion->name }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-diagram-3 text-warning"></i> Tipe
                        </label>
                        <select name="type" class="form-select form-select-lg rounded-3" required>
                            <option value="cost" {{ $criterion->type == 'cost' ? 'selected' : '' }}>Cost</option>
                            <option value="benefit" {{ $criterion->type == 'benefit' ? 'selected' : '' }}>Benefit</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-graph-up-arrow text-danger"></i> Bobot
                        </label>
                        <input type="number" name="weight" step="0.01" class="form-control form-control-lg rounded-3" value="{{ $criterion->weight }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">
                        <i class="bi bi-sort-numeric-down text-secondary"></i> Urutan Tampil
                    </label>
                    <input type="number" name="order" class="form-control form-control-lg rounded-3" value="{{ $criterion->order }}" required>
                </div>

                {{-- ================= SUB KRITERIA ================= --}}
                <hr class="my-4" style="border-color: #e2e8f0;">

                <h5 class="fw-bold text-dark mb-1 text-center">
                    <i class="bi bi-list-check text-info"></i> Sub Kriteria
                </h5>
                <p class="text-muted text-center small mb-4">Perbarui tingkatan nilai atau bobot detail sub kriteria.</p>

                <div id="sub-criteria-wrapper">
                    @foreach($criterion->subCriteria as $sub)
                        <div class="row g-2 mb-2 sub-item align-items-center">
                            <div class="col-md-3">
                                <input type="number" name="sub_value[]" class="form-control" value="{{ $sub->value }}" required>
                            </div>

                            <div class="col-md-7">
                                <input type="text" name="sub_label[]" class="form-control" value="{{ $sub->label }}" required>
                            </div>

                            <div class="col-md-2 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove" title="Hapus Sub Kriteria">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-start mt-2">
                    <button type="button" id="add-sub" class="btn btn-outline-primary btn-sm rounded-3">
                        <i class="bi bi-plus-circle"></i> Tambah Baris Sub Kriteria
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('criteria.index') }}" class="btn btn-light border px-4 rounded-3 shadow-sm text-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i> Perbarui Kriteria
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    /* Styling khusus Light Mode mengikuti halaman index alternatif */
    .glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    .form-control, .form-select {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #334155 !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.15rem rgba(59, 130, 246, 0.15) !important;
    }
    .animate-fade { animation: fadeIn .7s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-up { animation: slideUp .6s ease-out; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('add-sub');
    const wrapper = document.getElementById('sub-criteria-wrapper');

    if (!addBtn || !wrapper) return;

    addBtn.addEventListener('click', function () {
        wrapper.insertAdjacentHTML('beforeend', `
            <div class="row g-2 mb-2 sub-item align-items-center">
                <div class="col-md-3">
                    <input type="number" name="sub_value[]" class="form-control" min="1" max="5" placeholder="Skor (1–5)" required>
                </div>
                <div class="col-md-7">
                    <input type="text" name="sub_label[]" class="form-control" placeholder="Deskripsi Sub Kriteria" required>
                </div>
                <div class="col-md-2 text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove" title="Hapus Sub Kriteria">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `);
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-remove');
        if (!btn) return;

        const items = document.querySelectorAll('.sub-item');
        if (items.length <= 1) {
            alert('Minimal harus ada 1 sub kriteria.');
            return;
        }
        btn.closest('.sub-item').remove();
    });
});
</script>
@endpush
@endsection