<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\PinLedger;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PinLedgerController extends Controller
{
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
        $pinLedger->description = Auth::user()->email . " send pin : " . $request->pin . " to " . $grabUser->email;
        $pinLedger->save();

        $pinLedger = new PinLedger();
        $pinLedger->user_id = $grabUser->id;
        $pinLedger->debit = $request->pin;
        $pinLedger->credit = 0;
        $pinLedger->description = $grabUser->email . " receive pin : " . $request->pin . " from " . Auth::user()->email;
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
