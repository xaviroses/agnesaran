<?php
/**
 * Miniatures de YouTube: les "hqdefault" (480×360) porten franges (negres o de color) que
 * varien segons el vídeo. Es descarrega cada miniatura, es detecten les vores uniformes i
 * es retorna el rectangle amb contingut [esquerra, dalt, dreta, baix] en píxels.
 * Es desa a cache/yt-crop.json perquè no calgui tornar-ho a calcular.
 */
const YT_DEFAULT_CROP = [0, 45, 480, 315]; // 16:9 dins del 4:3: el cas més habitual

function yt_crop(string $id): array
{
    static $cache = null;
    $file = dirname(__DIR__) . '/cache/yt-crop.json';
    if ($cache === null) {
        $cache = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    if (isset($cache[$id])) {
        return $cache[$id];
    }
    $crop = YT_DEFAULT_CROP;
    $jpg = function_exists('imagecreatefromstring') ? http_get("https://i.ytimg.com/vi/$id/hqdefault.jpg") : null;
    $im = $jpg ? @imagecreatefromstring($jpg) : false;
    if (!$im) {
        return $crop; // sense xarxa o sense GD: no es desa, es tornarà a provar
    }
    $crop = yt_content_box($im);
    $cache[$id] = $crop;
    @mkdir(dirname($file), 0755, true);
    @file_put_contents($file, json_encode($cache), LOCK_EX);
    return $crop;
}

function yt_content_box($im): array
{
    $w = imagesx($im);
    $h = imagesy($im);
    $px = function ($x, $y) use ($im) {
        $c = imagecolorat($im, $x, $y);
        return [($c >> 16) & 255, ($c >> 8) & 255, $c & 255];
    };
    // Una línia és "franja" si tots els seus píxels són gairebé del mateix color
    $uniform = function (callable $get, int $from, int $to): bool {
        $vals = [];
        for ($i = $from; $i < $to; $i += 2) {
            $vals[] = $get($i);
        }
        $avg = [0, 0, 0];
        foreach ($vals as $c) {
            for ($k = 0; $k < 3; $k++) {
                $avg[$k] += $c[$k] / count($vals);
            }
        }
        foreach ($vals as $c) {
            if (abs($c[0] - $avg[0]) + abs($c[1] - $avg[1]) + abs($c[2] - $avg[2]) >= 40) {
                return false;
            }
        }
        return true;
    };
    // Passades successives: si hi ha franges negres a dalt i grises als costats, primer
    // es treuen unes i després les altres (comprovant només la zona que queda)
    [$l, $t, $r, $b] = [0, 0, $w - 1, $h - 1];
    do {
        $prev = [$l, $t, $r, $b];
        while ($t < $h / 2 && $uniform(fn($i) => $px($i, $t), $l, $r + 1)) $t++;
        while ($b > $h / 2 && $uniform(fn($i) => $px($i, $b), $l, $r + 1)) $b--;
        while ($l < $w / 2 && $uniform(fn($i) => $px($l, $i), $t, $b + 1)) $l++;
        while ($r > $w / 2 && $uniform(fn($i) => $px($r, $i), $t, $b + 1)) $r--;
    } while ($prev !== [$l, $t, $r, $b]);
    $box = [$l, $t, $r + 1, $b + 1];
    // Si queda massa petit (imatge gairebé llisa), millor no retallar-la tant
    if (($box[2] - $box[0]) * ($box[3] - $box[1]) < 0.35 * $w * $h) {
        return YT_DEFAULT_CROP;
    }
    return $box;
}
