<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateScraperJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraper_jobs', function (Blueprint $table) {
            $table->tinyInteger('failed')->default('0')->comment('0==Not_Failed, 1==Failed')->nullable();
            $table->text('exception')->nullable();
            $table->integer('last_index')->nullable();
            $table->dropColumn('keyword');
            $table->dropColumn('location');
            $table->foreignId('scraper_criteria_id')->nullable()->constrained('scraper_criterias');
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
            $table->dropColumn('failed');
            $table->dropColumn('exception');
            $table->dropColumn('last_index');
            $table->string('keyword')->nullable();
            $table->string('location')->nullable();
            $table->dropForeign('scraper_criteria_id');
        });
    }
}
