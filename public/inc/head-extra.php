<?php
/** Elements comuns del <head>: favicon i, si està configurat, el banner de cookies de CookieYes. */
$cookieyes = trim(site_config()['cookieyes_id'] ?? '');
if ($cookieyes !== ''): ?>
<!-- Start cookieyes banner -->
<script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/<?= e($cookieyes) ?>/script.js"></script>
<!-- End cookieyes banner -->
<?php endif; ?>
<?php $v = '2'; // canvia-ho quan es canviï el favicon perquè els navegadors no facin servir la còpia antiga ?>
<link rel="icon" href="<?= BASE ?>/favicon.ico?v=<?= $v ?>" sizes="48x48">
<link rel="icon" href="<?= BASE ?>/favicon-32x32.png?v=<?= $v ?>" type="image/png" sizes="32x32">
<link rel="icon" href="<?= BASE ?>/favicon-16x16.png?v=<?= $v ?>" type="image/png" sizes="16x16">
<link rel="apple-touch-icon" href="<?= BASE ?>/apple-touch-icon.png?v=<?= $v ?>">
<link rel="manifest" href="<?= BASE ?>/site.webmanifest?v=<?= $v ?>">
<meta name="theme-color" content="#F3EADB">
