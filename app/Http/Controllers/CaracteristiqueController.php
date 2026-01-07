<?php

namespace App\Http\Controllers;

use App\Models\Accessibilite;
use App\Models\Conclusion;
use Illuminate\Http\Request;
use App\Models\Rythme;

class CaracteristiqueController extends Controller
{
    public function rythme(Rythme $rythme)
    {
        $articles = $rythme->articles()->where('en_ligne', true)->get();

        return view('article.rythme', compact('articles', 'rythme'));
    }

    public function accessibilite(Accessibilite $accessibilite)
    {
        $articles = $accessibilite->articles()->where('en_ligne', true)->get();

        return view('article.accessibilite', compact('articles', 'accessibilite'));
    }

    public function conclusion(Conclusion $conclusion)
    {
        $articles = $conclusion->articles()->where('en_ligne', true)->get();

        return view('article.conclusion', compact('articles', 'conclusion'));
    }
}

