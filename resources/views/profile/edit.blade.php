<x-app-layout>
    <x-slot name="title">Profile</x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <section class="shelter-card p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">{{ Str::headline($user->role) }} account</p>
            <h2 class="mt-2 text-3xl font-black">Profile</h2>

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')

                <label class="block text-sm font-semibold text-[#5b4638]">Name
                    <input name="name" value="{{ old('name', $user->name) }}" required class="shelter-input">
                </label>
                <label class="block text-sm font-semibold text-[#5b4638]">Email
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="shelter-input">
                </label>
                <label class="block text-sm font-semibold text-[#5b4638]">Phone
                    <input name="phone" value="{{ old('phone', $user->phone) }}" class="shelter-input">
                </label>

                <button class="shelter-button w-full">Save Profile</button>
            </form>
        </section>

        <section class="space-y-5">
            <div class="shelter-card p-6">
                <h3 class="text-xl font-black">Password</h3>
                @if (session('status') === 'password-updated')
                    <p class="mt-3 rounded-[8px] bg-[#eef5ed] px-3 py-2 text-sm font-semibold text-[#5d7460]">Password updated.</p>
                @endif
                <form method="POST" action="{{ route('password.update') }}" class="mt-4 grid gap-4 sm:grid-cols-3">
                    @csrf
                    @method('PUT')
                    <label class="text-sm font-semibold text-[#5b4638]">Current password
                        <input name="current_password" type="password" autocomplete="current-password" class="shelter-input">
                        @error('current_password', 'updatePassword')
                            <span class="mt-1 block text-xs font-semibold text-red-700">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="text-sm font-semibold text-[#5b4638]">New password
                        <input name="password" type="password" autocomplete="new-password" class="shelter-input">
                        @error('password', 'updatePassword')
                            <span class="mt-1 block text-xs font-semibold text-red-700">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="text-sm font-semibold text-[#5b4638]">Confirm password
                        <input name="password_confirmation" type="password" autocomplete="new-password" class="shelter-input">
                    </label>
                    <div class="sm:col-span-3">
                        <button class="shelter-button">Update Password</button>
                    </div>
                </form>
            </div>

            <div class="shelter-card p-6">
                <h3 class="text-xl font-black">Account summary</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                        <p class="text-sm text-[#7f6a58]">Role</p>
                        <p class="font-black">{{ Str::headline($user->role) }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                        <p class="text-sm text-[#7f6a58]">Status</p>
                        <p class="font-black">{{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                        <p class="text-sm text-[#7f6a58]">Shelter</p>
                        <p class="font-black">{{ $user->shelter?->name ?? 'Public' }}</p>
                    </div>
                </div>
            </div>

            @if ($user->isCaretaker())
                <div class="shelter-card p-6">
                    <h3 class="text-xl font-black">Assigned dogs</h3>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @forelse ($user->assignedDogs as $dog)
                            <a href="{{ route('caretaker.dogs.show', $dog) }}" class="rounded-[8px] bg-[#fbf6ef] p-4">
                                <p class="font-black">{{ $dog->name }}</p>
                                <p class="text-sm text-[#7f6a58]">Kennel {{ $dog->kennel }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-[#7f6a58]">No active assignment.</p>
                        @endforelse
                    </div>
                </div>

                <div class="shelter-card p-6">
                    <h3 class="text-xl font-black">Recent contact</h3>
                    <div class="mt-4 divide-y divide-[#eaded0]">
                        @forelse ($user->contactLogs()->with('dog')->latest('logged_at')->take(5)->get() as $log)
                            <div class="py-3 text-sm">
                                <span class="font-semibold">{{ $log->dog->name }}</span>
                                <span class="text-[#7f6a58]"> - {{ Str::headline($log->contact_type) }} - {{ $log->logged_at->format('M d') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-[#7f6a58]">No contact log yet.</p>
                        @endforelse
                    </div>
                </div>
            @elseif ($user->isAdopter())
                <div class="shelter-card p-6">
                    <h3 class="text-xl font-black">Adopter workspace</h3>
                    <p class="mt-2 text-sm leading-6 text-[#7f6a58]">Favorite/history is intentionally outside MVP. Browse public adoption listings and contact shelters directly.</p>
                    <a href="{{ route('adopt.index') }}" class="shelter-button mt-5">Browse Dogs</a>
                </div>
            @else
                <div class="shelter-card p-6">
                    <h3 class="text-xl font-black">Admin workspace</h3>
                    <p class="mt-2 text-sm leading-6 text-[#7f6a58]">Manage dogs, caretakers, schedules, and contact trace from the sidebar.</p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
