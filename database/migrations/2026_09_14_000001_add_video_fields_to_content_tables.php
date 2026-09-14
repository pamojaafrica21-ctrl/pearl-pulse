<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['destinations', 'journeys', 'experiences', 'stays'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (! Schema::hasColumn($table, 'video_path')) {
                    $blueprint->string('video_path')->nullable()->after('cover_path');
                }
                if (! Schema::hasColumn($table, 'video_url')) {
                    $blueprint->string('video_url')->nullable()->after('video_path');
                }
            });
        }

        Schema::table('pulse_items', function (Blueprint $table) {
            if (! Schema::hasColumn('pulse_items', 'video_path')) {
                $table->string('video_path')->nullable()->after('cover_path');
            }
        });
    }

    public function down(): void
    {
        foreach (['destinations', 'journeys', 'experiences', 'stays'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (Schema::hasColumn($table, 'video_url')) {
                    $blueprint->dropColumn('video_url');
                }
                if (Schema::hasColumn($table, 'video_path')) {
                    $blueprint->dropColumn('video_path');
                }
            });
        }

        Schema::table('pulse_items', function (Blueprint $table) {
            if (Schema::hasColumn('pulse_items', 'video_path')) {
                $table->dropColumn('video_path');
            }
        });
    }
};
