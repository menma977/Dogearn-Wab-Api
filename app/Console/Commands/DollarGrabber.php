<?php

namespace App\Console\Commands;

use App\Model\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DollarGrabber extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'hourly:dollarGrabber';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Grab Dollar';

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
    $response = Http::get('https://api.exmo.com/v1.1/ticker');
    $data = Setting::find(1);
    $data->dollar = $response->json()['DOGE_USD']['last_trade'];
    $data->save();
  }
}
