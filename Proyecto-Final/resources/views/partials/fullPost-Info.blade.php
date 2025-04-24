@vite('resources/css/user_styles/user-index_styles.css')
<main class="main__posts-index">

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
        <p class="post-text">{{ $post->description }}</p>
        <p class="post-date">{{ $post->publish_date }}</p>
        </div>

        <p class="likes-text">Likes: {{ $post->n_likes }}</p>
        <p class="likes-text">Comentarios: {{ count($comments) }}</p>

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

        <form action="{{ route('comment.showRegisterComment', ['id' => $post->id]) }}" method="POST">
            @csrf
            @method('GET')
            <button type="submit" class="btn btn-comment">Comentar</button>
        </form>

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

</main>