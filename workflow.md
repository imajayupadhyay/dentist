# Project Workflow & Context

> **Read this file first.** It is the living context document for this project.
> It records the rules, the current structure, the decisions already taken and the work
> still pending, so any new session (human or AI) can continue without losing context.
>
> **Last updated:** 2026-08-29

---

## 1. Project overview

**Dr. Pushpa Patel's Dental Clinic** — a Laravel + Inertia + Vue monolith.

| Layer | Choice |
|---|---|
| Backend | Laravel 12.68 (PHP 8.2) |
| Bridge | Inertia.js (`inertiajs/inertia-laravel` ^3.3 / `@inertiajs/vue3` ^3.7) |
| Frontend | Vue 3.5 (SFC, `<script setup>`, Composition API) |
| Build | Vite 7 + `laravel-vite-plugin` 2 |
| Styling | Bespoke CSS design system (+ Tailwind 4 utilities available) |
| DB | SQLite (default, dev) |

Single application, single deploy. **No separate API, no client-side router** — Laravel routes
return `Inertia::render()` and Vue components receive props directly.

---

## 2. Core rules

### 2.1 Design preservation (highest priority)

- The Vue implementation must match the supplied HTML design **same-to-same**.
- **Do not redesign, "improve", modernise or otherwise alter the UI.** Spacing, colour,
  type scale, radii, shadows, animation timing and copy are all part of the contract.
- The original CSS is treated as **source of truth** and is carried over **verbatim**.
  It is split across files for maintainability, but the *declarations are never rewritten*
  and the **cascade order is preserved exactly** as it was in the source document.
- Do not convert the bespoke CSS into Tailwind utilities. Rewriting it would guarantee
  visual drift and breaks the rule above.
- If the source HTML contains a bug, fix it only when the design intent is unambiguous,
  and **record the fix in §6 of this file**.

**The one exception — explicit user requests.** When the user asks for a change to the
design, build it. "Match the source" governs the *conversion*, not the project forever.
Recorded departures already exist (§6). When one happens:

1. Never edit a verbatim file to do it. Put new rules in a clearly-named non-source
   stylesheet (e.g. `design/mobile-nav.css`) or a bannered block at the end of an
   existing one, so provenance stays readable at a glance.
2. Record it in §5 (what and why) and list it in §6 (departures).
3. Leave superseded source rules in place unless you are certain nothing else uses
   them — see the `@keyframes drop` trap in §5.8.

Do **not** "restore" a departure listed in §6 back to the source design. Those are
decisions, not drift.

### 2.2 Directory structure

Component-based, page-scoped, scalable:

```text
resources/
├── css/
│   ├── app.css                     ← Tailwind entry + design-system imports (order matters)
│   └── design/
│       ├── tokens.css              ← :root variables, reset, .wrap, .dis, .eyebrow
│       ├── buttons.css
│       ├── sections.css            ← .sec, h2.dis, .lede, [data-rv] reveal
│       ├── header.css              ← global
│       ├── mobile-nav.css          ← full-screen drawer (non-source, redesign)
│       ├── footer.css              ← global (+ WhatsApp CTA block at end, non-source)
│       ├── home/                   ← page-scoped stylesheets
│       │   ├── hero.css
│       │   ├── about.css
│       │   ├── treatments.css
│       │   ├── stories.css
│       │   ├── reviews.css
│       │   └── contact.css
│       ├── about/                  ← page-scoped stylesheets
│       │   └── about.css           ← verbatim about.html page stylesheet
│       ├── treatments/
│       │   └── treatment.css       ← verbatim treatment.html page stylesheet + bannered rich-text additions
│       ├── admin/
│       │   └── admin.css           ← admin-only, selectors prefixed `admin-`
│       └── responsive.css          ← ALWAYS imported last
│
└── js/
    ├── Components/
    │   └── Global/                 ← shared across every page
    │       ├── Header.vue
    │       ├── Footer.vue
    │       └── RichText.vue         ← safe display helper for admin-authored rich text
    │
    ├── Composables/                ← shared reusable logic
    │   └── useScrollReveal.js
    │
    ├── Layouts/
    │   └── AppLayout.vue           ← composes Header + <slot> + Footer
    │
    └── Pages/
        ├── Home/
        │   ├── Index.vue           ← composes the page's sections only
        │   └── Components/         ← page-specific, never shared
        │       ├── Hero.vue
        │       ├── About.vue
        │       ├── Treatments.vue
        │       ├── Stories.vue
        │       ├── Reviews.vue
        │       └── Contact.vue
        ├── About/
        │   ├── Index.vue           ← composes the page's sections only
        │   └── Components/         ← page-specific, never shared
        │       ├── Masthead.vue
        │       ├── Figures.vue
        │       ├── FoundersNote.vue
        │       ├── Values.vue
        │       ├── Team.vue
        │       └── Cta.vue
        ├── Treatments/
        │   ├── Show.vue            ← dynamic treatment detail page
        │   └── Components/         ← treatment-detail-only sections
        │       ├── AccentHeading.vue
        │       ├── TreatmentHero.vue
        │       ├── TreatmentSectionNav.vue
        │       ├── TreatmentOverview.vue
        │       ├── TreatmentSuitability.vue
        │       ├── TreatmentProcess.vue
        │       ├── TreatmentFaq.vue
        │       ├── TreatmentRelated.vue
        │       └── TreatmentCta.vue
        └── Admin/                  ← admin panel only; never mix with public pages
            ├── Auth/
            │   └── Login.vue
            ├── Dashboard/
            │   └── Index.vue
            ├── Home/
            │   └── Form.vue
            ├── About/
            │   └── Form.vue
            ├── Contacts/
            │   └── Index.vue
            ├── Treatments/
            │   ├── Index.vue
            │   └── Form.vue
            └── Components/
                ├── AdminShell.vue
                ├── RichTextEditor.vue
                ├── Sidebar.vue
                └── Topbar.vue
```

