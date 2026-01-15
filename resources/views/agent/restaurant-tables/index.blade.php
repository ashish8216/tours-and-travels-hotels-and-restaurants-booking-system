@extends('agent.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Restaurant Tables</h1>
            <p class="text-gray-600 mt-1">Manage tables for {{ $restaurant->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.restaurants.tables.create', $restaurant) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                + Add New Table
            </a>
            <a href="{{ route('agent.restaurants.show', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Restaurant
            </a>
        </div>
    </div>

    <!-- Tables Grid -->
    @if($tables->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tables as $table)
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition">
                    <!-- Table Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $table->table_name ?? 'Table ' . $table->table_number }}
                            </h3>
                            <p class="text-gray-600 text-sm">#{{ $table->table_number }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            {{ $table->status === 'available' ? 'bg-green-100 text-green-800' :
                               ($table->status === 'occupied' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ ucfirst($table->status) }}
                        </span>
                    </div>

                    <!-- Table Details -->
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-users mr-3 text-gray-500"></i>
                            <span>Capacity: {{ $table->capacity }} people</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-chair mr-3 text-gray-500"></i>
                            <span>Type: {{ ucfirst(str_replace('_', ' ', $table->type)) }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mt-6">
                        <a href="{{ route('agent.restaurants.tables.edit', [$restaurant, $table]) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg text-sm font-semibold transition">
                            Edit
                        </a>
                        @if($table->reservations()->whereIn('status', ['pending', 'confirmed'])->count() === 0)
                            <form action="{{ route('agent.restaurants.tables.destroy', [$restaurant, $table]) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tables->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Tables Added</h3>
            <p class="text-gray-600 mb-6">Start by adding tables to your restaurant</p>
            <a href="{{ route('agent.restaurants.tables.create', $restaurant) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                Add First Table
            </a>
        </div>
    @endif
</div>
@endsection
