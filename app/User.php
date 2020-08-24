<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 * @property integer role
 * @property string phone
 * @property string email
 * @property string password
 * @property string password_junk
 * @property string transaction_password
 * @property string username_doge
 * @property string password_doge
 * @property string account_cookie
 * @property string wallet
 * @property integer level
 * @property integer status
 * @property integer suspend
 */
class User extends Authenticatable
{
  use Notifiable, SoftDeletes, HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'role',
    'phone',
    'email',
    'password',
    'password_junk',
    'transaction_password',
    'username_doge',
    'password_doge',
    'account_cookie',
    'wallet',
    'level',
    'suspend',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'password_junk',
    'username_doge',
    'password_doge',
    'transaction_password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
}
