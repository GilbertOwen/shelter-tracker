<x-guest-layout>
    <div x-data="{ role: '{{ old('role', 'adopter') }}' }" class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-950">Create your ShelterTrack account</h1>
            <p class="mt-1 text-sm text-slate-500">Register as an adopter or open a new shelter workspace as admin.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="grid gap-3 sm:grid-cols-2">
                <label class="rounded-md border p-4" :class="role === 'adopter' ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200'">
                    <input type="radio" name="role" value="adopter" x-model="role" class="sr-only">
                    <span class="block font-semibold">Adopter</span>
                    <span class="text-sm text-slate-500">Browse available dogs.</span>
                </label>
                <label class="rounded-md border p-4" :class="role === 'admin' ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200'">
                    <input type="radio" name="role" value="admin" x-model="role" class="sr-only">
                    <span class="block font-semibold">Admin Shelter</span>
                    <span class="text-sm text-slate-500">Create a shelter workspace.</span>
                </label>
            </div>

            <div>
                <x-input-label for="name" value="Full name" />
                <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" value="Phone" />
                <x-text-input id="phone" class="mt-1 block w-full" type="text" name="phone" :value="old('phone')" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div x-show="role === 'admin'" x-cloak class="rounded-md border border-slate-200 bg-stone-50 p-4">
                <h2 class="font-semibold text-slate-900">Shelter profile</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="shelter_name" value="Shelter name" />
                        <x-text-input id="shelter_name" class="mt-1 block w-full" type="text" name="shelter_name" :value="old('shelter_name')" />
                    </div>
                    <div>
                        <x-input-label for="shelter_city" value="City" />
                        <x-text-input id="shelter_city" class="mt-1 block w-full" type="text" name="shelter_city" :value="old('shelter_city')" />
                    </div>
                    <div>
                        <x-input-label for="shelter_address" value="Address" />
                        <x-text-input id="shelter_address" class="mt-1 block w-full" type="text" name="shelter_address" :value="old('shelter_address')" />
                    </div>
                    <div>
                        <x-input-label for="shelter_phone" value="Shelter phone" />
                        <x-text-input id="shelter_phone" class="mt-1 block w-full" type="text" name="shelter_phone" :value="old('shelter_phone')" />
                    </div>
                </div>
                <div class="mt-4">
                    <x-input-label for="shelter_description" value="Shelter description" />
                    <textarea id="shelter_description" name="shelter_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('shelter_description') }}</textarea>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Confirm password" />
                    <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm font-semibold text-slate-600 hover:text-slate-900" href="{{ route('login') }}">Already registered?</a>
                <x-primary-button>Register</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>