<!--Estructura del formulario de registro de insectos.-->
@push('scripts') 
    @vite(['resources/css/user_styles/register_styles.css', 'resources/js/editor.js'])
@endpush
<main class="main__register">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('insect.doRegisterInsect') }}" method="post" enctype="multipart/form-data" x-data="editor" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input class="form-control" type="text" name="name" placeholder="Introduce el Nombre del insecto">
            @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="scientificName">Nombre Científico:</label>
            <input class="form-control" type="text" name="scientificName" placeholder="Introduce el Nombre Científico del insecto">
            @error('scientificName') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="family">Familia:</label>
            <input class="form-control" type="text" name="family" placeholder="Introduce la Familia del insecto">
            @error('family') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="diet">Dieta:</label>
            <input class="form-control" type="text" name="diet" placeholder="Introduce la Dieta del insecto">
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
            <input class="form-control" type="number" name="n_spotted" min="0" placeholder="Introduce la Cantidad de individuos documentados del insecto">
            @error('n_spotted') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="maxSize">Tamaño Record:</label>
            <input class="form-control" type="number" name="maxSize" min="0" step="0.01" placeholder="Introduce el Tamaño Máximo del insecto">
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
            @error('photo.*') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Registrar Insecto</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>
</main>