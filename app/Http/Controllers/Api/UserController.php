<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return void
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return void
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   * @throws Exception
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'username' => 'required|string|unique:users',
      'email' => 'required|string|unique:users',
      'phone' => 'required|numeric|unique:users',
      'password' => 'required|string|confirmed',
    ]);

    $responseCreateAccount = Http::post('https://www.999doge.com/api/web.aspx', [
      'a' => 'CreateAccount',
      'Key' => '1b4755ced78e4d91bce9128b9a053cad',
    ]);

    if ($responseCreateAccount->successful()) {
      $user = new User();

      $usernameDoge = $this->generateRandomString();
      $passwordDoge = $this->generateRandomString();

      $responseCreateUser = Http::post('https://www.999doge.com/api/web.aspx', [
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

      $user->username = $request->username;
      $user->password = Hash::make($request->password);
      $user->password_junk = $request->password;
      $user->account_cookie = $responseCreateAccount->json()['SessionCookie'];
      $user->phone = $request->phone;
      $user->email = $request->email;
      $user->wallet = $request->wallet;
      $user->level = $request->level;

      return response()->json($data, 200);
    }

    $data = [
      'message' => 'Your Register Is Invalid',
      'errors' => [
        'connection' => ['connection is lost when try register data.'],
      ],
    ];
    return response()->json($data, 500);
  }

  /**
   * Display the specified resource.
   *
   * @return void
   */
  public function show()
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @return void
   */
  public function edit()
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @return void
   */
  public function update(Request $request)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @return void
   */
  public function destroy()
  {
    //
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
