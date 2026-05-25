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
    @php
        $layoutUser = auth()->user();
        $notificationItems = collect();

        if ($layoutUser?->shelter_id) {
            $urgentRecords = \App\Models\HealthRecord::with('dog')
                ->whereHas('dog', fn ($query) => $query->where('shelter_id', $layoutUser->shelter_id)->active())
                ->where('severity', 'urgent')
                ->latest('recorded_at')
                ->take(3)
                ->get();

            foreach ($urgentRecords as $record) {
                $notificationItems->push([
                    'title' => 'Urgent health: '.$record->dog->name,
                    'body' => Str::limit($record->observation, 82),
                    'tone' => 'urgent',
                ]);
            }

            if ($layoutUser->isCaretaker()) {
                $scheduleCount = \App\Models\Schedule::where('assigned_to', $layoutUser->id)
                    ->whereDate('start_time', today())
                    ->whereIn('status', ['pending', 'overdue'])
                    ->count();
            } elseif ($layoutUser->isAdmin()) {
                $scheduleCount = \App\Models\Schedule::whereHas('dog', fn ($query) => $query->where('shelter_id', $layoutUser->shelter_id))
                    ->whereDate('start_time', today())
                    ->whereIn('status', ['pending', 'overdue'])
                    ->count();
            } else {
                $scheduleCount = 0;
            }

            if ($scheduleCount > 0) {
                $notificationItems->push([
                    'title' => $scheduleCount.' schedule pending today',
                    'body' => 'Open Schedule to review daily care tasks.',
                    'tone' => 'schedule',
                ]);
            }
        }
    @endphp

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
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = ! open" @keydown.escape.window="open = false" class="relative grid h-11 w-11 place-items-center rounded-[8px] border border-white/15 bg-white/10 text-sm font-black text-white hover:bg-white/20" title="Notifications">
                                !
                                @if ($notificationItems->isNotEmpty())
                                    <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-red-500 px-1 text-[11px] font-black text-white">{{ $notificationItems->count() }}</span>
                                @endif
                            </button>
                            <div x-cloak x-show="open" x-transition @click.outside="open = false" class="absolute right-0 mt-3 w-80 rounded-[8px] border border-[#eaded0] bg-white p-4 text-[#3f3027] shadow-xl">
                                <div class="flex items-center justify-between">
                                    <p class="font-black">Notifications</p>
                                    <button type="button" @click="open = false" class="text-sm font-semibold text-[#7f6a58]">Close</button>
                                </div>
                                <div class="mt-4 space-y-3">
                                    @forelse ($notificationItems as $item)
                                        <div class="rounded-[8px] p-3 {{ $item['tone'] === 'urgent' ? 'bg-red-50 text-red-800' : 'bg-[#fbf6ef]' }}">
                                            <p class="text-sm font-black">{{ $item['title'] }}</p>
                                            <p class="mt-1 text-xs leading-5">{{ $item['body'] }}</p>
                                        </div>
                                    @empty
                                        <p class="rounded-[8px] bg-[#eef5ed] p-3 text-sm font-semibold text-[#5d7460]">No urgent notification right now.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
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
