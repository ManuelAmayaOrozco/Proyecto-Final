@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js'])
<main class="main__insects-index">

    @if(!$current_user || !$current_user->banned)

        <div class="insect__searchbar">
            <form action="{{ route('insect.showInsects') }}" method="GET" id="insect-search-form">
                <select class="search-select" name="searchtype" id="insect-searchtype">
                        <option value="" disabled selected>Opción de búsqueda</option>
                        <option value="user">Usuario</option>
                        <option value="scientificName">Nombre Científico</option>
                        <option value="family">Familia</option>
                        <option value="diet">Dieta</option>
                        <option value="inDanger">En Peligro de Extinción</option>
                </select>
                <input name="search" placeholder="Busca un Insecto" class="search-bar" type="text" id="insect-search-input">
                <button class="search-button">Buscar</button>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchType = document.getElementById('insect-searchtype');
                const searchInput = document.getElementById('insect-search-input');

                searchType.addEventListener('change', function () {
                    const selected = this.value;

                    if (selected === 'inDanger') {
                        searchInput.disabled = true;
                        searchInput.value = '';
                        searchInput.placeholder = 'No se requiere búsqueda';
                    } else {
                        searchInput.disabled = false;
                        searchInput.type = 'text';
                        searchInput.placeholder = 'Busca un Insecto';
                    }
                });
            });
        </script>

        @if(Auth::check() && $current_user->isAdmin) 
        <a href="{{ route('insect.showRegisterInsect') }}" class="submit_post">
            REGISTER INSECT
        </a>
        @endif

        @forelse($insects as $insect)

            <div class="insect-box">

                <h2 class="insect-name" onclick="location.href=`{{ route('insect.showFullInsect', ['id' => $insect->id]) }}`">{{ $insect->name }}</h2>
                <h3 class="insect-scientificName">{{ $insect->scientificName }}</h2>
                <h3 class="insect-user">@foreach ($users as $user)

                                            @if($user->id == $insect->registered_by) 

                                                {{ $user->name }} 

                                            @endif 

                                        @endforeach
                                    </h3>
                <div class="insect-separator-box">
                <div class="insect-picture-display">
                    @foreach ($insect->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto de {{ $insect->name }}" class="insect-picture" onclick="location.href=`{{ route('insect.showFullInsect', ['id' => $insect->id]) }}`">
                    @endforeach
                </div>
                <p class="insect-info">Familia: {{ $insect->family }}</p>
                <p class="insect-info">Dieta: {{ $insect->diet }}</p>
                <p class="insect-info">Nº Documentados: {{ $insect->n_spotted }}</p>
                <p class="insect-info">Tamaño record: {{ $insect->maxSize }}</p>
                <p class="insect-info">En peligro de extinción: {{ $insect->protectedSpecies ? 'SI' : 'NO' }}</p>
                <p class="insect-text">{{ $insect->description }}</p>
                </div>

            </div>
        @empty
            <h2>Vaya no se encontraron insectos, vuelve a intentarlo.</h2>
        @endforelse

        <div class="custom-pagination">
            {{ $insects->links('vendor.pagination.tailwind') }}
        </div>

    @endif

    @if($current_user && $current_user->banned)
        <h2>Vaya parece que tu usuario está baneado.</h2>
        <h3>Si crees que pueda ser una equivocación ponte en contacto con nosotros.</h3>
    @endif

</main>