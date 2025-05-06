@vite('resources/css/user_styles/user-index_styles.css', 'resources/js/app.js')
<main class="main__welcome">

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
        <section class="featured-section" x-data 
                                            x-init="
                                                setInterval(() => {
                                                    fetch('{{ route('post.updateDaily') }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Content-Type': 'application/json'
                                                        }
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        console.log('Post diario actualizado automáticamente:', data);
                                                        location.reload();
                                                    });
                                                }, 60000);
                                            "
                                        >

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
                <p class="post-text">{{ $dailyPost->description }}</p>
                <p class="post-date">{{ $dailyPost->publish_date }}</p>
                </div>

                <p class="likes-text">Likes: {{ $dailyPost->n_likes }}</p>

                <form action="{{ route('post.like', ['id' => $dailyPost->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-like">Like</button>
                </form>

            </div>

        </section>
        @endif

    @endif

</main>