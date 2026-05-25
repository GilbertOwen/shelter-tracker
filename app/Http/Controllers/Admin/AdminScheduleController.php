<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $schedules = Schedule::with(['dog', 'assignee'])
            ->whereHas('dog', fn ($query) => $query->where('shelter_id', $request->user()->shelter_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('start_time', $request->date('date')))
            ->orderBy('start_time')
            ->paginate(14)
            ->withQueryString();

        return view('admin.schedules.index', [
            'schedules' => $schedules,
            'todayCount' => Schedule::whereHas('dog', fn ($query) => $query->where('shelter_id', $request->user()->shelter_id))
                ->whereDate('start_time', today())
                ->count(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.schedules.form', [
            'schedule' => new Schedule(['start_time' => now()->addHour(), 'duration_minutes' => 30, 'priority' => 'medium', 'status' => 'pending']),
            'dogs' => $this->dogs($request),
            'caretakers' => $this->caretakers($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Schedule::create($this->validated($request));

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created.');
    }

    public function edit(Request $request, Schedule $schedule): View
    {
        $this->ensureShelterSchedule($request, $schedule);

        return view('admin.schedules.form', [
            'schedule' => $schedule,
            'dogs' => $this->dogs($request),
            'caretakers' => $this->caretakers($request),
        ]);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->ensureShelterSchedule($request, $schedule);

        $schedule->update($this->validated($request));

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated.');
    }

    public function destroy(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->ensureShelterSchedule($request, $schedule);
        $schedule->delete();

        return back()->with('success', 'Schedule removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'dog_id' => [
                'required',
                Rule::exists('dogs', 'id')->where(fn ($query) => $query
                    ->where('shelter_id', $request->user()->shelter_id)
                    ->where('is_active', true)),
            ],
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('shelter_id', $request->user()->shelter_id)
                    ->where('role', 'caretaker')
                    ->where('is_active', true)),
            ],
            'type' => ['required', Rule::in(['feeding', 'bathing', 'medication', 'vaccination', 'vet_visit', 'exercise', 'grooming', 'behavior_training'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_time' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:720'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['pending', 'completed', 'overdue'])],
        ]);
    }

    private function dogs(Request $request)
    {
        return Dog::where('shelter_id', $request->user()->shelter_id)->active()->orderBy('name')->get();
    }

    private function caretakers(Request $request)
    {
        return User::where('shelter_id', $request->user()->shelter_id)
            ->where('role', 'caretaker')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function ensureShelterSchedule(Request $request, Schedule $schedule): void
    {
        abort_unless($schedule->dog()->where('shelter_id', $request->user()->shelter_id)->exists(), 403);
    }
}
