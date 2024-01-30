<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
</head>

<body>
    <div>
        {{ $slot }}
    </div>
    @include('layouts.landing.js')
    @include('layouts.sweetalert')
</body>

</html>
