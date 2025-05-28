<!--Estructura de la vista principal.-->
@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/alpine.js', 'resources/js/app.js'])
<main class="main__welcome">
    <img src="{{ asset('storage/imagenesBugs/Bug3.png') }}" class="bg-image bg-image--1" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug3.png') }}" class="bg-image bg-image--2" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--3" alt="">
    <img src="{{ asset('storage/imagenesBugs/Bug5.png') }}" class="bg-image bg-image--4" alt="">

    <section class="welcome-section">

        <div class="welcome-text">

            <h1 class="welcome-heading">Bienvenidos</h1>

            <div class="welcome-description">
                <p>Aquí en BugBuds, todos los amantes de la entomología pueden seguir sus pasiones, escribir a cerca de los diferentes encuentros e investigaciones que hayan tenido y compartirlos con otros entusiastas del mismo campo.</p>
                <p>Nuestra plantilla es simple y efectiva para que cualquiera pueda usarla fácilmente y publicar lo que quiera.</p>
                <p>Tenemos una amplia comunidad activa con varios profesionales que aportan nuevos descubrimientos y aportaciones diariamente.</p>
                <p>¿A qué esperas? Inscríbete en BudBuds y forma parte de nuestro grupo.</p>
            </div>

        </div>

        <div class="welcome-image">
        <img src="{{ asset('storage/imagenesBugs/Bug1.png') }}" alt="Ilustración de bienvenida" class="welcome-illustration" />
        </div>

    </section>

    @if(!$current_user || !$current_user->banned)

        @if ($dailyPost != null)
        <section class="featured-section">

            <h1 class="featured-heading">Post del día</h1>

            <div class="post-box">

                <h2 class="post-title" onclick="location.href=`{{ route('post.showFullPost', ['id' => $dailyPost->id]) }}`">{{ $dailyPost->title }}</h2>
                @foreach ($insects as $insect)

                    @if($insect->id == $dailyPost->related_insect) 
                <h4 class="post-insect" onclick="location.href=`{{ route('insect.showFullInsect', ['id' => $insect->id]) }}`">{{ $insect->name }}</h4>
                    @endif 

                @endforeach
                <h3 class="post-user">@foreach ($users as $user)

                                        @if($user->id == $dailyPost->belongs_to) 

                                            {{ $user->name }} 

                                        @endif 

                                    @endforeach
                                    </h3>
                <div class="post-separator-box">
                <div class="post-picture-display" onclick="location.href=`{{ route('post.showFullPost', ['id' => $dailyPost->id]) }}`">
                    <img src="{{ asset('storage/' . $dailyPost->photo) }}" class="post-picture">
                </div>
                <p class="post-tags">
                    @foreach ($dailyPost->tags as $tag)
                        <span class="tag" onclick="location.href=`{{ route('post.showPosts', ['tagId' => $tag->id]) }}`">{{ ucfirst($tag->name) }}</span>
                    @endforeach
                </p>
                <div class="post-text">
                    <script id="post-description-json" type="application/json">
                        {!! $dailyPost->description !!}
                    </script>
                </div>
                <p class="post-date">{{ $dailyPost->publish_date }}</p>
                </div>

                <p class="likes-text">Likes: {{ $dailyPost->n_likes }}</p>

            </div>

        </section>
        @endif

    @endif

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