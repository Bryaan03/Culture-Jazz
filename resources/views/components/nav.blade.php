<nav>
    <form action="{{ url('/list_articles') }}" method="GET">
                <div class="search"><input
                type="text"
                name="titre"
                placeholder="Rechercher..."
                class="border border-gray-300 rounded-lg p-2"
                value="{{ request('titre') }}"

        >
        <i class='bx bx-search' ></i>
</div>
    </form>

    <a href="{{route('accueil')}}">Accueil</a>
    <a href="{{route('auteurs')}}">Auteurs</a>
    <a href="{{route('articles.list')}}">Articles</a>

            @auth
                    <a href="{{route('articles.create')}}" class="header-links">New Article</a>
                    <a href="{{route("profil.show")}}"><i class='bx bx-user-circle'></i></a>
                    <a href="{{route("logout")}}"
                    onclick="document.getElementById('logout').submit(); return false;"><i class='bx bx-log-out' ></i></a>
                    <form id="logout" action="{{route("logout")}}" method="post">
                        @csrf
                    </form>
            @else
                    <a href="{{route("login")}}">Login</a>
                    <a href="{{route("register")}}">Register</a>
            @endauth
</nav>