<?php

namespace Database\Seeders;

class SeedCopy
{
    public static function country(string $slug): array
    {
        return match ($slug) {
            'uganda' => [
                'subtitle' => 'The pearl of Africa',
                'teaser' => 'Gorilla forests, chimpanzee country, and the Nile — planned by people who live here.',
                'description' => <<<'HTML'
<p>Uganda is our home, and it is still the country we recommend first when someone wants Africa to feel personal rather than packaged. You can trek mountain gorillas in Bwindi, sit with chimpanzees in Kibale, then follow the Nile through Murchison — all without the scale of a giant international itinerary.</p>
<p>The landscape changes quickly: montane forest, crater lakes, open savannah, and the roar of Murchison Falls. Distances are real, so we design days around light and rest, not a checklist. Private vehicles, local guides, and selected stays keep the journey quiet even when the wildlife is not.</p>
<p>We begin most journeys in Entebbe or Kampala. From there the southwest is gorilla and chimpanzee country; the northwest is river and plains. Tell us how many days you have and whether you want forest, water, or both — we will not pretend eight parks in eight nights is a gift.</p>
<h2>How a Uganda journey typically feels</h2>
<p>Short journeys often pair Bwindi with Queen Elizabeth: one forest morning, then savannah and a boat on the Kazinga Channel. With more time we add Kibale or Murchison so primates and the Nile both have room to breathe. Lake Mburo is a gentle first or last night if you want a walk before the flight home.</p>
HTML,
                'practical' => <<<'HTML'
<p>Gorilla permits are limited and should be secured months ahead for June–September and December–February. A yellow fever vaccination is typically required for entry. Roads between parks are improving but still take time; we prefer an extra night over a rushed transfer.</p>
<p>Fitness for gorilla trekking varies by sector and family location. We brief you honestly and can arrange a porter. Pack layers, broken-in boots, and garden gloves for the vegetation.</p>
HTML,
                'best_time' => 'June–September and December–February are the most reliable. We still travel in the rains when the forest suits you.',
            ],
            'rwanda' => [
                'subtitle' => 'Land of a thousand hills',
                'teaser' => 'Highland gorilla trekking, restored savannah, and a country that is compact and carefully run.',
                'description' => <<<'HTML'
<p>Rwanda is small on the map and large in feeling. Volcanoes National Park offers some of the most refined gorilla trekking in the region — excellent guiding, well-managed trails, and lodges that understand when to be quiet. Kigali is an easy, well-organised arrival.</p>
<p>Many travellers come only for gorillas. We often add a night or two in Akagera so the journey has plains and water as well as forest: lion, rhino, and elephant in a park that has been patiently restored. The contrast makes the highlands feel even more distinct.</p>
<p>Rwanda also pairs naturally with southwest Uganda. A crossing lets you trek in both Bwindi and Volcanoes if that is the story you want — two forests, two permit systems, one private itinerary.</p>
HTML,
                'practical' => <<<'HTML'
<p>Gorilla permits are a significant part of the investment and should be booked well ahead. Golden monkey treks and the Dian Fossey hike are worthwhile extras if your legs and dates allow. Dress for cool highland mornings even in the dry season.</p>
HTML,
                'best_time' => 'June–September and December–February',
            ],
            'kenya' => [
                'subtitle' => 'Savannah and migration country',
                'teaser' => 'Private vehicles on the Mara, elephants under Kilimanjaro, and a different Kenya in the north.',
                'description' => <<<'HTML'
<p>Kenya is where many travellers meet the Big Five and, in the right months, the Great Migration. We do not treat the Mara as a postcard. We choose camps for location and guiding, use private vehicles, and leave room in the day for the unexpected — a leopard at last light, or a crossing that is worth waiting for.</p>
<p>Beyond the Mara, Amboseli is elephant country with Kilimanjaro on a clear morning. Samburu is drier, more specialised, and a strong contrast if you have the nights. We would rather you see two landscapes properly than collect names.</p>
<p>Nairobi is the usual gateway. We plan connections so you are not exhausted before the first game drive. Migration weeks book early; if those dates are gone we will say so, and we will offer a Mara or Amboseli chapter that still feels exceptional.</p>
HTML,
                'practical' => <<<'HTML'
<p>July–October is the classic Mara River period. Wildlife is strong year-round. Conservancy stays often mean fewer vehicles and a different night-drive permission set than the reserve itself — we will explain the trade-offs before you choose.</p>
HTML,
                'best_time' => 'July–October for the Mara River crossings; year-round for wildlife',
            ],
            'tanzania' => [
                'subtitle' => 'Endless plains and spice islands',
                'teaser' => 'Tarangire, Ngorongoro, and the Serengeti — then the Indian Ocean if you want to exhale.',
                'description' => <<<'HTML'
<p>Tanzania’s northern circuit remains one of the great safari sequences: baobabs and elephant in Tarangire, a day on the crater floor at Ngorongoro, then the Serengeti’s open country. The risk is treating it like a race. We keep nights complementary so each park still feels distinct.</p>
<p>Where you stay in the Serengeti depends on the month. Calving in the south, the western corridor, or the north when the herds move — we place camps for the season, not a permanent postcard. A light-aircraft hop can spare you a long road day when it earns the cost.</p>
<p>Zanzibar is how many journeys finish: Stone Town, spice, and warm water after dust and dawn starts. We choose the coast for character, not a generic strip.</p>
HTML,
                'practical' => <<<'HTML'
<p>Arusha or Kilimanjaro are typical arrivals. Crater fees and vehicle rules change; we confirm the current picture in your proposal. If you want both migration drama and a quiet beach, tell us your dates early so the Serengeti camp and the coast still talk to each other.</p>
HTML,
                'best_time' => 'June–October and December–March, depending on where the herds are',
            ],
            default => ['teaser' => '', 'description' => '', 'practical' => '', 'best_time' => ''],
        };
    }

