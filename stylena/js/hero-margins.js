// Hero Section Responsive Margin Handler
document.addEventListener('DOMContentLoaded', function () {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;

    const desktopMargin = heroSection.getAttribute('data-desktop-margin') || '120px';
    const mobileMargin = heroSection.getAttribute('data-mobile-margin') || '80px';

    function applyResponsiveMargins() {
        const isSmall = window.innerWidth < 768;
        const isMiddle = window.innerWidth >= 768 && window.innerWidth <= 1024;
        const margin = isSmall ? mobileMargin : isMiddle ? '9.5rem' : desktopMargin;

        heroSection.style.marginTop = margin;

        // Also update height to account for margin
        const heroVideoContainer = heroSection.querySelector('.hero-video-container');
        if (heroVideoContainer) {
            const baseHeight = isSmall ? '50vh' : isMiddle ? '60vh' : '100vh';
            heroSection.style.height = `calc(${baseHeight} - ${margin})`;
            heroVideoContainer.style.height = `calc(${baseHeight} - ${margin})`;
        }
    }

    // Apply margins on load
    applyResponsiveMargins();

    // Apply margins on resize
    let resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(applyResponsiveMargins, 100);
    });
});
