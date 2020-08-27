<?php

namespace App\Console\Commands;

use App\Model\WithdrawQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteWithdrawQueuesIfDone extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'hourly:deleteWithdrawQueuesIfDone';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Delete Withdraw Queues When Status is 1(Done)';

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
    WithdrawQueue::where('status', 1)->delete();
    Log::info('Delete Queue');
  }
}
