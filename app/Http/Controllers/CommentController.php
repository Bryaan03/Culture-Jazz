<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        $article->avis()->create([
            'user_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }
}
