<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SeedImage
{
    public static function catalog(): array
    {
        $u = fn (string $id) => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w=1800&q=80";
        $p = fn (string $id) => "https://images.pexels.com/photos/{$id}/pexels-photo-{$id}.jpeg?auto=compress&cs=tinysrgb&w=1800";

        $jeep = $u('photo-1516426122078-c23e76319801');
        $acacia = $u('photo-1547471080-7cc2caa01a7e');
        $savannah = $u('photo-1516026672322-bc52d61a55d5');
        $kilimanjaro = $u('photo-1489392191049-fc10c97e64b6');
        $elephantsDusk = $u('photo-1564760055775-d63b17a55c44');
        $elephantForest = $u('photo-1549366021-9f761d450615');
        $cheetah = $u('photo-1551969014-7d2c4cddf0b6');
        $herd = $p('631317');
        $zebras = $p('247376');
        $giraffe = $p('802112');
        $calf = $p('4577793');
        $forest = $p('975771');
        $reef = $p('1320684');
        $waterhole = $p('1054655');
        $plainsElephant = $p('667205');
        $highlands = $p('1183099');
        $guide = $p('1670732');

        return [
            'hero' => $jeep,
            'uganda' => $forest,
            'rwanda' => $highlands,
            'kenya' => $acacia,
            'tanzania' => $kilimanjaro,
            'bwindi-impenetrable-forest' => $forest,
            'kibale-forest' => $forest,
            'queen-elizabeth-national-park' => $herd,
            'murchison-falls' => $giraffe,
            'lake-mburo' => $zebras,
            'volcanoes-national-park' => $highlands,
            'akagera-national-park' => $elephantsDusk,
            'masai-mara' => $jeep,
            'amboseli' => $kilimanjaro,
            'samburu' => $savannah,
            'serengeti' => $savannah,
            'ngorongoro' => $highlands,
            'tarangire' => $plainsElephant,
            'zanzibar' => $reef,
            'gorilla-trekking' => $forest,
            'big-five-safari' => $cheetah,
            'chimpanzee-tracking' => $forest,
            'birding-shoebill' => $waterhole,
            'wildlife-photography' => $cheetah,
            'culture-community' => $guide,
            'adventure' => $jeep,
            'pure-pulse-wellness' => $reef,
            'boat-water-experiences' => $reef,
            '5-day-uganda-gorilla-wildlife' => $forest,
            'uganda-classic' => $giraffe,
            'rwanda-highland-gorilla' => $highlands,
            'kenya-mara-migration' => $jeep,
            'tanzania-northern-circuit' => $kilimanjaro,
            'uganda-rwanda-gorilla-crossing' => $forest,
            'uganda-to-kenya' => $acacia,
            'pure-pulse-wildlife-wellness' => $reef,
            'buhoma-forest-lodge' => $forest,
            'ishasha-wilderness-camp' => $herd,
            'mara-plains-camp' => $jeep,
            'serengeti-safari-camp' => $plainsElephant,
            'volcanoes-view-lodge' => $highlands,
            'team-amina-n' => $guide,
            'team-joseph-k' => $guide,
            'team-grace-m' => $guide,
            'pulse-morning-in-bwindi' => $forest,
            'pulse-kazinga-at-dusk' => $waterhole,
            'pulse-mara-crossing' => $jeep,
            'article' => $jeep,
            'elephant-forest' => $elephantForest,
            'elephant-calf' => $calf,
        ];
    }

    public static function photo(string $key, string $path, int $width = 1600, int $height = 1067, bool $force = true): string
    {
        $url = self::catalog()[$key] ?? self::catalog()['hero'];

        return self::fromUrl($path, $url, $width, $height, $force);
    }

    public static function fromUrl(string $path, string $url, int $width = 1600, int $height = 1067, bool $force = true): string
    {
        $disk = Storage::disk('public');

        if (! $force && $disk->exists($path) && $disk->size($path) > 80000) {
            return $path;
        }

        try {
            $response = Http::timeout(35)
                ->withHeaders(['User-Agent' => 'PearlPulseSeeder/1.0'])
                ->get($url);

            $body = $response->body();

            if ($response->successful() && strlen($body) > 20000 && str_starts_with($body, "\xFF\xD8")) {
                $disk->put($path, $body);

                return $path;
            }
        } catch (\Throwable) {
            // Fall through to a quiet gradient so seeders still complete offline.
        }

        if ($disk->exists($path) && $disk->size($path) > 80000) {
            return $path;
        }

        return self::gradient($path, $width, $height);
    }

    public static function gradient(string $path, int $width = 1400, int $height = 1600): string
    {
        $disk = Storage::disk('public');

        $img = imagecreatetruecolor($width, $height);
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $color = imagecolorallocate(
                $img,
                (int) (40 + 40 * $ratio),
                (int) (55 + 30 * $ratio),
                (int) (35 + 20 * $ratio)
            );
            imageline($img, 0, $y, $width, $y, $color);
        }
        ob_start();
        imagejpeg($img, null, 85);
        $binary = ob_get_clean();
        imagedestroy($img);
        $disk->put($path, $binary);

        return $path;
    }
}
