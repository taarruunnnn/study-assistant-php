<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompletedModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('rating');
            $table->string('grade')->nullable()->default(null);
            $table->integer('completed_sessions');
            $table->integer('failed_sessions');
            $table->integer('user_id');
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
        Schema::dropIfExists('completed_modules');
    }
}
