@push('styles')
        @vite(['resources/css/index.css', 'resources/css/articles.css', 'resources/css/utilisateur.css', 'resources/js/app.js'])
@endpush
<x-layout.app>
    <div>

        {{-- PROFIL UTILISATEUR --}}
        <section class="text-center">
            <!-- <img
                    src="{{ $user->avatar && file_exists(public_path($user->avatar)) ? asset($user->avatar) : asset('images/no-profile-picture.png') }}"
                    alt="Avatar de {{ $user->name }}"> -->
            <div id="pp"></div>
            <h1>{{ $user->name }}</h1>
            <p>{{ $nbFollowers }} </br>followers</p>
            <p>{{ $nbSuivis }} </br>suivis</p>

            @auth
                @if (auth()->id() !== $user->id && !$dejaSuivi)
                    <form method="POST" action="{{ route('utilisateur.suivre', $user->id) }}">
                        @csrf
                        <button type="submit">Suivre</button>
                    </form>
                @elseif(auth()->id() !== $user->id && $dejaSuivi)
                    <form method="POST" action="{{ route('utilisateur.suivre', $user->id) }}">
                        @csrf
                        <button type="submit">Ne plus suivre</button>
                    </form>
                @endif
            @endauth
        </section>  

        {{-- ARTICLES ÉCRITS --}}
        <section>
            <h2>Articles écrits</h2>
            <div>
                @forelse ($mesArticles as $article)
                <div class="gapeeeeeeeuh">
                    <x-card-article :article="$article" />
                </div>
                @empty
                    <p>Aucun article écrit.</p>
                @endforelse
            </div>
        </section>

        {{-- ARTICLES AIMÉS --}}
        <section>
            <h2>Articles aimés</h2>
            <div>
                @forelse ($articlesAimes as $article)
                <div class="gapeeeeeeeuh">
                    <x-card-article :article="$article" />
                </div>
                @empty
                    <p>Aucun article aimé.</p>
                @endforelse
            </div>
        </section>

        {{-- RYTHMES ASSOCIÉS AUX ARTICLES AIMÉS --}}
        <section>
            <h2>Rythmes associés aux articles aimés</h2>
            @if ($rythmes->count())
                <ul>
                    @foreach ($rythmes as $rythme)
                        <a href="{{ route('rythmes.articles', $rythme) }}" class="text-blue-600 hover:underline">
                            {{ $rythme->nom ?? 'Rythme #' . $rythme->id }}
                        </a>
                    @endforeach
                </ul>
            @else
                <p class="text-center">Aucun rythme</p>
            @endif
        </section>

    </div>
</x-layout.app>
