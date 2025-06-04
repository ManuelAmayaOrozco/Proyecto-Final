<!--Estructura de la información detallada de un insecto.-->
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
@endpush
@push('scripts') 
    @vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js'])
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
@endpush
<main class="main__full-insect-index">

    <div class="insect-box">

        <h2 class="insect-name">{{ $insect->name }}</h2>
        <h3 class="insect-scientificName">{{ $insect->scientificName }}</h2>
        <h3 class="insect-user">{{ $insect_user }}</h3>
        <div class="insect-separator-box">
        <div class="insect-picture-display">
            @foreach ($insect->photos as $photo)
                <img src="{{ $photo->path }}" alt="Foto de {{ $insect->name }}" class="insect-picture">
            @endforeach
        </div>
            <p class="insect-info-start">Familia: <span class="insect-info">{{ $insect->family }}</span></p>
            <p class="insect-info-start">Dieta: <span class="insect-info">{{ $insect->diet }}</span></p>
            <p class="insect-info-start">Nº Documentados: <span class="insect-info">{{ $insect->n_spotted }}</span></p>
            <p class="insect-info-start">Tamaño record: <span class="insect-info">{{ $insect->maxSize }}</span></p>
            <p class="insect-info-start">En peligro de extinción: <span class="insect-info">{{ $insect->protectedSpecies ? 'SI' : 'NO' }}</span></p>

            @php
                $postsWithCoordinates = collect($insect_posts)->filter(function ($post) {
                    return $post->latitude && $post->longitude;
                });
            @endphp

            @if ($postsWithCoordinates->isNotEmpty())
                <div class="post-map">
                    <p class="insect-info-start">Localizaciones:</p>
                    <div id="map" style="height: 400px;"></div>
                </div>
            @endif

            <div class="insect-text">
                <script id="post-description-json" type="application/json">
                    {!! $insect->description !!}
                </script>
            </div>
        </div>

        @if($current_user && $current_user->isAdmin)
        <form action="{{ route('insect.showUpdateInsect', ['id' => $insect->id]) }}" method="POST">
            @csrf
            @method('GET')
            <button type="submit" class="btn btn-like btn-update"><i class="bi bi-arrow-clockwise icon-white"></i> Actualizar Insecto</button>
        </form>

        <div x-data="{}">
            <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger"><i class="bi bi-trash icon-white"></i> Eliminar Insecto</button>
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

    <!-- Cargar librería -->
    <script src="https://cdn.jsdelivr.net/npm/editorjs-html-revised@3.3.0/build/edjsHTML.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof window.edjsHTML !== 'function') {
                console.error("❌ edjsHTML no está disponible.");
                return;
            }

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

            const scriptTag = document.getElementById('post-description-json');

            try {
                const content = JSON.parse(scriptTag.textContent);
                const html = parser.parse(content);

                const container = document.createElement('div');
                container.classList.add('editorjs-content');
                container.innerHTML = html.join('');
                scriptTag.insertAdjacentElement('afterend', container);
            } catch (e) {
                console.error("⚠️ Error al procesar el contenido de Editor.js:", e);
            }

            // Inicializa el mapa solo si las coordenadas están disponibles
            const posts = @json($postsWithCoordinates->values());
            const postBaseUrl = "{{ url('/posts/fullPost') }}"; // <- Ajusta según tu ruta real

            if (posts.length > 0) {
                const map = L.map('map').setView([posts[0].latitude, posts[0].longitude], 6);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const bugIcon = L.icon({
                    iconUrl: '{{ asset('storage/imagenesBugs/marker-icon.png') }}',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                posts.forEach(post => {
                    const postUrl = `${postBaseUrl}/${post.id}`;

                    L.marker([post.latitude, post.longitude], { icon: bugIcon })
                        .addTo(map)
                        .bindPopup(`<a href="${postUrl}" target="_blank">Ver Post Relacionado #${post.id}</a>`);
                });

                const bounds = posts.map(p => [p.latitude, p.longitude]);
                map.fitBounds(bounds);
            }
        });
    </script>

</main>