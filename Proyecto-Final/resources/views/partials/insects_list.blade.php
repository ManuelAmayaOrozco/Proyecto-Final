@vite('resources/css/user_styles/user-index_styles.css')
<main class="main__insects-index">

    @if(Auth::check()) 
    <a href="{{ route('insect.showRegisterInsect') }}" class="submit_post">
        REGISTER INSECT
    </a>
    @endif

    @foreach($insects as $insect)

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
            <p class="insect-info">Familia: {{ $insect->family }}</p>
            <p class="insect-info">Dieta: {{ $insect->diet }}</p>
            <p class="insect-info">Nº Documentados: {{ $insect->n_spotted }}</p>
            <p class="insect-info">Tamaño record: {{ $insect->maxSize }}</p>
            <p class="insect-info">En peligro de extinción: {{ $insect->protectedSpecies ? 'SI' : 'NO' }}</p>
            <p class="insect-text">{{ $insect->description }}</p>
            </div>

        </div>

    @endforeach

</main>