<?php

namespace App\Console\Commands;

use App\Model\DogeHistory;
use App\Model\WithdrawQueue;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendBalanceFromWithdrawQueue extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'minute:sendBalanceFromWithdrawQueue';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send Balance to user when have queue from WithdrawQueue';

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
    $data = WithdrawQueue::where('status', 0)->first();
    if ($data) {
      $data->user = User::find($data->user_id);
      $data->sendToUser = User::find($data->send_to);

      $responseGetSession = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
        'a' => 'Login',
        'Key' => '1b4755ced78e4d91bce9128b9a053cad',
        'username' => $data->user->username_doge,
        'password' => $data->user->password_doge,
        'Totp' => ''
      ]);

      if ($responseGetSession->successful() && str_contains($responseGetSession->body(), 'InvalidApiKey') === false && str_contains($responseGetSession->body(), 'LoginInvalid') === false) {
        $dataGetSession = $responseGetSession->json();
        $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'Withdraw',
          's' => $dataGetSession["SessionCookie"],
          'Amount' => $data->send_value,
          'Address' => $data->sendToUser->wallet,
          'Totp ' => '',
          'Currency' => 'doge',
        ]);

        if ($response->successful() && str_contains($response->body(), 'TooSmall') === false && str_contains($response->body(), 'InsufficientFunds') === false) {
          $data->status = 1;
          $data->save();

          $dogeHistory = new DogeHistory();
          $dogeHistory->user_id = $data->user->id;
          $dogeHistory->send_to = $data->sendToUser->id;
          $dogeHistory->total = $data->send_value;
          $dogeHistory->description = "Your send " . $dogeHistory->total . " Doge to" . $data->sendToUser->email;
          $dogeHistory->save();

          Log::info('delivery process success');
        } else {
          Log::info('failed to delivery balance');
          Log::info($response->body());
        }
      }
    } else {
      Log::info('no delivery process');
    }
  }
}
