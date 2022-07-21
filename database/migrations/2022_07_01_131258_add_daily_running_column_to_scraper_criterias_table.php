<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyRunningColumnToScraperCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraper_criterias', function (Blueprint $table) {
            $table->tinyInteger('daily_running')->default(0)->comment('0 = Not Running, 1 = Running, 2 = Finished Running');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scraper_criterias', function (Blueprint $table) {
            $table->dropColumn('daily_running');
        });
    }
}
