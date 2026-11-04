(function () {
    'use strict';

    var root = document.documentElement;
    var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Dark mode toggle (the saved theme is applied in <head> so the page never flashes).
    var themeToggle = document.getElementById('theme-toggle');
    function updateThemeIcon(theme) {
        if (!themeToggle) return;
        var icon = themeToggle.querySelector('i');
        icon.classList.toggle('fa-sun', theme === 'dark');
        icon.classList.toggle('fa-moon', theme !== 'dark');
    }
    updateThemeIcon(root.getAttribute('data-theme'));
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            root.setAttribute('data-theme', next);
            try { localStorage.setItem('theme', next); } catch (e) { /* private mode */ }
            updateThemeIcon(next);
        });
    }

    // Animate On Scroll. Inner pages mark blocks with .reveal; give them the same fade-up.
    document.querySelectorAll('.reveal').forEach(function (el) {
        if (!el.hasAttribute('data-aos')) el.setAttribute('data-aos', 'fade-up');
    });
    if (window.AOS) {
        window.AOS.init({ duration: 800, easing: 'ease-out-cubic', once: true, offset: 50, disable: reduceMotion });
    }

    // Navbar: tighter once scrolled, and highlight the section in view on the homepage.
    var nav = document.querySelector('.navbar');
    var sectionLinks = Array.prototype.filter.call(document.querySelectorAll('.navbar .nav-link'), function (a) {
        var hash = a.hash;
        return hash && a.pathname === window.location.pathname && document.querySelector(hash);
    });
    function onScroll() {
        if (nav) nav.classList.toggle('scrolled', window.scrollY > 30);
        if (!sectionLinks.length) return;
        var current = null;
        sectionLinks.forEach(function (a) {
            var section = document.querySelector(a.hash);
            if (window.scrollY >= section.offsetTop - 200) current = a;
        });
        sectionLinks.forEach(function (a) { a.classList.toggle('active', a === current); });
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Close the mobile menu after choosing a link.
    document.querySelectorAll('.navbar .nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            var menu = document.getElementById('navbarNav');
            if (menu && menu.classList.contains('show') && window.bootstrap) {
                window.bootstrap.Collapse.getOrCreateInstance(menu).hide();
            }
        });
    });

    // Typewriter headline: types each [data-type] part in turn, so the gold ending keeps its colour.
    var typeEl = document.getElementById('typewriter-text');
    if (typeEl && !reduceMotion) {
        var parts = Array.prototype.slice.call(typeEl.querySelectorAll('[data-type]'));
        parts.forEach(function (p) { p.textContent = ''; });
        var part = 0, pos = 0;
        (function typeWriter() {
            if (part >= parts.length) return;
            var text = parts[part].getAttribute('data-type');
            if (pos < text.length) {
                parts[part].textContent += text.charAt(pos++);
            } else {
                part++;
                pos = 0;
            }
            setTimeout(typeWriter, 50);
        })();
    }

    // Spotlight that follows the mouse on product cards.
    document.querySelectorAll('.product-card').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
            card.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
        });
    });

    // Scroll progress ring / back to top.
    var progressWrap = document.querySelector('.progress-wrap');
    var progressPath = progressWrap && progressWrap.querySelector('path');
    if (progressPath) {
        var pathLength = progressPath.getTotalLength();
        progressPath.style.transition = 'none';
        progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = 'stroke-dashoffset 10ms linear';
        var updateProgress = function () {
            var height = document.documentElement.scrollHeight - window.innerHeight;
            progressPath.style.strokeDashoffset = height > 0 ? pathLength - (window.scrollY * pathLength / height) : pathLength;
            progressWrap.classList.toggle('active-progress', window.scrollY > 50);
        };
        updateProgress();
        window.addEventListener('scroll', updateProgress, { passive: true });
        progressWrap.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Count-up numbers: <span class="counter-number" data-target="150" data-suffix="+">0</span>
    function animateCounter(counter) {
        var target = parseInt(counter.getAttribute('data-target'), 10) || 0;
        var suffix = counter.getAttribute('data-suffix') || '';
        var start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / 1600, 1);
            counter.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))) + (p === 1 ? suffix : '');
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    var counters = document.querySelectorAll('.counter-number[data-target]');
    if ('IntersectionObserver' in window && !reduceMotion) {
        var counterObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { animateCounter(entry.target); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.5 });
        counters.forEach(function (c) { c.textContent = '0'; counterObserver.observe(c); });
    }

    // 3D tilt on cards (mouse only).
    if (window.VanillaTilt && finePointer && !reduceMotion) {
        window.VanillaTilt.init(document.querySelectorAll('.glass-card:not(.no-tilt), .product-card, .about-img-item, .stats-item, .help-card'), {
            max: 10, speed: 400, glare: true, 'max-glare': 0.2, scale: 1.02
        });
    }

    // Slide-up reveal for section headings (keeps highlighted words like <span class="hl">).
    var revealTargets = Array.prototype.filter.call(document.querySelectorAll('h2, .service-title'), function (el) {
        return el.textContent.trim() !== '' && !el.closest('.academy-cta');
    });
    if ('IntersectionObserver' in window && !reduceMotion) {
        revealTargets.forEach(function (el) {
            var outer = document.createElement('span');
            outer.className = 'reveal-text-wrapper';
            var inner = document.createElement('span');
            inner.className = 'reveal-text-inner';
            while (el.firstChild) inner.appendChild(el.firstChild);
            outer.appendChild(inner);
            el.appendChild(outer);
        });
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) entry.target.classList.add('reveal-visible');
            });
        }, { threshold: 0.2 });
        revealTargets.forEach(function (el) { revealObserver.observe(el); });
    }

    // Project category filter on the homepage.
    document.querySelectorAll('[data-filter]').forEach(function (chip) {
        chip.addEventListener('click', function () {
            var f = chip.getAttribute('data-filter');
            document.querySelectorAll('[data-filter]').forEach(function (c) { c.classList.toggle('active', c === chip); });
            document.querySelectorAll('.proj-item').forEach(function (item) {
                item.classList.toggle('hide', f !== '*' && item.getAttribute('data-cat') !== f);
            });
            if (window.AOS) window.AOS.refresh();
        });
    });

    // Help-centre search: filters the FAQ cards as you type.
    var faqSearch = document.getElementById('faq-search');
    if (faqSearch) {
        var faqEmpty = document.getElementById('faq-empty');
        var runFaqSearch = function () {
            var q = faqSearch.value.trim().toLowerCase();
            var shown = 0;
            document.querySelectorAll('.faq-item').forEach(function (item) {
                var match = !q || item.textContent.toLowerCase().indexOf(q) !== -1;
                item.classList.toggle('hide', !match);
                if (match) shown++;
            });
            if (faqEmpty) faqEmpty.classList.toggle('d-none', shown > 0);
        };
        faqSearch.addEventListener('input', runFaqSearch);
        var faqForm = faqSearch.closest('form');
        if (faqForm) faqForm.addEventListener('submit', function (e) { e.preventDefault(); runFaqSearch(); });
    }

    // Hide flash messages after a few seconds.
    document.querySelectorAll('.flash').forEach(function (f) {
        setTimeout(function () { f.style.transition = 'opacity .4s'; f.style.opacity = '0'; setTimeout(function () { f.remove(); }, 400); }, 6000);
    });
})();
