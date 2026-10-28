// Honour the visitor's reduced-motion preference (WCAG 2.3.3 / 2.2.2).
var stylenaReducedMotion = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : { matches: false };

// Right-to-left languages: sliders translate the other way and swipe directions swap.
var stylenaIsRtl = function () {
    return document.documentElement.dir === 'rtl' || document.body.classList.contains('rtl');
};

// Mouse drag support for the home sliders (touch is handled separately by each slider).
var stylenaBindMouseDrag = function (swiper, prev, next) {
    if (!swiper || !window.PointerEvent) return;
    let startX = 0, dragging = false, moved = false;
    swiper.addEventListener('pointerdown', function (e) {
        if (e.pointerType !== 'mouse' || e.button !== 0) return;
        dragging = true; moved = false; startX = e.clientX;
    });
    window.addEventListener('pointermove', function (e) {
        if (!dragging) return;
        if (Math.abs(e.clientX - startX) > 6) { moved = true; swiper.classList.add('is-dragging'); }
    });
    window.addEventListener('pointerup', function (e) {
        if (!dragging) return;
        dragging = false;
        swiper.classList.remove('is-dragging');
        const dx = e.clientX - startX;
        if (Math.abs(dx) > 50) { if ((dx > 0) !== stylenaIsRtl()) prev(); else next(); }
    });
    // A drag must not also open the lightbox or follow a link.
    swiper.addEventListener('click', function (e) {
        if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
    }, true);
    swiper.addEventListener('dragstart', function (e) { e.preventDefault(); });
};

// Size slides so exactly `visible` cards fit the slider viewport (3 desktop, 2 tablet, 1 phone).
var stylenaSizeSlides = function (viewport, slides, visible) {
    if (!viewport || !slides.length) return;
    const cs = window.getComputedStyle(slides[0]);
    const gap = (parseFloat(cs.marginRight) || 0) + (parseFloat(cs.marginLeft) || 0);
    // Each slide's margin box takes 1/visible of the viewport, whichever side(s) carry the margin.
    const width = Math.floor(viewport.clientWidth / visible - gap);
    if (width <= 0) return;
    // !important: small-screen stylesheet rules fix card widths with !important.
    slides.forEach(function (slide) { slide.style.setProperty('flex', '0 0 ' + width + 'px', 'important'); });
};

// Contact Form 7 thank you message handling
var wpcf7Elm = document.querySelector('.wpcf7');
if (wpcf7Elm) {
    wpcf7Elm.addEventListener('wpcf7submit', function (event) {
        var thanksTarget = document.querySelector('[data-target="thanks"]');
        if (thanksTarget) {
            thanksTarget.classList.add('active');
        }
    }, false);
}

// Reviews slider functionality
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        const reviewsSlider = document.querySelector('.reviews-slider');
        if (!reviewsSlider) return;

        const prevBtns = document.querySelectorAll('.reviews-prev');
        const nextBtns = document.querySelectorAll('.reviews-next');
        const wrapper = document.querySelector('.reviews-wrapper');
        const slides = document.querySelectorAll('.review-slide');

        if (!wrapper || slides.length === 0) {
            return;
        }

        let currentIndex = 0;

        function getVisibleSlides() {
            const width = window.innerWidth;
            if (width >= 1200) return 3;
            if (width >= 768) return 2;
            return 1;
        }

        function updateSlider() {
            if (slides.length === 0) return;

            const slideWidth = slides[0].offsetWidth;
            const isMobile = window.innerWidth < 768;
            const gap = isMobile ? 32 : 24;
            const translateX = currentIndex * (slideWidth + gap);

            wrapper.style.transform = `translate3d(${stylenaIsRtl() ? '' : '-'}${translateX}px, 0px, 0px)`;
            wrapper.style.transition = 'transform 0.4s ease-in-out';

            const visibleSlides = getVisibleSlides();
            const maxIndex = Math.max(0, slides.length - visibleSlides);

            const isPrevDisabled = currentIndex === 0;
            const isNextDisabled = currentIndex >= maxIndex;

            prevBtns.forEach(btn => {
                btn.classList.toggle('disabled', isPrevDisabled);
                btn.disabled = isPrevDisabled;
            });

            nextBtns.forEach(btn => {
                btn.classList.toggle('disabled', isNextDisabled);
                btn.disabled = isNextDisabled;
            });


            reviewsSlider.setAttribute('data-index', currentIndex);
        }

        function slideNext() {
            const visibleSlides = getVisibleSlides();
            const maxIndex = Math.max(0, slides.length - visibleSlides);

            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        }

        function slidePrev() {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        }

        prevBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                slidePrev();
            });
        });

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                slideNext();
            });
        });

        // Keyboard navigation (only when slider is focused)
        reviewsSlider.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                slidePrev();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                slideNext();
            }
        });

        reviewsSlider.setAttribute('tabindex', '0');

        // Touch support for mobile
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        reviewsSlider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        }, { passive: true });

        reviewsSlider.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        }, { passive: true });

        reviewsSlider.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;

            const deltaX = currentX - startX;
            const threshold = 50;

            if (Math.abs(deltaX) > threshold) {
                if (deltaX > 0) {
                    slidePrev();
                } else {
                    slideNext();
                }
            }
        });

        // Intentionally do NOT respond to window resize events here.
        // Mobile browsers frequently emit resize during vertical scroll (address bar show/hide),
        // which can cause the slider to recalc and hide images. We keep the slider static
        // so it doesn't disappear when the user scrolls or the browser chrome changes.
        // If later you want live-resize behavior, call updateSlider() manually or re-enable
        // a guarded resize handler that checks width changes only.

        updateSlider();

    }, 200);
});

