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
                    ['day' => 1, 'title' => 'Arrive Uganda', 'description' => 'Meet in Entebbe. After a briefing we travel toward the southwest at an unhurried pace, with a proper lunch stop — not a race to make the forest by dark.'],
                    ['day' => 2, 'title' => 'Bwindi at rest', 'description' => 'Settle above the canopy. A short forest-edge walk if you want one, and a detailed briefing for tomorrow’s trek. Sleep early; the walk can be long.'],
                    ['day' => 3, 'title' => 'Gorilla trek', 'description' => 'Permit briefing, walk with trackers, and a regulated hour with a habituated family. Afternoon to recover at the lodge — not another transfer.'],
                    ['day' => 4, 'title' => 'Queen Elizabeth and the channel', 'description' => 'Change of landscape: savannah and a boat on the Kazinga Channel. Hippo, elephant, and birds from the water. Evening in a selected stay chosen for access.'],
                    ['day' => 5, 'title' => 'Depart', 'description' => 'A final morning and the road or hop back toward Entebbe — or onward if we have already planned the next country.'],
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
                    ['day' => 1, 'title' => 'Entebbe to the southwest', 'description' => 'Arrive Uganda. After a briefing we travel toward Bwindi at an unhurried pace, with a proper lunch stop — not a race to make the forest by dark.'],
                    ['day' => 2, 'title' => 'Bwindi at rest', 'description' => 'A forest-edge day: short walk, community visit if you want one, and a detailed briefing for tomorrow’s trek. Sleep early.'],
                    ['day' => 3, 'title' => 'Gorilla trek', 'description' => 'Permit briefing, walk with trackers, and a regulated hour with a habituated family. Afternoon to recover at the lodge.'],
                    ['day' => 4, 'title' => 'Toward Kibale', 'description' => 'Leave the high forest for chimpanzee country. The drive is part of seeing Uganda change under the wheels.'],
                    ['day' => 5, 'title' => 'Chimpanzee tracking', 'description' => 'A vocal morning in Kibale with a habituated community. Optional wetland or crater time if legs allow.'],
                    ['day' => 6, 'title' => 'North to the Nile', 'description' => 'Travel toward Murchison. We break the journey so you arrive able to look at the river, not only the pillow.'],
                    ['day' => 7, 'title' => 'Falls and game', 'description' => 'Boat toward the base of the falls, a walk to the top if you wish, and a game drive on the north bank.'],
                    ['day' => 8, 'title' => 'Depart', 'description' => 'A last morning and the road or flight back to Entebbe — or onward if we have already planned the next country.'],
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
                    ['day' => 1, 'title' => 'Kigali to the volcanoes', 'description' => 'Arrive Kigali and travel into the highlands. Time to settle, breathe the cooler air, and walk the lodge grounds.'],
                    ['day' => 2, 'title' => 'Gorilla trek', 'description' => 'Briefing at the park, walk with trackers, and an hour with a habituated family. The afternoon is for rest, not another activity.'],
                    ['day' => 3, 'title' => 'A second forest day', 'description' => 'Golden monkeys, the Dian Fossey hike, or simply a quiet highland morning — we choose with you, not from a default add-on.'],
                    ['day' => 4, 'title' => 'Return to Kigali', 'description' => 'A last view of the peaks if the cloud lifts, then the city and your flight. Easy to extend into Akagera or Uganda.'],
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
                    ['day' => 1, 'title' => 'Nairobi to the Mara', 'description' => 'A scheduled or private hop to the conservancy. Afternoon drive to understand the light and where the cats have been.'],
                    ['day' => 2, 'title' => 'Full safari day', 'description' => 'Dawn departure, a proper rest, last light. Private vehicle — we stay with a sighting instead of collecting a list.'],
                    ['day' => 3, 'title' => 'River or plains', 'description' => 'In season we give time to the Mara River. Out of season we work the conservancy and the reserve edge for cats and elephant.'],
                    ['day' => 4, 'title' => 'A slower morning', 'description' => 'Optional balloon, or a later start if yesterday was long. Afternoon drive shaped around photography if that is your pace.'],
                    ['day' => 5, 'title' => 'Last full day', 'description' => 'We return to areas that were quiet or promising. Guiding, not a new loop for the sake of it.'],
                    ['day' => 6, 'title' => 'Fly to Nairobi', 'description' => 'A final short drive and the hop back. International connections are planned so you are not running through the terminal.'],
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
                    ['day' => 1, 'title' => 'Arusha to Tarangire', 'description' => 'Meet in Arusha and enter baobab country. Afternoon among elephant if the river is drawing herds.'],
                    ['day' => 2, 'title' => 'Tarangire at length', 'description' => 'A full day in the park — river, baobabs, and time to photograph rather than transit.'],
                    ['day' => 3, 'title' => 'Toward Ngorongoro', 'description' => 'Travel to the crater rim. Evening briefing so tomorrow’s descent is unhurried.'],
                    ['day' => 4, 'title' => 'The crater floor', 'description' => 'Descend after breakfast, spend the useful hours on the floor, picnic, and climb out before the light goes.'],
                    ['day' => 5, 'title' => 'Into the Serengeti', 'description' => 'Enter the plains. Camp is placed for the month you travel, not a single famous kopje.'],
                    ['day' => 6, 'title' => 'Serengeti', 'description' => 'Game drives shaped around the herds or the resident cats — we decide with the guide each evening.'],
                    ['day' => 7, 'title' => 'Serengeti, again', 'description' => 'A second full day so the first was not your only chance. Balloon optional.'],
                    ['day' => 8, 'title' => 'Depart', 'description' => 'Light aircraft or road back toward Arusha. Zanzibar can begin the same afternoon if we have already built it in.'],
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
                    ['day' => 1, 'title' => 'Arrive Uganda', 'description' => 'Entebbe briefing and the road toward Bwindi. We do not try to trek on arrival day.'],
                    ['day' => 2, 'title' => 'Bwindi forest', 'description' => 'Settle, walk, and prepare. The trek is tomorrow; tonight is for altitude and rest.'],
                    ['day' => 3, 'title' => 'Uganda gorilla trek', 'description' => 'A Bwindi family in the sector we booked for your fitness. Afternoon to recover.'],
                    ['day' => 4, 'title' => 'Cross to Rwanda', 'description' => 'A private transfer through the southwest and into the highlands. Immigration is planned, not improvised.'],
                    ['day' => 5, 'title' => 'Volcanoes rest', 'description' => 'A highland day before the second permit — golden monkeys or simply the view.'],
                    ['day' => 6, 'title' => 'Rwanda gorilla trek', 'description' => 'A second forest, a second hour. The comparison is the point for travellers who asked for both.'],
                    ['day' => 7, 'title' => 'Kigali and depart', 'description' => 'Return to the city. We can reverse the crossing if your flights prefer Kigali first.'],
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
                    ['day' => 1, 'title' => 'Arrive Entebbe', 'description' => 'Rest after the long flight. We do not put you on the southwest road until you can enjoy it.'],
                    ['day' => 2, 'title' => 'Toward Bwindi', 'description' => 'Travel into gorilla country with a proper pause. Lodge by last light.'],
                    ['day' => 3, 'title' => 'Gorilla trek', 'description' => 'Bwindi briefing, walk, and a regulated hour. Evening above the canopy.'],
                    ['day' => 4, 'title' => 'Kibale', 'description' => 'Leave the high forest for chimpanzee country.'],
                    ['day' => 5, 'title' => 'Chimpanzees', 'description' => 'A vocal morning with a habituated community. Afternoon at an easier pace.'],
                    ['day' => 6, 'title' => 'Entebbe and Nairobi', 'description' => 'Return to Entebbe and the hop to Kenya. We protect the connection so this is not a stressful day.'],
                    ['day' => 7, 'title' => 'The Mara', 'description' => 'Conservancy arrival and a first drive — open country after days of forest.'],
                    ['day' => 8, 'title' => 'Plains', 'description' => 'A full safari day. Private vehicle, cats and light.'],
                    ['day' => 9, 'title' => 'Plains, again', 'description' => 'Second full day so the change of landscape has time to settle.'],
                    ['day' => 10, 'title' => 'Nairobi and depart', 'description' => 'Fly to Nairobi. International departures are timed with a buffer, not a prayer.'],
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
                    ['day' => 1, 'title' => 'Arrive Uganda, slowly', 'description' => 'Entebbe or a lakeshore night. No long transfer on day one.'],
                    ['day' => 2, 'title' => 'Lake Mburo', 'description' => 'Walking safari, boat, and a pace that does not steal tomorrow.'],
                    ['day' => 3, 'title' => 'Water and rest', 'description' => 'A second Mburo day or a private lakeshore — we choose with you.'],
                    ['day' => 4, 'title' => 'A gentle wildlife day', 'description' => 'Optional Queen Elizabeth boat if you want more wildlife without a full circuit.'],
                    ['day' => 5, 'title' => 'Travel day, protected', 'description' => 'We move toward the coast connection without treating the airport as the destination.'],
                    ['day' => 6, 'title' => 'Zanzibar arrives', 'description' => 'Stone Town or the beach — we do not try to do both before you have slept.'],
                    ['day' => 7, 'title' => 'The ocean', 'description' => 'Swim, walk, or do nothing. This is the point of Pure Pulse.'],
                    ['day' => 8, 'title' => 'Spice or reef', 'description' => 'A single outing if you want one. We will not stack a town tour and a snorkel on the same tired morning.'],
                    ['day' => 9, 'title' => 'Depart', 'description' => 'A last swim if flights allow. We write the proposal around your actual departure, not a brochure checkout.'],
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
                    'itinerary' => $item['itinerary'] ?? [
                        ['day' => 1, 'title' => 'Arrive', 'description' => 'A gentle arrival and first briefing. We do not trek or game-drive on a long-haul day unless you ask.'],
                        ['day' => 2, 'title' => 'Into the journey', 'description' => 'Travel privately toward your first wilderness, with a proper pause — not a transfer that steals the light.'],
                    ],
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

    protected function team(): void
    {
        $people = [
            ['Amina N.', 'Lead Uganda guide', 'Born in Kampala, Amina has spent a decade in Bwindi and Queen Elizabeth. She designs days around light, not a checklist.'],
            ['Joseph K.', 'Safari director', 'Joseph matches travellers to the right parks and the right pace — private vehicles, fewer transfers, deeper stays.'],
            ['Grace M.', 'Rwanda specialist', 'Grace knows the volcanoes and the quiet logistics that make a gorilla morning feel seamless.'],
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
            ['Elena Rossi', 'Italy', 'They knew the forest, and they knew when to be quiet. It never felt like a package.', '5-day-uganda-gorilla-wildlife'],
            ['James Whitaker', 'United Kingdom', 'Private vehicles, exceptional guides, and a journey that felt designed for us — not sold to us.', 'kenya-mara-migration'],
            ['Sofia Mensah', 'Ghana', 'Local, warm, and precise. We left feeling we had been looked after by people who belong here.', 'uganda-rwanda-gorilla-crossing'],
        ];

        foreach ($items as $i => [$name, $country, $quote, $journey]) {
            Review::query()->updateOrCreate(
                ['guest_name' => $name],
                [
                    'guest_country' => $country,
                    'quote' => $quote,
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
            ['How do you price a journey?', 'We show a from-price where it helps, or invite a private proposal for highly tailored travel. Luxury is in the experience, not a catalogue number.'],
            ['Do you own lodges?', 'No. We select stays for location, character, and how they complement your journey.'],
            ['Can you arrange gorilla permits?', 'Yes. Permits are limited; we recommend starting the conversation early.'],
        ];

        foreach ($site as $i => [$q, $a]) {
            Faq::query()->updateOrCreate(
                ['question' => $q, 'group' => 'site'],
                ['answer' => $a, 'status' => 'published', 'sort_order' => $i + 1]
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

    protected function pages(): void
    {
        $pages = [
            ['privacy', 'Privacy Policy', '<p>We collect only what we need to plan your journey and reply to you. We do not sell personal information.</p>'],
            ['terms', 'Terms & Conditions', '<p>Journeys are privately arranged. A detailed confirmation will outline what is included before you travel.</p>'],
            ['cancellation', 'Cancellation Policy', '<p>Cancellation terms depend on permits, stays, and season. We will set these out clearly in your proposal.</p>'],
            ['cookies', 'Cookie Policy', '<p>We use essential cookies to run the site and understand which pages are useful. You can control cookies in your browser.</p>'],
        ];

        foreach ($pages as $i => [$slug, $title, $content]) {
            Page::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => $content,
                    'meta_title' => $title.' | Pearl Pulse Safaris',
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
