<x-app-layout>
    <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
        <div class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold">Profile</h1>
            <p class="mt-1 text-sm text-slate-500">{{ Str::headline($user->role) }} account</p>
            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                @csrf @method('PATCH')
                <label class="block text-sm font-medium">Name
                    <input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-md border-slate-300">
                </label>
                <label class="block text-sm font-medium">Email
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-md border-slate-300">
                </label>
                <label class="block text-sm font-medium">Phone
                    <input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-md border-slate-300">
                </label>
                <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Save Profile</button>
            </form>
        </div>
        <div class="space-y-4">
            @if ($user->isCaretaker())
                <div class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-semibold">Assigned dogs</h2>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        @foreach ($user->assignedDogs as $dog)
                            <div class="rounded-md border border-slate-100 p-3"><p class="font-semibold">{{ $dog->name }}</p><p class="text-sm text-slate-500">Kennel {{ $dog->kennel }}</p></div>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-semibold">Recent contact</h2>
                    <div class="mt-3 divide-y divide-slate-100">
                        @foreach ($user->contactLogs->take(5) as $log)
                            <div class="py-2 text-sm">{{ $log->dog->name }} · {{ Str::headline($log->contact_type) }} · {{ $log->logged_at->format('M d') }}</div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-md border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">Use the navigation to manage your workspace or browse adoptable dogs.</div>
            @endif
        </div>
    </div>
</x-app-layout>