<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
            $table->string('duration')->nullable()->after('teaser');
            $table->string('best_time')->nullable()->after('duration');
            $table->string('activities', 500)->nullable()->after('best_time');
            $table->string('price_from')->nullable()->after('activities');
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'duration', 'best_time', 'activities', 'price_from']);
        });
    }
};
