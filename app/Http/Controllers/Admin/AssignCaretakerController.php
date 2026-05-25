<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssignCaretakerController extends Controller
{
    public function __invoke(Request $request, Dog $dog): RedirectResponse
    {
        abort_unless($dog->shelter_id === $request->user()->shelter_id, 403);

        $data = $request->validate([
            'caretaker_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('shelter_id', $request->user()->shelter_id)
                    ->where('role', 'caretaker')
                    ->where('is_active', true)),
            ],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $dog, $data) {
            $dog->assignments()->where('is_active', true)->update([
                'is_active' => false,
                'ended_at' => now(),
            ]);

            $dog->assignments()->create([
                'caretaker_id' => $data['caretaker_id'],
                'assigned_by' => $request->user()->id,
                'assigned_at' => now(),
                'is_active' => true,
                'notes' => $data['notes'] ?? null,
            ]);
        });

        $caretaker = User::find($data['caretaker_id']);

        return back()->with('success', $dog->name.' assigned to '.$caretaker->name.'.');
    }
}
