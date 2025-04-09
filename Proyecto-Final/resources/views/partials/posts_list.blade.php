@vite('resources/css/user_styles/user-index_styles.css')
<main class="main__posts-index">

    @if(Auth::check()) 
    <a href="{{ route('post.showRegisterPost') }}" class="submit_post">
        MAKE POST
    </a>
    @endif

    @foreach($posts as $post)

        <div class="post-box" onclick="location.href=`{{ route('post.showFullPost', ['id' => $post->id]) }}`">

            <h2 class="post-title">{{ $post->title }}</h2>
            <h3 class="post-user">@foreach ($users as $user)

                                    @if($user->id == $post->belongs_to) 

                                        {{ $user->name }} 

                                    @endif 

                                  @endforeach
                                </h3>
            <div class="post-separator-box">
            <div class="post-picture-display">
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
            <form action="{{ route('post.delete', ['id' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
            @endif
        </div>

    @endforeach

</main>