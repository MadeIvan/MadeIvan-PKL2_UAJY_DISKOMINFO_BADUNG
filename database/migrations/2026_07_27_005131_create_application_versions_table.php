<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'application_versions',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('application_id')
                    ->constrained('applications')
                    ->cascadeOnDelete();

                $table->string('version_number', 50);

                $table->date('release_date')
                    ->nullable();

                $table->text('release_notes')
                    ->nullable();

                $table->enum('status', [
                    'draft',
                    'beta',
                    'stable',
                    'deprecated',
                ])->default('draft');

                $table->boolean('is_current')
                    ->default(false);

                $table->timestamps();
                $table->softDeletes();

                $table->unique([
                    'application_id',
                    'version_number',
                ]);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('application_versions');
    }
};