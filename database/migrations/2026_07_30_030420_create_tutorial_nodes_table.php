<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutorial_nodes', function (Blueprint $table): void {
            $table->id();

            $table
                ->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('application_version_id')
                ->nullable()
                ->constrained('application_versions')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table
                ->foreignId('parent_id')
                ->nullable()
                ->constrained('tutorial_nodes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('title', 200);
            $table->string('slug', 200);

            $table->text('description')->nullable();

            $table
                ->string('node_type', 30)
                ->default('tutorial');

            $table
                ->unsignedInteger('sort_order')
                ->default(0);

            $table
                ->string('status', 30)
                ->default('draft');

            $table
                ->boolean('is_public')
                ->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'application_id',
                'parent_id',
                'sort_order',
            ]);

            $table->index([
                'application_id',
                'status',
                'is_public',
            ]);

            $table->index([
                'application_version_id',
                'status',
            ]);

            $table->index('slug');
            $table->index('node_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutorial_nodes');
    }
};