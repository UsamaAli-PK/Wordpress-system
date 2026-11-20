=== Hello Animation ===

Contributors: wealcoder
Requires at least: 6.5
Requires PHP: 7.4
Tested up to: 7.0
Stable tag: 1.1.10
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, theme-options, featured-images, custom-logo, custom-menu, footer-widgets, left-sidebar, right-sidebar, full-width-template, post-formats, sticky-post, threaded-comments, translation-ready, rtl-language-support

A lightweight Elementor theme for animated websites. Elementor V4 ready, GSAP-friendly, companion theme for Animation Addons for Elementor.

== Description ==

Hello Animation is a fast, lightweight WordPress theme for animated websites. It is the companion theme for the Animation Addons for Elementor plugin and the base its GSAP scroll, hover and page-load animations were designed against.

**Why use it for animated sites**

* Stays out of the way. The header, footer, banner and blog layouts are made to sit underneath animated content, so you do not need CSS overrides or a child theme.
* No double loading. A plugin that renders its own header and footer can switch the theme's markup and CSS off with one filter.
* Starter templates just work. An animated Animation Addons starter template imports and runs, with GSAP scroll, hover and page-load effects included.

= Ready for Elementor V4 (Atomic) =

Elementor 4 introduces the Atomic editor, with heading, paragraph, button, div block and flexbox elements that carry their own styles. Hello Animation was checked against Elementor 4.3 with each of these elements on the default template, Elementor's Header & Footer template and Canvas. In practice:

* Atomic elements keep Elementor's own spacing, colour, button and link styling. The theme's content styles are scoped to plain post and page content and stop at the edge of anything Elementor draws, so a heading, paragraph or button you build in the V4 editor looks the way the editor shows it.
* Styles you set in the V4 editor win. The theme supplies a default heading font and a tight line height; any class or local style from the editor overrides both.
* Each template gets only the stylesheets it needs. Canvas loads the theme's base styles and fonts and nothing else; Header & Footer adds the header styles; the blog sheet is sent only where the theme's own templates print content.
* It sits cleanly under Elementor's Theme Builder header and footer locations, and still works with classic widgets and containers, so you can move to V4 one page at a time.

= Motion, when you want it =

Hello Animation can ease its menu, blog cards, sidebar, headings and paragraphs into place, and reveal the rest of a page as it scrolls into view. It is off until you switch it on. Everything respects the visitor's reduced-motion setting, and nothing built with Elementor is animated by the theme.

= How to use the animations =

**Turn animations on**

1. Go to Appearance > Customize.
2. Open Hello Animation > Animations.
3. Tick the animations you want, then click Publish.

**What each option does**

* Animate Menu: menu items ease in on load, dropdowns slide open, and the mobile menu staggers its items in.
* Split-Text Menu Hover: hovering a main menu item rolls its letters up one after another. Works on its own, and is skipped for Arabic, Hebrew and Indic scripts.
* Animate Blog and Content: blog cards, sidebar widgets, post headings and paragraphs ease in, and the rest reveal as you scroll to them.
* Animate Banner Title: the banner title words rise into place when the banner loads.

**Turn animations off**

Untick the options in Customizer > Animations and click Publish.

= Built for performance =

* Self-hosted DM Sans and Space Grotesk. No request to fonts.googleapis.com, so no visitor IP address is handed to a third party and no DNS lookup sits on the critical path.
* Conditional stylesheets. The blog, chrome and icon styles are sent only to pages whose markup actually needs them, rather than one large sheet everywhere.
* No jQuery dependency. The theme's own script is plain DOM, deferred, and under 5 KB minified.
* Block stylesheets are dropped on pages Elementor renders in full, where no block ever runs.

= A complete theme on its own =

Elementor and Animation Addons are recommendations, never requirements — nothing in the theme is gated behind either, and the suggestion can be dismissed for good. Without a single plugin you still get a working blog: threaded comments, four footer widget areas, a left, right or centred sidebar, sticky post support, post formats, a custom logo, breadcrumbs and a full-width page template, all configurable from the WordPress Customizer.

