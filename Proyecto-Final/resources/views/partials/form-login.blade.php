<!--Estructura del formulario de login.-->
@vite('resources/css/user_styles/login_styles.css')
<main class="main__login">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

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

        <label for="captcha">Verifica el Captcha para continuar:</label>
        <div>
            <span id="captcha-image">
                <img src="{{ captcha_src('flat') }}" id="captcha-img" style="border: 2px solid #0B6600; border-radius: 5px;" alt="captcha">
            </span>
            <button type="button" class="btn btn-primary btn-reload" id="reload">Recargar</button>
        </div>

        <div>
            <input type="text" name="captcha" class="form-control" placeholder="Introduce el captcha">
            @error('captcha') <small class="login_form__error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group align-self-center">
            <button type="submit" class="btn btn-primary">Acceder</button>
        </div>
    </form>

    <script>
        document.getElementById('reload').addEventListener('click', function () {
            fetch("{{ route('captcha.reload') }}")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha-img').src = data.captcha + '?' + Date.now();
                });
        });
    </script>
</main>