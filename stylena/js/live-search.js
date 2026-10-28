/**
 * Live Search Mega Menu - Production Version
 * Clean, minimal implementation for content search only
 * Supports both desktop and mobile search inputs
 */

jQuery(document).ready(function ($) {
    // Check if we have what we need
    if (typeof stylena_ajax === 'undefined') {
        return;
    }

    // Translatable strings (localized in PHP as stylena_ajax.i18n) with English fallbacks.
    const i18n = stylena_ajax.i18n || {};
    function t(key, fallback, arg) {
        const str = i18n[key] || fallback;
        return arg === undefined ? str : str.replace(/%[sd]/, arg);
    }

    // HTML-escape any server-provided text before it is placed in markup.
    function esc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Only allow http(s) or site-relative URLs in links.
    function safeUrl(url) {
        const u = String(url || '').trim();
        return /^(https?:\/\/|\/(?!\/))/i.test(u) ? u : '#';
    }

    // Find search inputs (both desktop and mobile)
    let $searchInputs = $('.header-search-input, .mobile-search-below-input');
    if ($searchInputs.length === 0) {
        $searchInputs = $('input[type="search"]');
    }
    if ($searchInputs.length === 0) {
        return;
    }

    // Create mega search container
    let $megaSearch = $('#stylena-mega-search');
    if ($megaSearch.length === 0) {
        $megaSearch = $('<div id="stylena-mega-search"></div>');
        $('body').append($megaSearch);
    }
    // Announce results politely to assistive technology.
    $megaSearch.attr({ 'role': 'status', 'aria-live': 'polite', 'aria-atomic': 'false' });

    let searchTimeout;
    let lastSearchTerm = '';
    let currentSearchInput = null;

    // Show mega menu
    function showMegaSearch() {
        $megaSearch.addClass('force-visible');

        if (!currentSearchInput) return;

        const $input = $(currentSearchInput);
        const isMobile = $input.hasClass('mobile-search-below-input');

        if (isMobile) {
            // Position for mobile search
            const inputRect = currentSearchInput.getBoundingClientRect();
            const mobileSearchHeader = $input.closest('.mobile-search-below-header')[0];

            if (mobileSearchHeader) {
                const headerRect = mobileSearchHeader.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const availableSpace = viewportHeight - headerRect.bottom - 20; // 20px margin

                $megaSearch.css({
                    'top': (headerRect.bottom + 5) + 'px',
                    'left': '10px',
                    'right': '10px',
                    'width': 'auto',
                    'max-width': 'calc(100vw - 20px)',
                    'max-height': Math.min(availableSpace, window.innerHeight * 0.5) + 'px' // Max 50% of viewport
                });
                $megaSearch.addClass('mobile-mega-search');
            }
        } else {
            // Position for desktop search
            const searchRect = currentSearchInput.getBoundingClientRect();
            const searchArea = $input.closest('#header-search-area')[0];

            if (searchArea) {
                const areaRect = searchArea.getBoundingClientRect();
                $megaSearch.css({
                    'top': (areaRect.bottom + 5) + 'px',
                    'left': areaRect.left + 'px',
                    'width': Math.max(areaRect.width, 400) + 'px',
                    'right': 'auto',
                    'max-width': 'none'
                });
                $megaSearch.removeClass('mobile-mega-search');
            }
        }
    }

    // Hide mega menu
    function hideMegaSearch() {
        $megaSearch.removeClass('force-visible mobile-mega-search');
    }

    // Perform search
    function performSearch(searchTerm) {
        if (searchTerm.length < 2) {
            hideMegaSearch();
            return;
        }

        if (searchTerm === lastSearchTerm) {
            return;
        }

        lastSearchTerm = searchTerm;

        // Show loading
        $megaSearch.html('<div class="search-loading">' + esc(t('searching', 'Searching...')) + '</div>');
        showMegaSearch();

        // AJAX request
        $.ajax({
            url: stylena_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'stylena_live_search',
                search_term: searchTerm,
                nonce: stylena_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    displayResults(response.data, searchTerm);
                } else {
                    $megaSearch.html('<div class="search-error">' + esc(t('failed', 'Search failed')) + '</div>');
                }
            },
            error: function () {
                $megaSearch.html('<div class="search-error">' + esc(t('connection', 'Connection error')) + '</div>');
            }
        });
    }

    // Display results
    function displayResults(data, searchTerm) {
        let html = '<div class="mega-search-container">';

        // Header
        html += '<div class="search-header">';
        html += '<h4>' + esc(t('resultsFor', 'Search Results for "%s"', searchTerm)) + '</h4>';
        html += '<button type="button" class="search-close" aria-label="' + esc(t('close', 'Close search results')) + '"><span aria-hidden="true">&times;</span></button>';
        html += '</div>';

        // Content results only
        if (data.posts && data.posts.length > 0) {
            html += '<span class="screen-reader-text">' + esc(t('found', '%d results found', data.posts.length)) + '</span>';
            html += '<div class="search-section">';
            html += '<div class="search-items">';

            data.posts.forEach(function (post) {
                // Determine URL based on post type
                let linkUrl = post.url; // default to individual post URL
                if (post.post_type === 'service') {
                    linkUrl = '/services-all'; // or whatever your main services page URL is
                } else if (post.post_type === 'team') {
                    linkUrl = '/team-all'; // or whatever your main teams page URL is
                }

                html += '<a class="search-item" href="' + esc(safeUrl(linkUrl)) + '">';

                html += '<div class="search-content">';
                html += '<h6>' + esc(post.title) + '</h6>';
                html += '<span class="search-meta">' + esc(post.post_type) + '</span>';

                if (post.excerpt) {
                    html += '<p>' + esc(post.excerpt) + '</p>';
                }

                html += '</div></a>';
            });

            html += '</div></div>';
        } else {
            // No results
            html += '<div class="search-section no-results">';
            html += '<h5>' + esc(t('noResults', 'No Results Found')) + '</h5>';
            html += '</div>';
        }

        html += '</div>';

        $megaSearch.html(html);
        showMegaSearch();
    }

    // Event handlers for all search inputs
    $searchInputs.on('input', function () {
        currentSearchInput = this;
        const searchTerm = $(this).val().trim();

        // Sync other search inputs
        $searchInputs.not(this).val(searchTerm);

        clearTimeout(searchTimeout);

        if (searchTerm.length === 0) {
            hideMegaSearch();
            return;
        }

        searchTimeout = setTimeout(function () {
            performSearch(searchTerm);
        }, 300);
    });

    // Focus handlers to set current input
    $searchInputs.on('focus', function () {
        currentSearchInput = this;
    });

    // Forms submit normally (Enter / submit button) so users can reach the full results page.


    // Make sure search result links work by stopping event bubbling
    $(document).on('click', '#stylena-mega-search a', function (e) {
        e.stopPropagation();
        // Let the link work normally
    });

    // Close handlers
    $(document).on('click', '.search-close', function () {
        hideMegaSearch();
        if (currentSearchInput) {
            currentSearchInput.focus();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            const wasOpen = $megaSearch.hasClass('force-visible');
            hideMegaSearch();
            if (wasOpen && currentSearchInput && $megaSearch.has(document.activeElement).length) {
                currentSearchInput.focus();
            }
            // Optional: clear search inputs on escape
            // $searchInputs.val('');
        }
    });

    $(document).click(function (e) {
        if (!$searchInputs.is(e.target) && !$megaSearch.is(e.target) && $megaSearch.has(e.target).length === 0) {
            hideMegaSearch();
        }
    });

    // Handle window resize to reposition mega menu
    $(window).on('resize', function () {
        if ($megaSearch.hasClass('force-visible') && currentSearchInput) {
            showMegaSearch(); // Reposition on resize
        }
    });
});
