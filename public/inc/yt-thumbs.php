<?php
/**
 * Miniatures de YouTube. Totes porten franges (negres o de color) que varien segons el vídeo.
 * Per a cada vídeo es descarreguen la miniatura petita (hqdefault, 480×360) i, si existeix,
 * una de gran (maxresdefault 1280×720 o sddefault 640×480); es detecten les vores uniformes
 * i es retorna, per a cada mida: [url, amplada, alçada, [esquerra, dalt, dreta, baix]].
 * La pàgina tria la petita o la gran segons la mida de la casella.
 * Es desa a cache/yt-thumbs.json perquè no calgui tornar-ho a calcular.
 */

function yt_thumbs(string $id): array
{
    static $cache = null;
    $file = dirname(__DIR__) . '/cache/yt-thumbs.json';
    if ($cache === null) {
        $cache = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    if (isset($cache[$id])) {
        return $cache[$id];
    }
    $out = [];
    $ok = true;
    foreach ([['hqdefault'], ['maxresdefault', 'sddefault']] as $names) {
        foreach ($names as $name) {
            $url = "https://i.ytimg.com/vi/$id/$name.jpg";
            $jpg = function_exists('imagecreatefromstring') ? http_get($url) : null;
            $im = $jpg ? @imagecreatefromstring($jpg) : false;
            if ($im && imagesx($im) > 120) { // 120×90 = "no hi ha miniatura"
                $out[] = [$url, imagesx($im), imagesy($im), yt_content_box($im)];
                break;
            }
            if ($name === 'hqdefault') {
                $ok = false; // sense xarxa o sense GD
            }
        }
    }
    if (!$ok) {
        // No es desa: es tornarà a provar. Mentrestant, la petita amb el retall habitual (16:9 dins del 4:3)
        return [["https://i.ytimg.com/vi/$id/hqdefault.jpg", 480, 360, [0, 45, 480, 315]]];
    }
    $cache[$id] = $out;
    @mkdir(dirname($file), 0755, true);
    @file_put_contents($file, json_encode($cache, JSON_UNESCAPED_SLASHES), LOCK_EX);
    return $out;
}

function yt_content_box($im): array
{
    $w = imagesx($im);
    $h = imagesy($im);
    $px = function ($x, $y) use ($im) {
        $c = imagecolorat($im, $x, $y);
        return [($c >> 16) & 255, ($c >> 8) & 255, $c & 255];
    };
    // Una línia és "franja" si gairebé tots els seus píxels (≥ 90%) són del mateix color:
    // així també es detecten les franges negres amb una línia de subtítols o text petit
    $uniform = function (callable $get, int $from, int $to): bool {
        $buckets = [];
        $n = 0;
        for ($i = $from; $i < $to; $i += 2) {
            [$r, $g, $b] = $get($i);
            $key = ($r >> 5) . '-' . ($g >> 5) . '-' . ($b >> 5);
            $buckets[$key] = ($buckets[$key] ?? 0) + 1;
            $n++;
        }
        return $n > 0 && max($buckets) >= 0.9 * $n;
    };
    // Gruix d'una franja des d'una vora: arriba fins a l'última línia uniforme sempre que
    // la majoria (≥ 75%) de les línies fins allà ho siguin (així no l'atura una línia de text)
    $edge = function (callable $isBar, int $limit): int {
        $best = 0;
        $bars = 0;
        for ($k = 0; $k < $limit; $k++) {
            if ($isBar($k)) {
                $bars++;
                if ($bars >= 0.75 * ($k + 1)) {
                    $best = $k + 1;
                }
            }
        }
        return $best;
    };
    // Passades successives: si hi ha franges negres a dalt i grises als costats, primer
    // es treuen unes i després les altres (comprovant només la zona que queda)
    [$l, $t, $r, $b] = [0, 0, $w - 1, $h - 1];
    $maxH = (int) ($h * 0.28);
    $maxW = (int) ($w * 0.28);
    do {
        $prev = [$l, $t, $r, $b];
        $t += $edge(fn($k) => $uniform(fn($i) => $px($i, $t + $k), $l, $r + 1), max(0, $maxH - $t));
        $b -= $edge(fn($k) => $uniform(fn($i) => $px($i, $b - $k), $l, $r + 1), max(0, $maxH - ($h - 1 - $b)));
        $l += $edge(fn($k) => $uniform(fn($i) => $px($l + $k, $i), $t, $b + 1), max(0, $maxW - $l));
        $r -= $edge(fn($k) => $uniform(fn($i) => $px($r - $k, $i), $t, $b + 1), max(0, $maxW - ($w - 1 - $r)));
    } while ($prev !== [$l, $t, $r, $b]);
    $box = [$l, $t, $r + 1, $b + 1];
    // Si queda massa petit (imatge gairebé llisa), millor no retallar-la tant
    if (($box[2] - $box[0]) * ($box[3] - $box[1]) < 0.35 * $w * $h) {
        return $w * 3 === $h * 4 ? [0, (int) ($h / 8), $w, (int) ($h * 7 / 8)] : [0, 0, $w, $h];
    }
    return $box;
}
