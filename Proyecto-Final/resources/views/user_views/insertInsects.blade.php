<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.include-styles')
    <title>Registra un Insecto BugBuds</title>
</head>

<body>
    <!-- HEADER -->
    @include('partials.header')

    <!-- LOGIN FORM -->
    @include('partials.form-insects')

    <!-- FOOTER -->
    @include('partials.footer')

    @stack('scripts')

    @vite(['resources/js/alpine.js'])
</body>

</html>