    public static function experience(string $slug): array
    {
        return match ($slug) {
            'gorilla-trekking' => [
                'description' => <<<'HTML'
<p>An hour with a habituated mountain gorilla family is the encounter that defines many Pearl Pulse journeys. It is physical, quiet, and tightly managed — and it should feel like a privilege, not a product.</p>
<h2>Where we trek</h2>
<p>In Uganda we work primarily in Bwindi Impenetrable Forest, matching the sector (Buhoma, Ruhija, Rushaga, Nkuringo) to your fitness and the lodges we have selected. In Rwanda, Volcanoes National Park offers excellent guiding and a more compact highland setting. Some travellers want both forests in one itinerary; we will be honest about whether the extra permit and transfer are worth it for you.</p>
<h2>How the day unfolds</h2>
<p>You brief at the park offices, walk with trackers who already know where the family slept, and spend a regulated hour once you arrive. We arrange a porter if you want one. The walk can be twenty minutes or several hours — that is the forest, not a failure of planning.</p>
<h2>What we need from you</h2>
<p>Permits must be secured early in peak months. A reasonable level of fitness helps. We will ask about knees, pace, and whether you prefer a shorter or more adventurous trek. Children have minimum ages; we will confirm the current rules before you book.</p>
HTML,
            ],
            'big-five-safari' => [
                'description' => <<<'HTML'
<p>Lion, leopard, elephant, rhino, and buffalo — privately guided, with time to stay with a sighting instead of racing the next vehicle. We use the phrase “Big Five” because travellers search for it; we design days around behaviour and light.</p>
<h2>Where it belongs</h2>
<p>Kenya’s Mara and Amboseli, Tanzania’s Serengeti, Ngorongoro, and Tarangire, and Uganda’s Queen Elizabeth and Murchison all offer serious wildlife. The mix of species and the feeling of the landscape are different in each. We will not pretend every park is interchangeable.</p>
<h2>How we pace it</h2>
<p>Private vehicles mean you can leave camp at a sensible hour, linger with a pride, and return for a rest before last light. We brief guides on whether you want photography, a first safari, or something slower after gorilla country.</p>
HTML,
            ],
            'chimpanzee-tracking' => [
                'description' => <<<'HTML'
<p>Chimpanzee tracking is louder and more mobile than a gorilla hour. In Kibale — East Africa’s primate capital — you follow a habituated community through tropical forest that can feel close and vocal.</p>
<h2>Why it pairs with gorillas</h2>
<p>Many travellers want both. We usually place Kibale after or before Bwindi so the two forests do not blur into one long drive. Chimpanzee permits are easier than gorilla permits but still worth arranging early in busy months.</p>
<p>Birders often add a second walk or Bigodi wetland. Tell us if primates or birds should lead the day.</p>
HTML,
            ],
            'birding-shoebill' => [
                'description' => <<<'HTML'
<p>Uganda is one of Africa’s great birding countries: Albertine endemics in the southwest, shoebill in the wetlands, and long lists along the Nile and Kazinga. We work with guides who understand when to be patient and when to move.</p>
<h2>How we design a birding chapter</h2>
<p>A dedicated birding journey is not a game-drive with binoculars. We allow pre-dawn starts, wetland time, and fewer transfers. If you are a serious lister, say so — we will not pad the itinerary with lodges that waste the morning.</p>
HTML,
            ],
            'wildlife-photography' => [
                'description' => <<<'HTML'
<p>Photography changes the shape of a day: beanbags, fewer passengers, and a guide who will wait for the head-turn. We use private vehicles and camps that understand a photographer’s hours.</p>
<h2>What we ask</h2>
<p>Tell us your kit, whether you want hides or open vehicles, and whether you are documenting a first safari or working toward a specific species. We will not promise a crossing or a leopard — we will put you in the right country, in the right month, with time.</p>
HTML,
            ],
            'culture-community' => [
                'description' => <<<'HTML'
<p>Visits to communities around Bwindi, the highlands, and the Mara should belong to the journey, not sit as a paid detour. We work with partners we know, keep group sizes small, and brief you on what is a visit and what is a performance.</p>
<p>If you would rather walk with a ranger or sit with a guide over a fire than attend a structured visit, say so. Authenticity is not a menu item — it is a relationship we protect.</p>
HTML,
            ],
            'adventure' => [
                'description' => <<<'HTML'
<p>Some travellers want Africa to ask more of the body: the Nile below Jinja, a longer gorilla sector, a crater-rim walk, or a hike that is not just a transfer in disguise. We place these days so they do not steal the wildlife chapter.</p>
<p>We will be clear about grades, water levels, and recovery time. Adventure here is chosen, not stacked until you are too tired to see a lion.</p>
HTML,
            ],
            'pure-pulse-wellness' => [
                'description' => <<<'HTML'
<p>Pure Pulse is our name for a slower safari: fewer transfers, water, and space between wildlife days. It might be a lakeshore after Queen Elizabeth, a Zanzibar close after the Serengeti, or simply an extra night so you are not always packing.</p>
<p>We do not sell a spa itinerary as a substitute for guiding. Rest is part of how you remember the forest and the plains.</p>
HTML,
            ],
            'boat-water-experiences' => [
                'description' => <<<'HTML'
<p>Water changes the angle: hippo and elephant along the Kazinga Channel, the Nile below Murchison Falls, crater lakes, and the Indian Ocean after the bush. Boat chapters are often the days guests describe first.</p>
<p>We time boats for light and wind, not a fixed hotel slot. If you want both a falls hike and a river afternoon, we will not try to do them as an afterthought on departure day.</p>
HTML,
            ],
            default => ['description' => ''],
        };
    }

