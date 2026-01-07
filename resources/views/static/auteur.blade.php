<x-layout.app>
    @push('styles')
        @vite(['resources/css/index.css', 'resources/css/auteur.css'])
    @endpush
    <div id="search">
    <form action="{{ url('/list_auteurs') }}" method="GET">
        <input
                type="text"
                name="name"
                placeholder="Rechercher..."
                class="border border-gray-300 rounded-lg p-2"
                value="{{ request('name') }}"
        >
        <p>Nombre d'article(s)</p>
        <input type="number" name="nb_articles" placeholder="3" value="{{ request('nb_articles') }}">
        <p>Date rejoins</p>
        <input type="date" name="compte_cree" value="{{ request('compte_cree') }}">
        <input type="submit" name="confirmer">
    </form>
    <a href="{{route('auteurs')}}">Reset</a>
    </div>

    <div class="liste-auteurs">
        <h2>Liste des auteurs</h2>

        <div id="liste_auteurs">
            @forelse($auteurs as $auteur)
            <div class="auteur">
                    <p>
                        <a href="{{ route('utilisateur.show', $auteur->id) }}">
                            <img src="{{ $auteur->avatar }}" alt="Avatar de {{ $auteur->name }}" class="inline w-8 h-8 rounded-full">
                            {{ $auteur->name }}, {{ $auteur->email }}
                            
                        </a>
                    </p>
            </div>
                @empty
                    <div class="auteur">
                    <h2>Aucun article trouvé.</h2>
                    </div>
            @endforelse
        </div>
    </div>
</x-layout.app>