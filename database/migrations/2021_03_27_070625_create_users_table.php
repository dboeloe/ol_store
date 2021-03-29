<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
        $table->bigIncrements('id');
        $table->timestamps();
        $table->string('name');
        $table->string('email')->unique()->notNullable();
        $table->string('password');
        $table->string('api_key')->nullable();
        $table->softDeletes($column = 'deleted_at', $precision = 0);
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
