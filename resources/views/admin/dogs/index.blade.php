<x-app-layout>
    <x-slot name="title">My Pets</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Admin Dogs</p>
            <h2 class="text-3xl font-black">My Pets</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dogs.create') }}" class="grid h-11 w-11 place-items-center rounded-[8px] bg-[#6f5543] text-xl font-black text-white" title="Add dog">+</a>
            <a href="{{ route('admin.schedules.create') }}" class="grid h-11 w-11 place-items-center rounded-[8px] border border-[#d6c8bb] bg-white text-xl font-black text-[#6f5543]" title="Create schedule">S</a>
        </div>
    </div>

    <form method="GET" class="mt-5 grid gap-3 md:grid-cols-[1fr_180px_160px_auto]">
        <input name="q" value="{{ request('q') }}" placeholder="Search name, breed, kennel" class="shelter-input mt-0">
        <select name="status" class="shelter-input mt-0">
            <option value="">All status</option>
            @foreach (['available', 'pending', 'adopted', 'not_ready'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>
            @endforeach
        </select>
        <select name="size" class="shelter-input mt-0">
            <option value="">All size</option>
            @foreach (['S', 'M', 'L', 'XL'] as $size)
                <option value="{{ $size }}" @selected(request('size') === $size)>Size {{ $size }}</option>
            @endforeach
        </select>
        <button class="shelter-button">Filter</button>
    </form>

    <div class="mt-16 grid gap-x-5 gap-y-12 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($dogs as $dog)
            <article class="shelter-card relative pt-14">
                <div class="absolute left-5 top-0 h-24 w-24 -translate-y-1/2 overflow-hidden rounded-full border-4 border-[#f7f1e8] bg-[#eaded0]">
                    @if ($dog->image_url)
                        <img src="{{ $dog->image_url }}" alt="{{ $dog->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="grid h-full place-items-center text-3xl font-black text-[#7f6a58]">{{ Str::substr($dog->name, 0, 1) }}</div>
                    @endif
                </div>
                <div class="px-5 pb-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-2xl font-black">{{ $dog->name }}</h3>
                            <p class="text-sm text-[#7f6a58]">{{ $dog->breed ?? 'Mixed breed' }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $dog->is_active ? 'bg-[#eef5ed] text-[#5d7460]' : 'bg-[#eee3d7] text-[#7f6a58]' }}">
                            {{ $dog->is_active ? Str::headline($dog->adoption_status) : 'Archived' }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs font-semibold text-[#7f6a58]">
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">{{ $dog->age_label }}</span>
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">Size {{ $dog->size ?? '-' }}</span>
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">Kennel {{ $dog->kennel ?? '-' }}</span>
                    </div>

                    <p class="mt-4 text-sm text-[#7f6a58]">Primary caretaker: <span class="font-semibold text-[#3f3027]">{{ $dog->activeAssignment?->caretaker?->name ?? 'Unassigned' }}</span></p>

                    <form method="POST" action="{{ route('admin.dogs.assign', $dog) }}" class="mt-4 flex gap-2">
                        @csrf
                        <select name="caretaker_id" class="shelter-input mt-0 flex-1">
                            <option value="">Assign caretaker</option>
                            @foreach ($caretakers as $caretaker)
                                <option value="{{ $caretaker->id }}" @selected($dog->activeAssignment?->caretaker_id === $caretaker->id)>{{ $caretaker->name }}</option>
                            @endforeach
                        </select>
                        <button class="shelter-button px-3">Save</button>
                    </form>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('admin.dogs.edit', $dog) }}" class="shelter-button-secondary flex-1">Edit</a>
                        <form method="POST" action="{{ route('admin.dogs.destroy', $dog) }}" class="flex-1">
                            @csrf @method('DELETE')
                            <button class="shelter-button-secondary w-full" onclick="return confirm('Archive {{ $dog->name }}?')">Archive</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="shelter-card p-8 text-sm text-[#7f6a58] sm:col-span-2 xl:col-span-3">No dogs found. Add the first dog to start tracking care.</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $dogs->links() }}</div>
</x-app-layout>
