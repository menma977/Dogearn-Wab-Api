<?php

namespace App\Http\Controllers;

use App\Model\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|Response|View
   */
  public function index()
  {
    $setting = Setting::find(1);

    $data = [
      'setting' => $setting
    ];

    return view('setting.index', $data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse|void
   * @throws ValidationException
   */
  public function updateIt(Request $request)
  {
    $this->validate($request, [
      'wallet' => 'required|string',
    ]);

    $setting = Setting::find(1);
    $setting->wallet_it = $request->wallet;
    $setting->save();

    return redirect()->back();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse|void
   * @throws ValidationException
   */
  public function fee(Request $request)
  {
    $this->validate($request, [
      'fee' => 'required|string',
    ]);

    $setting = Setting::find(1);
    $setting->fee = $request->fee;
    $setting->save();

    return redirect()->back();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse|void
   * @throws ValidationException
   */
  public function app(Request $request)
  {
    $this->validate($request, [
      'version' => 'required|numeric',
    ]);

    $setting = Setting::find(1);
    $setting->app_version = $request->version;
    $setting->save();

    DB::table('oauth_access_tokens')->delete();

    return redirect()->back();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param $status
   * @return RedirectResponse|void
   */
  public function shotDown($status)
  {
    $setting = Setting::find(1);
    $setting->maintenance = $status;
    $setting->save();

    if ($status == 1) {
      DB::table('oauth_access_tokens')->delete();
    }

    return redirect()->back();
  }
}
