<x-layout.app>
    <div class="forms-container">
    <h2>Créer un nouvel article</h2>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="forms">
        @csrf

        <label for="titre">Titre</label>
        <div class="inputs">
            <input type="text" name="titre" id="titre" value="{{ old('titre') }}" placeholder="Titre" required>
        </div>

        <label for="resume">Résumé</label>
        <div class="inputs">
            <input type="text" name="resume" id="resume" value="{{ old('resume') }}" placeholder="Résumé" required>
        </div>

        <label for="texte">Texte</label>
        <textarea class="inputs" name="texte" id="texte" required placeholder="Texte de l'article">{{ old('texte') }}</textarea>

        <label for="image">Photo d'accroche</label>
        <div class="inputs">
            <input type="file" name="image" id="image" required>
        </div>
        
        <label for="media">Média son (URL)</label>
        <div class="inputs">
            <input type="url" name="media" id="media" value="{{ old('media') }}" placeholder="https://..." required>
        </div>
        
        <label for="rythme_id">Rythme</label>
        <div class="select-wrap">
            <select name="rythme_id" id="rythme_id" required>
                <option value="">-- Choisir un rythme --</option>
                @foreach ($rythmes as $r)
                    <option value="{{ $r->id }}" {{ old('rythme_id') == $r->id ? 'selected' : '' }}>
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
                    <option value="{{ $a->id }}" {{ old('accessibilite_id') == $a->id ? 'selected' : '' }}>
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
                <option value="{{ $c->id }}" {{ old('conclusion_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->texte }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mise_en_ligne checkbox">
        <input type="hidden" name="en_ligne" value="0">
        <input type="checkbox" name="en_ligne" value="1">
        <label for="en_ligne">Mettre en ligne</label>
    </div>

        <button type="submit" class="btn">Créer l'article</button>
    </form>
    </div>
</x-layout.app>