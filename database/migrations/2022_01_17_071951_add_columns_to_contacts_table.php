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
            $table->foreignId('lead_status_id')->nullable()->constrained('lead_statuses');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('linkedIn_profile')->nullable();
            $table->integer('reached_count')->default('0')->nullable();
            $table->string('reached_platform')->nullable();
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
            $table->dropForeign(['lead_status_id']);
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('linkedIn_profile');
            $table->dropColumn('reached_count');
            $table->dropColumn('reached_platform');
        });
    }
}
