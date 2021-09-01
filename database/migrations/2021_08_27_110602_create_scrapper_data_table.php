<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapperDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrapper_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('scrapper_job_id');
            $table->string('company');
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('address');
            $table->string('website')->nullable();
            $table->tinyInteger('source')->default('0')->comment('0==external, 1==internal');
            $table->tinyInteger('status')->default('1')->comment('0==inActive, 1==active');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['company', 'website']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrapper_data');
    }
}
