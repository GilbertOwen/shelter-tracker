<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ShelterTrack') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#3f3027] antialiased">
        <div class="grid min-h-screen lg:grid-cols-[0.95fr_1.05fr]">
            <div class="relative hidden overflow-hidden bg-[#5b4638] p-12 text-white lg:block">
                <a href="{{ route('adopt.index') }}" class="flex items-center gap-3">
                    <span class="grid h-12 w-12 place-items-center rounded-full bg-[#f3e6d8] text-base font-black text-[#5b4638]">ST</span>
                    <span class="text-xl font-black">ShelterTrack</span>
                </a>
                <div class="mt-20 max-w-md">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#d7c4b1]">Dog shelter care</p>
                    <h1 class="mt-4 text-5xl font-black leading-tight">A calmer way to manage daily rescue work.</h1>
                    <p class="mt-5 text-base leading-7 text-[#ead7c3]">Register shelters, coordinate caretakers, log contact history, and help adopters meet available dogs.</p>
                </div>
                <div class="absolute bottom-12 right-12 w-72 rounded-[8px] border border-white/10 bg-white/10 p-6">
                    <p class="text-sm font-semibold text-[#f3e6d8]">Today</p>
                    <div class="mt-5 space-y-4">
                        <div class="h-3 w-44 rounded bg-[#f3e6d8]/70"></div>
                        <div class="h-3 w-56 rounded bg-[#f3e6d8]/45"></div>
                        <div class="h-3 w-36 rounded bg-[#f3e6d8]/55"></div>
                    </div>
                </div>
            </div>

            <div class="shelter-paw-bg flex min-h-screen items-center justify-center px-4 py-10 sm:px-8">
                <div class="w-full max-w-xl rounded-r-[32px] bg-white px-6 py-8 shadow-xl sm:px-10">
                    <a href="{{ route('adopt.index') }}" class="mb-8 flex items-center gap-3 lg:hidden">
                        <span class="grid h-11 w-11 place-items-center rounded-full bg-[#5b4638] text-sm font-black text-white">ST</span>
                        <span class="text-lg font-black">ShelterTrack</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
