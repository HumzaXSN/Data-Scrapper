<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('industry_id')->nullable()->constrained('industries');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('linkedIn_profile')->nullable();
            $table->integer('times_reached')->default('0')->nullable();
            $table->string('reached_platform')->nullable();
            $table->string('lead_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['industry_id']);
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('linkedIn_profile');
            $table->dropColumn('times_reached');
            $table->dropColumn('reached_platform');
            $table->dropColumn('lead_status');
        });
    }
}
