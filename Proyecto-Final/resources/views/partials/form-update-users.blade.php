<!--Estructura del formulario de actualización de usuarios.-->
@vite('resources/css/user_styles/register_styles.css')
<main class="main__register">
    <form class="register__register_form {{ $errors->any() ? 'register__register_form-error' : '' }}" action="{{ route('user.updateUser', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input class="form-control" type="text" name="name" placeholder="Enter name" value="{{ $user->name }}">
            @error('name') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input class="form-control" type="text" name="email" placeholder="Enter email" value="{{ $user->email }}">
            @error('email') <small class="register_form__error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label for="photo">Profile Picture</label>
            <input type="file" class="form-control" id="input_photo" name="photo" accept="image/*" value="{{ asset('storage/' . $user->photo) }}">
        </div>
        <div class="form-group d-flex justify-content-center gap-3">
            @method('PUT')
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </form>
</main>