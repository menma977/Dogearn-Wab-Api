<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Treding
 * @package App\Model
 *
 * @property integer user_id
 * @property string start_balance
 * @property string end_balance
 * @property integer status
 */
class Treding extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'start_balance',
    'end_balance',
    'status',
  ];
}
