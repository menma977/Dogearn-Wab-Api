<?php

namespace App\Http\Controllers;

use App\Model\Binary;
use App\Model\Grade;
use App\Model\GradeHistory;
use App\Model\Level;
use App\Model\PinLedger;
use App\Model\WithdrawQueue;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Validation\ValidationException;

class GradeHistoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return JsonResponse
   */
  public function create()
  {
    $gradeList = Grade::all();

    $data = [
      'grades' => $gradeList
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
    $this->validate($request, [
      'grade' => 'required|numeric|exists:grades,id',
    ]);

    $user = User::find(Auth::user()->id);
    $gradeHistoryList = GradeHistory::where('user_id', $user->id)->get();

    $getGradeList = Grade::find($request->gade);

    $grade = new GradeHistory();
    $grade->user_id = Auth::user()->id;
    $grade->debit = $getGradeList->price;
    $grade->credit = 0;
    if ($gradeHistoryList->count()) {
      $grade->upgrade_level = $gradeHistoryList->first()->upgrade_level + 1;
    } else {
      $grade->upgrade_level = 1;
    }

    $pinLedger = new PinLedger();
    $pinLedger->user_id = Auth::user()->id;
    $pinLedger->debit = $getGradeList->pin;
    $pinLedger->credit = 0;
    $pinLedger->description = Auth::user()->phone + " add pin : " + $getGradeList->pin;

    $level = Level::all();

    $sponsor = User::find(Binary::where('down_line', Auth::user()->id)->first()->sponsor);
    $totalValue = $getGradeList->price;
    foreach ($level as $id => $item) {
      try {
        $withdrawQueue = new WithdrawQueue();
        $withdrawQueue->user_id = Auth::user()->id;
        $withdrawQueue->status = 0;
        $withdrawQueue->send_to = $sponsor->id;
        if ($sponsor->level >= $getGradeList->id) {
          $withdrawQueue->send_value = $getGradeList->price * $item->percent / 100;
        } else {
          $sponsorGrade = Grade::find($sponsor->level);
          $withdrawQueue->send_value = $sponsorGrade->price * $item->percent / 100;
        }
        $totalValue -= $withdrawQueue->send_value;
        $withdrawQueue->total = $totalValue;

        $withdrawQueue->save();

        $sponsor = User::find(Binary::where('down_line', $sponsor->id)->first()->sponsor);
      } catch (Exception $e) {
      }
    }

    $grade->save();
    $pinLedger->save();

    $data = [
      'message' => 'Your upgrade is currently in the queue'
    ];

    return response()->json($data, 200);
  }

  /**
   * Display the specified resource.
   *
   * @param GradeHistory $grade_history
   * @return Response
   */
  public function show(GradeHistory $grade_history)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param GradeHistory $grade_history
   * @return Response
   */
  public function edit(GradeHistory $grade_history)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param GradeHistory $grade_history
   * @return Response
   */
  public function update(Request $request, GradeHistory $grade_history)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param GradeHistory $grade_history
   * @return Response
   */
  public function destroy(GradeHistory $grade_history)
  {
    //
  }
}
