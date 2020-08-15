<?php

use App\Model\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = new Level();
      $data->percent = 50.0;
      $data->save();

      $data = new Level();
      $data->percent = 15.0;
      $data->save();

      $data = new Level();
      $data->percent = 5.0;
      $data->save();

      $data = new Level();
      $data->percent = 5.0;
      $data->save();

      $data = new Level();
      $data->percent = 5.0;
      $data->save();

      $data = new Level();
      $data->percent = 5.0;
      $data->save();

      $data = new Level();
      $data->percent = 4.0;
      $data->save();
    }
}
