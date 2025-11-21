<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Search form --}}
            <div class="mb-6 bg-white p-6 rounded shadow">
                <form method="GET" action="{{ route('admin.index') }}" class="flex items-center gap-3">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by ID or text"
                        value="{{ request('search') }}"
                        class="rounded border-gray-300 p-2 w-64"
                    />

                    <select name="urgency" class="rounded border-gray-300 p-2">
                        <option value="">All urgency</option>
                        <option value="low" {{ request('urgency')=='low' ? 'selected':'' }}>Low</option>
                        <option value="medium" {{ request('urgency')=='medium' ? 'selected':'' }}>Medium</option>
                        <option value="high" {{ request('urgency')=='high' ? 'selected':'' }}>High</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Search
                    </button>
                </form>
            </div>

            {{-- MAP --}}
            <div class="bg-white p-6 rounded shadow mb-6">
                <h3 class="font-semibold mb-3 text-lg">Map of Complaints</h3>

                <div id="map" style="height: 420px;" class="rounded"></div>
            </div>

            {{-- Complaints Table --}}
            <div class="bg-white p-6 rounded shadow mb-6">
                <h3 class="font-semibold mb-3 text-lg">All Complaints</h3>

                <table class="w-full text-sm">
                    <thead class="text-left text-gray-600">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">Description</th>
                            <th class="p-2">Reporter</th>
                            <th class="p-2">Urgency</th>
                            <th class="p-2">Resolved</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaints as $complaint)
                            <tr class="border-t">
                                <td class="p-2">{{ $complaint->id }}</td>
                                <td class="p-2">{{ \Illuminate\Support\Str::limit($complaint->description, 90) }}</td>
                                <td class="p-2">{{ $complaint->reporter->name ?? 'N/A' }}</td>
                                <td class="p-2">{{ ucfirst($complaint->urgency) }}</td>
                                <td class="p-2">
                                    @if($complaint->is_resolved)
                                        <span class="text-green-700">Yes</span>
                                    @else
                                        <span class="text-red-700">No</span>
                                    @endif

                                    @if(!$complaint->is_resolved && $complaint->created_at->diffInDays(now()) > 14)
                                        <span class="ml-2 px-2 py-1 text-xs text-white bg-red-600 rounded">Overdue</span>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <a href="{{ route('admin.show', $complaint->id) }}"
                                        class="text-blue-600 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $complaints->links() }}
                </div>
            </div>

            {{-- Recent --}}
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-3 text-lg">5 Most Recent Complaints</h3>

                <ul>
                    @foreach($recent as $r)
                        <li class="border-b py-2">
                            <div class="flex justify-between">
                                <div>
                                    <span class="font-medium">#{{ $r->id }}</span> —
                                    {{ \Illuminate\Support\Str::limit($r->description, 50) }}
                                    <div class="text-gray-500 text-xs">
                                        {{ $r->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <a href="{{ route('admin.show', $r->id) }}"
                                   class="text-blue-600 hover:underline">
                                   Open
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>

            </div>

        </div>
    </div>

    {{-- LEAFLET MAP --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const complaintsWithCoords = [
            @foreach($complaints as $c)
                @if($c->latitude && $c->longitude)
                    {
                        id: {{ $c->id }},
                        lat: {{ $c->latitude }},
                        lng: {{ $c->longitude }},
                        desc: {!! json_encode(\Illuminate\Support\Str::limit($c->description, 150)) !!}
                    },
                @endif
            @endforeach
        ];

        let mapCenter = [52.370216, 4.895168];
        if (complaintsWithCoords.length > 0) {
            mapCenter = [complaintsWithCoords[0].lat, complaintsWithCoords[0].lng];
        }

        var map = L.map('map').setView(mapCenter, 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        complaintsWithCoords.forEach(function(item) {
            const marker = L.marker([item.lat, item.lng]).addTo(map);
            marker.bindPopup(`<strong>ID: ${item.id}</strong><br>${item.desc}`);
        });
    </script>

</x-app-layout>
