<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    /* =======================
       INDEX (TAMPIL + INPUT NILAI)
    ======================== */
   public function index()
{
    $alternatives = Alternative::orderBy('code')->get();

    $criteria = Criterion::orderBy('order')->get();

    $matrix = [];

    foreach ($alternatives as $alt) {

        $row = [
            'alternative' => $alt,
            'values' => []
        ];

        foreach ($criteria as $c) {

            $rating = Rating::where('alternative_id', $alt->id)
                ->where('criterion_id', $c->id)
                ->first();

            $row['values'][$c->id] = $rating
                ? $rating->value
                : '-';
        }

        $matrix[] = $row;
    }

    return view(
        'ratings.index',
        compact('criteria', 'matrix')
    );
}
    /* =======================
       STORE (SIMPAN NILAI)
    ======================== */
    public function store(Request $request)
    {
        // HARUS SAMA DENGAN NAME DI BLADE
        $values = $request->input('values', []);

        DB::transaction(function () use ($values) {
            foreach ($values as $altId => $criteriaValues) {
                foreach ($criteriaValues as $criterionId => $value) {

                    if ($value === null || $value === '') {
                        continue;
                    }

                    Rating::updateOrCreate(
                        [
                            'alternative_id' => $altId,
                            'criterion_id'   => $criterionId,
                        ],
                        [
                            'value' => $value
                        ]
                    );
                }
            }
        });

        return redirect()
            ->route('ratings.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
