@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Partner With Us</h4>
                    <small>Hotel / Restaurant Agent Request</small>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('agent.request.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Business Name</label>
                            <input type="text" name="business_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Owner / Manager Name</label>
                            <input type="text" name="owner_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Business Type (Select all that apply)</label>
                            <div class="border rounded p-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="business_types[]" value="hotel" id="hotel">
                                    <label class="form-check-label" for="hotel">
                                        Hotel
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="business_types[]" value="restaurant" id="restaurant">
                                    <label class="form-check-label" for="restaurant">
                                        Restaurant
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="business_types[]" value="both" id="both">
                                    <label class="form-check-label" for="both">
                                        Both (Hotel & Restaurant)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="business_types[]" value="tour_guide" id="tour_guide">
                                    <label class="form-check-label" for="tour_guide">
                                        Tour/Guide
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Select at least one option</small>
                            @error('business_types')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Business Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message (Optional)</label>
                            <textarea name="message" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Submit Agent Request
                        </button>
                    </form>
                </div>

                <div class="card-footer text-muted text-center">
                    Our team will review your request and contact you via email.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
