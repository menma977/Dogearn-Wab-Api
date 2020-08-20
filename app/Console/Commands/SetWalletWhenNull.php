<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SetWalletWhenNull extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'minute:SetWalletWhenNull';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Add Wallet When Null In User';

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
   */
  public function handle()
  {
    try {
      $user = User::whereNull('wallet')->first();
      if ($user) {

        $responseBeginSession = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'Login',
          'Key' => '1b4755ced78e4d91bce9128b9a053cad',
          'username' => $user->username_doge,
          'password' => $user->password_doge,
          'Totp' => ''
        ]);
        if ($responseBeginSession->successful()) {
          $responseGetWallet = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
            'a' => 'GetDepositAddress',
            's' => $responseBeginSession->json()['SessionCookie'],
            'Currency' => "doge"
          ]);

          if ($responseGetWallet->successful()) {
            $user->wallet = $responseGetWallet->json()['Address'];
            $user->save();
            Log::info('update wallet for email :' . $user->email);
          }
        }
      }
    } catch (Exception $e) {
      Log::info($e->getMessage());
    }
  }
}
