/* Land Records — homepage behaviour.
   Reveals, the fixed index rail, and the writing hover pane.
   Everything degrades to a working static page without JS. */
(function () {
  'use strict';

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- reveal on enter ---- */
  var rise = document.querySelectorAll('.lr-rise');

  /* Anything already on screen at first paint is revealed SYNCHRONOUSLY,
     before the observer is even wired up. Interior pages put content
     directly under the header, so waiting on an IntersectionObserver
     callback left the top of the page blank for a beat, and in a
     backgrounded tab it stayed blank until the 2.5s fallback fired.
     Reveal-on-scroll should only apply to things you scroll to. */
  var vh0 = window.innerHeight || document.documentElement.clientHeight;
  Array.prototype.forEach.call(rise, function (el) {
    if (el.getBoundingClientRect().top < vh0 * 0.92) { el.classList.add('is-in'); }
  });

  if (!('IntersectionObserver' in window) || reduce) {
    Array.prototype.forEach.call(rise, function (el) { el.classList.add('is-in'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
      });
    }, { rootMargin: '0px 0px -12% 0px', threshold: 0.08 });
    Array.prototype.forEach.call(rise, function (el) { io.observe(el); });
  }

  /* ---- index rail ---- */
  var items = document.querySelectorAll('.lr-index li');
  var sections = document.querySelectorAll('[data-lr-section]');
  if (items.length && sections.length && 'IntersectionObserver' in window) {
    var byId = {};
    Array.prototype.forEach.call(items, function (li) {
      var a = li.querySelector('a');
      if (a) { byId[a.getAttribute('href').slice(1)] = li; }
    });
    var railIo = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        var li = byId[e.target.id];
        if (!li) { return; }
        if (e.isIntersecting) {
          Array.prototype.forEach.call(items, function (x) { x.classList.remove('is-active'); });
          li.classList.add('is-active');
        }
      });
    }, { rootMargin: '-45% 0px -45% 0px' });
    Array.prototype.forEach.call(sections, function (s) { railIo.observe(s); });
  }


  /* ---- band parallax ----
     The picture is taller than its frame and slides inside it as the band
     crosses the viewport, so it drifts slower than the page.

     Driven by a rAF loop, NOT by scroll events. This theme sets
     overflow-y on <body>, so which element emits 'scroll' is not reliable
     here — a window scroll listener measured zero events while the page
     was demonstrably scrolling. The loop only runs while the band is on
     screen, gated by an observer that is kept in a variable (an
     unreferenced IntersectionObserver can be collected).
     Frame height and oversize factor both come from CSS. */
  var band = document.querySelector('.lr-band-photo');
  var bandImg = band && band.querySelector('img');
  if (bandImg && !reduce && window.requestAnimationFrame) {
    var running = false, rafId = 0, lastY = null;

    var place = function () {
      var r = band.getBoundingClientRect();
      var vh = window.innerHeight || document.documentElement.clientHeight;
      var oversize = parseFloat(
        getComputedStyle(band).getPropertyValue('--band-oversize')
      ) || 1.3;
      var slack = r.height * (oversize - 1) / 2;   // travel each way
      /* 0 when the band's top edge sits at the bottom of the viewport,
         1 when its bottom edge reaches the top. */
      var p = (vh - r.top) / (vh + r.height);
      if (p < 0) { p = 0; } else if (p > 1) { p = 1; }
      var y = (p - 0.5) * 2 * slack;
      if (lastY === null || Math.abs(y - lastY) > 0.25) {
        bandImg.style.transform = 'translate3d(0,' + y.toFixed(2) + 'px,0)';
        lastY = y;
      }
    };

    var loop = function () {
      place();
      if (running) { rafId = window.requestAnimationFrame(loop); }
    };

    var start = function () {
      if (running) { return; }
      running = true;
      band.classList.add('is-parallax');
      rafId = window.requestAnimationFrame(loop);
    };
    var stop = function () {
      running = false;
      band.classList.remove('is-parallax');
      if (rafId) { window.cancelAnimationFrame(rafId); rafId = 0; }
    };

    if ('IntersectionObserver' in window) {
      var bandIo = new IntersectionObserver(function (entries) {
        if (entries[entries.length - 1].isIntersecting) { start(); } else { stop(); }
      }, { rootMargin: '150px 0px' });
      bandIo.observe(band);
    } else {
      start();
    }

    bandImg.addEventListener('load', place);
    place();
  }

  /* ---- writing hover pane ---- */
  var entries = document.querySelectorAll('.lr-entry[data-pane]');
  var panes = document.querySelectorAll('.lr-pane-inner [data-pane-item]');
  if (entries.length && panes.length) {
    var show = function (key) {
      Array.prototype.forEach.call(panes, function (p) {
        p.classList.toggle('is-showing', p.getAttribute('data-pane-item') === key);
      });
    };
    show(entries[0].getAttribute('data-pane'));
    Array.prototype.forEach.call(entries, function (el) {
      var key = el.getAttribute('data-pane');
      ['mouseenter', 'focusin'].forEach(function (evt) {
        el.addEventListener(evt, function () { show(key); });
      });
    });
  }
})();
