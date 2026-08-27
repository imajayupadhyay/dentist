# Project Workflow & Context

> **Read this file first.** It is the living context document for this project.
> It records the rules, the current structure, the decisions already taken and the work
> still pending, so any new session (human or AI) can continue without losing context.
>
> **Last updated:** 2026-08-27

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
Three such changes already exist (§6). When one happens:

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
│       ├── admin/
│       │   └── admin.css           ← admin-only, selectors prefixed `admin-`
│       └── responsive.css          ← ALWAYS imported last
│
└── js/
    ├── Components/
    │   └── Global/                 ← shared across every page
    │       ├── Header.vue
    │       └── Footer.vue
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
        └── Admin/                  ← admin panel only; never mix with public pages
            ├── Auth/
            │   └── Login.vue
            ├── Dashboard/
            │   └── Index.vue
            └── Components/
                ├── AdminShell.vue
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

### 2.6 Frontend-only scope (public site)

- Implement the **frontend only**.
- Do **not** modify controllers, models, migrations, database schema, APIs or auth
  unless explicitly asked.
- Routes may be added only where needed to render a page.
- The appointment form is **front-end only** — it validates and shows a success state
  in the browser and submits nothing. Wiring it to the backend is future work (§7).

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
└── Admin/
    ├── Auth/       { Login.vue }
    ├── Dashboard/  { Index.vue }
    └── Components/ { AdminShell.vue, Sidebar.vue, Topbar.vue }
```

Page-specific components never mix between pages.

---

## 3. Current structure

### Backend

| File | Role |
|---|---|
| `routes/web.php` | `/` → `Inertia::render('Home/Index')`; `/about-us` → `Inertia::render('About/Index')` |
| `routes/admin.php` | `/drpushpa-secure-login`, `/admin`, `/admin/dashboard`, `/admin/logout` |
| `app/Http/Controllers/Admin/Auth/LoginController.php` | Admin login/logout, validation, throttling, session regeneration |
| `app/Http/Middleware/EnsureAdmin.php` | Blocks non-admin authenticated users from admin routes |
| `app/Http/Middleware/HandleInertiaRequests.php` | Shares `appName`, `auth.user`, `flash` |
| `bootstrap/app.php` | Registers web/admin route files, Inertia middleware, `admin` alias, auth redirects |
| `database/migrations/2026_08_27_180000_add_is_admin_to_users_table.php` | Adds the `users.is_admin` admin gate |
| `app/Models/User.php` | Casts `is_admin` to boolean; password remains hashed |
| `resources/views/app.blade.php` | Root template (`@inertia`, `@inertiaHead`, `@vite`) |

### Frontend

See the tree in §2.2 — it reflects the current state of the repo.

### Static assets

Images and video from the source HTML live in `public/assets/`.
Referenced from Vue as absolute paths: `/assets/hero-smile.jpg`.
They are **not** imported through Vite — they are static files served from `public/`,
which keeps the markup identical to the source HTML.

---

## 4. Page section maps

### Home page

`Pages/Home/Index.vue` composes, in order:

| # | Component | Anchor | Notes |
|---|---|---|---|
| 1 | `Hero.vue` | `#hero` | 3-slide carousel: autoplay 6.5s, dots w/ progress fill, arrows, swipe, arrow keys, pause on hover |
| 2 | `About.vue` | `#about` | Intro copy + 4 stat tiles |
| 3 | `Treatments.vue` | `#treatments` | 6 full-bleed colour bands, alternating `.flip`, driven by a data array |
| 4 | `Stories.vue` | `#stories` | Horizontal video rail, one clip plays at a time, pauses when scrolled away |
| 5 | `Reviews.vue` | `#reviews` | Google rating summary + 6 review cards |
| 6 | `Contact.vue` | `#contact` | Map iframe + appointment form (front-end validation only) |

Header and footer come from `AppLayout.vue`. `Header.vue` also renders the
full-screen mobile drawer (§5.8) as its second root node.

### About Us page

`Pages/About/Index.vue` composes, in order:

| # | Component | Anchor | Notes |
|---|---|---|---|
| 1 | `Masthead.vue` | `#ab-hero` | Editorial hero, image stack, 5-star proof chip, desktop-only parallax from `about.js` ported with cleanup |
| 2 | `Figures.vue` | — | Four-stat hairline strip, count-up animation on reveal |
| 3 | `FoundersNote.vue` | `#note` | Full-bleed founder's note band |
| 4 | `Values.vue` | `#values` | Four operating principles rendered from a data array |
| 5 | `Team.vue` | `#team` | Lead team image, clinician roster, certification chips |
| 6 | `Cta.vue` | — | Brand CTA band above the global footer |

About page CSS lives in `resources/css/design/about/about.css`. It is copied
byte-for-byte from `/Users/ajayupadhyay/Desktop/Dentist/drpuspa/assets/css/about.css`
and imported after the shared footer styles, before the final global `responsive.css`.

### Admin panel

| Page | Route | Component | Notes |
|---|---|---|---|
| Login | `/drpushpa-secure-login` | `Admin/Auth/Login.vue` | Title: `Doctor Pushpa - Secure Login`; posts to `/drpushpa-secure-login` |
| Dashboard | `/admin/dashboard` | `Admin/Dashboard/Index.vue` | Basic dashboard only; wrapped in `AdminShell` |

Admin chrome:

| Component | Role |
|---|---|
| `Admin/Components/AdminShell.vue` | Composes sidebar, topbar and page slot |
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
    Route: `/about-us` → `Inertia::render('About/Index')`. The HTML sections were
    converted into page-scoped Vue components under `Pages/About/Components/`.
    The supplied page stylesheet is preserved verbatim as `design/about/about.css`;
    behaviour from `about.js` was ported to Vue with `refs`,
    `onMounted`/`onBeforeUnmount`, and no leaked listeners. The About page uses
    `<Head>` for its title and meta description.

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

---

## 6. Deviations from the source file (and why)

Four, and they are the *only* places the build differs from the source documents.
The first is a restoration; the other three are changes the user asked for.

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

These are recorded decisions — see the note in §2.1 before reverting any of them.

Nothing else was changed. All copy, colours, imagery and layout are as supplied.
`design/about/about.css` is byte-for-byte identical to the supplied About page CSS.

> **Note:** the source footer carries a "Demonstration build" disclaimer — all copy,
> statistics, reviews, contact details, hours, photography and video are placeholder
> content and must be replaced before launch. That disclaimer has been preserved.

---

## 7. Pending / future work

- [ ] Wire the appointment form to the backend (route + FormRequest + mail/DB) using
      Inertia's `useForm`. Currently front-end only.
- [ ] Replace all placeholder content (copy, stats, reviews, phone, email, address, hours).
      Includes the WhatsApp number in `Components/Global/Footer.vue` (`wa.me/919820000000`).
- [ ] Replace stock photography and the generated sample videos.
- [ ] Point the map iframe at the real clinic location.
- [ ] Add the real Google reviews link on the "Write a review" button (`href="#"` today).
- [ ] Add legal pages: privacy policy, terms, sitemap (footer links are `href="#"`).
- [ ] SEO: the home page's meta description is still set in `app.blade.php`; move it to
      a per-page Inertia `<Head>` like the About page.
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
Admin foundation.
