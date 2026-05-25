<?php

namespace App\Http\Controllers\Caretaker;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaretakerDogController extends Controller
{
    public function index(Request $request): View
    {
        return view('caretaker.dogs.index', [
            'dogs' => $request->user()
                ->assignedDogs()
                ->with(['healthRecords', 'schedules' => fn ($query) => $query->whereIn('status', ['pending', 'overdue'])->orderBy('start_time')])
                ->active()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Request $request, Dog $dog): View
    {
        abort_unless($request->user()->assignedDogs()->where('dogs.id', $dog->id)->exists(), 403);

        return view('caretaker.dogs.show', [
            'dog' => $dog->load(['healthRecords.recordedBy', 'contactLogs.caretaker', 'schedules' => fn ($query) => $query->orderBy('start_time')]),
        ]);
    }
}
