<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->string('region')->nullable();
            $table->string('industry')->nullable();
            $table->string('title')->nullable();
            $table->string('created_by')->nullable();
            $table->text('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->dropColumn('region');
            $table->dropColumn('industry');
            $table->dropColumn('title');
            $table->dropColumn('created_by');
            $table->string('name')->nullable()->change();
        });
    }
}
