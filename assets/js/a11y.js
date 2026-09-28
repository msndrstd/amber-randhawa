/* ============================================================
   AMBER RANDHAWA — ACCESSIBILITY TOOLBAR
   Msndrstd Creative
   Custom-built ADA toolbar. No third-party service, no tracking,
   no external requests. State is stored in localStorage only.
   ============================================================ */

(function () {
  'use strict';

  var STORAGE_KEY = 'ar-a11y-settings';
  var root = document.documentElement;

  var TEXT_STEPS = ['a11y-text-1', 'a11y-text-2', 'a11y-text-3']; // index = step - 1
  var TOGGLE_CLASSES = {
    readable:   'a11y-readable',
    dyslexia:   'a11y-dyslexia',
    underline:  'a11y-underline-links',
    contrast:   'a11y-high-contrast',
    motion:     'a11y-reduce-motion',
    cursor:     'a11y-big-cursor'
  };

  function loadSettings() {
    try {
      var raw = window.localStorage.getItem(STORAGE_KEY);
      if (!raw) return { textStep: 0 };
      var parsed = JSON.parse(raw);
      if (typeof parsed.textStep !== 'number') parsed.textStep = 0;
      return parsed;
    } catch (e) {
      return { textStep: 0 };
    }
  }

  function saveSettings(settings) {
    try {
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
    } catch (e) { /* localStorage unavailable — settings just won't persist */ }
  }

  var settings = loadSettings();

  function applyTextStep(step) {
    TEXT_STEPS.forEach(function (cls) { root.classList.remove(cls); });
    if (step > 0) root.classList.add(TEXT_STEPS[step - 1]);
  }

  function applyToggle(key, on) {
    var cls = TOGGLE_CLASSES[key];
    if (!cls) return;
    root.classList.toggle(cls, !!on);

    // Reduced motion also stops Lenis smooth scroll if it's running.
    if (key === 'motion') {
      if (on && window.arLenis && typeof window.arLenis.stop === 'function') {
        window.arLenis.stop();
      } else if (!on && window.arLenis && typeof window.arLenis.start === 'function') {
        window.arLenis.start();
      }
    }
  }

  function applyAll() {
    applyTextStep(settings.textStep || 0);
    Object.keys(TOGGLE_CLASSES).forEach(function (key) {
      applyToggle(key, !!settings[key]);
    });
  }

  // Settings are already applied pre-paint by the inline snippet in header.php.
  // Re-apply here in case Lenis wasn't ready yet, and to sync UI state below.
  applyAll();

  document.addEventListener('DOMContentLoaded', function () {
    applyAll(); // re-run once Lenis (window.arLenis) exists, so motion toggle can call .stop()

    var toggleBtn = document.getElementById('a11y-toggle');
    var panel = document.getElementById('a11y-panel');
    var closeBtn = document.getElementById('a11y-panel-close');
    if (!toggleBtn || !panel) return;

    function openPanel() {
      panel.classList.add('is-open');
      toggleBtn.setAttribute('aria-expanded', 'true');
      var firstFocusable = panel.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
      if (firstFocusable) firstFocusable.focus();
    }

    function closePanel(returnFocus) {
      panel.classList.remove('is-open');
      toggleBtn.setAttribute('aria-expanded', 'false');
      if (returnFocus) toggleBtn.focus();
    }

    toggleBtn.addEventListener('click', function () {
      var isOpen = panel.classList.contains('is-open');
      if (isOpen) { closePanel(false); } else { openPanel(); }
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', function () { closePanel(true); });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && panel.classList.contains('is-open')) {
        closePanel(true);
      }
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!panel.classList.contains('is-open')) return;
      if (panel.contains(e.target) || toggleBtn.contains(e.target)) return;
      closePanel(false);
    });

    // Basic focus trap while panel is open
    panel.addEventListener('keydown', function (e) {
      if (e.key !== 'Tab') return;
      var focusables = panel.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
      if (!focusables.length) return;
      var first = focusables[0];
      var last = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    });

    // Text size steppers
    var incBtn = document.getElementById('a11y-text-inc');
    var decBtn = document.getElementById('a11y-text-dec');
    if (incBtn) {
      incBtn.addEventListener('click', function () {
        settings.textStep = Math.min(3, (settings.textStep || 0) + 1);
        applyTextStep(settings.textStep);
        saveSettings(settings);
      });
    }
    if (decBtn) {
      decBtn.addEventListener('click', function () {
        settings.textStep = Math.max(0, (settings.textStep || 0) - 1);
        applyTextStep(settings.textStep);
        saveSettings(settings);
      });
    }

    // Toggle options
    var optionButtons = panel.querySelectorAll('[data-a11y-toggle]');
    optionButtons.forEach(function (btn) {
      var key = btn.getAttribute('data-a11y-toggle');
      btn.setAttribute('aria-pressed', settings[key] ? 'true' : 'false');
      btn.addEventListener('click', function () {
        var next = !settings[key];
        settings[key] = next;
        btn.setAttribute('aria-pressed', next ? 'true' : 'false');
        applyToggle(key, next);
        saveSettings(settings);
      });
    });

    // Reset all
    var resetBtn = document.getElementById('a11y-reset');
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        settings = { textStep: 0 };
        Object.keys(TOGGLE_CLASSES).forEach(function (key) { settings[key] = false; });
        applyAll();
        saveSettings(settings);
        optionButtons.forEach(function (btn) {
          btn.setAttribute('aria-pressed', 'false');
        });
      });
    }
  });
})();
