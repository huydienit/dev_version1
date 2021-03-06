<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdtechCoreAclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adtech_core_acl', function (Blueprint $table) {
            $table->increments('acl_id');
            $table->integer('object_id', false, true);
            $table->enum('object_type', ['role', 'user', 'group']);
            $table->integer('domain_id', false, true)->default(1);
            $table->integer('allow')->nullable();
            $table->string('route_name')->nullable();
            $table->integer('route_name_crc', false, true)->nullable();
            $table->integer('created_user_id', false, true);
            $table->string('vendor', 255)->nullable();
            $table->string('package', 255)->nullable();
            $table->text('params')->nullable();
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
        Schema::dropIfExists('adtech_core_acl');
    }
}
