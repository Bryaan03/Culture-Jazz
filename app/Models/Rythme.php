<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rythme extends Model
{
    //
    protected $guarded = [];

    public function articles() {
        return $this->hasMany(Article::class);
    }

}