// Advice Section Form Enhancement
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.querySelector('input[name="advice_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            // Keep only characters valid in an international phone number (no locale-specific formatting).
            let value = e.target.value.replace(/[^\d+()\-\s]/g, '');
            e.target.value = value;
        });
    }

    // Custom form submission handling (for fallback form)
    const adviceForm = document.querySelector('.advice-contact-form');
    if (adviceForm) {
        adviceForm.addEventListener('submit', function (e) {
            const submitBtn = this.querySelector('.advice-submit-btn');
            if (submitBtn) {
                submitBtn.innerHTML = 'Sending...';
                submitBtn.disabled = true;
            }

            setTimeout(() => {
                if (submitBtn) {
                    submitBtn.innerHTML = 'Send a request';
                    submitBtn.disabled = false;
                }
            }, 3000);
        });
    }


    const adviceInputs = document.querySelectorAll('.advice-form .form-control');
    adviceInputs.forEach(input => {
        input.addEventListener('focus', function () {
            this.parentNode.classList.add('focused');
        });

        input.addEventListener('blur', function () {
            if (!this.value) {
                this.parentNode.classList.remove('focused');
            }
        });
    });
});

(function () {
    if (typeof jQuery === 'undefined') return;
    jQuery(document).ready(function ($) {
        'use strict';

        $('.error-btn[href^="#"]').on('click', function (e) {
            e.preventDefault();

            var target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800, 'easeInOutCubic');
            }
        });

        $('.error-number').on('mouseenter', function () {
            $(this).css('transform', 'scale(1.05)');
        }).on('mouseleave', function () {
            $(this).css('transform', 'scale(1)');
        });

        function stylenaAnimate404() {
            $('.error-404-links .widget').each(function () {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();
                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();

                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('animate-in');
                }
            });
        }

        stylenaAnimate404();
        $(window).on('scroll', stylenaAnimate404);

        $('<style>').prop('type', 'text/css').html('\n            .error-404-links .widget {\n                opacity: 0;\n                transform: translateY(30px);\n                transition: all 0.6s ease;\n            }\n            .error-404-links .widget.animate-in {\n                opacity: 1;\n                transform: translateY(0);\n            }\n            .error-search-form .search-form.focused {\n                border-color: #bc9c25;\n                box-shadow: 0 0 20px rgba(188, 156, 37, 0.3);\n            }\n        ').appendTo('head');

        $('.error-search-form .search-field').on('focus', function () {
            $(this).closest('.search-form').addClass('focused');
        }).on('blur', function () {
            $(this).closest('.search-form').removeClass('focused');
        });

        if ($(window).width() > 768) {
            $(window).on('scroll', function () {
                var scrolled = $(window).scrollTop();
                var rate = scrolled * -0.3;
                $('.error-number').css('transform', 'translateY(' + rate + 'px)');
            });
        }

        $('.help-link').on('mouseenter', function () {
            $(this).find('→').css('transform', 'translateX(5px)');
        }).on('mouseleave', function () {
            $(this).find('→').css('transform', 'translateX(0)');
        });

    });
})();

