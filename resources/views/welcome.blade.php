<!DOCTYPE html>
<html lang="{{ str\_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GDS-Control</title> <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
</head>
<body class="antialiased">
<div
    class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"> {{-- @if (Route::has('login'))--}} {{-- <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">--}} {{-- @auth--}} {{-- <a href="{{ url('/dashboard') }}"--}} {{-- class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>--}} {{-- @else--}} {{-- <a href="{{ route('login') }}"--}} {{-- class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log--}} {{-- in</a>--}}
    {{-- @if (Route::has('register'))--}} {{-- <a href="{{ route('register') }}"--}} {{-- class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>--}} {{-- @endif--}} {{-- @endauth--}} {{-- </div>--}} {{-- @endif--}}

    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
            <x-application-large-logo class="w-20 h-20 fill-current text-gray-500"/>
        </div>
        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8"> @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div><h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Go to
                                    dashboard</h2></div>
                            <!-- <div class="h-16 w-16 bg-red-50 dark:bg-white-800/20 flex items-center justify-center rounded-full"> <img width="48" height="48" src="https://img.icons8.com/parakeet/48/dashboard.png" alt="dashboard"/> </div> -->
                        </a>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">

                            <div><h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Log out</h2></div>
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}"
                              style="display: none;"> @csrf </form>
                    @else

                        <a href="{{ route('login') }}"
                           class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">

                            <div><h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Login</h2></div>
                            <!-- <div> <img width="48" height="48" src="https://img.icons8.com/fluency/48/login-rounded-right.png" alt="login-rounded-right"/> </div> -->
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">

                                <div><h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Register</h2>
                                </div>
                                <!-- <div> <img width="60" height="60" src="https://img.icons8.com/ultraviolet/40/add-user-male.png" alt="add-user-male"/> </div> -->
                            </a>
                        @endif
                    @endauth
                @endif </div>
        </div>
        @if (env('APP_DEBUG') == 'true')

            <div class="flex justify-center mt-16 px-0 sm:i tems-center sm:justify-between">
                <div class="text-center text-sm sm:text-left"> &nbsp;</div>
                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0"> Laravel
                    v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP\_VERSION }})
                </div>
            </div>
        @endif </div>
</body>
</html>
