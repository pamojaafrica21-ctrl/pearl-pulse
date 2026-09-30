<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('topic')->nullable()->after('group');
            $table->index('topic');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->string('cover_path')->nullable()->after('quote');
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex(['topic']);
            $table->dropColumn('topic');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('cover_path');
        });
    }
};
