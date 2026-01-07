<x-layout.app>
    <div class="forms-container">
    <h2>Modifier un article</h2>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.update', [$article->id]) }}" method="POST" enctype="multipart/form-data" class="forms">
        @csrf
        @method('PUT')

        <label for="titre">Titre</label>
        <div class="inputs">
            <input type="text" name="titre" id="titre" value="{{ old('titre', $article->titre) }}" required>
        </div>

        <label for="resume">Résumé</label>
        <div class="inputs">
            <input type="text" name="resume" id="resume" value="{{ old('resume', $article->resume) }}" required>
        </div>
        
        <label for="texte">Texte</label>
        <textarea name="texte" id="texte" required>{{ old('texte', $article->texte) }}</textarea>

        <label for="image">Remplacer la photo d'accroche</label>
        @if($article->image)
            <p>Image actuelle :</p>
            <img src="{{ asset($article->image) }}" alt="Image d'accroche" style="max-width: 200px; height: auto;">
        @endif
        <input type="file" name="image" id="image">

        <label for="media">Média son (URL)</label>
        <div class="inputs">
            <input type="url" name="media" id="media" value="{{ old('media', $article->media) }}" placeholder="https://..." required>
        </div>

        <label for="rythme_id">Rythme</label>
        <div class="select-wrap">
            <select name="rythme_id" id="rythme_id" required>
                <option value="">-- Choisir un rythme --</option>
                @foreach ($rythmes as $r)
                    <option value="{{ $r->id }}" {{ old('rythme_id', $article->rythme_id) == $r->id ? 'selected' : '' }}>
                        {{ $r->texte }}
                    </option>
                @endforeach
            </select>
        </div>

        <label for="accessibilite_id">Accessibilité</label>
        <div class="select-wrap">
            <select name="accessibilite_id" id="accessibilite_id" required>
                <option value="">-- Choisir --</option>
                @foreach ($accessibilites as $a)
                    <option value="{{ $a->id }}" {{ old('accessibilite_id', $article->accessibilite_id) == $a->id ? 'selected' : '' }}>
                        {{ $a->texte }}
                    </option>
                @endforeach
            </select>
        </div>

        <label for="conclusion_id">Conclusion</label>
        <div class="select-wrap">
            <select name="conclusion_id" id="conclusion_id" required>
                <option value="">-- Choisir --</option>
                @foreach ($conclusions as $c)
                    <option value="{{ $c->id }}" {{ old('conclusion_id', $article->conclusion_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->texte }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn">Modifier l'article</button>
    </form>
    </div>
</x-layout.app>