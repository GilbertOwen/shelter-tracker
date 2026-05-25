<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLog;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactTraceController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->subDays(14)->startOfDay();
        $to = $request->date('to') ?? now()->endOfDay();

        $logs = ContactLog::with(['dog', 'caretaker'])
            ->whereHas('dog', fn ($query) => $query->where('shelter_id', $request->user()->shelter_id))
            ->whereBetween('logged_at', [$from->startOfDay(), $to->endOfDay()])
            ->when($request->filled('dog_id'), fn ($query) => $query->where('dog_id', $request->integer('dog_id')))
            ->when($request->filled('caretaker_id'), fn ($query) => $query->where('caretaker_id', $request->integer('caretaker_id')))
            ->latest('logged_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.contact-trace.index', [
            'logs' => $logs,
            'dogs' => Dog::where('shelter_id', $request->user()->shelter_id)->active()->orderBy('name')->get(),
            'caretakers' => User::where('shelter_id', $request->user()->shelter_id)->where('role', 'caretaker')->orderBy('name')->get(),
            'from' => $from,
            'to' => $to,
        ]);
    }
}
