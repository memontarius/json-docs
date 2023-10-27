<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('pageTitle')</title>
    @vite(['resources/js/app.js'])
    @vite(['resources/css/app.css'])
</head>
<body>
<div class="container mx-auto max-w-screen-md">
    <main class="relative">
        <h1 class="text-xl font-bold mt-12 pl-4 font-serif">@yield('pageTitle')</h1>
        <div class="mt-8 flex flex-col w-full items-center p-4">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
