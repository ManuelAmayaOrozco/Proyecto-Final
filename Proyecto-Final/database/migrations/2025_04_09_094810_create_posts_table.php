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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamp('publish_date');
            $table->integer('n_likes');
            $table->unsignedBigInteger('belongs_to');
            $table->foreign('belongs_to')->references('id')->on('users');
            $table->unsignedBigInteger('related_insect');
            $table->foreign('related_insect')->references('id')->on('insects');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo', 255)->nullable();
            $table->boolean('dailyPost')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
