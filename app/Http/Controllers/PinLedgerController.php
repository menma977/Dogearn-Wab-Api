<?php

namespace App\Http\Controllers;

use App\Model\PinLedger;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PinLedgerController extends Controller
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
   * Display a listing of the resource.
   *
   * @return Application|Factory|Response|View
   */
  public function index()
  {
    $users = User::all();

    $pinLedgers = PinLedger::orderBy('id', 'desc')->take(1000)->get();
    $pinLedgers->map(function ($item) {
      $item->email = User::find($item->user_id)->email;
    });

    $data = [
      'users' => $users,
      'pinLedgers' => $pinLedgers
    ];

    return view('pin.index', $data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'user' => 'required|numeric|exists:users,id',
      'total_pin' => 'required|numeric'
    ]);

    $user = User::find($request->user);

    $pin = new PinLedger();
    $pin->user_id = $user->id;
    $pin->debit = $request->total_pin;
    $pin->credit = 0;
    $pin->description = 'ADMIN added ' . $request->total_pin . ' PIN to ' . $user->email;
    $pin->save();

    return redirect()->back();
  }
}
