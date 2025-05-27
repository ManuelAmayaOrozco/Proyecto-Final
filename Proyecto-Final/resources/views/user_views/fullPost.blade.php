<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.include-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Post Completo de BugBuds</title>
</head>

<body>
    <!-- HEADER -->
    @include('partials.header')

    <!-- POSTS -->
    @include('partials.fullPost-Info')

    <!-- FOOTER -->
    @include('partials.footer')

    @stack('scripts')
    @stack('styles')

</body>

</html>