@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js'])
<main class="main__posts-index">

    <!-- Cargar librería -->
    <script src="https://cdn.jsdelivr.net/npm/editorjs-html-revised@3.3.0/build/edjsHTML.min.js"></script>

    @if(!$current_user || !$current_user->banned)

            @if(Auth::check()) 
            <a href="{{ route('post.showRegisterPost') }}" class="submit_post">
                MAKE POST
            </a>
            @endif

            @forelse($posts as $post)

                @foreach ($users as $user)

                    @if($user->id == $post->belongs_to && !$user->banned) 

                        <div class="post-box">

                            <h2 class="post-title" onclick="location.href=`{{ route('post.showFullPost', ['id' => $post->id]) }}`">{{ $post->title }}</h2>
                            @foreach ($insects as $insect)

                                @if($insect->id == $post->related_insect) 
                            <h4 class="post-insect" onclick="location.href=`{{ route('insect.showFullInsect', ['id' => $insect->id]) }}`">{{ $insect->name }}</h4>
                                @endif 

                            @endforeach
                            <h3 class="post-user">@foreach ($users as $user)

                                                    @if($user->id == $post->belongs_to) 

                                                        {{ $user->name }} 

                                                    @endif 

                                                @endforeach
                                                </h3>
                            <div class="post-separator-box">
                            <div class="post-picture-display">
                                <img src="{{ asset('storage/' . $post->photo) }}" class="post-picture" onclick="location.href=`{{ route('post.showFullPost', ['id' => $post->id]) }}`">
                            </div>
                            <p class="post-tags">
                                @foreach ($post->tags as $tag)
                                    <span class="tag" onclick="location.href=`{{ route('post.showPosts', ['tagId' => $tag->id]) }}`">{{ ucfirst($tag->name) }}</span>
                                @endforeach
                            </p>
                            <script id="post-description-json-{{ $post->id }}" type="application/json">
                                {!! json_encode($post->description ?: ['blocks' => []]) !!}
                            </script>
                            <p class="post-date">{{ $post->publish_date }}</p>
                            </div>

                            <p class="likes-text">Likes: {{ $post->n_likes }}</p>

                            @if($current_user)
                            @if(!$post->likedByUsers->contains($current_user->id))
                                <form action="{{ route('post.like', ['id' => $post->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-like">Like</button>
                                </form>
                            @else
                                <form action="{{ route('post.dislike', ['id' => $post->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger">Quitar Like</button>
                                </form>
                            @endif

                            @php
                                $currentUserId = $current_user->id;
                                $isFavorite = $favorites->contains(function ($favorite) use ($currentUserId, $post) {
                                    return $favorite->id_user == $currentUserId && $favorite->id_post == $post->id;
                                });
                            @endphp

                            @if (!$isFavorite)
                                <form action="{{ route('post.newFavorite', ['id' => $post->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning btn-lg mb-3">
                                        <i class="bi bi-star"></i> Agregar a Favoritos
                                    </button>
                                </form>
                            @endif

                            @if ($isFavorite)
                                <form action="{{ route('post.removeFavorite', ['id' => $post->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-warning btn-lg mb-3">
                                        <i class="bi bi-star-fill"></i> Quitar de Favoritos
                                    </button>
                                </form>
                            @endif

                            @if ($post->belongs_to == $current_user->id)
                            <div x-data="{}">
                                <button @click="$refs.dialogDelUser.showModal()" class="btn btn-danger">Eliminar Post</button>
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
                            @endif
                            </div>

                    @endif 

                    @if($user->id == $post->belongs_to && $user->banned)

                        <div class="post-box">

                            <h2 class="post-title">ESTE POST ES DE UN USUARIO BANEADO</h2>

                        </div>

                    @endif

                @endforeach

            @empty
                <h2>Vaya no se encontraron posts, vuelve a intentarlo.</h2>
            @endforelse
    
    @endif

    @if($current_user && $current_user->banned)
        <h2>Vaya parece que tu usuario está baneado.</h2>
        <h3>Si crees que pueda ser una equivocación ponte en contacto con nosotros.</h3>
    @endif

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

            // Seleccionar todos los script tags con un id que comienza con "post-description-json-"
            const scriptTags = document.querySelectorAll('[id^="post-description-json-"]');

            scriptTags.forEach(scriptTag => {
                try {
                    const content = JSON.parse(scriptTag.textContent);

                    // Verificar si el contenido es válido antes de procesarlo
                    if (content && content.blocks && Array.isArray(content.blocks) && content.blocks.length > 0) {
                        const html = parser.parse(content);

                        const container = document.createElement('div');
                        container.classList.add('editorjs-content');
                        container.innerHTML = html.join('');
                        scriptTag.insertAdjacentElement('afterend', container);
                    } else {
                        console.error("❌ El contenido de Editor.js no tiene bloques o es inválido:", content);
                    }
                } catch (e) {
                    console.error("⚠️ Error al procesar el contenido de Editor.js:", e);
                }
            });
        });
    </script>


</main>