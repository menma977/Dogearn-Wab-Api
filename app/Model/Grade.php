<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**\
 * Class Grade
 * @package App\Model
 * @property integer price
 * @property integer pin
 */
class Grade extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'price',
    'pin',
  ];
}
