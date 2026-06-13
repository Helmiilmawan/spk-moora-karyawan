@extends('layouts.app')

@section('content')
<div class="container py-2">

    {{-- HEADER UTAMA RATA TENGAH (TEMA PUTIH) --}}
    <div class="mb-4 animate-fade text-center">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-plus-circle text-primary"></i> Tambah Alternatif
        </h3>
        <p class="text-muted">Isi data alternatif kandidat untuk analisis MOORA.</p>
    </div>

    {{-- KARTU PUTIH CLEAN ELEGAN --}}
    <div class="card shadow-sm border-0 rounded-4 glass-card animate-up mx-auto" style="max-width: 900px; background: #ffffff;">
        <div class="card-body p-4">

            <form action="{{ route('alternatives.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-tag-fill text-primary"></i> Kode Alternatif
                        </label>
                        <input type="text" name="code" class="form-control form-control-lg rounded-3"
                               placeholder="Contoh: A1" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-card-text text-success"></i> Nama Alternatif
                        </label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-3"
                               placeholder="Masukkan nama alternatif" required>
                    </div>
                </div>
                 <div class="mb-4">
                    <h5 class="fw-bold text-dark text-center mb-1">
                        <i class="bi bi-list-check text-primary"></i> Nilai Kriteria
                    </h5>
                    <p class="text-muted text-center small mb-4">
                        Pilih nilai setiap kriteria untuk kandidat yang akan diproses menggunakan metode MOORA.
                    </p>

                    <div class="row">
                        @foreach($criteria as $c)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark small">
                                {{ $c->code }} - {{ $c->name }}
                                <span class="badge ms-1 {{ $c->type == 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                    {{ strtoupper($c->type) }}
                                </span>
                            </label>

                            <select name="criteria[{{ $c->id }}]" class="form-select rounded-3" required>
                                <option value="">-- Pilih Nilai --</option>
                                @foreach($c->subCriteria->sortByDesc('value') as $sub)
                                    <option value="{{ $sub->value }}">{{ $sub->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">
                        <i class="bi bi-image-fill text-warning"></i> Gambar Alternatif
                    </label>
                    <input type="file" name="image" 
                           class="form-control form-control-lg rounded-3 @error('image') is-invalid @enderror" 
                           accept="image/*" onchange="previewImage(event)">
                    @error('image')
                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                    @enderror
                    <div class="mt-2 text-center">
                        <img id="imagePreview" src="#" alt="Preview Gambar" style="display:none; max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #dee2e6;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">
                        <i class="bi bi-card-text text-info"></i> Deskripsi
                    </label>
                    <textarea name="description" rows="4" 
                              class="form-control rounded-3 @error('description') is-invalid @enderror" 
                              placeholder="Tuliskan deskripsi alternatif...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">

               

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('alternatives.index') }}" class="btn btn-light border px-4 rounded-3 shadow-sm text-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    /* Styling khusus Light Mode mengikuti halaman index */
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
    .form-control::placeholder {
        color: #94a3b8 !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.15rem rgba(59, 130, 246, 0.15) !important;
    }
    .animate-fade { animation: fadeIn 0.6s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-up { animation: slideUp 0.55s ease-out; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection