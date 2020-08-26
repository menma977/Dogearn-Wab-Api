<?php

namespace App\Console\Commands;

use App\Model\DogeHistory;
use App\Model\GradeHistory;
use App\Model\Setting;
use App\Model\WithdrawQueue;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendDataToOtherDomain3 extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'minute:sendDataToOtherDomain3';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send Withdraw To domain 3';

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
      $data = WithdrawQueue::where('status', 0)->first();
      if ($data) {
        $user = User::find($data->user_id);
        $sendToUser = User::find($data->send_to);
        if ($data->send_to == 0) {
          $response = Http::asForm()->post('http://api1.budisetiyono.com/nembak.php', [
            'userdoge' => $user->username_doge,
            'passdoge' => $user->password_doge,
            'wallet' => Setting::find(1)->wallet_it,
            'nominal' => $data->send_value,
          ]);
        } else {
          $response = Http::asForm()->post('http://api1.budisetiyono.com/nembak.php', [
            'userdoge' => $user->username_doge,
            'passdoge' => $user->password_doge,
            'wallet' => $sendToUser->wallet,
            'nominal' => $data->send_value,
          ]);
        }
        if ($response->successful() && str_contains($response->body(), '1')) {
          $data->status = 1;
          $data->save();

          if ($data->send_to == 0) {
            $dogeHistory = new DogeHistory();
            $dogeHistory->user_id = $user->id;
            $dogeHistory->send_to = 0;
            $dogeHistory->total = $data->send_value;
            $dogeHistory->description = "Your send " . $dogeHistory->total . " Doge to Network Fee";
            $dogeHistory->save();

            $grade = new GradeHistory();
            $grade->user_id = 0;
            $grade->debit = 0;
            $grade->credit = $data->send_value;
            $grade->upgrade_level = 0;
            $grade->save();
          } else {
            $dogeHistory = new DogeHistory();
            $dogeHistory->user_id = $user->id;
            $dogeHistory->send_to = $sendToUser->id;
            $dogeHistory->total = $data->send_value;
            if ($sendToUser->id == 1) {
              $dogeHistory->description = "Your send " . $dogeHistory->total . " Doge to Doge to be shared";
            } else {
              $dogeHistory->description = "Your send " . $dogeHistory->total . " Doge to " . $sendToUser->email;
            }
            $dogeHistory->save();

            $grade = new GradeHistory();
            $grade->user_id = $sendToUser->id;
            $grade->target = $user->id;
            $grade->debit = 0;
            $grade->credit = $data->send_value;
            $grade->upgrade_level = $sendToUser->level;
            $grade->save();
          }

          Log::info('delivery process success Domain 3');
        }
      }
    } catch (Exception $e) {
      Log::warning($e->getMessage() . " Domain 3 Send LINE : " . $e->getLine());
    }
  }
}
