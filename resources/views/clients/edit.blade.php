@extends('layouts.app')

@section('content')

```
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">
        Edit Client
    </h1>

    <form method="POST" action="{{ route('clients.update', $client) }}">
        @csrf
        @method('PUT')

        {{-- Client Name --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Client Name *
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $client->name) }}"
                required
                class="border border-gray-300 p-2 rounded-lg w-full"
            >

            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email', $client->email) }}"
                class="border border-gray-300 p-2 rounded-lg w-full"
            >

            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Phone
            </label>

            <input
                type="text"
                name="phone"
                value="{{ old('phone', $client->phone) }}"
                class="border border-gray-300 p-2 rounded-lg w-full"
            >

            @error('phone')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Company Name --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Company Name
            </label>

            <input
                type="text"
                name="company_name"
                value="{{ old('company_name', $client->company_name) }}"
                class="border border-gray-300 p-2 rounded-lg w-full"
            >

            @error('company_name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tax Number --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tax / VAT Number
                <span class="text-gray-400 font-normal">(Optional)</span>
            </label>

            <input
                type="text"
                name="tax_number"
                value="{{ old('tax_number', $client->tax_number) }}"
                placeholder="Enter tax or VAT number"
                class="border border-gray-300 p-2 rounded-lg w-full"
            >

            @error('tax_number')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Address --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Address
            </label>

            <textarea
                name="address"
                rows="3"
                class="border border-gray-300 p-2 rounded-lg w-full"
                placeholder="Street address"
            >{{ old('address', $client->address) }}</textarea>

            @error('address')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- City + Zip Code --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    City
                </label>

                <input
                    type="text"
                    name="city"
                    value="{{ old('city', $client->city) }}"
                    class="border border-gray-300 p-2 rounded-lg w-full"
                >

                @error('city')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    ZIP / Postal Code
                </label>

                <input
                    type="text"
                    name="postal_code"
                    value="{{ old('postal_code', $client->postal_code) }}"
                    class="border border-gray-300 p-2 rounded-lg w-full"
                >

                @error('postal_code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Country --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Country
            </label>

            <input
                type="text"
                name="country"
                value="{{ old('country', $client->country) }}"
                class="border border-gray-300 p-2 rounded-lg w-full"
            >

            @error('country')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3">

            <button
                type="submit"
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700"
            >
                Update Client
            </button>

            <a
                href="{{ route('clients.show', $client) }}"
                class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200"
            >
                Cancel
            </a>

        </div>

    </form>

</div>
```

@endsection
