<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->integer('role');
      $table->string('phone')->unique();
      $table->string('email')->unique();
      $table->string('username')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->string('password_junk');
      $table->string('username_doge');
      $table->string('password_doge');
      $table->text('Account_cookie')->nullable();
      $table->text('wallet')->nullable();
      $table->integer('level')->default(1);
      $table->rememberToken();
      $table->timestamps();
      $table->integer('suspend')->default(0);
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
    Schema::dropIfExists('users');
  }
}
