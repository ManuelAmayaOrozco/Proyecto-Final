<header class="body__header">
    <div onclick="location.href=`{{ route('home') }}`" class="header__div_logo">
        LOGO HOME
    </div>
    <nav class="header__navigation">
        <a href="{{ Auth::check() ? route('user.showProfile') : route('login')  }}" class="navigation__a">
            {{ Auth::check() ? 'PROFILE' : 'LOGIN' }}
        </a>
        <a href="{{ route('user.showRegister') }}" class="navigation__a">
            REGISTER
        </a>
        <a class="navigation__a">
            POSTS
        </a>
    </nav>
</header>