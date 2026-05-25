<?php

namespace App\Http\Controllers\Caretaker;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaretakerScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->date('date') ?? today();
        $monthStart = $selectedDate->copy()->startOfMonth();
        $monthEnd = $selectedDate->copy()->endOfMonth();

        Schedule::where('assigned_to', $request->user()->id)
            ->where('status', 'pending')
            ->where('start_time', '<', now())
            ->update(['status' => 'overdue']);

        return view('caretaker.schedules.index', [
            'schedules' => Schedule::with('dog')
                ->where('assigned_to', $request->user()->id)
                ->whereDate('start_time', $selectedDate)
                ->orderBy('start_time')
                ->get(),
            'selectedDate' => $selectedDate,
            'monthStart' => $monthStart,
            'taskDates' => Schedule::where('assigned_to', $request->user()->id)
                ->whereBetween('start_time', [$monthStart, $monthEnd])
                ->selectRaw('DATE(start_time) as task_date, COUNT(*) as total')
                ->groupBy('task_date')
                ->pluck('total', 'task_date'),
        ]);
    }

    public function complete(Request $request, Schedule $schedule): RedirectResponse
    {
        abort_unless($schedule->assigned_to === $request->user()->id, 403);

        $data = $request->validate([
            'notes' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_url'] = $request->file('photo')->store('activity-logs', 'public');
        }

        $schedule->update(['status' => 'completed']);

        ActivityLog::updateOrCreate(
            ['schedule_id' => $schedule->id],
            [
                'dog_id' => $schedule->dog_id,
                'user_id' => $request->user()->id,
                'performed_at' => now(),
                'notes' => $data['notes'] ?? 'Completed from schedule.',
                'photo_url' => $data['photo_url'] ?? null,
            ]
        );

        return back()->with('success', $schedule->title.' completed.');
    }
}
