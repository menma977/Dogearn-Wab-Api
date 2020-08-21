<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Login username to be used by the controller.
   *
   * @var string
   */
  protected $phone;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');

    $this->phone = $this->findUsername();
  }

  /**
   * Get the login username to be used by the controller.
   *
   * @return string
   */
  public function findUsername(): string
  {
    $login = request()->input('email');
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    request()->merge([$fieldType => $login]);
    return $fieldType;
  }

  /**
   * Get username property.
   *
   * @return string
   */
  public function username(): string
  {
    return $this->phone;
  }

  /**
   * @param Request $request
   * @return array
   */
  protected function credentials(Request $request): array
  {
    $validateData = [
      'role' => 1
    ];
    return array_merge($request->only($this->username(), 'password'), $validateData);
  }

  protected function sendFailedLoginResponse(Request $request)
  {
    $errors = [$this->username() => trans('auth.failed')];
    $user = User::where($this->username(), $request->{$this->username()})->first();

    if ($user && Hash::check($request->password, $user->password) && $user->role !== 1) {
      $errors = [$this->username() => trans('You do not have the appropriate permissions')];
    }

    if ($request->expectsJson()) {
      return response()->json($errors, 422);
    }

    return redirect()->back()->withInput($request->only($this->username(), 'remember'))->withErrors($errors);
  }
}
