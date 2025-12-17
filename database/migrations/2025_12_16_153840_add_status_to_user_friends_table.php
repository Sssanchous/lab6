<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Проверяем SQLite (у него особый синтаксис)
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Для SQLite проверяем существование столбца
            $hasColumn = collect(DB::select('PRAGMA table_info(user_friends)'))
                ->contains('name', 'status');
            
            if (!$hasColumn) {
                DB::statement('ALTER TABLE user_friends ADD COLUMN status VARCHAR DEFAULT "pending" NOT NULL');
            }
        } else {
            // Для других БД (MySQL, PostgreSQL)
            if (!Schema::hasColumn('user_friends', 'status')) {
                Schema::table('user_friends', function ($table) {
                    $table->string('status')->default('pending');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('user_friends', function ($table) {
            $table->dropColumn('status');
        });
    }
};