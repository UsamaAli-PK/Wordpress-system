/**
 * Agency Scroll To Top - Native Vanilla ES6 (Zero jQuery)
 */
(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('agency-scroll-top');
        if (!btn) return;

        const threshold = 300;
        let ticking = false;

        const checkScroll = () => {
            if (window.scrollY > threshold) {
                btn.removeAttribute('hidden');
                btn.classList.add('is-visible');
            } else {
                btn.classList.remove('is-visible');
                // wait for transition before setting hidden
                setTimeout(() => {
                    if (window.scrollY <= threshold) {
                        btn.setAttribute('hidden', '');
                    }
                }, 250);
            }
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(checkScroll);
                ticking = true;
            }
        }, { passive: true });

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({
                top: 0,
                behavior: prefersReduced ? 'auto' : 'smooth'
            });
            btn.blur();
        });
    });
})();
