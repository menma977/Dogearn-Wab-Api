<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App\Model
 * @property integer maintenance
 * @property integer type_withdraw
 * @property integer wallet_it
 * @property double fee
 * @property double admin_fee
 * @property integer app_version
 * @property string dollar
 * @property integer lot
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
    'fee',
    'admin_fee',
    'app_version',
    'dollar',
    'lot',
  ];
}
