<header class="body__header">
    <div onclick="location.href=`{{ route('home') }}`" class="header__div_logo">
        <img src="{{ asset('storage/imagenesBugs/BugBuds.png') }}" alt="Logo BugBuds" class="header__logo"/>
    </div>
    <nav class="header__navigation">
        <a href="{{ Auth::check() ? route('user.showProfile') : route('login')  }}" class="navigation__a">
            {{ Auth::check() ? 'PROFILE' : 'LOGIN' }}
        </a>
        @if(!Auth::check())
        <a href="{{ route('user.showRegister') }}" class="navigation__a">
            REGISTER
        </a>
        @endif
        <a href="{{ route('post.showPosts') }}" class="navigation__a">
            POSTS
        </a>
        <a href="{{ route('insect.showInsects') }}" class="navigation__a">
            INSECTS
        </a>
        <a href="{{ route('user.showContact') }}" class="navigation__a">
            CONTACT US
        </a>
    </nav>

    <div class="header__searchbar">
        <form action="{{ route('post.showPosts') }}" method="GET">
            <select class="search-select" name="searchtype" id="searchtype">
                    <option value="" disabled selected>Opción de búsqueda</option>
                    <option value="user">Usuario</option>
                    <option value="insect">Insecto</option>
                    <option value="tag">Etiqueta</option>
                    <option value="favorites">Favoritos</option>
            </select>
            <input name="search" placeholder="..." class="search-bar" type="text">
            <button class="search-button">Buscar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchType = document.getElementById('searchtype');
            const searchInput = document.querySelector('.search-bar');

            searchType.addEventListener('change', function () {
                if (this.value === 'favorites') {
                    searchInput.disabled = true;
                    searchInput.value = ''; // limpia el campo si estaba escrito algo
                } else {
                    searchInput.disabled = false;
                }
            });
        });
    </script>
</header>