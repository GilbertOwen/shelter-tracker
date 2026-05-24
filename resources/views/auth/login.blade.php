<x-guest-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-950">Welcome back</h1>
            <p class="mt-1 text-sm text-slate-500">Login to manage shelter care, schedules, and adoption data.</p>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-slate-600 hover:text-slate-900" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <x-primary-button class="w-full justify-center">Log in</x-primary-button>
        </form>
    </div>
</x-guest-layout>