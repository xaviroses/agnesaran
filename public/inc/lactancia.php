<?php
/** Plantilla de /lactancia. Cal definir $lang ('ca' | 'es' | 'en') abans d'incloure-la. */
require_once __DIR__ . '/sheet.php';

$lang = $lang ?? 'ca';
$T = (require __DIR__ . '/lactancia-i18n.php')[$lang];
$cfg = site_config();
$paths = ['ca' => '/lactancia/', 'es' => '/lactancia/es/', 'en' => '/lactancia/en/'];
$img = BASE . '/assets/img/lactancia/';
$wa = wa_link();
$ids = $T['ids'];

// ---- Tallers: amaga els que ja han passat ----
$today = new DateTimeImmutable('today');
$tallers = [];
foreach (sheet_rows('tallers') as $r) {
    $d = parse_date($r['data'] ?? '');
    if ($d && $d < $today) {
        continue;
    }
    $r['_date'] = tr($r, 'data_text', $lang) ?: ($d ? format_date($d, $lang) : '');
    $tallers[] = $r;
}
$preus = group_rows(sheet_rows('preus'), $lang);
$formacio = group_rows(sheet_rows('formacio'), $lang);

$mapsLink = function (string $q): string {
    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($q);
};
$ext = fn(string $href, string $text) => '<a href="' . e($href) . '" target="_blank" rel="noopener">' . e($text) . '</a>';
$svcLinks = [
    '{mandri}' => $ext('https://www.farmaciamandri54.cat/', 'Farmàcia Mandri 54'),
    '{creix}' => $ext('https://xn--creixambtraa-udb.com/', 'Creix amb Traça'),
];
$strip = fn(array $files, array $alts) => array_map(null, $files, $alts);
?><!doctype html>
<html lang="<?= $lang ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include __DIR__ . '/head-extra.php'; ?>
<title><?= e($T['title']) ?></title>
<meta name="description" content="<?= e($T['description']) ?>">
<link rel="canonical" href="<?= e($cfg['site_url'] . $paths[$lang]) ?>">
<?php foreach ($paths as $l => $p): ?>
<link rel="alternate" hreflang="<?= $l ?>" href="<?= e($cfg['site_url'] . $p) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= e($cfg['site_url'] . $paths['ca']) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($T['title']) ?>">
<meta property="og:description" content="<?= e($T['description']) ?>">
<meta property="og:image" content="<?= e($cfg['site_url'] . '/assets/img/lactancia/' . '6eb65e67cded5ca5abdf9825535aa54d.jpg') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600&amp;display=swap">
<link rel="stylesheet" href="<?= BASE ?>/assets/css/lactancia.css">
</head>
<body>
<div class="page">

<header class="top">
  <div class="top-in">
    <div class="brand-wrap">
      <a class="brand" href="<?= BASE ?>/">Agnès Aran</a>
      <img class="logo" src="<?= $img ?>4391242454c9a4c82a559eb00628f506.png" alt="" width="48" height="72">
    </div>
    <nav class="langs" aria-label="<?= e($T['nav_lang']) ?>">
      <?php foreach ($paths as $l => $p): if ($l === $lang) continue; ?>
      <a href="<?= BASE . $p ?>" lang="<?= $l ?>" hreflang="<?= $l ?>"><?= strtoupper($l) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-grid">
    <div class="hero-txt">
      <h1>
        <span class="kicker"><?= e($T['kicker']) ?></span>
        <span class="h1-big"><?= $T['h1'] ?></span>
      </h1>
      <p class="intro"><?= e($T['intro']) ?></p>
      <div class="hero-ctas">
        <a class="btn" href="<?= $wa ?>">
          <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg>
          WhatsApp
        </a>
      </div>
    </div>
    <img class="hero-photo" src="<?= $img ?>6eb65e67cded5ca5abdf9825535aa54d.jpg" alt="<?= e($T['portrait_alt']) ?>" width="263" height="400">
  </div>
  <nav class="quick-nav" aria-label="<?= e($T['nav_sec']) ?>">
    <?php foreach ($T['nav'] as $k => $label): ?>
    <a href="#<?= $ids[$k] ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </nav>
</section>

<div class="strip" aria-label="<?= e($T['photos']) ?>">
  <?php foreach ($strip(
      ['411ac471f1cd46a7c1dde76ecd0ebd3e.jpg', 'd7217f0075c11e676711efceb86d2362.jpg', '4c7ea82cef6103299b4876971c68d502.jpg', 'fae212c160c2cb964d60642a0a253c2b.jpg'],
      $T['strip1']) as $i => [$f, $alt]): ?>
  <figure><img src="<?= $img . $f ?>" alt="<?= e($alt) ?>" loading="lazy" class="p1-<?= $i ?>"></figure>
  <?php endforeach; ?>
</div>

<section id="<?= $ids['serveis'] ?>" class="sec bg-mist">
  <div class="sec-in">
    <div class="sec-head">
      <h2><?= e($T['svc_h2']) ?></h2>
      <p class="lead"><?= e($T['svc_lead']) ?></p>
    </div>
    <div class="fm-grid">
      <?php foreach ($T['services'] as [$h, $p]): ?>
      <details class="fm-acc" open>
        <summary><h3><?= e($h) ?></h3></summary>
        <div class="acc-body">
          <p class="txt"><?= strtr(e($p), $svcLinks) ?></p>
          <a class="btn btn-sm svc-btn" href="<?= $wa ?>"><?= e($T['write_me']) ?></a>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
    <a class="btn svc-btn-all" href="<?= $wa ?>"><?= e($T['write_me']) ?></a>
  </div>
