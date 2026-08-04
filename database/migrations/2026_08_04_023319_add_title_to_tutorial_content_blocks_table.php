<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasColumn(
                'tutorial_content_blocks',
                'title'
            )
        ) {
            Schema::table(
                'tutorial_content_blocks',
                function (Blueprint $table): void {
                    $table
                        ->string('title', 255)
                        ->nullable()
                        ->after('block_type');
                }
            );
        }
    }

    public function down(): void
    {
        if (
            Schema::hasColumn(
                'tutorial_content_blocks',
                'title'
            )
        ) {
            Schema::table(
                'tutorial_content_blocks',
                function (Blueprint $table): void {
                    $table->dropColumn('title');
                }
            );
        }
    }
};