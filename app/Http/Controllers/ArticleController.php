<?php

namespace App\Http\Controllers;

use App\Models\Accessibilite;
use App\Models\Article;
use App\Models\Conclusion;
use App\Models\Rythme;
use App\Models\User;
use App\Notifications\ArticleNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function index() {
        $articles = Article::where('en_ligne', true)->orWhere('user_id', Auth::id())->get()->take(5);

        return view('static.welcome', ['articles' => $articles]);
    }
    public function auteurs(){
        $auteurs = User::whereIn('id',Article::select('user_id')->distinct())->get();
        return view('static.auteur', ['auteurs' => $auteurs]);
    }
    public function list_auteurs(Request $request)
    {
        $query = User::query();

        // Filtre par nom
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtre par date (avant la date)
        if ($request->filled('compte_cree')) {
            $query->whereDate('created_at', '<=', $request->compte_cree);
        }

        // Filtre par nombre minimum d'articles
        if ($request->filled('nb_articles')) {
            $nbArticles = (int)$request->nb_articles;
            $query->whereIn('id', function ($sub) use ($nbArticles) {
                $sub->select('user_id')
                    ->from('articles')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(id) >= ?', [$nbArticles]);
            });
        } else {
            // Toujours inclure les utilisateurs avec au moins 1 article
            $query->whereIn('id', Article::select('user_id')->distinct());
        }

        $auteurs = $query->get();

        return view('static.auteur', compact('auteurs'));
    }

    public function list_articles(Request $request) {
        // requete
        $articleQuery = Article::query();

        // filtre titre
        if ($request->filled('titre')) {
            $articleQuery->where('titre', 'LIKE', '%' . $request->input('titre') . '%');
        }

        $articles = $articleQuery->get();

        // Passe la collection à la vue principale
        return view('article.list-articles', compact('articles'));
    }

    public function show(Article $article)
    {
        // Incrémenter le nombre de vues
        $article->increment('nb_vues');

        return view('article.show', compact('article'));
    }

    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('article.create', [
            'rythmes' => Rythme::all(),
            'accessibilites' => Accessibilite::all(),
            'conclusions' => Conclusion::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);

        $validated = $request->validate([
            'titre' => 'required|min:3|max:100',
            'resume' => 'required|max:500',
            'texte' => 'required',
            'image' => 'required|file',
            'media' => 'required|string',
            'rythme_id' => 'required|exists:rythmes,id',
            'accessibilite_id' => 'required|exists:accessibilites,id',
            'conclusion_id' => 'required|exists:conclusions,id',
            'en_ligne' => 'boolean',
        ]);

        // Copier l'image dans resources/images et récupérer le chemin
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $destinationPath = public_path('images'); // public/images
            $image->move($destinationPath, $imageName);
            $imagePath = 'images/' . $imageName; // chemin à stocker en BD
        }

        // Créer l'article
        $article = Article::create([
            'titre' => $validated['titre'],
            'resume' => $validated['resume'],
            'texte' => $validated['texte'],
            'image' => $imagePath,
            'media' => $validated['media'],
            'rythme_id' => $validated['rythme_id'],
            'accessibilite_id' => $validated['accessibilite_id'],
            'conclusion_id' => $validated['conclusion_id'],
            'user_id' => auth()->id(),
            'en_ligne' => $validated['en_ligne'] ?? false,
        ]);

        $followers = auth()->user()->suiveurs;

        foreach ($followers as $follower) {
            $follower->notify(new ArticleNotification($article));
        }

        return redirect()->route('accueil');
    }

    public function mettre_en_ligne(Article $article)
    {
        Gate::authorize('mettre_en_ligne', $article);

        $article->en_ligne = true;
        $article->updated_at = now();
        $article->save();

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article mis en ligne avec succès.');
    }

    public function edit(Article $article)
    {
        Gate::authorize('update', $article);
        return view('article.edit', ['article' => $article, 'rythmes' => Rythme::all(),
            'accessibilites' => Accessibilite::all(),
            'conclusions' => Conclusion::all(),
        ]);
    }

    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);

        $validated = $request->validate([
            'titre' => 'required|min:3|max:100',
            'resume' => 'required|max:500',
            'texte' => 'required',
            'image' => 'nullable|file|image',
            'media' => 'required|string',
            'rythme_id' => 'required|exists:rythmes,id',
            'accessibilite_id' => 'required|exists:accessibilites,id',
            'conclusion_id' => 'required|exists:conclusions,id',
        ]);

        $imagePath = $article->image; // image actuelle par défaut

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $destinationPath = public_path('images');
            $image->move($destinationPath, $imageName);
            $imagePath = 'images/' . $imageName;
        }


        $article->update([
            'titre' => $validated['titre'],
            'resume' => $validated['resume'],
            'texte' => $validated['texte'],
            'image' => $imagePath,
            'media' => $validated['media'],
            'rythme_id' => $validated['rythme_id'],
            'accessibilite_id' => $validated['accessibilite_id'],
            'conclusion_id' => $validated['conclusion_id'],
            'updated_at' => now(),
        ]);

        return redirect()->route('articles.show', $article)->with('success', 'Article mis à jour avec succès.');
    }
}