= Accessible and translation ready =

Keyboard-navigable menus with a skip link, focus styles and an offcanvas panel that closes on Escape and returns focus where it came from. Fully translatable, with a current .pot file included.

Suited to animated agency, portfolio, business and blog sites built with Elementor.

== Copyright ==

Hello Animation WordPress Theme, Copyright 2026 wealcoder
Hello Animation is distributed under the terms of the GNU GPL

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

== Resources ==

Everything the theme bundles, and where it came from.

DM Sans
* assets/fonts/dm-sans-latin.woff2, assets/fonts/dm-sans-latin-ext.woff2
* Copyright 2014 The DM Sans Project Authors
* Source: https://github.com/googlefonts/dm-fonts
* Licence: SIL Open Font License 1.1, https://scripts.sil.org/OFL
* Full licence text: assets/fonts/DM-Sans-OFL.txt

Space Grotesk
* assets/fonts/space-grotesk-latin.woff2, assets/fonts/space-grotesk-latin-ext.woff2,
  assets/fonts/space-grotesk-vietnamese.woff2
* Copyright 2020 The Space Grotesk Project Authors
* Source: https://github.com/floriankarsten/space-grotesk
* Licence: SIL Open Font License 1.1, https://scripts.sil.org/OFL
* Full licence text: assets/fonts/Space-Grotesk-OFL.txt

IcoMoon-Free icon font
* assets/fonts/icomoon.eot, icomoon.svg, icomoon.ttf, icomoon.woff, icomoon.woff2
  (icomoon.woff2 is the same font converted to WOFF2; no glyphs added or removed.)
* Copyright Keyamoon
* Source: https://github.com/Keyamoon/IcoMoon-Free
* Licence: GNU General Public License, version 2 or later
  (IcoMoon-Free is dual-licensed CC BY 4.0 / GPL; it is used here under the GPL.)

TGM Plugin Activation
* app/vendor/class-tgm-plugin-activation.php
* Copyright Thomas Griffin, Gary Jones, Juliette Reinders Folmer
* Source: http://tgmpluginactivation.com/
* Licence: GNU General Public License, version 2 or later

Images
* assets/imgs/default-blog/arrow-left.png, assets/imgs/default-blog/arrow-right.png
* assets/imgs/icons/menu.svg
* screenshot.jpg
* Created for this theme by wealcoder. Copyright 2026 wealcoder.
* Licence: GNU General Public License, version 2 or later

== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload Theme and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.

== Frequently Asked Questions ==

= Does this theme support any plugins? =

Hello Animation is built to work with Elementor Page Builder, and its starter templates and motion effects are built with Animation Addons for Elementor. Neither is required — the theme renders on its own — and the theme will suggest both from the Dashboard, Appearance, Plugins and Tools screens until they are installed. That suggestion can be dismissed for good.

= Are the theme's animations on by default? =

No. Nothing moves until you switch it on, so a site that installs or updates the theme looks exactly as before. Customizer > Animations lets you pick which of the menu, the blog and content, and the banner title move. Visitors who ask their device to reduce motion never see them either way.

= Does Hello Animation work with Elementor V4 and Atomic elements? =

Yes. It was checked against Elementor 4.3 using the Atomic heading, paragraph, button, div block and flexbox elements on the default template, Elementor's Header & Footer template and Canvas. The theme's own styles do not reach into Elementor-built content, so Atomic elements keep Elementor's spacing, colours and links, and anything you style in the V4 editor overrides the theme's defaults. Classic widgets and containers keep working too, so you can adopt Atomic gradually.

= Can I use it with Elementor's Theme Builder? =

Yes. When a Theme Builder header and footer claim a page, the theme prints none of its own header or footer markup and loads none of the CSS that goes with them, so nothing is rendered or downloaded twice.

