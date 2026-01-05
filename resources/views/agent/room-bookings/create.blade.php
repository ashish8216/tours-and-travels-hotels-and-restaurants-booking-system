@extends('agent.layout')
<style>
    /* Make dropdown clearly visible */
    #roomSelect {
        min-height: 42px;
        background-color: white;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        width: 100%;
        font-size: 1rem;
        line-height: 1.5;
    }

    #roomSelect option {
        padding: 8px 12px;
        font-size: 0.875rem;
    }

    #roomSelect:focus {
        outline: none;
        border-color: #3b82f6;
        ring-width: 2px;
    }

    /* Style for disabled past dates */
    input[type="date"]:invalid {
        border-color: #f87171;
        background-color: #fef2f2;
    }
</style>

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg border border-gray-200">

    <h2 class="text-xl font-semibold mb-6">Create Room Booking (Walk-in)</h2>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('agent.room-bookings.store') }}">
        @csrf

        <!-- Guest Name -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Guest Name</label>
            <input type="text" name="guest_name" class="w-full border rounded px-3 py-2"
                   value="{{ old('guest_name') }}" required>
        </div>

        <!-- Guest Phone -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Guest Phone</label>
            <input type="text" name="guest_phone" class="w-full border rounded px-3 py-2"
                   value="{{ old('guest_phone') }}" required>
        </div>

        <!-- Check-in -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Check-in Date</label>
            <input type="date"
                   id="check_in"
                   name="check_in"
                   class="w-full border rounded px-3 py-2"
                   min="{{ date('Y-m-d') }}"
                   value="{{ old('check_in', date('Y-m-d')) }}"
                   required>
            @error('check_in')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Check-out -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Check-out Date</label>
            <input type="date"
                   id="check_out"
                   name="check_out"
                   class="w-full border rounded px-3 py-2"
                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                   value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                   required>
            @error('check_out')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Room Select -->
        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Select Room</label>
            <select name="room_id" id="roomSelect" class="w-full border rounded px-3 py-2" required>
                <option value="">Select check-in & check-out first</option>
            </select>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Create Booking
        </button>

        <a href="{{ route('agent.room-bookings.index') }}"
           class="ml-3 text-gray-600 hover:underline">
            Cancel
        </a>
    </form>
</div>

{{-- ========================= --}}
{{-- Room availability script --}}
{{-- ========================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const roomSelect = document.getElementById('roomSelect');

        // Set min dates
        const today = new Date().toISOString().split('T')[0];
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];

        // Set default values if not already set by old() data
        if (!checkIn.value) checkIn.value = today;
        if (!checkOut.value) checkOut.value = tomorrowStr;

        // Set min attributes
        checkIn.min = today;
        checkOut.min = tomorrowStr;

        // Function to load rooms
        function loadRooms() {
            if (!checkIn.value || !checkOut.value) {
                roomSelect.innerHTML = '<option value="">Select dates first</option>';
                return;
            }

            // Validate dates
            const selectedCheckIn = new Date(checkIn.value);
            const selectedCheckOut = new Date(checkOut.value);
            const todayDate = new Date(today);

            if (selectedCheckIn < todayDate) {
                alert('Check-in date cannot be in the past. Please select a future date.');
                checkIn.value = today;
                return;
            }

            if (selectedCheckOut <= todayDate) {
                alert('Check-out date must be after today.');
                checkOut.value = tomorrowStr;
                return;
            }

            if (selectedCheckOut <= selectedCheckIn) {
                alert('Check-out date must be after check-in date.');
                checkOut.value = new Date(selectedCheckIn);
                checkOut.setDate(checkOut.getDate() + 1);
                checkOut.value = checkOut.toISOString().split('T')[0];
                return;
            }

            console.log('Loading rooms for:', checkIn.value, 'to', checkOut.value);

            // Show loading message
            roomSelect.innerHTML = '<option value="">Loading rooms...</option>';

            // Fetch rooms
            fetch(`/agent/available-rooms?check_in=${checkIn.value}&check_out=${checkOut.value}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(rooms => {
                    console.log('Received rooms:', rooms);

                    // Clear dropdown
                    roomSelect.innerHTML = '';

                    // Add default option
                    const defaultOption = document.createElement('option');
                    defaultOption.value = "";
                    defaultOption.textContent = "-- Select Room --";
                    roomSelect.appendChild(defaultOption);

                    // Check if we have rooms
                    if (!rooms || rooms.length === 0) {
                        const noRoomOption = document.createElement('option');
                        noRoomOption.value = "";
                        noRoomOption.textContent = "No rooms available for selected dates";
                        roomSelect.appendChild(noRoomOption);
                        return;
                    }

                    // Add each room
                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `${room.room_name} - Rs ${room.price_per_night}/night`;
                        roomSelect.appendChild(option);
                    });

                    console.log('Added', rooms.length, 'rooms to dropdown');
                })
                .catch(error => {
                    console.error('Error:', error);
                    roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
                });
        }

        // Update check-out min when check-in changes
        checkIn.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const nextDay = new Date(selectedDate);
            nextDay.setDate(nextDay.getDate() + 1);

            checkOut.min = nextDay.toISOString().split('T')[0];

            // If current check-out is before new minimum, update it
            if (new Date(checkOut.value) < nextDay) {
                checkOut.value = nextDay.toISOString().split('T')[0];
            }

            loadRooms();
        });

        // Load rooms when check-out changes
        checkOut.addEventListener('change', loadRooms);

        // Load rooms immediately
        setTimeout(() => {
            loadRooms();
        }, 100);
    });
</script>
@endsection
