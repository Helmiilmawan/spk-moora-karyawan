<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criterion;
use Illuminate\Support\Facades\DB;

class CriterionController extends Controller
{
    /* =======================
       INDEX
    ======================== */
    public function index()
    {
        // Menampilkan kriteria diurutkan berdasarkan kode kriteria (misal: C1, C2)
        $criteria = Criterion::orderBy('code')->get();
        return view('criteria.index', compact('criteria'));
    }

    /* =======================
       CREATE
    ======================== */
    public function create()
    {
        return view('criteria.create');
    }

    /* =======================
       STORE
    ======================== */
    public function store(Request $request)
    {
        $request->validate([
            'code'   => 'required|unique:criteria,code',
            'name'   => 'required|string',
            'type'   => 'required|in:cost,benefit',
            'weight' => 'required|numeric',

            'sub_value'   => 'nullable|array',
            'sub_label'   => 'nullable|array',
            'sub_value.*' => 'numeric',
            'sub_label.*' => 'string',
        ]);

        DB::transaction(function () use ($request) {

            // Simpan kriteria tanpa kolom order
            $criterion = Criterion::create([
                'code'   => $request->code,
                'name'   => $request->name,
                'type'   => $request->type,
                'weight' => $request->weight,
            ]);

            // Simpan sub kriteria (PAKAI RELASI)
            if ($request->filled('sub_value')) {
                foreach ($request->sub_value as $i => $value) {
                    $criterion->subCriteria()->create([
                        'value' => $value,
                        'label' => $request->sub_label[$i] ?? '',
                    ]);
                }
            }
    });

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria dan sub kriteria berhasil ditambahkan.');
    }

    /* =======================
       EDIT
    ======================== */
    public function edit(Criterion $criterion)
    {
        $criterion->load('subCriteria');
        return view('criteria.edit', compact('criterion'));
    }

    /* =======================
       UPDATE
    ======================== */
    public function update(Request $request, Criterion $criterion)
    {
        $request->validate([
            'name'   => 'required|string',
            'type'   => 'required|in:cost,benefit',
            'weight' => 'required|numeric',

            'sub_value'   => 'nullable|array',
            'sub_label'   => 'nullable|array',
            'sub_value.*' => 'numeric',
            'sub_label.*' => 'string',
        ]);

        DB::transaction(function () use ($request, $criterion) {

            // Update kriteria tanpa kolom order
            $criterion->update([
                'name'   => $request->name,
                'type'   => $request->type,
                'weight' => $request->weight,
            ]);

            // Reset sub kriteria
            $criterion->subCriteria()->delete();

            // Simpan sub kriteria baru (PAKAI RELASI)
            if ($request->filled('sub_value')) {
                foreach ($request->sub_value as $i => $value) {
                    $criterion->subCriteria()->create([
                        'value' => $value,
                        'label' => $request->sub_label[$i] ?? '',
                    ]);
                }
            }
        });

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria dan sub kriteria berhasil diperbarui.');
    }

    /* =======================
       DESTROY
    ======================== */
    public function destroy(Criterion $criterion)
    {
        DB::transaction(function () use ($criterion) {
            $criterion->subCriteria()->delete();
            $criterion->delete();
        });

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria dan sub kriteria berhasil dihapus.');
    }
}