**Rules:**

- A component used by **one page only** lives in that page's `Components/` directory.
- A component used by **more than one page** lives in `Components/Global/`.
- `Index.vue` is a **composition file**: it imports the section components and renders them.
  It should contain almost no markup of its own.
- Reusable logic goes in `Composables/`, not duplicated per component.
- Import with the `@` alias (`@/Components/Global/Header.vue`), already configured in
  `vite.config.js` → `resources/js`.
- Admin pages must stay under `resources/js/Pages/Admin/`. Public pages must not import
  admin-only components, and admin pages must not use public `AppLayout`.

### 2.3 Global header & footer

- `Header.vue` and `Footer.vue` are **global components**.
- They are rendered by `AppLayout.vue`, **not** duplicated inside each page.
- A page opts in simply by wrapping its content in `<AppLayout>`.
- Never copy header/footer markup into a page component.
- `Header.vue` is a **multi-root component**: the `.nav` bar *and* the `.mnav`
  full-screen drawer are siblings. The drawer must not be nested inside the bar —
  see §5.8 for why.

### 2.4 Admin panel architecture

- Admin routes live in `routes/admin.php`, not `routes/web.php`.
- Admin route names are prefixed with `admin.` and URLs are prefixed with `/admin`.
- Admin pages live in `resources/js/Pages/Admin/`.
- Admin-specific reusable components live in `resources/js/Pages/Admin/Components/`.
  `Sidebar.vue` and `Topbar.vue` are the shared admin chrome and should be reused
  across future Admin pages.
- `AdminShell.vue` composes `Sidebar` + `Topbar` + page content. New authenticated
  Admin pages should normally render inside `AdminShell`.
- Admin CSS lives in `resources/css/design/admin/admin.css`; keep selectors prefixed
  with `admin-` so the public website design remains isolated.
- Admin authentication uses Laravel's existing `web` session guard plus the `is_admin`
  flag on `users`. Protected admin routes use `auth` + `admin` middleware.
- Admin accounts are created manually with **PHP Artisan Tinker only**. Do not create
  seeders for admin credentials. Passwords must be hashed before storage.

### 2.5 Laravel + Vue + Inertia conventions

- Follow the architecture already in the repo — inspect before adding.
- Pages are resolved from `resources/js/Pages/**` by `resolvePageComponent` in
  `resources/js/app.js`. `Inertia::render('Home/Index')` maps to
  `resources/js/Pages/Home/Index.vue`.
- Shared props (`appName`, `auth.user`, `flash`) come from
  `app/Http/Middleware/HandleInertiaRequests.php`. Read them with `usePage()`.
- Use `<script setup>` + Composition API. Use `<Head>` from `@inertiajs/vue3` for page titles.
- Use Inertia's `<Link>` for internal navigation. Plain `<a>` is correct for
  same-page anchors (`#about`), `tel:` and `mailto:`.
- **Do not introduce new frameworks, UI kits, state managers or dependencies**
  without a clear need. No Vuex/Pinia, no component library, no jQuery.

### 2.6 Frontend/backend scope

- Implement the **frontend only**.
- Do **not** modify controllers, models, migrations, database schema, APIs or auth
  unless explicitly asked.
- Routes may be added only where needed to render a page.
- The homepage appointment form is the current explicit exception: it posts through
  Inertia to `/contact-submissions`, stores rows in `contact_submissions`, and admins
  manage follow-up in `/admin/contacts`.

### 2.7 Workflow maintenance (this file)

- `workflow.md` is the ongoing context/documentation file for the project.
- **Every meaningful change must be reflected here** — new pages, new components,
  structural changes, conventions, decisions and their reasoning.
- Keep §3 (structure), §5 (decisions), §6 (deviations) and §7 (pending work) current.
- Update the "Last updated" date at the top.
- The goal: another session can open this file and continue with full context.

### 2.8 Future scalability

Adding a page follows one repeatable pattern:

1. `resources/js/Pages/<Page>/Index.vue` + `resources/js/Pages/<Page>/Components/`
2. Page-only CSS (if any) in `resources/css/design/<page>/`, imported from `app.css`
   **above** `responsive.css`
3. A route returning `Inertia::render('<Page>/Index')`
4. Wrap the page in `<AppLayout>` so header/footer come along for free
5. Record it in this file

```text
Pages/
├── Home/     { Index.vue, Components/ }
├── About/    { Index.vue, Components/ }
├── Contact/  { Index.vue, Components/ }
├── Treatments/ { Show.vue, Components/ }
└── Admin/
    ├── Auth/       { Login.vue }
    ├── Dashboard/  { Index.vue }
    ├── Contacts/   { Index.vue }
    ├── Treatments/ { Index.vue, Form.vue }
    └── Components/ { AdminShell.vue, Sidebar.vue, Topbar.vue }
```

