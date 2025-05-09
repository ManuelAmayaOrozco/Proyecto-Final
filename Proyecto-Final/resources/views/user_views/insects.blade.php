<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.include-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <!-- HEADER -->
    @include('partials.header')

    <!-- POSTS -->
    @include('partials.insects_list')

    <!-- FOOTER -->
    @include('partials.footer')
</body>

</html>