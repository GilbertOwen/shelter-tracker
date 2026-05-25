<x-app-layout>
    <x-slot name="title">Caretakers</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">User Management</p>
            <h2 class="text-3xl font-black">Caretakers</h2>
        </div>
        <a href="{{ route('admin.users.create') }}" class="shelter-button">Add Caretaker</a>
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        @forelse ($users as $person)
            <article class="shelter-card p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xl font-black">{{ $person->name }}</p>
                        <p class="text-sm text-[#7f6a58]">{{ $person->email }}</p>
                        <p class="mt-1 text-sm text-[#7f6a58]">{{ $person->phone ?? 'No phone number' }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $person->is_active ? 'bg-[#eef5ed] text-[#5d7460]' : 'bg-[#eee3d7] text-[#7f6a58]' }}">{{ $person->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                        <p class="text-[#7f6a58]">Assigned dogs</p>
                        <p class="text-2xl font-black">{{ $person->active_assignments_count }}</p>
                    </div>
                    <div class="rounded-[8px] bg-[#fbf6ef] p-3">
                        <p class="text-[#7f6a58]">Pending tasks</p>
                        <p class="text-2xl font-black">{{ $person->pending_schedules_count }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.edit', $person) }}" class="shelter-button-secondary mt-5 w-full">Edit Profile</a>
            </article>
        @empty
            <div class="shelter-card p-8 text-sm text-[#7f6a58]">No internal users yet.</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $users->links() }}</div>
</x-app-layout>
