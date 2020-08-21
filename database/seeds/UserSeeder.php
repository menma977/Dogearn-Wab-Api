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
    $data->phone = "6281211610807";
    $data->email = "admin@gmail.com";
    $data->password = Hash::make('admin');
    $data->password_junk = "1234";
    $data->transaction_password = Hash::make('admin');
    $data->username_doge = "dogearn977";
    $data->password_doge = "123456789";
    $data->wallet = "D7nMkN16Jm9pyZvh5xCRJzqk1xdHojgC6c";
    $data->status = 0;
    $data->level = 0;
    $data->save();
  }
}
