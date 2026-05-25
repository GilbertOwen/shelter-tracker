<x-app-layout>
    <x-slot name="title">Schedule</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Daily care</p>
            <h2 class="text-3xl font-black">Schedule</h2>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="shelter-input mt-0">
            <button class="shelter-button">Go</button>
        </form>
    </div>

    <section class="mt-6 grid gap-5 xl:grid-cols-[1.3fr_0.7fr]">
        <div class="shelter-card p-5">
            <div class="space-y-3">
                @forelse ($schedules as $schedule)
                    <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-black">{{ $schedule->title }}</p>
                                <p class="text-sm text-[#7f6a58]">{{ $schedule->dog->name }} - {{ $schedule->start_time->format('H:i') }} - {{ Str::headline($schedule->status) }}</p>
                            </div>
                            @if ($schedule->status !== 'completed')
                                <form method="POST" action="{{ route('caretaker.schedules.complete', $schedule) }}" class="flex gap-2">
                                    @csrf @method('PATCH')
                                    <input name="notes" placeholder="Completion notes" class="shelter-input mt-0">
                                    <button class="shelter-button">Done</button>
                                </form>
                            @else
                                <span class="rounded-full bg-[#eef5ed] px-3 py-2 text-xs font-bold text-[#5d7460]">Completed</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="rounded-[8px] border border-dashed border-[#d6c8bb] p-8 text-sm text-[#7f6a58]">No schedule for this date.</p>
                @endforelse
            </div>
        </div>

        <aside class="shelter-card p-5">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">{{ $selectedDate->format('F Y') }}</p>
            <div class="mt-6 grid grid-cols-7 gap-1 text-center text-xs font-semibold text-[#7f6a58]">
                @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                    <span>{{ $day }}</span>
                @endforeach
                @for ($i = 1; $i <= $selectedDate->daysInMonth; $i++)
                    <span class="rounded-[8px] py-2 {{ $i === (int) $selectedDate->format('d') ? 'bg-[#6f5543] text-white' : 'bg-[#fbf6ef]' }}">{{ $i }}</span>
                @endfor
            </div>
        </aside>
    </section>
</x-app-layout>
