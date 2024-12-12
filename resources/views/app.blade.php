<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <script>
            // ダークモードの状態を管理
            document.addEventListener('DOMContentLoaded', () => {
                const setDarkMode = (isDarkMode) => {
                    if (isDarkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                };

                // OSの設定を読み取る
                const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
                setDarkMode(prefersDarkScheme.matches);

                // OSの設定が変更されたとき
                prefersDarkScheme.addEventListener('change', (event) => {
                    setDarkMode(event.matches);
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased" :class="{ 'dark': isDarkMode }">
        @inertia
    </body>
</html>
