<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\PinLedger;
use App\Model\Binary;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PinLedgerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    $pinLedgers = PinLedger::where('user_id', Auth::user()->id)->take(50)->orderBy('id', 'desc')->get();
    $pinLedgers->map(function ($item) {
      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    $arrayWallet = array();

    $binaries = Binary::where('down_line', Auth::user()->id)->first();
    if($binaries) {
        $sponsor = User::find($binaries->sponsor);
        while(true) {
            if ($sponsor->id == 1) {
                break;
            }
            $dataList = [
                'wallet' => $sponsor->wallet,
            ];
            array_push($arrayWallet, $dataList);
            $oldSponsor = $sponsor->id;
            $sponsor = User::find(Binary::where('down_line', $oldSponsor)->first()->sponsor);
        }
    }

    $binaries = Binary::where('sponsor', Auth::user()->id)->first();
    if($binaries) {
        $down_line = User::find($binaries->down_line);
        while(true) {
            $dataList = [
                'wallet' => $down_line->wallet,
            ];
            array_push($arrayWallet, $dataList);
            $old_down_line = $down_line->id;
            if(Binary::where('sponsor', $old_down_line)->first()) {
                $down_line = User::find(Binary::where('sponsor', $old_down_line)->first()->down_line);
            } else {
                break;
            }
        }
    }

    $data = [
      'pinLedgers' => $pinLedgers,
      'walletList' => $arrayWallet
    ];

    return response()->json($data, 200);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'wallet' => 'required|string|exists:users,wallet',
      'pin' => 'required|numeric|min:1',
      'secondaryPassword' => 'required|numeric'
    ]);

    if (Hash::check($request->secondaryPassword, Auth::user()->transaction_password)) {
      $pin = PinLedger::where('user_id', Auth::user()->id)->sum('debit') - PinLedger::where('user_id', Auth::user()->id)->sum('credit');
      if ($pin >= $request->pin) {
        $grabUser = User::where('wallet', $request->wallet)->first();
        $pinLedger = new PinLedger();
        $pinLedger->user_id = Auth::user()->id;
        $pinLedger->debit = 0;
        $pinLedger->credit = $request->pin;
        $pinLedger->description = Auth::user()->email . " send Pin to " . $grabUser->email;
        $pinLedger->save();

        $pinLedger = new PinLedger();
        $pinLedger->user_id = $grabUser->id;
        $pinLedger->debit = $request->pin;
        $pinLedger->credit = 0;
        $pinLedger->description = $grabUser->email . " receive Pin from " . Auth::user()->email;
        $pinLedger->save();

        $data = [
          'message' => 'success',
        ];

        return response()->json($data, 200);
      }

      $data = [
        'message' => 'insufficient pin to make transaction',
      ];

      return response()->json($data, 500);
    }

    $data = [
      'message' => 'Wrong Secondary password'
    ];

    return response()->json($data, 500);
  }
}
