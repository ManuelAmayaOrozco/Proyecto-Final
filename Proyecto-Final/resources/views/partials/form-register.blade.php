<!--Estructura del formulario de registro de usuarios.-->
@vite('resources/css/user_styles/register_styles.css')
<main class="main__register">
    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('user.doRegister') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input class="form-control" type="text" name="name" placeholder="Enter name">
            @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input class="form-control" type="text" name="email" placeholder="Enter email">
            @error('email') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="input_password" name="password" placeholder="Password">
            @error('password') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="repeat_password">Repite la Contraseña:</label>
            <input type="password" class="form-control" id="input_repeat_password" name="password_repeat" placeholder="Repeat password">
            @error('repeat_password') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="photo">Profile Picture</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
            <button type="reset" class="btn btn-danger">Resetar</button>
        </div>
    </form>
</main>