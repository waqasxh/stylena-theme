/**
 * Header behaviour: slide-down search, scroll state, mobile navigation dialog.
 * Moved out of header.php so it is cacheable and CSP-friendly.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Publish the real fixed-header height so pages can reserve exactly that space (see custom.css).
    var siteHeader = document.querySelector('.homepage-header');
    function setHeaderHeight() {
        if (siteHeader && siteHeader.offsetHeight) {
            document.documentElement.style.setProperty('--stylena-header-h', siteHeader.offsetHeight + 'px');
        }
    }
    setHeaderHeight();
    window.addEventListener('load', setHeaderHeight);
    window.addEventListener('resize', setHeaderHeight);
    if (document.fonts && document.fonts.ready) { document.fonts.ready.then(setHeaderHeight); }
    if (window.ResizeObserver && siteHeader) { new ResizeObserver(setHeaderHeight).observe(siteHeader); }

    var searchBtn = document.getElementById('search-toggle-btn');
    var searchArea = document.getElementById('header-search-area');
    var headerBottom = document.querySelector('.header-bottom');
    var searchInput = searchArea ? searchArea.querySelector('input[name="s"]') : null;
    var isOpen = false;
    var hideTimer = null;

    // This variable will be used by live-search.js
    window.stylenaSearchOpen = false;

    function hideMega() {
        if (window.jQuery && window.jQuery('#stylena-mega-search').length) {
            window.jQuery('#stylena-mega-search').fadeOut(200);
        }
    }

    function openSearch() {
        clearTimeout(hideTimer);
        searchArea.style.display = 'block';
        searchArea.style.position = 'relative';
        headerBottom.insertAdjacentElement('afterend', searchArea);
        searchBtn.setAttribute('aria-expanded', 'true');
        isOpen = true;
        setTimeout(function() {
            searchArea.style.height = '25.6vh';
            searchArea.style.maxHeight = '25.6vh';
            searchArea.style.minHeight = 'unset';
            searchInput.focus();
            window.stylenaSearchOpen = true;
        }, 10);
    }

    function closeSearch(returnFocus) {
        searchArea.style.height = '0';
        window.stylenaSearchOpen = false;
        searchBtn.setAttribute('aria-expanded', 'false');
        hideMega();
        clearTimeout(hideTimer);
        hideTimer = setTimeout(function() {
            searchArea.style.display = 'none';
        }, 400);
        isOpen = false;
        if (returnFocus) {
            searchBtn.focus();
        }
    }

    if (searchBtn && searchArea && headerBottom && searchInput) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!isOpen) {
                openSearch();
            } else {
                closeSearch(false);
            }
        });

        // role="button" on a link: Space must activate it too.
        searchBtn.addEventListener('keydown', function(e) {
            if (e.key === ' ' || e.key === 'Spacebar') {
                e.preventDefault();
                searchBtn.click();
            }
        });

        // Escape closes the search area and returns focus to the toggle.
        searchArea.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeSearch(true);
            }
        });

        // Close search on outside click
        document.addEventListener('click', function(e) {
            if (isOpen && !searchArea.contains(e.target) && !searchBtn.contains(e.target)) {
                // Don't close if clicking on mega menu
                if (e.target.closest('#stylena-mega-search')) {
                    return;
                }
                closeSearch(false);
            }
        });
        // Enter submits the form natively (results page); no custom handler needed.
    }

    // Header scroll behavior
    var header = document.querySelector('.homepage-header');

    if (header) window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});

// Mobile navigation (modal dialog): focus moves in on open, Tab is trapped,
// Esc closes, focus returns to the toggle button.
function stylenaMobileNavOpen(overlay) {
    return overlay.style.display === 'block';
}

function toggleMobileNav() {
    var overlay = document.getElementById('mobile-nav-overlay');
    if (!overlay) return;
    var toggle = document.getElementById('mobile-nav-toggle');
    var closeBtn = overlay.querySelector('.mobile-nav-close');

    // We rely on inline style state because Bootstrap's .d-md-none applies
    // display:none !important (setProperty with 'important' overrides it).
    if (!stylenaMobileNavOpen(overlay)) {
        overlay.style.setProperty('display', 'block', 'important');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
        if (closeBtn) closeBtn.focus();
    } else {
        overlay.style.setProperty('display', 'none', 'important');
        document.body.style.overflow = 'auto'; // Restore scroll
        if (toggle) {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.focus();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('mobile-nav-overlay');
    if (!overlay) return;

    // Open/close buttons (bound here instead of inline onclick, so a strict CSP works).
    var navToggle = document.getElementById('mobile-nav-toggle');
    var navClose = overlay.querySelector('.mobile-nav-close');
    if (navToggle) navToggle.addEventListener('click', toggleMobileNav);
    if (navClose) navClose.addEventListener('click', toggleMobileNav);

    // Close mobile nav when clicking the backdrop
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            toggleMobileNav();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (!stylenaMobileNavOpen(overlay)) return;
        if (e.key === 'Escape' || e.key === 'Esc') {
            e.preventDefault();
            toggleMobileNav();
            return;
        }
        if (e.key !== 'Tab') return;
        var items = Array.prototype.filter.call(
            overlay.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'),
            function(el) { return el.offsetParent !== null; }
        );
        if (!items.length) return;
        var first = items[0];
        var last = items[items.length - 1];
        if (!overlay.contains(document.activeElement)) {
            e.preventDefault();
            first.focus();
        } else if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    });
});
