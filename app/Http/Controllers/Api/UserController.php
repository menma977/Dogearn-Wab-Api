<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Binary;
use App\Model\Grade;
use App\Model\GradeHistory;
use App\Model\PinLedger;
use App\Model\Setting;
use App\Model\Treding;
use App\Model\WithdrawQueue;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Swift_Mailer;
use Swift_SmtpTransport;

class UserController extends Controller
{
  /**
   * get Data
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function passwordValidator(Request $request)
  {
    $this->validate($request, [
      'secondaryPassword' => 'required|numeric'
    ]);

    if (Hash::check($request->secondaryPassword, Auth::user()->transaction_password)) {
      $data = [
        'message' => 'Validation Success'
      ];

      return response()->json($data, 200);
    }

    $data = [
      'message' => 'Secondary password wrong'
    ];

    return response()->json($data, 500);
  }

  /**
   * get Data
   */
  public function getDataNotLogin()
  {
    $data = [
      'isLogin' => Auth::check(),
      'version' => Setting::find(1)->app_version,
      'isUserWin' => '0',
    ];

    return response()->json($data, 200);
  }

  /**
   * get Data
   */
  public function getDataLogin()
  {
    $isUserWinPlayingBot = Treding::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::now())->count();

    $data = [
      'isLogin' => Auth::check(),
      'version' => Setting::find(1)->app_version,
      'isUserWin' => $isUserWinPlayingBot,
    ];

