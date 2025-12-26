@extends('agent.layout')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Manage Rooms</h1>
            <p class="text-gray-600">View and manage all your property rooms</p>
        </div>
        <a href="{{ route('agent.rooms.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add New Room
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    @if($rooms->isEmpty())
        <div class="p-8 text-center">
            <i class="fas fa-hotel text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">No rooms yet</h3>
            <p class="text-gray-500 mb-4">Add your first room to get started</p>
            <a href="{{ route('agent.rooms.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Add First Room
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm">
                        <th class="px-6 py-3 text-left">Image</th>
                        <th class="px-6 py-3 text-left">Room Name</th>
                        <th class="px-6 py-3 text-left">Price / Night</th>
                        <th class="px-6 py-3 text-left">Guests</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Facilities</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($rooms as $room)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($room->primaryImage)
                                <img src="{{ asset('storage/' . $room->primaryImage->image_path) }}"
                                     class="w-16 h-16 object-cover rounded-lg border border-gray-300">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $room->room_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $room->images->count() }} image(s)
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">₹{{ number_format($room->price_per_night) }}</span>
                            <div class="text-sm text-gray-500">per night</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                <span class="text-gray-700">{{ $room->max_guests }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                {{ $room->availability == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($room->availability) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if($room->ac)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">AC</span>
                                @endif
                                @if($room->tv)
                                    <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">TV</span>
                                @endif
                                @if($room->breakfast)
                                    <span class="px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">Breakfast</span>
                                @endif
                                @if($room->attached_bathroom)
                                    <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-800 rounded">Bath</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('agent.rooms.edit', $room) }}"
                                   class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('agent.rooms.destroy', $room) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this room?');"
                                      class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
