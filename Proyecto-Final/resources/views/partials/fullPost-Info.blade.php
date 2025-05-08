@vite('resources/css/user_styles/user-index_styles.css')
<main class="main__full-posts-index">

    <div class="post-box">

        <h2 class="post-title">{{ $post->title }}</h2>
        <h4 class="post-insect" onclick="location.href=`{{ route('insect.showFullInsect', ['id' => $post_insect_id]) }}`">{{ $post_insect }}</h3>
        <h3 class="post-user">{{ $post_user }}</h3>
        <div class="post-separator-box">
        <div class="post-picture-display">
            <img src="{{ asset('storage/' . $post->photo) }}" class="post-picture">
        </div>
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
                    <button type="submit" class="btn btn-like">Like</button>
                </form>
            @else
                <form action="{{ route('post.dislike', ['id' => $post->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Quitar Like</button>
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

            @if ($post->belongs_to == $current_user->id)
            <form action="{{ route('post.delete', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
            @endif

            <form action="{{ route('comment.showRegisterComment', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('GET')
                <button type="submit" class="btn btn-comment">Comentar</button>
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
        });
    </script>

</main>