Page-specific components never mix between pages.

---

## 3. Current structure

### Backend

| File | Role |
|---|---|
| `routes/web.php` | `/` → `HomeController`; `POST /contact-submissions` → homepage appointment storage; `/about-us` → `AboutController`; `/treatments/{treatment}` → dynamic treatment detail page |
| `routes/admin.php` | `/drpushpa-secure-login`, `/admin`, `/admin/dashboard`, `/admin/home`, `/admin/about`, `/admin/contacts`, `/admin/treatments`, `/admin/logout` |
| `app/Http/Controllers/HomeController.php` | Loads singleton `HomePage` content/SEO plus active treatments for the homepage |
| `app/Http/Controllers/ContactSubmissionController.php` | Stores homepage appointment form submissions in `contact_submissions` |
| `app/Http/Controllers/AboutController.php` | Loads singleton `AboutPage` content/SEO for the About Us page |
| `app/Http/Controllers/TreatmentController.php` | Public treatment detail page by slug; hides inactive treatments |
| `app/Http/Controllers/Admin/HomePageController.php` | Admin singleton editor for homepage SEO and non-treatment/non-review content |
| `app/Http/Controllers/Admin/AboutPageController.php` | Admin singleton editor for About page SEO and section content |
| `app/Http/Controllers/Admin/ContactSubmissionController.php` | Admin inbox for viewing homepage appointment requests and updating status/notes |
| `app/Http/Controllers/Admin/TreatmentController.php` | Admin CRUD for treatments, including optional public asset image uploads |
| `app/Http/Controllers/Admin/Auth/LoginController.php` | Admin login/logout, validation, throttling, session regeneration |
| `app/Http/Requests/ContactSubmissionRequest.php` | Public homepage appointment validation and input cleanup |
| `app/Http/Requests/Admin/HomePageRequest.php` | Admin homepage validation, rich-text sanitizing and repeat-row cleanup |
| `app/Http/Requests/Admin/AboutPageRequest.php` | Admin About page validation, rich-text sanitizing and repeat-row cleanup |
| `app/Http/Requests/Admin/TreatmentRequest.php` | Admin treatment validation and repeat-row cleanup |
| `app/Support/RichTextSanitizer.php` | Allowlist sanitizer for admin-authored treatment rich text |
| `app/Http/Middleware/EnsureAdmin.php` | Blocks non-admin authenticated users from admin routes |
| `app/Http/Middleware/HandleInertiaRequests.php` | Shares `appName`, `auth.user`, `flash`, dynamic `treatmentLinks` for global footer navigation |
| `bootstrap/app.php` | Registers web/admin route files, Inertia middleware, `admin` alias, auth redirects |
| `database/migrations/2026_08_27_180000_add_is_admin_to_users_table.php` | Adds the `users.is_admin` admin gate |
| `database/migrations/2026_08_29_090000_create_treatments_table.php` | Adds dynamic treatment homepage/detail content |
| `database/migrations/2026_08_29_101000_add_advanced_seo_fields_to_treatments_table.php` | Adds advanced treatment SEO fields for canonical, robots, social sharing and schema metadata |
| `database/migrations/2026_08_29_120000_create_home_pages_table.php` | Adds singleton homepage CMS content and SEO fields |
| `database/migrations/2026_08_29_130000_create_about_pages_table.php` | Adds singleton About page CMS content and SEO fields |
| `database/migrations/2026_08_29_140000_create_contact_submissions_table.php` | Adds stored homepage appointment/contact submissions |
| `database/migrations/2026_08_29_141000_add_contact_form_fields_to_home_pages_table.php` | Adds admin-editable homepage contact form copy/options to `home_pages` |
| `database/seeders/HomePageSeeder.php` | Seeds the original static homepage content into `home_pages` |
| `database/seeders/AboutPageSeeder.php` | Seeds the original static About page content into `about_pages` |
| `database/seeders/TreatmentSeeder.php` | Seeds the six original homepage treatments and starter detail-page content |
| `database/seeders/DatabaseSeeder.php` | Calls `HomePageSeeder`, `AboutPageSeeder` and `TreatmentSeeder` |
| `app/Models/HomePage.php` | Singleton homepage defaults, casts, admin/public serialization and SEO metadata |
| `app/Models/AboutPage.php` | Singleton About page defaults, casts, admin/public serialization and SEO metadata |
| `app/Models/ContactSubmission.php` | Stored contact/appointment request data, status options and admin serialization |
| `app/Models/Treatment.php` | Treatment casts, tone map, public/admin serialization helpers, slug route binding |
| `app/Models/User.php` | Casts `is_admin` to boolean; password remains hashed |
| `resources/views/app.blade.php` | Root template (`@inertia`, `@inertiaHead`, `@vite`) |

### Frontend

See the tree in §2.2 — it reflects the current state of the repo.

### Static assets

