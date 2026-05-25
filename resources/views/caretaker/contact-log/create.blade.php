<x-app-layout>
    <x-slot name="title">Log Contact</x-slot>

    <form method="POST" action="{{ route('caretaker.contact-log.store') }}" class="mx-auto max-w-3xl shelter-card p-6">
        @csrf
        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Contact tracing</p>
        <h2 class="mt-2 text-2xl font-black">New contact log</h2>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-semibold text-[#5b4638]">Dog
                <select name="dog_id" class="shelter-input" required>
                    @foreach ($dogs as $dog)
                        <option value="{{ $dog->id }}" @selected((int) request('dog_id') === $dog->id)>{{ $dog->name }} - {{ $dog->kennel }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Contact type
                <select name="contact_type" class="shelter-input" required>
                    @foreach (['feeding', 'handling', 'bathing', 'medication_application', 'walking', 'cleaning_cage', 'other'] as $type)
                        <option value="{{ $type }}" @selected(old('contact_type', 'handling') === $type)>{{ Str::headline($type) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Duration minutes
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 15) }}" class="shelter-input">
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">PPE used
                <select name="ppe_used" class="shelter-input" required>
                    @foreach (['none', 'gloves', 'mask', 'full_suit'] as $ppe)
                        <option value="{{ $ppe }}" @selected(old('ppe_used', 'none') === $ppe)>{{ Str::headline($ppe) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Logged at
                <input type="datetime-local" name="logged_at" value="{{ old('logged_at', now()->format('Y-m-d\TH:i')) }}" class="shelter-input" required>
            </label>
            <label class="sm:col-span-2 text-sm font-semibold text-[#5b4638]">Notes
                <textarea name="notes" rows="4" class="shelter-input">{{ old('notes') }}</textarea>
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="shelter-button flex-1">Save Contact Log</button>
            <a href="{{ route('caretaker.contact-log.index') }}" class="shelter-button-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
