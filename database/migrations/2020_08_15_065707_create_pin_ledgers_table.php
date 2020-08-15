<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinLedgersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pin_ledgers', function (Blueprint $table) {
      $table->id();
      $table->integer('id_user');
      $table->text('description');
      $table->integer('debit');
      $table->integer('credit');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pin_ledgers');
  }
}
