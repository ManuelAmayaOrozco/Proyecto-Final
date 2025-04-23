@vite('resources/css/user_styles/register_styles.css')
<main class="main__register">
    <form id="termsForm" class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('insect.doRegisterInsect') }}" method="post" enctype="multipart/form-data">
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
        <div class="form-group">
            <label for="description">Descripción:</label>
            <input class="form-control" type="text" name="description" placeholder="Enter description">
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

            <script>
                const form = document.getElementById('termsForm');
                const checkbox = document.getElementById('checkboxTerms');
                const hidden = document.getElementById('hiddenTerms');

                form.addEventListener('submit', () => {
                    hidden.value = checkbox.checked ? '1' : '0';
                });
            </script>
        </div>
        <div class="form-group">
            <label for="photo">Insect Picture</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*">
            @error('photo') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </form>
</main>