<!--Estructura del formulario de login.-->
<main class="main__verify">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">

    <div class="verify-content">
        <h3>Verifica tu correo antes de continuar.</h3>
        <p>Si no has podido recibir ningún correo, haz click en el botón de abajo para reenviarlo.</p>

        <form action="{{ route('verification.send') }}" method="post">
            @csrf
            <div class="form-group align-self-center">
                <button type="submit" class="btn btn-primary">Reenviar Correo</button>
            </div>
        </form>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
</div>

</main>