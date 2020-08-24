<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Setting
 * @package App\Model
 * @property integer maintenance
 * @property integer type_withdraw
 * @property integer wallet_it
 */
class Setting extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'maintenance',
    'type_withdraw',
    'wallet_it',
  ];
}
