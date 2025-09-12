/* Vanilla JS for core theme interactions (no jQuery). */
(function(){
  'use strict';

  function on(el, type, handler, opts){ el.addEventListener(type, handler, opts || false); }
  function qs(sel, ctx){ return (ctx||document).querySelector(sel); }
  function qsa(sel, ctx){ return Array.prototype.slice.call((ctx||document).querySelectorAll(sel)); }

  document.documentElement.classList.remove('no-js');
  document.documentElement.classList.add('js');

  function initNavigation(){
    const nav = qs('#main-nav');
    const btn = qs('.mobile-menu-button');
    const menu = qs('.mobile-menu');
    const ham = qs('.hamburger-icon');
    const close = qs('.close-icon');
    if (btn && menu){
      on(btn, 'click', function(){
        const open = menu.classList.contains('show');
        if (open){
          menu.classList.remove('show');
          menu.classList.add('hidden');
          ham && ham.classList.remove('hidden');
          close && close.classList.add('hidden');
          btn.setAttribute('aria-expanded','false');
        } else {
          menu.classList.add('show');
          menu.classList.remove('hidden');
          ham && ham.classList.add('hidden');
          close && close.classList.remove('hidden');
          btn.setAttribute('aria-expanded','true');
        }
      });
    }
    qsa('.mobile-nav-link').forEach(function(a){
      on(a,'click',function(){
        if (menu){ menu.classList.remove('show'); menu.classList.add('hidden'); }
        ham && ham.classList.remove('hidden');
        close && close.classList.add('hidden');
        btn && btn.setAttribute('aria-expanded','false');
      });
    });
    // Smooth anchors via scrollIntoView (CSS scroll-margin-top handles offset)
    qsa('a[href^="#"]').forEach(function(a){
      on(a,'click',function(e){
        const href = a.getAttribute('href');
        if (!href || href === '#') return;
        const tgt = qs(href);
        if (!tgt) return;
        e.preventDefault();
        if (typeof tgt.scrollIntoView === 'function') {
          tgt.scrollIntoView({ behavior:'smooth', block:'start' });
        } else {
          location.hash = href;
        }
      });
    });
    // Nav bg on scroll
    let lastY = window.scrollY || window.pageYOffset || 0, raf = false, solid = null;
    function update(){
      raf = false;
      if (!nav) return;
      const dark = document.documentElement.classList.contains('dark');
      const makeSolid = lastY > 50;
      if (makeSolid === solid) return;
      solid = makeSolid;
      if (makeSolid){
        nav.classList.remove('bg-transparent','backdrop-blur-none','bg-white','bg-neutral-900');
        nav.classList.add(dark ? 'bg-neutral-900' : 'bg-white','shadow-lg');
      } else {
        nav.classList.remove('bg-white','bg-neutral-900','shadow-lg');
        nav.classList.add('bg-transparent','backdrop-blur-none');
      }
    }
    on(window,'scroll',function(){
      lastY = window.scrollY || window.pageYOffset || 0;
      if (!raf){ raf = true; requestAnimationFrame(update); }
    }, { passive:true });
    update();
  }

  function initScrollEffects(){
    const bar = qs('.scroll-progress');
    let winH = window.innerHeight, docH = document.documentElement.scrollHeight;
    function refresh(){ winH = window.innerHeight; docH = document.documentElement.scrollHeight; requestAnimationFrame(updateBar); }
    function updateBar(){
      const y = window.scrollY || window.pageYOffset || 0;
      const ratio = Math.min(y / Math.max(1, docH - winH), 1);
      if (bar) bar.style.transform = 'scaleX(' + ratio + ')';
    }
    on(window,'scroll',function(){ requestAnimationFrame(updateBar); }, { passive:true });
    on(window,'orientationchange',function(){ setTimeout(refresh,300); }, { passive:true });
    refresh();

    // Animate-on-scroll
    const hero = qsa('.hero-section .animate-on-scroll, .animate-fade-in-up');
    hero.forEach(function(el){ const d = el.classList.contains('animation-delay-600')?600: el.classList.contains('animation-delay-400')?400: el.classList.contains('animation-delay-200')?200:0; setTimeout(function(){ el.classList.add('visible'); }, d); });
    const rest = qsa('.animate-on-scroll').filter(function(el){ return hero.indexOf && hero.indexOf(el) === -1; });
    if ('IntersectionObserver' in window && rest.length){
      const io = new IntersectionObserver(function(entries){ entries.forEach(function(en){ if (en.isIntersecting){ en.target.classList.add('visible'); io.unobserve(en.target); } }); }, { threshold:0.1, rootMargin:'0px 0px -50px 0px' });
      rest.forEach(function(el){ io.observe(el); });
    }
  }

  function initThemeToggle(){
    const html = document.documentElement;
    const toggles = qsa('#theme-toggle, #mobile-theme-toggle');
    function applyTurnstileTheme(){
      const widgets = qsa('.cf-turnstile'); if (!widgets.length) return;
      const dark = html.classList.contains('dark');
      widgets.forEach(function(old){
        const ph = document.createElement('div');
        ph.className = 'cf-turnstile';
        ['data-sitekey','data-action','data-callback'].forEach(function(attr){ const v = old.getAttribute(attr); if (v) ph.setAttribute(attr, v); });
        ph.setAttribute('data-theme', dark ? 'dark' : 'light');
        old.replaceWith(ph);
        if (window.turnstile && typeof window.turnstile.render === 'function') {
          try { window.turnstile.render(ph, { sitekey: ph.getAttribute('data-sitekey') || undefined, theme: dark?'dark':'light', action: ph.getAttribute('data-action') || undefined }); } catch(e){}
        }
      });
    }
    window.updateTurnstileTheme = applyTurnstileTheme;
    // initial
    const saved = localStorage.getItem('theme');
    if (saved) html.classList.toggle('dark', saved === 'dark');
    setTimeout(applyTurnstileTheme, 0);

    function updateNavBg(){ requestAnimationFrame(function(){ const y = window.scrollY || window.pageYOffset || 0; if (y>50){ const nav = qs('#main-nav'); if (!nav) return; nav.classList.remove('bg-white','bg-neutral-900'); nav.classList.add(html.classList.contains('dark')?'bg-neutral-900':'bg-white'); } }); }

    toggles.forEach(function(btn){ on(btn,'click',function(){ const isDark = html.classList.contains('dark'); html.classList.toggle('dark', !isDark); localStorage.setItem('theme', !isDark ? 'dark' : 'light'); setTimeout(updateNavBg,50); setTimeout(applyTurnstileTheme,10); document.body.classList.add('theme-transitioning'); setTimeout(function(){ document.body.classList.remove('theme-transitioning'); },300); }); });

    if (window.matchMedia){
      const mq = window.matchMedia('(prefers-color-scheme: dark)');
      if (mq && mq.addEventListener) mq.addEventListener('change', function(e){ if (!localStorage.getItem('theme')){ html.classList.toggle('dark', e.matches); setTimeout(updateNavBg,50); setTimeout(applyTurnstileTheme,10); } });
    }
    window.cfTurnstileReady = function(){ setTimeout(applyTurnstileTheme,0); };
  }

  function initCommentsToggle(){
    const btn = qs('#comments-toggle');
    const content = qs('#comments-content');
    const txt = qs('#comments-toggle-text');
    const icon = qs('#comments-toggle-icon');
    if (!btn || !content) return;
    let visible = false;
    on(btn,'click',function(){
      if (visible){
        content.classList.remove('visible'); content.classList.add('opacity-0');
        setTimeout(function(){ content.classList.add('hidden'); content.setAttribute('aria-hidden','true'); },300);
        if (txt) txt.textContent = 'Show Comments';
        if (icon){ icon.classList.remove('fa-chevron-up'); icon.classList.add('fa-chevron-down'); }
        visible = false;
      } else {
        content.classList.remove('hidden'); content.setAttribute('aria-hidden','false');
        setTimeout(function(){ content.classList.add('visible'); content.classList.remove('opacity-0'); if (window.updateTurnstileTheme) setTimeout(window.updateTurnstileTheme,10); },50);
        if (txt) txt.textContent = 'Hide Comments';
        if (icon){ icon.classList.remove('fa-chevron-down'); icon.classList.add('fa-chevron-up'); }
        visible = true;
        setTimeout(function(){ if (content.scrollIntoView) content.scrollIntoView({ behavior:'smooth', block:'start' }); },100);
      }
    });

    if (window.location.hash && (window.location.hash.indexOf('#comment')!==-1 || window.location.hash === '#comments')){
      btn.click();
      setTimeout(function(){ const target = document.querySelector(window.location.hash); if (target && target.scrollIntoView) target.scrollIntoView({ behavior:'smooth', block:'start' }); }, 600);
    }
  }

  function ready(fn){ if (document.readyState !== 'loading') fn(); else document.addEventListener('DOMContentLoaded', fn); }
  ready(function(){ initNavigation(); initScrollEffects(); initThemeToggle(); initCommentsToggle(); });
  window.addEventListener('load', function(){ const ls = document.querySelector('.loading-screen'); if (ls && ls.parentNode) ls.parentNode.removeChild(ls); });
})();

