<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->string('company')->nullable()->change();
            $table->foreignId('lead_status_id')->default('1')->change();
            $table->foreignId('industry_id')->default('1')->change();
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
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('title');
            $table->dropColumn('company');
            $table->dropForeign(['lead_status_id']);
            $table->dropForeign(['industry_id']);
        });
    }
}
