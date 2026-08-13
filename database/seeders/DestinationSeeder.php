<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Services\ImageUploader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $uploader = app(ImageUploader::class);

        $items = [
            [
                'name' => 'Bwindi Impenetrable Forest',
                'subtitle' => 'Mountain gorilla country',
                'country' => 'Uganda',
                'region' => 'Southwest',
                'teaser' => 'Misty montane forest and the world’s most intimate gorilla encounters.',
                'duration' => '2–3 nights',
                'best_time' => 'June–August & December–February',
                'activities' => 'Gorilla trekking, nature walks, community visits',
                'price_from' => 'From $1,800 pp',
                'description' => '<p>Bwindi is a cathedral of green — steep slopes draped in ancient forest, home to nearly half of the world’s remaining mountain gorillas. Treks are humbling, physical, and unforgettable.</p><p>Stay in lodges perched above the canopy, then descend into the forest with expert trackers for a rare hour among a habituated family.</p>',
                'highlights' => [
                    ['label' => 'Altitude', 'value' => '1,160–2,607 m'],
                    ['label' => 'Permit tip', 'value' => 'Book gorilla permits months ahead'],
                ],
                'is_featured' => true,
                'sort_order' => 1,
                'photo' => 'bwindi.jpg',
            ],
            [
                'name' => 'Murchison Falls',
                'subtitle' => 'Where the Nile thunders',
                'country' => 'Uganda',
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
                'sort_order' => 2,
                'photo' => 'murchison.jpg',
            ],
            [
                'name' => 'Masai Mara',
                'subtitle' => 'Migration plains of Kenya',
                'country' => 'Kenya',
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
                'sort_order' => 3,
                'photo' => 'masai-mara.jpg',
            ],
            [
                'name' => 'Serengeti',
                'subtitle' => 'The endless plain',
                'country' => 'Tanzania',
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
                'sort_order' => 4,
                'photo' => 'serengeti.jpg',
            ],
            [
                'name' => 'Volcanoes National Park',
                'subtitle' => 'Rwanda’s highland wilderness',
                'country' => 'Rwanda',
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
                'is_featured' => false,
                'sort_order' => 5,
                'photo' => 'volcanoes.jpg',
            ],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['name']);
            $source = storage_path('app/seed-downloads/'.$item['photo']);

            $destination = Destination::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'subtitle' => $item['subtitle'],
                    'country' => $item['country'],
                    'region' => $item['region'],
                    'teaser' => $item['teaser'],
                    'duration' => $item['duration'],
                    'best_time' => $item['best_time'],
                    'activities' => $item['activities'],
                    'price_from' => $item['price_from'],
                    'description' => $item['description'],
                    'highlights' => $item['highlights'],
                    'meta_title' => $item['name'].' | Pearl Pulse Safaris',
                    'meta_description' => $item['teaser'],
                    'is_featured' => $item['is_featured'],
                    'status' => 'published',
                    'sort_order' => $item['sort_order'],
                ]
            );

            if (is_file($source)) {
                if ($destination->cover_path) {
                    $uploader->delete($destination->cover_path);
                }
                $path = $uploader->storeFromPath($source, 'destinations/'.$destination->id);
                $destination->update(['cover_path' => $path]);
            } elseif (! $destination->cover_path) {
                $destination->update([
                    'cover_path' => $this->makeFallbackCover($slug),
                ]);
            }
        }
    }

    protected function makeFallbackCover(string $slug): string
    {
        $path = "seed/{$slug}.jpg";
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $img = imagecreatetruecolor(1200, 1500);
            for ($y = 0; $y < 1500; $y++) {
                $ratio = $y / 1500;
                $color = imagecolorallocate($img, (int) (40 + 40 * $ratio), (int) (55 + 30 * $ratio), (int) (35 + 20 * $ratio));
                imageline($img, 0, $y, 1200, $y, $color);
            }
            ob_start();
            imagejpeg($img, null, 85);
            $binary = ob_get_clean();
            imagedestroy($img);
            $disk->put($path, $binary);
        }

        return $path;
    }
}
