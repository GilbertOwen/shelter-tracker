<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicDogController extends Controller
{
    public function index(Request $request): View
    {
        $dogs = Dog::availableForAdoption()
            ->with('shelter')
            ->when($request->filled('size'), fn ($query) => $query->where('size', $request->input('size')))
            ->when($request->filled('sex'), fn ($query) => $query->where('sex', $request->input('sex')))
            ->when($request->boolean('good_with_kids'), fn ($query) => $query->where('good_with_kids', true))
            ->when($request->boolean('good_with_pets'), fn ($query) => $query->where('good_with_pets', true))
            ->when($request->filled('breed'), fn ($query) => $query->where('breed', 'like', '%'.$request->input('breed').'%'))
            ->when($request->filled('age'), function ($query) use ($request) {
                return match ($request->string('age')->toString()) {
                    'puppy' => $query->where('birth_date', '>=', now()->subMonths(12)->toDateString()),
                    'adult' => $query->whereBetween('birth_date', [now()->subYears(7)->toDateString(), now()->subMonths(12)->toDateString()]),
                    'senior' => $query->where('birth_date', '<=', now()->subYears(7)->toDateString()),
                    default => $query,
                };
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('public.adopt-index', ['dogs' => $dogs]);
    }

    public function show(Dog $dog): View
    {
        abort_unless($dog->is_active && $dog->adoption_status === 'available', 404);

        return view('public.adopt-show', [
            'dog' => $dog->load('shelter'),
        ]);
    }
}
