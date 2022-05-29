<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnsForScraperJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraper_jobs', function (Blueprint $table) {
            $table->dropColumn('failed');
            $table->renameColumn('exception', 'message');
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
            $table->tinyInteger('failed')->default('0')->comment('0==Not_Failed, 1==Failed')->nullable();
            $table->renameColumn('message', 'exception');
        });
    }
}