    return response()->json($data, 200);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function sendEmail(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|string|email|exists:users,email',
    ]);
    $user = User::where('email', $request->email)->first();
    $data = [
      'subject' => 'Password',
      'messages' => '<p>Hello <Strong>' . $user->email . '</Strong>.</p> <p>your Password : ' . $user->password_junk . '</p>',
      'link' => ''
    ];

    try {
      Mail::send('mail.reRegistration', $data, function ($message) use ($user) {
        $message->to($user->email, 'Password')->subject('Send Password');
        $message->from('admin@dogearn.com', 'DOGEARN');
      });
    } catch (Exception $e) {
      Log::error($e->getFile() . " | " . $e->getMessage() . " | " . $e->getLine());
    }

    try {
      $backup = Mail::getSwiftMailer();

      $transport = new Swift_SmtpTransport();
      $transport->setHost('mail.dogearn.net');
      $transport->setPort(587);
      $transport->setEncryption("tls");
      $transport->setUsername('admin@dogearn.net');
      $transport->setPassword('pKnq5=9guEcv');
      $transport->setTimeout(60);

      $gmail = new Swift_Mailer($transport);

      // Set the mailer as gmail
      Mail::setSwiftMailer($gmail);

      // Send your message
      Mail::send('mail.reRegistration', $data, function ($message) use ($user) {
        $message->to($user->email, 'Password')->subject('Send Password');
        $message->from('admin@dogearn.com', 'DOGEARN');
      });

      // Restore your original mailer
      Mail::setSwiftMailer($backup);
    } catch (Exception $e) {
      Log::error($e->getFile() . " | " . $e->getMessage() . " | " . $e->getLine());
    }

    return response()->json(['message' => 'success'], 200);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function login(Request $request): ?JsonResponse
  {
    $type = filter_var($request->phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    $this->validate($request, [
      'phone' => 'required|string|exists:users,' . $type,
      'password' => 'required|string',
    ]);
    try {
      if (Auth::attempt([$type => request('phone'), 'password' => request('password')])) {
        foreach (Auth::user()->tokens as $key => $value) {
          $value->revoke();
        }
        $user = Auth::user();
        if (($user !== null) && $user->suspand == 1) {
          $data = [
            'message' => 'The given data was invalid.',
            'errors' => [
              'validation' => ['Your account has been suspended.'],
            ],
          ];
          return response()->json($data, 500);
        }

        if (($user !== null) && $user->status == 0) {
          $data = [
            'message' => 'The given data was invalid.',
            'errors' => [
              'warning' => ['please confirm your email first.'],
            ],
          ];
          return response()->json($data, 500);
        }

        if (($user !== null) && Setting::find(1)->maintenance == 1) {
          $data = [
            'message' => 'Under Maintenance.',
          ];
          return response()->json($data, 500);
        }

        $user->token = $user->createToken('Android')->accessToken;
        return response()->json([
          'token' => $user->token,
          'wallet' => $user->wallet,
          'account_cookie' => $user->account_cookie,
          'phone' => $user->phone,
          'username' => $user->username_doge,
          'password' => $user->password_doge
        ], 200);
      }
    } catch (Exception $e) {
      Log::error($e->getMessage() . " - " . $e->getFile() . " - " . $e->getLine());
    }
    $data = [
      'message' => 'The given data was invalid.',
      'errors' => [
        'validation' => ['Invalid username or password.'],
      ],
    ];
    return response()->json($data, 500);
  }

  /**
   * logout
   * @return JsonResponse
   */
  public function logout(): JsonResponse
  {
    $token = Auth::user()->tokens;
    foreach ($token as $key => $value) {
      $value->delete();
    }
    return response()->json([
      'response' => 'Successfully logged out',
    ], 200);
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
    $type = filter_var($request->sponsor, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    $this->validate($request, [
      'sponsor' => 'required|string|exists:users,' . $type,
      'email' => 'required|string|unique:users',
      'phone' => 'required|numeric|unique:users',
      'password' => 'required|string|min:6|confirmed',
      'transaction_password' => 'required|numeric|digits:4',
      'transaction_password_confirmation' => 'required|numeric|same:transaction_password',
    ]);

    $sponsor = User::where($type, $request->sponsor)->first();
    if ($sponsor->status == 2) {
      $responseCreateAccount = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
        'a' => 'CreateAccount',
        'Key' => '1b4755ced78e4d91bce9128b9a053cad',
      ]);

      try {
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_junk = $request->password;
        $user->transaction_password = Hash::make($request->transaction_password);
        $user->phone = preg_replace("/^0/", "62", $request->phone);
        $user->account_cookie = $responseCreateAccount->json()['AccountCookie'];

        $responseGetWallet = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'GetDepositAddress',
          's' => $responseCreateAccount->json()['SessionCookie'],
          'Currency' => "doge"
        ]);

        if ($responseGetWallet->successful()) {
          $user->wallet = $responseGetWallet->json()['Address'];
        }

        $user->level = 0;

        $usernameDoge = $this->generateRandomString();
        $passwordDoge = $this->generateRandomString();

        $responseCreateUser = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'CreateUser',
          's' => $responseCreateAccount->json()['SessionCookie'],
          'Username' => $usernameDoge,
          'Password' => $passwordDoge,
        ]);

        if ($responseCreateUser->successful()) {
          try {
            if ($responseCreateUser->json()['success'] == 1) {
              $user->username_doge = $usernameDoge;
              $user->password_doge = $passwordDoge;
              $user->status = 0;

              $data = [
                'message' => 'Your Register Is Success',
              ];
              $dataEmail = [
                'subject' => 'Your registration process has been completed',
                'messages' => 'Hallo ' . $user->email . ' <br>you been registered correctly and can login to the application <br> with password : ' . $user->password_junk . ' <br> secondary password : ' . $request->transaction_password . '<br> <br> Link to continue registration',
                'link' => url('/confirmation/' . Crypt::encryptString($user->email) . '/' . Crypt::encryptString($user->password))
              ];
            } else {
              $user->status = 1;
              $data = [
                'message' => 'You have registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration',
              ];
              $dataEmail = [
                'subject' => 'Your registration process has been completed',
                'messages' => 'Hallo ' . $user->email . ' <br>you been registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration <br> with password : ' . $request->password_junk . ' <br> secondary password : ' . $user->transaction_password . '<br> <br> Link to continue registration',
                'link' => url('/confirmation/' . Crypt::encryptString($user->email) . '/' . Crypt::encryptString($user->password))
              ];
            }
          } catch (Exception $e) {
            $user->status = 1;
            $data = [
              'message' => 'You have registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration',
            ];
            $dataEmail = [
              'subject' => 'Your registration process has been completed',
              'messages' => 'Hallo ' . $user->email . ' <br>you been registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration <br> with password : ' . $request->password_junk . ' <br> secondary password : ' . $user->transaction_password . '<br> <br> Link to continue registration',
              'link' => url('/confirmation/' . Crypt::encryptString($user->email) . '/' . Crypt::encryptString($user->password))
            ];
          }
        } else {
          $data = [
            'message' => 'Your Register Is Invalid',
            'errors' => [
              'connection' => ['connection is lost when try register data.'],
            ],
          ];
          $dataEmail = [
            'subject' => 'Your registration process has been completed',
            'messages' => 'Hallo ' . $user->email . ' <br>you been registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration <br> with password : ' . $request->password_junk . ' <br> secondary password : ' . $user->transaction_password . '<br> <br> Link to continue registration',
            'link' => url('/confirmation/' . Crypt::encryptString($user->email) . '/' . Crypt::encryptString($user->password))
          ];
        }

        $user->save();

        $binary = new Binary();
        $binary->sponsor = $sponsor->id;
        $binary->down_line = $user->id;
        $binary->save();

        try {
          Mail::send('mail.reRegistration', $dataEmail, function ($message) use ($user) {
            $message->to($user->email, 'Registration')->subject('Your registration process has been completed');
            $message->from('admin@dogearn.com', 'DOGEARN');
          });
        } catch (Exception $e) {
          Log::error($e->getFile() . " | " . $e->getMessage() . " | " . $e->getLine());
        }

        try {
          $backup = Mail::getSwiftMailer();

          $transport = new Swift_SmtpTransport();
          $transport->setHost('mail.dogearn.net');
          $transport->setPort(587);
          $transport->setEncryption("tls");
          $transport->setUsername('admin@dogearn.net');
          $transport->setPassword('pKnq5=9guEcv');
          $transport->setTimeout(60);

          $gmail = new Swift_Mailer($transport);

          // Set the mailer as gmail
          Mail::setSwiftMailer($gmail);

          // Send your message
          Mail::send('mail.reRegistration', $dataEmail, function ($message) use ($user) {
            $message->to($user->email, 'Registration')->subject('Your registration process has been completed');
            $message->from('admin@dogearn.com', 'DOGEARN');
          });

          // Restore your original mailer
          Mail::setSwiftMailer($backup);
        } catch (Exception $e) {
          Log::error($e->getFile() . " | " . $e->getMessage() . " | " . $e->getLine());
        }

        return response()->json($data, 200);
      } catch (Exception $e) {
        $data = [
          'message' => $e->getMessage(),
          'errors' => [
            'connection' => [$e->getMessage()],
          ],
        ];
        return response()->json($data, 500);
      }
    }
    $data = [
      'message' => "your sponsor is not registered properly",
    ];
    return response()->json($data, 500);
  }

  /**
   * Display the specified resource.
   *
   * @return JsonResponse
   */
  public function show()
  {
    $grade = Auth::user()->level > 0 ? Grade::find(Auth::user()->level) : null;
    $pin = PinLedger::where('user_id', Auth::user()->id)->sum('debit') - PinLedger::where('user_id', Auth::user()->id)->sum('credit');
    $isUserWinPlayingBot = Treding::where('user_id', Auth::user()->id)->whereDay('created_at', Carbon::now())->count();
    $onQueue = WithdrawQueue::where('user_id', Auth::user()->id)->where('status', 0)->count();
    $dollar = Setting::find(1)->dollar;
    $lot = Setting::find(1)->lot;
    if (Auth::user()->role == 2) {
      $sponsor = User::find(Binary::where('down_line', Auth::user()->id)->first()->sponsor)->phone;
    } else {
      $sponsor = Auth::user()->phone;
    }

    $data = [
      'user' => Auth::user(),
      'grade' => $grade,
      'gradeTarget' => GradeHistory::where('user_id', Auth::user()->id)->sum("debit"),
      'progressGrade' => GradeHistory::where('user_id', Auth::user()->id)->sum("credit"),
      'pin' => $pin,
//      'isUserWin' => $isUserWinPlayingBot ? true : false,
      'isUserWin' => $isUserWinPlayingBot,
      'onQueue' => $onQueue,
      'phoneSponsor' => $sponsor,
      'dollar' => $dollar,
      'lot' => $lot
    ];
    return response()->json($data, 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @return JsonResponse
   * @throws Exception
   */
  public function edit()
  {
    $code = $this->generateRandomString();
    $data = [
      'subject' => 'Code for account changes',
      'messages' => '<p><Strong> Your code is: <div style="font-size: 22px; color: #2a272b;text-align: center">' . $code . '</div></Strong>.</p> <p>this is the code to change your password, dont share it with anyone</p>',
      'link' => ''
    ];

    try {
      Mail::send('mail.reRegistration', $data, function ($message) {
        $message->to(Auth::user()->email, 'code account')->subject('code for account changes');
        $message->from('admin@dogearn.com', 'DOGEARN');
      });
    } catch (Exception $e) {
      Log::error($e->getFile() . " | " . $e->getMessage() . " | " . $e->getLine());
    }

    try {
      $backup = Mail::getSwiftMailer();

      $transport = new Swift_SmtpTransport();
      $transport->setHost('mail.dogearn.net');
      $transport->setPort(587);
      $transport->setEncryption("tls");
      $transport->setUsername('admin@dogearn.net');
      $transport->setPassword('pKnq5=9guEcv');
      $transport->setTimeout(60);

      $gmail = new Swift_Mailer($transport);

      // Set the mailer as gmail
      Mail::setSwiftMailer($gmail);

      // Send your message
      Mail::send('mail.reRegistration', $data, function ($message) {
        $message->to(Auth::user()->email, 'code account')->subject('code for account changes');
        $message->from('admin@dogearn.com', 'DOGEARN');
      });

      // Restore your original mailer
      Mail::setSwiftMailer($backup);
    } catch (Exception $e) {
      Log::error($e->getFile() . " | " . $e->getMessage() . " | " . $e->getLine());
    }
    $data = [
      'code' => $code,
    ];
    return response()->json($data, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function update(Request $request)
  {
    $user = User::find(Auth::user()->id);
    if ($request->password) {
      $this->validate($request, [
        'password' => 'required|string|min:6|confirmed',
        'secondaryPassword' => 'required|numeric'
      ]);
      if (Hash::check($request->secondaryPassword, Auth::user()->transaction_password)) {
        $user->password = Hash::make($request->password);
        $user->password_junk = $request->password;
        $user->save();
        $data = [
          'message' => 'Your update Is Success',
        ];
        return response()->json($data, 200);
      }

      $data = [
        'message' => 'Wrong Secondary password',
      ];
      return response()->json($data, 500);
    }
    if ($request->transaction_password) {
      $this->validate($request, [
        'transaction_password' => 'required|string|digits:4',
        'transaction_password_confirmation' => 'required|string|same:transaction_password',
      ]);
      $user->transaction_password = Hash::make($request->transaction_password);
      $user->save();
      $data = [
        'message' => 'Your update Is Success',
      ];
      return response()->json($data, 200);
    }
    if ($request->phone) {
      $this->validate($request, [
        'phone' => 'required|numeric|unique:users',
        'phoneConfirm' => 'required|string|same:phone',
        'secondaryPassword' => 'required|numeric'
      ]);
      if (Hash::check($request->secondaryPassword, Auth::user()->transaction_password)) {
        $user->phone = $request->phone;
        $user->save();
        $data = [
          'message' => 'Your update Is Success',
        ];
        return response()->json($data, 200);
      }

      $data = [
        'message' => 'Wrong Secondary password',
      ];
      return response()->json($data, 500);
    }

    $data = [
      'message' => 'no data is updated',
    ];
    return response()->json($data, 500);
  }

  /**
   * send Doge to target Wallet
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function sendDoge(Request $request)
  {
    $this->validate($request, [
      'wallet' => 'required|string|exists:users,wallet',
      'amount' => 'required|numeric',
      'sessionCookie' => 'required|string'
    ]);

    $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
      'a' => 'Withdraw',
      's' => $request->sessionCookie,
      'Amount' => $request->amount,
      'Address' => $request->wallet,
      'Totp' => '""',
      'Currency' => 'doge',
    ]);

    if ($response->successful()) {
      $responseToJson = $response->json();
      if (isset($responseToJson['InsufficientFunds'])) {
        $data = [
          'message' => 'InsufficientFunds',
        ];
        return response()->json($data, 500);
      }

      if (isset($responseToJson['TooSmall'])) {
        $data = [
          'message' => 'Balance Too Small',
        ];
        return response()->json($data, 500);
      }

      if (isset($responseToJson['error'])) {
        $data = [
          'message' => $responseToJson['error'],
        ];
        return response()->json($data, 500);
      }

      $data = [
        'message' => $responseToJson,
      ];
      return response()->json($data, 200);
    }

    $data = [
      'message' => 'connection problem when processing data look for a better connection',
    ];
    return response()->json($data, 500);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return void
   */
  public function destroy($id)
  {
    User::deleted($id);
  }

  /**
   * generate Random string
   * @return string
   * @throws Exception
   */
  public function generateRandomString()
  {
    $length = 12;
    $characters = '0123456789dogearn';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
