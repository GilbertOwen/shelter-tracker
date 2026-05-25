<x-app-layout>
    <x-slot name="title">Schedule</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Care calendar</p>
            <h2 class="text-3xl font-black">Schedule</h2>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="shelter-button">Add Schedule</a>
    </div>

    <div class="mt-5 grid gap-5 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="shelter-card p-5">
            <form method="GET" class="grid gap-3 sm:grid-cols-[180px_180px_auto]">
                <input type="date" name="date" value="{{ request('date') }}" class="shelter-input mt-0">
                <select name="status" class="shelter-input mt-0">
                    <option value="">All status</option>
                    @foreach (['pending', 'completed', 'overdue'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>
                    @endforeach
                </select>
                <button class="shelter-button">Filter</button>
            </form>

            <div class="mt-5 divide-y divide-[#eaded0]">
                @forelse ($schedules as $schedule)
                    <div class="py-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-black">{{ $schedule->title }}</p>
                                <p class="mt-1 text-sm text-[#7f6a58]">{{ $schedule->dog->name }} - {{ $schedule->assignee?->name }} - {{ $schedule->start_time->format('M d, H:i') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="rounded-full bg-[#fbf6ef] px-3 py-2 text-xs font-bold text-[#6f5543]">{{ Str::headline($schedule->status) }}</span>
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="shelter-button-secondary px-3">Edit</a>
                                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}">
                                    @csrf @method('DELETE')
                                    <button class="shelter-button-secondary px-3" onclick="return confirm('Delete this schedule?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-sm text-[#7f6a58]">No schedules found.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $schedules->links() }}</div>
        </section>

        <aside class="shelter-card p-5">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Today</p>
            <p class="mt-2 text-5xl font-black">{{ $todayCount }}</p>
            <p class="mt-2 text-sm text-[#7f6a58]">tasks scheduled for {{ now()->format('M d, Y') }}</p>
            <div class="mt-6 grid grid-cols-7 gap-1 text-center text-xs font-semibold text-[#7f6a58]">
                @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                    <span>{{ $day }}</span>
                @endforeach
                @for ($i = 1; $i <= now()->daysInMonth; $i++)
                    <span class="rounded-[8px] py-2 {{ $i === (int) now()->format('d') ? 'bg-[#6f5543] text-white' : 'bg-[#fbf6ef]' }}">{{ $i }}</span>
                @endfor
            </div>
        </aside>
    </div>
</x-app-layout>
