<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfpSiteCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afp_site_category', function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('name');
            $table->integer('status')->default(1)->comment('-1: xoa, 0: dong, 1: mo');
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
        Schema::dropIfExists('g_afp_site_category');
    }
}
