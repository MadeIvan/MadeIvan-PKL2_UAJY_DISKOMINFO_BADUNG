<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutorial_content_blocks', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('tutorial_node_id')
                ->constrained('tutorial_nodes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('block_type', [
                'text',
                'image',
                'youtube',
                'pdf',
            ]);

            $table->longText('content')->nullable();

            $table->string('file_path')->nullable();
            $table->string('original_file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();

            $table->text('external_url')->nullable();

            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['tutorial_node_id', 'sort_order'],
                'tutorial_content_blocks_node_order_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutorial_content_blocks');
    }
};