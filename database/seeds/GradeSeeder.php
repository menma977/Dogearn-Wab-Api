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
    $data->price = 500000000000;
    $data->pin = 1;
    $data->save();

    $data = new Grade();
    $data->price = 1000000000000;
    $data->pin = 1;
    $data->save();

    $data = new Grade();
    $data->price = 2000000000000;
    $data->pin = 2;
    $data->save();

    $data = new Grade();
    $data->price = 4000000000000;
    $data->pin = 2;
    $data->save();

    $data = new Grade();
    $data->price = 8000000000000;
    $data->pin = 3;
    $data->save();

    $data = new Grade();
    $data->price = 16000000000000;
    $data->pin = 4;
    $data->save();

    $data = new Grade();
    $data->price = 32000000000000;
    $data->pin = 5;
    $data->save();

    $data = new Grade();
    $data->price = 64000000000000;
    $data->pin = 6;
    $data->save();

    $data = new Grade();
    $data->price = 128000000000000;
    $data->pin = 7;
    $data->save();

    $data = new Grade();
    $data->price = 256000000000000;
    $data->pin = 8;
    $data->save();
  }
}
