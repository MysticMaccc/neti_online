<html lang="en">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
</head>

<body>
    @include('layouts.landing.header')
    {{ $slot }}

    @include('layouts.landing.footer')
    @include('layouts.landing.js')
    @stack('scripts')

</body>

</html>
