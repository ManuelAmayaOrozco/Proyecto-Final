<!--Estructura del menú de administración de usuarios.-->
@vite(['resources/js/alpine.js', 'resources/js/app.js'])
<main class="main__posts-index">

    @forelse($users as $user)

        <div class="user-box">

            <h2 class="user-name">{{ $user->name }}</h2>

            @if ($user->photo)
                <div class="profile-picture-display-small">
                    <img src="{{ asset('storage/' . $user->photo) }}" class="profile-picture">
                </div>
            @else
                <div class="profile-picture-display-small">
                    <img src="{{ asset('storage/' . 'default/Default.jpg') }}" class="profile-picture">
                </div>
            @endif

            @if(!$user->isAdmin  && !$user->banned || $user->id == $current_user->id)
            <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-success btn-update"><i class="bi bi-lock icon-white"></i> Cambiar Contraseña</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres cambiar la contraseña de este usuario?</h2>

                <form action="{{ route('user.showUpdatePassword', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn btn-success">Sí, cambiar contraseña</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
            </div>
            @endif

            @if(!$user->isAdmin && !$user->banned)
            <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-success btn-admin"><i class="bi bi-shield-lock icon-white"></i> Dar permisos de Administrador</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres dar permisos de Administrador este usuario?</h2>
                <p>Esta acción no se podrá deshacer.</p>

                <form action="{{ route('user.makeAdmin', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Dar permisos de Administrador</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
            </div>
            @endif

            @if($user->isAdmin)
            <h4>Este usuario ya es Administrador.</h4>
            @endif

            @if(!$user->isAdmin && $user->id != $current_user->id && !$user->banned)
            <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger btn-ban"><i class="bi bi-ban icon-white"></i> Banear Usuario</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres banear este usuario?</h2>

                <form action="{{ route('user.banUser', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Sí, Banear Usuario</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
            </div>
            @endif

            @if(!$user->isAdmin && $user->id != $current_user->id && $user->banned)
            <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-success btn-ban"><i class="bi bi-check-circle icon-white"></i> Desbanear Usuario</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres desbanear este usuario?</h2>

                <form action="{{ route('user.unbanUser', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Sí, Desbanear Usuario</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
            </div>
            @endif

            @if(!$user->isAdmin && $user->id != $current_user->id)
            <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger"><i class="bi bi-person-x icon-white"></i> Eliminar Usuario</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres eliminar este usuario?</h2>

                <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success">Sí, Eliminar Usuario</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
            </div>
            @endif

        </div>
    @empty
        <h2>Vaya no se encontraron usuarios, vuelve a intentarlo.</h2>
    @endforelse

</main>