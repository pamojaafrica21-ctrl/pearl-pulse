<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('teaser', 500)->nullable();
            $table->longText('description')->nullable();
            $table->longText('practical')->nullable();
            $table->string('best_time')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_featured')->default(true);
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        $countryIds = [];
        foreach ([
            ['Uganda', 'uganda', 'The pearl of Africa', 1],
            ['Rwanda', 'rwanda', 'Land of a thousand hills', 2],
            ['Kenya', 'kenya', 'Savannah and migration country', 3],
            ['Tanzania', 'tanzania', 'Endless plains and spice islands', 4],
        ] as [$name, $slug, $subtitle, $sort]) {
            $countryIds[$name] = DB::table('countries')->insertGetId([
                'name' => $name,
                'slug' => $slug,
                'subtitle' => $subtitle,
                'status' => 'published',
                'sort_order' => $sort,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('destinations', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('slug')->constrained()->nullOnDelete();
            $table->longText('why')->nullable()->after('description');
            $table->longText('practical')->nullable()->after('why');
        });

        foreach (DB::table('destinations')->get() as $destination) {
            $countryId = $countryIds[$destination->country] ?? $countryIds['Uganda'];
            DB::table('destinations')->where('id', $destination->id)->update([
                'country_id' => $countryId,
            ]);
        }

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn('country');
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('teaser', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('teaser', 500)->nullable();
            $table->longText('overview')->nullable();
            $table->unsignedSmallInteger('days')->nullable();
            $table->string('duration_label')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('highlights')->nullable();
            $table->json('included')->nullable();
            $table->json('not_included')->nullable();
            $table->string('best_time')->nullable();
            $table->longText('practical')->nullable();
            $table->string('price_mode')->default('from');
            $table->string('price_from')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->boolean('is_signature')->default(false);
            $table->boolean('is_multi_country')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('cover_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('teaser', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->string('style')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('role')->nullable();
            $table->longText('bio')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('guest_country')->nullable();
            $table->text('quote');
            $table->foreignId('journey_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('guide');
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pulse_items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('type')->default('photo');
            $table->text('caption')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('approved')->default(false);
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->longText('answer')->nullable();
            $table->string('group')->default('site');
            $table->nullableMorphs('faqable');
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('content_images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->string('path');
            $table->string('alt')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('destination_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained()->cascadeOnDelete();
            $table->unique(['destination_id', 'experience_id']);
        });

        Schema::create('journey_country', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->unique(['journey_id', 'country_id']);
        });

        Schema::create('journey_destination', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->unique(['journey_id', 'destination_id']);
        });

        Schema::create('journey_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained()->cascadeOnDelete();
            $table->unique(['journey_id', 'experience_id']);
        });

        Schema::create('journey_stay', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stay_id')->constrained()->cascadeOnDelete();
            $table->unique(['journey_id', 'stay_id']);
        });

        Schema::create('destination_stay', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stay_id')->constrained()->cascadeOnDelete();
            $table->unique(['destination_id', 'stay_id']);
        });

        Schema::create('destination_pulse_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pulse_item_id')->constrained()->cascadeOnDelete();
            $table->unique(['destination_id', 'pulse_item_id']);
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('journey_id')->nullable()->after('destination_id')->constrained()->nullOnDelete();
            $table->json('preferred_destinations')->nullable()->after('message');
            $table->string('days')->nullable()->after('preferred_destinations');
            $table->string('travellers')->nullable()->after('days');
            $table->json('preferred_experiences')->nullable()->after('travellers');
            $table->string('accommodation')->nullable()->after('preferred_experiences');
            $table->string('investment')->nullable()->after('accommodation');
            $table->string('travel_dates')->nullable()->after('investment');
            $table->text('preferences')->nullable()->after('travel_dates');
            $table->string('whatsapp')->nullable()->after('preferences');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('journey_id');
            $table->dropColumn([
                'preferred_destinations',
                'days',
                'travellers',
                'preferred_experiences',
                'accommodation',
                'investment',
                'travel_dates',
                'preferences',
                'whatsapp',
            ]);
        });

        Schema::dropIfExists('destination_pulse_item');
        Schema::dropIfExists('destination_stay');
        Schema::dropIfExists('journey_stay');
        Schema::dropIfExists('journey_experience');
        Schema::dropIfExists('journey_destination');
        Schema::dropIfExists('journey_country');
        Schema::dropIfExists('destination_experience');
        Schema::dropIfExists('content_images');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('pulse_items');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('stays');
        Schema::dropIfExists('journeys');
        Schema::dropIfExists('experiences');

        Schema::table('destinations', function (Blueprint $table) {
            $table->string('country')->nullable()->after('slug');
        });

        $names = DB::table('countries')->pluck('name', 'id');
        foreach (DB::table('destinations')->get() as $destination) {
            DB::table('destinations')->where('id', $destination->id)->update([
                'country' => $names[$destination->country_id] ?? 'Uganda',
            ]);
        }

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('country_id');
            $table->dropColumn(['why', 'practical']);
        });

        Schema::dropIfExists('countries');
    }
};
