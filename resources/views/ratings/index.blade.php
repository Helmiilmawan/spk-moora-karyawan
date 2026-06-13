@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3">
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-table text-primary me-2"></i>
            Matriks Keputusan
        </h4>
        <small class="text-muted">
            Data nilai alternatif berdasarkan kriteria yang telah diinput
        </small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <div class="table-responsive">
            {{-- PERBAIKAN: Menambahkan class text-center pada tag table di bawah ini --}}
            <table class="table table-bordered table-hover align-middle text-center">

                <thead class="table-primary">
                    <tr>
                        <th>Kode</th>
                        <th>Alternatif</th>

                        @foreach ($criteria as $c)
                            <th>
                                {{ $c->code }}
                                <br>
                                <small>{{ $c->name }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @forelse ($matrix as $row)
                        <tr>
                            <td class="fw-bold">
                                {{ $row['alternative']->code }}
                            </td>

                            {{-- Sekarang nama alternatif juga otomatis rata tengah --}}
                            <td>
                                {{ $row['alternative']->name }}
                            </td>

                            @foreach ($criteria as $c)
                                <td>
                                    {{ $row['values'][$c->id] }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($criteria) + 2 }}">
                                Belum ada data nilai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection