<?php

namespace App\Console\Commands;

use App\Model\WithdrawQueue;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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

      $responseGetSession = Http::post('https://www.999doge.com/api/web.aspx', [
        'a' => 'BeginSession',
        'Key' => '1b4755ced78e4d91bce9128b9a053cad',
        'AccountCookie' => $data->user->account_cookie,
      ]);

      if ($responseGetSession->successful()) {
        $dataGetSession = $responseGetSession->json();
        $response = Http::post('https://www.999doge.com/api/web.aspx', [
          'a' => 'Withdraw',
          's' => $dataGetSession["SessionCookie"],
          'Amount' => $data->total,
          'Address' => $data->sendToUser->wallet,
          'Totp ' => '',
          'Currency' => 'doge',
        ]);
        if ($response->successful() && str_contains($response->body(), 'InvalidApiKey') === false && str_contains($response->body(), 'LoginRequired') === false) {
          $data->status = 1;
          $data->save();
        }
      }
    }
  }
}
