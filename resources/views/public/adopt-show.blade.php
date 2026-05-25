@extends('layouts.public', ['title' => $dog->name.' - ShelterTrack'])

@section('content')
    <a href="{{ route('adopt.index') }}" class="text-sm font-semibold text-[#6f5543]">Back to adoptable dogs</a>

    <section class="mt-5 grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="shelter-card overflow-hidden">
            <div class="h-[420px] bg-[#eaded0]">
                @if ($dog->image_url)
                    <img src="{{ $dog->image_url }}" alt="{{ $dog->name }}" class="h-full w-full object-cover">
                @else
                    <div class="grid h-full place-items-center text-6xl font-black text-[#7f6a58]">{{ Str::substr($dog->name, 0, 1) }}</div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="shelter-card p-6">
                <span class="rounded-full bg-[#eef5ed] px-3 py-1 text-xs font-bold text-[#5d7460]">Available for adoption</span>
                <h1 class="mt-4 text-4xl font-black">{{ $dog->name }}</h1>
                <p class="mt-2 text-[#7f6a58]">{{ $dog->breed ?? 'Mixed breed' }} - {{ $dog->age_label }} - Size {{ $dog->size ?? '-' }}</p>
                <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                        <p class="font-semibold text-[#7f6a58]">Sex</p>
                        <p class="font-bold">{{ Str::headline($dog->sex) }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                        <p class="font-semibold text-[#7f6a58]">Fee</p>
                        <p class="font-bold">Rp {{ number_format((float) $dog->adoption_fee, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                        <p class="font-semibold text-[#7f6a58]">Kids</p>
                        <p class="font-bold">{{ $dog->good_with_kids ? 'Good fit' : 'Ask shelter' }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                        <p class="font-semibold text-[#7f6a58]">Pets</p>
                        <p class="font-bold">{{ $dog->good_with_pets ? 'Good fit' : 'Ask shelter' }}</p>
                    </div>
                </div>
            </div>

            <div class="shelter-card p-6">
                <h2 class="text-xl font-black">Shelter contact</h2>
                <p class="mt-2 text-sm text-[#7f6a58]">{{ $dog->shelter->name }}</p>
                <p class="mt-1 text-sm text-[#7f6a58]">{{ $dog->shelter->address }}</p>
                @if ($dog->shelter->contact_for_adoption)
                    <a href="{{ Str::startsWith($dog->shelter->contact_for_adoption, ['http://', 'https://']) ? $dog->shelter->contact_for_adoption : 'tel:'.$dog->shelter->contact_for_adoption }}" class="shelter-button mt-4 w-full">Contact Shelter</a>
                @endif
            </div>
        </div>
    </section>

    <section class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="shelter-card p-6">
            <h2 class="text-xl font-black">Story</h2>
            <p class="mt-3 text-sm leading-6 text-[#7f6a58]">{{ $dog->story ?? 'This dog is ready to meet a patient adopter.' }}</p>
        </div>
        <div class="shelter-card p-6">
            <h2 class="text-xl font-black">Temperament</h2>
            <p class="mt-3 text-sm leading-6 text-[#7f6a58]">{{ $dog->temperament ?? 'Ask shelter for temperament notes.' }}</p>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Public health summary</p>
            <p class="mt-2 text-sm text-[#7f6a58]">Detailed internal health records, schedule, contact logs, and assigned caretaker data are private.</p>
        </div>
    </section>
@endsection
