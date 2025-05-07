@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/app.js'])
<main class="main__full-insect-index">

    <div class="insect-box">

        <h2 class="insect-name">{{ $insect->name }}</h2>
        <h3 class="insect-scientificName">{{ $insect->scientificName }}</h2>
        <h3 class="insect-user">{{ $insect_user }}</h3>
        <div class="insect-separator-box">
        <div class="insect-picture-display">
            @foreach ($insect->photos as $photo)
                <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto de {{ $insect->name }}" class="insect-picture">
            @endforeach
        </div>
        <p class="insect-info">Familia: {{ $insect->family }}</p>
        <p class="insect-info">Dieta: {{ $insect->diet }}</p>
        <p class="insect-info">Nº Documentados: {{ $insect->n_spotted }}</p>
        <p class="insect-info">Tamaño record: {{ $insect->maxSize }}</p>
        <p class="insect-info">En peligro de extinción: {{ $insect->protectedSpecies ? 'SI' : 'NO' }}</p>
        <p class="insect-text">{{ $insect->description }}</p>
        </div>

        @if($current_user && $current_user->isAdmin)
        <form action="{{ route('insect.showUpdateInsect', ['id' => $insect->id]) }}" method="POST">
            @csrf
            @method('GET')
            <button type="submit" class="btn btn-like">Actualizar Insecto</button>
        </form>

        <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger">Eliminar Insecto</button>
            <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
            
                <h2>¿Estas seguro de que quieres eliminar el insecto del registro?</h2>

                <form action="{{ route('insect.deleteInsect', ['id' => $insect->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success">Sí, Eliminar Insecto</button>
                </form>

                <form method="dialog">

                    <button class="btn btn-danger">Cancelar</button>

                </form>

            </dialog>
        </div>
        @endif

    </div>

</main>