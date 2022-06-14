<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidateColumnToDecisionMakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('decision_makers', function (Blueprint $table) {
            $table->tinyInteger('validate')->default(0)->comment('0==Not Validated, 1==Validated, 2==Data Inserted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('decision_makers', function (Blueprint $table) {
            $table->dropColumn('validate');
        });
    }
}
