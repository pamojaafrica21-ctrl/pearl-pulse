<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $countries = Country::query()->pluck('id', 'slug');

        $items = [
            [
                'name' => 'Bwindi Impenetrable Forest',
                'subtitle' => 'Mountain gorilla country',
                'country' => 'uganda',
                'region' => 'Southwest',
                'teaser' => 'Misty montane forest and the world’s most intimate gorilla encounters.',
                'duration' => '2–3 nights',
                'best_time' => 'June–August & December–February',
                'activities' => 'Gorilla trekking, nature walks, community visits',
                'price_from' => 'From $1,800 pp',
                'description' => '<p>Bwindi is a cathedral of green — steep slopes draped in ancient forest, home to nearly half of the world’s remaining mountain gorillas. Treks are humbling, physical, and unforgettable.</p><p>Stay in lodges perched above the canopy, then descend into the forest with expert trackers for a rare hour among a habituated family.</p>',
                'why' => '<p>This is where Pearl Pulse began: local trackers, forest mornings, and journeys shaped around a single unforgettable hour with gorillas.</p>',
                'practical' => '<p>Permits are limited and should be secured months ahead in peak season. Treks can be steep; a reasonable level of fitness helps.</p>',
                'highlights' => [
                    ['label' => 'Altitude', 'value' => '1,160–2,607 m'],
                    ['label' => 'Permit tip', 'value' => 'Book gorilla permits months ahead'],
                ],
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kibale Forest',
                'subtitle' => 'The primate capital',
                'country' => 'uganda',
                'region' => 'West',
                'teaser' => 'Thirteen primate species and some of East Africa’s finest chimpanzee tracking.',
                'duration' => '2 nights',
                'best_time' => 'June–September & December–February',
                'activities' => 'Chimpanzee tracking, birding, crater-lake walks',
                'description' => '<p>Kibale’s tropical forest holds one of the highest primate densities on the continent. Chimpanzee tracking here is intimate and often vocal — a different rhythm from gorilla country.</p>',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Queen Elizabeth National Park',
                'subtitle' => 'Craters, channels, and tree-climbing lions',
                'country' => 'uganda',
                'region' => 'Southwest',
                'teaser' => 'Kazinga Channel boat safaris, crater lakes, and a classic Ugandan wildlife chapter.',
                'duration' => '2–3 nights',
                'best_time' => 'June–September & December–February',
                'activities' => 'Game drives, boat safari, crater walks',
                'description' => '<p>Queen Elizabeth stretches from the Rwenzori foothills to the Kazinga Channel. Hippo, elephant, and a remarkable bird list sit alongside the chance of tree-climbing lions in Ishasha.</p>',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Murchison Falls',
                'subtitle' => 'Where the Nile thunders',
                'country' => 'uganda',
                'region' => 'Northwest',
                'teaser' => 'The Nile forced through a rocky gorge — thunder, wildlife, and open savannah.',
                'duration' => '2–3 nights',
                'best_time' => 'December–February & June–September',
                'activities' => 'Game drives, Nile boat safari, hike to the falls',
                'price_from' => 'From $1,200 pp',
                'description' => '<p>At Murchison Falls, the Victoria Nile explodes through a seven-metre gap before plunging into the gorge below. Boat safaris, game drives, and the roar of the falls define classic Ugandan safari.</p>',
                'highlights' => [
                    ['label' => 'Wildlife', 'value' => 'Elephant, giraffe, hippo, lion'],
                ],
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Lake Mburo',
                'subtitle' => 'A gentle close to Uganda',
                'country' => 'uganda',
                'region' => 'West',
                'teaser' => 'Walking safari, lakeshore horses, and zebra country an easy drive from Kampala.',
                'duration' => '1–2 nights',
                'best_time' => 'June–August & December–February',
                'activities' => 'Walking safari, boat, horseback',
                'description' => '<p>Lake Mburo is compact, accessible, and ideal for a first or last night in Uganda. Walks with a ranger bring you close to zebra, impala, and lakeshore birds.</p>',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Volcanoes National Park',
                'subtitle' => 'Rwanda’s highland wilderness',
                'country' => 'rwanda',
                'region' => 'Northern Province',
                'teaser' => 'Volcanic peaks, golden monkeys, and mountain gorilla trekking in style.',
                'duration' => '2–3 nights',
                'best_time' => 'June–September & December–February',
                'activities' => 'Gorilla & golden monkey trekking, Dian Fossey hike',
                'price_from' => 'From $2,200 pp',
                'description' => '<p>Rwanda’s Volcanoes National Park pairs world-class gorilla permits with refined lodges and excellent guiding. Add golden monkey treks and community experiences for a complete highland chapter.</p>',
                'highlights' => [
                    ['label' => 'Base', 'value' => 'Musanze / Kinigi'],
                ],
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Akagera National Park',
                'subtitle' => 'Rwanda’s Big Five savannah',
                'country' => 'rwanda',
                'region' => 'East',
                'teaser' => 'Lakes, plains, and a carefully restored Big Five park on Rwanda’s eastern edge.',
                'duration' => '2 nights',
                'best_time' => 'June–September',
                'activities' => 'Game drives, boat safari',
                'description' => '<p>Akagera’s lakes and acacia woodland offer a quieter complement to gorilla country — lion, rhino, and elephant in a landscape still being restored.</p>',
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'Maasai Mara',
                'subtitle' => 'Migration plains of Kenya',
                'country' => 'kenya',
                'region' => 'Rift Valley',
                'teaser' => 'Endless plains, big cats, and the drama of the Great Migration.',
                'duration' => '3–4 nights',
                'best_time' => 'July–October for migration; year-round for wildlife',
                'activities' => 'Game drives, hot-air ballooning, cultural visits',
                'price_from' => 'From $2,400 pp',
                'description' => '<p>The Mara’s golden grasslands are synonymous with East African safari. From July to October, wildebeest and zebra pour across the Mara River; year-round, lion, leopard, and cheetah thrive.</p>',
                'highlights' => [
                    ['label' => 'Signature experience', 'value' => 'Balloon safari at dawn'],
                ],
                'is_featured' => true,
                'sort_order' => 8,
                'slug' => 'masai-mara',
            ],
            [
                'name' => 'Amboseli',
                'subtitle' => 'Elephants under Kilimanjaro',
                'country' => 'kenya',
                'region' => 'South',
                'teaser' => 'Great tuskers, swamp-edge game, and the mountain on a clear morning.',
                'duration' => '2–3 nights',
                'best_time' => 'June–October & January–February',
                'activities' => 'Game drives, photography',
                'description' => '<p>Amboseli is elephant country first. When the cloud lifts, Kilimanjaro sits behind the herds — one of East Africa’s most photographed scenes.</p>',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'Samburu',
                'subtitle' => 'Northern specialists',
                'country' => 'kenya',
                'region' => 'North',
                'teaser' => 'Dry-country wildlife, the Ewaso Nyiro, and a different Kenya beyond the Mara.',
                'duration' => '2–3 nights',
                'best_time' => 'June–October',
                'activities' => 'Game drives, walking, cultural visits',
                'description' => '<p>Samburu’s arid beauty holds Grevy’s zebra, reticulated giraffe, and gerenuk. It pairs beautifully with the Mara for travellers who want contrast.</p>',
                'is_featured' => false,
                'sort_order' => 10,
            ],
            [
                'name' => 'Serengeti',
                'subtitle' => 'The endless plain',
                'country' => 'tanzania',
                'region' => 'Northern Circuit',
                'teaser' => 'The endless plain — migration corridors and quiet corners beyond the crowds.',
                'duration' => '3–5 nights',
                'best_time' => 'December–March (south) & June–October (north/west)',
                'activities' => 'Game drives, walking safaris, balloon flights',
                'price_from' => 'From $2,600 pp',
                'description' => '<p>Serengeti means “endless plains” in Maasai. Follow the herds across the south in calving season, or seek solitude in the western corridor and north when the migration moves.</p>',
                'highlights' => [
                    ['label' => 'Pair with', 'value' => 'Ngorongoro Crater'],
                ],
                'is_featured' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Ngorongoro',
                'subtitle' => 'The crater floor',
                'country' => 'tanzania',
                'region' => 'Northern Circuit',
                'teaser' => 'A collapsed caldera dense with wildlife — a single, extraordinary day on the floor.',
                'duration' => '1–2 nights',
                'best_time' => 'June–October & December–March',
                'activities' => 'Crater game drive',
                'description' => '<p>Ngorongoro concentrates lion, rhino, and flamingo-edged soda lakes inside a volcanic bowl. We treat it as a highlight, not a rush.</p>',
                'is_featured' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'Tarangire',
                'subtitle' => 'Baobabs and elephant herds',
                'country' => 'tanzania',
                'region' => 'Northern Circuit',
                'teaser' => 'Baobab silhouettes and some of Tanzania’s greatest elephant concentrations.',
                'duration' => '2 nights',
                'best_time' => 'June–October',
                'activities' => 'Game drives, walking',
                'description' => '<p>Tarangire’s dry-season river draws elephant in remarkable numbers. It is a strong opening or close to a northern circuit journey.</p>',
                'is_featured' => false,
                'sort_order' => 13,
            ],
            [
                'name' => 'Zanzibar',
                'subtitle' => 'Spice, stone, and Indian Ocean',
                'country' => 'tanzania',
                'region' => 'Coast',
                'teaser' => 'A coastal coda — Stone Town, spice farms, and warm water after the bush.',
                'duration' => '3–5 nights',
                'best_time' => 'June–October & December–March',
                'activities' => 'Beach, Stone Town, snorkelling',
                'description' => '<p>Zanzibar is how many journeys exhale. We choose stays for character and coastline, not a generic resort strip.</p>',
                'is_featured' => false,
                'sort_order' => 14,
            ],
        ];

        foreach ($items as $item) {
            $slug = $item['slug'] ?? Str::slug($item['name']);
            $rich = SeedCopy::destination($slug);

            $destination = Destination::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'subtitle' => $item['subtitle'],
                    'country_id' => $countries[$item['country']] ?? null,
                    'region' => $item['region'],
                    'teaser' => $item['teaser'],
                    'duration' => $item['duration'] ?? null,
                    'best_time' => $item['best_time'] ?? null,
                    'activities' => $item['activities'] ?? null,
                    'price_from' => $item['price_from'] ?? null,
                    'description' => $rich['description'] ?? $item['description'],
                    'why' => $rich['why'] ?? ($item['why'] ?? null),
                    'practical' => $rich['practical'] ?? ($item['practical'] ?? null),
                    'highlights' => $item['highlights'] ?? [],
                    'meta_title' => $item['name'].' | Pearl Pulse Safaris',
                    'meta_description' => $item['teaser'],
                    'is_featured' => $item['is_featured'],
                    'status' => 'published',
                    'sort_order' => $item['sort_order'],
                ]
            );

            $destination->update([
                'cover_path' => SeedImage::photo($slug, "seed/{$slug}.jpg"),
            ]);
        }
    }
}
