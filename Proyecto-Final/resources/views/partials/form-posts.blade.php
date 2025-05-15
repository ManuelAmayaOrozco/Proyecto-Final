<!--Estructura del formulario de registro de posts.-->
@push('scripts') 
    @vite(['resources/css/user_styles/register_styles.css', 'resources/js/editor.js'])
@endpush
<main class="main__register">
    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('post.doRegisterPost') }}" method="post" enctype="multipart/form-data" x-data="editor" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="title">Título:</label>
            <input class="form-control" type="text" name="title" placeholder="Escribe el título">
            @error('title') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>

        <input type="hidden" name="description" id="description">
        <div class="form-group">
            <label for="editor">Descripción:</label>
            <div id="editor" class="editor-input"></div>
            @error('description') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="insect">Insecto:</label>
            <select class="form-select" name="insect" id="insect">
                    <option value="" disabled selected>Elige un insecto</option>
                @foreach ($insects as $insect)
                    <option value="{{ $insect->id }}">{{ $insect->name }}</option>
                @endforeach
            </select>
            @error('insect') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="tags">Etiquetas:</label>
            <input class="form-control" type="text" name="tags" placeholder="Escribe las etiquetas (Separadas por ',')">
        </div>
        <div class="form-group">
            <label for="photo">Imagen:</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </form>

    <!--<div id="editor">-->

    <!--<div class="form-group" id="editor">
            <label for="description">Description:</label>
            <textarea rows="4" class="form-control" name="description" placeholder="Enter description"></textarea>
            @error('description') <small class="register_form__error">{{ $message }}</small> @enderror
    </div>-->
</main>