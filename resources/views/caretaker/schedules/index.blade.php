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
                                <form method="POST" action="{{ route('caretaker.schedules.complete', $schedule) }}" enctype="multipart/form-data" class="grid gap-2 md:min-w-[360px]" x-data="{ preview: null }">
                                    @csrf @method('PATCH')
                                    <div class="flex gap-2">
                                        <input name="notes" placeholder="Completion notes" class="shelter-input mt-0 min-w-0 flex-1">
                                        <button class="shelter-button">Done</button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <label class="shelter-button-secondary cursor-pointer px-3 py-2">
                                            Photo
                                            <input type="file" name="photo" accept="image/*" class="sr-only" @change="const file = $event.target.files[0]; preview = file ? URL.createObjectURL(file) : null">
                                        </label>
                                        <template x-if="preview">
                                            <img :src="preview" alt="Activity preview" class="h-12 w-12 rounded-[8px] object-cover">
                                        </template>
                                    </div>
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
            @php
                $prevMonth = $selectedDate->copy()->subMonth()->startOfMonth()->format('Y-m-d');
                $nextMonth = $selectedDate->copy()->addMonth()->startOfMonth()->format('Y-m-d');
                $dayOffset = $monthStart->dayOfWeek;
            @endphp
            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('caretaker.schedules.index', ['date' => $prevMonth]) }}" class="grid h-9 w-9 place-items-center rounded-[8px] border border-[#d6c8bb] bg-white font-black text-[#6f5543]">&lt;</a>
                <p class="text-center text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">{{ $selectedDate->format('F Y') }}</p>
                <a href="{{ route('caretaker.schedules.index', ['date' => $nextMonth]) }}" class="grid h-9 w-9 place-items-center rounded-[8px] border border-[#d6c8bb] bg-white font-black text-[#6f5543]">&gt;</a>
            </div>
            <a href="{{ route('caretaker.schedules.index', ['date' => today()->format('Y-m-d')]) }}" class="shelter-button-secondary mt-4 w-full">Today</a>
            <div class="mt-6 grid grid-cols-7 gap-1 text-center text-xs font-semibold text-[#7f6a58]">
                @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                    <span>{{ $day }}</span>
                @endforeach
                @for ($i = 0; $i < $dayOffset; $i++)
                    <span></span>
                @endfor
                @for ($i = 1; $i <= $selectedDate->daysInMonth; $i++)
                    @php
                        $date = $monthStart->copy()->day($i);
                        $dateKey = $date->format('Y-m-d');
                        $hasTask = $taskDates->has($dateKey);
                    @endphp
                    <a href="{{ route('caretaker.schedules.index', ['date' => $dateKey]) }}" class="relative rounded-[8px] py-2 {{ $dateKey === $selectedDate->format('Y-m-d') ? 'bg-[#6f5543] text-white' : 'bg-[#fbf6ef] hover:bg-[#f0e4d6]' }}">
                        {{ $i }}
                        @if ($hasTask)
                            <span class="absolute bottom-1 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full {{ $dateKey === $selectedDate->format('Y-m-d') ? 'bg-white' : 'bg-[#6f5543]' }}"></span>
                        @endif
                    </a>
                @endfor
            </div>
        </aside>
    </section>
</x-app-layout>
