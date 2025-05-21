<!--Estructura del formulario de registro de comentarios.-->
@vite('resources/css/user_styles/register_styles.css')
<main class="main__register">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('comment.doRegisterComment', ['id' => request('id')]) }}" method="post">
        @csrf
        <div class="form-group">
            <label for="comment">Comentario:</label>
            <input class="form-control" type="text" name="comment" placeholder="Type your comment">
            @error('comment') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Registrar Comentario</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>
</main>