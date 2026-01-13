@extends('frontend.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-3xl mx-auto">

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <h4 class="text-xl font-bold mb-1">Partner With Us</h4>
                <small class="text-blue-100">Hotel / Restaurant Agent Request</small>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <form method="POST" action="{{ route('agent.request.store') }}">
                    @csrf

                    <!-- Business Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Business Name</label>
                        <input
                            type="text"
                            name="business_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('business_name') }}"
                            required
                        >
                        @error('business_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Owner/Manager Name -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Owner / Manager Name</label>
                        <input
                            type="text"
                            name="owner_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('owner_name') }}"
                            required
                        >
                        @error('owner_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input
                            type="text"
                            name="phone"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('phone') }}"
                            required
                        >
                        @error('phone')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Business Type -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Business Type (Select all that apply)</label>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="mb-3">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        type="checkbox"
                                        name="business_types[]"
                                        value="hotel"
                                        id="hotel"
                                        {{ is_array(old('business_types')) && in_array('hotel', old('business_types')) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-gray-700">Hotel</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        type="checkbox"
                                        name="business_types[]"
                                        value="restaurant"
                                        id="restaurant"
                                        {{ is_array(old('business_types')) && in_array('restaurant', old('business_types')) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-gray-700">Restaurant</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        type="checkbox"
                                        name="business_types[]"
                                        value="tour_guide"
                                        id="tour_guide"
                                        {{ is_array(old('business_types')) && in_array('tour_guide', old('business_types')) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-gray-700">Tour/Guide</span>
                                </label>
                            </div>
                        </div>
                        <small class="text-gray-500 text-sm mt-1 block">Select at least one option</small>
                        @error('business_types')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Business Address -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Business Address</label>
                        <textarea
                            name="address"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="2"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Message (Optional)</label>
                        <textarea
                            name="message"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="3"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200"
                    >
                        Submit Agent Request
                    </button>
                </form>
            </div>

            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4 text-center text-gray-600">
                Our team will review your request and contact you via email.
            </div>
        </div>

    </div>
</div>
@endsection
