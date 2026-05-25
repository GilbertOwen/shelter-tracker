<x-app-layout>
    <x-slot name="title">{{ $schedule->exists ? 'Edit Schedule' : 'Add Schedule' }}</x-slot>

    <form method="POST" action="{{ $schedule->exists ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}" class="mx-auto max-w-4xl shelter-card p-6">
        @csrf
        @if ($schedule->exists)
            @method('PUT')
        @endif

        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Care task</p>
        <h2 class="mt-2 text-2xl font-black">{{ $schedule->exists ? $schedule->title : 'New schedule' }}</h2>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-semibold text-[#5b4638]">Dog
                <select name="dog_id" class="shelter-input" required>
                    @foreach ($dogs as $dog)
                        <option value="{{ $dog->id }}" @selected(old('dog_id', $schedule->dog_id) == $dog->id)>{{ $dog->name }} - {{ $dog->kennel }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Caretaker
                <select name="assigned_to" class="shelter-input" required>
                    @foreach ($caretakers as $caretaker)
                        <option value="{{ $caretaker->id }}" @selected(old('assigned_to', $schedule->assigned_to) == $caretaker->id)>{{ $caretaker->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Type
                <select name="type" class="shelter-input" required>
                    @foreach (['feeding', 'bathing', 'medication', 'vaccination', 'vet_visit', 'exercise', 'grooming', 'behavior_training'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $schedule->type ?: 'feeding') === $type)>{{ Str::headline($type) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Title
                <input name="title" value="{{ old('title', $schedule->title) }}" class="shelter-input" required>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Start time
                <input type="datetime-local" name="start_time" value="{{ old('start_time', optional($schedule->start_time)->format('Y-m-d\TH:i')) }}" class="shelter-input" required>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Duration minutes
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $schedule->duration_minutes) }}" class="shelter-input">
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Priority
                <select name="priority" class="shelter-input" required>
                    @foreach (['low', 'medium', 'high'] as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', $schedule->priority ?: 'medium') === $priority)>{{ Str::headline($priority) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Status
                <select name="status" class="shelter-input" required>
                    @foreach (['pending', 'completed', 'overdue'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $schedule->status ?: 'pending') === $status)>{{ Str::headline($status) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="sm:col-span-2 text-sm font-semibold text-[#5b4638]">Description
                <textarea name="description" rows="4" class="shelter-input">{{ old('description', $schedule->description) }}</textarea>
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="shelter-button flex-1">Save Schedule</button>
            <a href="{{ route('admin.schedules.index') }}" class="shelter-button-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
