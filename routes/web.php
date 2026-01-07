<?php

use App\Http\Controllers\ArticleLikeController;
use App\Http\Controllers\CaracteristiqueController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UtilisateurController;

Route::get('/', [ArticleController::class,'index'])->name('accueil');
Route::get('/list_articles', [ArticleController::class,'list_articles'])->name('articles.list');
Route::get('/list_auteurs', [ArticleController::class, 'list_auteurs'])->name('auteurs.list');


Route::middleware(['auth'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
});


Route::get('/articles/{article}', [ArticleController::class, 'show'])
    ->name('articles.show');

Route::get('/rythmes/{rythme}', [CaracteristiqueController::class, 'rythme'])
    ->name('rythmes.articles');

Route::get('/accessibilites/{accessibilite}', [CaracteristiqueController::class, 'accessibilite'])
    ->name('accessibilites.articles');

Route::get('/conclusions/{conclusion}', [CaracteristiqueController::class, 'conclusion'])
    ->name('conclusions.articles');

Route::get('/contact', function () {
    return view('contact');
})->name("contact");

Route::get('/test-vite', function () {
    return view('static/test-vite');
})->name("test-vite");

Route::get('/home', function () {
    return view('home');
})->name("home");
Route::get('/auteurs', [ArticleController::class, 'auteurs'])->name('auteurs');
Route::get('/auteurs', [ArticleController::class, 'auteurs'])->name('auteurs');

Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'updateProfil'])->name('profil.update');
    Route::patch('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password.update');
    Route::delete('/profil', [ProfilController::class, 'destroy'])->name('profil.destroy');
    Route::middleware('auth')->post(
        '/articles/{article}/react/{nature}',
        [ArticleLikeController::class, 'react']
    )->whereIn('nature', ['like', 'dislike'])
        ->name('articles.react');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])
        ->name('articles.update');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])
        ->name('articles.edit');
    Route::post('/articles/{article}/commentaires', [CommentController::class, 'store'])
        ->name('commentaires.store');
    Route::post('/utilisateur/{id}/suivre', [UtilisateurController::class, 'suivre'])->name('utilisateur.suivre');
    Route::post('/articles/{article}/mettre-en-ligne', [ArticleController::class, 'mettre_en_ligne'])
        ->name('articles.mettre_en_ligne');
});

Route::get('/utilisateur/{id}', [UtilisateurController::class, 'show'])
    ->name('utilisateur.show');

Route::post('/utilisateur/{id}/suivre', [UtilisateurController::class, 'suivre'])
    ->middleware('auth')
    ->name('utilisateur.suivre');



