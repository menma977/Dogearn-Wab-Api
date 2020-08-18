<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Binary;
use App\Model\Treding;
use App\Model\WithdrawQueue;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  /**
   * get Data
   */
  public function getData()
  {
    if (Auth::check()) {
      $isUserWinPlayingBot = Treding::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::now())->count();

      $data = [
        'isLogin' => Auth::check(),
        'version' => '0.1',
        'isUserWin' => $isUserWinPlayingBot,
      ];
    } else {
      $data = [
        'isLogin' => Auth::check(),
        'version' => '0.1',
        'isUserWin' => '0',
      ];
    }

    return response()->json($data, 200);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function login(Request $request): ?JsonResponse
  {
    $this->validate($request, [
      'phone' => 'required|string',
      'password' => 'required|string',
    ]);
    $type = filter_var($request->phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    if (Auth::attempt([$type => request('phone'), 'password' => request('password')])) {
      $user = Auth::user();
      if (($user !== null) && $user->suspand === 1) {
        $data = [
          'message' => 'The given data was invalid.',
          'errors' => [
            'validation' => ['Your account has been suspended.'],
          ],
        ];
        return response()->json($data, 500);
      }

      $token = Auth::user()->tokens;
      foreach ($token as $key => $value) {
        $value->delete();
      }
      $user->token = $user->createToken('Android')->accessToken;
      return response()->json([
        'token' => $user->token,
        'account_cookie' => $user->account_cookie,
      ], 200);
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
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'sponsor' => 'required|string|exists:users,phone',
      'email' => 'required|string|unique:users',
      'phone' => 'required|numeric|unique:users',
      'password' => 'required|string|min:6|confirmed',
      'transaction_password' => 'required|string|min:6',
      'transaction_password_confirmation' => 'required|string|same:transaction_password',
    ]);

    $responseCreateAccount = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
      'a' => 'CreateAccount',
      'Key' => '1b4755ced78e4d91bce9128b9a053cad',
    ]);

    try {
      $user = new User();

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
          if ($responseCreateUser->json()['success'] === 1) {
            $user->username_doge = $usernameDoge;
            $user->password_doge = $passwordDoge;
            $user->status = 0;

            $data = [
              'message' => 'Your Register Is Success',
            ];
          } else {
            $user->status = 1;
            $data = [
              'message' => 'You have registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration',
            ];
          }
        } catch (Exception $e) {
          $user->status = 1;
          $data = [
            'message' => 'You have registered correctly, but there is a problem in the registration section. Please wait up to 30 minutes for automatic re-registration',
          ];
        }
      } else {
        $data = [
          'message' => 'Your Register Is Invalid',
          'errors' => [
            'connection' => ['connection is lost when try register data.'],
          ],
        ];
      }

      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->password_junk = $request->password;
      $user->transaction_password = Hash::make($request->transaction_password);
      $user->phone = $request->phone;
      $user->account_cookie = $responseCreateAccount->json()['AccountCookie'];
      $user->wallet = $responseCreateAccount->json()['DepositAddress'];
      $user->level = 1;
      $user->save();

      $sponsor = User::where('phone', $request->sponsor)->first();

      $binary = new Binary();
      $binary->sponsor = $sponsor->id;
      $binary->down_line = $user->id;
      $binary->save();

      return response()->json($data, 200);
    } catch (Exception $e) {
      $data = [
        'message' => $e->getMessage(),
        'errors' => [
          'connection' => ['connection is lost when try register data.'],
        ],
      ];
      return response()->json($data, 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @return Authenticatable
   */
  public function show()
  {
    return Auth::user();
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
      'subject' => 'code for account changes',
      'massage' => '<Strong> your code is: "' . $code . '"</Strong></br>. this is the code to change your password, dont share it with anyone'
    ];
    Mail::send('mail.reRegistration', $data, function ($message) {
      $message->to(Auth::user()->email, 'code account')->subject('code for account changes');
      $message->from('admin@dogearn.com', 'DOGEARN');
    });
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
        'password' => 'required|string|confirmed',
      ]);

      $user->password = Hash::make($request->password);
      $user->password_junk = $request->password;
    }
    if ($request->transaction_password) {
      $this->validate($request, [
        'transaction_password' => 'required|string',
        'transaction_password_confirmation' => 'required|string|same:transaction_password',
      ]);
      $user->transaction_password = Hash::make($request->transaction_password);
    }
    $user->save();
    $data = [
      'message' => 'Your update Is Success',
    ];
    return response()->json($data, 200);
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
          'massage' => 'InsufficientFunds',
        ];
        return response()->json($data, 500);
      }

      if (isset($responseToJson['TooSmall'])) {
        $data = [
          'massage' => 'Balance Too Small',
        ];
        return response()->json($data, 500);
      }

      if (isset($responseToJson['error'])) {
        $data = [
          'massage' => $responseToJson['error'],
        ];
        return response()->json($data, 500);
      }

      $data = [
        'massage' => $responseToJson,
      ];
      return response()->json($data, 200);
    }

    $data = [
      'massage' => 'connection problem when processing data look for a better connection',
    ];
    return response()->json($data, 500);
  }

  public function isUserGradeProcess()
  {
    $onQueue = WithdrawQueue::where('user_id', Auth::user()->id)->count();

    $data = [
      'onQueue' => $onQueue
    ];

    return response()->json($data, 200);
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
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
