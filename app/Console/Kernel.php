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
    $schedule->command('hourly:dollarGrabber')->hourly()->withoutOverlapping();

    $schedule->command('hourly:deleteWithdrawQueuesIfDone')->daily()->withoutOverlapping();

    $schedule->command('minute:reRegisterUserToDoge')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:SetWalletWhenNull')->everyMinute()->withoutOverlapping();

    $schedule->command('minute:DeleteUserIfNotActive')->daily()->withoutOverlapping();

    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:SaveUserShare')->everyTwoMinutes()->withoutOverlapping();
    $schedule->command('minute:sendBalanceFromWithdrawQueue')->everyTwoMinutes()->withoutOverlapping();

//    $schedule->command('minute:sendDataToOtherDomain1')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendDataToOtherDomain2')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendDataToOtherDomain3')->everyMinute()->withoutOverlapping();
//    $schedule->command('minute:sendDataToOtherDomain4')->everyMinute()->withoutOverlapping();
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
