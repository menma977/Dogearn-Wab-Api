<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Binary;
use App\Model\Grade;
use App\Model\GradeHistory;
use App\Model\Level;
use App\Model\PinLedger;
use App\Model\WithdrawQueue;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Validation\ValidationException;

class GradeHistoryController extends Controller
{
  /**
   * Show the form for creating a new resource.
   *
   * @return JsonResponse
   */
  public function create()
  {
    $gradeList = Grade::all();

    $data = [
      'grades' => $gradeList,
    ];

    return response()->json($data, 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $sumPin = PinLedger::where('user_id', Auth::user()->id)->sum('debit') - PinLedger::where('user_id', Auth::user()->id)->sum('credit');
    if ($sumPin) {
      $this->validate($request, [
        'grade' => 'required|numeric|exists:grades,id',
      ]);

      $user = User::find(Auth::user()->id);
      $getGradeById = Grade::find($request->grade);
      if ($sumPin >= $getGradeById->pin) {
        $gradeHistoryList = GradeHistory::where('user_id', $user->id)->get();
        $grade = new GradeHistory();
        $grade->user_id = Auth::user()->id;
        $grade->debit = $getGradeById->price * 5;
        $grade->credit = 0;
        if ($gradeHistoryList->count()) {
          $grade->upgrade_level = $gradeHistoryList->first()->upgrade_level + 1;
        } else {
          $grade->upgrade_level = 1;
        }

        $pinLedger = new PinLedger();
        $pinLedger->user_id = Auth::user()->id;
        $pinLedger->debit = 0;
        $pinLedger->credit = $getGradeById->pin;
        $pinLedger->description = Auth::user()->phone . " use pin : " . $getGradeById->pin . " to Upgrade Grade " . $getGradeById->id;

        $level = Level::all();

        $sponsor = User::find(Binary::where('down_line', Auth::user()->id)->first()->sponsor);
        $totalValue = $getGradeById->price;
        foreach ($level as $id => $item) {
          try {
            $withdrawQueue = new WithdrawQueue();
            $withdrawQueue->user_id = Auth::user()->id;
            $withdrawQueue->status = 0;
            $withdrawQueue->send_to = $sponsor->id;
            if ($sponsor->level >= $getGradeById->id) {
              $withdrawQueue->send_value = $getGradeById->price * $item->percent / 100;
            } else {
              $sponsorGrade = Grade::find($sponsor->level);
              $withdrawQueue->send_value = $sponsorGrade->price * $item->percent / 100;
            }
            $totalValue -= $withdrawQueue->send_value;
            $withdrawQueue->total = $totalValue;

            $getGradeSponsorSum = GradeHistory::where('user_id', $sponsor->id)->sum('debit') - GradeHistory::where('user_id', $sponsor->id)->sum('credit');

            if ($sponsor->level >= $user->level && $getGradeSponsorSum >= 0) {
              $withdrawQueue->save();
            }

            $sponsor = User::find(Binary::where('down_line', $sponsor->id)->first()->sponsor);
          } catch (Exception $e) {
          }
        }

        $withdrawQueue = new WithdrawQueue();
        $withdrawQueue->user_id = Auth::user()->id;
        $withdrawQueue->status = 0;
        $withdrawQueue->send_to = User::find(1)->id;
        if ($sponsor->level >= $getGradeById->id) {
          $withdrawQueue->send_value = $totalValue;
        } else {
          $withdrawQueue->send_value = $totalValue;
        }
        $withdrawQueue->total = 0;

        $withdrawQueue->save();

        $grade->save();
        $pinLedger->save();

        $data = [
          'massage' => 'Your upgrade is currently in the queue'
        ];

        return response()->json($data, 200);
      }

      $data = [
        'massage' => 'insufficient pin to make the transaction'
      ];

      return response()->json($data, 500);
    }

    $data = [
      'massage' => 'insufficient pin to make the transaction'
    ];

    return response()->json($data, 500);
  }
}
