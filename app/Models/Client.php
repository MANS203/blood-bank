<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $all)
 * @method static where(string $string, $phone)
 */
class Client extends Model
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('phone', 'email', 'name', 'password', 'd_o_b', 'last_donation_date', 'pin_code', 'blood_type_id', 'city_id');
    protected $hidden =
    [
        'password','api_token'
    ];

    public function blood_Type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\BloodType');
    }

    public function City(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\City');
    }

    public function Contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Contact');
    }
    public function Tokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Token');
    }

    public function Donation_Requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\DonationRequest');
    }

    public function Posts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Post');
    }

    public function Governorates(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Governorate');
    }

    public function Notifications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Notification');
    }

    public function blood_Types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\BloodType');
    }

}
