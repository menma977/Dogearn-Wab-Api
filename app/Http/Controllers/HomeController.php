<?php

namespace App\Http\Controllers;

use App\Model\Binary;
use App\Model\GradeHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Swift_Mailer;
use Swift_SmtpTransport;

class HomeController extends Controller
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
   * Show the application dashboard.
   *
   * @return Renderable
   */
  public function index()
  {
//    $backup = Mail::getSwiftMailer();
//
//    $transport = new Swift_SmtpTransport();
//    $transport->setHost('mail.dogearn.net');
//    $transport->setPort(465);
//    $transport->setEncryption("ssl");
//    $transport->setUsername('admin@dogearn.net');
//    $transport->setPassword('DavidAndra58');
//
//    $gmail = new Swift_Mailer($transport);
//
//    // Set the mailer as gmail
//    Mail::setSwiftMailer($gmail);
//
//    $dataEmail = [
//      'subject' => 'Your registration process has been completed',
//      'messages' => 'Hallo aww <br>you been registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration <br> with password : 123 <br> secondary password : 123<br> <br> Link to continue registration',
//      'link' => ''
//    ];
//
//    // Send your message
//    Mail::send('mail.reRegistration', $dataEmail, function ($message) {
//      $message->to('com.owl.minerva@gmail.com', 'Registration')->subject('Your registration process has been completed');
//      $message->from('admin@dogearn.com', 'DOGEARN');
//    });
//
//    // Restore your original mailer
//    Mail::setSwiftMailer($backup);

    $graphic = GradeHistory::whereMonth('created_at', '<=', Carbon::now())->where('credit', 0)->get();
    $graphicGroup = $graphic->groupBy(function ($item) {
      return (string)Carbon::parse($item->created_at)->format('d');
    })->map(function ($item) {
      $item->upgrade = $item->count();
      $item->newUser = 0;
      foreach ($item as $subItem) {
        $item->newUser = User::whereDay('created_at', $subItem->created_at)->count();
      }
      return $item;
    });

    $data = [
      'graphicGroup' => $graphicGroup
    ];

    return view('home', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function onlineUserView()
  {
    $dataUser = DB::table('oauth_access_tokens')->where('revoked', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('onlineUser', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function newUserView()
  {
    $dataUser = User::whereDay('created_at', Carbon::now())->get();
    $dataUser->map(function ($item) {
      $binary = Binary::where('down_line', $item->id)->first();
      $item->sponsor = User::find($binary->sponsor);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('newUser', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function newUserNotVerifiedView()
  {
    $dataUser = User::where('status', 0)->get();

    $data = [
      'data' => $dataUser
    ];

    return view('notActiveUser', $data);
  }

  public function totalUpgradeView()
  {
    $dataUser = GradeHistory::whereDay('created_at', Carbon::now())->where('credit', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('totalLot', $data);
  }

  /**
   * @return int
   */
  public function totalUser()
  {
    return User::all()->count();
  }

  /**
   * @return int
   */
  public function onlineUser()
  {
    return DB::table('oauth_access_tokens')->where('revoked', 0)->get()->count();
  }

  /**
   * @return int
   */
  public function newUser()
  {
    return User::whereDay('created_at', Carbon::now())->get()->count();
  }

  /**
   * @return int
   */
  public function newUserNotVerified()
  {
    return User::where('status', 0)->get()->count();
  }

  public function totalUpgrade()
  {
    return GradeHistory::whereDay('created_at', Carbon::now())->where('credit', 0)->count();
  }
}
