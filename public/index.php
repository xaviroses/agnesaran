<?php require_once __DIR__ . '/inc/sheet.php'; $cfg = site_config(); ?><!doctype html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $section_icon = 'home'; include __DIR__ . '/inc/head-extra.php'; ?>
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
<g fill="currentColor" stroke="currentColor" stroke-width="13" stroke-linejoin="miter">
<path d="M47.9 30H108L154.6 205.6H155.4V30H198.1V323.3H148.9L91.4 109.2H90.5V323.3H47.9ZM230.4 253.7V99.6Q230.4 64.4 249.1 45.1Q267.8 25.8 303.1 25.8Q338.4 25.8 357.1 45.1Q375.9 64.4 375.9 99.6V253.7Q375.9 288.9 357.1 308.2Q338.4 327.5 303.1 327.5Q267.8 327.5 249.1 308.2Q230.4 288.9 230.4 253.7ZM327.9 256.7V96.6Q327.9 67.7 303.1 67.7Q278.3 67.7 278.3 96.6V256.7Q278.3 285.6 303.1 285.6Q327.9 285.6 327.9 256.7ZM477.8 30H548.4Q584.1 30 601.9 48.4Q619.8 66.9 619.8 102.5V131.4Q619.8 167 601.9 185.4Q584.1 203.9 548.4 203.9H525.7V323.3H477.8ZM548.4 162Q560.1 162 566 155.7Q571.9 149.4 571.9 134.3V99.6Q571.9 84.5 566 78.2Q560.1 71.9 548.4 71.9H525.7V162ZM644.2 253.7V30H692.1V257.1Q692.1 272.2 698.4 278.9Q704.7 285.6 716.5 285.6Q728.2 285.6 734.6 278.9Q740.9 272.2 740.9 257.1V30H787.1V253.7Q787.1 289.4 768.8 308.4Q750.5 327.5 715.6 327.5Q680.8 327.5 662.5 308.4Q644.2 289.4 644.2 253.7ZM818 255.4V97.9Q818 63.5 836.1 44.7Q854.1 25.8 888.5 25.8Q923 25.8 941 44.7Q959.1 63.5 959.1 97.9V128.9H913.8V94.9Q913.8 67.7 889.9 67.7Q865.9 67.7 865.9 94.9V258.8Q865.9 285.6 889.9 285.6Q913.8 285.6 913.8 258.8V213.9H959.1V255.4Q959.1 289.8 941 308.6Q923 327.5 888.5 327.5Q854.1 327.5 836.1 308.6Q818 289.8 818 255.4Z"/>
<path d="M50.7 353.4H202.4V395.3H106.4V472.8H182.7V514.7H106.4V604.8H202.4V646.7H50.7ZM225.7 577.1V560.4H278.3V580.5Q278.3 609 307.1 609Q321.3 609 328.6 602.1Q335.9 595.2 335.9 579.7Q335.9 561.2 325.8 547.2Q315.7 533.2 288.4 513.5Q254 488.3 240.3 468Q226.7 447.7 226.7 422.1Q226.7 387.3 247.9 368.3Q269.2 349.2 309.6 349.2Q349.6 349.2 370 368.3Q390.5 387.3 390.5 423V435.1H337.9V420Q337.9 404.9 330.9 398Q323.8 391.1 310.1 391.1Q282.3 391.1 282.3 419.2Q282.3 435.1 292.7 448.9Q303.1 462.8 330.4 482.5Q365.2 507.6 378.4 528.1Q391.5 548.7 391.5 576.3Q391.5 612.3 370 631.6Q348.6 650.9 307.6 650.9Q267.1 650.9 246.4 631.8Q225.7 612.8 225.7 577.1ZM466.9 395.3H408.7V353.4H580.7V395.3H522.5V646.7H466.9ZM627.2 353.4H702.5L760.2 646.7H704.5L694.4 588.5V589.3H631.2L621.1 646.7H569.5ZM687.8 549.5 663.1 404.5H662.1L637.8 549.5ZM787 353.4H869.4Q912.4 353.4 932.1 370Q951.8 386.5 951.8 420.9V438.9Q951.8 484.5 915.4 496.7V497.5Q935.6 502.6 944 518.1Q952.3 533.6 952.3 559.5V611.1Q952.3 623.7 953.3 631.4Q954.3 639.2 958.4 646.7H901.7Q898.7 639.6 897.7 633.3Q896.7 627 896.7 610.7V557Q896.7 536.9 888.8 529Q881 521 861.8 521H842.6V646.7H787ZM862.8 479.1Q879.5 479.1 887.8 472Q896.2 464.9 896.2 448.1V425.5Q896.2 409.5 889.3 402.4Q882.5 395.3 867.9 395.3H842.6V479.1Z"/>
<path d="M153.6 969.2Q139.6 974.2 120.6 974.2Q83.9 974.2 64.4 954.9Q44.9 935.6 44.9 900.4V746.3Q44.9 711.1 64.4 691.8Q83.9 672.5 120.6 672.5Q157.2 672.5 176.7 691.8Q196.2 711.1 196.2 746.3V900.4Q196.2 929.8 182.1 948.6Q184.9 952.8 188.9 954.1Q193 955.3 200.7 955.3H208.4V997.2H195.3Q161.3 997.2 153.6 969.2ZM146.4 903.4V743.3Q146.4 714.4 120.6 714.4Q94.7 714.4 94.7 743.3V903.4Q94.7 932.3 120.6 932.3Q146.4 932.3 146.4 903.4ZM227.9 900.4V676.7H277.7V903.8Q277.7 918.9 284.2 925.6Q290.8 932.3 303 932.3Q315.3 932.3 321.8 925.6Q328.4 918.9 328.4 903.8V676.7H376.4V900.4Q376.4 936.1 357.4 955.1Q338.4 974.2 302.1 974.2Q265.9 974.2 246.9 955.1Q227.9 936.1 227.9 900.4ZM411.7 676.7H461.5V970H411.7ZM498.6 676.7H634.5V718.6H548.4V796.1H616.8V838H548.4V928.1H634.5V970H498.6ZM701.9 718.6H649.9V676.7H803.8V718.6H751.8V970H701.9ZM845.5 676.7H912.9L964.6 970H914.8L905.7 911.8V912.6H849.1L840 970H793.9ZM899.8 872.8 877.6 727.8H876.7L855 872.8Z"/>
</g>
</svg>
</h1>
</main>
</body>
</html>
