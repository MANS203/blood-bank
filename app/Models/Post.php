<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $table = 'posts';
    public $timestamps = true;
    protected $fillable = array('id','title', 'image', 'content', 'category_id');

    public function Clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Client');
    }

    public function Category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Category');
    }

}
