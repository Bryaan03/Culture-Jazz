@props(['article'])

@auth
    <form action="{{ route('commentaires.store', $article) }}" method="POST" class="add_comment">
        @csrf
        <input
                type="text"
                name="contenu"
                placeholder="Écrire un commentaire..."
                class="border rounded p-2 w-full"
                required
        >
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
            Envoyer
        </button>
    </form>
@endauth
