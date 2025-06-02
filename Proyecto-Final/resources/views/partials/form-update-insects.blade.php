<!--Estructura del formulario de actualización de insectos.-->
@push('scripts') 
    @vite('resources/js/editor.js')
@endpush
<main class="main__register">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">

    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('insect.updateInsect', ['id' => $insect->id]) }}" method="post" enctype="multipart/form-data" x-data="editor({{ $insect->description ? $insect->description : '{}' }})" @submit.prevent="beforeSend" id="post-form">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input class="form-control" type="text" name="name" placeholder="Introduce el Nombre del insecto" value="{{ $insect->name }}">
            @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="scientificName">Nombre Científico:</label>
            <input class="form-control" type="text" name="scientificName" placeholder="Introduce el Nombre Científico del insecto" value="{{ $insect->scientificName }}">
            @error('scientificName') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="family">Familia:</label>
            <input class="form-control" type="text" name="family" placeholder="Introduce la Familia del insecto" value="{{ $insect->family }}">
            @error('family') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="diet">Dieta:</label>
            <input class="form-control" type="text" name="diet" placeholder="Introduce la Dieta del insecto" value="{{ $insect->diet }}">
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
            <input class="form-control" type="number" name="n_spotted" min="0" placeholder="Introduce la Cantidad de individuos documentados del insecto" value="{{ $insect->n_spotted }}">
            @error('n_spotted') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="maxSize">Tamaño Record:</label>
            <input class="form-control" type="number" name="maxSize" min="0" step="0.01" placeholder="Introduce el Tamaño Máximo del insecto" value="{{ $insect->maxSize }}">
            @error('maxSize') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="protectedSpecies">En peligro de extinción:
                <input type="hidden" name="protectedSpecies" id="hiddenTerms">
                <input class="form-checkbox" type="checkbox" id="checkboxTerms" {{ $insect->protectedSpecies ? 'checked' : '' }}>
            </label>

            <script>
                const checkbox = document.getElementById('checkboxTerms');
                const hidden = document.getElementById('hiddenTerms');

                form.addEventListener('submit', () => {
                    hidden.value = checkbox.checked ? '1' : '0';
                });
            </script>
        </div>
        <div class="form-group">
            <label for="photo">Imágenes del Insecto:</label>
            <input type="file" class="form-control" id="input_photo" name="photo[]" accept="image/*" multiple>
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
            @error('photo.*') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            @method('PUT')
            <button type="submit" class="btn btn-primary">Actualizar Insecto</button>
            <button type="reset" class="btn btn-danger">Resetear</button>
        </div>
    </form>
</main>