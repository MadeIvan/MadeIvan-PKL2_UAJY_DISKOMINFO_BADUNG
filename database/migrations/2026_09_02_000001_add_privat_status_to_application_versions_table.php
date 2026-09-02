<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite does not support modifying enum columns; the original
        // migration already includes 'privat' for fresh SQLite installs.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table(
            'application_versions',
            function (Blueprint $table): void {
                $table->enum('status', [
                    'draft',
                    'beta',
                    'stable',
                    'deprecated',
                    'privat',
                ])->default('draft')->change();
            }
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table(
            'application_versions',
            function (Blueprint $table): void {
                $table->enum('status', [
                    'draft',
                    'beta',
                    'stable',
                    'deprecated',
                ])->default('draft')->change();
            }
        );
    }
};
