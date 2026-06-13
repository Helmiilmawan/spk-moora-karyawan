<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Rating;
use Illuminate\Support\Facades\Storage;

class AlternativeController extends Controller
{
    public function index()
    {
        $alternatives = Alternative::withCount('ratings')
            ->orderBy('code')
            ->get();

        return view('alternatives.index', compact('alternatives'));
    }

    public function create()
    {
        $criteria = Criterion::with('subCriteria')
            ->orderBy('order')
            ->get();

        return view('alternatives.create', compact('criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'  => 'required|unique:alternatives,code',
            'name'  => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' .
                $request->image->getClientOriginalName();

            $request->image->storeAs(
                'public/alternatives',
                $imageName
            );
        }

        $alternative = Alternative::create([
            'code'        => $request->code,
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $imageName
        ]);

        if ($request->has('criteria')) {

            foreach ($request->criteria as $criterionId => $value) {

                if ($value == '' || $value === null) {
                    continue;
                }

                Rating::create([
                    'alternative_id' => $alternative->id,
                    'criterion_id'   => $criterionId,
                    'value'          => $value
                ]);
            }
        }

        return redirect()
            ->route('alternatives.index')
            ->with('success', 'Alternatif berhasil ditambahkan.');
    }

    public function show(Alternative $alternative)
    {
        $alternative->load('ratings.criterion');

        return view(
            'alternatives.show',
            compact('alternative')
        );
    }

    public function edit(Alternative $alternative)
    {
        $criteria = Criterion::with('subCriteria')
            ->orderBy('order')
            ->get();

        $alternative->load('ratings');

        return view(
            'alternatives.edit',
            compact(
                'alternative',
                'criteria'
            )
        );
    }

    public function update(
        Request $request,
        Alternative $alternative
    ) {
        $request->validate([
            'name'  => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $imageName = $alternative->image;

        if ($request->hasFile('image')) {

            if (
                $imageName &&
                Storage::disk('public')
                    ->exists('alternatives/' . $imageName)
            ) {
                Storage::disk('public')
                    ->delete('alternatives/' . $imageName);
            }

            $imageName = time() . '_' .
                $request->image->getClientOriginalName();

            $request->image->storeAs(
                'public/alternatives',
                $imageName
            );
        }

        $alternative->update([
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $imageName
        ]);

        if ($request->has('criteria')) {

            foreach ($request->criteria as $criterionId => $value) {

                Rating::updateOrCreate(
                    [
                        'alternative_id' => $alternative->id,
                        'criterion_id'   => $criterionId
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }

        return redirect()
            ->route('alternatives.index')
            ->with('success', 'Alternatif berhasil diperbarui.');
    }

    public function destroy(Alternative $alternative)
    {
        Rating::where(
            'alternative_id',
            $alternative->id
        )->delete();

        if (
            $alternative->image &&
            Storage::disk('public')
                ->exists(
                    'alternatives/' . $alternative->image
                )
        ) {
            Storage::disk('public')
                ->delete(
                    'alternatives/' . $alternative->image
                );
        }

        $alternative->delete();

        return redirect()
            ->route('alternatives.index')
            ->with('success', 'Alternatif berhasil dihapus.');
    }
}