Images and video from the source HTML live in `public/assets/`.
Referenced from Vue as absolute paths: `/assets/hero-smile.jpg`.
They are **not** imported through Vite — they are static files served from `public/`,
which keeps the markup identical to the source HTML.
Admin-uploaded homepage assets are stored in `public/assets/home/`; About page uploads
are stored in `public/assets/about/`; treatment uploads are stored in
`public/assets/treatments/`. All are saved as public `/assets/...` paths.

---

## 4. Page section maps

### Home page

`Pages/Home/Index.vue` composes, in order:

| # | Component | Anchor | Notes |
|---|---|---|---|
| 1 | `Hero.vue` | `#hero` | CMS-driven slides and trust metrics from `HomePage`; carousel keeps autoplay 6.5s, dots w/ progress fill, arrows, swipe, arrow keys, pause on hover |
| 2 | `About.vue` | `#about` | CMS-driven intro copy, CTA and stat tiles from `HomePage` |
| 3 | `Treatments.vue` | `#treatments` | Full-bleed colour bands, alternating `.flip`, driven by active `Treatment` records from the database; each band links to `/treatments/{slug}` |
| 4 | `Stories.vue` | `#stories` | CMS-driven heading and video/poster rows from `HomePage`; one clip plays at a time and pauses when scrolled away |
| 5 | `Reviews.vue` | `#reviews` | Google rating summary + 6 review cards |
| 6 | `Contact.vue` | `#contact` | CMS-driven heading, map iframe, form copy and dropdown options; posts appointment requests to `/contact-submissions` |

Header and footer come from `AppLayout.vue`. `Header.vue` also renders the
full-screen mobile drawer (§5.8) as its second root node.

Home page SEO is generated from the singleton `HomePage` record. The public route
passes the backend SEO payload into Blade for crawlers/social scrapers, and
`Pages/Home/Index.vue` uses the same payload inside Inertia `<Head>` for client-side
navigation. The homepage Admin editor deliberately excludes Treatments, Reviews,
Header and Footer because those are managed separately; contact form copy/options
are now part of the Home content tab.

### About Us page

`Pages/About/Index.vue` composes, in order:

| # | Component | Anchor | Notes |
|---|---|---|---|
| 1 | `Masthead.vue` | `#ab-hero` | CMS-driven editorial hero, image stack, proof chip, CTA buttons and meta items; desktop-only parallax from `about.js` ported with cleanup |
| 2 | `Figures.vue` | — | CMS-driven hairline stat strip with count-up animation on reveal |
| 3 | `FoundersNote.vue` | `#note` | CMS-driven full-bleed founder's note band |
| 4 | `Values.vue` | `#values` | CMS-driven operating principles rendered from repeat rows |
| 5 | `Team.vue` | `#team` | CMS-driven lead team image, clinician roster and certification chips |
| 6 | `Cta.vue` | — | CMS-driven brand CTA band above the global footer |

About page CSS lives in `resources/css/design/about/about.css`. It is copied
from `/Users/ajayupadhyay/Desktop/Dentist/drpuspa/assets/css/about.css`, then a
clearly bannered non-source rich-text block was appended for admin-authored
paragraph/list/link formatting. It is imported after the shared footer styles,
before the final global `responsive.css`.

About page SEO is generated from the singleton `AboutPage` record. The public route
passes the backend SEO payload into Blade for crawlers/social scrapers, and
`Pages/About/Index.vue` uses the same payload inside Inertia `<Head>` for
client-side navigation.

### Treatment detail pages

`Pages/Treatments/Show.vue` composes, in order:

| # | Component | Anchor | Notes |
|---|---|---|---|
| 1 | `TreatmentHero.vue` | — | Dynamic hero from `Treatment`; carries `data-tone`, breadcrumbs, facts, WhatsApp and phone CTAs |
| 2 | `TreatmentSectionNav.vue` | — | Sticky pill nav generated from template section IDs: Overview, Is it for you?, How it works, FAQs |
| 3 | `TreatmentOverview.vue` | `#overview` | Dynamic overview heading, lede, body paragraphs, image and caption |
| 4 | `TreatmentSuitability.vue` | `#suitability` | Dynamic usually-good-fit and treat-first lists |
| 5 | `TreatmentProcess.vue` | `#process` | Dynamic numbered steps |
| 6 | `TreatmentFaq.vue` | `#faq` | Native `<details>` FAQs, one open at a time |
| 7 | `TreatmentRelated.vue` | — | Automatically shows the first three other active treatments |
| 8 | `TreatmentCta.vue` | — | Dynamic CTA band using the treatment tone |

Treatment detail CSS lives in `resources/css/design/treatments/treatment.css`.
It was copied unchanged from
`/Users/ajayupadhyay/Desktop/Dentist/drpuspa/assets/css/treatment.css`, then a
clearly bannered non-source rich-text block was appended for admin-authored
`strong`, `em`, `mark`, lists, links and line breaks. It is imported above
`admin.css` and the final global `responsive.css`.

Treatment detail SEO is generated from each `Treatment` record. The public route
passes a backend SEO payload into the initial Blade response for crawlers/social
scrapers, while `Pages/Treatments/Show.vue` uses the same payload inside Inertia
`<Head>` for client-side navigation. The payload includes title, description,
canonical URL, robots, keywords, Open Graph, Twitter card, article dates and
JSON-LD (`WebSite`, `WebPage`, `BreadcrumbList`, treatment/service schema and
FAQ schema when FAQs exist).

