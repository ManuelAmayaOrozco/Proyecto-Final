<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.include-styles')
    <title>Document</title>
</head>
<body>
    
    <h1>Correo de Contacto</h1>

    <p><strong>Nombre: </strong>{{ $data['name'] }}</p>
    <p><strong>Apellidos: </strong>{{ $data['surnames'] }}</p>
    <p><strong>Teléfono: </strong>{{ $data['phonenumber'] }}</p>
    <p><strong>Email: </strong>{{ $data['email'] }}</p>
    @if ($data['company'] != null)
    <p><strong>Compañía: </strong>{{ $data['company'] }}</p>
    @endif

    <p><strong>Mensaje: </strong></p>
    <p>{{ $data['message'] }}</p>

</body>
</html>