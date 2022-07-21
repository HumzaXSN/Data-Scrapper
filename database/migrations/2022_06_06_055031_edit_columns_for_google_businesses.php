<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnsForGoogleBusinesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_businesses', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('linkedin');
            $table->dropColumn('is_website_checked');
            $table->dropColumn('is_facebook_checked');
            $table->dropColumn('is_twitter_checked');
            $table->dropColumn('is_linkedin_checked');
            $table->dropColumn('source');
            $table->longText('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_businesses', function (Blueprint $table) {
            $table->string('email')->nullable()->unique();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('is_website_checked')->default(false);
            $table->boolean('is_facebook_checked')->default(false);
            $table->boolean('is_twitter_checked')->default(false);
            $table->boolean('is_linkedin_checked')->default(false);
            $table->tinyInteger('source')->default('0')->comment('0==external, 1==internal');
            $table->dropColumn('url');
        });
    }
}
