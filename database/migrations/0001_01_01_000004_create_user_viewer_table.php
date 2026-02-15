<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_viewer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('viewer_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['full', 'limited'])->default('limited');
            $table->timestamps();

            $table->unique(['user_id', 'viewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_viewer');
    }
};
