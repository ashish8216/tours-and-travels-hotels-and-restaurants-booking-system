@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Tour Booking</h1>
                <p class="text-gray-600 mt-1">Manually book a tour for a customer</p>
            </div>
            <a href="{{ route('agent.tour-bookings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Bookings
            </a>
        </div>
    </div>

    <form action="{{ route('agent.tour-bookings.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <!-- Customer Information -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Full Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Customer's full name">
                    @error('customer_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="customer@example.com">
                    @error('customer_email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="+1 (555) 123-4567">
                    @error('customer_phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tour Selection -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Tour Selection</h2>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Select Tour *</label>
                <select name="tour_id" id="tourSelect" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Choose a tour</option>
                    @foreach($tours as $tour)
                        <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                            {{ $tour->title }} - ${{ number_format($tour->price, 2) }} per person
                        </option>
                    @endforeach
                </select>
                @error('tour_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Available Dates -->
            <div id="tourDatesContainer" class="hidden">
                <label class="block text-gray-700 font-medium mb-2">Select Date *</label>
                <select name="tour_date_id" id="tourDateSelect" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Choose a date</option>
                </select>
                @error('tour_date_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Date Details -->
                <div id="dateDetails" class="mt-4 bg-gray-50 rounded-lg p-4 hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Available Slots:</p>
                            <p id="availableSlots" class="font-medium text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Booked Slots:</p>
                            <p id="bookedSlots" class="font-medium text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Time:</p>
                            <p id="tourTime" class="font-medium text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status:</p>
                            <p id="tourStatus" class="font-medium text-gray-900"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Details</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Number of People *</label>
                    <input type="number" name="total_people" id="totalPeople" value="{{ old('total_people', 1) }}"
                        min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('total_people')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Price per Person</label>
                    <div id="priceDisplay" class="text-2xl font-bold text-gray-900">$0.00</div>
                    <input type="hidden" id="tourPrice" value="0">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700 font-medium mb-2">Total Amount</label>
                <div id="totalAmountDisplay" class="text-3xl font-bold text-blue-600">$0.00</div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700 font-medium mb-2">Special Requests (Optional)</label>
                <textarea name="special_requests" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Any special requests or notes...">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Availability Check -->
        <div id="availabilityMessage" class="mb-6 hidden">
            <!-- Message will be inserted here by JavaScript -->
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" id="submitButton" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Create Booking
            </button>
            <a href="{{ route('agent.tour-bookings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourSelect = document.getElementById('tourSelect');
    const tourDateSelect = document.getElementById('tourDateSelect');
    const tourDatesContainer = document.getElementById('tourDatesContainer');
    const dateDetails = document.getElementById('dateDetails');
    const totalPeopleInput = document.getElementById('totalPeople');
    const priceDisplay = document.getElementById('priceDisplay');
    const totalAmountDisplay = document.getElementById('totalAmountDisplay');
    const tourPriceInput = document.getElementById('tourPrice');
    const availabilityMessage = document.getElementById('availabilityMessage');
    const submitButton = document.getElementById('submitButton');

    const tourData = @json($tours->keyBy('id'));

    // When tour is selected
    tourSelect.addEventListener('change', function() {
        const tourId = this.value;
        tourDateSelect.innerHTML = '<option value="">Choose a date</option>';
        dateDetails.classList.add('hidden');
        availabilityMessage.classList.add('hidden');

        if (tourId) {
            tourDatesContainer.classList.remove('hidden');
            const tour = tourData[tourId];

            // Update price display
            priceDisplay.textContent = '$' + parseFloat(tour.price).toFixed(2);
            tourPriceInput.value = tour.price;
            updateTotalAmount();

            // Load available dates
            if (tour.tour_dates && tour.tour_dates.length > 0) {
                tour.tour_dates.forEach(date => {
                    const option = document.createElement('option');
                    option.value = date.id;

                    // Format date
                    const dateObj = new Date(date.date);
                    const dateStr = dateObj.toLocaleDateString('en-US', {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    // Format time if exists
                    let timeStr = '';
                    if (date.start_time) {
                        const [hours, minutes] = date.start_time.split(':');
                        const timeObj = new Date();
                        timeObj.setHours(hours, minutes);
                        timeStr = ' at ' + timeObj.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                    }

                    option.textContent = `${dateStr}${timeStr} - ${date.available_slots - date.booked_slots} slots available`;
                    option.dataset.date = JSON.stringify(date);
                    tourDateSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No available dates for this tour';
                option.disabled = true;
                tourDateSelect.appendChild(option);
                submitButton.disabled = true;
            }
        } else {
            tourDatesContainer.classList.add('hidden');
            priceDisplay.textContent = '$0.00';
            tourPriceInput.value = '0';
            updateTotalAmount();
        }
    });

    // When date is selected
    tourDateSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption.value && selectedOption.dataset.date) {
            const date = JSON.parse(selectedOption.dataset.date);

            // Show date details
            document.getElementById('availableSlots').textContent = date.available_slots;
            document.getElementById('bookedSlots').textContent = date.booked_slots;
            document.getElementById('tourStatus').textContent = date.status.charAt(0).toUpperCase() + date.status.slice(1);

            // Format time
            if (date.start_time) {
                const [hours, minutes] = date.start_time.split(':');
                const timeObj = new Date();
                timeObj.setHours(hours, minutes);
                document.getElementById('tourTime').textContent = timeObj.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            } else {
                document.getElementById('tourTime').textContent = 'All day';
            }

            dateDetails.classList.remove('hidden');
            checkAvailability(date);
        } else {
            dateDetails.classList.add('hidden');
            availabilityMessage.classList.add('hidden');
            submitButton.disabled = false;
        }
    });

    // Update total amount when people count changes
    totalPeopleInput.addEventListener('input', function() {
        updateTotalAmount();

        // Recheck availability if date is selected
        const selectedOption = tourDateSelect.options[tourDateSelect.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.date) {
            const date = JSON.parse(selectedOption.dataset.date);
            checkAvailability(date);
        }
    });

    // Update total amount calculation
    function updateTotalAmount() {
        const price = parseFloat(tourPriceInput.value) || 0;
        const people = parseInt(totalPeopleInput.value) || 1;
        const total = price * people;
        totalAmountDisplay.textContent = '$' + total.toFixed(2);
    }

    // Check availability
    function checkAvailability(date) {
        const people = parseInt(totalPeopleInput.value) || 1;
        const availableSlots = date.available_slots - date.booked_slots;

        availabilityMessage.classList.remove('hidden');
        submitButton.disabled = false;

        if (date.status !== 'available') {
            availabilityMessage.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                        <span class="text-red-800 font-medium">This date is not available for booking</span>
                    </div>
                </div>
            `;
            submitButton.disabled = true;
        } else if (people > availableSlots) {
            availabilityMessage.innerHTML = `
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                        <span class="text-amber-800 font-medium">Not enough available slots. Only ${availableSlots} slot(s) remaining.</span>
                    </div>
                    <p class="text-amber-700 text-sm mt-1">Please reduce the number of people or choose another date.</p>
                </div>
            `;
            submitButton.disabled = true;
        } else if (availableSlots <= 5) {
            availabilityMessage.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span class="text-blue-800 font-medium">Limited availability: ${availableSlots} slot(s) remaining</span>
                    </div>
                </div>
            `;
        } else {
            availabilityMessage.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-green-800 font-medium">Available: ${availableSlots} slot(s) remaining</span>
                    </div>
                </div>
            `;
        }
    }

    // Initialize if there's old input
    @if(old('tour_id'))
        tourSelect.value = {{ old('tour_id') }};
        tourSelect.dispatchEvent(new Event('change'));

        @if(old('tour_date_id'))
            // Need to wait for dates to load
            setTimeout(() => {
                tourDateSelect.value = {{ old('tour_date_id') }};
                tourDateSelect.dispatchEvent(new Event('change'));
            }, 100);
        @endif
    @endif
});
</script>
@endsection
