<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
  /**
   * @param $email
   * @param $password
   * @return Application|RedirectResponse|Redirector
   */
  public function index($email, $password)
  {
    $user = User::where('email', Crypt::decryptString($email))->where('password', Crypt::decryptString($password))->first();
    if ($user) {
      $user->status = 2;
      $user->save();
    }

    return redirect('/');
  }
}
