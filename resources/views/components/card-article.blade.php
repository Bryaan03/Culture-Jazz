<div class="card-article">

    <div class="p-5">
        <h2>{{ $article->titre }}</h2>

        <p class="text-gray-600 mt-2">{{ $article->create_at }}</p>
        <p class="text-gray-600">{{ $article->resume }}</p>

        <form action="{{ route('articles.show', $article) }}" method="GET" class="mt-4">
            <button class="bouton-ensavoirplus">
                En savoir plus
            </button>
        </form>
        @php
            $reaction = $article->likes
            ->firstWhere('id', auth()->id())
            ?->pivot->nature;
        @endphp
        <x-reaction :reaction="$reaction" :article="$article" />
    </div>

    <img
                src="{{ asset(($article->image)) }}"
                alt="Affiche de l'article {{ $article->titre }}"
        />

</div>