    public static function destination(string $slug): array
    {
        return match ($slug) {
            'bwindi-impenetrable-forest' => [
                'description' => '<p>Bwindi is a cathedral of green — steep slopes of ancient forest, home to nearly half of the world’s remaining mountain gorillas. Treks are humbling and physical. You brief at the park gate, walk with trackers, and spend a regulated hour with a habituated family once you find them.</p><p>The four sectors (Buhoma, Ruhija, Rushaga, Nkuringo) feel different underfoot. We match sector, lodge, and your fitness so the day is demanding for the right reasons. Evenings are cool and quiet above the canopy.</p>',
                'why' => '<p>This is where Pearl Pulse began: local trackers, forest mornings, and journeys shaped around a single unforgettable hour. We still treat Bwindi as a place to stay, not a tick on a fly-in.</p>',
                'practical' => '<p>Permits should be secured months ahead in peak season. Treks can be steep; a porter is money well spent. Children have a minimum age. Yellow fever rules apply to Uganda as a whole.</p>',
            ],
            'kibale-forest' => [
                'description' => '<p>Kibale holds one of the highest primate densities in Africa — chimpanzees plus a long supporting cast of monkeys, and a bird list that rewards an early start. Tracking is often vocal and fast; the forest is closer and warmer than Bwindi.</p><p>We usually stay two nights so you are not driving out on the afternoon you have just walked. Bigodi wetland and crater-lake country sit next door if you want a second, slower day.</p>',
                'why' => '<p>Kibale is the primate counterpart to gorilla country. Together they tell a fuller Uganda story than either forest alone.</p>',
                'practical' => '<p>Chimpanzee permits are easier than gorilla permits but still book out on busy weeks. Trails can be muddy. Insect repellent and long sleeves earn their place.</p>',
            ],
            'queen-elizabeth-national-park' => [
                'description' => '<p>Queen Elizabeth stretches from the Rwenzori foothills to the Kazinga Channel. Hippo, elephant, kob, and a remarkable bird list sit alongside the chance of tree-climbing lions in Ishasha. A boat on the channel is often the finest hour of the park.</p><p>We treat the north and south as different chapters. If tree-climbing lions matter, we place you in Ishasha with enough game-drive time — not a lunchtime dash.</p>',
                'why' => '<p>This is the classic wildlife counterpart to Bwindi: savannah and water after the forest, without leaving Uganda.</p>',
                'practical' => '<p>Two or three nights is the honest minimum. The channel boat should be booked with a private or small-group option when we can. Distances inside the park are larger than they look on a map.</p>',
            ],
            'murchison-falls' => [
                'description' => '<p>Here the Victoria Nile is forced through a seven-metre gap before it falls into the gorge. Boat safaris, game drives on the north bank, and the walk to the top of the falls define a classic Ugandan chapter: thunder, giraffe, elephant, and hippo in one landscape.</p>',
                'why' => '<p>Murchison is Uganda at scale — river, plains, and a geological spectacle — and it pairs cleanly with primates if you have eight days or more.</p>',
                'practical' => '<p>The drive from Kampala is long; we often break it or fly. A boat plus a falls walk in the same day is possible if we do not also insist on a full game-drive loop.</p>',
            ],
            'lake-mburo' => [
                'description' => '<p>Lake Mburo is compact and close to the Kampala–southwest road. Walking with a ranger, a short boat, and zebra in open woodland make it an ideal first or last night — a safari that does not demand a 5 a.m. departure.</p>',
                'why' => '<p>We use Mburo when the journey needs a breath: arrival day, departure eve, or a Pure Pulse chapter with fewer transfers.</p>',
                'practical' => '<p>One or two nights is enough. Walking safaris are the point; say if you would rather not walk and we will design drives instead.</p>',
            ],
            'volcanoes-national-park' => [
                'description' => '<p>Rwanda’s Volcanoes National Park pairs world-class gorilla permits with highland lodges and excellent briefing. Golden monkeys and the Dian Fossey hike add days that are still about the forest, not a different country entirely.</p>',
                'why' => '<p>If you want gorilla trekking with refined logistics and a short hop from Kigali, this is the cleanest chapter we offer.</p>',
                'practical' => '<p>Permits are costly and limited. Nights are cold. Two or three nights lets the trek sit in the middle of the stay rather than on the afternoon you arrive.</p>',
            ],
            'akagera-national-park' => [
                'description' => '<p>Akagera’s lakes and acacia woodland are Rwanda’s Big Five savannah — lion, rhino, and elephant in a landscape still being restored. It is quieter than the Mara and a considered complement to Volcanoes.</p>',
                'why' => '<p>We add Akagera when a Rwanda journey should not be only highlands: water, plains, and a different dawn.</p>',
                'practical' => '<p>Two nights is the useful minimum. Boat time on the lakes is worth protecting in the itinerary.</p>',
            ],
            'masai-mara' => [
                'description' => '<p>The Mara’s grasslands are synonymous with East African safari. From July to October, wildebeest and zebra test the river; year-round, lion, leopard, and cheetah thrive. Conservancies at the edge often mean fewer vehicles and a different night.</p>',
                'why' => '<p>We come here for guiding and space, not for a logo on a hat. Private vehicles and the right camp matter more than collecting a crossing on day one.</p>',
                'practical' => '<p>Three or four nights is the honest stay. Balloon mornings are optional and book early. We will explain reserve versus conservancy before you choose.</p>',
            ],
            'amboseli' => [
                'description' => '<p>Amboseli is elephant country first. Swamp-edge herds, great tuskers, and — when the cloud lifts — Kilimanjaro behind them. It is one of the most photographed scenes in East Africa, and it still rewards a patient morning.</p>',
                'why' => '<p>We add Amboseli when Kenya should not be only the Mara: a different dust, a different mountain, a different scale of elephant.</p>',
                'practical' => '<p>Two or three nights. Midday haze can hide the mountain; we plan for first and last light.</p>',
            ],
            'samburu' => [
                'description' => '<p>Dry-country Kenya: Grevy’s zebra, reticulated giraffe, gerenuk, and the Ewaso Nyiro. Samburu is a specialist’s park and a strong contrast after the Mara if you have the nights.</p>',
                'why' => '<p>For travellers who want a second Kenya that is not a copy of the first.</p>',
                'practical' => '<p>Two or three nights. It is warmer and more arid; we plan water and shade into the day.</p>',
            ],
            'serengeti' => [
                'description' => '<p>Serengeti means “endless plains.” Follow calving in the south, the western corridor, or the north when the migration moves. We place camps for the month you travel, not a single famous kopje.</p>',
                'why' => '<p>This is Tanzania’s great open chapter — and it only works if we do not rush you toward Ngorongoro on the same afternoon.</p>',
                'practical' => '<p>Three to five nights. Light aircraft can save a road day. Balloon flights are optional.</p>',
            ],
            'ngorongoro' => [
                'description' => '<p>A collapsed caldera dense with lion, rhino, and flamingo-edged soda lakes. We treat the crater floor as a highlight day, not a race around the rim. Descent, time on the floor, and a night on the rim or nearby is the humane shape.</p>',
                'why' => '<p>One extraordinary bowl of wildlife — best when it is not sandwiched between two long transfers.</p>',
                'practical' => '<p>One or two nights. Vehicle numbers and fees change; we confirm the current picture in your proposal.</p>',
            ],
            'tarangire' => [
                'description' => '<p>Baobab silhouettes and dry-season elephant concentrations along the river. Tarangire is a strong opening or close to a northern circuit — a different tree line and a different density of elephant than the Serengeti.</p>',
                'why' => '<p>We use it so the circuit has a beginning that is not already “the plains.”</p>',
                'practical' => '<p>Two nights in the dry months is ideal. Walking is available in some concessions.</p>',
            ],
            'zanzibar' => [
                'description' => '<p>Stone Town, spice, and the Indian Ocean after dawn starts and dust. We choose the coast for character — a house, a quieter beach, a town stay — not a generic resort strip that could be anywhere.</p>',
                'why' => '<p>Zanzibar is how a long safari exhales. It is part of Tanzania’s story, not an add-on from a brochure rack.</p>',
                'practical' => '<p>Three to five nights. We time the hop from Arusha or the Serengeti so you do not lose a day in terminals.</p>',
            ],
            default => [],
        };
    }

