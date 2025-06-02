<!--Estructura del formulario de registro de usuarios.-->
<main class="main__register">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('user.doRegister') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input class="form-control" type="text" name="name" placeholder="Introduce tu nombre de usuario">
            @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input class="form-control" type="text" name="email" placeholder="Introduce tu email">
            @error('email') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="input_password" name="password" placeholder="Introduce tu contraseña">
            @error('password') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="repeat_password">Repite la Contraseña:</label>
            <input type="password" class="form-control" id="input_repeat_password" name="password_repeat" placeholder="Repite la contraseña">
            @error('repeat_password') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="photo">Foto de Perfil</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
            <button type="reset" class="btn btn-danger">Resetar</button>
        </div>
    </form>
</main>