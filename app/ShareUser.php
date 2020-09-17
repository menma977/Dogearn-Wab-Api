<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShareUser
 * @package App
 *
 * @property integer user_id
 */
class ShareUser extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
  ];
}
