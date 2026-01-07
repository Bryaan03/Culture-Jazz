@push('styles')
        @vite(['resources/css/articles.css', 'resources/js/app.js'])
@endpush
<x-layout.app>


        {{-- ================= TITRE ================= --}}
        <div id="content">
            <h1>
                {{ $article->titre }}
            </h1>

            <div class="infos-article">
                <span>👁 {{ $article->nb_vues }} vues</span>

                <span>📅 {{ $article->created_at->format('d/m/Y') }}</span>

                <a
                        href="{{ route('utilisateur.show', $article->user_id) }}"
                        class="text-blue-600 hover:underline"
                >
                    ✍️ {{ $article->editeur->name ?? 'Anonyme' }}
                </a>
                @if($article->user_id === auth()->id())
                <a href="{{route('articles.edit', $article->id)}}" class="btn-edit"> Modifier l'article </a>
            @endif
            </div>
        </div>

                {{-- ================= CARACTÉRISTIQUES ================= --}}

    <section id="caracteristique">

            <ul>

                <li>
                    
                    <a
                            href="{{ route('rythmes.articles', $article->rythme) }}"
                            class="text-blue-600 hover:underline"
                    >   <strong>Rythme :</strong>
                        {{ $article->rythme->texte }}
                    </a>
                </li>

                <li>
                    
                    <a
                            href="{{ route('accessibilites.articles', $article->accessibilite) }}"
                            class="text-blue-600 hover:underline"
                    >   <strong>Accessibilité :</strong>
                        {{ $article->accessibilite->texte }}
                    </a>
                </li>

                <li>
                    
                    <a
                            href="{{ route('conclusions.articles', $article->conclusion) }}"
                            class="text-blue-600 hover:underline"
                    >   <strong>Conclusion :</strong>
                        {{ $article->conclusion->texte }}
                    </a>
                </li>

            </ul>
        </section>
        <div id="img_txt">
            <div id="texte">
                {{-- ============== === TEXTE ================= --}}
                <p>
                    <x-markdown>
                        {!! $article->texte !!}
                    </x-markdown>
                </p>
            </div>
            
            <div id="right">
            {{-- ================= IMAGE ================= --}}
            @if ($article->image)
                <img
                        src="{{ asset(($article->image)) }}"
                        alt="Image de l'article {{ $article->titre }}"
                        class="w-full rounded-xl shadow"
                >
            @endif
            

            {{-- ================= MEDIA ================= --}}
            @if ($article->media)
                <section>
                    <audio controls src="{!! $article->media !!}">
                    </audio>
                </section>
            @endif
            </div id="right">
        </div>

            {{-- ================= LIKES ================= --}}
        @php
            $reaction = $article->likes
                ->firstWhere('id', auth()->id())
                ?->pivot->nature;

            $reaction = match ($reaction) {
                1 => 'like',
                0 => 'dislike',
                default => null,
            };
        @endphp
        <x-reaction :reaction="$reaction" :article="$article" />

        <section id="appreciation">
            <h2 class="text-2xl font-bold mb-4">Appréciation</h2>

            <p>
                <span>{{ $article->likes->where('pivot.nature', 1)->count() }}</span> likes
                —
                <span>{{ $article->likes->where('pivot.nature', 0)->count() }}</span> dislikes
            </p>
        </section>        

        {{-- ================= COMMENTAIRES ================= --}}
        <section id="commentaires">
            <h2>
                Commentaires ({{ $article->avis->count() }})
            </h2>

            @forelse ($article->avis()->orderBy('created_at', 'desc')->get()->take(3) as $avis)
                <div class="commentaire">
                    <div>
                        <span class="user">{{ $avis->user->name ?? 'Utilisateur' }}</span>
                        <span>{{ $avis->created_at->format('d/m/Y') }}</span>
                    </div>

                    <p>
                        {{ $avis->contenu }}
                    </p>
                </div>
            @empty
                <p>
                    Aucun commentaire pour le moment.
                </p>
            @endforelse

            <x-commentaire :article="$article"/>

        </section>

        {{-- ================= STATUT ================= --}}
        <div>
            @if ($article->en_ligne)
                <span>
                    ✔ Article en ligne
                </span>
            @else
                <span>
                    ❌ Article hors ligne
                </span>
            @endif
        </div>

</x-layout.app>
