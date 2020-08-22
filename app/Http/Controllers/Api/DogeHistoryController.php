<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\DogeHistory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DogeHistoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    $dogeHistory = DogeHistory::take(50)->orderBy('id', 'desc')->get();
    $dogeHistory->map(function ($item) {
      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    $data = [
      'dogeHistory' => $dogeHistory
    ];

    return response()->json($data, 200);
  }
}
