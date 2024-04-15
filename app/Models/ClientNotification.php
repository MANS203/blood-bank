<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNotification extends Model
{

    protected $table = 'client_notification';
    public $timestamps = true;
    protected $fillable = array('is_seen', 'client_id', 'notification_id');

}
