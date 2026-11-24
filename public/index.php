<?php require_once __DIR__ . '/inc/sheet.php'; $cfg = site_config(); ?><!doctype html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Agnès Aran — No puc estar quieta</title>
<meta name="description" content="Agnès Aran: assessora de lactància, veu (locucions, jingles i música) i cuina de casa. Barcelona.">
<link rel="canonical" href="<?= e($cfg['site_url']) ?>/">
<meta property="og:type" content="website">
<meta property="og:title" content="Agnès Aran — No puc estar quieta">
<meta property="og:description" content="Lactància, veu i cuina. Barcelona.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap">
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
<text x="30" y="323.3" font-size="419" textLength="940" lengthAdjust="spacingAndGlyphs" fill="currentColor">NO PUC</text>
<text x="30" y="646.7" font-size="419" textLength="940" lengthAdjust="spacingAndGlyphs" fill="currentColor">ESTAR</text>
<text x="30" y="970" font-size="419" textLength="940" lengthAdjust="spacingAndGlyphs" fill="currentColor">QUIETA</text>
</svg>
</h1>
</main>
</body>
</html>
