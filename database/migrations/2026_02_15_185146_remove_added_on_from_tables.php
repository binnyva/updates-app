<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['users', 'viewers', 'videos', 'comments', 'video_views'];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'added_on')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('added_on');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['users', 'viewers', 'videos', 'comments', 'video_views'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamp('added_on')->useCurrent();
            });
        }
    }
};
