<?php
/**
 * Genera la web estàtica a dist/ a partir de les plantilles PHP de public/ i del Google Sheet.
 *
 *   php build.php                     → build local (fa servir la memòria cau / CSV locals si cal)
 *   BUILD_STRICT=1 php build.php      → build de publicació: si Google falla, s'atura amb error
 *   BASE_PATH=/agnesaran php build.php → per publicar a xaviroses.github.io/agnesaran sense domini
 *   CNAME=agnesaran.com php build.php → escriu el fitxer CNAME per al domini de GitHub Pages
 */

$root = __DIR__;
$src = "$root/public";
$dist = "$root/dist";

$pages = [
    'index.php'              => 'index.html',
    'lactancia/index.php'    => 'lactancia/index.html',
    'lactancia/es/index.php' => 'lactancia/es/index.html',
    'lactancia/en/index.php' => 'lactancia/en/index.html',
    'cuina/index.php'        => 'cuina/index.html',
    'veu/index.php'          => 'veu/index.html',
];

// Neteja dist/
if (is_dir($dist)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dist, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $f) {
        $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname());
    }
}
@mkdir($dist, 0755, true);

// Pàgines: cada una en un procés PHP propi (com si fos una visita)
foreach ($pages as $in => $out) {
    $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg("$src/$in");
    $html = [];
    exec($cmd, $html, $code);
    if ($code !== 0) {
        fwrite(STDERR, "ERROR generant $in (codi $code)\n");
        exit(1);
    }
    $html = implode("\n", $html) . "\n";
    if (strlen($html) < 500 || stripos($html, 'Fatal error') !== false || stripos($html, 'Warning:') !== false) {
        fwrite(STDERR, "ERROR: la sortida de $in sembla incorrecta\n");
        exit(1);
    }
    @mkdir(dirname("$dist/$out"), 0755, true);
    file_put_contents("$dist/$out", $html);
    echo "✓ $out\n";
}

// Recursos estàtics
$assets = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$src/assets", FilesystemIterator::SKIP_DOTS));
$n = 0;
foreach ($assets as $f) {
    if ($f->getFilename() === '.DS_Store') {
        continue;
    }
    $rel = substr($f->getPathname(), strlen($src) + 1);
    @mkdir(dirname("$dist/$rel"), 0755, true);
    copy($f->getPathname(), "$dist/$rel");
    $n++;
}
foreach (['favicon.ico', 'favicon-16x16.png', 'favicon-32x32.png', 'apple-touch-icon.png', 'android-chrome-192x192.png', 'android-chrome-512x512.png', 'site.webmanifest'] as $f) {
    copy("$src/$f", "$dist/$f");
    $n++;
}
echo "✓ $n fitxers d'assets i icones\n";

// Fitxers per a GitHub Pages
touch("$dist/.nojekyll");
if ($cname = getenv('CNAME')) {
    file_put_contents("$dist/CNAME", trim($cname) . "\n");
    echo "✓ CNAME $cname\n";
}

$cfg = require "$src/inc/config.php";
$site = rtrim($cfg['site_url'], '/');
file_put_contents("$dist/robots.txt", "User-agent: *\nAllow: /\nSitemap: $site/sitemap.xml\n");
$urls = ['/', '/lactancia/', '/lactancia/es/', '/lactancia/en/', '/cuina/', '/veu/'];
$today = date('Y-m-d');
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $u) {
    $xml .= "  <url><loc>$site$u</loc><lastmod>$today</lastmod></url>\n";
}
file_put_contents("$dist/sitemap.xml", $xml . "</urlset>\n");

$base = rtrim((string) getenv('BASE_PATH'), '/');
file_put_contents("$dist/404.html", <<<HTML
<!doctype html>
<html lang="ca"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pàgina no trobada · Agnès Aran</title><meta name="robots" content="noindex">
<style>html,body{margin:0;height:100%;background:#F3EADB;color:#1E1A16;font-family:system-ui,sans-serif}
main{min-height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1.5rem;padding:24px;text-align:center}
h1{margin:0;font-size:clamp(2rem,6vw,4rem)}a{color:#1E1A16;font-weight:600}</style></head>
<body><main><h1>Aquesta pàgina no existeix</h1><p><a href="$base/">Torna a l'inici</a></p></main></body></html>

HTML);
echo "✓ robots.txt, sitemap.xml, 404.html\n";
echo "Fet: $dist\n";
