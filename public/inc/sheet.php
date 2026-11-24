<?php
/**
 * Lectura de les dades editables (Google Sheet o CSV locals).
 *
 * Ordre de preferència per a cada pestanya:
 *   1. còpia en memòria cau (/cache) si té menys de cache_ttl segons
 *   2. Google Sheet (exportació CSV de la pestanya)
 *   3. còpia en memòria cau caducada (si Google no respon)
 *   4. CSV de /data (contingut inicial / mode local)
 */

function site_config(): array
{
    static $cfg = null;
    return $cfg ??= require __DIR__ . '/config.php';
}

/**
 * Prefix de les rutes internes. Buit amb domini propi; "/agnesaran" si es publica
 * a xaviroses.github.io/agnesaran abans de tenir el domini. Ve de la variable d'entorn BASE_PATH.
 */
if (!defined('BASE')) {
    define('BASE', rtrim((string) getenv('BASE_PATH'), '/'));
}

/**
 * Mentre la web no estigui a agnesaran.com (proves en local o en un subdomini de staging),
 * demanem als cercadors que no l'indexin.
 */
if (PHP_SAPI !== 'cli' && !headers_sent()) {
    $host = strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
    $prod = parse_url(site_config()['site_url'], PHP_URL_HOST);
    if ($host !== $prod && $host !== "www.$prod") {
        header('X-Robots-Tag: noindex, nofollow');
    }
}

function sheet_rows(string $tab): array
{
    static $memo = [];
    if (isset($memo[$tab])) {
        return $memo[$tab];
    }
    return $memo[$tab] = array_values(array_filter(
        csv_to_rows(sheet_csv($tab)),
        fn($r) => !in_array(norm_key($r['visible'] ?? 'si'), ['no', 'n', '0', 'false'], true)
    ));
}

function sheet_csv(string $tab): string
{
    $cfg = site_config();
    $root = dirname(__DIR__);
    $local = "$root/data/$tab.csv";
    $id = trim($cfg['sheet_id'] ?? '');
    $gid = trim($cfg['gid'][$tab] ?? '');

    if ($id === '' || $gid === '') {
        return is_file($local) ? file_get_contents($local) : '';
    }

    $url = 'https://docs.google.com/spreadsheets/d/' . rawurlencode($id)
         . '/export?format=csv&gid=' . rawurlencode($gid);

    // Build estàtic (GitHub Actions): sempre del full, i si Google falla, aturem el build
    // perquè no es publiqui una web amb dades velles o buides.
    if (getenv('BUILD_STRICT')) {
        $csv = http_get($url);
        if ($csv === null || $csv === '' || stripos(ltrim($csv), '<') === 0) {
            fwrite(STDERR, "ERROR: no s'ha pogut llegir la pestanya '$tab' del Google Sheet.\n");
            exit(1);
        }
        return $csv;
    }

    $cacheDir = "$root/cache";
    $cache = "$cacheDir/$tab.csv";
    $fresh = is_file($cache) && (time() - filemtime($cache)) < (int) $cfg['cache_ttl'];
    if ($fresh && !isset($_GET['refresh'])) {
        return file_get_contents($cache);
    }

    $csv = http_get($url);

    // Si el full no és públic, Google retorna una pàgina HTML d'inici de sessió.
    if ($csv !== null && $csv !== '' && stripos(ltrim($csv), '<') !== 0) {
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        @file_put_contents($cache, $csv, LOCK_EX);
        return $csv;
    }
    if (is_file($cache)) {
        return file_get_contents($cache);
    }
    return is_file($local) ? file_get_contents($local) : '';
}

function http_get(string $url): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 5,
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return ($body !== false && $code === 200) ? $body : null;
    }
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $body = @file_get_contents($url, false, $ctx);
    return $body === false ? null : $body;
}