(function () {
    'use strict';

    // Initialize Portfolio (Team) and Services sliders separately with scoped variables
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            // Portfolio (Team) slider
            (function () {
                const container = document.querySelector('.portfolio-section');
                if (!container) return;

                const swiper = container.querySelector('.portfolio-swiper');
                const prevBtn = container.querySelector('.desktop-btns .swiper-button-prev');
                const nextBtn = container.querySelector('.desktop-btns .swiper-button-next');
                const mobilePrev = container.querySelector('.mobile-btns .swiper-button-prev');
                const mobileNext = container.querySelector('.mobile-btns .swiper-button-next');
                const wrapper = container.querySelector('.portfolio-swiper .swiper-wrapper');
                const slides = container.querySelectorAll('.portfolio-slide');
                if (!swiper || !wrapper || slides.length === 0) return;

                let idx = 0;

                function visible() {
                    const w = window.innerWidth;
                    if (w >= 1200) return 3;
                    if (w >= 768) return 2;
                    return 1;
                }

                function update() {
                    if (slides.length === 0) return;
                    stylenaSizeSlides(swiper, Array.from(slides), visible());
                    // compute the actual step between slides (distance between left edges)
                    const slideRect = slides[0].getBoundingClientRect();
                    const slideW = slideRect.width || slides[0].offsetWidth;
                    let step = slideW;
                    if (slides.length > 1) {
                        try {
                            const r0 = slides[0].getBoundingClientRect();
                            const r1 = slides[1].getBoundingClientRect();
                            step = Math.abs(r1.left - r0.left);
                        } catch (e) {
                            // fallback to margin left+right
                            try {
                                const cs = window.getComputedStyle(slides[0]);
                                const ml = parseFloat(cs.marginLeft) || 0;
                                const mr = parseFloat(cs.marginRight) || 0;
                                step = slideW + ml + mr;
                            } catch (er) { step = slideW; }
                        }
                    } else {
                        try {
                            const cs = window.getComputedStyle(slides[0]);
                            const ml = parseFloat(cs.marginLeft) || 0;
                            const mr = parseFloat(cs.marginRight) || 0;
                            step = slideW + ml + mr;
                        } catch (e) { step = slideW; }
                    }
                    const tx = idx * step;
                    wrapper.style.transform = `translate3d(${stylenaIsRtl() ? '' : '-'}${tx}px,0,0)`;
                    wrapper.style.transition = 'transform 0.4s ease-in-out';

                    const vis = visible();
                    const max = Math.max(0, slides.length - vis);
                    const prevDisabled = idx === 0;
                    const nextDisabled = idx >= max;
                    if (prevBtn) { prevBtn.classList.toggle('swiper-button-disabled', prevDisabled); prevBtn.disabled = prevDisabled; }
                    if (nextBtn) { nextBtn.classList.toggle('swiper-button-disabled', nextDisabled); nextBtn.disabled = nextDisabled; }
                    if (mobilePrev) { mobilePrev.classList.toggle('swiper-button-disabled', prevDisabled); mobilePrev.disabled = prevDisabled; }
                    if (mobileNext) { mobileNext.classList.toggle('swiper-button-disabled', nextDisabled); mobileNext.disabled = nextDisabled; }
                }

                function next() { const v = visible(); const max = Math.max(0, slides.length - v); if (idx < max) { idx++; update(); if (container.dataset.debugSlider === 'true') console.log('portfolio next', { idx }); } }
                function prev() { if (idx > 0) { idx--; update(); if (container.dataset.debugSlider === 'true') console.log('portfolio prev', { idx }); } }

                [prevBtn, mobilePrev].forEach(b => { if (b) b.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); prev(); }); });
                [nextBtn, mobileNext].forEach(b => { if (b) b.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); next(); }); });

                // touch
                let sx = 0, cx = 0, dragging = false;
                swiper.addEventListener('touchstart', e => { sx = e.touches[0].clientX; dragging = true; }, { passive: true });
                swiper.addEventListener('touchmove', e => { if (!dragging) return; cx = e.touches[0].clientX; const d = Math.abs(cx - sx); if (d > 10) e.preventDefault(); }, { passive: false });
                swiper.addEventListener('touchend', e => { if (!dragging) return; dragging = false; const dx = cx - sx; if (Math.abs(dx) > 50) { if ((dx > 0) !== stylenaIsRtl()) prev(); else next(); } });
                stylenaBindMouseDrag(swiper, prev, next);

                // Do not react to window.resize here. Keeping slider static prevents
                // flicker or disappearing slides during mobile scroll/resize events.
                // If needed in the future, re-enable with a guard that only runs when
                // the window width actually changes.

                // Re-fit slides when the width changes (rotation, window resize); height-only resizes are ignored.
                let lastWidth = window.innerWidth;
                window.addEventListener('resize', function () {
                    if (window.innerWidth === lastWidth) return;
                    lastWidth = window.innerWidth;
                    idx = Math.min(idx, Math.max(0, slides.length - visible()));
                    update();
                });

                update();
            })();

            // Services slider
            (function () {
                const container = document.querySelector('.services');
                if (!container) return;

                const swiper = container.querySelector('.services-swiper');
                const prevBtn = container.querySelector('.desktop-btns .swiper-button-prev');
                const nextBtn = container.querySelector('.desktop-btns .swiper-button-next');
                const mobilePrev = container.querySelector('.mobile-btns .swiper-button-prev');
                const mobileNext = container.querySelector('.mobile-btns .swiper-button-next');
                const wrapper = container.querySelector('.services-swiper .swiper-wrapper');
                const slides = container.querySelectorAll('.services .swiper-slide');
                if (!swiper || !wrapper || slides.length === 0) return;

                let idx = 0;
                function visible() { const w = window.innerWidth; if (w >= 1200) return 3; if (w >= 768) return 2; return 1; }

                function update() {
                    if (slides.length === 0) return;
                    stylenaSizeSlides(swiper, Array.from(slides), visible());
                    // compute the actual step between slides (distance between left edges)
                    const slideRect = slides[0].getBoundingClientRect();
                    const slideW = slideRect.width || slides[0].offsetWidth;
                    let step = slideW;
                    if (slides.length > 1) {
                        try {
                            const r0 = slides[0].getBoundingClientRect();
                            const r1 = slides[1].getBoundingClientRect();
                            step = Math.abs(r1.left - r0.left);
                        } catch (e) {
                            try {
                                const cs = window.getComputedStyle(slides[0]);
                                const ml = parseFloat(cs.marginLeft) || 0;
                                const mr = parseFloat(cs.marginRight) || 0;
                                step = slideW + ml + mr;
                            } catch (er) { step = slideW; }
                        }
                    } else {
                        try {
                            const cs = window.getComputedStyle(slides[0]);
                            const ml = parseFloat(cs.marginLeft) || 0;
                            const mr = parseFloat(cs.marginRight) || 0;
                            step = slideW + ml + mr;
                        } catch (e) { step = slideW; }
                    }
                    const tx = idx * step;
                    wrapper.style.transform = `translate3d(${stylenaIsRtl() ? '' : '-'}${tx}px,0,0)`;
                    wrapper.style.transition = 'transform 0.4s ease-in-out';
                    const vis = visible();
                    const max = Math.max(0, slides.length - vis);
                    const prevDisabled = idx === 0;
                    const nextDisabled = idx >= max;
                    if (prevBtn) { prevBtn.classList.toggle('swiper-button-disabled', prevDisabled); prevBtn.disabled = prevDisabled; }
                    if (nextBtn) { nextBtn.classList.toggle('swiper-button-disabled', nextDisabled); nextBtn.disabled = nextDisabled; }
                    if (mobilePrev) { mobilePrev.classList.toggle('swiper-button-disabled', prevDisabled); mobilePrev.disabled = prevDisabled; }
                    if (mobileNext) { mobileNext.classList.toggle('swiper-button-disabled', nextDisabled); mobileNext.disabled = nextDisabled; }
                }

                function next() { const v = visible(); const max = Math.max(0, slides.length - v); if (idx < max) { idx++; update(); if (container.dataset.debugSlider === 'true') console.log('services next', { idx }); } }
                function prev() { if (idx > 0) { idx--; update(); if (container.dataset.debugSlider === 'true') console.log('services prev', { idx }); } }

                [prevBtn, mobilePrev].forEach(b => { if (b) b.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); prev(); }); });
                [nextBtn, mobileNext].forEach(b => { if (b) b.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); next(); }); });

                // touch
                let sx = 0, cx = 0, dragging = false;
                swiper.addEventListener('touchstart', e => { sx = e.touches[0].clientX; dragging = true; }, { passive: true });
                swiper.addEventListener('touchmove', e => { if (!dragging) return; cx = e.touches[0].clientX; const d = Math.abs(cx - sx); if (d > 10) e.preventDefault(); }, { passive: false });
                swiper.addEventListener('touchend', e => { if (!dragging) return; dragging = false; const dx = cx - sx; if (Math.abs(dx) > 50) { if ((dx > 0) !== stylenaIsRtl()) prev(); else next(); } });
                stylenaBindMouseDrag(swiper, prev, next);

                // Intentionally not listening to resize events for the services slider.
                // This keeps behavior consistent with the portfolio slider and prevents
                // layout/visibility issues triggered by mobile browser resizes while scrolling.

                // Re-fit slides when the width changes (rotation, window resize); height-only resizes are ignored.
                let lastWidth = window.innerWidth;
                window.addEventListener('resize', function () {
                    if (window.innerWidth === lastWidth) return;
                    lastWidth = window.innerWidth;
                    idx = Math.min(idx, Math.max(0, slides.length - visible()));
                    update();
                });

                update();
            })();

        }, 200);
    });

})();