### Admin panel

| Page | Route | Component | Notes |
|---|---|---|---|
| Login | `/drpushpa-secure-login` | `Admin/Auth/Login.vue` | Title: `Doctor Pushpa - Secure Login`; posts to `/drpushpa-secure-login` |
| Dashboard | `/admin/dashboard` | `Admin/Dashboard/Index.vue` | Basic dashboard only; wrapped in `AdminShell` |
| Home | `/admin/home` | `Admin/Home/Form.vue` | Singleton homepage editor with SEO and Content tabs; manages hero, about, stories, contact-map and contact-form content |
| About | `/admin/about` | `Admin/About/Form.vue` | Singleton About page editor with SEO and Content tabs; manages masthead, figures, founder note, values, team and CTA |
| Contacts | `/admin/contacts` | `Admin/Contacts/Index.vue` | Admin inbox for homepage appointment requests, with status and notes controls |
| Treatments list | `/admin/treatments` | `Admin/Treatments/Index.vue` | Lists treatments with visible/hidden state, public view, edit and delete actions |
| Treatment create | `/admin/treatments/create` | `Admin/Treatments/Form.vue` | Tabbed editor: SEO, Home and Content; SEO tab has search, crawl, social and schema sections; long detail copy uses `RichTextEditor` |
| Treatment edit | `/admin/treatments/{slug}/edit` | `Admin/Treatments/Form.vue` | Same tabbed editor as create; supports optional content/social image uploads to `public/assets/treatments/` and WYSIWYG rich text |

Admin chrome:

| Component | Role |
|---|---|
| `Admin/Components/AdminShell.vue` | Composes sidebar, topbar and page slot |
| `Admin/Components/RichTextEditor.vue` | Small allowlisted WYSIWYG editor for CMS paragraph fields |
| `Admin/Components/Sidebar.vue` | Reusable admin navigation |
| `Admin/Components/Topbar.vue` | Reusable page topbar and logout action |

Admin account status: the initial admin user was created with Artisan Tinker
(`admin@gmail.com`, name `Admin`, `is_admin=true`). The password supplied by the
user was hashed before storage; do not record plaintext credentials in this file.

---

## 5. Implementation decisions

1. **CSS carried over verbatim, split by concern.**
   The design is a bespoke CSS system, not Tailwind. It was extracted from the source
   `<style>` block **without rewriting a single declaration** and split into files that
   mirror the component tree. `app.css` imports them **in the original source order**, so
   the source rules resolve in exactly the order they did in the original document.
   Non-source stylesheets (§6) are interleaved at the point they apply, but they only
   ever add selectors — no verbatim declaration is overridden or reordered.

2. **Tailwind Preflight is deliberately disabled.**
   `app.css` imports Tailwind's `theme` and `utilities` layers but **not** `preflight`.
   The handed-over design ships its own reset (`box-sizing`, `img`, `a`, `button`) and
   sets every margin explicitly. Preflight would fight it — it restyles headings, lists
   and paragraphs — and cause visual drift, violating rule §2.1. Tailwind utility classes
   remain fully available for future pages.

3. **Static assets in `public/assets/`, not Vite-processed.**
   Keeps `src` attributes identical to the source HTML and avoids per-image imports.

4. **Behaviour ported to Vue idioms, not copied as DOM scripting.**
   The original IIFE used `getElementById` and `classList`. In Vue that logic is
   reactive state (`refs`, `computed`, `:class`) with `onMounted`/`onBeforeUnmount`
   cleanup. Visual output and timings are unchanged — only the mechanism differs.
   Timers, listeners and `IntersectionObserver`s are all torn down on unmount so
   Inertia's client-side navigation does not leak them.

5. **Scroll-reveal extracted to a composable.**
   Every section used `[data-rv]` + a shared `IntersectionObserver`. That is now
   `useScrollReveal()` — one observer, registered per component, disconnected on unmount.

6. **Repeated markup driven by data arrays.**
   Treatments, reviews, stories, nav links and footer links are arrays rendered with
   `v-for`. Same DOM output, far less duplication, and content becomes trivial to move
   to the backend later.

7. **WhatsApp action added to the footer emergency CTA.**
   The band previously held one button. The two actions are now wrapped in
   `.ft-cta-actions`, which takes over the `margin-left:auto` so the cluster stays
   right-aligned as one unit (leaving `auto` on both buttons would have split them
   apart across the free space). The WhatsApp button reuses the band's existing
   translucent-white treatment rather than WhatsApp green — green on the brand red
   clashes and would introduce a colour outside the palette. Its leading glyph opts
   out of `.btn:hover svg{transform:translateX(4px)}`, which is meant for trailing
   arrows. Styles live in a clearly-bannered block at the end of
   `design/footer.css`; everything above that banner is still verbatim source.
   Link: `https://wa.me/919820000000` with a prefilled message.

