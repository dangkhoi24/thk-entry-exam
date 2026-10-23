<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>THK Holdings Vietman Hanoi</title>
        {{-- individual CSS --}}
        {{-- @vite('resources/css/reset.css') --}}
        @vite('resources/scss/admin/base.scss')
        @vite('resources/scss/admin/side-menu.scss')
        @yield('custom_css')
    </head>
    <body>
        <div class="admin-layout">
            <x-admin.side-menu />
            <main class="admin-layout__main">
                @yield('main_contents')
            </main>
        </div>
        <x-toast />
        @vite('resources/js/admin/app.js')
        @yield('page_js')
    </body>
</html>
