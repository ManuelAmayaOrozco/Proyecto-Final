<!--Estructura del formulario de actualización de contraseña.-->
@vite('resources/css/user_styles/register_styles.css')
<main class="main__register">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('user.updatePassword', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" class="form-control" id="input_password" name="password" placeholder="Introduce tu nueva contraseña">
            @error('password') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="repeat_password">Repite la Nueva Contraseña:</label>
            <input type="password" class="form-control" id="input_repeat_password" name="password_repeat" placeholder="Repite tu nueva contraseña">
            @error('repeat_password') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            @method('PUT')
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>
</main>