<?php
/**
 * Configuració general de la web.
 *
 * GOOGLE SHEET
 * - sheet_id: l'identificador del full (el tros llarg de l'URL entre /d/ i /edit).
 *   El full ha d'estar compartit com "Qualsevol persona amb l'enllaç: lector".
 * - gid: el número de cada pestanya (surt a l'URL com #gid=123456 quan la selecciones).
 * Si sheet_id és buit, la web fa servir els CSV de /data (mode local / còpia de seguretat).
 */
return [
    'sheet_id' => '1vwq6fj-VA_gRaReWfi9ystauD5J7pby03-yanbDsKHg',
    'gid' => [
        'tallers'  => '2130862753',
        'preus'    => '1343828472',
        'formacio' => '208147106',
        'videos'   => '2083268928',
    ],
    // Segons que es guarda la còpia del full abans de tornar-lo a llegir de Google.
    'cache_ttl' => 300,

    // ID del lloc a CookieYes (el tros de l'URL del script: cdn-cookieyes.com/client_data/<ID>/script.js).
    // Buit = no es mostra cap banner.
    'cookieyes_id' => '',

    'whatsapp' => '34651453340',
    'site_url' => 'https://agnesaran.com',
];
