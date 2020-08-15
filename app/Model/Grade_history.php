<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Grade_history
 * @package App\Model
 * @property integer user_id
 * @property integer debit
 * @property integer credit
 */
class Grade_history extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'debit',
    'credit',
  ];
}