    public static function stay(string $slug): array
    {
        return match ($slug) {
            'buhoma-forest-lodge' => [
                'description' => '<p>A forest-edge stay on the northern side of Bwindi, chosen for access to the Buhoma sector and a quiet evening after the trek. Rooms look into trees, not a car park. We select it when you want comfort without a resort scale.</p><p>We do not own this lodge. We recommend it because the location and the night after the forest work.</p>',
            ],
            'ishasha-wilderness-camp' => [
                'description' => '<p>A camp in Queen Elizabeth’s southern sector, selected for game and the chance of tree-climbing lions. Nights are closer to the bush than a hotel in the north of the park.</p><p>We do not own this camp. We place you here when Ishasha is the point of the stay, not a name on a map.</p>',
            ],
            'mara-plains-camp' => [
                'description' => '<p>A highly considered Mara camp for travellers who want exclusivity and guiding that can stay with a sighting. Conservancy setting, private vehicles, and a quieter night than the busiest reserve loops.</p><p>We do not own this camp. Availability in migration months is the conversation we start early.</p>',
            ],
            'serengeti-safari-camp' => [
                'description' => '<p>A mobile-feeling stay placed for the season — south for calving, or farther along the herds’ path — rather than a single permanent postcard view.</p><p>We do not own this camp. We choose the month first, then the canvas.</p>',
            ],
            'volcanoes-view-lodge' => [
                'description' => '<p>Highland comfort after a gorilla day near Musanze: fire, a view of the peaks when the cloud lifts, and a short morning transfer to the briefing.</p><p>We do not own this lodge. We select it for character and recovery, not ownership.</p>',
            ],
            default => ['description' => ''],
        };
    }

