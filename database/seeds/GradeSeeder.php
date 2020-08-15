<?php

use App\Model\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new Grade();
    $data->price = 5000;
    $data->pin = 1;
    $data->save();

    $data = new Grade();
    $data->price = 10000;
    $data->pin = 1;
    $data->save();

    $data = new Grade();
    $data->price = 20000;
    $data->pin = 2;
    $data->save();

    $data = new Grade();
    $data->price = 40000;
    $data->pin = 2;
    $data->save();

    $data = new Grade();
    $data->price = 80000;
    $data->pin = 3;
    $data->save();

    $data = new Grade();
    $data->price = 160000;
    $data->pin = 4;
    $data->save();

    $data = new Grade();
    $data->price = 320000;
    $data->pin = 5;
    $data->save();

    $data = new Grade();
    $data->price = 640000;
    $data->pin = 6;
    $data->save();

    $data = new Grade();
    $data->price = 1280000;
    $data->pin = 7;
    $data->save();

    $data = new Grade();
    $data->price = 2560000;
    $data->pin = 8;
    $data->save();
  }
}
