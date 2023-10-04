<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="https://boolean.careers/favicon/favicon.ico" />
    <title>@yield('title')</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')

</body>

</html>
