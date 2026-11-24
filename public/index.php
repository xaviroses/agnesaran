<?php require_once __DIR__ . '/inc/sheet.php'; $cfg = site_config(); ?><!doctype html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include __DIR__ . '/inc/head-extra.php'; ?>
<title>Agnès Aran — No puc estar quieta</title>
<meta name="description" content="Agnès Aran: assessora de lactància, veu (locucions, jingles i música) i cuina de casa. Barcelona.">
<link rel="canonical" href="<?= e($cfg['site_url']) ?>/">
<meta property="og:type" content="website">
<meta property="og:title" content="Agnès Aran — No puc estar quieta">
<meta property="og:description" content="Lactància, veu i cuina. Barcelona.">
<style>
html,body{margin:0;height:100%;background:#F3EADB}
main{position:relative;width:100%;height:100vh;height:100dvh;overflow:hidden;font-family:'Bebas Neue',Impact,sans-serif;color:#1E1A16}
h1{margin:0;width:100%;height:100%}
svg.mot{display:block}
.lg{position:absolute;z-index:2;display:block}
.lg:focus-visible{outline:3px solid #1E1A16;outline-offset:6px;border-radius:12px}
.lg>span{display:block;transition:transform .3s ease}
.lg:hover>span,.lg:focus-visible>span{transform:scale(1.08)}
/* COCU: gran, tort, a mig camí de la primera línia */
/* La gif original fa 200×200 px: no la mostrem més gran perquè no es vegi borrosa */
.cocu{left:4vw;top:-2vh;width:clamp(120px,min(34vw,42vh),200px);animation:deriva1 9s ease-in-out infinite alternate}
.cocu img{display:block;width:100%;height:auto;transform:rotate(-9deg);filter:drop-shadow(1.5px 0 .6px #fff) drop-shadow(-1.5px 0 .6px #fff) drop-shadow(0 1.5px .6px #fff) drop-shadow(0 -1.5px .6px #fff)}
/* Gota: rodona verda, torta, entre ESTAR i QUIETA a la dreta */
.lact{right:7vw;top:52vh;width:clamp(64px,min(16vw,20vh),200px);aspect-ratio:1;animation:deriva2 7s ease-in-out infinite alternate}
.lact>span{width:100%;height:100%;border-radius:50%;background:#95a08c;display:flex;align-items:center;justify-content:center;transform:rotate(14deg)}
.lact svg{width:42%;height:auto;animation:gota 2.4s ease-in-out infinite}
/* Micròfon: sota la gota, una mica més a l'esquerra */
.veu{right:17vw;top:70vh;width:clamp(80px,min(19vw,25vh),240px);animation:deriva3 8s ease-in-out infinite alternate}
.veu img{display:block;width:100%;height:auto;transform:rotate(-6deg)}
/* Olla vermella: a l'esquerra, damunt de QUIETA */
.cuina{left:18vw;top:60vh;width:clamp(110px,min(24vw,30vh),320px);animation:deriva4 10s ease-in-out infinite alternate}
.cuina img{display:block;width:100%;height:auto;transform:rotate(8deg);filter:drop-shadow(0 8px 12px rgba(30,26,22,.22))}
@keyframes deriva1{0%{translate:0 0;rotate:0deg}50%{translate:1.5vw 2vh;rotate:4deg}100%{translate:-1vw 3.5vh;rotate:-3deg}}
@keyframes deriva2{0%{translate:0 0;rotate:0deg}50%{translate:-2vw -3vh;rotate:-6deg}100%{translate:1vw -1vh;rotate:5deg}}
@keyframes deriva3{0%{translate:0 0;rotate:0deg}50%{translate:1.5vw -2vh;rotate:5deg}100%{translate:-1.5vw 1vh;rotate:-4deg}}
@keyframes deriva4{0%{translate:0 0;rotate:0deg}50%{translate:2vw -2.5vh;rotate:-5deg}100%{translate:-1vw 1.5vh;rotate:4deg}}
@keyframes gota{0%,100%{transform:translateY(-6%)}50%{transform:translateY(6%)}}
@media (max-aspect-ratio:3/4){.cocu{left:2vw;top:0}.lact{right:5vw;top:58vh}.veu{right:20vw;top:72vh}.cuina{left:14vw;top:64vh}}
@media (prefers-reduced-motion:reduce){.cocu,.lact,.veu,.cuina,.lact svg{animation:none}}
</style>
</head>
<body>
<main>
<a class="lg cocu" href="https://wearecocu.com/" target="_blank" rel="noopener"><span><img src="<?= BASE ?>/assets/img/home/cocu.gif" alt="COCU — disseny gràfic"></span></a>
<a class="lg lact" href="<?= BASE ?>/lactancia/" aria-label="Agnès Aran, assessora de lactància"><span><svg viewBox="0 0 66 100" aria-hidden="true"><path d="M33 0C22 18 0 48 0 67a33 33 0 0 0 66 0C66 48 44 18 33 0Z" fill="#fff"/></svg></span></a>
<a class="lg veu" href="<?= BASE ?>/veu/"><span><img src="<?= BASE ?>/assets/img/home/veu.png" alt="Agnès Aran — veu, locucions i jingles"></span></a>
<a class="lg cuina" href="<?= BASE ?>/cuina/" aria-label="Agnès Aran — cuina per encàrrec"><span><img src="<?= BASE ?>/assets/img/home/cuina.png" alt=""></span></a>
<h1>
<svg class="mot" viewBox="0 0 1000 1000" preserveAspectRatio="none" width="100%" height="100%" role="img" aria-label="No puc estar quieta">
<g fill="currentColor" stroke="currentColor" stroke-width="16" stroke-linejoin="miter">
<path d="M47.9 30H108L154.6 205.6H155.4V30H198.1V323.3H148.9L91.4 109.2H90.5V323.3H47.9ZM230.4 253.7V99.6Q230.4 64.4 249.1 45.1Q267.8 25.8 303.1 25.8Q338.4 25.8 357.1 45.1Q375.9 64.4 375.9 99.6V253.7Q375.9 288.9 357.1 308.2Q338.4 327.5 303.1 327.5Q267.8 327.5 249.1 308.2Q230.4 288.9 230.4 253.7ZM327.9 256.7V96.6Q327.9 67.7 303.1 67.7Q278.3 67.7 278.3 96.6V256.7Q278.3 285.6 303.1 285.6Q327.9 285.6 327.9 256.7ZM477.8 30H548.4Q584.1 30 601.9 48.4Q619.8 66.9 619.8 102.5V131.4Q619.8 167 601.9 185.4Q584.1 203.9 548.4 203.9H525.7V323.3H477.8ZM548.4 162Q560.1 162 566 155.7Q571.9 149.4 571.9 134.3V99.6Q571.9 84.5 566 78.2Q560.1 71.9 548.4 71.9H525.7V162ZM644.2 253.7V30H692.1V257.1Q692.1 272.2 698.4 278.9Q704.7 285.6 716.5 285.6Q728.2 285.6 734.6 278.9Q740.9 272.2 740.9 257.1V30H787.1V253.7Q787.1 289.4 768.8 308.4Q750.5 327.5 715.6 327.5Q680.8 327.5 662.5 308.4Q644.2 289.4 644.2 253.7ZM818 255.4V97.9Q818 63.5 836.1 44.7Q854.1 25.8 888.5 25.8Q923 25.8 941 44.7Q959.1 63.5 959.1 97.9V128.9H913.8V94.9Q913.8 67.7 889.9 67.7Q865.9 67.7 865.9 94.9V258.8Q865.9 285.6 889.9 285.6Q913.8 285.6 913.8 258.8V213.9H959.1V255.4Q959.1 289.8 941 308.6Q923 327.5 888.5 327.5Q854.1 327.5 836.1 308.6Q818 289.8 818 255.4Z"/>
<path d="M50.3 353.4H198.4V395.3H104.6V472.8H179.2V514.7H104.6V604.8H198.4V646.7H50.3ZM220.2 577.1V560.4H271.5V580.5Q271.5 609 299.7 609Q313.5 609 320.7 602.1Q327.9 595.2 327.9 579.7Q327.9 561.2 318 547.2Q308.1 533.2 281.4 513.5Q247.8 488.3 234.5 468Q221.2 447.7 221.2 422.1Q221.2 387.3 241.9 368.3Q262.7 349.2 302.2 349.2Q341.2 349.2 361.2 368.3Q381.2 387.3 381.2 423V435.1H329.8V420Q329.8 404.9 322.9 398Q316 391.1 302.7 391.1Q275.5 391.1 275.5 419.2Q275.5 435.1 285.6 448.9Q295.7 462.8 322.4 482.5Q356.5 507.6 369.3 528.1Q382.2 548.7 382.2 576.3Q382.2 612.3 361.2 631.6Q340.2 650.9 300.2 650.9Q260.7 650.9 240.4 631.8Q220.2 612.8 220.2 577.1ZM455.8 395.3H399V353.4H566.9V395.3H510.1V646.7H455.8ZM635.1 353.4H708.7L765 646.7H710.7L700.8 588.5V589.3H639L629.2 646.7H578.8ZM694.4 549.5 670.2 404.5H669.2L645.5 549.5ZM791.2 353.4H871.7Q913.7 353.4 933 370Q952.2 386.5 952.2 420.9V438.9Q952.2 484.5 916.7 496.7V497.5Q936.4 502.6 944.6 518.1Q952.7 533.6 952.7 559.5V611.1Q952.7 623.7 953.7 631.4Q954.7 639.2 958.6 646.7H903.3Q900.4 639.6 899.4 633.3Q898.4 627 898.4 610.7V557Q898.4 536.9 890.7 529Q883.1 521 864.3 521H845.5V646.7H791.2ZM865.3 479.1Q881.6 479.1 889.7 472Q897.9 464.9 897.9 448.1V425.5Q897.9 409.5 891.2 402.4Q884.5 395.3 870.2 395.3H845.5V479.1Z"/>
<path d="M150.9 969.2Q137.2 974.2 118.6 974.2Q82.7 974.2 63.7 954.9Q44.6 935.6 44.6 900.4V746.3Q44.6 711.1 63.7 691.8Q82.7 672.5 118.6 672.5Q154.5 672.5 173.5 691.8Q192.6 711.1 192.6 746.3V900.4Q192.6 929.8 178.8 948.6Q181.5 952.8 185.5 954.1Q189.5 955.3 197 955.3H204.5V997.2H191.7Q158.5 997.2 150.9 969.2ZM143.8 903.4V743.3Q143.8 714.4 118.6 714.4Q93.3 714.4 93.3 743.3V903.4Q93.3 932.3 118.6 932.3Q143.8 932.3 143.8 903.4ZM223.6 900.4V676.7H272.3V903.8Q272.3 918.9 278.7 925.6Q285.2 932.3 297.1 932.3Q309.1 932.3 315.5 925.6Q321.9 918.9 321.9 903.8V676.7H368.9V900.4Q368.9 936.1 350.3 955.1Q331.7 974.2 296.2 974.2Q260.8 974.2 242.2 955.1Q223.6 936.1 223.6 900.4ZM403.4 676.7H452.2V970H403.4ZM488.5 676.7H621.4V718.6H537.2V796.1H604.1V838H537.2V928.1H621.4V970H488.5ZM687.4 718.6H636.4V676.7H787V718.6H736.1V970H687.4ZM848.2 676.7H914.2L964.7 970H916L907.1 911.8V912.6H851.7L842.9 970H797.7ZM901.3 872.8 879.6 727.8H878.7L857.5 872.8Z"/>
</g>
</svg>
</h1>
</main>
</body>
</html>
