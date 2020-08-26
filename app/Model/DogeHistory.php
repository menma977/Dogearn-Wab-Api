<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DogeHistory
 * @package App\Model
 * @property integer user_id
 * @property string description
 * @property integer send_to
 * @property string total
 * @property integer type
 */
class DogeHistory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'description',
        'send_to',
        'total',
        'type',
    ];
}
