<!--Estructura de la información detallada de un post.-->
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
<main class="main__full-posts-index">

    <div class="post-box">

        <h2 class="post-title">{{ $post->title }}</h2>
        <h4 class="post-insect" onclick="location.href=`{{ route('insect.showFullInsect', ['id' => $post_insect_id]) }}`">{{ $post_insect }}</h3>
        <h3 class="post-user">{{ $post_user }}</h3>
        <div class="post-separator-box">
        <div class="post-picture-display">
            <img src="{{ asset('storage/' . $post->photo) }}" class="post-picture">
        </div>
        @if($post->latitude && $post->longitude)
            <div class="post-map">
                <p class="likes-text">Localización:</p>
                <div id="map"></div>
            </div>
        @endif
        <p class="post-tags">
                @foreach ($post->tags as $tag)
                    <span class="tag" onclick="location.href=`{{ route('post.showPosts', ['tagId' => $tag->id]) }}`">{{ ucfirst($tag->name) }}</span>
                @endforeach
        </p>
        <script id="post-description-json" type="application/json">
            {!! $post->description !!}
        </script>
        <p class="post-date">{{ $post->publish_date }}</p>
        </div>

        <p class="likes-text">Likes: {{ $post->n_likes }}</p>
        <p class="likes-text">Comentarios: {{ count($comments) }}</p>

        @if($current_user)
            @if(!$post->likedByUsers->contains($current_user->id))
                <form action="{{ route('post.like', ['id' => $post->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-like"><i class="bi bi-hand-thumbs-up icon-white"></i> Like</button>
                </form>
            @else
                <form action="{{ route('post.dislike', ['id' => $post->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger"><i class="bi bi-hand-thumbs-down icon-white"></i> Quitar Like</button>
                </form>
            @endif

            @if ($isFavorite == null)
            <form action="{{ route('post.newFavorite', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-warning btn-lg mb-2"><i class="bi bi-star"></i> Agregar a Favoritos</button>
            </form>
            @endif

            @if ($isFavorite != null)
            <form action="{{ route('post.removeFavorite', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-warning btn-lg mb-2"><i class="bi bi-star-fill"></i> Quitar de Favoritos</button>
            </form>
            @endif

            @if($post->belongs_to == $current_user->id || $current_user->isAdmin)
                <form action="{{ route('post.showUpdatePost', ['id' => $post->id]) }}" method="POST">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn btn-like"><i class="bi bi-arrow-clockwise icon-white"></i> Actualizar Post</button>
                </form>
            @endif

            @if ($post->belongs_to == $current_user->id || $current_user->isAdmin)
                <div x-data="{}">
                    <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger"><i class="bi bi-trash icon-white"></i> Eliminar Post</button>
                    <dialog x-ref="dialogDelUser" class="bg-white rounded-lg shadow-lg p-4">
                                
                    <h2>¿Estas seguro de que quieres eliminar este post?</h2>

                    <form action="{{ route('post.delete', ['id' => $post->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-success">Sí, Eliminar Post</button>
                    </form>

                    <form method="dialog">

                        <button class="btn btn-danger">Cancelar</button>

                    </form>

                    </dialog>
                </div>
            @endif

            <form action="{{ route('comment.showRegisterComment', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('GET')
                <button type="submit" class="btn btn-comment"><i class="bi bi-chat-right-dots icon-white"></i> Comentar</button>
            </form>
        @endif

    </div>

    @foreach($comments as $comment)

        <div class="comment-box">

            <h3 class="comment-user">@foreach ($users as $user)

                                        @if($user->id == $comment->user_id) 

                                            {{ $user->name }} 

                                        @endif 

                                     @endforeach
                                    </h3>
            <div class="comment-separator-box">
            <p class="comment-text">{{ $comment->comment }}</p>
            <p class="comment-date">{{ $comment->publish_date }}</p>
            </div>

        </div>

    @endforeach

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
            @if($post->latitude && $post->longitude)
                const map = L.map('map').setView([{{ $post->latitude }}, {{ $post->longitude }}], 13);

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

                L.marker([{{ $post->latitude }}, {{ $post->longitude }}], { icon: bugIcon }).addTo(map);

            @endif
        });
    </script>

</main>