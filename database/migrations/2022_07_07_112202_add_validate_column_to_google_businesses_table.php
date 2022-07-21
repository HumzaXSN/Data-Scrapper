<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidateColumnToGoogleBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_businesses', function (Blueprint $table) {
            $table->tinyInteger('validated')->default(0)->comment('0 = Not Validated, 1 = Validated');
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
            $table->dropColumn('validated');
        });
    }
}
