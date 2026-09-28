/* ============================================================
   AMBER RANDHAWA — MAIN JS
   Msndrstd Creative
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ----------------------------------------------------------
     LENIS SMOOTH SCROLL
     ---------------------------------------------------------- */
  const lenis = new Lenis({
    duration: 1.4,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smooth: true,
  });

  // Exposed so the accessibility toolbar (a11y.js) can stop/start smooth
  // scroll when "Reduce motion" is toggled on.
  window.arLenis = lenis;

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);

  // Wire Lenis into GSAP ScrollTrigger
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => { lenis.raf(time * 1000); });
  gsap.ticker.lagSmoothing(0);

  /* ----------------------------------------------------------
     NAVIGATION
     Homepage: transparent → linen on scroll past hero
     Sub-pages: transparent (linen elements) → linen bar auto after short delay
     ---------------------------------------------------------- */
  const nav = document.querySelector('.site-nav');
  const isHome = document.body.classList.contains('home');

  if (nav) {
    if (isHome) {
      // Homepage: scroll-triggered
      // The linen bar arrives early, well before the hero is cleared.
      const heroEl = document.querySelector('.lr-hero-fs, .hero');
      const getThreshold = () => heroEl ? Math.min(heroEl.offsetHeight * 0.18, 150) : 150;
      const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > getThreshold());
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    } else {
      // Sub-pages: scroll-triggered (low threshold — transitions quickly after first scroll)
      const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 60);
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }
  }

  /* ----------------------------------------------------------
     MOBILE NAV TOGGLE
     ---------------------------------------------------------- */
  const navToggle = document.querySelector('.nav-toggle');
  const navLinks  = document.querySelector('.nav-links');
  const navClose  = document.querySelector('.nav-panel-close');

  if (navToggle && navLinks) {
    /* Scroll lock. `overflow: hidden` on <body> alone does not hold on
       iOS Safari -- the page keeps rubber-banding behind the overlay.
       Pinning the body with position:fixed does, but it loses the
       scroll position, so stash it and put it back on close. */
    let lockedY = 0;

    const setNav = (open) => {
      navLinks.classList.toggle('open', open);
      navToggle.setAttribute('aria-expanded', open);
      /* body.nav-open also kills the nav's backdrop-filter in CSS.
         A filtered element is the containing block for its fixed
         children, which was clipping this panel to the nav bar. */
      document.body.classList.toggle('nav-open', open);

      if (open) {
        lockedY = window.scrollY || window.pageYOffset || 0;
        document.body.style.position = 'fixed';
        document.body.style.top      = `-${lockedY}px`;
        document.body.style.left     = '0';
        document.body.style.right    = '0';
        document.body.style.width    = '100%';
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.position = '';
        document.body.style.top      = '';
        document.body.style.left     = '';
        document.body.style.right    = '';
        document.body.style.width    = '';
        document.body.style.overflow = '';
        window.scrollTo(0, lockedY);
      }
    };

    navToggle.addEventListener('click', () => {
      setNav(!navLinks.classList.contains('open'));
    });

    if (navClose) {
      navClose.addEventListener('click', () => {
        setNav(false);
        navToggle.focus();
      });
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navLinks.classList.contains('open')) {
        setNav(false);
        navToggle.focus();
      }
    });

    navLinks.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => setNav(false));
    });
  }

  /* ----------------------------------------------------------
     GSAP SCROLL ANIMATIONS
     ---------------------------------------------------------- */
  gsap.registerPlugin(ScrollTrigger);

  // Generic reveal
  gsap.utils.toArray('.reveal').forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      y: 0,
      duration: 1,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      },
    });
  });

  // Reveal from left
  gsap.utils.toArray('.reveal-left').forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      duration: 1,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      },
    });
  });

  // Reveal from right
  gsap.utils.toArray('.reveal-right').forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      duration: 1,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      },
    });
  });

  // Scale reveal
  gsap.utils.toArray('.reveal-scale').forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      scale: 1,
      duration: 1.2,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      },
    });
  });

  // Stagger children — add class 'stagger-children' to parent
  gsap.utils.toArray('.stagger-children').forEach((parent) => {
    const children = parent.children;
    gsap.from(children, {
      opacity: 0,
      y: 24,
      duration: 0.8,
      ease: 'power3.out',
      stagger: 0.12,
      scrollTrigger: {
        trigger: parent,
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    });
  });

  /* ----------------------------------------------------------
     HERO ENTRANCE
     ---------------------------------------------------------- */
  const heroContent = document.querySelector('.hero-content');
  if (heroContent) {
    gsap.from(heroContent.children, {
      opacity: 0,
      y: 30,
      duration: 1.2,
      ease: 'power3.out',
      stagger: 0.18,
      delay: 0.3,
    });
  }

  /* ----------------------------------------------------------
     PARALLAX HERO BG
     ---------------------------------------------------------- */
  const heroBg = document.querySelector('.hero-bg img');
  if (heroBg) {
    gsap.to(heroBg, {
      yPercent: 25,
      ease: 'none',
      scrollTrigger: {
        trigger: '.hero',
        start: 'top top',
        end: 'bottom top',
        scrub: true,
      },
    });
  }

  /* ----------------------------------------------------------
     POST CARD HOVER — slight lift
     ---------------------------------------------------------- */
  document.querySelectorAll('.post-card').forEach((card) => {
    card.addEventListener('mouseenter', () => {
      gsap.to(card, { y: -4, duration: 0.4, ease: 'power2.out' });
    });
    card.addEventListener('mouseleave', () => {
      gsap.to(card, { y: 0, duration: 0.4, ease: 'power2.out' });
    });
  });

});


/* ============================================================
   COMMUNITIES DROPDOWN
   ============================================================ */
(function () {
  const items = document.querySelectorAll('.nav-has-dropdown');
  if (!items.length) return;

  items.forEach(function (item) {
    const trigger = item.querySelector('.nav-dropdown-trigger');
    const dropdown = item.querySelector('.nav-dropdown');
    if (!trigger || !dropdown) return;

    // Desktop: hover is handled by CSS. JS handles keyboard + click-outside + mobile tap.

    // Toggle on click/tap
    trigger.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = item.classList.contains('open');
      // Close all others first
      items.forEach(function (i) {
        i.classList.remove('open');
        const t = i.querySelector('.nav-dropdown-trigger');
        if (t) t.setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
      }
    });

    // Keyboard: Enter/Space to toggle, Escape to close
    trigger.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        trigger.click();
      }
      if (e.key === 'Escape') {
        item.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.focus();
      }
    });

    // Arrow-key nav within dropdown links
    dropdown.addEventListener('keydown', function (e) {
      const links = Array.from(dropdown.querySelectorAll('a'));
      const idx = links.indexOf(document.activeElement);
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (idx < links.length - 1) links[idx + 1].focus();
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (idx > 0) links[idx - 1].focus(); else trigger.focus();
      } else if (e.key === 'Escape') {
        item.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.focus();
      }
    });
  });

  // Click outside closes all dropdowns
  document.addEventListener('click', function () {
    items.forEach(function (item) {
      item.classList.remove('open');
      const t = item.querySelector('.nav-dropdown-trigger');
      if (t) t.setAttribute('aria-expanded', 'false');
    });
  });
})();