= Do I have to install the recommended plugins? =

No. Nothing in the theme is gated behind them. Install Elementor and Animation Addons if you want the starter templates and the GSAP animations they are built with; skip both and you still have a working blog theme with its own header, footer, banner and blog layouts.

== More from Wealcoder ==

Other free plugins by the same author, all hosted on WordPress.org.

= Animation Addons for Elementor =

GSAP-powered motion, widgets and ready-made templates for Elementor. This is the plugin Hello Animation's starter templates are built with, so it is the one to reach for if you are using this theme.

https://wordpress.org/plugins/animation-addons-for-elementor/

= BricksFly Elements and Templates for Bricks =

The same idea for a different builder: elements, extensions and a no-code GSAP animation engine for Bricks Builder.

Worth knowing before you click: BricksFly is for sites built on the Bricks theme, and a WordPress site runs one theme at a time — so it is an alternative to this setup rather than an addition to it. It is listed here because people who like what Hello Animation does with motion often ask whether there is an equivalent for Bricks.

https://wordpress.org/plugins/bricksfly-elements-for-bricks/

== Changelog ==

= 1.1.10 =

Changed

* The optional animations are now controlled only from Customizer > Hello Animation > Animations. The Animations item in the admin bar and the Dashboard banner that 1.1.9 added have been removed; the animations themselves and their four switches are unchanged, and a site that already turned them on keeps them on.

Fixed

* Installing both recommended plugins at once from Appearance > Install Plugins no longer fails to activate Animation Addons. Elementor is now listed and installed first, so the add-on finds it already active.

Documentation

* The readme now explains how to turn each animation on and off.

= 1.1.9 =

Added

* Elementor V4 (Atomic) support, checked against Elementor 4.3 with the Atomic heading, paragraph, button, div block and flexbox elements on the default template, Header & Footer and Canvas: the theme's content styles leave them alone, styles set in the V4 editor win over the theme's defaults, and each template loads only the stylesheets it needs. The readme and screenshot now say so.
* A wpml-config.xml registering the Customizer text a site owner writes — header button text and URL, copyright text, offcanvas description and gallery heading, and the search page heading — so WPML String Translation (and Polylang, which reads the same file) can translate them per language. They were stored as plain theme mods and showed the same text on every language.
* RTL language support. Each stylesheet ships a mirrored -rtl twin, generated with rtlcss, that WordPress swaps in on Arabic, Hebrew, Persian and other RTL locales: header, menus and sub-menus (nested flyouts open to the left), the mobile offcanvas menu, blog archive, single post, sidebar, comments and footer all follow the reading direction. Arrow and chevron icons are mirrored so "next" and "read more" point the right way.
* Optional animations, off by default and switched on from Customizer > Animations. Four switches:
  - Animate Menu: menu items ease in on load, dropdowns slide open with their items following one by one, links get an underline that sweeps in, and the mobile menu staggers its items in when it opens.
  - Split-Text Menu Hover: hovering a main menu item rolls its letters up one after another. Independent of Animate Menu; letters are cut up by script, screen readers still get the plain text, and Arabic, Hebrew and Indic scripts are left whole.
  - Animate Blog and Content: the first blog cards (with their meta, title, excerpt and button following), sidebar widgets, post heading, headings, paragraphs and blocks ease in on load, and everything further down is revealed as it scrolls into view, staggered in batches. Elementor-built content is never touched.
  - Animate Banner Title: each word of the blog and search banner title rises into place, blurred to sharp, and the breadcrumb fades up behind it. The title stays plain text in the page (words, not letters, so Arabic and other joined scripts keep their forms).
  Every animation uses transforms and opacity only, so nothing shifts in the layout, and none of it runs for visitors whose device asks for reduced motion. CSS drives the first screen and anything state-driven (hover, dropdowns, the mobile menu), so it paints before any script runs; a small script using the Web Animations API handles only the scroll reveal below the fold. The hello_animation/motion/menu, hello_animation/motion/split, hello_animation/motion/content and hello_animation/banner/animate_title filters switch each one in code.
