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
        Schema::create('insect_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insect_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('delete_url')->nullable()->after('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insect_photos');
    }
};
