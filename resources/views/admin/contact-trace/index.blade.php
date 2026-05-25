<x-app-layout>
    <x-slot name="title">Contact Trace</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Zoonosis tracing</p>
            <h2 class="text-3xl font-black">Contact Trace</h2>
            <p class="mt-2 text-sm text-[#7f6a58]">Search contact logs by dog, caretaker, and date range.</p>
        </div>
    </div>

    <form method="GET" class="shelter-card mt-5 grid gap-3 p-5 md:grid-cols-5">
        <select name="dog_id" class="shelter-input mt-0">
            <option value="">All dogs</option>
            @foreach ($dogs as $dog)
                <option value="{{ $dog->id }}" @selected((int) request('dog_id') === $dog->id)>{{ $dog->name }}</option>
            @endforeach
        </select>
        <select name="caretaker_id" class="shelter-input mt-0">
            <option value="">All caretakers</option>
            @foreach ($caretakers as $caretaker)
                <option value="{{ $caretaker->id }}" @selected((int) request('caretaker_id') === $caretaker->id)>{{ $caretaker->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}" class="shelter-input mt-0">
        <input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}" class="shelter-input mt-0">
        <button class="shelter-button">Trace</button>
    </form>

    <section class="shelter-card mt-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#eaded0] text-sm">
                <thead class="bg-[#fbf6ef] text-left text-xs font-bold uppercase tracking-[0.12em] text-[#7f6a58]">
                    <tr>
                        <th class="px-5 py-4">Logged At</th>
                        <th class="px-5 py-4">Dog</th>
                        <th class="px-5 py-4">Caretaker</th>
                        <th class="px-5 py-4">Contact</th>
                        <th class="px-5 py-4">PPE</th>
                        <th class="px-5 py-4">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eaded0] bg-white">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $log->logged_at->format('M d, Y H:i') }}</td>
                            <td class="px-5 py-4">{{ $log->dog->name }}</td>
                            <td class="px-5 py-4">{{ $log->caretaker->name }}</td>
                            <td class="px-5 py-4">{{ Str::headline($log->contact_type) }}<br><span class="text-xs text-[#7f6a58]">{{ $log->duration_minutes ?? '-' }} minutes</span></td>
                            <td class="px-5 py-4">{{ Str::headline($log->ppe_used) }}</td>
                            <td class="px-5 py-4 text-[#7f6a58]">{{ $log->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-[#7f6a58]">No contact logs in this range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6">{{ $logs->links() }}</div>
</x-app-layout>
