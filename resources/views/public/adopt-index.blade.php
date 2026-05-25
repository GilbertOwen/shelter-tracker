@extends('layouts.public', ['title' => 'Adopt Dogs - ShelterTrack'])

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#9a8069]">Public Adoption</p>
            <h1 class="mt-3 text-4xl font-black leading-tight text-[#3f3027] sm:text-5xl">Find a dog ready for a new home</h1>
            <p class="mt-4 max-w-xl text-sm leading-6 text-[#7f6a58]">Browse active, available dogs from shelters. Internal health records, schedules, and contact logs stay private.</p>
        </div>
        <form method="GET" class="shelter-card grid gap-3 p-5 sm:grid-cols-2">
            <input name="breed" value="{{ request('breed') }}" placeholder="Breed or keyword" class="shelter-input mt-0">
            <select name="age" class="shelter-input mt-0">
                <option value="">Any age</option>
                <option value="puppy" @selected(request('age') === 'puppy')>Puppy</option>
                <option value="adult" @selected(request('age') === 'adult')>Adult</option>
                <option value="senior" @selected(request('age') === 'senior')>Senior</option>
            </select>
            <select name="size" class="shelter-input mt-0">
                <option value="">Any size</option>
                @foreach (['S', 'M', 'L', 'XL'] as $size)
                    <option value="{{ $size }}" @selected(request('size') === $size)>Size {{ $size }}</option>
                @endforeach
            </select>
            <select name="sex" class="shelter-input mt-0">
                <option value="">Any sex</option>
                <option value="male" @selected(request('sex') === 'male')>Male</option>
                <option value="female" @selected(request('sex') === 'female')>Female</option>
            </select>
            <label class="flex items-center gap-2 text-sm font-semibold text-[#5b4638]">
                <input type="checkbox" name="good_with_kids" value="1" @checked(request()->boolean('good_with_kids')) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                Good with kids
            </label>
            <label class="flex items-center gap-2 text-sm font-semibold text-[#5b4638]">
                <input type="checkbox" name="good_with_pets" value="1" @checked(request()->boolean('good_with_pets')) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                Good with pets
            </label>
            <div class="sm:col-span-2 flex gap-3">
                <button class="shelter-button flex-1">Filter</button>
                <a href="{{ route('adopt.index') }}" class="shelter-button-secondary">Reset</a>
            </div>
        </form>
    </section>

    <section class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($dogs as $dog)
            <a href="{{ route('adopt.show', $dog) }}" class="shelter-card group block overflow-hidden">
                <div class="h-56 bg-[#eaded0]">
                    @if ($dog->image_url)
                        <img src="{{ $dog->image_url }}" alt="{{ $dog->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                    @else
                        <div class="grid h-full place-items-center text-4xl font-black text-[#7f6a58]">{{ Str::substr($dog->name, 0, 1) }}</div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-black">{{ $dog->name }}</h2>
                            <p class="text-sm text-[#7f6a58]">{{ $dog->breed ?? 'Mixed breed' }}</p>
                        </div>
                        <span class="rounded-full bg-[#eef5ed] px-3 py-1 text-xs font-bold text-[#5d7460]">Available</span>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs font-semibold text-[#7f6a58]">
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">{{ $dog->age_label }}</span>
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">Size {{ $dog->size ?? '-' }}</span>
                        <span class="rounded-[8px] bg-[#fbf6ef] px-2 py-2">{{ Str::headline($dog->sex) }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="shelter-card p-8 text-sm text-[#7f6a58] sm:col-span-2 lg:col-span-3">No available dog matches the selected filters.</div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $dogs->links() }}
    </div>
@endsection
