<?php

namespace App\Http\Controllers;

use App\Model\Binary;
use App\Model\GradeHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
      return "LOT " . $item->level;
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

    $dataFrom = Carbon::now();
    $dateNow = Carbon::now();

    $data = [
      'graphicGroup' => $graphicGroup,
      'lot' => $lot,
      'dateFrom' => $dataFrom,
      'dateNow' => $dateNow
    ];

    return view('home', $data);
  }

  /**
   * Show the application dashboard.
   *
   * @param Request $request
   * @return Renderable
   * @throws ValidationException
   */
  public function find(Request $request)
  {
    $this->validate($request, [
      'dateFrom' => 'required|date',
      'dateNow' => 'required|date',
    ]);

    $dateFrom = Carbon::parse($request->dateFrom);
    $dateTo = Carbon::parse($request->dateNow);

    $lot = User::where('status', 2)->whereBetween('created_at', [$dateFrom, $dateTo->addDay()])->orderBy('level', 'asc')->get()->groupBy(function ($item) {
      return "LOT " . $item->level;
    })->map(function ($item) {
      $item->total = $item->count();
      return $item;
    });

    $graphic = GradeHistory::whereBetween('created_at', [$dateFrom, $dateTo->addDay()])->where('credit', 0)->get();
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
      'lot' => $lot,
      'dateFrom' => $dateFrom,
      'dateNow' => $dateTo
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
   * @param $dateFrom
   * @param $dateNow
   * @return Application|Factory|View|int
   */
  public function newUserView($dateFrom, $dateNow)
  {
    $dateFrom = Carbon::parse($dateFrom);
    $dateTo = Carbon::parse($dateNow);
    $dataUser = User::whereBetween('created_at', [$dateFrom, $dateTo->addDay()])->get();
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
   * @param $dateFrom
   * @param $dateNow
   * @return Application|Factory|View
   */
  public function totalUpgradeView($dateFrom, $dateNow)
  {
    $dateFrom = Carbon::parse($dateFrom);
    $dateTo = Carbon::parse($dateNow);
    $dataUser = GradeHistory::whereBetween('created_at', [$dateFrom, $dateTo->addDay()])->where('credit', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('totalLot', $data);
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
   * @param $dateFrom
   * @param $dateNow
   * @return int
   */
  public function newUser($dateFrom, $dateNow)
  {
    $dateFrom = Carbon::parse($dateFrom);
    $dateTo = Carbon::parse($dateNow);
    return User::whereBetween('created_at', [$dateFrom, $dateTo->addDay()])->get()->count();
  }

  /**
   * @param $dateFrom
   * @param $dateNow
   * @return mixed
   */
  public function totalUpgrade($dateFrom, $dateNow)
  {
    $dateFrom = Carbon::parse($dateFrom);
    $dateTo = Carbon::parse($dateNow);
    return GradeHistory::whereBetween('created_at', [$dateFrom, $dateTo->addDay()])->where('credit', 0)->count();
  }

  /**
   * @return int
   */
  public function newUserNotVerified()
  {
    return User::where('status', 0)->get()->count();
  }
}