</section>

<section id="<?= $ids['tallers'] ?>" class="sec bg-sage">
  <div class="sec-in">
    <h2><?= e($T['ws_h2']) ?></h2>
    <div class="ws">
      <?php if ($tallers): ?>
      <div class="ws-head">
        <span></span>
        <span><?= e($T['ws_date']) ?></span>
        <span><?= e($T['ws_place']) ?></span>
        <span><?= e($T['ws_price']) ?></span>
        <span aria-hidden="true" class="ws-ghost"><?= e($T['ws_signup']) ?></span>
      </div>
      <?php foreach ($tallers as $t):
          $title = tr($t, 'titol', $lang);
          $link = ($t['enllac'] ?? '') !== '' ? $t['enllac'] : wa_link($T['ws_wa'] . $title);
          $lloc = $t['lloc'] ?? '';
          $adr = $t['adreca'] ?? '';
      ?>
      <div class="ws-row">
        <h3><?= e($title) ?></h3>
        <span class="ws-cell"><span class="ws-lbl"><?= e($T['ws_date']) ?>: </span><?= e($t['_date']) ?></span>
        <span class="ws-cell"><span class="ws-lbl"><?= e($T['ws_place']) ?>: </span><?= e($lloc) ?><?php if ($adr !== ''): ?><br><a href="<?= e($mapsLink(trim("$lloc, $adr, Barcelona", ', '))) ?>" target="_blank" rel="noopener"><?= e($adr) ?></a><?php endif; ?></span>
        <span class="ws-price"><span class="ws-lbl"><?= e($T['ws_price']) ?>: </span><?= e(money($t['preu'] ?? '')) ?></span>
        <a class="btn btn-md" href="<?= e($link) ?>"<?= str_starts_with($link, 'https://wa.me/') ? '' : ' target="_blank" rel="noopener"' ?>><?= e($T['ws_signup']) ?></a>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <p class="ws-none"><?= e($T['ws_none']) ?></p>
      <?php endif; ?>
      <p class="ws-more"><?= e($T['ws_more']) ?> <a href="<?= $wa ?>"><?= e($T['ws_contact']) ?></a></p>
    </div>
  </div>
</section>

<div class="strip" aria-label="<?= e($T['photos']) ?>">
  <?php foreach ($strip(
      ['c748a916aaeb6a0aaf1594ed36762eb7.jpg', 'cfd065775be057d317e7a7c328932ab0.jpg', '5fb17c9e6fbacbb90ea8016b1d5ee3db.jpg', 'f638e32efa48db4926f5186a65353608.jpg'],
      $T['strip2']) as $i => [$f, $alt]): ?>
  <figure><img src="<?= $img . $f ?>" alt="<?= e($alt) ?>" loading="lazy" class="p2-<?= $i ?>"></figure>
  <?php endforeach; ?>
</div>

<section id="<?= $ids['formacio'] ?>" class="sec bg-mist">
  <div class="sec-in">
    <h2><?= e($T['fm_h2']) ?></h2>
    <p class="lead"><?= e($T['fm_lead']) ?></p>
    <div class="fm-grid">
      <?php foreach ($formacio as $g): ?>
      <details class="fm-acc" open>
        <summary><h3><?= e($g['title']) ?></h3></summary>
        <div class="acc-body acc-list">
          <?php foreach ($g['items'] as $it):
              $anys = str_ireplace('actualitat', $T['present'], $it['anys'] ?? '');
          ?>
          <p class="txt"><?php if ($anys !== ''): ?><b class="k"><?= e($anys) ?></b> <?php endif; ?><?= fmt(tr($it, 'text', $lang), 'k') ?></p>
          <?php endforeach; ?>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="<?= $ids['preus'] ?>" class="sec bg-light">
  <div class="sec-in">
    <h2><?= e($T['pr_h2']) ?></h2>
    <div class="pr-grid">
      <?php foreach ($preus as $gi => $g): ?>
      <div class="pr-group">
        <h3><?= e($g['title']) ?></h3>
        <div class="pr-list">
          <?php foreach ($g['items'] as $it): ?>
          <div class="pr-row"><span><?= fmt(tr($it, 'concepte', $lang)) ?></span><span class="pr-val"><?= e(money($it['preu'] ?? '')) ?></span></div>
          <?php endforeach; ?>
        </div>
        <?php if ($gi === array_key_last($preus)): ?>
        <p class="pr-note"><?= e($T['pr_note']) ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <div class="gift">
        <svg aria-hidden="true" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="13" rx="2"></rect><path d="M3 12h18M12 8v13"></path><path d="M12 8c-1.5-3-5-3.5-5-1s3 1 5 1c2 0 5 1.5 5-1s-3.5-2-5 1z"></path></svg>
        <h3><?= e($T['gift_h3']) ?></h3>
        <p><?= e($T['gift_p']) ?></p>
        <a class="btn btn-md btn-light" href="<?= $wa ?>"><?= e($T['gift_btn']) ?></a>
      </div>
    </div>
    <p class="pr-other"><?= str_replace('{link}', '<a href="' . $wa . '">' . e($T['pr_other_link']) . '</a>', e($T['pr_other'])) ?></p>
  </div>
</section>

<footer class="foot">
  <div class="foot-in">
    <span><?= e($T['foot1']) ?></span>
    <span><?= e($T['foot2']) ?></span>
  </div>
</footer>

</div>
<script src="<?= BASE ?>/assets/js/lactancia.js" defer></script>
</body>
</html>
