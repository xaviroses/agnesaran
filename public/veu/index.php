<?php
require_once __DIR__ . '/../inc/sheet.php';
require_once __DIR__ . '/../inc/yt-thumbs.php';
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
    // Miniatura de YouTube (si l'enllaç és de YouTube) i la zona sense franges; i.ytimg.com no posa galetes
    $thumbs = preg_match('~(?:youtu\.be/|[?&]v=|/(?:embed|shorts|live)/)([\w-]{11})~', $url, $m)
        ? yt_thumbs($m[1]) : [];
    $videos[] = [$cat, $r['titol'] ?? '', $url, $thumbs];
}
?><!doctype html>
<html lang="ca">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $section_icon = 'veu'; include __DIR__ . '/../inc/head-extra.php'; ?>
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
#graella a.thumb::before{content:"";position:absolute;inset:0;background:var(--img) var(--bp,center)/var(--bs,cover) no-repeat;transition:transform .4s}
#graella a.thumb:hover::before,#graella a.thumb:focus-visible::before{transform:scale(1.04)}
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
var rows=Math.ceil(n/cols);
g.style.gridTemplateColumns='repeat('+cols+',minmax(0,1fr))';
g.style.gridTemplateRows='repeat('+rows+',minmax(0,1fr))';
list.forEach(function(v,i){
var a=document.createElement('a');a.href=v[2];a.target='_blank';a.rel='noopener';
a.setAttribute('aria-label',v[1]);if(v[1])a.title=v[1];
var fosc=!((i+Math.floor(i/cols))%2);
a.style.background=fosc?'#EBA096':'#F2AFA5';
if(v[3]&&v[3].length){a.className='thumb';a._t=v[3]}
a.innerHTML='<span>'+icon+'</span>';g.appendChild(a);
});
// Les caselles que sobren a l'última fila es queden buides, en rosa (seguint l'arlequinat)
for(var i=n;i<rows*cols;i++){var d=document.createElement('div');d.setAttribute('aria-hidden','true');d.style.background=((i+Math.floor(i/cols))%2)?'#F2AFA5':'#EBA096';g.appendChild(d)}
// Cada miniatura porta franges; t = [url, amplada, alçada, zona amb imatge [esq, dalt, dreta, baix]].
// S'amplia perquè la zona amb imatge cobreixi tota la casella, i si la petita quedaria pixelada es fa servir la gran.
var dpr=window.devicePixelRatio||1;
g.querySelectorAll('a.thumb').forEach(function(a){
var w=a.offsetWidth,h=a.offsetHeight,ts=a._t,t=ts[0];
function fit(t){
// Marge de seguretat: la vora de les franges és borrosa (JPEG); on n'hi havia, se'n treuen uns píxels més
var W=t[1],H=t[2],c=t[3].slice(),u=W/480,m=3*u,e=6*u;
c[0]+=m+(c[0]>0?e:0);c[1]+=m+(c[1]>0?e:0);c[2]-=m+(c[2]<W?e:0);c[3]-=m+(c[3]<H?e:0);
var cw=c[2]-c[0],ch=c[3]-c[1],k=Math.max(w/cw,h/ch);
return {k:k,s:Math.ceil(W*k)+'px '+Math.ceil(H*k)+'px',p:(-(c[0]*k-(w-cw*k)/2)).toFixed(1)+'px '+(-(c[1]*k-(h-ch*k)/2)).toFixed(1)+'px'};
}
var f=fit(t);
if(ts[1]&&f.k*dpr>1.3){t=ts[1];f=fit(t)}
a.style.setProperty('--img','url("'+t[0]+'")');
a.style.setProperty('--bs',f.s);
a.style.setProperty('--bp',f.p);
});
document.querySelectorAll('.filtres button').forEach(function(b){b.setAttribute('aria-pressed',String(b.dataset.cat===sel))});
}
document.querySelectorAll('.filtres button').forEach(function(b){b.addEventListener('click',function(){sel=sel===b.dataset.cat?null:b.dataset.cat;render()})});
window.addEventListener('resize',render);render();
})();
</script>
</body>
</html>
