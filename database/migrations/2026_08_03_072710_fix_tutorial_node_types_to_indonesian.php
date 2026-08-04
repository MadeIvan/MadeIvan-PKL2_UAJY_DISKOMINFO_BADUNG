<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tutorial_nodes')
            ->where('node_type', 'category')
            ->update([
                'node_type' => 'kategori',
            ]);

        DB::table('tutorial_nodes')
            ->where('node_type', 'section')
            ->update([
                'node_type' => 'bagian',
            ]);

        DB::table('tutorial_nodes')
            ->whereIn('node_type', [
                'tutorial',
                'step',
            ])
            ->update([
                'node_type' => 'materi',
            ]);

        DB::statement(
            "ALTER TABLE tutorial_nodes
            MODIFY node_type VARCHAR(30)
            NOT NULL DEFAULT 'materi'"
        );
    }

    public function down(): void
    {
        DB::table('tutorial_nodes')
            ->where('node_type', 'kategori')
            ->update([
                'node_type' => 'category',
            ]);

        DB::table('tutorial_nodes')
            ->where('node_type', 'bagian')
            ->update([
                'node_type' => 'section',
            ]);

        DB::table('tutorial_nodes')
            ->where('node_type', 'materi')
            ->update([
                'node_type' => 'tutorial',
            ]);

        DB::statement(
            "ALTER TABLE tutorial_nodes
            MODIFY node_type VARCHAR(30)
            NOT NULL DEFAULT 'tutorial'"
        );
    }
};