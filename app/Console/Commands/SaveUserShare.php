<?php

namespace App\Console\Commands;

use App\ShareUser;
use App\User;
use Illuminate\Console\Command;

class SaveUserShare extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'minute:SaveUserShare';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Save User In Table Share';

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
    $user = User::cursor()->random(1)->first();
    $shareUserCount = ShareUser::where('user_id', $user->id)->count();
    if ($shareUserCount) {
      $totalShare = $user->level - $shareUserCount;
      if ($totalShare > 0) {
        for ($i = 0; $i < $totalShare; $i++) {
          $shareUser = new ShareUser();
          $shareUser->user_id = $user->id;
          $shareUser->save();
        }
      }
    } else {
      for ($i = 0; $i < $user->level; $i++) {
        $shareUser = new ShareUser();
        $shareUser->user_id = $user->id;
        $shareUser->save();
      }
    }
  }
}
