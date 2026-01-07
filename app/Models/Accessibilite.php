<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessibilite extends Model
{
    //
    protected $guarded = [];

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
