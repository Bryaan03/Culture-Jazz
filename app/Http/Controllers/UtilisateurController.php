<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilisateurController extends Controller
{
    // Afficher la page d'un utilisateur
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Articles écrits par l'utilisateur
        $mesArticles = $user->mesArticles()->get();

        // Articles aimés par l'utilisateur
        $articlesAimes = $user->likes()->get();

        // Rythmes associés aux articles aimés
        $rythmes = $articlesAimes
            ->load('rythme')
            ->pluck('rythme')
            ->filter()
            ->unique('id');

        // Nombre de followers et suivis
        $nbFollowers = $user->suiveurs()->count();
        $nbSuivis = $user->suivis()->count();

        // Vérifier si le visiteur connecté suit déjà cet utilisateur
        $dejaSuivi = false;
        if (Auth::check()) {
            $dejaSuivi = Auth::user()
                ->suivis()
                ->where('suivi_id', $user->id)
                ->exists();
        }

        return view('utilisateur.show', compact(
            'user',
            'mesArticles',
            'articlesAimes',
            'rythmes',
            'nbFollowers',
            'nbSuivis',
            'dejaSuivi'
        ));
    }

    // Action pour suivre un utilisateur
    public function suivre($id)
    {
        $isFollowing = Auth::user()->suivis() // check dans la table suvis de user si suivi_id de tel id existe
            ->where('suivi_id', $id)
            ->exists();

        if ($isFollowing) {
            Auth::user()->suivis()->detach($id); // plus suivre
        } else {
            Auth::user()->suivis()->attach($id); // suivre
        }
        return back();
    }
}