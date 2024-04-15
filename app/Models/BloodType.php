<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $blood_type_id)
 */
class BloodType extends Model
{

    protected $table = 'blood_types';
    public $timestamps = true;
    protected $fillable = array('name');

    public function Donation_Requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\DonationRequest');
    }

    public function Client(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Client');
    }

    public function Clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Client');
    }

}
