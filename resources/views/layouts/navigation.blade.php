@php
    $user = auth()->user();
    $navClass = fn ($active) => $active
        ? 'bg-slate-900 text-white'
        : 'text-slate-600 hover:bg-white hover:text-slate-950';
@endphp

<nav class="border-b border-slate-200 bg-stone-100/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-md bg-emerald-700 text-sm font-bold text-white">ST</span>
            <span>
                <span class="block text-sm font-bold tracking-wide text-slate-950">ShelterTrack</span>
                <span class="block text-xs text-slate-500">{{ $user?->shelter?->name ?? ucfirst($user?->role ?? 'Public') }}</span>
            </span>
        </a>

        <div class="hidden items-center gap-2 md:flex">
            @auth
                <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('dashboard')) }}" href="{{ route('dashboard') }}">Dashboard</a>

                @if ($user->isAdmin())
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('admin.dogs.*')) }}" href="{{ route('admin.dogs.index') }}">Dogs</a>
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('admin.schedules.*')) }}" href="{{ route('admin.schedules.index') }}">Schedule</a>
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('admin.users.*')) }}" href="{{ route('admin.users.index') }}">Users</a>
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('admin.contact-trace')) }}" href="{{ route('admin.contact-trace') }}">Contact Trace</a>
                @elseif ($user->isCaretaker())
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('caretaker.dogs.*')) }}" href="{{ route('caretaker.dogs.index') }}">My Dogs</a>
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('caretaker.schedules.*')) }}" href="{{ route('caretaker.schedules.index') }}">Schedule</a>
                    <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('caretaker.contact-log.*')) }}" href="{{ route('caretaker.contact-log.index') }}">Contact Log</a>
                @endif

                <a class="rounded-md px-3 py-2 text-sm font-semibold {{ $navClass(request()->routeIs('profile.*')) }}" href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-white">Log out</button>
                </form>
            @endauth
        </div>
    </div>
</nav>