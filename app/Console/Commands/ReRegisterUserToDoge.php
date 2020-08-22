<?php

namespace App\Console\Commands;

use App\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReRegisterUserToDoge extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'minute:reRegisterUserToDoge';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Re Register user when failed to doge';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return void
   * @throws Exception
   */
  public function handle()
  {
    try {
      $user = User::where('status', 1)->first();
      if ($user) {
        $responseBeginSession = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'BeginSession',
          'Key' => '1b4755ced78e4d91bce9128b9a053cad',
          'AccountCookie' => $user->account_cookie,
        ]);
        if ($responseBeginSession->successful()) {
          $usernameDoge = $this->generateRandomString();
          $passwordDoge = $this->generateRandomString();
          $responseCreateUser = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
            'a' => 'CreateUser',
            's' => $responseBeginSession->json()['SessionCookie'],
            'Username' => $usernameDoge,
            'Password' => $passwordDoge,
          ]);

          if ($responseCreateUser->successful() && $responseCreateUser->json()['success'] === 1) {
            $user->username_doge = $usernameDoge;
            $user->password_doge = $passwordDoge;
            $user->status = 0;
            $user->save();

            $data = [
              'subject' => 'Your registration process has been completed',
              'message' => 'Hallo ' . $user->email . ' has been registered correctly and can login to the application'
            ];
            Mail::send('mail.reRegistration', $data, function ($message) use ($user) {
              $message->to($user->email, 'Registration')->subject('Your registration process has been completed');
              $message->from('admin@dogearn.com', 'DOGEARN');
            });
          }
        }
      }
    } catch (Exception $e) {
      Log::info($e->getMessage(). " - ". $e->getLine());
    }
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
