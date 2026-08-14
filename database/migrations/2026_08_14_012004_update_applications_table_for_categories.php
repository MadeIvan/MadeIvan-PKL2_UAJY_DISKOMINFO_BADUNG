<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('description')->constrained('categories')->nullOnDelete();
            $table->dropColumn('category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('category_name', 150)->nullable()->after('description');
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
