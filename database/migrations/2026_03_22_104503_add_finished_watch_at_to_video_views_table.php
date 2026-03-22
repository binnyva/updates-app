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
        Schema::table('video_views', function (Blueprint $table) {
            $table->timestamp('finished_watch_at')->nullable()->after('video_view_time');
        });
    }

    public function down(): void
    {
        Schema::table('video_views', function (Blueprint $table) {
            $table->dropColumn('finished_watch_at');
        });
    }
};
