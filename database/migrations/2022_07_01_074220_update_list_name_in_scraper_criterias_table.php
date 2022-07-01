<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateListNameInScraperCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraper_criterias', function (Blueprint $table) {
            $table->renameColumn('list_id', 'lists_id');
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
            $table->renameColumn('lists_id', 'list_id');
        });
    }
}
