# Architecture & Component Reconnaissance: Travelin' Website

**Source Repository:** `https://github.com/UsamaAli-PK/Travelin-website.git`  
**Local Cloned Path:** `workspace/external-projects/travelin-website/`  
**Date of Audit:** 2026-11-20  
**Status:** Mapped & Cataloged (Ready for component selection)

---

## 1. Technology Stack Breakdown

| Layer | Technologies / Libraries | Role in Application |
| :--- | :--- | :--- |
| **Framework** | Next.js 16.3.4 (App Router) + React 19.2 + TypeScript 5.9 | Full-stack SSR/SSG and interactive client components |
| **Styling & Motion** | Tailwind CSS 4.2 + GSAP 3.15 + Lenis 1.3 (Smooth Scroll) | Fluid layout, typography masking, physics curves, smooth scroll |
| **UI Primitives** | Radix UI + Lucide React + Shadcn UI + Embla Carousel | Accessible dialogs, drawers, dropdowns, accordions, and carousels |
| **Visual 3D Canvas** | Cobe / WebGL Canvas (`cobe-globe-polaroids.tsx`) | 3D interactive rotating globe with floating polaroid pins |
| **Backend & CMS** | Payload CMS 3.90 (`@payloadcms/next`) embedded in Next.js | Headless CMS for trips, media, inquiries, and departures |
| **Database & ORM** | Drizzle ORM 0.45 + LibSQL / SQLite (`@libsql/client`) | High-speed local database storing relational data and form entries |

---

## 2. Page & Route Map (`app/(site)/`)

| Route Path | File Location | Purpose & Core Features |
| :--- | :--- | :--- |
| `/` | `app/(site)/page.tsx` | **Homepage**: Video hero, Experience grid, Google Reviews, Trips carousel, FAQ accordion. |
| `/trips` | `app/(site)/trips/page.tsx` | **Trips Catalog**: Filterable travel packages, pricing, departure badges, and waitlist modals. |
| `/trips/[slug]` | `app/(site)/trips/[slug]/page.tsx` | **Trip Detail**: Full itinerary, accommodation breakdown, WeTravel booking widget, countdown. |
| `/gallery` | `app/(site)/gallery/page.tsx` | **Photo Showcase**: Interactive 3D Circular Arc Gallery + lightbox view. |
| `/how-it-works` | `app/(site)/how-it-works/page.tsx` | **Guide & Process**: Step-by-step traveler onboarding, payment plans, community perks. |
| `/about` | `app/(site)/about/page.tsx` | **Story & Founder**: Brand mission, Olivia's host story, community philosophy. |
| `/blog` & `[slug]` | `app/(site)/blog/page.tsx` | **Editorial Content**: Travel guides, destination tips, and RichText Lexical articles. |
| `/contact` | `app/(site)/contact/page.tsx` | **Inquiries**: Interactive trip inquiry form with direct email routing. |
| `/partnerships` | `app/(site)/partnerships/page.tsx` | **B2B Inquiries**: Brand sponsorships, hotel partnerships, and creator collabs. |
| `/faq` | `app/(site)/faq/page.tsx` | **Support Center**: Categorized accordion FAQs (booking, refunds, solo travel). |
| Legal / Policies | `cookies/`, `privacy/`, `terms/` | Structured policy shells for terms, cancellation, and privacy policies. |

---

## 3. UI Components & Feature Catalog (`components/`)

### A. High-Impact Interactive Sections (Prime Conversion Candidates)
1. **Interactive 3D Globe with Polaroids** (`components/ui/cobe-globe-polaroids.tsx`):
   - Interactive WebGL globe rotating with momentum drag.
   - Pinned Polaroid photos with tilt rotations over geographic coordinates (San Francisco, NY, Tokyo, Paris, Sydney, London).
2. **Circular Arc Photo Gallery** (`components/gallery/circular-arc-gallery.tsx`):
   - Cylindrical perspective 3D arc gallery with touch-swipe, previous/next controls, and lightbox integration.
3. **Google Reviews & Testimonials Carousel** (`components/home/google-reviews.tsx`):
   - Review cards with star ratings, traveler tags ("Booked Solo"), reviewer avatar/initials, and modal reading drawer.
4. **Hero Atmosphere & Video Canvas** (`components/home/hero.tsx`):
   - Background video with atmospheric cloud layers, masked staggered typography, and action CTAs.
5. **Interactive Experience Grid** (`components/home/experience.tsx`):
   - Card grid with video hover preview, sound toggles, and solo traveler stories.
6. **Trip Detail Itinerary & Pricing Box** (`components/trips/trip-detail.tsx`):
   - Day-by-day travel timeline, included perks checklist, and WeTravel booking modal trigger.
7. **FAQ Accordion Experience** (`components/support/faq-experience.tsx`):
   - Smooth animated expanding/collapsing questions with zero layout shift.

---

## 4. Backend Collections & Data Models (`cms/collections/`)

- **`Departures.ts`**: Dates, availability, prices, room types (Private vs Shared), deposit amounts.
- **`Enquiries.ts`**: Leads captured from contact forms and trip questions.
- **`WaitlistEntries.ts`**: Waitlist capture when a departure is sold out.
- **`Posts.ts`**: Blog articles with RichText Lexical editor.
- **`Media.ts`**: Image and video assets.
