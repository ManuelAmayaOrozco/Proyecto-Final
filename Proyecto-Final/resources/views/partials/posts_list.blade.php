@vite(['resources/css/user_styles/user-index_styles.css', 'resources/js/app.js'])
<main class="main__posts-index">

    @if(Auth::check()) 
    <a href="{{ route('post.showRegisterPost') }}" class="submit_post">
        MAKE POST
    </a>
    @endif

    @foreach($posts as $post)

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
            <div class="post-picture-display" onclick="location.href=`{{ route('post.showFullPost', ['id' => $post->id]) }}`">
                <img src="{{ asset('storage/' . $post->photo) }}" class="post-picture">
            </div>
            <p class="post-text">{{ $post->description }}</p>
            <p class="post-date">{{ $post->publish_date }}</p>
            </div>

            <p class="likes-text">Likes: {{ $post->n_likes }}</p>

            <form action="{{ route('post.like', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-like">Like</button>
            </form>

            @if ($post->belongs_to == $current_user_id)
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
        </div>

    @endforeach

</main>