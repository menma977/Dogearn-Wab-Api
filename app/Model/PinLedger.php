<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PinLedger
 * @package App\Model
 * @property integer user_id
 * @property string description
 * @property integer debit
 * @property integer credit
 */
class PinLedger extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'description',
    'debit',
    'credit',
  ];
}
