<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('section_user', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('co_author')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('section_user', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->string('co_author')->nullable()->change();
        });
    }
};
