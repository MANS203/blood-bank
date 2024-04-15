<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $token)
 */
class Token extends Model
{
    protected $table = 'tokens';
    public $timestamps = true;
    protected $fillable = array('id','token','client_id','type');

    public function Client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Client');
    }

}
