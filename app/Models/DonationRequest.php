<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationRequest extends Model
{

    protected $table = 'donation_requests';
    public $timestamps = true;
    protected $fillable = array('patient_name', 'patient_age', 'patient_phone', 'hospital_name', 'hospital_address', 'bags_num', 'details', 'latitude', 'longitude', 'blood_type_id', 'city_id', 'client_id');

    public function City(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\City');
    }

    public function blood_Type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\BloodType');
    }

    public function Client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function Notifications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Notification');
    }

    // public function Notifications()
    // {
    //     return $this->hasMany('App\Models\Notification');
    // }

}
