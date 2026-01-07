<x-layout.app>
    @push('styles')
        @vite(['resources/css/index.css', 'resources/css/articles.css'])
    @endpush
    <div class="banner-intro">
        <h1 class="text-4xl font-bold mb-6">Welcome to Culture Jazz</h1>
        <img src=" {{ Vite::asset('resources/images/illustration_banniere.png') }}" alt="illustration bannière" />
        <span>Our national french and international jazz culture but we speak in english just to upset you !</span>
        <a href="#">Découvrez nos reviews !</a>
    </div>
    <div class="grille-liste-articles">
        <div class="liste-articles">
            @forelse ($articles as $article)
                @if($article->en_ligne || $article->user_id == auth()->id())
                    <x-card-article :article="$article" />
                @endif
            @empty
                <p class="text-gray-600">Aucun article trouvé.</p>
            @endforelse
        </div>
        <!-- <div class="liste-auteurs">
            <h2><a href="{{route('auteurs')}}">Liste des auteurs</a></h2>

            @foreach($articles as $article)
                <p>
                    <a href="{{ route('utilisateur.show', $article->editeur->id) }}">
                        {{ $article->editeur->name }}
                    </a>
                </p>
            @endforeach
        </div> -->
    </div>

    <div class="apropos">

        <div class="texte-apropos">
            <h2>Qui sommes nous ?</h2>
            <p>Le jazz, c’est une respiration libre entre les notes : un dialogue spontané où l’improvisation devient langage. Né de la rencontre entre rythmes africains et harmonies occidentales, il transforme chaque silence en promesse et chaque variation en émotion. Une musique vivante, mouvante, qui ne se répète jamais tout à fait. Bienvenue chez nous, bienvenue chez Culture Jazz. 🎷</p>
        </div>
        <img src=" {{ Vite::asset('resources/images/logo_culture_jazz.png') }}" />

    </div>
</x-layout.app>