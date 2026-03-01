<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index(Request $request)
    {
        $query = Specialist::orderBy('specialty')->orderBy('practice_name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('practice_name', 'like', "%{$s}%")
                  ->orWhere('specialty', 'like', "%{$s}%");
            });
        }

        $specialists = $query->get();
        return view('specialists.index', compact('specialists'));
    }

    public function create()
    {
        return view('specialists.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateSpecialist($request);
        Specialist::create($data);
        return redirect()->route('specialists.index')->with('success', 'Specialist added.');
    }

    public function edit(Specialist $specialist)
    {
        return view('specialists.edit', compact('specialist'));
    }

    public function update(Request $request, Specialist $specialist)
    {
        $specialist->update($this->validateSpecialist($request));
        return redirect()->route('specialists.index')->with('success', 'Specialist updated.');
    }

    public function destroy(Specialist $specialist)
    {
        $specialist->delete();
        return back()->with('success', 'Specialist deleted.');
    }

    public function apiSearch(Request $request)
    {
        $specialists = Specialist::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $s = $request->q;
                $query->where('practice_name', 'like', "%{$s}%")
                      ->orWhere('specialty', 'like', "%{$s}%");
            })
            ->orderBy('specialty')->orderBy('practice_name')
            ->get(['id', 'practice_name', 'specialty', 'phone', 'fax', 'address']);

        return response()->json($specialists);
    }

    private function validateSpecialist(Request $request): array
    {
        return $request->validate([
            'practice_name' => ['required', 'string', 'max:200'],
            'specialty'     => ['required', 'string', 'max:150'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'fax'           => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'notes'         => ['nullable', 'string'],
        ]);
    }
}
