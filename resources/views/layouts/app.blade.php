<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>Smart Invoice Pro</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class=\"bg-gray-100\">

        <div class=\"flex min-h-screen\">

            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content -->
            <div class=\"flex-1 flex flex-col\">

                <!-- Topbar -->
                @include('components.topbar')

                <!-- Page Content -->
                <main class=\"p-6\">
                    {{ $slot ?? '' }}

                    @yield('content')
                </main>

            </div>

        </div>

    </body>
</html>