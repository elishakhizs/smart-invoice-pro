@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto py-8">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900">
            Edit Payment
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Invoice #{{ $payment->invoice->invoice_number }}
        </p>

    </div>

    <div class="bg-white rounded-lg shadow p-6">

        <form
            method="POST"
            action="{{ route('payments.update', $payment) }}"
        >

            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Payment Amount
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="amount"
                        value="{{ old('amount', $payment->amount) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >

                    @error('amount')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Payment Date
                    </label>

                    <input
                        type="date"
                        name="payment_date"
                        value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >

                    @error('payment_date')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Payment Method
                    </label>

                    <select
                        name="payment_method"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >
                        <option value="">Select method</option>

                        <option value="Cash"
                            {{ old('payment_method', $payment->payment_method) === 'Cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="Bank Transfer"
                            {{ old('payment_method', $payment->payment_method) === 'Bank Transfer' ? 'selected' : '' }}>
                            Bank Transfer
                        </option>

                        <option value="EFT"
                            {{ old('payment_method', $payment->payment_method) === 'EFT' ? 'selected' : '' }}>
                            EFT
                        </option>

                        <option value="Card"
                            {{ old('payment_method', $payment->payment_method) === 'Card' ? 'selected' : '' }}>
                            Card
                        </option>

                        <option value="Other"
                            {{ old('payment_method', $payment->payment_method) === 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Reference
                    </label>

                    <input
                        type="text"
                        name="reference"
                        value="{{ old('reference', $payment->reference) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >
                </div>

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    >{{ old('notes', $payment->notes) }}</textarea>

                </div>

            </div>

            <div class="mt-6 flex items-center gap-3">

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    Update Payment
                </button>

                <a
                    href="{{ route('invoices.show', $payment->invoice) }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection