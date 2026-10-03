@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Company Settings
        </h1>

        <p class="text-gray-600 mt-1">
            Manage your business information used on invoices.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <form
            method="POST"
            action="{{ route('company.settings.update') }}"
        >

            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Company Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Company Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $company->name) }}"
                        required
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Business Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $company->email) }}"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $company->phone) }}"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address', $company->address) }}"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('address')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city', $company->city) }}"
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

                {{-- Country --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Country
                    </label>

                    <input
                        type="text"
                        name="country"
                        value="{{ old('country', $company->country) }}"
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

                {{-- Tax Number --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tax / VAT Number
                    </label>

                    <input
                        type="text"
                        name="tax_number"
                        value="{{ old('tax_number', $company->tax_number) }}"
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Save Company Settings
                </button>
            </div>

        </form>

    </div>

</div>

@endsection