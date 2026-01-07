<x-layout.app>
    @push('styles')
        @vite(['resources/css/index.css', 'resources/css/articles.css'])
    @endpush
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
    </div>
</x-layout.app>