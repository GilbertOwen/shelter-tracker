<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\HealthRecord;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $dogQuery = Dog::where('shelter_id', $user->shelter_id)->active();

            return view('dashboard', [
                'mode' => 'admin',
                'totalDogs' => (clone $dogQuery)->count(),
                'availableDogs' => (clone $dogQuery)->where('adoption_status', 'available')->count(),
                'newIntake' => (clone $dogQuery)->where('intake_date', '>=', now()->subDays(14))->count(),
                'urgentRecords' => HealthRecord::with(['dog', 'recordedBy'])
                    ->whereHas('dog', fn ($query) => $query->where('shelter_id', $user->shelter_id)->active())
                    ->where('severity', 'urgent')
                    ->latest('recorded_at')
                    ->take(5)
                    ->get(),
                'todaySchedules' => Schedule::with(['dog', 'assignee'])
                    ->whereHas('dog', fn ($query) => $query->where('shelter_id', $user->shelter_id))
                    ->whereDate('start_time', today())
                    ->orderBy('start_time')
                    ->take(8)
                    ->get(),
                'workloads' => User::query()
                    ->where('shelter_id', $user->shelter_id)
                    ->where('role', 'caretaker')
                    ->withCount([
                        'assignments as active_assignments_count' => fn ($query) => $query->where('is_active', true),
                        'schedules as pending_schedules_count' => fn ($query) => $query->whereIn('status', ['pending', 'overdue']),
                    ])
                    ->orderBy('name')
                    ->get(),
            ]);
        }

        if ($user->isCaretaker()) {
            $assignedDogs = $user->assignedDogs()->with(['healthRecords' => fn ($query) => $query->take(1)])->active()->get();

            return view('dashboard', [
                'mode' => 'caretaker',
                'assignedDogs' => $assignedDogs,
                'todaySchedules' => Schedule::with('dog')
                    ->where('assigned_to', $user->id)
                    ->whereDate('start_time', today())
                    ->orderBy('start_time')
                    ->get(),
                'urgentRecords' => HealthRecord::with(['dog', 'recordedBy'])
                    ->whereHas('dog', fn ($query) => $query->where('shelter_id', $user->shelter_id)->active())
                    ->where('severity', 'urgent')
                    ->latest('recorded_at')
                    ->take(5)
                    ->get(),
                'recentActivityCount' => $user->activityLogs()->where('performed_at', '>=', now()->subDays(7))->count(),
                'contactCount' => $user->contactLogs()->where('logged_at', '>=', now()->subDays(7))->count(),
            ]);
        }

        return view('dashboard', [
            'mode' => 'adopter',
            'availableDogs' => Dog::availableForAdoption()->with('shelter')->latest()->take(6)->get(),
        ]);
    }
}
