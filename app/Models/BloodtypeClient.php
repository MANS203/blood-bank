<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodtypeClient extends Model
{

    protected $table = 'blood_type_client';
    public $timestamps = true;
    protected $fillable = array('blood_type_id', 'client_id');

}
