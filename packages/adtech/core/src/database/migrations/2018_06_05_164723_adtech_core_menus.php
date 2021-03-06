<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdtechCoreMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('adtech_core_menus', function (Blueprint $table) {
            $table->increments('menu_id')->index();
            $table->string('name');
            $table->string('group')->default('');
            $table->string('route_name')->default('');
            $table->string('icon')->default('question');
            $table->integer('domain_id', false, true)->index();
            $table->integer('parent', false, true)->default(0);
            $table->tinyInteger('sort', false, true)->default(99);
            $table->tinyInteger('visible', false, true)->default(1);
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
        //
        Schema::drop('adtech_core_menus');
    }
}
