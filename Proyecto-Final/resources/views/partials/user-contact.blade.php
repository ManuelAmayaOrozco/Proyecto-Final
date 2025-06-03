<!--Estructura de la página de contactos.-->
@vite(['resources/css/user_styles/user-index_styles.css', 'resources/css/user_styles/register_styles.css'])
<main class="main__contact">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--4" alt="">

    <section class="contact-content">

        <div class="contact-text">

            <h1 class="contact-title">Nuestra empresa</h1>

            <div class="contact-description">
                <p>¿Te gustaria unirte a nuestro grupo?</p>
                <p>Aquí en BugBuds siempre estamos buscando nueva gente que nos pueda ayudar en nuestros proyectos de conservación e investigación de todo tipo de insectos.</p>
                <p>Estamos completamente abiertos a cualquier personal, incluso aquellos sin formación laboral, tenemos diversos cursos disponibles para preparar a empleados y que puedan aprender a tratar a los diversos especimenes que mantenemos diariamente.</p>
                <p>Si no deseas trabajar con nosotros de forma permanente, siempre disponemos de puestos de voluntariado para aquellos que quieran apoyarnos en nuestras instalaciones y poder ver como trabajamos.</p>
                <p>Si trabajas para alguna empresa que está interesada en apoyar nuestra causa, pásales nuestro contacto o mándanos un correo.</p>
            </div>

        </div>

        <div class="welcome-image">
        <img src="{{ asset('storage/imagenesBugs/Bug6.png') }}" alt="Ilustración de contacto" class="contact-illustration" />
        </div>

    </section>

    @if (session('info'))
        <h5>{{ session('info') }}</h5>
    @endif

    <section class="contact-form">

        <form class="contact__register_form {{ $errors->any() ? 'contact__register_form-error' : '' }}" action="{{ route('user.doContact') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input class="form-control" type="text" name="name" placeholder="Escribe tu nombre">
                @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="surnames">Apellidos:</label>
                <input class="form-control" type="text" name="surnames" placeholder="Escribe tus apellidos">
                @error('surnames') <small class="register_form__error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input class="form-control" type="text" name="email" placeholder="Escribe tu email">
                @error('email') <small class="register_form__error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="phonenumber">Teléfono:</label>
                <input class="form-control" type="text" name="phonenumber" placeholder="Escribe tu número de teléfono">
                @error('phonenumber') <small class="register_form__error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="company">Compañia:</label>
                <input class="form-control" type="text" name="company" placeholder="Escribe el nombre de tu compañía">
            </div>
            <div class="form-group">
                <label for="message">Mensaje:</label>
                <textarea rows="6" class="form-control" name="message" placeholder="Escribe tu mensaje"></textarea>
                @error('message') <small class="register_form__error">{{ $message }}</small> @enderror
            </div>
            <div class="form-group d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary">Enviar</button>
                <button type="reset" class="btn btn-danger">Reiniciar</button>
            </div>
        </form>
    </section>

</main>