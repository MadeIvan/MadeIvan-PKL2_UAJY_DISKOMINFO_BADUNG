<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->string('name', 200);
            $table->string('slug', 200)->unique();

            $table->text('description')->nullable();
            $table->string('category_name', 150)->nullable();

            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'archived',
            ])->default('active');

            $table->boolean('is_public')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};