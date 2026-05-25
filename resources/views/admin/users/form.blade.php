<x-app-layout>
    <x-slot name="title">{{ $caretaker->exists ? 'Edit Caretaker' : 'Add Caretaker' }}</x-slot>

    <form method="POST" action="{{ $caretaker->exists ? route('admin.users.update', $caretaker) : route('admin.users.store') }}" class="mx-auto max-w-3xl shelter-card p-6">
        @csrf
        @if ($caretaker->exists)
            @method('PUT')
        @endif

        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Internal user</p>
        <h2 class="mt-2 text-2xl font-black">{{ $caretaker->exists ? $caretaker->name : 'New caretaker account' }}</h2>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-semibold text-[#5b4638]">Name
                <input name="name" value="{{ old('name', $caretaker->name) }}" class="shelter-input" required>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Email
                <input type="email" name="email" value="{{ old('email', $caretaker->email) }}" class="shelter-input" required>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Phone
                <input name="phone" value="{{ old('phone', $caretaker->phone) }}" class="shelter-input">
            </label>
            @if ($caretaker->exists)
                <label class="flex items-end gap-2 pb-2 text-sm font-semibold text-[#5b4638]">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $caretaker->is_active)) class="rounded border-[#d8cabc] text-[#6f5543] focus:ring-[#6f5543]">
                    Active account
                </label>
            @endif
            <label class="text-sm font-semibold text-[#5b4638]">{{ $caretaker->exists ? 'New password' : 'Password' }}
                <input type="password" name="password" class="shelter-input" @if(! $caretaker->exists) required @endif>
            </label>
            <label class="text-sm font-semibold text-[#5b4638]">Confirm password
                <input type="password" name="password_confirmation" class="shelter-input" @if(! $caretaker->exists) required @endif>
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="shelter-button flex-1">Save Caretaker</button>
            <a href="{{ route('admin.users.index') }}" class="shelter-button-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
