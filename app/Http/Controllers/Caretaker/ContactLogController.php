<?php

namespace App\Http\Controllers\Caretaker;

use App\Http\Controllers\Controller;
use App\Models\ContactLog;
use App\Models\Dog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactLogController extends Controller
{
    public function index(Request $request): View
    {
        return view('caretaker.contact-log.index', [
            'logs' => ContactLog::with('dog')
                ->where('caretaker_id', $request->user()->id)
                ->latest('logged_at')
                ->paginate(16),
        ]);
    }

    public function create(Request $request): View
    {
        return view('caretaker.contact-log.create', [
            'dogs' => $this->assignedDogs($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dogIds = $this->assignedDogs($request)->pluck('id')->all();

        $data = $request->validate([
            'dog_id' => ['required', Rule::in($dogIds)],
            'contact_type' => ['required', Rule::in(['feeding', 'handling', 'bathing', 'medication_application', 'walking', 'cleaning_cage', 'other'])],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:720'],
            'ppe_used' => ['required', Rule::in(['gloves', 'mask', 'full_suit', 'none'])],
            'notes' => ['nullable', 'string'],
            'logged_at' => ['required', 'date'],
        ]);

        $data['caretaker_id'] = $request->user()->id;

        ContactLog::create($data);

        return redirect()->route('caretaker.contact-log.index')->with('success', 'Contact log saved for traceability.');
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
