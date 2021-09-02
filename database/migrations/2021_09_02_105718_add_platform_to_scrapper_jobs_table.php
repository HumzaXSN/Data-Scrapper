<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlatformToScrapperJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrapper_jobs', function (Blueprint $table) {
            $table->string('platform')->after('url');
            $table->string('url')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrapper_jobs', function (Blueprint $table) {
            $table->dropColumn('platform');
        });
    }
}
