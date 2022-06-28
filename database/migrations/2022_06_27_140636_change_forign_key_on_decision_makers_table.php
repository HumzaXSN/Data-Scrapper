<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForignKeyOnDecisionMakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('decision_makers', function (Blueprint $table) {
            $table->foreignId('google_business_id')->onDelete('cascade')->onUpdate('cascade')->change();
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
            $table->foreignId('google_business_id')->nullable()->constrained('google_businesses')->change();
        });
    }
}
