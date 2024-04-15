<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = array('name');

    public function Posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Post');
    }

}
