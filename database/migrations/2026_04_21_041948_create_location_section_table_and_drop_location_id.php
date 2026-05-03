<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_section', function (Blueprint $table) {
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->primary(['section_id', 'location_id']);
        });

        // Migrate existing data before dropping the column
        \DB::statement('
            INSERT INTO location_section (section_id, location_id)
            SELECT id, location_id FROM sections WHERE location_id IS NOT NULL
        ');

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }

    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
        });

        // Restore one location per section (take first)
        \DB::statement('
            UPDATE sections s
            JOIN location_section ls ON ls.section_id = s.id
            SET s.location_id = ls.location_id
        ');

        Schema::dropIfExists('location_section');
    }
};
