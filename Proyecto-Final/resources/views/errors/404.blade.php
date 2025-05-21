<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.include-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Error 404</title>
</head>

<body>
    @vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js', 'resources/js/app.js'])

    @include('partials.header')

    <main class="main__error">
        <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--error1" alt="">
        <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--error2" alt="">

        <div class="error-section">
            <h1>404</h1>
            <p>Ha ocurrido un error al intentar mostrar tu página</p>
            <p>Por favor, vuelve a intentarlo otra vez más tarde o si el error persiste contáctanos</p>
            <img src="{{ asset('storage/imagenesBugs/Bug7.png') }}" class="404-illustration" alt="Ilustración de error 404">
        </div>
    </main>

    @include('partials.footer')
</body>

</html>