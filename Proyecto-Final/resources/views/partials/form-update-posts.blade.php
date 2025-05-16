<!--Estructura del formulario de actualización de posts.-->
@push('scripts') 
    @vite(['resources/css/user_styles/register_styles.css', 'resources/js/editor.js'])
@endpush
<main class="main__register">
    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('post.updatePost', ['id' => $post->id]) }}" method="post" enctype="multipart/form-data" x-data="editor({{ $post->description ? $post->description : '{}' }})" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="title">Título:</label>
            <input class="form-control" type="text" name="title" placeholder="Escribe el título" value="{{ $post->title }}">
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
                @foreach ($insects as $insect)
                    <option value="{{ $insect->id }}" {{ $post->related_insect == $insect->id ? 'selected' : '' }}>
                        {{ $insect->name }}
                    </option>
                @endforeach
            </select>
            @error('insect') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="tags">Etiquetas:</label>
            <input class="form-control" type="text" name="tags" placeholder="Escribe las etiquetas (Separadas por ',')" value="{{ $post->tags->pluck('name')->implode(', ') }}">
        </div>
        <div class="form-group">
            <label for="photo">Imagen:</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            @method('PUT')
            <button type="submit" class="btn btn-primary">Actualizar Post</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>

</main>