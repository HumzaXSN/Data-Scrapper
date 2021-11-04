<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScraperDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraper_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('scraper_job_id');
            $table->string('company');
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('address');
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('is_website_checked')->default(false);
            $table->boolean('is_facebook_checked')->default(false);
            $table->boolean('is_twitter_checked')->default(false);
            $table->boolean('is_linkedin_checked')->default(false);
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
        Schema::dropIfExists('scraper_data');
    }
}
