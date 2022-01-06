<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGooglebusinessNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('googlebusiness_note', function (Blueprint $table) {
            $table->id();
            $table->foreignId('googlebusiness_id')->nullable()->constrained('google_businesses');
            $table->foreignId('note_id')->nullable()->constrained('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('googlebusiness_note');
    }
}
