<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('section_user', function (Blueprint $table) {
            $table->string('degree_type')->nullable()->after('co_author');
            $table->unsignedTinyInteger('course')->nullable()->after('degree_type');
            $table->string('group_number')->nullable()->after('course');
        });
    }

    public function down(): void
    {
        Schema::table('section_user', function (Blueprint $table) {
            $table->dropColumn(['degree_type', 'course', 'group_number']);
        });
    }
};