8. **Mobile nav redesigned as a full-screen right-to-left drawer.**
   Replaces the original `.sheet` dropdown at the user's request. Styles live in
   `design/mobile-nav.css` (clearly non-source); the verbatim `header.css` and
   `responsive.css` were left untouched, so their `.sheet` rules are now dead but
   harmless — `@keyframes drop` in `header.css` must stay regardless, because
   `contact.css` `.c-ok.show` still animates with it.
   Three constraints shaped the implementation:
   - The drawer is a **sibling of `.nav`, never a child of `.nav-in`** — `.nav-in`
     carries `backdrop-filter`, which makes it a containing block for
     `position:fixed` descendants and would trap the panel inside the pill.
     `Header.vue` is therefore a multi-root component.
   - It sits at `z-index:190`, just under the nav's `200`, so the floating pill
     stays above it and the burger's existing X morph doubles as the close control.
   - `visibility` is transitioned with a delay rather than toggling `display`, so
     the slide animates in both directions while closed links stay untabbable
     (`tabindex="-1"` reinforces this).
   Also handles: Escape to close, body scroll lock via `body.mnav-lock`,
   auto-close on resize past 860px, and a `@media(min-width:861px)` display guard.

9. **`AppLayout.vue` rewritten to match this design.**
   The previous scaffold layout (Tailwind demo chrome) was replaced by
   `Header` + `<slot>` + `Footer`. The old placeholder `Pages/Home.vue` and
   `Pages/About.vue` scaffold pages were removed — superseded by `Pages/Home/Index.vue`.

10. **About Us page added from `about.html`.**
    Route: `/about-us` → `AboutController`. The HTML sections were converted into
    page-scoped Vue components under `Pages/About/Components/`. The supplied page
    stylesheet is preserved as source CSS in `design/about/about.css`, with only
    the bannered rich-text additions listed in §6 appended after the source rules;
    behaviour from `about.js` was ported to Vue with `refs`,
    `onMounted`/`onBeforeUnmount`, and no leaked listeners. The About page uses
    admin-managed `<Head>` metadata from `AboutPage`.

11. **Global navigation now spans pages.**
    The header's requested **About Us** item routes to `/about-us`. Home-section
    links point back to `/#...` from other pages, while same-page hashes are
    normalised to `#...` so in-page scrolling remains native. The Doctors item
    points to `/about-us#team`. Inertia `<Link>` is used for route changes;
    plain anchors are retained for same-page hashes, `tel:` and `mailto:`.

12. **Admin panel foundation added as a separate architecture.**
    `routes/admin.php` owns every admin route. `bootstrap/app.php` loads both web
    route files and registers the `admin` middleware alias. The Admin UI lives
    under `resources/js/Pages/Admin/`; public components and layouts are not reused
    for the admin shell. Authentication uses the existing `web` session guard,
    `LoginController`, request validation, login throttling, session regeneration,
    `/admin/logout`, and the `users.is_admin` flag. The dashboard is intentionally
    minimal until real modules are requested.

13. **Treatments are now database-backed.**
    `Treatment` records own both homepage-card content and detail-page content.
    Homepage fields are separate from detail fields so a short band can stay concise
    while the landing page carries longer copy. Repeatable structures (`facts`,
    `suitable_for`, `not_suitable`, `steps`, `faqs`) are JSON arrays, which avoids
    a schema change when a treatment needs one more fact, step or FAQ. Active records
    are ordered by `sort_order`; inactive records remain editable in Admin but are
    hidden from the homepage and public detail route.

14. **The supplied treatment detail template was converted to a dynamic page.**
    Route: `/treatments/{treatment:slug}`. The detail page uses
    `Pages/Treatments/Show.vue` and page-scoped components under
    `Pages/Treatments/Components/`. The supplied `treatment.css` is preserved
    unchanged in `design/treatments/treatment.css`. The original template's
    page-specific behaviour was ported to Vue: the section nav is rendered from
    the active sections, scroll spy uses `IntersectionObserver`, scroll reveal reuses
    `useScrollReveal()`, and FAQs keep native `<details>` with exclusive opening.

15. **Admin Treatments module added.**
    Admin treatment routes are resource routes under `/admin/treatments`, protected
    by the existing `auth` + `admin` middleware. The form has the two requested
    sections: homepage treatment container data and treatment landing-page data.
    It validates all fields through `TreatmentRequest`, strips blank repeat rows,
    stores uploaded images under `public/assets/treatments/`, and keeps uploaded
    image paths as public `/assets/treatments/...` URLs to match the existing static
    asset convention.

16. **Treatment links are shared for global footer navigation.**
    `HandleInertiaRequests` now shares active treatment links as `treatmentLinks`.
    `Footer.vue` uses those links when available and falls back to the original
    static homepage anchors if the table is unavailable during setup.

17. **CMS paragraph fields support WYSIWYG editing.**
    `Admin/Components/RichTextEditor.vue` is a small project-local editor using
    `contenteditable` and browser editing commands. It supports bold, italic,
    highlight, bulleted lists, numbered lists, line breaks, links and clear
    formatting. It is used for long treatment detail fields and homepage paragraph
    fields such as hero slide copy, homepage/About ledes, founder notes, value-card
    copy and CTA bodies. `TreatmentRequest`, `HomePageRequest` and
    `AboutPageRequest` sanitize those fields before validation/storage through
    `RichTextSanitizer`, which allows only `p`, `br`, `strong`, `em`, `i`, `mark`,
    `ul`, `ol`, `li` and safe `a` links. Public pages render those fields through
    `Components/Global/RichText.vue`; legacy plain text is converted to paragraphs
    for display.

