<x-app-layout>
    <x-slot name="title">Contact Log</x-slot>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#9a8069]">Traceability</p>
            <h2 class="text-3xl font-black">Contact Log</h2>
        </div>
        <a href="{{ route('caretaker.contact-log.create') }}" class="shelter-button">Log Contact</a>
    </div>

    <section class="shelter-card mt-6 overflow-hidden">
        <div class="divide-y divide-[#eaded0]">
            @forelse ($logs as $log)
                <div class="p-5">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-black">{{ $log->dog->name }} - {{ Str::headline($log->contact_type) }}</p>
                            <p class="text-sm text-[#7f6a58]">{{ $log->logged_at->format('M d, Y H:i') }} - {{ $log->duration_minutes ?? '-' }} minutes - PPE {{ Str::headline($log->ppe_used) }}</p>
                        </div>
                    </div>
                    @if ($log->notes)
                        <p class="mt-3 text-sm text-[#7f6a58]">{{ $log->notes }}</p>
                    @endif
                </div>
            @empty
                <p class="p-8 text-sm text-[#7f6a58]">No contact logs yet.</p>
            @endforelse
        </div>
    </section>

    <div class="mt-6">{{ $logs->links() }}</div>
</x-app-layout>
