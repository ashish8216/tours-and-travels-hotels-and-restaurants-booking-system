@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 mt-10 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

    <form>
        <input type="email" placeholder="Email"
               class="w-full border p-2 mb-4">

        <input type="password" placeholder="Password"
               class="w-full border p-2 mb-4">

        <button class="bg-blue-600 text-white w-full py-2 rounded">
            Login
        </button>
    </form>
</div>
@endsection
