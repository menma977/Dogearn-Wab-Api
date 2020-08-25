<?php

namespace App\Http\Controllers;

use App\Model\Binary;
use App\Model\Grade;
use App\Model\GradeHistory;
use App\Model\PinLedger;
use App\Model\Treding;
use App\Model\WithdrawQueue;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
  /**
   * @param $email
   * @param $password
   * @return Application|RedirectResponse|Redirector
   */
  public function indexConfirmation($email, $password)
  {
    $user = User::where('email', Crypt::decryptString($email))->where('password', Crypt::decryptString($password))->first();
    if ($user) {
      $user->status = 2;
      $user->save();
    }

    return redirect('/success');
  }

  /**
   * @return Application|RedirectResponse|Redirector
   */
  public function index()
  {
    $users = User::all();

    $data = [
      'users' => $users
    ];

    return view('user.index', $data);
  }

  /**
   * @param $id
   * @param $status
   * @return RedirectResponse
   */
  public function suspend($id, $status)
  {
    $user = User::find($id);
    $user->suspend = $status;
    $user->save();

    return redirect()->back();
  }

  /**
   * @param $id
   * @return RedirectResponse
   */
  public function activate($id)
  {
    $user = User::find($id);
    $user->status = 2;
    $user->save();

    return redirect()->back();
  }

  /**
   * @param $id
   * @return Application|Factory|View
   */
  public function show($id)
  {
    $user = User::find($id);
    $grade = Auth::user()->level > 0 ? Grade::find($user->level) : null;
    $pin = PinLedger::where('user_id', $user->id)->sum('debit') - PinLedger::where('user_id', $user->id)->sum('credit');
    $isUserWinPlayingBot = Treding::where('user_id', $user->id)->whereDate('created_at', Carbon::now())->count();
    $onQueue = WithdrawQueue::where('user_id', $user->id)->where('status', 0)->count();

    $sponsorLine = $user->email;
    $binaries = Binary::where('down_line', $user->id)->first();
    if ($binaries) {
      $sponsor = User::find($binaries->sponsor);
      while (true) {
        $sponsorLine .= " -> " . $sponsor->email;

        if ($sponsor->role == 1) {
          break;
        }

        $oldSponsor = $sponsor->id;
        $sponsor = User::find(Binary::where('down_line', $oldSponsor)->first()->sponsor);
      }

      if ($user->role == 2) {
        $sponsor = User::find(Binary::where('down_line', $user->id)->first()->sponsor)->phone;
      } else {
        $sponsor = $user->phone;
      }
    } else {
      $sponsor = "";
    }

    $data = [
      'user' => $user,
      'grade' => $grade,
      'gradeTarget' => GradeHistory::where('user_id', Auth::user()->id)->sum("debit"),
      'progressGrade' => GradeHistory::where('user_id', Auth::user()->id)->sum("credit"),
      'pin' => $pin,
      'isUserWin' => $isUserWinPlayingBot,
      'onQueue' => $onQueue,
      'phoneSponsor' => $sponsor,
      'sponsorLine' => $sponsorLine
    ];

    return view('user.show', $data);
  }

  /**
   * @param Request $request
   * @param $id
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function updatePassword(Request $request, $id)
  {
    $this->validate($request, [
      'password' => 'required|string|min:6|confirmed',
    ]);
    $user = User::find($id);
    $user->password = Hash::make($request->password);
    $user->password_junk = $request->password;
    $user->save();

    return redirect()->back();
  }

  /**
   * @param Request $request
   * @param $id
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function updateSecondaryPassword(Request $request, $id)
  {
    $this->validate($request, [
      'new_secondary_password' => 'required|string|digits:4',
      'retype_new_secondary_password' => 'required|string|same:new_secondary_password',
    ]);
    $user = User::find($id);
    $user->transaction_password = Hash::make($request->new_secondary_password);
    $user->save();

    return redirect()->back();
  }

  /**
   * @param Request $request
   * @param $id
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function updatePhone(Request $request, $id)
  {
    $this->validate($request, [
      'new_phone_number' => 'required|numeric|unique:users,phone',
      'retype_new_phone_number' => 'required|string|same:new_phone_number',
    ]);
    $user = User::find($id);
    $user->phone = $request->new_phone_number;
    $user->save();

    return redirect()->back();
  }

  /**
   * @param $id
   * @return GradeHistory
   */
  public function lotList($id)
  {
    $gradeHistory = GradeHistory::where('user_id', $id)->take(50)->orderBy('id', 'desc')->get();
    $gradeHistory->map(function ($item) {
      if ($item->user_id == 0) {
        $item->email = "Network Fee";
      } else {
        $item->email = User::find($item->user_id)->email;
      }

      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    return $gradeHistory;
  }

  /**
   * @param $id
   * @return GradeHistory
   */
  public function pinList($id)
  {
    $pinLedgers = PinLedger::where('user_id', $id)->take(50)->orderBy('id', 'desc')->get();
    $pinLedgers->map(function ($item) {
      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    return $pinLedgers;
  }
}
