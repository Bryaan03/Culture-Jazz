@push('styles')
        @vite(['resources/css/utilisateur.css'])
        @vite(['resources/css/index.css'])
        @vite(['resources/css/profil.css'])
@endpush 
<x-layout.app>
    <div id="content_profil">
        <!-- Entête -->
        <div>
            <div>
                <div id="entete_user">
                    <!-- Texte -->
                    <div>
                        <h2>Bonjour {{ $user->name }}!</h2>
                    </div>
                    @if( $user->avatar  == null)
                        <!-- Avatar à droite -->
                        <img src="{{Vite::asset('resources/images/no-profile-picture.png')}}" alt="Image par défaut">
                    @else

                        <!-- Avatar à droite --> 
                        <img src="{{ $user->avatar }}" alt="Avatar" >
                </div>
                @endif


            </div>
        </div>

        <!-- Bouton modifier -->
        <div>
            <a href="{{ route('profil.edit') }}"
               class="inline-block bg-indigo-600 text-white font-semibold py-2 px-6 rounded-lg shadow
              hover:bg-indigo-700 transition">
                Modifier
            </a>
        </div>
        <hr>
        <h2>Profils similaires</h2>

        @if($profilsSimilaires->isEmpty())
            <p>Aucun profil similaire trouvé.</p>
        @else
            <div class="profils-similaires">
                @foreach($profilsSimilaires as $profil)
                    <div class="profil-similaire">
                        <img src="{{ $profil->avatar ?? Vite::asset('resources/images/no-profile-picture.png') }}">
                        <a href="{{ route('utilisateur.show', $profil->id) }}">
                            <p>{{ $profil->name }}</p>
                        </a>
                            En commun :
                            @if($rythmeId && $profil->mesArticles->contains('rythme_id', $rythmeId))
                            <a
                                    href="{{ route('rythmes.articles', $profil->mesArticles->firstWhere('rythme_id', $rythmeId)?->rythme) }}"
                                    class="text-blue-600 hover:underline"
                            >
                                rythme
                            </a>
                            @endif

                            @if($conclusionId && $profil->mesArticles->contains('conclusion_id', $conclusionId))
                            <a
                                    href="{{ route('conclusions.articles', $profil->mesArticles->firstWhere('conclusion_id', $conclusionId)?->conclusion) }}"
                                    class="text-blue-600 hover:underline"
                            >
                                conclusion
                            </a>
                                 ·
                            @endif

                            @if($accessibiliteId && $profil->mesArticles->contains('accessibilite_id', $accessibiliteId))
                                <a
                                        href="{{ route('accessibilites.articles', $profil->mesArticles->firstWhere('accessibilite_id', $accessibiliteId)?->accessibilite) }}"
                                        class="text-blue-600 hover:underline"
                                >
                                    Accessibilité
                                </a>
                            @endif
                    </div>
                @endforeach
            </div>
        @endif

        <hr>

        <h2>Notifications</h2>

        @forelse($notifications as $notification)
            <div class="notification">
                <strong>{{ $notification->data['auteur'] }}</strong>
                a publié un nouvel article :
                <a href="{{ route('articles.show', $notification->data['article_id']) }}">
                    {{ $notification->data['titre'] }}
                </a>
            </div>
        @empty
            <p>Aucune notification</p>
        @endforelse

        <hr>
        <div>
            <h2>Articles aimé(s)</h2>
            <div id="arts_like">
                @php
                    $likedArticles = $likedArticle->filter(function($art) use ($user) {
                        return $art->likes->contains(function($like) use ($user) {
                            return $like->id == $user->id && $like->pivot->nature == 1;
                        });
                    });
                @endphp

                @if($likedArticles->isNotEmpty())
                    @foreach($likedArticles as $art)
                        <div id="art_like">
                        <p>{{ $art->titre }}</p>
                        <img src="{{ asset($art->image) }}" alt="Image de l'article">
                            <form action="{{ route('articles.show', $art) }}" method="GET" class="mt-4">
                                <button class="bouton-ensavoirplus">
                                    En savoir plus
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p>Tu n'aimes aucun article</p>
                @endif
            </div>
        </div>
        <hr>
        <div id="follows">
            <h2>Personnes suivie(s)</h2>
            <div>
                @forelse($user->suivis as $suivi)
                    <div id="follow">
                        <img src="{{ $suivi->avatar }}" alt="avatar">
                        <a href="{{ route('utilisateur.show', $suivi->id) }}">
                            <p>{{ $suivi->name }}</p>
                        </a>
                    </div>
                @empty
                    <p>Tu ne suis personne</p>
                @endforelse
            </div>
        </div>
        <hr>
        <div>
            <h2>En cours...</h2>
            <div>
                @php
                    $enCours = $articles->filter(fn($art) => $art->en_ligne == 0);
                @endphp

                @forelse($enCours as $art)
                    <p>{{ $art->titre }}</p>
                    <img src="{{ $art->image }}" alt="Image de l'article">
                    <form action="{{ route('articles.mettre_en_ligne', $art) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit">Mettre en ligne</button>
                        </form>
                    <form action="{{ route('articles.show', $art) }}" method="GET" class="mt-4">
                        <button class="bouton-ensavoirplus">
                            En savoir plus
                        </button>
                    </form>
                @empty
                    <p>Aucun article en cours</p>
                @endforelse


            </div>
        </div>
    </div>
</x-layout.app>

