@vite('resources/css/user_styles/login_styles.css')
<main class="main__login">
    <form class="login__login_form {{ $errors->any() ? 'login__login_form-error' : '' }}" action="{{ route('login') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input class="form-control" type="text" id="input_email" name="email" placeholder="Introduce tu email">
            @error('email') <small class="login_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="input_password" name="password" placeholder="Introduce tu contraseña">
            @error('password') <small class="login_form__error">{{ $message }}</small> @enderror
            @error('credentials') <small class="login_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group align-self-center">
            <button type="submit" class="btn btn-primary">Acceder</button>
        </div>
    </form>
</main>