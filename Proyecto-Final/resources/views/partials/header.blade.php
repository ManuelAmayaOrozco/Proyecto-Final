<header class="body__header">
    <div onclick="location.href=`{{ route('home') }}`" class="header__div_logo">
    <img src="{{ asset('storage/imagenesBugs/BugBuds.png') }}" alt="Logo BugBuds" class="header__logo"/>
    </div>
    <nav class="header__navigation">
        <a href="{{ Auth::check() ? route('user.showProfile') : route('login')  }}" class="navigation__a">
            {{ Auth::check() ? 'PROFILE' : 'LOGIN' }}
        </a>
        <a href="{{ route('user.showRegister') }}" class="navigation__a">
            REGISTER
        </a>
        <a href="{{ route('post.showPosts') }}" class="navigation__a">
            POSTS
        </a>
        <a href="{{ route('insect.showInsects') }}" class="navigation__a">
            INSECTS
        </a>
    </nav>
    <div class="header__searchbar">
        <form action="{{ route('post.showPosts') }}" method="GET">
            <input name="search" placeholder="..." class="search-bar" type="text">
            <button class="search-button">Buscar</button>
        </form>
    </div>
</header>