<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        'titre',
        'resume',
        'texte',
        'image',
        'media',
        'rythme_id',
        'accessibilite_id',
        'conclusion_id',
        'user_id',
        'en_ligne',
    ];

    public function editeur() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function avis() {
        return $this->hasMany(Avis::class);
    }

    public function likes() {
        return $this->belongsToMany(User::class, 'likes')->withPivot("nature");
    }

    public function accessibilite() {
        return $this->belongsTo(Accessibilite::class);
    }


    public function conclusion() {
        return $this->belongsTo(Conclusion::class);
    }

    public function rythme() {
        return $this->belongsTo(Rythme::class);
    }


}
