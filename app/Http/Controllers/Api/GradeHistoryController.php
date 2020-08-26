<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Binary;
use App\Model\Grade;
use App\Model\GradeHistory;
use App\Model\Level;
use App\Model\PinLedger;
use App\Model\Setting;
use App\Model\WithdrawQueue;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class GradeHistoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index()
  {
    $gradeHistory = GradeHistory::where('user_id', Auth::user()->id)->take(50)->orderBy('id', 'desc')->get();
    $gradeHistory->map(function ($item) {
      if ($item->user_id == 0) {
        $item->email = "Network Fee";
      } else {
        $item->email = User::find($item->user_id)->wallet;
      }
      $item->emailTarget = User::find($item->target)->wallet;

      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    $data = [
      'gradeHistory' => $gradeHistory
    ];

    return response()->json($data, 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return JsonResponse
   */
  public function create()
  {
    $gradeId = Grade::orderBy('id', 'desc')->get()->first();
    if (Auth::user()->level >= $gradeId->id) {
      $grade = Grade::find($gradeId->id);
    } else {
      $grade = Grade::find(Auth::user()->level + 1);
    }

    $totalValue = $grade->price;

    $level = Level::all();
    $dataQueue = array();
    $binaries = Binary::where('down_line', Auth::user()->id)->first();

    $itTotalValue = $grade->price * Setting::find(1)->fee / 100;
    $dataList = [
      'user' => "Network Fee",
      'value' => $grade->price * Setting::find(1)->fee / 100,
    ];
    $totalValue -= $itTotalValue;
    array_push($dataQueue, $dataList);

    $levels = 1;
    if ($binaries) {
      $sponsor = User::find($binaries->sponsor);
      while (true) {
        if ($sponsor->id == 1 || $levels == 7) {
          break;
        }

        $getGradeSponsorSum = GradeHistory::where('user_id', $sponsor->id)->sum('debit') - GradeHistory::where('user_id', $sponsor->id)->sum('credit');
        if ($getGradeSponsorSum >= 0 && $sponsor->role == 2 && $sponsor->level >= 0 && $sponsor->level >= $grade->id) {
          $totalValue -= $grade->price * $level->find($levels)->percent / 100;
          $dataList = [
            'user' => $sponsor->wallet,
            'value' => $grade->price * $level->find($levels)->percent / 100,
          ];
          array_push($dataQueue, $dataList);
          $levels++;
        }

        $oldSponsor = $sponsor->id;
        $sponsor = User::find(Binary::where('down_line', $oldSponsor)->first()->sponsor);
      }
//      foreach ($level as $id => $item) {
//        try {
//          if ($sponsor->id == 1) {
//            break;
//          }
//
//          $getGradeSponsorSum = GradeHistory::where('user_id', $sponsor->id)->sum('debit') - GradeHistory::where('user_id', $sponsor->id)->sum('credit');
//          if ($getGradeSponsorSum >= 0 && $sponsor->role == 2 && $sponsor->level >= 0) {
//            if ($sponsor->level >= $grade->id) {
//              $totalValue -= $grade->price * $item->percent / 100;
//              $dataList = [
//                'user' => $sponsor->wallet,
//                'value' => $grade->price * $item->percent / 100,
//              ];
//              array_push($dataQueue, $dataList);
//            } else {
//              $sponsorGrade = Grade::find($sponsor->level);
//              if ($sponsorGrade) {
//                $totalValue -= $sponsorGrade->price * $item->percent / 100;
//                $dataList = [
//                  'user' => $sponsor->wallet,
//                  'value' => $sponsorGrade->price * $item->percent / 100,
//                ];
//                array_push($dataQueue, $dataList);
//              }
//            }
//          }
//
//          $oldSponsor = $sponsor->id;
//          $sponsor = User::find(Binary::where('down_line', $oldSponsor)->first()->sponsor);
//        } catch (Exception $e) {
//          Log::warning($e->getMessage() . " - LINE : " . $e->getLine());
//        }
//      }
    }

    $dataList = [
      'user' => "Doge to be shared",
      'value' => $totalValue,
    ];
    array_push($dataQueue, $dataList);

    $data = [
      'grade' => $grade,
      'dataQueue' => $dataQueue,
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
      'balance' => 'required|numeric',
      'secondaryPassword' => 'required|numeric'
    ]);

    if (Hash::check($request->secondaryPassword, Auth::user()->transaction_password)) {
      $sumPin = PinLedger::where('user_id', Auth::user()->id)->sum('debit') - PinLedger::where('user_id', Auth::user()->id)->sum('credit');
      if ($sumPin) {
        $user = User::find(Auth::user()->id);
        $getGradeById = Grade::find($request->grade);
        if ($sumPin >= $getGradeById->pin && $request->balance > $getGradeById->price) {
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

          $user->level = $grade->upgrade_level;

          $pinLedger = new PinLedger();
          $pinLedger->user_id = Auth::user()->id;
          $pinLedger->debit = 0;
          $pinLedger->credit = $getGradeById->pin;
          $pinLedger->description = Auth::user()->email . " use pin : " . $getGradeById->pin . " to Upgrade Grade " . $getGradeById->id;

          $level = Level::all();

          $totalValue = $getGradeById->price;
          $binaries = Binary::where('down_line', Auth::user()->id)->first();
          $levels = 1;

          $withdrawQueue = new WithdrawQueue();
          $withdrawQueue->user_id = Auth::user()->id;
          $withdrawQueue->status = 0;
          $withdrawQueue->send_to = 0;
          $withdrawQueue->send_value = $getGradeById->price * Setting::find(1)->fee / 100;
          $totalValue -= $withdrawQueue->send_value;
          $withdrawQueue->total = $totalValue;
          $withdrawQueue->save();

          if ($binaries) {
            $sponsor = User::find($binaries->id);
            while (true) {
              if ($sponsor->id == 1 || $levels == 7) {
                break;
              }

              $withdrawQueue = new WithdrawQueue();
              $withdrawQueue->user_id = Auth::user()->id;
              $withdrawQueue->status = 0;
              $withdrawQueue->send_to = $sponsor->id;

              $getGradeSponsorSum = GradeHistory::where('user_id', $sponsor->id)->sum('debit') - GradeHistory::where('user_id', $sponsor->id)->sum('credit');
              if ($getGradeSponsorSum >= 0 && $sponsor->role == 2 && $sponsor->level >= 0 && $sponsor->level >= $grade->id) {
                $withdrawQueue->send_value = $getGradeById->price * $level->find($levels)->percent / 100;
                $totalValue -= $withdrawQueue->send_value;
                $withdrawQueue->total = $totalValue;
                $withdrawQueue->save();
                $levels++;
              }

              $oldSponsor = $sponsor->id;
              $sponsor = User::find(Binary::where('down_line', $oldSponsor)->first()->sponsor);
            }

//            foreach ($level as $id => $item) {
//              try {
//                if ($sponsor->id == 1) {
//                  break;
//                }
//
//                $withdrawQueue = new WithdrawQueue();
//                $withdrawQueue->user_id = Auth::user()->id;
//                $withdrawQueue->status = 0;
//                $withdrawQueue->send_to = $sponsor->id;
//
//                $getGradeSponsorSum = GradeHistory::where('user_id', $sponsor->id)->sum('debit') - GradeHistory::where('user_id', $sponsor->id)->sum('credit');
//
//                if ($getGradeSponsorSum > 0 && $sponsor->role == 2 && $sponsor->level > 0) {
//                  if ($sponsor->level >= $getGradeById->id) {
//                    $withdrawQueue->send_value = $getGradeById->price * $item->percent / 100;
//                    $totalValue -= $withdrawQueue->send_value;
//                    $withdrawQueue->total = $totalValue;
//                    $withdrawQueue->save();
//                  } else {
//                    $sponsorGrade = Grade::find($sponsor->level);
//                    if ($sponsorGrade) {
//                      $withdrawQueue->send_value = $sponsorGrade->price * $item->percent / 100;
//                      $totalValue -= $withdrawQueue->send_value;
//                      $withdrawQueue->total = $totalValue;
//                      $withdrawQueue->save();
//                    }
//                  }
//                }
//
//                $oldSponsor = $sponsor->id;
//                $sponsor = User::find(Binary::where('down_line', $oldSponsor)->first()->sponsor);
//              } catch (Exception $e) {
//                Log::warning($e->getMessage() . " - LINE : " . $e->getLine());
//              }
//            }
          }

          $withdrawQueue = new WithdrawQueue();
          $withdrawQueue->user_id = Auth::user()->id;
          $withdrawQueue->status = 0;
          $withdrawQueue->send_to = User::find(1)->id;
          $withdrawQueue->send_value = $totalValue;
          $withdrawQueue->total = 0;
          $withdrawQueue->save();

          $user->save();
          $grade->save();
          $pinLedger->save();

          $data = [
            'message' => 'Your upgrade is currently in the queue'
          ];

          return response()->json($data, 200);
        }

        $data = [
          'message' => 'insufficient pin or balance to make transaction',
        ];

        return response()->json($data, 500);
      }

      $data = [
        'message' => 'insufficient pin or balance to make transaction'
      ];

      return response()->json($data, 500);
    }

    $data = [
      'message' => 'Secondary password wrong'
    ];

    return response()->json($data, 500);
  }
}
