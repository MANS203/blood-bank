<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $table = 'contacts';
    public $timestamps = true;
    protected $fillable = array('supject', 'message', 'client_id');

    public function Client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Client');
    }

}
