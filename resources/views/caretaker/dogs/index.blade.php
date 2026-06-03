<x-app-layout>
    <x-slot name="title">My Pets</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Assigned care</p>
            <h2 class="text-3xl font-black">My Pets</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('caretaker.health-records.create') }}" class="shelter-button">Log Health</a>
            <a href="{{ route('caretaker.contact-log.create') }}" class="shelter-button-secondary">Log Contact</a>
        </div>
    </div>

    <div class="mt-16 grid gap-x-5 gap-y-12 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($dogs as $dog)
            <a href="{{ route('caretaker.dogs.show', $dog) }}" class="shelter-card relative block pt-14">
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
                        <span class="rounded-full bg-[#fbf6ef] px-3 py-1 text-xs font-bold text-[#6f5543]">Kennel {{ $dog->kennel ?? '-' }}</span>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs font-semibold text-[#7f6a58]">
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">{{ $dog->age_label }}</span>
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">Size {{ $dog->size ?? '-' }}</span>
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">{{ $dog->schedules->count() }} tasks</span>
                    </div>
                    <p class="mt-4 text-sm text-[#7f6a58]">{{ Str::limit($dog->story, 120) }}</p>
                </div>
            </a>
        @empty
            <div class="shelter-card p-8 text-sm text-[#7f6a58] sm:col-span-2 xl:col-span-3">No active dog assignment yet.</div>
        @endforelse
    </div>
</x-app-layout>
