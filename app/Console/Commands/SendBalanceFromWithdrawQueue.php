<?php

namespace App\Console\Commands;

use App\Model\AdminWallet;
use App\Model\DogeHistory;
use App\Model\GradeHistory;
use App\Model\Setting;
use App\Model\WithdrawQueue;
use App\User;
use Exception;
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
    try {
      $data = WithdrawQueue::where('status', 0)->orderBy('id', 'asc')->get()->first();
      if ($data) {
        $user = User::find($data->user_id);
        if ($data->type == 2) {
          $sendToUser = AdminWallet::find($data->send_to);
        } else {
          $sendToUser = User::find($data->send_to);
        }

        $responseGetSession = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'Login',
          'Key' => '1b4755ced78e4d91bce9128b9a053cad',
          'username' => $user->username_doge,
          'password' => $user->password_doge,
          'Totp' => ''
        ]);

        Log::info($responseGetSession->body());

        if ($responseGetSession->successful() && str_contains($responseGetSession->body(), 'InvalidApiKey') == false && str_contains($responseGetSession->body(), 'LoginInvalid') == false) {
          $dataGetSession = $responseGetSession->json();
          if ($data->send_to == 0) {
            $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
              'a' => 'Withdraw',
              's' => $dataGetSession["SessionCookie"],
              'Amount' => $data->send_value,
              'Address' => Setting::find(1)->wallet_it,
              'Totp ' => '',
              'Currency' => 'doge',
            ]);
          } else {
            $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
              'a' => 'Withdraw',
              's' => $dataGetSession["SessionCookie"],
              'Amount' => $data->send_value,
              'Address' => $sendToUser->wallet,
              'Totp ' => '',
              'Currency' => 'doge',
            ]);
          }

          if ($response->successful() && str_contains($response->body(), 'Pending') == true) {
            $data->status = 1;
            $data->save();

            if ($data->send_to == 0) {
              $dogeHistory = new DogeHistory();
              $dogeHistory->user_id = $user->id;
              $dogeHistory->send_to = 0;
              $dogeHistory->total = $data->send_value;
              $dogeHistory->description = "You send " . $dogeHistory->total . " Doge to Network Fee";
              $dogeHistory->save();

              $grade = new GradeHistory();
              $grade->user_id = 0;
              $grade->target = $user->id;
              $grade->debit = 0;
              $grade->credit = $data->send_value;
              $grade->upgrade_level = 0;
              $grade->save();
            } else if ($data->type == 2) {
              $dogeHistory = new DogeHistory();
              $dogeHistory->user_id = $user->id;
              $dogeHistory->send_to = $sendToUser->id;
              $dogeHistory->total = $data->send_value;
              $dogeHistory->description = "You send " . $dogeHistory->total . " Doge to " . $sendToUser->wallet;
              $dogeHistory->type = 2;
              $dogeHistory->save();

              $grade = new GradeHistory();
              $grade->user_id = $sendToUser->id;
              $grade->target = $user->id;
              $grade->debit = 0;
              $grade->credit = $data->send_value;
              $grade->upgrade_level = 0;
              $grade->type = 2;
              $grade->save();
            } else {
              $dogeHistory = new DogeHistory();
              $dogeHistory->user_id = $user->id;
              $dogeHistory->send_to = $sendToUser->id;
              $dogeHistory->total = $data->send_value;
              $dogeHistory->description = "You send " . $dogeHistory->total . " Doge to " . $sendToUser->wallet;
              $dogeHistory->save();

              $grade = new GradeHistory();
              $grade->user_id = $sendToUser->id;
              $grade->target = $user->id;
              $grade->debit = 0;
              $grade->credit = $data->send_value;
              $grade->upgrade_level = $sendToUser->level;
              $grade->save();
            }

            Log::info('delivery process success');
          }
        }
      }
    } catch (Exception $e) {
      Log::warning($e->getMessage() . " LINE : " . $e->getLine());
    }
  }
}
