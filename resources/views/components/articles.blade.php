@push('styles')
        @vite(['resources/css/index.css', 'resources/css/articles.css', 'resources/css/liste_articles.css'])
@endpush

@props(['articles'])
<div class="liste-articles">
            @foreach ($articles as $article)
                @if($article->en_ligne || $article->user_id == auth()->id())
                    <div class="cartes"><x-card-article :article="$article" /></div>
                @endif
            @endforeach

        @if ($articles->isEmpty())
            <p>
                Aucun article.
            </p>
        @endif

</div>
