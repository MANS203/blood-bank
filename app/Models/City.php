<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{

    protected $table = 'cities';
    public $timestamps = true;
    protected $fillable = array('name', 'governorate_id');

    public function Governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Governorate');
    }

    public function Clients(): HasMany
    {
        return $this->hasMany('App\Models\Client');
    }

    public function Donation_Requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\DonationRequest');
    }

}
