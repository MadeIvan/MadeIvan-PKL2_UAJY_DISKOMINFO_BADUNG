<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutorial_content_blocks', function (Blueprint $table): void {
            $table
                ->string('title')
                ->nullable()
                ->after('block_type');
        });
    }

    public function down(): void
    {
        Schema::table('tutorial_content_blocks', function (Blueprint $table): void {
            $table->dropColumn('title');
        });
    }
};