18. **Admin treatment editing is split into tabs.**
    `Admin/Treatments/Form.vue` now presents three horizontal tabs: SEO, Home and
    Content. Only the selected panel is shown, which keeps the treatment editor
    from becoming one long stacked page. SEO owns the slug and search metadata,
    Home owns the homepage treatment band, and Content owns the treatment landing
    page sections. The tab bar marks panels with validation errors and automatically
    opens the first tab that needs attention after a failed save.

19. **Treatment SEO is editable and rendered server-side.**
    The SEO tab now manages search basics, crawl controls, social sharing metadata
    and structured data controls for each treatment. `Treatment::toSeoMeta()`
    builds a single payload used by both the initial Blade response and
    `Treatments/Show.vue`, so direct loads expose canonical, robots, Open Graph,
    Twitter and JSON-LD metadata before JavaScript runs, while Inertia navigation
    still updates the document head on the client. Server-rendered SEO tags are
    marked `data-server-seo` and removed after hydration to avoid duplicate tags.

20. **Homepage content is CMS-backed through a singleton record.**
    `home_pages` stores one row keyed `home`. `HomePage::defaultAttributes()` and
    `HomePageSeeder` seed the current static source content so the public page does
    not visually change after migration. `Admin/Home/Form.vue` edits the user-
    requested areas: Hero slides/trust metrics, About intro/CTA/stats, Patient
    Stories heading/video/poster rows, Contact heading/map iframe, and homepage
    appointment form copy/options. Treatments, Reviews, Header and Footer are
    intentionally excluded because they already are, or will be, managed by separate
    systems. Admin uploads for these homepage sections are stored under
    `public/assets/home/`.

21. **Homepage SEO is editable and rendered server-side.**
    The Home admin page has a separate SEO tab with search basics, robots controls,
    Open Graph/Twitter fields and clinic schema controls. `HomePage::toSeoMeta()`
    feeds both `HomeController::withViewData()` for initial Blade output and
    `Pages/Home/Index.vue` for Inertia `<Head>`, matching the treatment SEO path.

22. **About page content is CMS-backed through a singleton record.**
    `about_pages` stores one row keyed `about`. `AboutPage::defaultAttributes()`
    and `AboutPageSeeder` seed the current static About page content so the public
    page does not visually change after migration. `Admin/About/Form.vue` edits
    every visible About page section: Masthead, Figures, Founder's Note, Values,
    Team and CTA. The editor uses separate heading-before/highlight/heading-after
    fields where the source markup has text after `<em>`, so admins can change copy
    without losing the original highlight structure. Admin uploads for About page
    imagery are stored under `public/assets/about/`.

23. **About page SEO is editable and rendered server-side.**
    The About admin page has a separate SEO tab with the same search basics, robots,
    social sharing and schema controls as Home. `AboutPage::toSeoMeta()` feeds both
    `AboutController::withViewData()` for initial Blade output and
    `Pages/About/Index.vue` for Inertia `<Head>`, matching the Home and Treatment
    SEO paths.

24. **Homepage appointment requests are stored and managed in Admin.**
    The public `Contact.vue` form now uses Inertia `useForm` and posts to
    `/contact-submissions`. `ContactSubmissionRequest` validates/normalizes the
    patient fields, and `ContactSubmissionController` stores the request with
    source, status, IP and user-agent metadata. Admins review the latest requests at
    `/admin/contacts`, where `Admin/Contacts/Index.vue` supports status changes and
    internal notes.

---

## 6. Deviations from the source file (and why)

Ten, and they are the *only* places the build differs from the source documents.
The first is a restoration; the other nine are changes the user asked for.

**A. Restoration — a bug in the source file**

- **`index2.html` was missing two base rules** that its sibling `index.html` defines:
  `.eyebrow{…}` / `.eyebrow::before{…}` (the pill label) and `.dis em{…}` (the brand-coloured
  word with the gold highlighter). Without them the eyebrow pills render as plain uppercase
  text and every `<em>` in a heading loses its highlight.
  The design intent is unambiguous — `index2.html` still contains
  `.hero h1 em{color:#fff}` with the comment *"keeps the gold highlight, drops the red on dark"*,
  which only makes sense if `.dis em` exists.
  **Both rules were restored verbatim from `index.html`** into `design/tokens.css`.

**B. Deliberate, user-requested departures**

- **Footer emergency CTA** now carries a WhatsApp action beside the call button (§5.7).
  Styles: bannered block at the end of `design/footer.css`.
- **Mobile navigation** replaced — the source's dropdown `.sheet` panel is gone, and a
  full-screen drawer sliding in from the right takes its place (§5.8).
  Styles: `design/mobile-nav.css`.
  The `.sheet` rules in `header.css` and `responsive.css` are now **dead code**, left
  in place deliberately: `header.css` also defines `@keyframes drop`, which
  `contact.css` `.c-ok.show` still depends on.
- **Global header navigation** now has an **About Us** item that routes to the new
  `/about-us` page, and Doctors routes to `/about-us#team` (§5.11). This replaces
  the source's same-page About/Doctors anchor behaviour at the user's request.
