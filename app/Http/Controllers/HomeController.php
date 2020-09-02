<?php

namespace App\Http\Controllers;

use App\Model\Binary;
use App\Model\GradeHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
    $lot = User::where('status', 2)->orderBy('level', 'asc')->get()->groupBy(function ($item) {
      return "LOT ".$item->level;
    })->map(function ($item) {
      $item->total = $item->count();
      return $item;
    });

    $graphic = GradeHistory::whereMonth('created_at', '<=', Carbon::now())->whereMonth('created_at', '>=', Carbon::now()->addMonths(-1))->where('credit', 0)->get();
    $graphicGroup = $graphic->groupBy(function ($item) {
      return (string)Carbon::parse($item->created_at)->format('d');
    })->map(function ($item) {
      $item->upgrade = $item->count();
      $item->newUser = 0;
      foreach ($item as $subItem) {
        $item->newUser = User::whereDay('created_at', $subItem->created_at)->count();
      }
      return $item;
    });

    $data = [
      'graphicGroup' => $graphicGroup,
      'lot' => $lot
    ];

    return view('home', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function onlineUserView()
  {
    $dataUser = DB::table('oauth_access_tokens')->where('revoked', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('onlineUser', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function newUserView()
  {
    $dataUser = User::whereDay('created_at', Carbon::now())->get();
    $dataUser->map(function ($item) {
      $binary = Binary::where('down_line', $item->id)->first();
      $item->sponsor = User::find($binary->sponsor);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('newUser', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function newUserNotVerifiedView()
  {
    $dataUser = User::where('status', 0)->get();

    $data = [
      'data' => $dataUser
    ];

    return view('notActiveUser', $data);
  }

  public function totalUpgradeView()
  {
    $dataUser = GradeHistory::whereDay('created_at', Carbon::now())->where('credit', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('totalLot', $data);
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

  /**
   * @return int
   */
  public function newUserNotVerified()
  {
    return User::where('status', 0)->get()->count();
  }

  public function totalUpgrade()
  {
    return GradeHistory::whereDay('created_at', Carbon::now())->where('credit', 0)->count();
  }
}
