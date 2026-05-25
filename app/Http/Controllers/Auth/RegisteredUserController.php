<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'selectedRole' => request('role', 'adopter'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'adopter');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['nullable', Rule::in(['admin', 'adopter'])],
            'shelter_name' => [Rule::requiredIf($role === 'admin'), 'nullable', 'string', 'max:255'],
            'shelter_city' => ['nullable', 'string', 'max:255'],
            'shelter_address' => ['nullable', 'string'],
            'shelter_phone' => ['nullable', 'string', 'max:50'],
            'shelter_description' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request, $role) {
            $shelter = null;

            if ($role === 'admin') {
                $shelter = Shelter::create([
                    'name' => $request->input('shelter_name'),
                    'address' => $request->input('shelter_address') ?: 'Address not set',
                    'city' => $request->input('shelter_city') ?: 'City not set',
                    'phone' => $request->input('shelter_phone') ?: $request->input('phone'),
                    'email' => $request->input('email'),
                    'description' => $request->input('shelter_description'),
                    'contact_for_adoption' => $request->input('shelter_phone'),
                ]);
            }

            $user = User::create([
                'shelter_id' => $shelter?->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->input('phone'),
                'role' => $role,
                'is_active' => true,
                'password' => Hash::make($request->password),
            ]);

            Role::findOrCreate($role);
            $user->assignRole($role);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
