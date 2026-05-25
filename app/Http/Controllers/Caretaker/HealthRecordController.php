<?php

namespace App\Http\Controllers\Caretaker;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Models\HealthRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HealthRecordController extends Controller
{
    public function create(Request $request): View
    {
        return view('caretaker.health-records.create', [
            'dogs' => $this->assignedDogs($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dogIds = $this->assignedDogs($request)->pluck('id')->all();

        $data = $request->validate([
            'dog_id' => ['required', Rule::in($dogIds)],
            'observation' => ['required', 'string'],
            'severity' => ['required', Rule::in(['normal', 'watch', 'concerning', 'urgent'])],
            'symptoms' => ['nullable', 'string'],
            'zoonosis_flag' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
            'recorded_at' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_url'] = $request->file('photo')->store('health-records', 'public');
        }

        $data['recorded_by'] = $request->user()->id;
        $data['zoonosis_flag'] = $request->boolean('zoonosis_flag');

        HealthRecord::create($data);

        return redirect()->route('caretaker.dogs.show', $data['dog_id'])->with('success', 'Health record saved.');
    }

    private function assignedDogs(Request $request)
    {
        return Dog::whereHas('assignments', fn ($query) => $query
            ->where('caretaker_id', $request->user()->id)
            ->where('is_active', true))
            ->active()
            ->orderBy('name')
            ->get();
    }
}
