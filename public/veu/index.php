<?php
require_once __DIR__ . '/../inc/sheet.php';
$cfg = site_config();

// Vídeos del full "videos": categoria (locucions | jingles | musica), titol, url
$cats = ['loc' => 'locucions', 'jin' => 'jingles', 'mus' => 'musica'];
$videos = [];
foreach (sheet_rows('videos') as $r) {
    $cat = $cats[substr(norm_key($r['categoria'] ?? ''), 0, 3)] ?? null;
    $url = $r['url'] ?? '';
    if (!$cat || !preg_match('~^https?://~', $url)) {
        continue;
    }
    // Miniatura de YouTube (si l'enllaç és de YouTube): i.ytimg.com no posa galetes
    $thumb = preg_match('~(?:youtu\.be/|[?&]v=|/(?:embed|shorts|live)/)([\w-]{11})~', $url, $m)
        ? "https://i.ytimg.com/vi/{$m[1]}/hqdefault.jpg" : '';
    $videos[] = [$cat, $r['titol'] ?? '', $url, $thumb];
}
?><!doctype html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include __DIR__ . '/../inc/head-extra.php'; ?>
<title>Veu · Agnès Aran</title>
<meta name="description" content="Agnès Aran, veu: locucions, jingles i música.">
<link rel="canonical" href="<?= e($cfg['site_url']) ?>/veu/">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&amp;display=swap">
<style>
html,body{margin:0;background:#ffc9c1}
a{color:#A3483D}a:hover{color:#82362D}
.wrap{height:100vh;height:100dvh;min-height:480px;display:flex;flex-direction:column;background:#ffc9c1;font-family:Inter,sans-serif;color:#A3483D}
nav{flex:none;display:flex;justify-content:center;padding:24px 16px}
.filtres{display:flex;flex-wrap:wrap;justify-content:center;gap:12px}
.filtres button{font:500 15px Inter,sans-serif;min-height:44px;padding:0 22px;border:1px solid #A3483D;border-radius:999px;cursor:pointer;background:transparent;color:#A3483D}
.filtres button[aria-pressed="true"]{background:#A3483D;color:#fff}
#graella{flex:1;min-height:0;display:grid;gap:0}
#graella a{min-width:0;min-height:0;display:flex;align-items:center;justify-content:center;text-decoration:none;color:#A3483D}
#graella a{position:relative;overflow:hidden}
#graella a.thumb::before{content:"";position:absolute;inset:0;background:var(--img) center/var(--bs,cover) no-repeat;transition:transform .4s}
#graella a.thumb::after{content:"";position:absolute;inset:0;background:rgba(163,72,61,.25);transition:background .3s}
#graella a.thumb:hover::before,#graella a.thumb:focus-visible::before{transform:scale(1.04)}
#graella a.thumb:hover::after,#graella a.thumb:focus-visible::after{background:rgba(163,72,61,.05)}
#graella a.thumb span{position:relative;z-index:1;filter:drop-shadow(0 1px 4px rgba(0,0,0,.45))}
#graella a.thumb svg{stroke:#fff;stroke-width:1}
#graella a span{display:flex;flex-direction:column;align-items:center;gap:12px;padding:12px;transition:transform .2s}
#graella a:hover span,#graella a:focus-visible span{transform:scale(1.12)}
footer{flex:none;display:flex;justify-content:center;padding:24px 16px}
footer a{display:inline-flex;align-items:center;min-height:44px;padding:0 22px;border:1px solid #A3483D;border-radius:999px;font:500 15px Inter,sans-serif;color:#A3483D;text-decoration:none}
</style>
</head>
<body>
<div class="wrap">
<nav><div class="filtres">
<button type="button" data-cat="locucions" aria-pressed="false">Locucions</button>
<button type="button" data-cat="jingles" aria-pressed="false">Jingles</button>
<button type="button" data-cat="musica" aria-pressed="false">Música</button>
</div></nav>
<div id="graella"></div>
<footer><a href="<?= BASE ?>/">Agnès</a></footer>
</div>
<script>
(function(){
var all=<?= json_encode($videos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
var sel=null,g=document.getElementById('graella');
var icon='<svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#A3483D" stroke-width="0.75" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M10 8l6 4-6 4z"></path></svg>';
function render(){
var list=all.filter(function(v){return !sel||v[0]===sel}),n=list.length;
g.innerHTML='';
if(!n)return;
var W=window.innerWidth||1280,H=Math.max((window.innerHeight||800)-184,300);
var cols=Math.min(n,Math.max(1,Math.round(Math.sqrt(n*(W/H)/(16/9)))));
var rows=Math.ceil(n/cols),spare=rows*cols-n;
g.style.gridTemplateColumns='repeat('+cols+',minmax(0,1fr))';
g.style.gridTemplateRows='repeat('+rows+',minmax(0,1fr))';
list.forEach(function(v,i){
var a=document.createElement('a');a.href=v[2];a.target='_blank';a.rel='noopener';
a.setAttribute('aria-label',v[1]);if(v[1])a.title=v[1];
a.style.background=((i+Math.floor(i/cols))%2)?'#F2AFA5':'#EBA096';
if(i===n-1&&spare>0)a.style.gridColumn='span '+(spare+1);
if(v[3]){a.className='thumb';a.style.setProperty('--img','url("'+v[3]+'")')}
a.innerHTML='<span>'+icon+'</span>';g.appendChild(a);
});
// hqdefault és 4:3 amb franges negres a dalt i a baix: la mida fa que el 16:9 del mig cobreixi la casella
g.querySelectorAll('a.thumb').forEach(function(a){var w=a.offsetWidth,h=a.offsetHeight;a.style.setProperty('--bs',Math.ceil(Math.max(w,h*16/9))+'px auto')});
document.querySelectorAll('.filtres button').forEach(function(b){b.setAttribute('aria-pressed',String(b.dataset.cat===sel))});
}
document.querySelectorAll('.filtres button').forEach(function(b){b.addEventListener('click',function(){sel=sel===b.dataset.cat?null:b.dataset.cat;render()})});
window.addEventListener('resize',render);render();
})();
</script>
</body>
</html>
