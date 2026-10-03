@extends('layouts.app')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Reports & Analytics
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Monitor your business performance and invoice activity.
            </p>
        </div>

    </div>


    <div class="flex flex-wrap gap-2 mb-5">

        <a
        href="{{ route('reports.index', [
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->toDateString()
        ]) }}"
        class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
            This Month
        </a>


        <a
        href="{{ route('reports.index', [
            'start_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'end_date' => now()->subMonth()->endOfMonth()->toDateString()
        ]) }}"
        class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
            Last Month
        </a>


        <a
        href="{{ route('reports.index', [
            'start_date' => now()->startOfYear()->toDateString(),
            'end_date' => now()->toDateString()
        ]) }}"
        class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
            This Year
        </a>


        <a
        href="{{ route('reports.index', [
            'start_date' => now()->subYear()->startOfYear()->toDateString(),
            'end_date' => now()->subYear()->endOfYear()->toDateString()
        ]) }}"
        class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
        Last Year
        </a>

    </div>

    {{-- Date Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form
            method="GET"
            action="{{ route('reports.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end"
        >

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Start Date
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                >

            </div>


            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    End Date
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                >

            </div>


            <div>

                <button type="submit"class="w-full md:w-auto px-5 py-2.5 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800">
                    Generate Report
                </button>
                <a href="javascript:window.print()" class="inline-flex items-center justify-center px-5 py-2.5 border border-red-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-red-500 hover:text-white">
                    Print Report
                </a>

            </div>

        </form>

    </div>


    {{-- Financial Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Total Invoiced
            </p>

            <p class="text-3xl font-bold mt-2">
                R{{ number_format($totalInvoiced, 2) }}
            </p>

        </div>


        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Total Paid
            </p>

            <p class="text-3xl font-bold mt-2">
                R{{ number_format($totalPaid, 2) }}
            </p>

        </div>


        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Outstanding
            </p>

            <p class="text-3xl font-bold mt-2">
                R{{ number_format($totalOutstanding, 2) }}
            </p>

        </div>


        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <p class="text-sm text-gray-500">
                Overdue
            </p>

            <p class="text-3xl font-bold mt-2">
                R{{ number_format($totalOverdue, 2) }}
            </p>

        </div>

        <div class="bg-white rounded-lg shadow p-6">

            <p class="text-sm font-medium text-gray-500">
                Partially Paid
            </p>

            <p class="mt-2 text-3xl font-bold text-yellow-600">
                {{ $partialInvoices }}
            </p>

        </div>

    </div>


    {{-- Revenue Analytics --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Revenue Analytics
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Monthly revenue from paid invoices.
                </p>
            </div>

        </div>

        <div class="text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
            -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </div>

    </div>


    @if($monthlyRevenue->count())

        @php
            $maxRevenue = $monthlyRevenue->max('total');
        @endphp

        <div class="mt-8 space-y-5">

            @foreach($monthlyRevenue as $revenue)

                @php
                    $percentage = $maxRevenue > 0
                        ? ($revenue->total / $maxRevenue) * 100
                        : 0;
                @endphp

                <div>

                    <div class="flex justify-between items-center mb-2">

                        <span class="text-sm font-medium text-gray-700">

                            {{ \Carbon\Carbon::createFromFormat(
                                'Y-m',
                                $revenue->month
                            )->format('F Y') }}

                        </span>

                        <span class="text-sm font-semibold text-gray-900">

                            R{{ number_format($revenue->total, 2) }}

                        </span>

                    </div>


                    <div class="w-full bg-gray-100 rounded-full h-3">

                        <div
                            class="bg-gray-900 h-3 rounded-full"
                            style="width: {{ $percentage }}%"
                        ></div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="py-10 text-center text-gray-500">
            No revenue data available for this period.
        </div>

    @endif

</div>

    {{-- Invoice Status Breakdown --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <h2 class="text-lg font-bold text-gray-900">
        Invoice Status Breakdown
    </h2>

    <p class="text-sm text-gray-500 mt-1">
        Distribution of invoices during the selected period.
    </p>


    @php
        $statusTotal = max($totalInvoices, 1);
    @endphp


    <div class="mt-6 space-y-5">

        {{-- Draft --}}
        <div>

            <div class="flex justify-between mb-2">

                <span class="text-sm text-gray-600">
                    Draft
                </span>

                <span class="text-sm font-medium">
                    {{ $draftInvoices }}
                </span>

            </div>

            <div class="w-full bg-gray-100 rounded-full h-2">

                <div
                    class="bg-gray-400 h-2 rounded-full"
                    style="width: {{ ($draftInvoices / $statusTotal) * 100 }}%"
                ></div>

            </div>

        </div>


        {{-- Sent --}}
        <div>

            <div class="flex justify-between mb-2">

                <span class="text-sm text-gray-600">
                    Sent
                </span>

                <span class="text-sm font-medium">
                    {{ $sentInvoices }}
                </span>

            </div>

            <div class="w-full bg-gray-100 rounded-full h-2">

                <div
                    class="bg-blue-500 h-2 rounded-full"
                    style="width: {{ ($sentInvoices / $statusTotal) * 100 }}%"
                ></div>

            </div>

        </div>


        {{-- Paid --}}
        <div>

            <div class="flex justify-between mb-2">

                <span class="text-sm text-gray-600">
                    Paid
                </span>

                <span class="text-sm font-medium">
                    {{ $paidInvoices }}
                </span>

            </div>

            <div class="w-full bg-gray-100 rounded-full h-2">

                <div
                    class="bg-green-500 h-2 rounded-full"
                    style="width: {{ ($paidInvoices / $statusTotal) * 100 }}%"
                ></div>

            </div>

        </div>

    </div>

</div><br>


    {{-- Invoice Statistics --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <h2 class="text-lg font-bold text-gray-900">
            Invoice Overview
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">

            <div>
                <p class="text-sm text-gray-500">
                    Total
                </p>

                <p class="text-2xl font-bold mt-1">
                    {{ $totalInvoices }}
                </p>
            </div>


            <div>
                <p class="text-sm text-gray-500">
                    Draft
                </p>

                <p class="text-2xl font-bold mt-1">
                    {{ $draftInvoices }}
                </p>
            </div>


            <div>
                <p class="text-sm text-gray-500">
                    Sent
                </p>

                <p class="text-2xl font-bold mt-1">
                    {{ $sentInvoices }}
                </p>
            </div>


            <div>
                <p class="text-sm text-gray-500">
                    Paid
                </p>

                <p class="text-2xl font-bold mt-1">
                    {{ $paidInvoices }}
                </p>
            </div>

        </div>

    </div><br>


    {{-- Monthly Revenue --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <h2 class="text-lg font-bold text-gray-900">
            Monthly Revenue
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Revenue generated from paid invoices.
        </p>


        <div class="overflow-x-auto mt-6">

            @if($monthlyRevenue->count())

                <table class="w-full text-sm">

                    <thead>

                        <tr class="border-b text-left text-gray-500">

                            <th class="pb-3">
                                Month
                            </th>

                            <th class="pb-3 text-right">
                                Revenue
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($monthlyRevenue as $revenue)

                            <tr class="border-b last:border-0">

                                <td class="py-4 text-gray-700">

                                    {{ \Carbon\Carbon::createFromFormat(
                                        'Y-m',
                                        $revenue->month
                                    )->format('F Y') }}

                                </td>


                                <td class="py-4 text-right font-semibold">

                                    R{{ number_format(
                                        $revenue->total,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <p class="text-gray-500 py-6 text-center">
                    No paid invoices found for this period.
                </p>

            @endif

        </div>

    </div><br>


    {{-- Revenue By Client --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <h2 class="text-lg font-bold text-gray-900">
            Revenue By Client
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Clients ranked by paid invoice value.
        </p>


        <div class="overflow-x-auto mt-6">

            <table class="w-full text-sm">

                <thead>

                    <tr class="border-b text-left text-gray-500">

                        <th class="pb-3">
                            Client
                        </th>

                        <th class="pb-3">
                            Company
                        </th>

                        <th class="pb-3 text-right">
                            Paid Revenue
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($clientRevenue as $client)

                        @if($client->paid_total > 0)

                            <tr class="border-b last:border-0">

                                <td class="py-4 font-medium text-gray-900">
                                    {{ $client->name }}
                                </td>

                                <td class="py-4 text-gray-600">
                                    {{ $client->company_name ?? '—' }}
                                </td>

                                <td class="py-4 text-right font-semibold">
                                    R{{ number_format(
                                        $client->paid_total,
                                        2
                                    ) }}
                                </td>

                            </tr>

                        @endif

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="py-6 text-center text-gray-500"
                            >
                                No client revenue found for this period.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
<style>
    @media print {

        nav,
        aside,
        header,
        button,
        a {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .shadow-sm,
        .shadow {
            box-shadow: none !important;
        }

        .rounded-2xl {
            border-radius: 0 !important;
        }

    }
</style>

@endsection