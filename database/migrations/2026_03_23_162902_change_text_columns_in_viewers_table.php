<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viewers', function (Blueprint $table) {
            $table->text('avatar')->nullable()->change();
            $table->text('remember_token')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('viewers', function (Blueprint $table) {
            $table->string('avatar')->nullable()->change();
            $table->string('remember_token', 100)->nullable()->change();
        });
    }
};