(function () {
    'use strict';

    // Home portfolio slider: same stepping behaviour as the Services and Team sliders.
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            const container = document.querySelector('.stylena-portfolio-home');
            if (!container) return;

            const swiper = container.querySelector('.stylena-portfolio-swiper');
            const wrapper = container.querySelector('.stylena-portfolio-swiper .swiper-wrapper');
            const slides = container.querySelectorAll('.stylena-portfolio-swiper .swiper-slide');
            const prevBtns = container.querySelectorAll('.swiper-button-prev');
            const nextBtns = container.querySelectorAll('.swiper-button-next');
            if (!swiper || !wrapper || slides.length === 0) return;

            let idx = 0;
            const visible = function () { const w = window.innerWidth; if (w >= 1200) return 3; if (w >= 768) return 2; return 1; };
            const maxIdx = function () { return Math.max(0, slides.length - visible()); };

            let lastWidth = window.innerWidth;
            const update = function () {
                stylenaSizeSlides(swiper, Array.from(slides), visible());
                // Slides outside the visible window are inert so keyboard focus never lands on hidden cards.
                slides.forEach(function (slide, i) {
                    const inView = i >= idx && i < idx + visible();
                    if (inView) { slide.removeAttribute('inert'); slide.removeAttribute('aria-hidden'); }
                    else { slide.setAttribute('inert', ''); slide.setAttribute('aria-hidden', 'true'); }
                });
                let step = slides[0].getBoundingClientRect().width || slides[0].offsetWidth;
                if (slides.length > 1) {
                    step = Math.abs(slides[1].getBoundingClientRect().left - slides[0].getBoundingClientRect().left) || step;
                }
                wrapper.style.transform = 'translate3d(' + (stylenaIsRtl() ? '' : '-') + (idx * step) + 'px,0,0)';
                wrapper.style.transition = stylenaReducedMotion.matches ? 'none' : 'transform 0.4s ease-in-out';
                prevBtns.forEach(function (b) { b.classList.toggle('swiper-button-disabled', idx === 0); b.disabled = idx === 0; });
                nextBtns.forEach(function (b) { const off = idx >= maxIdx(); b.classList.toggle('swiper-button-disabled', off); b.disabled = off; });
            };
            const next = function () { if (idx < maxIdx()) { idx++; update(); } };
            const prev = function () { if (idx > 0) { idx--; update(); } };

            prevBtns.forEach(function (b) { b.addEventListener('click', function (e) { e.preventDefault(); prev(); }); });
            nextBtns.forEach(function (b) { b.addEventListener('click', function (e) { e.preventDefault(); next(); }); });

            let sx = 0, cx = 0, dragging = false;
            swiper.addEventListener('touchstart', function (e) { sx = cx = e.touches[0].clientX; dragging = true; }, { passive: true });
            swiper.addEventListener('touchmove', function (e) { if (dragging) { cx = e.touches[0].clientX; } }, { passive: true });
            swiper.addEventListener('touchend', function () { if (!dragging) return; dragging = false; const dx = cx - sx; if (Math.abs(dx) > 50) { if ((dx > 0) !== stylenaIsRtl()) prev(); else next(); } });
            stylenaBindMouseDrag(swiper, prev, next);

            // Re-fit and re-clamp when the width changes (rotation, window resize).
            window.addEventListener('resize', function () {
                if (window.innerWidth === lastWidth) return;
                lastWidth = window.innerWidth;
                idx = Math.min(idx, maxIdx());
                update();
            });

            update();
        }, 200);
    });
})();

