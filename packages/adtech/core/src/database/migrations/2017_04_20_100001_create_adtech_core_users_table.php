<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adtech_core_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact_name', 255);
            $table->string('salt', 3)->default('sal');
            $table->tinyInteger('status', false, true)->default(1);
            $table->tinyInteger('activated', false, true)->default(0);
            $table->tinyInteger('permission_locked', false, true)->default(0);
            $table->tinyInteger('visible', false, true)->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adtech_core_users');
    }
}
