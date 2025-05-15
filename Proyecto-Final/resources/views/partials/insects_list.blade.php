<!--Estructura de la lista de insectos.-->
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
            REGISTRAR UN INSECTO
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
                    <p class="insect-info-start">Familia: <span class="insect-info">{{ $insect->family }}</span></p>
                    <p class="insect-info-start">Dieta: <span class="insect-info">{{ $insect->diet }}</span></p>
                    <p class="insect-info-start">Nº Documentados: <span class="insect-info">{{ $insect->n_spotted }}</span></p>
                    <p class="insect-info-start">Tamaño record: <span class="insect-info">{{ $insect->maxSize }} cm</span></p>
                    <p class="insect-info-start">En peligro de extinción: <span class="insect-info">{{ $insect->protectedSpecies ? 'SI' : 'NO' }}</span></p>
                    <div class="insect-text">
                        <script class="post-description-json" type="application/json">
                            {!! $insect->description ?: json_encode(['blocks' => []]) !!}
                        </script>
                    </div>
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

    <!-- Cargar librería -->
    <script src="https://cdn.jsdelivr.net/npm/editorjs-html-revised@3.3.0/build/edjsHTML.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.post-description-json').forEach((scriptTag) => {
                const postBox = scriptTag.closest('.insect-box');

                try {
                    const data = JSON.parse(scriptTag.textContent);
                    if (!data || !Array.isArray(data.blocks)) return;

                    // Configuración del parser para las listas y otros bloques
                    const parser = edjsHTML({
                        list: (block) => {
                            const tag = block.data.style === 'ordered' ? 'ol' : 'ul';
                            const items = block.data.items.map(item => `<li>${item.content}</li>`).join('');
                            return `<${tag}>${items}</${tag}>`;
                        },
                        checklist: (block) => {
                            const items = block.data.items.map(item => {
                                const checked = item.meta.checked ? 'checked' : '';
                                return `<li><input type="checkbox" ${checked} disabled> ${item.content}</li>`;
                            }).join('');
                            return `<ul class="checklist">${items}</ul>`;
                        },
                        quote: (block) => {
                            return `<blockquote>${block.data.text} <footer>— ${block.data.caption}</footer></blockquote>`;
                        },
                        header: (block) => {
                            const level = block.data.level || 2;
                            return `<h${level}>${block.data.text}</h${level}>`;
                        }
                    });

                    const html = parser.parse(data);
                    const descriptionContainer = document.createElement('div');
                    descriptionContainer.classList.add('post-description');
                    descriptionContainer.innerHTML = html.join('');
                    const postText = postBox.querySelector('.insect-text');
                    if (postText) {
                        postText.appendChild(descriptionContainer); // ✅ Ahora sí se coloca dentro de .post-text
                    }
                } catch (error) {
                    console.error('Error al convertir descripción Editor.js:', error);
                }
            });
        });
    </script>

</main>