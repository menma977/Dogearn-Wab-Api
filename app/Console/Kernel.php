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
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:reRegisterUserToDoge')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:SetWalletWhenNull')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:DeleteUserIfNotActive')->everyMinute()->withoutOverlapping();
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
