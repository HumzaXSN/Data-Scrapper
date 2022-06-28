<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDecisionMakersEmailStatusToScraperJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraper_jobs', function (Blueprint $table) {
            $table->tinyInteger('decision_makers_email_status')->default('0')->comment('0==pending, 1==complete, 2==failed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scraper_jobs', function (Blueprint $table) {
            $table->dropColumn('decision_makers_email_status');
        });
    }
}
