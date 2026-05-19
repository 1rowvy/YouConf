<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->unsignedSmallInteger('revision_count')->default(0)->after('co_authors');
            $table->text('review_comment')->nullable()->after('revision_count');
        });
    }

    public function down(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn(['revision_count', 'review_comment']);
        });
    }
};
