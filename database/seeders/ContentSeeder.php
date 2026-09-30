<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Journey;
use App\Models\Page;
use App\Models\PulseItem;
use App\Models\Review;
use App\Models\Specialist;
use App\Models\Stay;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->countries();
        $experiences = $this->experiences();
        $stays = $this->stays();
        $journeys = $this->journeys($experiences, $stays);
        $this->team();
        $this->reviews($journeys);
        $this->articles();
        $this->pulse();
        $this->faqs();
        $this->specialists();
        $this->pages();
        $this->attachExperiences($experiences);
    }

    protected function countries(): void
    {
        foreach (['uganda', 'rwanda', 'kenya', 'tanzania'] as $slug) {
            $data = SeedCopy::country($slug);
            Country::query()->where('slug', $slug)->update([
                'subtitle' => $data['subtitle'] ?? null,
                'teaser' => $data['teaser'],
                'description' => $data['description'],
                'practical' => $data['practical'],
                'best_time' => $data['best_time'],
                'meta_title' => Country::query()->where('slug', $slug)->value('name').' Safaris | Pearl Pulse',
                'meta_description' => $data['teaser'],
                'cover_path' => SeedImage::photo($slug, "seed/country-{$slug}.jpg", 1800, 1200),
            ]);
        }
    }

    protected function experiences(): array
    {
        $items = [
            ['Gorilla Trekking', 'An hour with a habituated family in Bwindi or Volcanoes.', 'The encounter that defines many Pearl Pulse journeys.', 1],
            ['Big Five Safari', 'Lion, leopard, elephant, rhino, and buffalo — privately guided.', 'Classic wildlife days in Kenya, Tanzania, and Uganda.', 2],
            ['Chimpanzee Tracking', 'Forest mornings in Kibale and beyond.', 'A vocal, energetic counterpart to gorilla country.', 3],
            ['Birding & Shoebill', 'Shoebill, Albertine endemics, and patient guiding.', 'For travellers who want more than the checklist.', 4],
            ['Wildlife Photography', 'Light, vehicles, and time shaped around the image.', 'Private vehicles and guiding that understand a photographer’s pace.', 5],
            ['Culture & Community', 'Visits that belong to the journey, not a detour.', 'Time with people who make these landscapes possible.', 6],
            ['Adventure', 'Rafting, hiking, and days that ask more of the body.', 'When you want Africa to feel physical as well as quiet.', 7],
            ['Pure Pulse / Wellness', 'Rest, water, and space between wildlife days.', 'A slower pulse — spa, lakeshore, or simply fewer transfers.', 8],
            ['Boat & Water Experiences', 'The Nile, Kazinga, and Indian Ocean.', 'Wildlife from the water, and the coast after the bush.', 9],
        ];

        $models = [];
        foreach ($items as [$name, $teaser, $subtitle, $sort]) {
            $slug = Str::slug($name);
            $models[$slug] = Experience::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'subtitle' => $subtitle,
                    'teaser' => $teaser,
                    'description' => SeedCopy::experience($slug)['description'] ?: "<p>{$teaser}</p>",
                    'cover_path' => SeedImage::photo($slug, "seed/experience-{$slug}.jpg"),
                    'meta_title' => $name.' | Pearl Pulse Safaris',
                    'meta_description' => $teaser,
                    'is_featured' => $sort <= 6,
                    'status' => 'published',
                    'sort_order' => $sort,
                ]
            );
        }

        return $models;
    }

    protected function stays(): array
    {
        $destinations = Destination::query()->pluck('id', 'slug');

        $items = [
            [
                'name' => 'Buhoma Forest Lodge',
                'slug' => 'buhoma-forest-lodge',
                'destination' => 'bwindi-impenetrable-forest',
                'location' => 'Bwindi, Uganda',
                'style' => 'Comfortable',
                'teaser' => 'A forest-edge stay chosen for access to the northern sector and a quiet evening after the trek.',
            ],
            [
                'name' => 'Ishasha Wilderness Camp',
                'slug' => 'ishasha-wilderness-camp',
                'destination' => 'queen-elizabeth-national-park',
                'location' => 'Queen Elizabeth, Uganda',
                'style' => 'Luxury',
                'teaser' => 'Selected for southern-sector game and the chance of tree-climbing lions.',
            ],
            [
                'name' => 'Mara Plains Camp',
                'slug' => 'mara-plains-camp',
                'destination' => 'masai-mara',
                'location' => 'Maasai Mara, Kenya',
                'style' => 'Ultra-luxury',
                'teaser' => 'A highly considered camp for travellers who want exclusivity and exceptional guiding.',
            ],
            [
                'name' => 'Serengeti Safari Camp',
                'slug' => 'serengeti-safari-camp',
                'destination' => 'serengeti',
                'location' => 'Serengeti, Tanzania',
                'style' => 'Luxury',
                'teaser' => 'A mobile-feeling stay placed for the season, not a permanent postcard.',
            ],
            [
                'name' => 'Volcanoes View Lodge',
                'slug' => 'volcanoes-view-lodge',
                'destination' => 'volcanoes-national-park',
                'location' => 'Musanze, Rwanda',
                'style' => 'Luxury',
                'teaser' => 'Highland comfort after a gorilla day — selected for character, not ownership.',
            ],
        ];

        $models = [];
        foreach ($items as $i => $item) {
            $destinationId = $destinations[$item['destination']] ?? null;
            $models[$item['slug']] = Stay::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'subtitle' => 'A selected stay',
                    'teaser' => $item['teaser'],
                    'description' => SeedCopy::stay($item['slug'])['description'] ?: '<p>'.$item['teaser'].'</p>',
                    'location' => $item['location'],
                    'destination_id' => $destinationId,
                    'style' => $item['style'],
                    'cover_path' => SeedImage::photo($item['slug'], 'seed/stay-'.$item['slug'].'.jpg'),
                    'meta_title' => $item['name'].' | Selected Stays',
                    'meta_description' => $item['teaser'],
                    'is_featured' => $i < 4,
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );

            if ($destinationId) {
                $models[$item['slug']]->destinations()->syncWithoutDetaching([$destinationId]);
            }
        }

        return $models;
    }

    protected function journeys(array $experiences, array $stays): array
    {
        $countries = Country::query()->pluck('id', 'slug');
        $destinations = Destination::query()->pluck('id', 'slug');

        $definitions = [
            [
                'name' => '5-Day Uganda Gorilla & Wildlife Journey',
                'slug' => '5-day-uganda-gorilla-wildlife',
                'subtitle' => 'Bwindi and a wildlife chapter',
                'teaser' => 'Gorilla trekking in Bwindi, then savannah and water — a short, complete Uganda.',
                'days' => 5,
                'price_mode' => 'from',
                'price_from' => 'From $3,400 pp',
                'is_signature' => true,
                'is_multi_country' => false,
                'is_featured' => true,
                'countries' => ['uganda'],
                'destinations' => ['bwindi-impenetrable-forest', 'queen-elizabeth-national-park'],
                'experiences' => ['gorilla-trekking', 'big-five-safari', 'boat-water-experiences'],
                'stays' => ['buhoma-forest-lodge', 'ishasha-wilderness-camp'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrive Uganda', 'description' => 'Meet in Entebbe. After a briefing we travel toward the southwest at an unhurried pace, with a proper lunch stop — not a race to make the forest by dark.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['L', 'D']],
                    ['day' => 2, 'title' => 'Bwindi at rest', 'description' => 'Settle above the canopy. A short forest-edge walk if you want one, and a detailed briefing for tomorrow’s trek. Sleep early; the walk can be long.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'Gorilla trek', 'description' => 'Permit briefing, walk with trackers, and a regulated hour with a habituated family. Afternoon to recover at the lodge — not another transfer.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'Queen Elizabeth and the channel', 'description' => 'Change of landscape: savannah and a boat on the Kazinga Channel. Hippo, elephant, and birds from the water. Evening in a selected stay chosen for access.', 'stay_slug' => 'ishasha-wilderness-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Depart', 'description' => 'A final morning and the road or hop back toward Entebbe — or onward if we have already planned the next country.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Uganda Classic',
                'slug' => 'uganda-classic',
                'subtitle' => 'Primates and the Nile',
                'teaser' => 'Bwindi, Kibale, and Murchison — the Uganda we recommend when you have a little more time.',
                'days' => 8,
                'price_mode' => 'from',
                'price_from' => 'From $5,200 pp',
                'is_signature' => true,
                'countries' => ['uganda'],
                'destinations' => ['bwindi-impenetrable-forest', 'kibale-forest', 'murchison-falls'],
                'experiences' => ['gorilla-trekking', 'chimpanzee-tracking', 'boat-water-experiences'],
                'stays' => ['buhoma-forest-lodge'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Entebbe to the southwest', 'description' => 'Arrive Uganda. After a briefing we travel toward Bwindi at an unhurried pace, with a proper lunch stop — not a race to make the forest by dark.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['L', 'D']],
                    ['day' => 2, 'title' => 'Bwindi at rest', 'description' => 'A forest-edge day: short walk, community visit if you want one, and a detailed briefing for tomorrow’s trek. Sleep early.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'Gorilla trek', 'description' => 'Permit briefing, walk with trackers, and a regulated hour with a habituated family. Afternoon to recover at the lodge.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'Toward Kibale', 'description' => 'Leave the high forest for chimpanzee country. The drive is part of seeing Uganda change under the wheels.', 'stay_name' => 'Kibale forest lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Chimpanzee tracking', 'description' => 'A vocal morning in Kibale with a habituated community. Optional wetland or crater time if legs allow.', 'stay_name' => 'Kibale forest lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 6, 'title' => 'North to the Nile', 'description' => 'Travel toward Murchison. We break the journey so you arrive able to look at the river, not only the pillow.', 'stay_name' => 'Murchison riverside stay', 'meals' => ['B', 'L', 'D']],
                    ['day' => 7, 'title' => 'Falls and game', 'description' => 'Boat toward the base of the falls, a walk to the top if you wish, and a game drive on the north bank.', 'stay_name' => 'Murchison riverside stay', 'meals' => ['B', 'L', 'D']],
                    ['day' => 8, 'title' => 'Depart', 'description' => 'A last morning and the road or flight back to Entebbe — or onward if we have already planned the next country.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Rwanda Highland Gorilla Journey',
                'slug' => 'rwanda-highland-gorilla',
                'subtitle' => 'Volcanoes, then rest',
                'teaser' => 'A refined gorilla chapter in Rwanda, with time to breathe in the highlands.',
                'days' => 4,
                'price_mode' => 'from',
                'price_from' => 'From $4,800 pp',
                'is_signature' => false,
                'countries' => ['rwanda'],
                'destinations' => ['volcanoes-national-park'],
                'experiences' => ['gorilla-trekking'],
                'stays' => ['volcanoes-view-lodge'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Kigali to the volcanoes', 'description' => 'Arrive Kigali and travel into the highlands. Time to settle, breathe the cooler air, and walk the lodge grounds.', 'stay_slug' => 'volcanoes-view-lodge', 'meals' => ['D']],
                    ['day' => 2, 'title' => 'Gorilla trek', 'description' => 'Briefing at the park, walk with trackers, and an hour with a habituated family. The afternoon is for rest, not another activity.', 'stay_slug' => 'volcanoes-view-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'A second forest day', 'description' => 'Golden monkeys, the Dian Fossey hike, or simply a quiet highland morning — we choose with you, not from a default add-on.', 'stay_slug' => 'volcanoes-view-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'Return to Kigali', 'description' => 'A last view of the peaks if the cloud lifts, then the city and your flight. Easy to extend into Akagera or Uganda.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Kenya Mara Migration',
                'slug' => 'kenya-mara-migration',
                'subtitle' => 'Private vehicles on the plains',
                'teaser' => 'The Mara at the right time of year, with guiding that leaves room for wonder.',
                'days' => 6,
                'price_mode' => 'from',
                'price_from' => 'From $6,400 pp',
                'is_signature' => true,
                'countries' => ['kenya'],
                'destinations' => ['masai-mara'],
                'experiences' => ['big-five-safari', 'wildlife-photography'],
                'stays' => ['mara-plains-camp'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Nairobi to the Mara', 'description' => 'A scheduled or private hop to the conservancy. Afternoon drive to understand the light and where the cats have been.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['L', 'D']],
                    ['day' => 2, 'title' => 'Full safari day', 'description' => 'Dawn departure, a proper rest, last light. Private vehicle — we stay with a sighting instead of collecting a list.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'River or plains', 'description' => 'In season we give time to the Mara River. Out of season we work the conservancy and the reserve edge for cats and elephant.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'A slower morning', 'description' => 'Optional balloon, or a later start if yesterday was long. Afternoon drive shaped around photography if that is your pace.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Last full day', 'description' => 'We return to areas that were quiet or promising. Guiding, not a new loop for the sake of it.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 6, 'title' => 'Fly to Nairobi', 'description' => 'A final short drive and the hop back. International connections are planned so you are not running through the terminal.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Tanzania Northern Circuit',
                'slug' => 'tanzania-northern-circuit',
                'subtitle' => 'Tarangire, crater, and Serengeti',
                'teaser' => 'The classic sequence, paced so each park still feels distinct.',
                'days' => 8,
                'price_mode' => 'from',
                'price_from' => 'From $7,100 pp',
                'is_signature' => true,
                'countries' => ['tanzania'],
                'destinations' => ['tarangire', 'ngorongoro', 'serengeti'],
                'experiences' => ['big-five-safari', 'wildlife-photography'],
                'stays' => ['serengeti-safari-camp'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arusha to Tarangire', 'description' => 'Meet in Arusha and enter baobab country. Afternoon among elephant if the river is drawing herds.', 'stay_name' => 'Tarangire lodge', 'meals' => ['L', 'D']],
                    ['day' => 2, 'title' => 'Tarangire at length', 'description' => 'A full day in the park — river, baobabs, and time to photograph rather than transit.', 'stay_name' => 'Tarangire lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'Toward Ngorongoro', 'description' => 'Travel to the crater rim. Evening briefing so tomorrow’s descent is unhurried.', 'stay_name' => 'Ngorongoro rim lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'The crater floor', 'description' => 'Descend after breakfast, spend the useful hours on the floor, picnic, and climb out before the light goes.', 'stay_name' => 'Ngorongoro rim lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Into the Serengeti', 'description' => 'Enter the plains. Camp is placed for the month you travel, not a single famous kopje.', 'stay_slug' => 'serengeti-safari-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 6, 'title' => 'Serengeti', 'description' => 'Game drives shaped around the herds or the resident cats — we decide with the guide each evening.', 'stay_slug' => 'serengeti-safari-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 7, 'title' => 'Serengeti, again', 'description' => 'A second full day so the first was not your only chance. Balloon optional.', 'stay_slug' => 'serengeti-safari-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 8, 'title' => 'Depart', 'description' => 'Light aircraft or road back toward Arusha. Zanzibar can begin the same afternoon if we have already built it in.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Uganda & Rwanda Gorilla Crossing',
                'slug' => 'uganda-rwanda-gorilla-crossing',
                'subtitle' => 'Two forests, one journey',
                'teaser' => 'Bwindi and Volcanoes in a single private itinerary — for travellers who want both.',
                'days' => 7,
                'price_mode' => 'tailored',
                'is_signature' => true,
                'is_multi_country' => true,
                'countries' => ['uganda', 'rwanda'],
                'destinations' => ['bwindi-impenetrable-forest', 'volcanoes-national-park'],
                'experiences' => ['gorilla-trekking'],
                'stays' => ['buhoma-forest-lodge', 'volcanoes-view-lodge'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrive Uganda', 'description' => 'Entebbe briefing and the road toward Bwindi. We do not try to trek on arrival day.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['L', 'D']],
                    ['day' => 2, 'title' => 'Bwindi forest', 'description' => 'Settle, walk, and prepare. The trek is tomorrow; tonight is for altitude and rest.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'Uganda gorilla trek', 'description' => 'A Bwindi family in the sector we booked for your fitness. Afternoon to recover.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'Cross to Rwanda', 'description' => 'A private transfer through the southwest and into the highlands. Immigration is planned, not improvised.', 'stay_slug' => 'volcanoes-view-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Volcanoes rest', 'description' => 'A highland day before the second permit — golden monkeys or simply the view.', 'stay_slug' => 'volcanoes-view-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 6, 'title' => 'Rwanda gorilla trek', 'description' => 'A second forest, a second hour. The comparison is the point for travellers who asked for both.', 'stay_slug' => 'volcanoes-view-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 7, 'title' => 'Kigali and depart', 'description' => 'Return to the city. We can reverse the crossing if your flights prefer Kigali first.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Uganda to Kenya',
                'slug' => 'uganda-to-kenya',
                'subtitle' => 'Forests, then the Mara',
                'teaser' => 'Gorillas and chimps, then open plains — a two-country journey with a clear change of light.',
                'days' => 10,
                'price_mode' => 'tailored',
                'is_multi_country' => true,
                'countries' => ['uganda', 'kenya'],
                'destinations' => ['bwindi-impenetrable-forest', 'kibale-forest', 'masai-mara'],
                'experiences' => ['gorilla-trekking', 'chimpanzee-tracking', 'big-five-safari'],
                'stays' => ['buhoma-forest-lodge', 'mara-plains-camp'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrive Entebbe', 'description' => 'Rest after the long flight. We do not put you on the southwest road until you can enjoy it.', 'stay_name' => 'Entebbe lakeshore hotel', 'meals' => ['D']],
                    ['day' => 2, 'title' => 'Toward Bwindi', 'description' => 'Travel into gorilla country with a proper pause. Lodge by last light.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'Gorilla trek', 'description' => 'Bwindi briefing, walk, and a regulated hour. Evening above the canopy.', 'stay_slug' => 'buhoma-forest-lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'Kibale', 'description' => 'Leave the high forest for chimpanzee country.', 'stay_name' => 'Kibale forest lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Chimpanzees', 'description' => 'A vocal morning with a habituated community. Afternoon at an easier pace.', 'stay_name' => 'Kibale forest lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 6, 'title' => 'Entebbe and Nairobi', 'description' => 'Return to Entebbe and the hop to Kenya. We protect the connection so this is not a stressful day.', 'stay_name' => 'Nairobi overnight', 'meals' => ['B', 'L', 'D']],
                    ['day' => 7, 'title' => 'The Mara', 'description' => 'Conservancy arrival and a first drive — open country after days of forest.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 8, 'title' => 'Plains', 'description' => 'A full safari day. Private vehicle, cats and light.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 9, 'title' => 'Plains, again', 'description' => 'Second full day so the change of landscape has time to settle.', 'stay_slug' => 'mara-plains-camp', 'meals' => ['B', 'L', 'D']],
                    ['day' => 10, 'title' => 'Nairobi and depart', 'description' => 'Fly to Nairobi. International departures are timed with a buffer, not a prayer.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
            [
                'name' => 'Pure Pulse: Wildlife & Wellness',
                'slug' => 'pure-pulse-wildlife-wellness',
                'subtitle' => 'Fewer transfers, deeper rest',
                'teaser' => 'A slower safari with space for water, quiet, and the kind of days that restore.',
                'days' => 9,
                'price_mode' => 'proposal',
                'is_signature' => true,
                'is_multi_country' => true,
                'countries' => ['uganda', 'tanzania'],
                'destinations' => ['lake-mburo', 'zanzibar'],
                'experiences' => ['pure-pulse-wellness', 'boat-water-experiences'],
                'stays' => [],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrive Uganda, slowly', 'description' => 'Entebbe or a lakeshore night. No long transfer on day one.', 'stay_name' => 'Lakeshore rest', 'meals' => ['D']],
                    ['day' => 2, 'title' => 'Lake Mburo', 'description' => 'Walking safari, boat, and a pace that does not steal tomorrow.', 'stay_name' => 'Lake Mburo lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 3, 'title' => 'Water and rest', 'description' => 'A second Mburo day or a private lakeshore — we choose with you.', 'stay_name' => 'Lake Mburo lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 4, 'title' => 'A gentle wildlife day', 'description' => 'Optional Queen Elizabeth boat if you want more wildlife without a full circuit.', 'stay_name' => 'Lake Mburo lodge', 'meals' => ['B', 'L', 'D']],
                    ['day' => 5, 'title' => 'Travel day, protected', 'description' => 'We move toward the coast connection without treating the airport as the destination.', 'stay_name' => 'Travel day', 'meals' => ['B', 'L']],
                    ['day' => 6, 'title' => 'Zanzibar arrives', 'description' => 'Stone Town or the beach — we do not try to do both before you have slept.', 'stay_name' => 'Zanzibar beach house', 'meals' => ['D']],
                    ['day' => 7, 'title' => 'The ocean', 'description' => 'Swim, walk, or do nothing. This is the point of Pure Pulse.', 'stay_name' => 'Zanzibar beach house', 'meals' => ['B', 'L', 'D']],
                    ['day' => 8, 'title' => 'Spice or reef', 'description' => 'A single outing if you want one. We will not stack a town tour and a snorkel on the same tired morning.', 'stay_name' => 'Zanzibar beach house', 'meals' => ['B', 'L', 'D']],
                    ['day' => 9, 'title' => 'Depart', 'description' => 'A last swim if flights allow. We write the proposal around your actual departure, not a brochure checkout.', 'stay_name' => 'Departure day', 'meals' => ['B']],
                ],
            ],
        ];

        $models = [];
        foreach ($definitions as $i => $item) {
            $journey = Journey::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'subtitle' => $item['subtitle'],
                    'teaser' => $item['teaser'],
                    'overview' => SeedCopy::journey($item['slug']),
                    'days' => $item['days'],
                    'duration_label' => $item['days'].' days',
                    'itinerary' => $this->resolveItineraryDays($item['itinerary'] ?? [
                        ['day' => 1, 'title' => 'Arrive', 'description' => 'A gentle arrival and first briefing. We do not trek or game-drive on a long-haul day unless you ask.', 'meals' => ['D']],
                        ['day' => 2, 'title' => 'Into the journey', 'description' => 'Travel privately toward your first wilderness, with a proper pause — not a transfer that steals the light.', 'meals' => ['B', 'L', 'D']],
                    ], $stays),
                    'highlights' => [
                        ['label' => 'Pace', 'value' => 'Private, tailor-made'],
                        ['label' => 'Guiding', 'value' => 'Exceptional local guides'],
                        ['label' => 'Vehicles', 'value' => 'Yours — not a seat on a shared loop'],
                        ['label' => 'Stays', 'value' => 'Selected, never “our lodges”'],
                    ],
                    'included' => ['Private vehicle and guide', 'Selected stays as outlined', 'Park fees and permits as confirmed', 'Meals as specified in your proposal', 'Bottled water on game drives and treks'],
                    'not_included' => ['International flights', 'Visa fees', 'Travel insurance', 'Drinks and personal extras', 'Tips (we will suggest a fair range)'],
                    'best_time' => 'We match dates to wildlife, weather, and permit availability — not a generic high season.',
                    'practical' => '<p>This journey can be shortened, extended, or combined with another country. Gorilla permits, conservancy fees, and crater rules change; we confirm the current picture in your proposal before you pay a deposit.</p><p>Tell us about fitness, children, photography, and whether you want more forest or more plains. We would rather adjust the outline than surprise you on the ground.</p>',
                    'price_mode' => $item['price_mode'],
                    'price_from' => $item['price_from'] ?? null,
                    'is_signature' => $item['is_signature'] ?? false,
                    'is_multi_country' => $item['is_multi_country'] ?? false,
                    'is_featured' => $item['is_featured'] ?? ($i < 4),
                    'cover_path' => SeedImage::photo($item['slug'], 'seed/journey-'.$item['slug'].'.jpg', 1800, 1200),
                    'meta_title' => $item['name'].' | Pearl Pulse Safaris',
                    'meta_description' => $item['teaser'],
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );

            $journey->countries()->sync(array_values(array_filter(array_map(
                fn ($slug) => $countries[$slug] ?? null,
                $item['countries']
            ))));
            $journey->destinations()->sync(array_values(array_filter(array_map(
                fn ($slug) => $destinations[$slug] ?? null,
                $item['destinations']
            ))));
            $journey->experiences()->sync(array_values(array_filter(array_map(
                fn ($slug) => $experiences[$slug]->id ?? null,
                $item['experiences']
            ))));
            $journey->stays()->sync(array_values(array_filter(array_map(
                fn ($slug) => $stays[$slug]->id ?? null,
                $item['stays']
            ))));

            $models[$item['slug']] = $journey;
        }

        return $models;
    }

    /**
     * @param  list<array<string, mixed>>  $days
     * @param  array<string, Stay>  $stays
     * @return list<array<string, mixed>>
     */
    protected function resolveItineraryDays(array $days, array $stays): array
    {
        $resolved = [];

        foreach (array_values($days) as $i => $day) {
            $row = [
                'day' => (int) ($day['day'] ?? $i + 1),
                'title' => (string) ($day['title'] ?? ''),
                'description' => (string) ($day['description'] ?? ''),
            ];

            $staySlug = $day['stay_slug'] ?? null;
            if ($staySlug && isset($stays[$staySlug])) {
                $row['stay_id'] = $stays[$staySlug]->id;
            }

            if (! empty($day['stay_name'])) {
                $row['stay_name'] = (string) $day['stay_name'];
            }

            $meals = $day['meals'] ?? [];
            if (is_string($meals)) {
                $meals = array_map('trim', explode(',', $meals));
            }
            $meals = array_values(array_intersect(['B', 'L', 'D'], $meals));
            if ($meals !== []) {
                $row['meals'] = $meals;
            }

            if (! empty($day['image_path'])) {
                $row['image_path'] = (string) $day['image_path'];
            }

            $resolved[] = $row;
        }

        return $resolved;
    }

    protected function team(): void
    {
        $people = [
            ['Amina N.', 'Lead Uganda guide', 'Born in Kampala, Amina has spent a decade in Bwindi and Queen Elizabeth. She designs days around light and wildlife behaviour — not a checklist — and briefs guests so the forest feels approachable, never rushed.'],
            ['Joseph K.', 'Safari director', 'Joseph matches travellers to the right parks and the right pace: private vehicles, fewer transfers, deeper stays. He is the quiet centre of logistics — permits, lodge holds, and the honest “no” when a route will not work.'],
            ['Grace M.', 'Rwanda specialist', 'Grace knows the volcanoes and the quiet logistics that make a gorilla morning feel seamless. She favours highland nights, clear briefings, and camps that leave room to breathe after the trek.'],
            ['Daniel O.', 'Kenya & Tanzania planner', 'Daniel stitches Mara, Serengeti, and coast chapters with an eye on migration intelligence and light for photographers. He prefers conservancy camps when guests want fewer vehicles and longer evenings.'],
        ];

        foreach ($people as $i => [$name, $role, $bio]) {
            TeamMember::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'role' => $role,
                    'bio' => $bio,
                    'cover_path' => SeedImage::photo('team-'.Str::slug($name), 'seed/team-'.Str::slug($name).'.jpg', 900, 1100),
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );
        }
    }

    protected function reviews(array $journeys): void
    {
        $items = [
            ['Elena Rossi', 'Italy', 'They knew the forest, and they knew when to be quiet. It never felt like a package.', '5-day-uganda-gorilla-wildlife', 'elena'],
            ['James Whitaker', 'United Kingdom', 'Private vehicles, exceptional guides, and a journey that felt designed for us — not sold to us.', 'kenya-mara-migration', 'james'],
            ['Sofia Mensah', 'Ghana', 'Local, warm, and precise. We left feeling we had been looked after by people who belong here.', 'uganda-rwanda-gorilla-crossing', 'sofia'],
            ['Tom & Aya Nakamura', 'Japan', 'Photography light protected, transfers calm, and evenings that never felt rushed. We will return.', 'kenya-mara-migration', 'tom-aya'],
            ['Claire Dubois', 'France', 'Our children still talk about the boat at Kazinga. Pearl Pulse paced every day so nobody was left behind.', '5-day-uganda-gorilla-wildlife', 'claire'],
            ['Marcus Okonkwo', 'Nigeria', 'Honest counsel when we asked for too much in too few days — then a proposal that actually fitted.', 'uganda-rwanda-gorilla-crossing', 'marcus'],
        ];

        foreach ($items as $i => [$name, $country, $quote, $journey, $slug]) {
            Review::query()->updateOrCreate(
                ['guest_name' => $name],
                [
                    'guest_country' => $country,
                    'quote' => $quote,
                    'cover_path' => SeedImage::photo('review-'.$slug, 'seed/review-'.$slug.'.jpg', 1200, 900),
                    'journey_id' => $journeys[$journey]->id ?? null,
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );
        }
    }

    protected function articles(): void
    {
        $items = [
            ['Best time for gorilla trekking in Uganda', 'field', 'Dry-season mornings, permit reality, and what “best” actually means.', 1],
            ['Uganda vs Rwanda gorilla trekking', 'guide', 'Two forests, two permits, and how we help you choose.', 2],
            ['What to pack for gorilla trekking', 'practical', 'Layers, gloves, and the few things that actually matter on the trail.', 3],
            ['Visa and entry for East Africa', 'practical', 'A clear starting point for Uganda, Rwanda, Kenya, and Tanzania.', 4],
            ['How to combine gorilla trekking with a wildlife safari', 'guide', 'The sequences we recommend when you want both forest and plains.', 5],
            ['Best time for a Uganda safari', 'field', 'When the parks breathe, and when to avoid the longest rains.', 6],
        ];

        foreach ($items as [$title, $type, $excerpt, $sort]) {
            Article::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'type' => $type,
                    'excerpt' => $excerpt,
                    'body' => SeedCopy::articleBody($excerpt),
                    'cover_path' => SeedImage::photo('article', 'seed/article-'.Str::slug($title).'.jpg', 1600, 1000),
                    'meta_title' => $title.' | Insiders',
                    'meta_description' => $excerpt,
                    'is_featured' => $sort <= 3,
                    'status' => 'published',
                    'sort_order' => $sort,
                    'published_at' => now()->subDays($sort),
                ]
            );
        }
    }

    protected function pulse(): void
    {
        $destinations = Destination::query()->pluck('id', 'slug');

        $items = [
            ['Morning in Bwindi', 'photo', 'Mist lifting off the forest after the trek.', 'Hannah L.'],
            ['Kazinga at dusk', 'story', 'Hippo, fishermen, and a sky that went copper.', 'David O.'],
            ['Mara crossing', 'reel', 'A minute of river and dust — shared with permission.', 'Priya S.'],
        ];

        foreach ($items as $i => [$title, $type, $caption, $guest]) {
            $item = PulseItem::query()->updateOrCreate(
                ['title' => $title],
                [
                    'type' => $type,
                    'caption' => $caption,
                    'guest_name' => $guest,
                    'cover_path' => SeedImage::photo('pulse-'.Str::slug($title), 'seed/pulse-'.Str::slug($title).'.jpg'),
                    'approved' => true,
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );

            $item->destinations()->sync(array_filter([
                $destinations['bwindi-impenetrable-forest'] ?? null,
            ]));
        }
    }

    protected function faqs(): void
    {
        $uganda = Country::query()->where('slug', 'uganda')->first();

        $site = [
            ['permits', 'Can you arrange gorilla permits?', 'Yes. Permits are limited and often sell out months ahead in peak season. We recommend starting the conversation early so we can secure the right dates and parks — Uganda, Rwanda, or both.'],
            ['permits', 'How far ahead should we book permits?', 'For June–September and Christmas peak windows, six to twelve months is wise. Shoulder months can be shorter, but popular lodges still fill. We will tell you honestly what is still available.'],
            ['permits', 'Are chimpanzee permits easier than gorilla permits?', 'Usually yes — but busy weeks still book out. We confirm current allocation when we propose your dates.'],
            ['best_time', 'When is the best time to visit East Africa?', 'It depends on what you want to see. Dry seasons (roughly June–September and December–February) are most reliable for wildlife and trekking. We still travel in the green seasons when it suits your pace and interests — fewer vehicles, softer light, and often better rates.'],
            ['best_time', 'Is the rainy season a bad idea?', 'Not necessarily. Short rains can mean greener landscapes and quieter parks. Long rains can slow roads and close some treks briefly. We match season to your priorities, not a calendar myth.'],
            ['best_time', 'When is the Great Migration best?', 'Herds move with rain and grass — timing shifts year to year. We plan Mara–Serengeti chapters around the latest intelligence, not a fixed brochure month.'],
            ['inclusions', 'What is typically included in a private journey?', 'Private guiding and vehicles, lodge nights as confirmed, park fees and permits as agreed, and the logistics that stitch each day together. International flights, visas, travel insurance, and personal expenses are usually separate — we spell this out clearly in your proposal.'],
            ['inclusions', 'Do you own the lodges?', 'No. We select stays for location, character, and how they complement your journey. We do not own camps — we curate preferred partners.'],
            ['inclusions', 'Are meals and park fees included?', 'Meals follow each lodge’s plan (often full board on safari). Park fees and permits are confirmed line by line in your proposal so there are no surprises.'],
            ['families', 'Is Pearl Pulse suitable for families?', 'Yes. We design family journeys with age-appropriate pacing, quieter camps where possible, and activities that work for mixed ages — from gentle game drives to cultural visits and swimming holes.'],
            ['families', 'How old must children be for gorilla trekking?', 'Parks set minimum ages (commonly 15 for mountain gorillas). We plan chimpanzee forest, savannah, and boat days for younger travellers, and keep gorilla days for those who qualify.'],
            ['families', 'Can grandparents and toddlers travel together?', 'Often yes — with private vehicles, flexible daily rhythm, and lodges that welcome mixed ages. Tell us the ages and energy levels; we design around them.'],
            ['payment_cancellation', 'How does payment work?', 'We outline a clear deposit and balance schedule in your proposal, tied to lodge and permit deadlines. International bank transfer is most common; we confirm details once the itinerary is agreed.'],
            ['payment_cancellation', 'What is your cancellation policy?', 'Terms depend on permits, stays, and season. Non-refundable permits and lodge deposits are common close to travel. We set everything out in writing before you pay a deposit — see also our Cancellation Policy page.'],
            ['payment_cancellation', 'Can we change dates after booking?', 'Sometimes — subject to permit availability and lodge rules. Early notice helps. We renegotiate on your behalf and confirm any cost difference before you decide.'],
            ['health_visas', 'Do I need a yellow fever vaccination?', 'Many travellers to Uganda and the region need proof of yellow fever vaccination for entry. Rules change — check official sources and your doctor; we share current guidance when you enquire.'],
            ['health_visas', 'What about malaria and travel insurance?', 'East Africa is a malaria region for many itineraries. Discuss prophylaxis with a travel clinic. Comprehensive travel insurance (including evacuation) is essential; we can outline what cover to look for.'],
            ['health_visas', 'Do you help with visas?', 'We advise on typical requirements and visa-on-arrival or e-visa processes, but you remain responsible for valid documents. We include a practical briefing in your pre-departure notes.'],
        ];

        foreach ($site as $i => [$topic, $q, $a]) {
            Faq::query()->updateOrCreate(
                ['question' => $q, 'group' => 'site'],
                [
                    'answer' => $a,
                    'topic' => $topic,
                    'status' => 'published',
                    'sort_order' => $i + 1,
                    'faqable_type' => null,
                    'faqable_id' => null,
                ]
            );
        }

        if ($uganda) {
            Faq::query()->updateOrCreate(
                ['question' => 'When should I visit Uganda?', 'group' => 'country'],
                [
                    'answer' => 'June–September and December–February are the most reliable. We still travel in the rains when it suits the traveller.',
                    'faqable_type' => Country::class,
                    'faqable_id' => $uganda->id,
                    'status' => 'published',
                    'sort_order' => 1,
                ]
            );
        }
    }

    protected function specialists(): void
    {
        $items = [
            [
                'slug' => 'family',
                'name' => 'Family journeys',
                'subtitle' => 'Travel together',
                'teaser' => 'Private days paced for mixed ages — wildlife without the rush, lodges that welcome children, and memories the whole family owns.',
                'description' => '<p>A family safari should feel generous, not exhausting. We shape routes around nap windows, shorter drives when needed, and camps where young travellers are welcomed rather than merely tolerated.</p><p>Gorilla trekking has age rules; chimpanzee forest and savannah days can be gentler. We balance iconic encounters with swimming holes, boat time, and evenings that do not run late.</p><p>Tell us the ages in your group and the kind of days that keep everyone happy — we will build from there.</p>',
            ],
            [
                'slug' => 'honeymoon',
                'name' => 'Honeymoon journeys',
                'subtitle' => 'Quiet romance',
                'teaser' => 'Private vehicles, secluded camps, and unhurried mornings — East Africa as a shared chapter, not a checklist.',
                'description' => '<p>Honeymoons with Pearl Pulse favour privacy: your own vehicle, carefully chosen suites, and space between activities so the trip feels like time together rather than a schedule.</p><p>Many couples combine a primate chapter in Uganda or Rwanda with open plains in Kenya or Tanzania, then finish with water — a lakeshore, or the coast.</p><p>We handle permits and logistics quietly so you can stay in the moment.</p>',
            ],
            [
                'slug' => 'photography',
                'name' => 'Photography journeys',
                'subtitle' => 'Light first',
                'teaser' => 'Vehicles, guides, and timing shaped around the image — golden hours protected, not rushed past.',
                'description' => '<p>Photography trips need different logistics: flexible departure times, fewer travellers in the vehicle, and guides who understand composition as well as behaviour.</p><p>We draw on our <a href="/experiences/wildlife-photography">Wildlife Photography</a> and <a href="/experiences/birding-shoebill">Birding</a> experience pages when shaping the brief — then tailor parks and seasons to the subjects you care about most.</p><p>Bring your shot list. We will protect the light.</p>',
            ],
            [
                'slug' => 'wellness',
                'name' => 'Wellness journeys',
                'subtitle' => 'A slower pulse',
                'teaser' => 'Rest between wildlife days — spa, water, highland air, and itineraries that leave room to breathe.',
                'description' => '<p>Not every chapter needs to be full. Wellness journeys weave wildlife with recovery: spa mornings, lakeshore nights, and fewer transfers so the body can keep up with the wonder.</p><p>Explore our <a href="/experiences/pure-pulse-wellness">Pure Pulse / Wellness</a> experience for the tone we aim for — then we place it inside a private route that still delivers the Africa you came for.</p><p>Tell us how you want to feel at the end of each day. We will design toward that.</p>',
            ],
        ];

        foreach ($items as $i => $item) {
            Specialist::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'subtitle' => $item['subtitle'],
                    'teaser' => $item['teaser'],
                    'description' => $item['description'],
                    'cover_path' => SeedImage::photo('specialist-'.$item['slug'], 'seed/specialist-'.$item['slug'].'.jpg', 1800, 1200, true),
                    'meta_title' => $item['name'].' | Pearl Pulse Safaris',
                    'meta_description' => $item['teaser'],
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );
        }

        // Full-bleed animal backdrop for the homepage Specialist Journeys section.
        SeedImage::photo('specialist-section-bg', 'seed/specialist-section-bg.jpg', 2200, 1400, true);
    }

    protected function pages(): void
    {
        $pages = [
            [
                'privacy',
                'Privacy Policy',
                '<p>Pearl Pulse Safaris (“we”, “us”) respects your privacy. This policy explains what information we collect when you use pearlpulse.com or enquire with us, how we use it, and your choices.</p>'.
                '<h2>Information we collect</h2>'.
                '<p>We may collect your name, email address, phone or WhatsApp number, travel preferences, and message content when you submit an enquiry, create an account, or otherwise contact us. We also collect technical data such as IP address, browser type, and pages visited (including via cookies — see our Cookie Policy).</p>'.
                '<h2>How we use information</h2>'.
                '<p>We use your information to respond to enquiries, design and administer your journey, send booking-related communications, improve our website, and meet legal obligations. We do not sell personal information.</p>'.
                '<h2>Sharing</h2>'.
                '<p>We may share necessary details with lodges, parks, transport partners, and payment processors solely to deliver your journey. Service providers who host our site or email may process data on our instructions.</p>'.
                '<h2>Retention & security</h2>'.
                '<p>We keep enquiry and booking records for as long as needed for the journey and legitimate business or legal purposes. We apply reasonable technical and organisational measures to protect personal data.</p>'.
                '<h2>Your rights & contact</h2>'.
                '<p>Depending on where you live, you may have rights to access, correct, or delete personal data. Contact us at the email address on our Contact page. We may update this policy; the “last updated” date on the page reflects the latest revision.</p>',
            ],
            [
                'terms',
                'Terms & Conditions',
                '<p>By using this website or requesting a proposal from Pearl Pulse Safaris, you agree to these terms. Journeys are privately arranged; a written confirmation and proposal will set out inclusions, exclusions, prices, and payment schedules for your specific trip.</p>'.
                '<h2>Website use</h2>'.
                '<p>Content on this site is for general information. Sample itineraries are starting points, not fixed products. Images and descriptions of lodges are illustrative; availability and standards may change.</p>'.
                '<h2>Bookings</h2>'.
                '<p>A booking is formed when we confirm your itinerary in writing and receive the required deposit. You are responsible for accurate traveller details, valid travel documents, visas, vaccinations, and suitable travel insurance.</p>'.
                '<h2>Prices & changes</h2>'.
                '<p>Prices may change until confirmed. Park fees, permits, fuel, and taxes can adjust with little notice; we will communicate material changes and options before you are committed further.</p>'.
                '<h2>Liability</h2>'.
                '<p>Safari travel involves inherent risks. We plan carefully and work with trusted partners, but we are not liable for events outside our reasonable control (including weather, wildlife behaviour, political disruption, or third-party failures). Nothing in these terms excludes liability that cannot be excluded by law.</p>'.
                '<h2>Governing law</h2>'.
                '<p>These terms are governed by the laws of Uganda unless your confirmation states otherwise. Contact us via the Contact page for questions about these terms.</p>',
            ],
            [
                'cancellation',
                'Cancellation Policy',
                '<p>Cancellation terms depend on permits, lodge contracts, and season. The schedule in your written proposal prevails for your booking. The outline below is typical guidance only.</p>'.
                '<h2>Deposits</h2>'.
                '<p>Deposits secure limited permits and lodge inventory. Gorilla and chimpanzee permits are often non-refundable once issued. Lodge deposits may become non-refundable closer to arrival according to each property’s rules.</p>'.
                '<h2>Guest cancellations</h2>'.
                '<p>If you cancel, we will recover what we can from suppliers and refund any unused portion after deductions for non-recoverable costs and our reasonable administration. Early notice improves outcomes.</p>'.
                '<h2>Our cancellations</h2>'.
                '<p>If we must cancel for reasons within our control, we will offer a suitable alternative or a refund of amounts paid for undelivered services. Force majeure events may limit refunds where suppliers retain funds.</p>'.
                '<h2>Travel insurance</h2>'.
                '<p>We strongly recommend comprehensive travel insurance that covers cancellation, curtailment, medical treatment, and evacuation. Ask us what cover to look for before you pay a deposit.</p>',
            ],
            [
                'cookies',
                'Cookie Policy',
                '<p>This Cookie Policy explains how Pearl Pulse Safaris uses cookies and similar technologies on our website.</p>'.
                '<h2>What are cookies?</h2>'.
                '<p>Cookies are small text files stored on your device. They help the site function, remember preferences, and understand which pages are useful.</p>'.
                '<h2>Cookies we use</h2>'.
                '<ul><li><strong>Essential</strong> — required for security, session, and core features (for example login and form submission).</li><li><strong>Analytics</strong> — help us see aggregated traffic patterns so we can improve content and performance. Where used, we prefer privacy-conscious settings.</li></ul>'.
                '<h2>Your choices</h2>'.
                '<p>You can control cookies through your browser settings, including blocking or deleting them. Blocking essential cookies may affect site functionality. For personal data processed via cookies, see our Privacy Policy.</p>'.
                '<h2>Updates</h2>'.
                '<p>We may update this policy when our tools or practices change. The date on this page shows the latest revision.</p>',
            ],
        ];

        foreach ($pages as $i => [$slug, $title, $content]) {
            Page::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => $content,
                    'meta_title' => $title.' | Pearl Pulse Safaris',
                    'meta_description' => 'Pearl Pulse Safaris '.$title.'.',
                    'status' => 'published',
                    'sort_order' => $i + 1,
                ]
            );
        }
    }

    protected function attachExperiences(array $experiences): void
    {
        $map = [
            'bwindi-impenetrable-forest' => ['gorilla-trekking', 'culture-community'],
            'kibale-forest' => ['chimpanzee-tracking', 'birding-shoebill'],
            'queen-elizabeth-national-park' => ['big-five-safari', 'boat-water-experiences'],
            'murchison-falls' => ['big-five-safari', 'boat-water-experiences'],
            'volcanoes-national-park' => ['gorilla-trekking'],
            'masai-mara' => ['big-five-safari', 'wildlife-photography'],
            'serengeti' => ['big-five-safari', 'wildlife-photography'],
            'zanzibar' => ['pure-pulse-wellness', 'boat-water-experiences'],
        ];

        foreach ($map as $destSlug => $expSlugs) {
            $destination = Destination::query()->where('slug', $destSlug)->first();
            if (! $destination) {
                continue;
            }
            $ids = array_values(array_filter(array_map(
                fn ($slug) => $experiences[$slug]->id ?? null,
                $expSlugs
            )));
            $destination->experiences()->sync($ids);
        }
    }
}
