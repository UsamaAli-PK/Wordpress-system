# Agency Companion Plugin Boilerplate

**Location:** `boilerplates/companion-plugin/`  
**License:** GPL-2.0+  
**Stack:** PSR-4 Namespaced PHP 8.1+ Plugin with Elementor 3.5+ Widget Loader & Decoupled Feature Modules

---

## 1. Architectural Overview

- **Namespace:** `Agency\Companion\`
- **Autoloading:** Native lightweight PSR-4 autoloader registered in `agency-companion.php`.
- **Safe Activation / Deactivation**: Deferring rewrite flushes using transient flags on activation, preventing expensive runtime cache flushes on standard loads.
- **CPT / Taxonomy Registry**: `src/CPT/PostTypeRegistry.php` registers custom post types with `show_in_rest => true` for compatibility with Block Editor, REST, and AI MCP adapters.
- **Modern Elementor 3.x Widgets**: `src/Elementor/WidgetLoader.php` uses `elementor/widgets/register` and registers custom sidebar categories via `elementor/elements/categories_registered`.
- **First Extracted Feature**: `src/Extracted/Astra/ScrollToTop.php` — A fully working, production-grade module extracted from Astra theme, completely decoupled from Astra's theme options and jQuery, rewritten to pure native vanilla ES6.

---

## 2. Travel & LIV Collective Elementor Widgets (`src/Elementor/Widgets/Travelin/`)

A production suite of 11 bespoke Elementor widgets decompiled and converted from the Next.js 16 + Tailwind 4 frontend of [Travel & LIV Collective](https://travelnliv.com/):

| Widget Class | Elementor Name | Description & Visual Controls |
| :--- | :--- | :--- |
| `Widget_Travelin_Hero` | `travelin-hero` | Cinematic video loop, atmospheric cloud layers, fluid clamp kinetic typography, primary CTA and micro-copy. |
| `Widget_Travelin_Pillars` | `travelin-pillars` | 3 narrative pillars (The Stay, The Days Out, The People) with organic tilted photo triplets. |
| `Widget_Travelin_Experience` | `travelin-experience` | "You had to be there" interactive full-width video with ambient sound toggle and blur play overlay. |
| `Widget_Travelin_Trips_Carousel` | `travelin-trips-carousel` | Destination tabs (Brazil, Punta Cana, Phuket, China), 3D perspective cards, dates, pricing, and arrow controls. |
| `Widget_Travelin_Stories_Reels` | `travelin-stories` | "Who's coming with you?" 3-reel video cards with independent audio toggle and scroll intersection observer. |
| `Widget_Travelin_Google_Reviews` | `travelin-google-reviews` | Centered 5.0 Google score pill, review cards repeater with reviewer avatars, and photo lightbox dialog. |
| `Widget_Travelin_Host_Bio` | `travelin-host-bio` | Olivia Owen founder portrait with caption, multi-paragraph bio, question link, and handwritten serif signature. |
| `Widget_Travelin_Moments_Strip` | `travelin-moments-strip` | Asymmetric 7-photo editorial collage grid with hover zoom micro-interactions. |
| `Widget_Travelin_FAQ` | `travelin-faq` | Numbered 6-item accordion ("Before you book") with smooth slide toggle and deep-link triggers. |
| `Widget_Travelin_Booking_Steps` | `travelin-booking-steps` | 3-step numbered booking journey timeline ("Found your trip? Here's what happens next."). |
| `Widget_Travelin_Final_CTA` | `travelin-final-cta` | Full-bleed hero image background banner, headline clamp, and primary/secondary contact buttons. |

---

## 3. Assets & Enqueuing Pipeline

- **Scoped Layout CSS:** `assets/css/travelin/travelin-journey.css` — Namespaced under `.travelin-scope` with CSS custom properties and fluid typography tokens.
- **Reviews CSS:** `assets/css/travelin/travelin-reviews.css` — Dedicated styles for Google Reviews cards, tilts, and modal lightbox dialog.
- **Frontend Interactions JS:** `assets/js/travelin/travelin-frontend.js` — ES6 handlers hooked into `elementor/frontend/init` and `elementorFrontend.hooks.addAction('frontend/element_ready/...')`.