* A new default banner. Until a background colour or image is chosen in Banner Settings, the banner is a deep navy-to-indigo gradient with warm and cool glows and a faint grid, in place of the orange that turned muddy brown under the dark overlay. Choosing a colour or an image brings back the previous banner exactly.
* Two offcanvas menu settings under Offcanvas Settings: Submenu Icon Gap (px), the space between a menu label and its + / - toggle (default 10px), and Submenu Icon Position, which keeps the toggle next to its label or spreads each row so the toggle sits at the far edge (Space between).

Changed

* Offcanvas Content Align now defaults to Left. Sites that saved an alignment keep it.
* The icon font ships as WOFF2 (about 11 KB instead of 22 KB for the TrueType file browsers used before). Older formats stay as fallbacks.
* DM Sans and Space Grotesk, the two fonts nearly every page uses, are preloaded from the top of the head, so text no longer waits for the stylesheet to be parsed before its font starts downloading.

Fixed

* Every `transition: all` in the theme, the global link rule included, now lists only the properties that change on hover or open (colour, background, border, width, opacity and visibility, as each rule needs). `all` also eased transform and opacity, so a GSAP tween on a theme element — Animation Addons' included — was dragged behind its own target by the theme's 0.3s. Hover and dropdown looks are unchanged.
* The theme's content styles reached into Elementor-built content. They hang off the hello-animation-base body class, which every page carries — Elementor Canvas and Header & Footer templates included — so bare element selectors applied to every widget. Each one now carries :not(.elementor *) and styles only plain post and page content, where it behaves as before.
* Elementor's editor handles broke apart on pages using the default template: the content list style (padding-left 20px, padding-bottom 30px) landed on the handle bar, which is a ul > li too, pushing its icons up and leaving a 30px strip below it.
* Every Elementor heading on a default-template page carried a 1.5rem bottom margin, and every paragraph a #555555 colour, 1.7 line height and 40px bottom margin, overriding the widget's own spacing and typography.
* Any widget that renders a blockquote, testimonials among them, was drawn as the theme's orange quote box with white text.
* Elementor form selects showed two arrows: the theme removed the native appearance and drew its own arrow beside Elementor's.
* Tables inside Elementor were forced to white-space: nowrap with 8px cell padding, so table and pricing widgets could not wrap.
* On phones every Elementor iframe was forced to 360px tall, distorting video and map embeds sized by the widget.
* Image widgets ignored a custom height (height: auto won), and links inside Elementor carried a 0.3s all-property transition that fought GSAP-driven animation, plus the theme's underline and hover colour on default-template pages and single posts.
* The default copyright text read "Alrights reserved", and the footer template carried its own, shorter fallback that did not match the Customizer default. Both now read "All rights reserved by Wealcoder". A copyright text already saved in the Customizer is left as it is.
* The Offcanvas Logo setting did nothing on a site without a site logo: it was only read inside a has_custom_logo() check. It now always wins, with the site logo as the fallback.
* On Elementor pages rendered with the theme header, the mobile menu toggle showed beside the desktop menu on wide screens. The rule hiding it lived in blog.css, which only loads on blog views; it now ships with the header styles.
* A long menu no longer runs off the edge of the header. Items keep their labels on one line and wrap to a second row, and the logo is no longer squeezed to one word per line; on narrow phones a long text logo wraps instead of pushing the menu toggle off screen.
* Desktop dropdowns near the end of the menu opened past the edge of the screen, cutting off the panel. They now open the other way when there is no room, in both LTR and RTL, with the arrow pointing the way the panel opens.
* In the mobile (offcanvas) menu, tapping a submenu's + often opened the page instead: the toggle was a 10px glyph pressed against the label, so a near miss landed on the link. Its tap target is now about 30x40px and reaches back across the gap to the label.
* The offcanvas Content Align setting used a physical text-align, so on RTL sites "Left" put the menu on the right and the description on the left. It now follows the reading direction for everything in the panel.
* An item on the deepest menu level the theme prints got a submenu arrow (and, in the mobile menu, a toggle) for children that are never shown.

