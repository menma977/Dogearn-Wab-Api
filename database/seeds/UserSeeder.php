<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new User();
    $data->role = 1;
    $data->phone = "6200000000000";
    $data->email = "admin@dogearn.com";
    $data->password = Hash::make('arif999999');
    $data->password_junk = "arif999999";
    $data->transaction_password = Hash::make('1234');
    $data->username_doge = "arn2";
    $data->password_doge = "arif999999";
    $data->wallet = "DHRDzBmt5NJtq1nkGz7rdEWVETUDWmQkKm";
    $data->status = 0;
    $data->level = 0;
    $data->save();
  }
}
