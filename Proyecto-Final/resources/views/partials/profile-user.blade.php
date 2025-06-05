<!--Estructura del perfil del usuario actual.-->
@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js', 'resources/js/app.js'])
<main class="main__profile-index">

    <div class="box__user">

        <h1 class="username__text">{{ $current_user->name }}</h1class>

        @if ($current_user->photo)
            <div class="profile-picture-display">
                <img src="{{ $current_user->photo) }}" class="profile-picture">
            </div>
        @else
            <div class="profile-picture-display">
                <img src="{{ asset('storage/' . 'default/Default.jpg') }}" class="profile-picture">
            </div>
        @endif

        <p class="userdata__text">Email: {{ $current_user->email }}</p>

        <div x-data="{}">
            <button @click="$refs.dialogLogout.showModal()" class="btn btn-danger btn-exit"><i class="bi bi-box-arrow-right icon-white"></i> Cerrar Sesión</button>
            <dialog x-ref="dialogLogout" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres cerrar sesión?</h2>

                <form action="{{ route('user.logout', ['id' => $current_user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success">Sí, Cerrar Sesión</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
        </div>

        <form action="{{ route('user.showUpdateUser', ['id' => $current_user->id]) }}" method="POST">
            @csrf
            @method('GET')
            <button type="submit" class="btn btn-success btn-update"><i class="bi bi-arrow-clockwise icon-white"></i> Actualizar Usuario</button>
        </form>

        @if ($current_user->isAdmin)
        <a href="{{ route('user.showAdminMenu') }}" class="btn btn-success btn-admin"><i class="bi bi-people icon-white"></i> Menú Administrador</a>
        @endif

        <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger"><i class="bi bi-trash icon-white"></i> Eliminar Usuario</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres eliminar tu usuario?</h2>

                <form action="{{ route('user.delete', ['id' => $current_user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success">Sí, Eliminar Usuario</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
        </div>

    </div>

</main>