= 1.1.8 =

Added

* Plugin recommendations are handled by TGM Plugin Activation again, offering Elementor and Animation Addons for Elementor. Both are optional: nothing is bundled, nothing is force-activated, and the notice can be dismissed for good.
* Every global the library declares carries the theme's prefix, so another copy of TGMPA on the same site cannot take over the theme's configuration.
* The library loads only on the Dashboard, Appearance, Plugins and Tools screens, only for a user who can install or activate a plugin, and only while a recommendation is outstanding. Once both plugins are active it is not parsed at all — 130 KB of PHP off every other admin page.
* style.css is enqueued. get_stylesheet_uri() resolves to the child theme's style.css, so every child theme of this theme had a stylesheet that was never loaded.
* The recommendation notice is drawn in the theme's orange, with the install action as a button. add_settings_error() renders it as a yellow warning bar by default, and nothing here is wrong or required.
* A Resources section in this readme, crediting every bundled font, icon set, library and image with its source and licence.

Fixed

* The recommendation notice was shown to anyone who could publish a post, while the page it links to needs install_plugins. It is gated on install_plugins now.
* Theme Check read every Customizer setting as unsanitised, which rejected the 1.1.6 upload: the argument array was assembled in a variable, and Theme Check reads the text between add_setting( and the next semicolon rather than parsing PHP. The arguments are written out inside the call. What gets sanitised is unchanged.
* The copyright notice is in style.css as well as readme.txt. Note that Theme Check locates both files by the literal path hello-animation/style.css, so the zip's top-level directory has to be named hello-animation or neither file is found.
* Escaping in the breadcrumb trail: unquoted hrefs, and raw category names and post titles. It is assembled as a list and escaped in one place.
* The site title was an <h1> on every page, competing with the post or page title. It is an <h1> on the front page and a <p> elsewhere; the comment navigation's two screen-reader headings are <h2>.
* A post with one comment read "1 Comments" and a post with none read "0 comment". Both counts use _n() now.
* The comment form's label pointed at for="name", an element that is not on the page, so it focused nothing and the field was announced as unlabelled. Its text was also untranslatable.
* Every menu item's DOM id appeared twice per page, because the offcanvas panel renders the same menu; that copy prefixes its ids now. Submenu items were also never closed — end_el() emitted </li> only at depth 0.
* The header button shipped pointing at https://yoururl.com/. That was the registered default, and registered defaults are what get_theme_mod() returns, so it was live on every untouched install. It defaults to empty and prints no button until set.
* Archive thumbnails were a hand-written <img> with no srcset, sizes or loading attribute, so every visitor downloaded the full-size crop for every card, eagerly. They use the_post_thumbnail() now.
* Author avatars lost their retina sources: the post meta ran through the theme's kses filter, which allows an img only alt, class, height, src and width, stripping srcset, sizes and loading.
* The offcanvas panel could not be closed with a keyboard — no Escape handler, and focus was left inside a hidden panel instead of returning to the button that opened it. aria-expanded was written to the submenu <ul> rather than the control, and the submenu toggles answer Enter and Space now.
* The viewport meta capped zoom at 2x, which fails WCAG 1.4.4 and disables pinch-zoom on iOS.
* wp_nav_menu() was called with 'menu' => 'primary', which is checked before theme_location — so a site with a menu named "primary" got that one regardless of what was assigned to the Primary location.
* The icon font's stylesheet handle was wcf-custom-icons, the one handle in the theme carrying another product's prefix. It is hello-animation-icons.
* Option labels were passed through esc_html__() and then escaped again by the Customizer, so apostrophes and ampersands displayed as entities.
* The offcanvas gallery warned on every page load for sites upgrading from the Kirki build, where the setting is still stored as a JSON string.
* The translation template predated both this release and the last; it is regenerated at 276 entries, up from 197, covering the recommendation UI. npm run build:pot rebuilds it.

Removed

* The apple-touch-icon link tag on single posts. It pointed at the post's full-size featured image, so it changed from post to post and downloaded far more than a touch icon needs; WordPress has rendered the whole icon set from Site Icon since 4.3.
* Dead code: a breadcrumb branch for custom post types whose body could never run, a duplicated is_shop() branch, a comment-form conditional that tested null === '', and four data-* entries sitting in the tag position of the kses allowlist.

= 1.1.7 =

Fixed

* Theme Check read every Customizer setting as unsanitised. Superseded by 1.1.8, which carries the same fix.
* Added the copyright notice to style.css.

= 1.1.6 =

* Version bump to 1.1.6.

= 1.1.5 =

Fixed

* The label above the search field lost the spacing it was designed with. The theme styles it as a 20px bold underlined heading with 25px beneath it, and the rule added in 1.1.4 to give the field a focus style was specific enough to beat that, cutting the gap to 2px and the weight to 600. The label rule now sets only the one thing that was genuinely missing — display: block, without which the 25px could never apply, because vertical margin on an inline box moves nothing.

= 1.1.4 =

Removed

* The Kirki Customizer Framework dependency. Kirki has moved on from being an options framework and is now a page-builder product, so the theme no longer recommends installing it and no longer registers anything through it. Kirki was optional, which meant the settings panel only existed on sites that happened to have the plugin installed — on every other site the controls were simply absent and each setting fell through to its inline default. The theme now owns its settings outright, registered against core's Customizer API.
* The "Sticky Header" setting. It was registered as a control but no PHP, JavaScript or CSS in the theme ever read it, so switching it did nothing.

Added

* A native "Hello Animation" customizer panel with the Header, Offcanvas, Banner, Blog and Footer sections, holding all 42 controls. The setting keys are unchanged, so a site that had Kirki installed keeps its saved values.
* Offcanvas Logo and Mobile Menu Icon now appear under Site Identity, next to the core logo setting they belong with.
* A repeater control for the offcanvas gallery, with media-library picking, drag-to-reorder and a per-row link.
* A CSS builder that emits the colour and alignment settings inline. Nine settings appear in no template and drove nothing but generated CSS, so they had no effect at all without Kirki.
* A dismissible admin notice explaining the Kirki removal. It retires itself permanently after three views whether or not it is dismissed.
* A plugin suggestion notice replacing TGMPA. It appears only on the Dashboard, Themes and Plugins screens, only for users who may actually install or activate, and only while one of the plugins is missing — once both are active it stops rendering on its own. Dismissing it is permanent. The `hello_animation/recommended_plugins` filter lets a child theme change the list.

Fixed

* Every checkbox setting was stored as the string 'off' when switched off, and 'off' is truthy in PHP. All fifteen switches were therefore stuck on: "Left Direction: Disable" still opened the offcanvas from the left, and no blog meta could be hidden. They store booleans now and behave as labelled.
* hello_animation_option() returned the default whenever the stored value was falsy, so a setting saved as false, 0 or an empty string read back as its default. "BreadCrumb Enable" could not be turned off.
* Registered defaults never reached the front end. get_theme_mod() falls back to the default at the call site, not the one given when the setting is registered, so "Breadcrumb Word Limit" showed 5 in the customizer while the template received an empty string.
* Offcanvas Content Align and Menu Color targeted selectors that no longer exist in the markup. The menu stayed black at every colour, and the gallery kept a fixed three-column grid that left an empty column when aligned right.
* The header button's rel attribute was assembled as a whole `rel=value` pair and then escaped as if it were a value, emitting a broken tag. The Rel control had no working output until now.
* The search form drew the browser's default black focus rectangle over a field designed as an underline, its submit button was never positioned so it sat outside the field while the input reserved padding for it, the field id was hard-coded so a second form on the page stole the first one's label, and the icon button had no accessible name.
* The block search widget's input kept core's default styling, unpadded and unmatched to the theme, because the theme's search rules could not reach it.
* Comment fields had no border and no focus style, so the browser drew a hard black box on focus. They now take a subtle border that turns to the accent colour on focus, with no layout shift.
* The description meta tag on single posts was built by passing an echoing function to esc_attr(). The excerpt was therefore printed while the attribute was still half-written and never escaped at all, so the tag carried the paragraph markup wpautop() had just added, content="" was always empty, and an excerpt containing a double quote closed the attribute early and could add attributes of its own to the tag.
* The apple-touch-icon link printed href="" on every post without a featured image. It is only printed when there is one.
* The search-results query filter ran against every WP_Query on the request rather than the main one, forcing post_type=post on secondary searches — a related-posts lookup, a widget, a REST search — that had asked for something else. It was also registered with add_filter() on pre_get_posts, which passes its query by reference and ignores return values.
* The breadcrumb read $_GET['paged'] directly, so on any site using pretty permalinks — where the page number is in the path — that branch never ran. It asks get_query_var() now.

Performance

* The fonts are the theme's own now, not Google's. DM Sans and Space Grotesk were fetched from fonts.googleapis.com on every page view, which handed each visitor's IP address to a third party before the page could render — not permitted for a theme in the WordPress.org directory — and put a DNS lookup, a TLS handshake and two round trips on the critical path before any text could paint. The five woff2 files ship with the theme and carry Google's own unicode-range rules, so a browser still downloads only the subsets it needs: 57.9 KB for a Latin page, the same as before. Both families are SIL Open Font License 1.1 and their licences are bundled. A locale these fonts cannot render can still switch them off by translating the "Theme fonts: on or off" string to "off".
* Reading any setting cost two reads of the theme_mods option instead of one. The default filter added for every key called get_theme_mods() afresh each time it fired, which is once per get_theme_mod() call anywhere in the theme. The mods are now read once per request and released as soon as anything writes one. Measured over the settings that generate CSS: 36 option reads down to 19, and that work 40% faster.
* hello_animation_get_random_category() called get_the_category() twice to answer the same question, once for every post in the loop.

Removed

* The TGM Plugin Activation library. The theme used it to suggest two optional plugins that both live on wordpress.org, and paid 130 KB of PHP across seven classes on every wp-admin page for it — including core's WP_List_Table, which merely including the library pulled in. TGMPA is built for cases this theme does not have: installing from a bundled zip, from an external URL, forcing activation, comparing versions. A plain dismissible notice with two links replaces it. The Appearance -> Install Plugins screen and its bulk installer are gone with it; the plugins install from the normal Plugins screen like any other.
* The Tags class. Its two public methods, posted_on() and entry_footer(), were never called by any template in the theme, and the only other method existed to clear a transient that nothing set. It also hooked save_post and edit_category on every request to do that. Left over from the Underscores starter theme the theme was built from.
* hello_animation_post_meta_2(), a near-copy of hello_animation_post_meta() that read setting keys no framework ever registered. Nothing called it.
* 85 translation strings that came with TGMPA, now that nothing in the theme uses them.

Changed

* Settings are declared once as plain arrays in app/options/, read by both the customizer registrar and the CSS builder. Adding a setting is a one-entry change, as it was under Kirki.
* Two settings in the Blog section, "Post Navigation" and "Read More", were registered twice. Registered once now.

= 1.0.3 =

Fixed

* Google Fonts: the request mixed `,` and `;` separators, which the v1 API could not parse, so weight 500 was silently dropped from both DM Sans and Space Grotesk. 500 is the weight the theme's own stylesheets use most (20 rules), and every one of them was falling back to a browser-synthesised face.
* Theme header rendered twice on sites using an Elementor Theme Builder header. header.php and footer.php now yield the location instead of always printing their own markup.
* Escaped output that was being printed raw: post navigation titles and permalinks, author display name, author bio, and the post thumbnail URL. Exploitable only by a user allowed to post unfiltered HTML, but escaped now regardless.
* Uncontrolled recursion in the asset gate. Both of its filters could be re-entered by a callback while the answer was still being worked out, which recursed until the stack gave out.
* HELLO_ANIMATION_VERSION was defined as a float, so every asset URL carried `?ver=1` and a version bump could never bust a cache. It is a string now.

Added

* Asset gate with three filters, so a plugin can switch the theme's front-end assets off on pages it renders itself: `hello_animation/assets/blank_mode`, `hello_animation/assets/load`, `hello_animation/assets/renders_theme_chrome`.
* Elementor Theme Builder location support (`elementor_theme_do_location`) for the header and footer.
* npm build for the stylesheets — `npm install && npm run build:css`.
* elementor Atomic compatible, performance improve

Changed

* The single 100 KB stylesheet is now three: base, chrome and blog. A page loads only the ones its markup needs; the blog sheet alone is 56 KB and matches nothing on a page Elementor lays out.
* The theme script no longer declares jQuery as a dependency. It never used it for anything but `$(document).ready` and `$.extend`, and declaring it pulled jquery-core and jquery-migrate — about 90 KB — onto every page. It is also deferred now.
* Google Fonts moved to the v2 API with `display=swap` and variable-font ranges: nine font files became five, and text no longer stays invisible while they load. Only the weights the stylesheets actually use are requested — 300, 800 and 900 were being fetched and never referenced.
* Block editor stylesheets are dequeued on pages Elementor renders, where no block can match them.
* TGMPA and the required-plugins notice load only in wp-admin. TGMPA disables itself on the front end anyway, so 133 KB of PHP was being parsed on every page view for nothing.
* screenshot.png replaced by screenshot.jpg at the same 1200x900 — 864 KB down to 183 KB.
* custom-icons.min.css is now actually minified. It had been a byte-for-byte copy of its own source.

Removed

* master.scss, master.min.scss and their compiled output. The three split stylesheets emit exactly the same rules.
* Seven unused helper functions: hello_animation_desc_limit, hello_animation_src, hello_animation_is_footer_widget_active, hello_animation_title_limit, hello_animation_footer_allowed_pages, hello_animation_random_tag_retrip, hello_animation_starts_with. Nothing in the theme, the bundled plugins or the database referenced them. If you called any of these from a child theme, copy it across before updating.
* skip-link-focus-fix.js, components/_cursor.scss, assets/fonts/lg.ttf, prepros.config and 14 unreferenced images.

== Credits ==

Hello Animation bundles the following third-party resources:
______
Images:
https://openverse.org/image/b91290f1-5bc8-4a8a-9bf8-9998908aa7d8?q=Office

* Icomoon
    * These fonts are licensed under the CC BY 4.0 License
* DM Sans, Copyright 2014 The DM Sans Project Authors
	* Source: https://github.com/googlefonts/dm-fonts
	* SIL Open Font License 1.1, https://scripts.sil.org/OFL
	* Licence bundled at assets/fonts/DM-Sans-OFL.txt
	* Bundled as assets/fonts/dm-sans-latin.woff2 and dm-sans-latin-ext.woff2
* Space Grotesk, Copyright 2020 The Space Grotesk Project Authors
	* Source: https://github.com/floriankarsten/space-grotesk
	* SIL Open Font License 1.1, https://scripts.sil.org/OFL
	* Licence bundled at assets/fonts/Space-Grotesk-OFL.txt
	* Bundled as assets/fonts/space-grotesk-latin.woff2, space-grotesk-latin-ext.woff2 and space-grotesk-vietnamese.woff2
* Based on Underscores https://underscores.me/, (C) 2012-2020 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* This theme include custom made icons, graphics and free images which are edited to fit the theme style.