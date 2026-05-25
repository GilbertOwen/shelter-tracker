<x-app-layout>
    <x-slot name="title">{{ $dog->name }}</x-slot>

    <a href="{{ route('caretaker.dogs.index') }}" class="text-sm font-semibold text-[#6f5543]">Back to My Pets</a>

    <section class="mt-5 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="shelter-card overflow-hidden">
            <div class="h-72 bg-[#eaded0]">
                @if ($dog->image_url)
                    <img src="{{ $dog->image_url }}" alt="{{ $dog->name }}" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-3xl font-black">{{ $dog->name }}</h2>
                        <p class="text-sm text-[#7f6a58]">{{ $dog->breed }} - Kennel {{ $dog->kennel }} - {{ $dog->age_label }}</p>
                    </div>
                    <span class="rounded-full bg-[#fbf6ef] px-3 py-1 text-xs font-bold text-[#6f5543]">{{ Str::headline($dog->adoption_status) }}</span>
                </div>
                <p class="mt-5 text-sm leading-6 text-[#7f6a58]">{{ $dog->story }}</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('caretaker.health-records.create', ['dog_id' => $dog->id]) }}" class="shelter-button">Log Health</a>
                    <a href="{{ route('caretaker.contact-log.create', ['dog_id' => $dog->id]) }}" class="shelter-button-secondary">Log Contact</a>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="shelter-card p-5">
                <h3 class="text-lg font-black">Upcoming schedule</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($dog->schedules->whereIn('status', ['pending', 'overdue'])->take(5) as $schedule)
                        <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                            <p class="font-bold">{{ $schedule->title }}</p>
                            <p class="text-sm text-[#7f6a58]">{{ $schedule->start_time->format('M d, H:i') }} - {{ Str::headline($schedule->status) }}</p>
                        </div>
                    @empty
                        <p class="rounded-[8px] border border-dashed border-[#d6c8bb] p-5 text-sm text-[#7f6a58]">No pending schedule.</p>
                    @endforelse
                </div>
            </div>

            <div class="shelter-card p-5">
                <h3 class="text-lg font-black">Recent health</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($dog->healthRecords->take(5) as $record)
                        <div class="rounded-[8px] {{ $record->severity === 'urgent' ? 'bg-red-50 text-red-800' : 'bg-[#fbf6ef]' }} p-4">
                            <p class="font-bold">{{ Str::headline($record->severity) }} - {{ $record->recorded_at->format('M d') }}</p>
                            <p class="mt-1 text-sm">{{ $record->observation }}</p>
                        </div>
                    @empty
                        <p class="rounded-[8px] border border-dashed border-[#d6c8bb] p-5 text-sm text-[#7f6a58]">No health record yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
