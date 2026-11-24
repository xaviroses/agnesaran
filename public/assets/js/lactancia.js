/* Lactància: carrusel de fotos i acordions en mòbil (del disseny original). */
(function () {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var MOB = 700;

  // Carrusel automàtic de les franges de fotos en mòbil
  setInterval(function () {
    if (window.innerWidth > MOB || reduce || document.hidden) return;
    document.querySelectorAll('.strip').forEach(function (el) {
      var w = el.clientWidth;
      var next = el.scrollLeft + w >= el.scrollWidth - 5 ? 0 : el.scrollLeft + w;
      el.scrollTo({ left: next, behavior: 'smooth' });
    });
  }, 3500);

  // Acordions: oberts en escriptori, tancats en mòbil
  var wasMob;
  function sync() {
    var mob = window.innerWidth <= MOB;
    if (mob === wasMob) return;
    wasMob = mob;
    document.querySelectorAll('.fm-acc').forEach(function (d) {
      d.open = !mob;
      d.classList.toggle('is-open', !mob);
    });
  }
  sync();
  window.addEventListener('resize', sync);

  document.addEventListener('click', function (e) {
    var sum = e.target.closest && e.target.closest('.fm-acc > summary');
    if (!sum || window.innerWidth > MOB) return;
    e.preventDefault();
    var d = sum.parentElement, body = sum.nextElementSibling;
    if (d._anim) { d._anim.cancel(); d._anim = null; }
    var opening = !d.classList.contains('is-open');
    var start = d.offsetHeight;
    d.open = true; var full = d.offsetHeight;
    d.open = false; var closed = d.offsetHeight;
    d.open = true;
    d.classList.toggle('is-open', opening);
    if (reduce || !d.animate) { d.open = opening; return; }
    d.style.overflow = 'hidden';
    var ease = 'cubic-bezier(.2,.7,.2,1)';
    d._anim = d.animate({ height: [start + 'px', (opening ? full : closed) + 'px'] }, { duration: opening ? 420 : 320, easing: ease });
    if (body) body.animate({ opacity: opening ? [0, 1] : [1, 0], transform: opening ? ['translateY(-8px)', 'translateY(0)'] : ['translateY(0)', 'translateY(-4px)'] }, { duration: opening ? 420 : 220, easing: ease, fill: 'forwards' });
    d._anim.onfinish = function () {
      d.open = opening; d.style.overflow = ''; d._anim = null;
      if (body) body.getAnimations().forEach(function (a) { a.cancel(); });
    };
  });
})();
