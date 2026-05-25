<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ShelterTrack Adopt' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="shelter-paw-bg font-sans text-[#3f3027] antialiased">
    <header class="bg-[#5b4638] text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('adopt.index') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-[#f3e6d8] text-sm font-black text-[#5b4638]">ST</span>
                <span class="text-xl font-black">ShelterTrack</span>
            </a>
            <nav class="flex items-center gap-2">
                <a href="{{ route('adopt.index') }}" class="rounded-[8px] px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">Adopt</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-[8px] bg-[#f3e6d8] px-3 py-2 text-sm font-semibold text-[#5b4638]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-[8px] bg-[#f3e6d8] px-3 py-2 text-sm font-semibold text-[#5b4638]">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>
</body>
</html>
