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
      $table->integer('role')->default(2);
      $table->string('phone')->unique();
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->string('password_junk');
      $table->string('transaction_password');
      $table->string('username_doge')->nullable();
      $table->string('password_doge')->nullable();
      $table->text('account_cookie')->nullable();
      $table->text('wallet')->nullable();
      $table->integer('level')->default(1);
      $table->integer('status')->default(1);
      $table->integer('suspend')->default(0);
      $table->rememberToken();
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
    Schema::dropIfExists('users');
  }
}
