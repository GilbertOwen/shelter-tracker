<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ShelterTrack') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="shelter-paw-bg font-sans antialiased text-[#3f3027]">
    <div class="min-h-screen lg:flex">
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 bg-[#5b4638] text-white lg:flex lg:flex-col">
            @include('layouts.navigation')
        </aside>

        <div class="min-h-screen flex-1 lg:pl-72">
            <header class="sticky top-0 z-30 bg-[#5b4638] text-white shadow-sm lg:rounded-bl-[28px]">
                <div class="flex min-h-20 items-center justify-between gap-4 px-4 py-4 sm:px-8 lg:px-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#d7c4b1]">{{ auth()->user()?->role === 'admin' ? 'Shelter Admin' : Str::headline(auth()->user()?->role ?? 'Workspace') }}</p>
                        <h1 class="text-xl font-bold sm:text-2xl">{{ $title ?? 'ShelterTrack' }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('adopt.index') }}" class="hidden rounded-[8px] border border-white/15 px-3 py-2 text-sm font-semibold text-white/90 hover:bg-white/10 sm:inline-flex">Adopt</a>
                        <div class="grid h-11 w-11 place-items-center rounded-full bg-[#f3e6d8] text-sm font-bold text-[#5b4638]">
                            {{ Str::of(auth()->user()->name)->explode(' ')->map(fn ($part) => Str::substr($part, 0, 1))->take(2)->implode('') }}
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 overflow-x-auto px-4 pb-4 lg:hidden">
                    @include('layouts.navigation', ['mobile' => true])
                </div>
            </header>

            <main class="px-4 py-6 sm:px-8 lg:px-10">
                @if (session('success'))
                    <div class="mb-4 rounded-[8px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-[8px] border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-[8px] border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <p class="font-semibold">Please review the form.</p>
                        <ul class="mt-1 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
