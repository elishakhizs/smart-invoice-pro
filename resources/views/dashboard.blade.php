@extends('layouts.app')

@section('content')

    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6\">

        <div class=\"bg-white rounded-xl shadow p-6\">
            <h3 class=\"text-gray-500 text-sm\">
                Total Revenue
            </h3>

            <p class=\"text-3xl font-bold mt-2\">
                R45,000
            </p>
        </div>

        <div class=\"bg-white rounded-xl shadow p-6\">
            <h3 class=\"text-gray-500 text-sm\">
                Outstanding Invoices
            </h3>

            <p class=\"text-3xl font-bold mt-2\">
                18
            </p>
        </div>

        <div class=\"bg-white rounded-xl shadow p-6\">
            <h3 class=\"text-gray-500 text-sm\">
                Clients
            </h3>

            <p class=\"text-3xl font-bold mt-2\">
                42
            </p>
        </div>

        <div class=\"bg-white rounded-xl shadow p-6\">
            <h3 class=\"text-gray-500 text-sm\">
                Paid Invoices
            </h3>

            <p class=\"text-3xl font-bold mt-2\">
                126
            </p>
        </div>

    </div>

    <div class=\"mt-8 bg-white rounded-xl shadow p-6\">

        <h3 class=\"text-xl font-semibold mb-4\">
            Recent Activity
        </h3>

        <div class=\"space-y-4\">

            <div class=\"border-b pb-3\">
                Invoice #INV-1001 was paid.
            </div>

            <div class=\"border-b pb-3\">
                New client registered.
            </div>

            <div class=\"border-b pb-3\">
                Payment received from ABC Company.
            </div>

        </div>

    </div>

@endsection