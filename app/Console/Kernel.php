<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    //
  ];

  /**
   * Define the application's command schedule.
   *
   * @param Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('daily:deleteWithdrawQueuesIfDone')->daily()->withoutOverlapping();

//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:reRegisterUserToDoge')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:SetWalletWhenNull')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:SetWalletWhenNull')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SetWalletWhenNull')->everyThreeMinutes()->withoutOverlapping();
    $schedule->command('minute:SetWalletWhenNull')->everyFourMinutes()->withoutOverlapping();
    $schedule->command('minute:SetWalletWhenNull')->everyFiveMinutes()->withoutOverlapping();
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