(function () {
    if (typeof jQuery === 'undefined') return;
    jQuery(document).ready(function ($) {
        'use strict';

        if ($('.faq-question').length === 0) return;

        // .faq-question is a real <button>: Enter/Space fire click natively.
        // aria-expanded is kept in sync when the panel starts to open/close.
        $('.faq-question').on('click', function (e) {
            e.preventDefault();
            var $target = $('#' + $(this).attr('aria-controls'));
            $target.collapse('toggle');
        });

        $('.faq-item .collapse').on('show.bs.collapse hide.bs.collapse', function (e) {
            var expanded = e.type === 'show';
            $('#' + this.id + '-btn').attr('aria-expanded', expanded ? 'true' : 'false');
        });

        if (window.location.hash === '#faq' || window.location.hash === '#faqs') {
            var $faqSection = $('.faq-section');
            if ($faqSection.length) {
                $('html, body').animate({
                    scrollTop: $faqSection.offset().top - 100
                }, stylenaReducedMotion.matches ? 0 : 800);
            }
        }

    });
})();

(function () {
    'use strict';

    if (document.querySelectorAll('.homepage-header, .services-swiper, .hero-video, .house-card').length === 0) {
        return;
    }

    document.addEventListener('DOMContentLoaded', function () {

        const header = document.querySelector('.homepage-header');
        if (header) {
            window.addEventListener('scroll', function () {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        }

        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') return;
                // Skip link and ARIA-button toggles keep their native behaviour.
                if (this.classList.contains('skip-link') || this.getAttribute('role') === 'button') return;
                if (!href.startsWith('#')) return;
                e.preventDefault();

                const targetId = href.substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    const headerHeight = header ? header.offsetHeight : 0;
                    const targetPosition = targetElement.offsetTop - headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: stylenaReducedMotion.matches ? 'auto' : 'smooth'
                    });
                }
            });
        });

        // Hero background video: poster-first. The <source> is attached lazily (after window load) and
        // never on mobile widths, Save-Data, 2g/slow-2g connections or for reduced-motion users:
        // in those cases only the poster is shown and the mp4 is never requested.
        const heroVideo = document.querySelector('.hero-video');

        if (heroVideo) {
            const heroSource = heroVideo.querySelector('source[data-src]');
            const heroConnection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            const heroMobileMq = window.matchMedia ? window.matchMedia('(max-width: 768px)') : { matches: false };
            const heroSlow = !!(heroConnection && (heroConnection.saveData || /(^|-)2g$/.test(heroConnection.effectiveType || '')));
            const heroDefer = heroMobileMq.matches || heroSlow || stylenaReducedMotion.matches;

            heroVideo.removeAttribute('autoplay');
            heroVideo.preload = 'none';

            heroVideo.addEventListener('playing', function () {
                heroVideo.classList.add('is-playing');
            });

            // Hide a broken video so the poster stays visible.
            heroVideo.addEventListener('error', function () {
                heroVideo.style.display = 'none';
                const failedToggle = document.querySelector('.hero-video-toggle');
                if (failedToggle) { failedToggle.remove(); }
            }, true);

            let heroUserPaused = false;

            // WCAG 2.2.2: moving content that lasts > 5s needs a pause control.
            const addHeroToggle = function () {
                const host = heroVideo.closest('section') || heroVideo.parentNode;
                if (!host || host.querySelector('.hero-video-toggle')) return;
                const labels = (window.stylenaA11y && window.stylenaA11y.hero) || {};
                const pauseLabel = labels.pause || 'Pause background video';
                const playLabel = labels.play || 'Play background video';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'hero-video-toggle';
                btn.setAttribute('aria-label', pauseLabel);
                btn.innerHTML = '<i class="fas fa-pause" aria-hidden="true"></i>';
                btn.addEventListener('click', function () {
                    heroUserPaused = !heroVideo.paused;
                    if (heroUserPaused) {
                        heroVideo.pause();
                    } else {
                        const p = heroVideo.play();
                        if (p && p.catch) { p.catch(function () { }); }
                    }
                    btn.setAttribute('aria-label', heroUserPaused ? playLabel : pauseLabel);
                    btn.firstChild.className = heroUserPaused ? 'fas fa-play' : 'fas fa-pause';
                });
                host.appendChild(btn);
            };

            // Only offer the control once the video is really playing, so its state is never wrong.
            heroVideo.addEventListener('playing', addHeroToggle);

            heroVideo.addEventListener('canplay', function () {
                if (heroUserPaused) return;
                const p = heroVideo.play();
                if (p && p.catch) { p.catch(function () { }); }
            });

            if (!heroDefer && heroSource) {
                const startHeroVideo = function () {
                    if (heroSource.hasAttribute('src')) return;
                    heroSource.setAttribute('src', heroSource.getAttribute('data-src'));
                    heroVideo.load();
                };
                if (document.readyState === 'complete') {
                    startHeroVideo();
                } else {
                    window.addEventListener('load', startHeroVideo);
                }
            }
        }

        const mobileToggle = document.querySelector('.mobile-menu-toggle .navbar-toggler');
        const mobileNav = document.querySelector('#mobile-nav');

        if (mobileToggle && mobileNav) {
            mobileToggle.addEventListener('click', function () {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);

                if (mobileNav.classList.contains('show')) {
                    mobileNav.classList.remove('show');
                } else {
                    mobileNav.classList.add('show');
                }
            });

            const mobileLinks = mobileNav.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function () {
                    mobileNav.classList.remove('show');
                    mobileToggle.setAttribute('aria-expanded', 'false');
                });
            });
        }

        const houseCards = document.querySelectorAll('.house-card');
        houseCards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-10px)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });

        // Generic slider block removed: services and portfolio have scoped handlers below

        function lazyLoadImages() {
            const images = document.querySelectorAll('img[data-src]');
            if (images.length === 0) return;

            const imageObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', lazyLoadImages);
        } else {
            lazyLoadImages();
        }

    });

})();

