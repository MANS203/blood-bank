<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Log extends Model
{
    protected $table = 'logs';
    public $timestamps = true;
    protected $fillable = array('content', 'service');


}