    public static function journey(string $slug): string
    {
        return match ($slug) {
            '5-day-uganda-gorilla-wildlife' => <<<'HTML'
<p>Five days is enough for a complete Uganda if we do not try to see the whole country. This journey is built around one gorilla morning in Bwindi, then a genuine wildlife chapter in Queen Elizabeth — savannah, the Kazinga Channel, and time to breathe after the forest.</p>
<p>You arrive in Entebbe and travel southwest at a human pace. The trek sits in the middle of the stay, not on the afternoon you land. After the forest we change landscape entirely: open country, a boat, and a selected stay chosen for access rather than a brochure photograph.</p>
<h2>Who this is for</h2>
<p>Travellers with a short window who still want the encounter that defines Uganda, plus animals and water so the trip is not only a single hour in the trees. If you have two extra nights, we would rather add Kibale or a rest day than squeeze in Murchison.</p>
<h2>How it feels</h2>
<p>Private vehicle, a guide who knows both the forest briefing and the channel, and lodges we select — we do not own them. Permits must be secured early in peak months. Tell us about knees and pace so we match the Bwindi sector honestly.</p>
HTML,
            'uganda-classic' => <<<'HTML'
<p>This is the Uganda we recommend when you have a little more time: gorillas in Bwindi, chimpanzees in Kibale, and the Nile at Murchison Falls. Three landscapes, one country, paced so each chapter still feels distinct.</p>
<p>The southwest is forest and altitude. Kibale is warmer, closer, and vocal. Murchison is river, plains, and the geological spectacle of the falls. We will not pretend you can do all three as day trips. Nights are placed so you recover between walks and drives.</p>
<h2>Why these three</h2>
<p>Together they tell a fuller Uganda story than gorillas alone: primates plus the scale of the north. If you care more about water than chimps, we can swap Kibale for a longer Queen Elizabeth chapter. If birds lead, we will say so in the proposal.</p>
<h2>Practical shape</h2>
<p>Eight days is the honest minimum. Gorilla permits book ahead. The road to Murchison is long; we break it or fly when it earns the cost. International flights are timed so you are not running through Entebbe on the morning after a game drive.</p>
HTML,
            'rwanda-highland-gorilla' => <<<'HTML'
<p>Rwanda is compact and carefully run. This journey is a refined gorilla chapter in Volcanoes National Park: excellent briefing, highland air, and a lodge selected for the morning transfer — not because we own it.</p>
<p>Kigali is an easy arrival. You travel into the volcanoes, rest, trek, then take a second forest day if you want one: golden monkeys, the Dian Fossey hike, or simply the view. We do not stack activities onto the afternoon you have just walked for hours.</p>
<h2>Who this is for</h2>
<p>Travellers who want gorilla trekking with short logistics and a quieter, more polished highland setting. Easy to reverse if your flights prefer Kigali last, or to extend into Akagera for plains and water, or across the border into Bwindi.</p>
<p>Permits are a significant part of the investment and should be booked well ahead. Nights are cold. Two or three nights lets the trek sit in the middle of the stay.</p>
HTML,
            'kenya-mara-migration' => <<<'HTML'
<p>The Mara at the right time of year, with a private vehicle and guiding that will stay with a sighting instead of collecting a list. We choose a camp for location and access — often a conservancy edge — and we leave room in the day for the unexpected.</p>
<p>July to October is the classic river period. Wildlife is strong year-round. We will not promise a crossing on day one. We will put you in the right grass, with time, and a guide who knows where the cats have been.</p>
<h2>How the days work</h2>
<p>Dawn, a proper rest, last light. Optional balloon if you want the air. Three or four full safari days is the honest stay; six days including travel is the shape we publish so you are not flying in and out on the same emotional beat.</p>
<p>Tell us if photography leads, if you want fewer vehicles, or if this is a first safari after gorilla country. Conservancy versus reserve is a conversation we have before you choose, not a surprise on the ground.</p>
HTML,
            'tanzania-northern-circuit' => <<<'HTML'
<p>Tarangire, Ngorongoro, and the Serengeti remain one of the great safari sequences — and the risk is treating them like a race. We keep nights complementary: baobabs and elephant, a highlight day on the crater floor, then enough Serengeti that the first plains morning is not your only chance.</p>
<p>Where you stay in the Serengeti depends on the month. Calving in the south, the western corridor, or the north when the herds move. We place the camp for the season, not a permanent postcard kopje. A light-aircraft hop can spare you a road day when it earns the cost.</p>
<h2>The humane shape</h2>
<p>Eight days is the useful minimum for the three parks. Zanzibar can begin the same afternoon you leave the bush if we have already built the hop. Crater fees and vehicle rules change; we confirm the current picture in your proposal.</p>
<p>This is a from-price starting point. Photography, children, or a tighter window will change the nights — we would rather rewrite the outline than rush the crater.</p>
HTML,
            'uganda-rwanda-gorilla-crossing' => <<<'HTML'
<p>Two forests, two permit systems, one private itinerary. You trek in Bwindi and in Volcanoes — not because more is always better, but because some travellers want the comparison: Uganda’s steep cathedral of green, then Rwanda’s highland briefing and a different family.</p>
<p>The crossing is planned, not improvised. Immigration, the southwest road, and a rest day before the second permit so you are not trekking on the afternoon you crossed a border. We can reverse the order if your flights prefer Kigali first.</p>
<h2>Be honest with us</h2>
<p>Two gorilla hours is a significant investment and a physical week. If you would rather one exceptional trek and a wildlife chapter, we will say so. If both forests are the story you want, we will protect the rest days that make the second hour a pleasure.</p>
<p>This journey is tailored. Permits, sectors, and lodges are confirmed in a private proposal — we do not own the stays we select.</p>
HTML,
            'uganda-to-kenya' => <<<'HTML'
<p>Forests, then the Mara: gorillas and chimpanzees in Uganda, then open plains in Kenya. The point is the change of light — canopy to grassland — in one private journey with a connection we protect, not a stressful airport day.</p>
<p>Uganda is home. We begin there, walk the forests properly, then hop to Nairobi and the conservancy. Ten days is the honest length. Shorter versions drop Kibale or a Mara night; we will not drop rest.</p>
<h2>Two countries, one team</h2>
<p>Guiding, vehicles, and selected stays on both sides. Tell us whether the Mara should be migration-minded or a quieter conservancy chapter, and whether photography or a first safari should set the pace in Kenya.</p>
<p>International arrivals can start in either country. We write the proposal around your actual flights.</p>
HTML,
            'pure-pulse-wildlife-wellness' => <<<'HTML'
<p>Pure Pulse is our name for a slower safari: fewer transfers, water, and space between wildlife days. This outline pairs a gentle Uganda chapter — Lake Mburo, walking, a boat — with the Indian Ocean in Zanzibar so the journey exhales on purpose.</p>
<p>It is not a spa brochure pretending to be a safari. You still see zebra, lakeshore birds, and warm water. You do not pack every morning. If you want a single Queen Elizabeth boat in the middle, we can add it without turning the week into a circuit.</p>
<h2>Who this is for</h2>
<p>Travellers who have already done the hard parks, or who want Africa to restore as well as impress. Families who need a softer first chapter. Anyone who knows that luxury, for us, is time.</p>
<p>This journey is by proposal. We choose the coast for character, not a generic strip, and we time the hop so you do not lose a day in terminals.</p>
HTML,
            default => '<p>This itinerary is a starting point, not a brochure you must obey. We reshape days, stays, and transfers around how you want to move — privately, with time, and with guides who know the ground.</p>',
        };
    }

    public static function articleBody(string $excerpt): string
    {
        return <<<HTML
<p>{$excerpt}</p>
<p>We answer this kind of question every week from Kampala, not from a call centre. The useful reply depends on your dates, fitness, and whether you are combining countries. A generic “June to September” is a start, not a plan.</p>
<h2>What we will ask you</h2>
<p>When you can travel, who you are travelling with, and what you hope the days will feel like. From there we can say which park, which permit window, and whether an extra night is worth more than another activity.</p>
<p>Pearl Pulse is based in Uganda. We plan from the ground. If a brochure answer would waste your time, we would rather write a shorter, more precise note.</p>
HTML;
    }
}
