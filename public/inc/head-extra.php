<?php
/** Elements comuns del <head>: favicon i, si està configurat, el banner de cookies de CookieYes. */
$cookieyes = trim(site_config()['cookieyes_id'] ?? '');
if ($cookieyes !== ''): ?>
<!-- Start cookieyes banner -->
<script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/<?= e($cookieyes) ?>/script.js"></script>
<!-- End cookieyes banner -->
<?php endif; ?>
<link rel="icon" href="<?= BASE ?>/favicon.ico" sizes="48x48">
<link rel="icon" href="<?= BASE ?>/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?= BASE ?>/apple-touch-icon.png">
<meta name="theme-color" content="#F3EADB">
