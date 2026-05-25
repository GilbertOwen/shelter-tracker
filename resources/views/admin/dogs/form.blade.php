<x-app-layout>
    <x-slot name="title">{{ $dog->exists ? 'Edit Dog' : 'Add Dog' }}</x-slot>

    <form method="POST" action="{{ $dog->exists ? route('admin.dogs.update', $dog) : route('admin.dogs.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]" x-data="{ photoPreview: @js($dog->image_url) }">
        @csrf
        @if ($dog->exists)
            @method('PUT')
        @endif

        <section class="shelter-card p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Dog profile</p>
            <h2 class="mt-2 text-2xl font-black">{{ $dog->exists ? $dog->name : 'New shelter dog' }}</h2>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="text-sm font-semibold text-[#5b4638]">Name
                    <input name="name" value="{{ old('name', $dog->name) }}" class="shelter-input" required>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Breed
                    <input name="breed" value="{{ old('breed', $dog->breed) }}" class="shelter-input">
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Size
                    <select name="size" class="shelter-input">
                        <option value="">Unknown</option>
                        @foreach (['S', 'M', 'L', 'XL'] as $size)
                            <option value="{{ $size }}" @selected(old('size', $dog->size) === $size)>Size {{ $size }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Sex
                    <select name="sex" class="shelter-input" required>
                        @foreach (['male', 'female', 'unknown'] as $sex)
                            <option value="{{ $sex }}" @selected(old('sex', $dog->sex ?: 'unknown') === $sex)>{{ Str::headline($sex) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Weight kg
                    <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $dog->weight_kg) }}" class="shelter-input">
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Color
                    <input name="color" value="{{ old('color', $dog->color) }}" class="shelter-input">
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Birth date
                    <input type="date" name="birth_date" value="{{ old('birth_date', optional($dog->birth_date)->format('Y-m-d')) }}" class="shelter-input">
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Intake date
                    <input type="date" name="intake_date" value="{{ old('intake_date', optional($dog->intake_date)->format('Y-m-d') ?? today()->format('Y-m-d')) }}" class="shelter-input" required>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Intake source
                    <select name="intake_source" class="shelter-input" required>
                        @foreach (['rescue', 'surrender', 'other'] as $source)
                            <option value="{{ $source }}" @selected(old('intake_source', $dog->intake_source ?: 'other') === $source)>{{ Str::headline($source) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Kennel
                    <input name="kennel" value="{{ old('kennel', $dog->kennel) }}" class="shelter-input">
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Quarantine
                    <select name="quarantine_status" class="shelter-input" required>
                        @foreach (['clear', 'quarantine'] as $status)
                            <option value="{{ $status }}" @selected(old('quarantine_status', $dog->quarantine_status ?: 'clear') === $status)>{{ Str::headline($status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Adoption status
                    <select name="adoption_status" class="shelter-input" required>
                        @foreach (['available', 'pending', 'adopted', 'not_ready'] as $status)
                            <option value="{{ $status }}" @selected(old('adoption_status', $dog->adoption_status ?: 'not_ready') === $status)>{{ Str::headline($status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Adoption fee
                    <input type="number" step="1000" name="adoption_fee" value="{{ old('adoption_fee', $dog->adoption_fee ?? 0) }}" class="shelter-input">
                </label>
                <label class="text-sm font-semibold text-[#5b4638]">Photo
                    <input type="file" name="photo" accept="image/*" class="shelter-input" @change="const file = $event.target.files[0]; photoPreview = file ? URL.createObjectURL(file) : @js($dog->image_url)">
                </label>
            </div>
        </section>

        <section class="space-y-6">
            <div class="shelter-card p-6">
                <h3 class="text-xl font-black">Photo preview</h3>
                <div class="mt-4 h-64 overflow-hidden rounded-[8px] bg-[#eaded0]">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" alt="Dog photo preview" class="h-full w-full object-cover">
                    </template>
                    <div x-show="! photoPreview" class="grid h-full place-items-center text-sm font-semibold text-[#7f6a58]">No photo selected</div>
                </div>
            </div>

            <div class="shelter-card p-6">
                <h3 class="text-xl font-black">Adoption story</h3>
                <label class="mt-4 block text-sm font-semibold text-[#5b4638]">Story
                    <textarea name="story" rows="5" class="shelter-input">{{ old('story', $dog->story) }}</textarea>
                </label>
                <label class="mt-4 block text-sm font-semibold text-[#5b4638]">Temperament
                    <textarea name="temperament" rows="4" class="shelter-input">{{ old('temperament', $dog->temperament) }}</textarea>
                </label>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#5b4638]">
                        <input type="checkbox" name="good_with_kids" value="1" @checked(old('good_with_kids', $dog->good_with_kids)) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                        Good with kids
                    </label>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#5b4638]">
                        <input type="checkbox" name="good_with_pets" value="1" @checked(old('good_with_pets', $dog->good_with_pets)) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                        Good with pets
                    </label>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#5b4638]">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $dog->is_active ?? true)) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                        Active dog
                    </label>
                </div>
            </div>

            <div class="shelter-card p-6">
                <h3 class="text-xl font-black">Primary caretaker</h3>
                <label class="mt-4 block text-sm font-semibold text-[#5b4638]">Caretaker
                    <select name="caretaker_id" class="shelter-input" @if($dog->exists) disabled @endif>
                        <option value="">No assignment</option>
                        @foreach ($caretakers as $caretaker)
                            <option value="{{ $caretaker->id }}" @selected(old('caretaker_id', $assignment?->caretaker_id) === $caretaker->id)>{{ $caretaker->name }}</option>
                        @endforeach
                    </select>
                </label>
                @if ($dog->exists)
                    <p class="mt-3 text-sm text-[#7f6a58]">Use the My Pets assignment selector to reassign while keeping history.</p>
                @endif
            </div>

            <div class="flex gap-3">
                <button class="shelter-button flex-1">Save Dog</button>
                <a href="{{ route('admin.dogs.index') }}" class="shelter-button-secondary">Cancel</a>
            </div>
        </section>
    </form>
</x-app-layout>
