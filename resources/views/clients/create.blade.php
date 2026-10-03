@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">
        Add Client
    </h1>

    <form method="POST" action="{{ route('clients.store') }}">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <input type="text" name="name" placeholder="Client Name"
                   class="border p-2 rounded w-full" required>

            <input type="email" name="email" placeholder="Email"
                   class="border p-2 rounded w-full">

            <input type="text" name="phone" placeholder="Phone"
                   class="border p-2 rounded w-full">

            <input type="text" name="company_name" placeholder="Company"
                   class="border p-2 rounded w-full">

        </div>

        <button class="mt-6 bg-indigo-600 text-white px-4 py-2 rounded">
            Save Client
        </button>

    </form>

</div>

@endsection