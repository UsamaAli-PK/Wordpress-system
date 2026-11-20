/**
 * Travelin' Elementor Widgets Frontend Interactions
 * Handles Trips Tabs, Experience Video, Stories Audio, FAQ Accordions, and Google Reviews Lightbox
 */

(function ($) {
    'use strict';

    // -------------------------------------------------------------------------
    // 0. Lenis smooth scroll — kick off once per page.
    // -------------------------------------------------------------------------
    function initLenis() {
        if (window.__tlLenis || typeof window.Lenis !== 'function') return;
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduced) return;
        const lenis = new window.Lenis({
            duration: 1.65,
            smoothWheel: true,
            touchMultiplier: 1,
            syncTouch: false,
            easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); }
        });
        window.__tlLenis = lenis;
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);
        document.documentElement.classList.add('tl-lenis-active');
    }

    // -------------------------------------------------------------------------
    // Hero — scroll-linked video playback (scrub through door-journey clip)
    // -------------------------------------------------------------------------
    function initHeroScrubVideo($scope) {
        const scope = $scope[0] || document;
        const section = scope.classList && scope.classList.contains('travelin-hero')
            ? scope
            : scope.querySelector('.travelin-hero');
        if (!section) return;
        const video = section.querySelector('.travelin-hero__film');
        if (!video) return;

        video.autoplay = false;
        video.loop = false;
        video.pause();

        let duration = 4.75; // sensible default; replaced on loadedmetadata
        let wanted = 0;
        let lastSeek = 0;
        video.addEventListener('loadedmetadata', function () {
            duration = Math.max(duration, video.duration || duration);
        });

        // Unlock video decoding on first user interaction (mobile Safari)
        let unlocked = false;
        function unlock() {
            if (unlocked) return;
            unlocked = true;
            const p = video.play();
            if (p && p.then) p.then(function () { video.pause(); }).catch(function () {});
        }
        ['touchstart', 'pointerdown', 'scroll'].forEach(function (evt) {
            window.addEventListener(evt, unlock, { passive: true, once: true });
        });

        function seek(t) {
            const max = Math.max(0, duration - 0.15);
            const target = Math.max(0, Math.min(t, max));
            if (Math.abs(video.currentTime - target) < 0.04) return;
            const now = performance.now();
            if (video.seeking && now - lastSeek < 160) return;
            lastSeek = now;
            if (typeof video.fastSeek === 'function') {
                try { video.fastSeek(target); return; } catch (e) {}
            }
            video.currentTime = target;
        }

        function onScroll() {
            const rect = section.getBoundingClientRect();
            const scrollable = section.offsetHeight - window.innerHeight;
            const progressed = -rect.top;
            let progress = progressed / Math.max(1, scrollable);
            if (progress < 0) progress = 0;
            if (progress > 1) progress = 1;
            wanted = progress * duration;
            seek(wanted);
            section.style.setProperty('--hero-progress', progress.toFixed(3));
        }

        let rafId = 0;
        function schedule() {
            if (rafId) return;
            rafId = requestAnimationFrame(function () { rafId = 0; onScroll(); });
        }
        window.addEventListener('scroll', schedule, { passive: true });
        window.addEventListener('resize', schedule);
        schedule();
    }

    // -------------------------------------------------------------------------
    // 1. Trips Destination Tabs & Carousel
    // -------------------------------------------------------------------------
    function initTripsCarousel($scope) {
        const $tabs = $scope.find('.travelin-trips__tab-btn, .trip-tabs button');
        const $cards = $scope.find('.travelin-trip-card, .trip-card');
        const $details = $scope.find('.travelin-trip-detail-pane');

        if (!$tabs.length) return;

        $tabs.on('click', function (e) {
            e.preventDefault();
            const index = $(this).index();

            $tabs.removeClass('is-active active').attr('aria-pressed', 'false');
            $(this).addClass('is-active active').attr('aria-pressed', 'true');

            if ($cards.length) {
                $cards.removeClass('selected is-active').attr('aria-hidden', 'true');
                $cards.eq(index).addClass('selected is-active').attr('aria-hidden', 'false');
            }

            if ($details.length) {
                $details.hide().removeClass('is-active');
                $details.eq(index).fadeIn(250).addClass('is-active');
            }
        });

        // Prev/Next carousel buttons
        const $prevBtn = $scope.find('.carousel-prev-btn, .carousel-controls button:first-child');
        const $nextBtn = $scope.find('.carousel-next-btn, .carousel-controls button:last-child');

        function advance(delta) {
            const currentIdx = $tabs.filter('.is-active, .active').index();
            const count = $tabs.length;
            const nextIdx = (currentIdx + delta + count) % count;
            $tabs.eq(nextIdx).trigger('click');
        }

        $prevBtn.on('click', function (e) {
            e.preventDefault();
            advance(-1);
        });

        $nextBtn.on('click', function (e) {
            e.preventDefault();
            advance(1);
        });
    }

    // -------------------------------------------------------------------------
    // 2. Experience Video Player
    // -------------------------------------------------------------------------
    function initExperienceVideo($scope) {
        const $container = $scope.find('.travelin-experience-film, .experience-film');
        const video = $container.find('video.film-poster')[0];
        const $playBtn = $container.find('.film-play');
        const $ambientBtn = $container.find('.ambient-control');
        const $frame = $container.find('.film-frame');

        if (!video) return;

        function togglePlayback() {
            if (video.paused) {
                video.muted = false;
                video.play().then(function () {
                    $frame.addClass('film-watching');
                }).catch(function () {});
            } else {
                video.pause();
                $frame.removeClass('film-watching');
            }
        }

        $playBtn.on('click', function (e) {
            e.stopPropagation();
            togglePlayback();
        });

        $frame.on('click', function () {
            if ($frame.hasClass('film-watching')) {
                togglePlayback();
            }
        });

        $ambientBtn.on('click', function (e) {
            e.stopPropagation();
            video.muted = !video.muted;
            $(this).toggleClass('is-muted', video.muted);
        });

        // IntersectionObserver to pause when out of view
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting && !video.paused) {
                        video.pause();
                        video.muted = true;
                        $frame.removeClass('film-watching');
                    }
                });
            }, { threshold: 0.25 });

            observer.observe(video);
        }
    }

    // -------------------------------------------------------------------------
    // 3. Story Reels Video Sound Toggle
    // -------------------------------------------------------------------------
    function initStoryReels($scope) {
        const $cards = $scope.find('.travelin-story-card, .story-card');
        if (!$cards.length) return;

        $cards.each(function (idx, el) {
            const $card = $(el);
            const video = $card.find('video')[0];
            const $soundToggle = $card.find('.travelin-story-sound-toggle, .story-sound-toggle');

            if (!video) return;

            function toggleSound() {
                const isCurrentlyMuted = video.muted;
                // Mute all other story videos
                $cards.each(function () {
                    const otherVideo = $(this).find('video')[0];
                    if (otherVideo && otherVideo !== video) {
                        otherVideo.muted = true;
                        $(this).find('.travelin-story-sound-toggle, .story-sound-toggle').removeClass('is-active active');
                    }
                });

                video.muted = !isCurrentlyMuted;
                if (!video.muted) {
                    video.play().catch(function () {});
                }
                $soundToggle.toggleClass('is-active active', !video.muted);
            }

            $card.on('click', function () {
                toggleSound();
            });

            $soundToggle.on('click', function (e) {
                e.stopPropagation();
                toggleSound();
            });
        });

        // Auto pause on scroll out of viewport
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        $cards.each(function () {
                            const video = $(this).find('video')[0];
                            if (video) {
                                video.pause();
                                video.muted = true;
                            }
                            $(this).find('.travelin-story-sound-toggle, .story-sound-toggle').removeClass('is-active active');
                        });
                    } else {
                        $cards.each(function () {
                            const video = $(this).find('video')[0];
                            if (video && video.paused) {
                                video.play().catch(function () {});
                            }
                        });
                    }
                });
            }, { threshold: 0.15 });

            const parentSection = $scope.find('.travelin-stories, .people-section')[0] || $scope[0];
            if (parentSection) {
                observer.observe(parentSection);
            }
        }
    }

    // -------------------------------------------------------------------------
    // 4. FAQ Accordion
    // -------------------------------------------------------------------------
    function initFAQ($scope) {
        const $items = $scope.find('.travelin-faq__item');
        if (!$items.length) return;

        $items.find('.travelin-faq__question').on('click', function () {
            const $item = $(this).closest('.travelin-faq__item');
            const isOpen = $item.hasClass('is-open');

            // Close others if single-accordion behavior is preferred
            $items.not($item).removeClass('is-open').find('.travelin-faq__answer').slideUp(200);

            if (isOpen) {
                $item.removeClass('is-open');
                $item.find('.travelin-faq__answer').slideUp(200);
            } else {
                $item.addClass('is-open');
                $item.find('.travelin-faq__answer').slideDown(200);
            }
        });
    }

    // -------------------------------------------------------------------------
    // 5. Google Reviews Lightbox Modal
    // -------------------------------------------------------------------------
    function initGoogleReviews($scope) {
        const $backdrop = $scope.find('.gr-lightbox-backdrop');
        const $closeBtn = $backdrop.find('.gr-lightbox-close');
        const $lightboxImg = $backdrop.find('.gr-lightbox-image');
        const $captionSpan = $backdrop.find('.gr-lightbox-caption span');
        const $captionP = $backdrop.find('.gr-lightbox-caption p');
        const $triggers = $scope.find('.gr-photo-trigger');

        if (!$triggers.length || !$backdrop.length) return;

        function openLightbox(src, alt, author) {
            $lightboxImg.attr('src', src).attr('alt', alt);
            $captionSpan.text('Photo by ' + (author || 'Traveler'));
            $captionP.text(alt || 'Shared trip photo');
            $backdrop.addClass('is-active');
            $('body').css('overflow', 'hidden');
        }

        function closeLightbox() {
            $backdrop.removeClass('is-active');
            $('body').css('overflow', '');
        }

        $triggers.on('click', function (e) {
            e.preventDefault();
            const src = $(this).data('photo-src') || $(this).find('img').attr('src');
            const alt = $(this).data('photo-alt') || $(this).find('img').attr('alt');
            const author = $(this).data('author') || '';
            openLightbox(src, alt, author);
        });

        $closeBtn.on('click', function (e) {
            e.stopPropagation();
            closeLightbox();
        });

        $backdrop.on('click', function (e) {
            if ($(e.target).closest('.gr-lightbox-content').length === 0) {
                closeLightbox();
            }
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Escape' && $backdrop.hasClass('is-active')) {
                closeLightbox();
            }
        });
    }

    // -------------------------------------------------------------------------
    // Hook into Elementor Frontend
    // -------------------------------------------------------------------------
    $(window).on('elementor/frontend/init', function () {
        if (!window.elementorFrontend) return;

        initLenis();

        elementorFrontend.hooks.addAction('frontend/element_ready/travelin-hero.default', function ($scope) {
            initHeroScrubVideo($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/travelin-trips-carousel.default', function ($scope) {
            initTripsCarousel($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/travelin-experience.default', function ($scope) {
            initExperienceVideo($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/travelin-stories.default', function ($scope) {
            initStoryReels($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/travelin-faq.default', function ($scope) {
            initFAQ($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/travelin-google-reviews.default', function ($scope) {
            initGoogleReviews($scope);
        });
    });

    // Fallback document ready initialization for non-Elementor preview or standard page renders
    $(document).ready(function () {
        initLenis();
        initHeroScrubVideo($('body'));
        initTripsCarousel($('body'));
        initExperienceVideo($('body'));
        initStoryReels($('body'));
        initFAQ($('body'));
        initGoogleReviews($('body'));
    });

})(jQuery);
