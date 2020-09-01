<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\DogeHistory;
use App\Model\Treding;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DogeHistoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    $dogeHistory = DogeHistory::where('user_id', Auth::user()->id)->take(50)->orderBy('id', 'desc')->get();
    $dogeHistory->map(function ($item) {
      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    $data = [
      'dogeHistory' => $dogeHistory
    ];

    return response()->json($data, 200);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'start_balance' => 'required|numeric',
      'end_balance' => 'required|numeric',
    ]);

    $data = new Treding();
    $data->status = 1;
    $data->user_id = Auth::user()->id;
    $data->start_balance = $request->start_balance;
    $data->end_balance = $request->end_balance;
    $data->save();

    return response()->json(['message' => 'success'], 200);
  }
}
