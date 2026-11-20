/***************************************************
 ====================  JS INDEX ======================
****************************************************
01. Mobile menu Start
02. Mobile offcanvas
****************************************************/

/**
 * No jQuery.
 *
 * Everything below already used the DOM API directly — jQuery was reached for
 * in exactly two places, $(document).ready and $.extend, and declaring it as a
 * dependency pulled jquery-core plus jquery-migrate onto every page of the
 * site. The `config` object those two lines maintained was never read by
 * anything, so it has gone with them.
 */
(function () {
  "use strict";

  var hello_animation_theme_module = {

    setup: function () {

      if(document.getElementById('hello-animation-openOffcanvas')){
        hello_animation_theme_module.offcanvas();
      }

      hello_animation_theme_module.dropdownEdges();
      hello_animation_theme_module.reveal();
      hello_animation_theme_module.splitMenu();

    },

    /**
     * Reveal-on-arrival for everything BELOW the fold at load.
     *
     * Who animates what: the stylesheets own the first screen (it has to paint
     * before this script runs) and everything state-driven (hover, dropdowns,
     * the offcanvas opening). This owns the one thing CSS cannot do portably —
     * reveal an element as it scrolls into view, and stagger the ones that
     * arrive in the same moment — using WAAPI, so each reveal is a plain
     * element.animate() with no classes toggled and nothing left behind.
     *
     * It never touches an element CSS already animates, one already on screen,
     * or anything inside Elementor, and it does nothing at all unless Customizer
     * > Animations > Animate Blog and Content is on and the visitor has not asked
     * for reduced motion. With no IntersectionObserver or WAAPI the page simply
     * stays as it is.
     *
     * Timing, distance and curve come from the same :root tokens the CSS uses
     * (--ha-dur, --ha-step, --ha-rise, --ha-ease), so the two cannot drift.
     */
    reveal: function () {
      if (!document.body.classList.contains('ha-motion-content') ||
          !('IntersectionObserver' in window) || !Element.prototype.animate) {
        return;
      }

      const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

      if (reduce.matches) {
        return;
      }

      const root  = getComputedStyle(document.documentElement);
      const num   = function (name, fallback) {
        const n = parseFloat(root.getPropertyValue(name));
        return isNaN(n) ? fallback : n;
      };
      const dur   = num('--ha-dur', 800);
      const step  = num('--ha-step', 80);
      const rise  = num('--ha-rise', 28);
      const ease  = root.getPropertyValue('--ha-ease').trim() || 'cubic-bezier(.16, 1, .3, 1)';

      // The theme's own blocks. The same structure the first-screen CSS is
      // written against; whatever that already animates is skipped below.
      const targets = [
        '.default-blog__item-content:not(.builder--page-details) > .default-blog__item-single',
        '.default-blog__item-content > :not(.default-blog__item-single)',
        '.default-blog__item-content:not(.builder--page-details) .default-blog__content > *',
        '.default-sidebar__wrapper > .widget',
        '.post-single .info--post-details > *',
        // Blocks nested one level in: columns, groups, covers, media & text.
        '.post-single .info--post-details :is(.wp-block-column, .wp-block-group, .wp-block-group__inner-container, .wp-block-cover__inner-container, .wp-block-media-text__content) > *',
        '.default-blog__details-content > :not(article)',
        '.builder--page-details > article > *'
      ].join(',');

      const pending = new Map();

      const io = new IntersectionObserver(function (entries) {
        let n = 0;

        entries
          .filter(function (e) { return e.isIntersecting; })
          .sort(function (a, b) { return a.boundingClientRect.top - b.boundingClientRect.top; })
          .forEach(function (e) {
            const anim = pending.get(e.target);

            if (!anim) {
              return;
            }

            pending.delete(e.target);
            io.unobserve(e.target);

            // Stagger within this batch: the ones arriving together follow one
            // another instead of landing as a block. Capped at five steps, so a
            // long jump down the page (a batch of twenty) still lands in well
            // under half a second instead of rolling in for a second and a half.
            anim.effect.updateTiming({ delay: Math.min(n++, 5) * step });
            anim.play();

            // Hand the element back untouched: no fill, no stacking context.
            anim.finished.then(function () { anim.cancel(); }, function () {});
          });
      }, { rootMargin: '0px 0px -10% 0px', threshold: 0.01 });

      document.querySelectorAll(targets).forEach(function (el) {
        if (el.closest('.elementor') || el.getAnimations().length ||
            el.getBoundingClientRect().top < window.innerHeight) {
          return;
        }

        // Headings travel a little further than the text around them.
        // Inside a card they travel less, to sit under the card's own entrance.
        const dist = el.closest('.default-blog__content') ? rise * 0.5 :
          /^H[1-6]$/.test(el.tagName) ? rise * 1.35 : rise;
        const anim = el.animate(
          [
            { opacity: 0, transform: 'translate3d(0,' + dist + 'px,0)' },
            { opacity: 1, transform: 'none' }
          ],
          { duration: dur, easing: ease, fill: 'both' }
        );

        // Held on its first frame — hidden — until it arrives.
        anim.pause();
        pending.set(el, anim);
        io.observe(el);
      });

      // Leave everything visible if it stops being wanted: the visitor turns on
      // reduced motion, or prints the page.
      const stop = function () {
        io.disconnect();
        pending.forEach(function (anim) { anim.cancel(); });
        pending.clear();
      };

      if (reduce.addEventListener) {
        reduce.addEventListener('change', function (e) { if (e.matches) { stop(); } });
      }

      window.addEventListener('beforeprint', stop);
    },

    /**
     * Split-text hover for the top-level menu items: each letter becomes its own
     * span so the CSS (components/_menu-motion.scss) can roll them one after
     * another. The script only cuts the text up; the movement is CSS, on :hover.
     *
     * Screen readers get the original text (visually hidden) and skip the
     * letters. Scripts that join or reorder letters — Arabic, Hebrew, the Indic
     * scripts — are left whole, because splitting them breaks the shaping.
     */
    splitMenu: function () {
      if (!document.body.classList.contains('ha-motion-split') ||
          window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
      }

      document.querySelectorAll('.header__nav .main-menu > ul > li > a .ha-nav-link').forEach(function (label) {
        const text = label.textContent.trim();

        if (!text || /[֐-ࣿऀ-෿]/.test(text) || label.querySelector('*')) {
          return;
        }

        const sr    = document.createElement('span');
        const split = document.createElement('span');

        sr.className = 'screen-reader-text';
        sr.textContent = text;
        split.className = 'ha-split';
        split.setAttribute('aria-hidden', 'true');

        Array.from(text).forEach(function (ch, i) {
          const c = document.createElement('span');
          c.className = 'ha-ch';
          c.textContent = ch;
          c.setAttribute('data-c', ch);
          c.style.setProperty('--i', i);
          split.appendChild(c);
        });

        label.textContent = '';
        label.appendChild(sr);
        label.appendChild(split);
      });
    },

    /**
     * Desktop dropdowns always open towards the end of the line (right in LTR,
     * left in RTL). Near the end of a long menu that ran past the viewport: the
     * panel was cut off and the page gained a horizontal scroll. Before a
     * dropdown opens, work out where it would land and, if it would not fit,
     * add .ha-flip so the CSS opens it the other way.
     */
    dropdownEdges: function () {
      const nav = document.querySelector('.header__nav');

      if (!nav) {
        return;
      }

      const GAP = 10; // the offset _menu.scss gives both panel levels

      const check = function (li) {
        const panel = li.querySelector(':scope > ul');

        li.classList.remove('ha-flip');

        if (!panel || window.innerWidth < 1200) {
          return;
        }

        const rtl    = getComputedStyle(li).direction === 'rtl';
        const nested = !!li.parentElement.closest('li');
        // A top-level panel hangs off its own item; a nested one off the
        // panel it sits in.
        const anchor = (nested ? li.parentElement : li).getBoundingClientRect();
        const width  = panel.offsetWidth;

        if (rtl) {
          const start = nested ? anchor.left - GAP - width : anchor.right - GAP - width;
          li.classList.toggle('ha-flip', start < 0);
        } else {
          const end = nested ? anchor.right + GAP + width : anchor.left + GAP + width;
          li.classList.toggle('ha-flip', end > document.documentElement.clientWidth);
        }
      };

      nav.querySelectorAll('li.menu-item-has-children').forEach(function (li) {
        li.addEventListener('mouseenter', function () { check(li); });
        li.addEventListener('focusin', function () { check(li); });
      });
    },

    offcanvas: function(){
         // Get the offcanvas element and buttons
       const offcanvas = document.getElementById('hello-animation-offcanvas');
       const openBtn   = document.getElementById('hello-animation-openOffcanvas');
       const closeBtn  = document.getElementById('hello-animation-closeOffcanvas');

       if (!offcanvas || !openBtn) {
           return;
       }

       // aria-expanded belongs on the control, not on the thing it controls —
       // it tells someone using the button what pressing it will do. It used to
       // be set on the submenu <ul> further down, where nothing announces it.
       const setOpen = (open) => {
           offcanvas.classList.toggle('show', open);
           openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
       };

       const isOpen = () => offcanvas.classList.contains('show');

       const close = ({ returnFocus = false } = {}) => {
           if (!isOpen()) {
               return;
           }
           setOpen(false);
           // Focus was inside a panel that has just been hidden; without this it
           // falls back to <body> and the next Tab starts from the top of the
           // document rather than from the button that opened the panel.
           if (returnFocus) {
               openBtn.focus();
           }
       };

       openBtn.addEventListener('click', () => {
           setOpen(true);
           const firstFocusable = offcanvas.querySelector(
               'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])'
           );
           if (firstFocusable) {
               firstFocusable.focus();
           }
       });

       if (closeBtn) {
           closeBtn.addEventListener('click', () => close({ returnFocus: true }));
       }

         // Close offcanvas when clicking outside of it
       document.addEventListener('click', (event) => {
           const isClickInside = offcanvas.contains(event.target) || openBtn.contains(event.target);
           if (!isClickInside) {
               close();
           }
       });

       // Escape closes it. A panel that traps you until you find the X is the
       // single most common keyboard complaint about offcanvas menus.
       document.addEventListener('keydown', (event) => {
           if (event.key === 'Escape') {
               close({ returnFocus: true });
           }
       });


          // Get all menu items with potential submenus
          const menuItems = document.querySelectorAll('.hello-animation-mb-menu-items li > a');

          // Loop through each menu item to add click event listener
          menuItems.forEach(menuItem => {
              const default_icon = menuItem.nextElementSibling;
                if (default_icon && default_icon.tagName === 'UL' && default_icon.classList.contains('dp-menu')) {
                    menuItem.querySelector('.nav-direction-icon').setAttribute('data-icon', '+')



                    const toggle = menuItem.querySelector('.nav-direction-icon');

                    // aria-expanded goes on the toggle, which is the thing a
                    // screen reader announces when you land on it. It used to be
                    // written to the submenu <ul> instead — a state nobody is
                    // told about, on an element that is not the control.
                    toggle.setAttribute('role', 'button');
                    toggle.setAttribute('aria-expanded', 'false');

                    const toggleSubmenu = function (e) {
                        const submenu = toggle.parentElement.nextElementSibling; // the submenu <ul>

                        // Check if this next sibling is a submenu (<ul>)
                        if (submenu && submenu.tagName === 'UL' && submenu.classList.contains('dp-menu')) {
                            e.preventDefault(); // Prevent the default link behavior (navigating)

                            const expanded = toggle.getAttribute('aria-expanded') === 'true';
                            toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');

                            // Toggle the display of the submenu (open/close)
                            submenu.style.display = expanded ? 'none' : 'block';

                            // Toggle + or - symbol
                            toggle.setAttribute('data-icon', expanded ? '+' : '-');
                        }
                    };

                    toggle.addEventListener('click', toggleSubmenu);

                    // It carries tabindex="0" and now role="button", so it has to
                    // answer Enter and Space like one.
                    toggle.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
                            toggleSubmenu(e);
                        }
                    });
                }
          });

          // Accessibility: Enable keyboard navigation
          document.querySelectorAll('.hello-animation-mb-menu-items a').forEach(link => {
            link.addEventListener('keydown', function (event) {
              if (event.key === 'ArrowDown') {
                event.preventDefault();
                let nextItem = event.target.parentElement.nextElementSibling;
                if (nextItem) nextItem.querySelector('a').focus();
              }
              if (event.key === 'ArrowUp') {
                event.preventDefault();
                let prevItem = event.target.parentElement.previousElementSibling;
                if (prevItem) prevItem.querySelector('a').focus();
              }
            });
          });


    }

  };

  // The script is deferred, so the parser may already be done by the time it
  // runs — DOMContentLoaded would never fire again in that case.
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hello_animation_theme_module.setup);
  } else {
    hello_animation_theme_module.setup();
  }

})();
