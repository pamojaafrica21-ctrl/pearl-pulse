<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Bwindi Impenetrable Forest',
                'country' => 'Uganda',
                'region' => 'Southwest',
                'teaser' => 'Misty montane forest and the world’s most intimate gorilla encounters.',
                'description' => '<p>Bwindi is a cathedral of green — steep slopes draped in ancient forest, home to nearly half of the world’s remaining mountain gorillas. Treks are humbling, physical, and unforgettable.</p><p>Stay in lodges perched above the canopy, then descend into the forest with expert trackers for a rare hour among a habituated family.</p>',
                'highlights' => [
                    ['label' => 'Best time to visit', 'value' => 'June–August & December–February'],
                    ['label' => 'Activities', 'value' => 'Gorilla trekking, nature walks, community visits'],
                    ['label' => 'Suggested duration', 'value' => '2–3 nights'],
                ],
                'is_featured' => true,
                'sort_order' => 1,
                'colors' => [34, 60, 40],
            ],
            [
                'name' => 'Murchison Falls',
                'country' => 'Uganda',
                'region' => 'Northwest',
                'teaser' => 'The Nile forced through a rocky gorge — thunder, wildlife, and open savannah.',
                'description' => '<p>At Murchison Falls, the Victoria Nile explodes through a seven-metre gap before plunging into the gorge below. Boat safaris, game drives, and the roar of the falls define classic Ugandan safari.</p>',
                'highlights' => [
                    ['label' => 'Best time to visit', 'value' => 'December–February & June–September'],
                    ['label' => 'Activities', 'value' => 'Game drives, Nile boat safari, hike to the falls'],
                    ['label' => 'Suggested duration', 'value' => '2–3 nights'],
                ],
                'is_featured' => true,
                'sort_order' => 2,
                'colors' => [55, 70, 45],
            ],
            [
                'name' => 'Masai Mara',
                'country' => 'Kenya',
                'region' => 'Rift Valley',
                'teaser' => 'Endless plains, big cats, and the drama of the Great Migration.',
                'description' => '<p>The Mara’s golden grasslands are synonymous with East African safari. From July to October, wildebeest and zebra pour across the Mara River; year-round, lion, leopard, and cheetah thrive.</p>',
                'highlights' => [
                    ['label' => 'Best time to visit', 'value' => 'July–October for migration; year-round for wildlife'],
                    ['label' => 'Activities', 'value' => 'Game drives, hot-air ballooning, cultural visits'],
                    ['label' => 'Suggested duration', 'value' => '3–4 nights'],
                ],
                'is_featured' => true,
                'sort_order' => 3,
                'colors' => [140, 110, 55],
            ],
            [
                'name' => 'Serengeti',
                'country' => 'Tanzania',
                'region' => 'Northern Circuit',
                'teaser' => 'The endless plain — migration corridors and quiet corners beyond the crowds.',
                'description' => '<p>Serengeti means “endless plains” in Maasai. Follow the herds across the south in calving season, or seek solitude in the western corridor and north when the migration moves.</p>',
                'highlights' => [
                    ['label' => 'Best time to visit', 'value' => 'December–March (south) & June–October (north/west)'],
                    ['label' => 'Activities', 'value' => 'Game drives, walking safaris, balloon flights'],
                    ['label' => 'Suggested duration', 'value' => '3–5 nights'],
                ],
                'is_featured' => true,
                'sort_order' => 4,
                'colors' => [120, 95, 50],
            ],
            [
                'name' => 'Volcanoes National Park',
                'country' => 'Rwanda',
                'region' => 'Northern Province',
                'teaser' => 'Volcanic peaks, golden monkeys, and mountain gorilla trekking in style.',
                'description' => '<p>Rwanda’s Volcanoes National Park pairs world-class gorilla permits with refined lodges and excellent guiding. Add golden monkey treks and community experiences for a complete highland chapter.</p>',
                'highlights' => [
                    ['label' => 'Best time to visit', 'value' => 'June–September & December–February'],
                    ['label' => 'Activities', 'value' => 'Gorilla & golden monkey trekking, Dian Fossey hike'],
                    ['label' => 'Suggested duration', 'value' => '2–3 nights'],
                ],
                'is_featured' => false,
                'sort_order' => 5,
                'colors' => [45, 55, 70],
            ],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['name']);
            $cover = $this->makeCover($slug, $item['colors']);

            Destination::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'country' => $item['country'],
                    'region' => $item['region'],
                    'teaser' => $item['teaser'],
                    'description' => $item['description'],
                    'highlights' => $item['highlights'],
                    'cover_path' => $cover,
                    'meta_title' => $item['name'].' | Pearl Pulse Safaris',
                    'meta_description' => $item['teaser'],
                    'is_featured' => $item['is_featured'],
                    'status' => 'published',
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }

    /**
     * @param  array{0:int,1:int,2:int}  $rgb
     */
    protected function makeCover(string $slug, array $rgb): string
    {
        $path = "seed/{$slug}.jpg";
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $img = imagecreatetruecolor(1200, 1500);
            [$r, $g, $b] = $rgb;
            for ($y = 0; $y < 1500; $y++) {
                $ratio = $y / 1500;
                $color = imagecolorallocate(
                    $img,
                    (int) ($r * (1 - 0.35 * $ratio)),
                    (int) ($g * (1 - 0.25 * $ratio)),
                    (int) ($b * (1 - 0.2 * $ratio))
                );
                imageline($img, 0, $y, 1200, $y, $color);
            }
            $textColor = imagecolorallocate($img, 232, 223, 208);
            imagestring($img, 5, 40, 1400, strtoupper(str_replace('-', ' ', $slug)), $textColor);
            ob_start();
            imagejpeg($img, null, 85);
            $binary = ob_get_clean();
            imagedestroy($img);
            $disk->put($path, $binary);

            // thumb companion for ImageUploader convention
            $thumb = imagecreatetruecolor(480, 600);
            $src = imagecreatefromstring($binary);
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, 480, 600, 1200, 1500);
            ob_start();
            imagejpeg($thumb, null, 80);
            $thumbBinary = ob_get_clean();
            imagedestroy($thumb);
            imagedestroy($src);
            $disk->put("seed/{$slug}_thumb.jpg", $thumbBinary);
        }

        return $path;
    }
}
