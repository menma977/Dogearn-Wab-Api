<?php

namespace App\Console\Commands;

use App\Model\Binary;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteUserIfNotActive extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'minute:DeleteUserIfNotActive';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Delete user when not active';

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
      $user = User::where('status', 0)->get();
      foreach ($user as $item) {
        $userDate = Carbon::parse($item->created_at);
        $now = Carbon::now();
        if ($userDate->diffInDays($now)) {
          $user = User::find($item->id);
          $user->forceDelete();
          $binary = Binary::where('down_line', $item->id)->first();
          $binary->forceDelete();
        }
      }
    } catch (\Exception $e) {
      Log::error($e->getMessage() . ' ' . $e->getLine());
    }
  }
}
