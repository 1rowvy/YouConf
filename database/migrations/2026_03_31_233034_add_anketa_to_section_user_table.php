<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('section_user', function (Blueprint $table) {
            $table->string('topic')->nullable()->after('section_id');
            $table->string('supervisor')->nullable()->after('topic');
            $table->string('co_author')->nullable()->after('supervisor');
            $table->string('description')->nullable()->after('co_author');
            $table->string('phone_number')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('section_user', function (Blueprint $table) {
            $table->dropColumn(['topic', 'supervisor', 'co_author', 'description', 'phone_number']);
        });
    }
};
