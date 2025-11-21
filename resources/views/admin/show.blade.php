<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complaint Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-6 rounded">

                <h3 class="text-lg font-semibold mb-4">Complaint #{{ $complaint->id }}</h3>

                <div class="space-y-4">
                    <p><strong>Description:</strong> {{ $complaint->description }}</p>
                    <p><strong>Reporter:</strong> {{ $complaint->reporter->name }} ({{ $complaint->reporter->email }})</p>
                    <p><strong>Urgency:</strong> {{ ucfirst($complaint->urgency) }}</p>
                    <p><strong>Submitted:</strong> {{ $complaint->created_at->format('Y-m-d H:i') }}</p>

                    @if($complaint->image_path)
                        <div>
                            <strong>Photo:</strong>
                            <img src="{{ asset('storage/' . $complaint->image_path) }}"
                                 class="mt-2 rounded shadow max-w-sm">
                        </div>
                    @endif

                    {{-- Admin Notes --}}
                    <div>
                        <h4 class="font-semibold mb-2">Admin Notes</h4>

                        @if($complaint->admin_note)
                            <div class="p-3 bg-gray-100 rounded mb-3">
                                {{ $complaint->admin_note }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.addNote', $complaint->id) }}">
                            @csrf
                            <textarea
                                name="note"
                                rows="3"
                                class="w-full rounded border-gray-300 p-2"
                                placeholder="Add admin note..."></textarea>

                            <button class="mt-2 bg-gray-700 text-white px-4 py-2 rounded">
                                Save Note
                            </button>
                        </form>
                    </div>

                </div>

                <div class="flex gap-4 mt-6">

                    {{-- Resolve --}}
                    @if(!$complaint->is_resolved)
                        <form method="POST" action="{{ route('admin.resolve', $complaint->id) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-4 py-2 rounded">
                                Mark as Resolved
                            </button>
                        </form>
                    @endif

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.destroy', $complaint->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-4 py-2 rounded">
                            Delete Complaint
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
