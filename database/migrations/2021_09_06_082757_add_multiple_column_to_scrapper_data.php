<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnToScrapperData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrapper_data', function (Blueprint $table) {
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();

            $table->boolean('is_website_checked')->default(false);
            $table->boolean('is_facebook_checked')->default(false);
            $table->boolean('is_twitter_checked')->default(false);
            $table->boolean('is_linkedin_checked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrapper_data', function (Blueprint $table) {
            $table->dropColumn(['facebook',  'twitter', 'linkedin', 'is_website_checked', 'is_facebook_checked', 'is_twitter_checked', 'is_linkedin_checked']);
        });
    }
}
