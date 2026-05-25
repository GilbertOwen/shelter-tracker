<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @if ($mode === 'admin')
        @if ($urgentRecords->isNotEmpty())
            <div class="mb-5 rounded-[8px] border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <span class="font-bold">Urgent health alert:</span>
                {{ $urgentRecords->first()->dog->name }} needs attention. Open health records or contact trace if zoonosis is suspected.
            </div>
        @endif

        <section class="grid gap-5 xl:grid-cols-[1.45fr_0.9fr]">
            <div class="space-y-5">
                <div class="shelter-card overflow-hidden">
                    <div class="grid gap-6 p-6 lg:grid-cols-[1fr_240px]">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Welcome Back</p>
                            <h2 class="mt-2 text-3xl font-black text-[#3f3027]">Daily shelter overview</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#7f6a58]">Monitor active dogs, adoption readiness, urgent health records, and caretaker workload from one calm workspace.</p>
                            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-[8px] bg-[#f7efe6] p-4">
                                    <p class="text-sm text-[#7f6a58]">Total Dogs</p>
                                    <p class="mt-1 text-3xl font-black">{{ $totalDogs }}</p>
                                </div>
                                <div class="rounded-[8px] bg-[#eef5ed] p-4">
                                    <p class="text-sm text-[#5d7460]">Available</p>
                                    <p class="mt-1 text-3xl font-black">{{ $availableDogs }}</p>
                                </div>
                                <div class="rounded-[8px] bg-[#f4ece1] p-4">
                                    <p class="text-sm text-[#7f6a58]">New Intake</p>
                                    <p class="mt-1 text-3xl font-black">{{ $newIntake }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-[8px] bg-[#5b4638] p-5 text-white">
                            <p class="text-sm font-semibold text-[#f3e6d8]">Quick actions</p>
                            <div class="mt-5 grid gap-3">
                                <a href="{{ route('admin.dogs.create') }}" class="rounded-[8px] bg-white px-4 py-3 text-sm font-bold text-[#5b4638]">Add Dog</a>
                                <a href="{{ route('admin.schedules.create') }}" class="rounded-[8px] bg-white/10 px-4 py-3 text-sm font-bold text-white">Create Schedule</a>
                                <a href="{{ route('admin.contact-trace') }}" class="rounded-[8px] bg-white/10 px-4 py-3 text-sm font-bold text-white">Open Trace</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shelter-card p-5">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-black">Caretaker workload</h3>
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-[#6f5543]">Manage</a>
                    </div>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        @forelse ($workloads as $caretaker)
                            <div class="rounded-[8px] border border-[#eaded0] p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-bold">{{ $caretaker->name }}</p>
                                        <p class="text-sm text-[#7f6a58]">{{ $caretaker->phone ?? 'No phone' }}</p>
                                    </div>
                                    <span class="rounded-full bg-[#f7efe6] px-3 py-1 text-xs font-bold text-[#6f5543]">{{ $caretaker->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                                        <p class="text-[#7f6a58]">Dogs</p>
                                        <p class="text-xl font-black">{{ $caretaker->active_assignments_count }}</p>
                                    </div>
                                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                                        <p class="text-[#7f6a58]">Tasks</p>
                                        <p class="text-xl font-black">{{ $caretaker->pending_schedules_count }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-[8px] border border-dashed border-[#d6c8bb] p-6 text-sm text-[#7f6a58]">No caretaker yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-5">
                <div class="shelter-card p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-black">My Activity</h3>
                        <span class="rounded-full bg-[#f7efe6] px-3 py-1 text-xs font-bold text-[#6f5543]">{{ now()->format('M d') }}</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($todaySchedules as $schedule)
                            <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-bold">{{ $schedule->title }}</p>
                                    <span class="text-xs font-bold uppercase text-[#9a8069]">{{ $schedule->start_time->format('H:i') }}</span>
                                </div>
                                <p class="mt-1 text-sm text-[#7f6a58]">{{ $schedule->dog->name }} with {{ $schedule->assignee?->name }}</p>
                            </div>
                        @empty
                            <p class="rounded-[8px] border border-dashed border-[#d6c8bb] p-6 text-sm text-[#7f6a58]">No schedule today.</p>
                        @endforelse
                    </div>
                </div>

                <div class="shelter-card p-5">
                    <h3 class="text-lg font-black">Urgent health</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($urgentRecords as $record)
                            <div class="rounded-[8px] bg-red-50 p-4 text-sm">
                                <p class="font-bold text-red-800">{{ $record->dog->name }}</p>
                                <p class="mt-1 text-red-700">{{ $record->observation }}</p>
                            </div>
                        @empty
                            <p class="rounded-[8px] bg-[#eef5ed] p-4 text-sm text-[#5d7460]">No urgent alert right now.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </section>
    @elseif ($mode === 'caretaker')
        @if ($urgentRecords->isNotEmpty())
            <div class="mb-5 rounded-[8px] border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <span class="font-bold">Urgent health alert:</span>
                {{ $urgentRecords->first()->dog->name }} has an urgent record in your shelter.
            </div>
        @endif

        <section class="grid gap-5 xl:grid-cols-[1.45fr_0.9fr]">
            <div class="shelter-card p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Welcome Back</p>
                <h2 class="mt-2 text-3xl font-black">Today care board</h2>
                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-[8px] bg-[#f7efe6] p-4">
                        <p class="text-sm text-[#7f6a58]">Assigned Dogs</p>
                        <p class="mt-1 text-3xl font-black">{{ $assignedDogs->count() }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#eef5ed] p-4">
                        <p class="text-sm text-[#5d7460]">Contact Logs</p>
                        <p class="mt-1 text-3xl font-black">{{ $contactCount }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#f4ece1] p-4">
                        <p class="text-sm text-[#7f6a58]">Activity</p>
                        <p class="mt-1 text-3xl font-black">{{ $recentActivityCount }}</p>
                    </div>
                </div>
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('caretaker.health-records.create') }}" class="shelter-button">Log Health</a>
                    <a href="{{ route('caretaker.contact-log.create') }}" class="shelter-button-secondary">Log Contact</a>
                </div>
            </div>

            <div class="shelter-card p-5">
                <h3 class="text-lg font-black">Schedule Today</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($todaySchedules as $schedule)
                        <div class="rounded-[8px] bg-[#fbf6ef] p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold">{{ $schedule->title }}</p>
                                <span class="text-xs font-bold uppercase text-[#9a8069]">{{ $schedule->start_time->format('H:i') }}</span>
                            </div>
                            <p class="mt-1 text-sm text-[#7f6a58]">{{ $schedule->dog->name }} - {{ Str::headline($schedule->status) }}</p>
                        </div>
                    @empty
                        <p class="rounded-[8px] border border-dashed border-[#d6c8bb] p-6 text-sm text-[#7f6a58]">No schedule today.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($assignedDogs as $dog)
                <a href="{{ route('caretaker.dogs.show', $dog) }}" class="shelter-card block overflow-hidden">
                    <div class="h-36 bg-[#eaded0]">
                        @if ($dog->image_url)
                            <img src="{{ $dog->image_url }}" alt="{{ $dog->name }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-lg font-black">{{ $dog->name }}</p>
                        <p class="text-sm text-[#7f6a58]">{{ $dog->breed }} - Kennel {{ $dog->kennel }}</p>
                    </div>
                </a>
            @endforeach
        </section>
    @else
        <section class="shelter-card p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Adopter</p>
            <h2 class="mt-2 text-3xl font-black">Meet adoptable dogs</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#7f6a58]">Your account is ready. Public adoption pages are browse-only for MVP, so contact the shelter directly from each dog detail page.</p>
            <a href="{{ route('adopt.index') }}" class="shelter-button mt-5">Browse Dogs</a>
        </section>
    @endif
</x-app-layout>
