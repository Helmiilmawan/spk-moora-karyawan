@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="card p-4 shadow-sm" style="border-radius: 18px; background: #fff;">
        <h3 class="fw-bold text-dark mb-1">Detail Hasil Perhitungan</h3>
        <p class="text-muted mb-4">Nama Sesi: <strong class="text-primary">{{ $history->process_name }}</strong></p>

        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="12%">Ranking</th>
                    <th width="20%">Kode</th>
                    <th>Nama Alternatif</th>
                    <th width="25%">Nilai Yi (Optimasi)</th>
                </tr>
            </thead>
            <tbody>
                {{-- Pastikan pembuka @foreach di bawah ini tertulis dengan benar --}}
                @foreach($history->results_data as $result)
                    {{-- Cek kondisi jika ranking 1 diberi highlight hijau --}}
                    @if($result['rank'] == 1)
                        <tr class="table-success fw-bold" style="border-left: 5px solid #198754;">
                    @else
                        <tr>
                    @endif
                    
                        <td>
                            <span class="badge {{ $result['rank'] == 1 ? 'bg-success' : 'bg-secondary' }}">
                                {{ $result['rank'] }}
                            </span>
                        </td>
                        <td>{{ $result['code'] }}</td>
                        <td class="text-start ps-4">{{ $result['name'] }}</td>
                        <td class="fw-semibold text-primary">{{ number_format($result['yi'], 4) }}</td>
                    </tr>
                @endforeach {{-- Penutup perulangan --}}
            </tbody>
        </table>
    </div>
</div>
@endsection