@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Reservation</h1>
                <p class="text-gray-600 mt-1">Book a table at {{ $restaurant->name }}</p>
            </div>
            <a href="{{ route('agent.restaurants.reservations.index', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Reservations
            </a>
        </div>
    </div>

    <form action="{{ route('agent.restaurants.reservations.store', $restaurant) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <!-- Customer Information -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Full Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('customer_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('customer_email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('customer_phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Reservation Details -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Reservation Details</h2>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Date *</label>
                    <input type="date" name="reservation_date" value="{{ old('reservation_date') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('reservation_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Time *</label>
                    <input type="time" name="reservation_time" value="{{ old('reservation_time') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('reservation_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Number of People *</label>
                    <input type="number" name="number_of_people" value="{{ old('number_of_people', 2) }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('number_of_people')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Table (Optional)</label>
                    <select name="restaurant_table_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">No specific table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}" {{ old('restaurant_table_id') == $table->id ? 'selected' : '' }}>
                                {{ $table->table_name ?? 'Table ' . $table->table_number }} ({{ $table->capacity }} people)
                            </option>
                        @endforeach
                    </select>
                    @error('restaurant_table_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Special Requests -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Special Requests</h2>
            <div>
                <textarea name="special_requests" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Any special requests or notes...">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Restaurant Info -->
        <div class="mb-8 bg-gray-50 rounded-lg p-4">
            <h3 class="font-medium text-gray-900 mb-2">Restaurant Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <p class="text-gray-500">Opening Hours:</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($restaurant->opening_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($restaurant->closing_time)->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Capacity:</p>
                    <p class="font-medium">{{ $restaurant->capacity }} seats</p>
                </div>
                <div>
                    <p class="text-gray-500">Available Tables:</p>
                    <p class="font-medium">{{ $tables->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Create Reservation
            </button>
            <a href="{{ route('agent.restaurants.reservations.index', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
