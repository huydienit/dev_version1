<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCorePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('adtech_core_packages', function (Blueprint $table) {
            $table->increments('package_id')->index();
            $table->string('space');
            $table->string('package');
            $table->string('module');
            $table->integer('create_by')->default(0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('adtech_core_packages');
    }
}