- **Homepage treatment bands are now full-card links** to `/treatments/{slug}`.
  The source used non-clickable `<article>` bands with only a "Read more" anchor.
  The dynamic Vue implementation renders each band as an Inertia link so clicking
  anywhere on the container opens the relevant treatment page. A bannered non-source
  `.tband{display:block}` rule was added at the end of `design/home/treatments.css`
  to preserve the original block layout after changing the element semantics.
- **Treatment detail paragraph fields now render admin-authored rich text.**
  The source template used static paragraph markup. At the user's request, the
  dynamic version allows bold, italic, highlighted text, lists, links and line
  breaks in long treatment detail copy. A bannered non-source rich-text block was
  appended to `design/treatments/treatment.css` so the formatting displays cleanly
  inside the existing template without rewriting source rules.
- **Treatment detail pages now output admin-managed SEO metadata.**
  The source template had static head metadata. Dynamic treatment pages now use
  admin-authored search, social and schema fields, including server-rendered
  Open Graph/Twitter tags and JSON-LD for treatment pages.
- **Homepage non-treatment content and SEO are now admin-managed.**
  The source homepage sections were static. At the user's request, Hero, About,
  Patient Stories, the Contact heading/map and the contact form copy/options are now driven by the singleton
  `HomePage` record and editable at `/admin/home`, with a separate SEO tab for
  search/social/schema metadata. The seeded defaults match the supplied source
  content; Treatments, Reviews, Header and Footer are deliberately excluded.
  Minimal bannered rich-text display additions were appended to
  `design/home/hero.css`, `design/home/about.css` and `design/home/contact.css`.
- **About page content and SEO are now admin-managed.**
  The source About page sections were static. At the user's request, Masthead,
  Figures, Founder's Note, Values, Team and CTA are now driven by the singleton
  `AboutPage` record and editable at `/admin/about`, with a separate SEO tab for
  search/social/schema metadata. The seeded defaults match the supplied source
  content. A minimal bannered rich-text display block was appended to
  `design/about/about.css`.
- **Homepage contact form now persists submissions.**
  The source form validated only in the browser. At the user's request, it now posts
  through Inertia to Laravel, stores each request in `contact_submissions`, and adds
  a protected `/admin/contacts` inbox for viewing requests and managing follow-up
  status/notes.

These are recorded decisions — see the note in §2.1 before reverting any of them.

Nothing else was changed. Default copy, colours, imagery and layout are as supplied.
`design/about/about.css` is still source CSS plus the clearly marked rich-text
addition described above.

> **Note:** the source footer carries a "Demonstration build" disclaimer — all copy,
> statistics, reviews, contact details, hours, photography and video are placeholder
> content and must be replaced before launch. That disclaimer has been preserved.

---

## 7. Pending / future work

- [ ] Replace all placeholder content (copy, stats, reviews, phone, email, address, hours).
      Includes the WhatsApp number in `Components/Global/Footer.vue` (`wa.me/919820000000`).
- [ ] Clinician-review the AI-generated starter treatment detail-page copy seeded by
      `TreatmentSeeder` before launch.
- [ ] Replace stock photography and the generated sample videos.
- [ ] Point the map iframe at the real clinic location.
- [ ] Add the real Google reviews link on the "Write a review" button (`href="#"` today).
- [ ] Add legal pages: privacy policy, terms, sitemap (footer links are `href="#"`).
- [ ] Admin modules are only a foundation today. Future modules should be added under
      `resources/js/Pages/Admin/<Module>/`, routed from `routes/admin.php`, and wrapped
      in `AdminShell`.
- [ ] Replace the temporary admin dashboard placeholder metrics once real backend
      modules exist.
- [ ] A11y: the mobile drawer sets `aria-modal` and closes on Escape, but does not trap
      focus. Add a focus trap (and restore focus to the burger on close) if this needs
      to meet WCAG properly.
- [ ] Once the `.sheet` markup is confirmed gone for good, the dead `.sheet` rules could
      be pruned from `header.css` / `responsive.css` — but keep `@keyframes drop` (§6).

**Done** (kept for context, do not redo): favicon wired to `/assets/logo.png`;
home-page meta description set; Poppins loaded from Google Fonts; About Us page
added at `/about-us`; global header About Us and Doctors links wired; `npm run build`
passes after the About page conversion; base migrations and admin migration ran;
initial admin account created via Tinker with a hashed password; Admin login and
basic dashboard added; `npm run build` and `php artisan test` pass after the
Admin foundation; Treatment model/migration/admin CRUD/public detail pages added;
six original homepage treatments seeded to the database with starter detail-page
content; WYSIWYG rich-text editor and sanitizer added for treatment paragraph
fields; Admin treatment form split into SEO/Home/Content tabs; `npm run build`
and `php artisan test` pass after the Treatments module; advanced treatment SEO
fields, server-rendered SEO tags and JSON-LD added; treatment SEO defaults seeded;
Home page CMS model/migration/admin editor added for Hero, About, Stories and
Contact map content; Home page SEO made admin-managed; `npm run build` and
`php artisan test` pass after the Home CMS module; About page CMS
model/migration/admin editor added for Masthead, Figures, Founder's Note, Values,
Team and CTA; About page SEO made admin-managed; `npm run build` and
`php artisan test` pass after the About CMS module; homepage appointment form posts
to the backend, stores `contact_submissions`, and is reviewable at `/admin/contacts`;
`php artisan test` and `npm run build` pass after the Contacts module.
