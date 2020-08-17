<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeHistoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('grade_histories', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->bigInteger('debit');
      $table->bigInteger('credit');
      $table->integer('upgrade_level');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('grade_histories');
  }
}
