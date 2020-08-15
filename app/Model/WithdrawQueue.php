<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WithdrawQueue
 * @package App\Model
 * @property integer user_id
 * @property integer send_to
 * @property string send_value
 * @property string total
 * @property string status
 */
class WithdrawQueue extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'send_to',
    'send_value',
    'total',
    'status'
  ];
}
