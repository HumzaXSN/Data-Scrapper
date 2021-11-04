<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScraperJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraper_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('url')->nullable();
            $table->string('platform')->nullable();
            $table->tinyInteger('status')->default('0')->comment('0==processing, 1==complete');
            $table->timestamps();
            $table->dateTime('end_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scraper_jobs');
    }
}
