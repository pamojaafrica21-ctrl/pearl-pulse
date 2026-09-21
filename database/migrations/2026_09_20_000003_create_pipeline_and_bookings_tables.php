<?php

use App\Models\Enquiry;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('channel')->nullable()->after('source');
            $table->foreignId('assigned_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });

        DB::table('enquiries')->where('status', 'read')->update(['status' => 'in_progress']);
        DB::table('enquiries')->where('status', 'responded')->update(['status' => 'proposal_sent']);
        DB::table('enquiries')->where('source', 'journey_finder')->update(['channel' => Enquiry::CHANNEL_FINDER]);
        DB::table('enquiries')->whereNull('channel')->update(['channel' => Enquiry::CHANNEL_WEBSITE]);

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('enquiry_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('journey_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('channel')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('travellers')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('travel_dates')->nullable();
            $table->string('investment')->nullable();
            $table->string('status')->default('held');
            $table->text('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('pipeline_notes', function (Blueprint $table) {
            $table->id();
            $table->morphs('noteable');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_notes');
        Schema::dropIfExists('bookings');

        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_user_id');
            $table->dropColumn('channel');
        });

        DB::table('enquiries')->where('status', 'in_progress')->update(['status' => 'read']);
        DB::table('enquiries')->where('status', 'proposal_sent')->update(['status' => 'responded']);
        DB::table('enquiries')->whereIn('status', ['won', 'lost'])->update(['status' => 'responded']);
    }
};
