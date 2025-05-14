<header class="body__header">
    <div onclick="location.href=`{{ route('home') }}`" class="header__div_logo">
        <img src="{{ asset('storage/imagenesBugs/BugBuds.png') }}" alt="Logo BugBuds" class="header__logo"/>
    </div>
    <nav class="header__navigation">
        <a href="{{ Auth::check() ? route('user.showProfile') : route('login')  }}" class="navigation__a">
            {{ Auth::check() ? 'PERFIL' : 'LOGIN' }}
        </a>
        @if(!Auth::check())
        <a href="{{ route('user.showRegister') }}" class="navigation__a">
            REGÍSTRATE
        </a>
        @endif
        <a href="{{ route('post.showPosts') }}" class="navigation__a">
            POSTS
        </a>
        <a href="{{ route('insect.showInsects') }}" class="navigation__a">
            INSECTOS
        </a>
        <a href="{{ route('user.showContact') }}" class="navigation__a">
            CONTÁCTANOS
        </a>
    </nav>

    <div class="header__searchbar">
        <form action="{{ route('post.showPosts') }}" method="GET" id="post-search-form">
            <select class="search-select" name="searchtype" id="post-searchtype">
                    <option value="" disabled selected>Opción de búsqueda</option>
                    <option value="user">Usuario</option>
                    <option value="insect">Insecto</option>
                    <option value="tag">Etiqueta</option>
                    <option value="favorites">Favoritos</option>
                    <option value="date">Fecha</option>
            </select>
            <input name="search" placeholder="Busca un Post" class="search-bar" type="text" id="post-search-input">
            <button class="search-button">Buscar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchType = document.getElementById('post-searchtype');
            const searchInput = document.getElementById('post-search-input');

            searchType.addEventListener('change', function () {
                const selected = this.value;

                if (selected === 'favorites') {
                    searchInput.disabled = true;
                    searchInput.value = '';
                    searchInput.placeholder = 'No se requiere búsqueda';
                    searchInput.type = 'text';
                } else if (selected === 'date') {
                    searchInput.disabled = false;
                    searchInput.type = 'date';
                    searchInput.placeholder = '';
                } else {
                    searchInput.disabled = false;
                    searchInput.type = 'text';
                    searchInput.placeholder = 'Busca un Post';
                }
            });
        });
    </script>

</header>