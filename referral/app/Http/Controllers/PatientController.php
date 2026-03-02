<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function apiSearch(Request $request)
    {
        $q = $request->input('q', '');

        $patients = Patient::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('last_name', 'like', "%{$q}%")
                      ->orWhere('first_name', 'like', "%{$q}%")
                      ->orWhere('chart_number', 'like', "%{$q}%");
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(20)
            ->get();

        return response()->json($patients->map(fn($p) => [
            'id'                    => $p->id,
            'chart_number'          => $p->chart_number,
            'last_name'             => $p->last_name,
            'first_name'            => $p->first_name,
            'display_name'          => $p->display_name,
            'dob'                   => $p->dob->format('m/d/Y'),
            'dob_raw'               => $p->dob->format('Y-m-d'),
            'parent_name'           => $p->parent_name,
            'phone'                 => $p->phone,
            'best_time'             => $p->best_time,
            'insurance'             => $p->insurance,
            'special_considerations'=> $p->special_considerations,
        ]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'chart_number'           => ['nullable', 'string', 'max:50', 'unique:patients,chart_number'],
            'last_name'              => ['required', 'string', 'max:100'],
            'first_name'             => ['required', 'string', 'max:100'],
            'dob'                    => ['required', 'date'],
            'parent_name'            => ['nullable', 'string', 'max:150'],
            'phone'                  => ['required', 'string', 'max:20'],
            'best_time'              => ['nullable', 'string', 'max:100'],
            'insurance'              => ['nullable', 'string'],
            'special_considerations' => ['nullable', 'string'],
        ]);

        $patient = Patient::create($data);

        return response()->json([
            'id'                    => $patient->id,
            'chart_number'          => $patient->chart_number,
            'last_name'             => $patient->last_name,
            'first_name'            => $patient->first_name,
            'display_name'          => $patient->display_name,
            'dob'                   => $patient->dob->format('m/d/Y'),
            'dob_raw'               => $patient->dob->format('Y-m-d'),
            'parent_name'           => $patient->parent_name,
            'phone'                 => $patient->phone,
            'best_time'             => $patient->best_time,
            'insurance'             => $patient->insurance,
            'special_considerations'=> $patient->special_considerations,
        ], 201);
    }
}
