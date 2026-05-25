<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#9a8069]">Welcome back</p>
            <h1 class="mt-2 text-3xl font-black text-[#3f3027]">Sign in</h1>
            <p class="mt-2 text-sm text-[#7f6a58]">Manage shelter care, schedules, adoption data, and contact trace records.</p>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="text-sm font-semibold text-[#5b4638]">Email</label>
                <input id="email" class="shelter-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <label for="password" class="text-sm font-semibold text-[#5b4638]">Password</label>
                <input id="password" class="shelter-input" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-[#7f6a58]">
                    <input type="checkbox" name="remember" class="rounded border-[#d8cabc] text-[#6f5543] shadow-sm focus:ring-[#6f5543]">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-[#6f5543] hover:text-[#3f3027]" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <button class="shelter-button w-full">Log in</button>
        </form>
        <p class="text-center text-sm text-[#7f6a58]">
            New here?
            <a href="{{ route('register') }}" class="font-semibold text-[#5b4638]">Create account</a>
        </p>
    </div>
</x-guest-layout>
