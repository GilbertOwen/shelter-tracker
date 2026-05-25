<x-guest-layout>
    <div x-data="{ role: '{{ old('role', $selectedRole ?? 'adopter') }}' }" class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#9a8069]">Registration</p>
            <h1 class="mt-2 text-3xl font-black text-[#3f3027]">Create account</h1>
            <p class="mt-2 text-sm text-[#7f6a58]">Adopter can browse dogs. Admin Shelter creates a shelter workspace. Caretaker accounts are created by Admin.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="grid gap-3 sm:grid-cols-2">
                <label class="rounded-[8px] border p-4" :class="role === 'adopter' ? 'border-[#6f5543] bg-[#f7efe6]' : 'border-[#e6d8ca]'">
                    <input type="radio" name="role" value="adopter" x-model="role" class="sr-only">
                    <span class="block font-semibold text-[#3f3027]">Adopter</span>
                    <span class="text-sm text-[#7f6a58]">Browse available dogs.</span>
                </label>
                <label class="rounded-[8px] border p-4" :class="role === 'admin' ? 'border-[#6f5543] bg-[#f7efe6]' : 'border-[#e6d8ca]'">
                    <input type="radio" name="role" value="admin" x-model="role" class="sr-only">
                    <span class="block font-semibold text-[#3f3027]">Admin Shelter</span>
                    <span class="text-sm text-[#7f6a58]">Create a shelter workspace.</span>
                </label>
            </div>

            <div>
                <label for="name" class="text-sm font-semibold text-[#5b4638]">Full name</label>
                <input id="name" class="shelter-input" type="text" name="name" value="{{ old('name') }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-[#5b4638]">Email</label>
                <input id="email" class="shelter-input" type="email" name="email" value="{{ old('email') }}" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="phone" class="text-sm font-semibold text-[#5b4638]">Phone</label>
                <input id="phone" class="shelter-input" type="text" name="phone" value="{{ old('phone') }}" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div x-show="role === 'admin'" x-cloak class="rounded-[8px] border border-[#e6d8ca] bg-[#fbf6ef] p-4">
                <h2 class="font-semibold text-[#3f3027]">Shelter profile</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="shelter_name" class="text-sm font-semibold text-[#5b4638]">Shelter name</label>
                        <input id="shelter_name" class="shelter-input" type="text" name="shelter_name" value="{{ old('shelter_name') }}" />
                    </div>
                    <div>
                        <label for="shelter_city" class="text-sm font-semibold text-[#5b4638]">City</label>
                        <input id="shelter_city" class="shelter-input" type="text" name="shelter_city" value="{{ old('shelter_city') }}" />
                    </div>
                    <div>
                        <label for="shelter_address" class="text-sm font-semibold text-[#5b4638]">Address</label>
                        <input id="shelter_address" class="shelter-input" type="text" name="shelter_address" value="{{ old('shelter_address') }}" />
                    </div>
                    <div>
                        <label for="shelter_phone" class="text-sm font-semibold text-[#5b4638]">Shelter phone</label>
                        <input id="shelter_phone" class="shelter-input" type="text" name="shelter_phone" value="{{ old('shelter_phone') }}" />
                    </div>
                </div>
                <div class="mt-4">
                    <label for="shelter_description" class="text-sm font-semibold text-[#5b4638]">Shelter description</label>
                    <textarea id="shelter_description" name="shelter_description" rows="3" class="shelter-input">{{ old('shelter_description') }}</textarea>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="text-sm font-semibold text-[#5b4638]">Password</label>
                    <input id="password" class="shelter-input" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div>
                    <label for="password_confirmation" class="text-sm font-semibold text-[#5b4638]">Confirm password</label>
                    <input id="password_confirmation" class="shelter-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm font-semibold text-[#6f5543] hover:text-[#3f3027]" href="{{ route('login') }}">Already registered?</a>
                <button class="shelter-button">Register</button>
            </div>
        </form>
    </div>
</x-guest-layout>
