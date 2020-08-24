<?php

use App\Model\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new Setting();
    $data->maintenance = 0;
    $data->type_withdraw = 1;
    $data->wallet_it = "DHRDzBmt5NJtq1nkGz7rdEWVETUDWmQkKm";
    $data->save();
  }
}