/** Converteix un CSV amb capçalera en una llista de files associatives (claus normalitzades). */
function csv_to_rows(string $csv): array
{
    $csv = preg_replace('/^\xEF\xBB\xBF/', '', $csv);
    $fh = fopen('php://temp', 'r+');
    fwrite($fh, $csv);
    rewind($fh);
    $head = null;
    $rows = [];
    while (($r = fgetcsv($fh, 0, ',', '"', '')) !== false) {
        if ($head === null) {
            $head = array_map('norm_key', $r);
            continue;
        }
        if (implode('', array_map('trim', $r)) === '') {
            continue; // fila buida
        }
        $row = [];
        foreach ($head as $i => $k) {
            if ($k !== '') {
                $row[$k] = trim($r[$i] ?? '');
            }
        }
        $rows[] = $row;
    }
    fclose($fh);
    return $rows;
}

/** "Títol CA" → "titol_ca": minúscules, sense accents, espais a guions baixos. */
function norm_key(string $s): string
{
    $s = mb_strtolower(trim($s));
    $s = strtr($s, ['à' => 'a', 'á' => 'a', 'è' => 'e', 'é' => 'e', 'í' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', '·' => '', 'ŀ' => 'l']);
    return trim(preg_replace('/[^a-z0-9]+/', '_', $s), '_');
}

/** Agafa el valor en l'idioma demanat; si és buit, fa servir el català. */
function tr(array $row, string $field, string $lang): string
{
    $v = $row["{$field}_{$lang}"] ?? '';
    return $v !== '' ? $v : ($row["{$field}_ca"] ?? '');
}

/**
 * Agrupa files consecutives: una fila amb grup_ca omplert obre un grup nou;
 * les següents amb el grup buit hi pertanyen.
 */
function group_rows(array $rows, string $lang): array
{
    $groups = [];
    $i = -1;
    foreach ($rows as $r) {
        if (($r['grup_ca'] ?? '') !== '' || $i < 0) {
            $groups[++$i] = ['title' => tr($r, 'grup', $lang), 'items' => []];
        }
        $groups[$i]['items'][] = $r;
    }
    return $groups;
}

/**
 * Format de text simple per al full:
 *   **negreta**   [text de l'enllaç](https://...)   salts de línia dins la cel·la
 */
function fmt(string $s, string $boldClass = ''): string
{
    $s = htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    $b = $boldClass !== '' ? '<b class="' . $boldClass . '">' : '<b>';
    $s = preg_replace('/\*\*(.+?)\*\*/u', $b . '$1</b>', $s);
    $s = preg_replace_callback('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/u', function ($m) {
        return '<a href="' . $m[2] . '" target="_blank" rel="noopener">' . $m[1] . '</a>';
    }, $s);
    return nl2br($s, false);
}

/** "70" → "70€"; "70€" o "a consultar" es deixen igual. */
function money(string $s): string
{
    return preg_match('/^\d+([.,]\d+)?$/', $s) ? $s . '€' : $s;
}

/** Accepta 31/01/2027, 31-1-2027 o 2027-01-31. */
function parse_date(string $s): ?DateTimeImmutable
{
    $s = trim($s);
    if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $s, $m)) {
        [$y, $mo, $d] = [$m[1], $m[2], $m[3]];
    } elseif (preg_match('/^(\d{1,2})[\/.-](\d{1,2})[\/.-](\d{2,4})$/', $s, $m)) {
        [$d, $mo, $y] = [$m[1], $m[2], strlen($m[3]) === 2 ? '20' . $m[3] : $m[3]];
    } else {
        return null;
    }
    return checkdate((int) $mo, (int) $d, (int) $y)
        ? new DateTimeImmutable(sprintf('%04d-%02d-%02d', $y, $mo, $d))
        : null;
}

function format_date(DateTimeImmutable $d, string $lang): string
{
    $m = (int) $d->format('n');
    $months = [
        'ca' => ['gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre'],
        'es' => ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
        'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    ];
    $name = $months[$lang][$m - 1];
    $day = $d->format('j');
    return match ($lang) {
        'en' => "$day $name {$d->format('Y')}",
        'es' => "$day de $name de {$d->format('Y')}",
        default => $day . (preg_match('/^[aeiou]/', $name) ? " d'" : ' de ') . $name . ' de ' . $d->format('Y'),
    };
}

function wa_link(string $text = ''): string
{
    $url = 'https://wa.me/' . site_config()['whatsapp'];
    return $text === '' ? $url : $url . '?text=' . rawurlencode($text);
}

function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
