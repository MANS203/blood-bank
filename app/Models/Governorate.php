<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{

    protected $table = 'governorates';
    public $timestamps = true;
    protected $fillable = array('name');

    public function Cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\City');
    }

    public function Clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Client');
    }

}
