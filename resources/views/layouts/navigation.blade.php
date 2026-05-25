@php
    $user = auth()->user();
    $isMobile = $mobile ?? false;
    $navClass = fn ($active) => $isMobile
        ? ($active ? 'bg-white text-[#5b4638]' : 'bg-white/10 text-white hover:bg-white/20')
        : ($active ? 'bg-[#f3e6d8] text-[#5b4638]' : 'text-[#efe2d3] hover:bg-white/10 hover:text-white');
    $itemClass = $isMobile ? 'shrink-0 rounded-[8px] px-3 py-2 text-sm font-semibold' : 'flex items-center gap-3 rounded-[8px] px-4 py-3 text-sm font-semibold';
@endphp

@if (! $isMobile)
    <div class="flex h-full flex-col px-6 py-7">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="grid h-12 w-12 place-items-center rounded-full bg-[#f3e6d8] text-base font-black text-[#5b4638]">ST</span>
            <span>
                <span class="block text-lg font-black tracking-wide text-white">ShelterTrack</span>
                <span class="block text-xs font-medium text-[#cdb9a6]">{{ $user?->shelter?->name ?? Str::headline($user?->role ?? 'Public') }}</span>
            </span>
        </a>
        <div class="mt-10 space-y-2">
@endif

@auth
    <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('dashboard')) }}" href="{{ route('dashboard') }}">
        <span>Dashboard</span>
    </a>

    @if ($user->isAdmin())
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('admin.dogs.*')) }}" href="{{ route('admin.dogs.index') }}">My Pets</a>
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('admin.schedules.*')) }}" href="{{ route('admin.schedules.index') }}">Schedule</a>
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('admin.users.*')) }}" href="{{ route('admin.users.index') }}">Caretakers</a>
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('admin.contact-trace')) }}" href="{{ route('admin.contact-trace') }}">Contact Trace</a>
    @elseif ($user->isCaretaker())
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('caretaker.dogs.*')) }}" href="{{ route('caretaker.dogs.index') }}">My Pets</a>
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('caretaker.schedules.*')) }}" href="{{ route('caretaker.schedules.index') }}">Schedule</a>
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('caretaker.health-records.*')) }}" href="{{ route('caretaker.health-records.create') }}">Health</a>
        <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('caretaker.contact-log.*')) }}" href="{{ route('caretaker.contact-log.index') }}">Contact Log</a>
    @endif

    <a class="{{ $itemClass }} {{ $navClass(request()->routeIs('profile.*')) }}" href="{{ route('profile.edit') }}">Profile</a>
@endauth

@if (! $isMobile)
        </div>
        <div class="mt-auto rounded-[8px] bg-white/10 p-4 text-sm text-[#f4e7d8]">
            <p class="font-semibold text-white">{{ $user->name }}</p>
            <p class="mt-1 text-xs">{{ $user->email }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="w-full rounded-[8px] border border-white/20 px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">Log out</button>
            </form>
        </div>
    </div>
@endif
