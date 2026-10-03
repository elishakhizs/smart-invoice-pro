<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Invoice Pro') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="min-h-screen">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-6 py-4">

                <div class="flex justify-between items-center">

                    <div>
                        <h1 class="text-xl font-bold">
                            Smart Invoice Pro
                        </h1>
                    </div>

                    <div class="text-sm text-gray-600">
                        {{ Auth::user()->name }}
                    </div>

                </div>

            </div>
        </header>

        {{-- Page Header --}}
        @isset($header)
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-6 py-4">
                    {{ $header }}
                </div>
            </div>
        @endisset

        {{-- Page Content --}}
        <main>
            {{ $slot }}
        </main>

    </div>

</body>

</html>