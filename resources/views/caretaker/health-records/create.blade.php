<x-app-layout>
    <x-slot name="title">Log Health</x-slot>

    <form method="POST" action="{{ route('caretaker.health-records.store') }}" enctype="multipart/form-data" class="mx-auto max-w-4xl shelter-card p-6">
        @csrf
        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Health record</p>
        <h2 class="mt-2 text-2xl font-black">New observation</h2>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-semibold text-[#5b4638]">Dog
                <select name="dog_id" class="shelter-input" required>
                    @foreach ($dogs as $dog)
                        <option value="{{ $dog->id }}" @selected((int) request('dog_id') === $dog->id)>{{ $dog->name }} - {{ $dog->kennel }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Severity
                <select name="severity" class="shelter-input" required>
                    @foreach (['normal', 'watch', 'concerning', 'urgent'] as $severity)
                        <option value="{{ $severity }}" @selected(old('severity', 'normal') === $severity)>{{ Str::headline($severity) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Recorded at
                <input type="datetime-local" name="recorded_at" value="{{ old('recorded_at', now()->format('Y-m-d\TH:i')) }}" class="shelter-input" required>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Photo
                <input type="file" name="photo" accept="image/*" class="shelter-input">
            </label>
            <label class="sm:col-span-2 text-sm font-semibold text-[#5b4638]">Observation
                <textarea name="observation" rows="4" class="shelter-input" required>{{ old('observation') }}</textarea>
            </label>
            <label class="sm:col-span-2 text-sm font-semibold text-[#5b4638]">Symptoms
                <textarea name="symptoms" rows="3" class="shelter-input">{{ old('symptoms') }}</textarea>
            </label>
            <label class="sm:col-span-2 text-sm font-semibold text-[#5b4638]">Notes
                <textarea name="notes" rows="3" class="shelter-input">{{ old('notes') }}</textarea>
            </label>
            <label class="flex items-center gap-2 text-sm font-semibold text-[#5b4638]">
                <input type="checkbox" name="zoonosis_flag" value="1" @checked(old('zoonosis_flag')) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                Zoonosis flag
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="shelter-button flex-1">Save Health Record</button>
            <a href="{{ route('caretaker.dogs.index') }}" class="shelter-button-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
