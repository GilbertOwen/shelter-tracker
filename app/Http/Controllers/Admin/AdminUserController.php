<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.users.index', [
            'users' => User::where('shelter_id', $request->user()->shelter_id)
                ->whereIn('role', ['admin', 'caretaker'])
                ->withCount([
                    'assignments as active_assignments_count' => fn ($query) => $query->where('is_active', true),
                    'schedules as pending_schedules_count' => fn ($query) => $query->whereIn('status', ['pending', 'overdue']),
                ])
                ->orderBy('role')
                ->orderBy('name')
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['caretaker' => new User(['role' => 'caretaker', 'is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $caretaker = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'shelter_id' => $request->user()->shelter_id,
            'role' => 'caretaker',
            'is_active' => true,
        ]);
        $caretaker->assignRole('caretaker');

        return redirect()->route('admin.users.index')->with('success', $caretaker->name.' invited as caretaker.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->ensureShelterUser($request, $user);

        return view('admin.users.form', ['caretaker' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureShelterUser($request, $user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', $user->name.' updated.');
    }

    private function ensureShelterUser(Request $request, User $user): void
    {
        abort_unless($user->shelter_id === $request->user()->shelter_id && $user->role !== 'adopter', 403);
    }
}
