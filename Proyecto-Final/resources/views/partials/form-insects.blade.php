<!--Estructura del formulario de registro de insectos.-->
@push('scripts') 
    @vite(['resources/css/user_styles/register_styles.css', 'resources/js/editor.js'])
@endpush
<main class="main__register">
    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('insect.doRegisterInsect') }}" method="post" enctype="multipart/form-data" x-data="editor" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input class="form-control" type="text" name="name" placeholder="Enter name">
            @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="scientificName">Nombre Científico:</label>
            <input class="form-control" type="text" name="scientificName" placeholder="Enter scientific name">
            @error('scientificName') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="family">Familia:</label>
            <input class="form-control" type="text" name="family" placeholder="Enter family">
            @error('family') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="diet">Dieta:</label>
            <input class="form-control" type="text" name="diet" placeholder="Enter diet">
            @error('diet') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>

        <input type="hidden" name="description" id="description">
        <div class="form-group">
            <label for="editor">Descripción:</label>
            <div id="editor" class="editor-input"></div>
            @error('description') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="n_spotted">Nº Documentados:</label>
            <input class="form-control" type="number" name="n_spotted" min="0" placeholder="Enter nº spotted">
            @error('n_spotted') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="maxSize">Tamaño Record:</label>
            <input class="form-control" type="number" name="maxSize" min="0" step="0.01" placeholder="Enter max size">
            @error('maxSize') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="protectedSpecies">En peligro de extinción:
                <input type="hidden" name="protectedSpecies" id="hiddenTerms">
                <input class="form-checkbox" type="checkbox" id="checkboxTerms" value="true">
            </label>
        </div>
        <div class="form-group">
            <label for="photo">Imágenes del Insecto:</label>
            <input type="file" class="form-control" id="input_photo" name="photo[]" accept="image/*" multiple>
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Registrar Insecto</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>
</main>