(function () {
    'use strict';

    if (document.querySelectorAll('.stylena-image-slider').length === 0) return;

    class StylenaImageSlider {
        constructor(element) {
            this.slider = element;
            this.wrapper = this.slider.querySelector('.slider-wrapper');
            this.originalSlides = Array.from(this.wrapper.querySelectorAll('.slider-slide'));

            this.autoplay = this.slider.dataset.autoplay !== 'false' && !stylenaReducedMotion.matches;
            this.autoplaySpeed = parseInt(this.slider.dataset.autoplaySpeed) || 4000;
            const sd = parseInt(this.slider.dataset.stopDuration);
            // For reliability on mobile and when the slider goes out/in of view,
            // we intentionally disable the automatic "stop/pause after each slide" behavior
            // so the slider remains active and visible at all times.
            // Keep the raw value for reference but force runtime behavior to continuous.
            this._configuredStopDuration = (isNaN(sd) || sd <= 0) ? 1000 : sd;
            this.stopDurationMs = 0; // disable auto stop
            this.slidesToShow = this.getSlidesToShow();

            this.slides = [];
            this.realTotal = this.originalSlides.length;
            this.k = this.slidesToShow;
            this.slideWidth = 0;
            this.translate = 0;
            this.startTranslate = 0;
            this.rafId = null;
            this.lastTime = null;
            // Remove pause-related flags entirely so the slider cannot be paused
            this.distanceSinceLastStop = 0;
            this.isStepMode = false; // continuous loop
            this.stepTimeout = null;
            this.stepAnimating = false;

            this.init();
        }

        // Track last window width to avoid handling height-only resizes (mobile address bar)
        // which can fire 'resize' during vertical scrolling.
        _lastWindowWidth = null;

        getSlidesToShow() {
            const width = window.innerWidth;
            if (width <= 480) return 1;
            if (width <= 768) return 2;
            return 3;
        }

        init() {
            if (this.realTotal === 0) return;
            this.buildLoopedStructure();
            this.updateMeasurements();
            this.addLeftBuffer();
            this.bindEvents();
            this.setInitialPosition();
            if (this.autoplay) {
                if (this.isStepMode) this.startStepLoop(); else this.startLoop();
                this.addPauseControl();
            }
        }

        // WCAG 2.2.2: moving content that lasts > 5s needs a pause control.
        addPauseControl() {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'slider-pause-toggle';
            const labels = (window.stylenaA11y && window.stylenaA11y.slider) || {};
            const pauseLabel = labels.pause || 'Pause slider';
            const playLabel = labels.play || 'Play slider';
            btn.setAttribute('aria-label', pauseLabel);
            btn.innerHTML = '<i class="fas fa-pause" aria-hidden="true"></i>';
            btn.addEventListener('click', () => {
                const paused = btn.getAttribute('aria-label') === playLabel;
                if (paused) {
                    this.startLoop();
                } else if (this.rafId) {
                    cancelAnimationFrame(this.rafId);
                    this.rafId = null;
                }
                btn.setAttribute('aria-label', paused ? pauseLabel : playLabel);
                btn.firstChild.className = paused ? 'fas fa-pause' : 'fas fa-play';
            });
            this.slider.appendChild(btn);
        }

        buildLoopedStructure() {
            const k = this.slidesToShow;
            // Build a single sequence of original slides and append the first k slides at the end
            // We'll recycle DOM nodes as the slider moves instead of keeping clones before/after
            this.wrapper.innerHTML = '';
            // Append originals
            this.originalSlides.forEach(n => {
                const node = n.cloneNode(true);
                node.setAttribute('data-slide-index', this.originalSlides.indexOf(n));
                this.wrapper.appendChild(node);
            });

            // If there are more slides than visible, append first k clones at the end to allow smooth start
            if (this.realTotal > k) {
                for (let i = 0; i < k; i++) {
                    const idx = i % this.realTotal;
                    const node = this.originalSlides[idx].cloneNode(true);
                    node.setAttribute('data-slide-index', idx);
                    node.classList.add('clone');
                    this.wrapper.appendChild(node);
                }
                // nextAppendIndex points to the next original slide index to append when recycling
                this.nextAppendIndex = k % this.realTotal;
            } else {
                this.nextAppendIndex = this.realTotal % this.realTotal; // 0
            }

            this.slides = Array.from(this.wrapper.querySelectorAll('.slider-slide'));
            this.realTotal = this.originalSlides.length;
        }

        // Prepend enough slides to fill the empty margin left of the first visible slide
        // (the track is centred, so without these the left edge shows a blank gap).
        addLeftBuffer() {
            if (!this.slideWidth || this.realTotal === 0) return;
            const needed = Math.max(1, Math.ceil(Math.max(0, this.startTranslate) / this.slideWidth));
            for (let i = needed; i >= 1; i--) {
                const idx = (((this.realTotal - i) % this.realTotal) + this.realTotal) % this.realTotal;
                const node = this.originalSlides[idx].cloneNode(true);
                node.setAttribute('data-slide-index', idx);
                node.classList.add('clone');
                this.wrapper.insertBefore(node, this.wrapper.firstChild);
                try { this.loadSlideMedia(node); } catch (e) { /* fail silently */ }
            }
            this.bufferCount = needed;
            this.slides = Array.from(this.wrapper.querySelectorAll('.slider-slide'));
            this.updateMeasurements();
        }

        updateMeasurements() {
            if (!this.slides || this.slides.length === 0) return;
            this.slideWidth = this.slides[0].getBoundingClientRect().width;
            this.k = this.getSlidesToShow();
            const containerRect = this.slider.getBoundingClientRect();
            const containerWidth = containerRect.width || window.innerWidth;
            const cs = window.getComputedStyle(this.slider);
            const paddingLeft = parseFloat(cs.paddingLeft) || 0;
            const paddingRight = parseFloat(cs.paddingRight) || 0;
            const containerInnerWidth = Math.max(0, containerWidth - paddingLeft - paddingRight);
            const wrapperStyle = window.getComputedStyle(this.wrapper);
            const gap = parseFloat(wrapperStyle.gap) || 0;
            const visibleWidth = this.k * this.slideWidth + Math.max(0, (this.k - 1) * gap);
            const centerOffset = paddingLeft + (containerInnerWidth - visibleWidth) / 2;
            let mobileShift = 0;
            try { if (window.innerWidth <= 480) { mobileShift = containerInnerWidth * 0.05; } } catch (e) { mobileShift = 0; }
            // With recycling approach we start at the beginning of the original sequence
            // and center the visible slides inside the container
            // Left buffer slides sit before the first visible slide, so the wrapper starts earlier.
            this.startTranslate = centerOffset - mobileShift - (this.bufferCount || 0) * this.slideWidth;
            this.speedPxPerMs = this.slideWidth / (this.autoplaySpeed || 1000);
        }

        setInitialPosition() {
            this.translate = this.startTranslate;
            this.wrapper.style.transition = 'none';
            this.wrapper.style.transform = `translateX(${this.translate}px)`;
            void this.wrapper.offsetWidth;
        }

        // Remove first slide(s) that have moved past the left edge and append next slides at the end
        recycleIfNeeded() {
            // Only recycle in situations where we have at least one full original loop present
            if (!this.slideWidth || this.realTotal === 0) return;

            // While we've moved at least one slide width beyond the start, recycle one slide
            while (this.translate <= this.startTranslate - this.slideWidth - 0.5) {
                // Remove first child
                const first = this.wrapper.firstElementChild;
                if (!first) break;
                this.wrapper.removeChild(first);

                // Append next slide by numeric index
                const idx = this.nextAppendIndex % this.realTotal;
                const node = this.originalSlides[idx].cloneNode(true);
                node.setAttribute('data-slide-index', idx);
                node.classList.add('recycled');
                this.wrapper.appendChild(node);

                // Ensure any lazy media on the newly appended/recycled node is loaded
                try { this.loadSlideMedia(node); } catch (e) { /* fail silently */ }

                // Advance the pointer
                this.nextAppendIndex = (this.nextAppendIndex + 1) % this.realTotal;

                // Compensate translate so content doesn't jump (we removed one slide width)
                this.translate += this.slideWidth;
                // Apply the immediate transform without transition
                this.wrapper.style.transition = 'none';
                this.wrapper.style.transform = `translateX(${this.translate}px)`;
                void this.wrapper.offsetWidth;
            }

            // Refresh slides reference
            this.slides = Array.from(this.wrapper.querySelectorAll('.slider-slide'));
        }

        // Ensure images, videos or background images on a slide node are loaded immediately
        loadSlideMedia(node) {
            if (!node) return;

            // 1) Background-image block: .slide-image with inline style or data-src attribute
            const bg = node.querySelector('.slide-image');
            if (bg) {
                // If we used data-bg-src to defer loading in markup, move it to style
                const dataBg = bg.getAttribute('data-bg-src');
                if (dataBg && (!bg.style.backgroundImage || bg.style.backgroundImage === '')) {
                    bg.style.backgroundImage = `url(${dataBg})`;
                    bg.removeAttribute('data-bg-src');
                }
            }

            // 2) <img data-src> lazy images
            const imgs = node.querySelectorAll('img[data-src]');
            imgs.forEach(img => {
                try {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                } catch (e) { }
            });

            // 3) <video data-src>
            const videos = node.querySelectorAll('video[data-src]');
            videos.forEach(video => {
                try {
                    video.src = video.dataset.src;
                    video.removeAttribute('data-src');
                    // attempt to load so posters/first frames are ready
                    if (typeof video.load === 'function') video.load();
                } catch (e) { }
            });
        }

        bindEvents() {
            // Intentionally do not bind to window.resize or scroll events.
            // The slider is designed to be "dumb" and keep running continuously
            // without reacting to mobile address-bar resizes or scroll-triggered resizes
            // which historically caused images to disappear. If consumers need resize
            // recalculation they can call slider.handleResize() directly.
            this._boundHandleResize = () => this.handleResize();
            // NOTE: Not attaching the resize listener by design.
        }

        startLoop() { if (this.rafId) return; this.lastTime = performance.now(); this.rafId = requestAnimationFrame((t) => this.tick(t)); }
        startStepLoop() { if (this.stepTimeout || this.stepAnimating) return; this.scheduleNextStep(0); }
        scheduleNextStep(delay) { if (this.stepTimeout) clearTimeout(this.stepTimeout); this.stepTimeout = setTimeout(() => this.performStep(), delay); }

        performStep() {
            if (!this.autoplay) return;
            this.stepAnimating = true;
            const durationMs = this.autoplaySpeed || 400;
            this.wrapper.style.transition = `transform ${durationMs}ms ease-in-out`;
            this.translate -= this.slideWidth;
            this.wrapper.style.transform = `translateX(${this.translate}px)`;
            const onTransitionEnd = () => {
                this.wrapper.removeEventListener('transitionend', onTransitionEnd);
                // Recycle DOM nodes for any slides that moved past the left edge
                this.recycleIfNeeded();
                this.stepAnimating = false;
                this.stepTimeout = setTimeout(() => {
                    this.wrapper.style.transition = 'none';
                    this.scheduleNextStep(0);
                }, Math.max(0, this.stopDurationMs || 0));
            };
            this.wrapper.addEventListener('transitionend', onTransitionEnd);
        }
        // Removed pauseLoop/resumeLoop entirely to keep slider always running

        tick(now) {
            const dt = now - this.lastTime;
            this.lastTime = now;
            const deltaPx = this.speedPxPerMs * dt;
            this.translate -= deltaPx;
            // Continuous movement; no auto-pausing behavior.
            // If we've moved past at least one slide, recycle DOM nodes to keep the buffer
            this.recycleIfNeeded();
            this.wrapper.style.transform = `translateX(${this.translate}px)`;
            this.rafId = requestAnimationFrame((t) => this.tick(t));
        }

        handleResize() {
            // Debounce frequent resize events
            clearTimeout(this.resizeTimer);
            this.resizeTimer = setTimeout(() => {
                try {
                    const currentWidth = window.innerWidth;
                    // If width hasn't changed, assume this was a height-only resize
                    // (common on mobile when the address bar shows/hides while scrolling)
                    if (this._lastWindowWidth !== null && this._lastWindowWidth === currentWidth) {
                        return;
                    }
                    this._lastWindowWidth = currentWidth;

                    const prevLoopLength = this.realTotal * this.slideWidth || 1;
                    this.updateMeasurements();
                    const loopLength = this.realTotal * this.slideWidth;
                    let offset = this.translate - this.startTranslate;
                    offset = ((offset % loopLength) + loopLength) % loopLength;
                    this.translate = this.startTranslate + offset;
                    this.wrapper.style.transition = 'none';
                    this.wrapper.style.transform = `translateX(${this.translate}px)`;
                } catch (e) {
                    // Fail silently
                }
            }, 200);
        }

        destroy() {
            // Stop RAF loop if running
            try {
                if (this.rafId) {
                    cancelAnimationFrame(this.rafId);
                    this.rafId = null;
                }
            } catch (e) { }

            // Clear any step timeout or resize debounce timers
            try { if (this.stepTimeout) { clearTimeout(this.stepTimeout); this.stepTimeout = null; } } catch (e) { }
            try { if (this.resizeTimer) { clearTimeout(this.resizeTimer); this.resizeTimer = null; } } catch (e) { }

            // Remove bound resize handler
            try { if (this._boundHandleResize) { window.removeEventListener('resize', this._boundHandleResize); this._boundHandleResize = null; } } catch (e) { }

            // Ensure wrapper has no active transition
            try { if (this.wrapper) { this.wrapper.style.transition = 'none'; } } catch (e) { }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sliders = document.querySelectorAll('.stylena-image-slider');
        sliders.forEach(slider => { new StylenaImageSlider(slider); });
    });

    window.StylenaImageSlider = StylenaImageSlider;

})();


/**
 * Portfolio page: "Load more" reveals portfolio cards that the server already
 * rendered (hidden). No network request, no token, no nonce.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('load-more-portfolio');
        if (!btn) {
            return;
        }
        var step = parseInt(btn.getAttribute('data-step'), 10) || 12;

        btn.addEventListener('click', function () {
            var hidden = document.querySelectorAll('.portfolio-grid .stylena-portfolio-extra[hidden]');
            for (var i = 0; i < hidden.length && i < step; i++) {
                hidden[i].removeAttribute('hidden');
                hidden[i].classList.remove('stylena-portfolio-extra');
            }
            if (document.querySelectorAll('.portfolio-grid .stylena-portfolio-extra[hidden]').length === 0) {
                btn.style.display = 'none';
            }
        });
    });
})();
