@extends('user.layout')

@section('content')

<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <h5>Total Bookings</h5>
                <h2>5</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <h5>Active Bookings</h5>
                <h2>2</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <h5>Total Paid</h5>
                <h2>Rs. 12,500</h2>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm">
    <div class="card-header">
        My Recent Bookings
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Hotel</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Hotel Everest</td>
                    <td>Deluxe Room</td>
                    <td><span class="badge bg-success">Confirmed</span></td>
                    <td>Rs. 3,000</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Hotel Annapurna</td>
                    <td>Standard Room</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>Rs. 2,500</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
