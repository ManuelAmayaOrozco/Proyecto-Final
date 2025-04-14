<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('insects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registered_by');
            $table->foreign('registered_by')->references('id')->on('users');
            $table->string('name');
            $table->string('scientificName');
            $table->string('family');
            $table->string('diet');
            $table->text('description');
            $table->integer('n_spotted');
            $table->double('maxSize');
            $table->boolean('protectedSpecies');
            $table->string('photo', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insects');
    }
};
