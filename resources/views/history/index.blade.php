@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- BLOK ALERT DI SINI SUDAH DIHAPUS AGAR TIDAK DOUBLE DENGAN LAYOUTS.APP --}}

    <div class="card p-4 shadow-sm" style="border-radius: 18px; background: #fff;">
        <h3 class="fw-bold text-dark mb-4">
            <i class="bi bi-journal-text text-primary"></i> Riwayat Pemrosesan Kandidat
        </h3>
        
        {{-- ... sisa kode table ke bawah tetap sama ... --}}
        
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th width="8%">No</th>
                    <th class="text-start text-center">Nama Proses</th>
                    <th width="25%">Tanggal Pengambilan Keputusan</th>
                    <th width="20%">Aksi</th> {{-- Lebar dinaikkan sedikit agar muat 2 tombol --}}
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $key => $history)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="text-start fw-semibold text-secondary">{{ $history->process_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($history->process_date)->translatedFormat('d F Y, H:i') }} WIB</td>
                    <td>
                        {{-- Tombol Detail --}}
                        <a href="{{ route('history.show', $history->id) }}" class="btn btn-primary btn-sm rounded-3 me-1">
                            <i class="bi bi-eye"></i> Detail
                        </a>

                        {{-- Form & Tombol Hapus (Baru) --}}
                        <form action="{{ route('history.destroy', $history->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat sesi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-3">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-muted py-4">Belum ada data riwayat kalkulasi yang disimpan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection