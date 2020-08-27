<?php

namespace App\Http\Controllers;

use App\Model\AdminWallet;
use App\Model\GradeHistory;
use App\Model\Level;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return Renderable
   */
  public function index()
  {
    return view('home');
  }

  /**
   * @return int
   */
  public function totalUser()
  {
    return User::all()->count();
  }

  /**
   * @return int
   */
  public function onlineUser()
  {
    return DB::table('oauth_access_tokens')->where('revoked', 0)->get()->count();
  }

  /**
   * @return int
   */
  public function newUser()
  {
    return User::whereDay('created_at', Carbon::now())->get()->count();
  }

  public function totalUpgrade()
  {
    return GradeHistory::whereDay('created_at', Carbon::now())->where('credit', 0)->count();
  }
}
