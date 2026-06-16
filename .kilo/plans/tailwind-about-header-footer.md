# Plan: AboutTailwind + Tailwind Header & Footer

## Goal
Create `/about2` (AboutTailwind page), rewrite header/footer in Tailwind, and apply the new components ONLY to `/about2` and `/contact2`.

## How it works

### Architecture
The key is that `TailwindPublicLayout.vue` already exists and is used by `ContactTailwind`. By pointing `AboutTailwind` to the same layout and updating the layout to use new Tailwind header/footer components, both `/about2` and `/contact2` get the new header/footer while original pages (`/about`, `/contact`, etc.) remain untouched.

```
Original pages:    PublicLayout → PublicHeader + PublicFooter  (template CSS)
Tailwind pages:    TailwindPublicLayout → TailwindPublicHeader + TailwindPublicFooter  (no template CSS)
```

---

## Steps

### 1. Create `TailwindPublicHeader.vue`
**File:** `resources/js/components/public/TailwindPublicHeader.vue`

Replicate the header from `PublicHeader.vue` using pure Tailwind CSS. Key elements:
- **Top bar:** dark background (#1D231F), email + phone left, social icons right
- **Lower header:** white background, logo left, nav center, search + language right, sticky on scroll
- **Navigation:** Home, Destinations (dropdown with countries/places), Tours (dropdown with categories), Blog, About, Contact, About 2, Contact 2
- **Mobile menu:** hamburger toggle
- **Search overlay:** full-screen panel with tour search (same logic as original)
- **Language selector:** stylized dropdown

Colors from template: header-top bg `#1D231F`, text `#081E2A`, green accent `#4DA528`, body font `Plus Jakarta Sans`

Import routes: `about, contact, contact2, home` from `@/routes`, `destinations`, `tours`, `blogIndex` as before.

Header is ~783 lines — aim for a concise Tailwind version (~250-350 lines) keeping only the functional portable logic (search, mobile menu, dropdowns, sticky header).

### 2. Create `TailwindPublicFooter.vue`
**File:** `resources/js/components/public/TailwindPublicFooter.vue`

Replicate the footer using pure Tailwind. Key elements from template CSS:
- **Background:** `#1D231F` (dark)
- **Main content:** 3-column grid (logo+description+contact info, services links, gallery images), pt 165px/pb 83px
- **Logo column:** logo 130px wide, white description text, green icon + white text for email/phone/address
- **Services column:** "Services" title (white, 25px margin-bottom), links in gray `#B4B4B4`, green on hover
- **Gallery column:** 3-column grid of images with 10px gap, 2px border-radius
- **Bottom bar:** border-top `rgba(242,242,242,0.07)`, white text 13px, SimplyIT credit + copyright + terms/privacy links
- **Back to top button:** green circle (already exists as Tailwind in current footer)

### 3. Update `TailwindPublicLayout.vue`
**File:** `resources/js/layouts/TailwindPublicLayout.vue`

Swap imports:
```diff
- import PublicHeader from '@/components/public/PublicHeader.vue';
- import PublicFooter from '@/components/public/PublicFooter.vue';
+ import TailwindPublicHeader from '@/components/public/TailwindPublicHeader.vue';
+ import TailwindPublicFooter from '@/components/public/TailwindPublicFooter.vue';
```

### 4. Create `AboutTailwind.vue`
**File:** `resources/js/pages/AboutTailwind.vue`

Tailwind rewrite of About page. Sections (with template CSS equivalents as reference):

| Section | Original CSS | Tailwind Approach |
|---------|-------------|-------------------|
| Breadcrumb | `breadcumb-section` (137px/135px padding, bg image, gradient overlay) | Same as ContactTailwind breadcrumb |
| About Content | `.about-us-h4` (103px/128px padding), images with offset positioning | 2-col grid, images with absolute positioning, green quote banner, dashed-border "25 years" box |
| Video | `.widget-video-about` with play button overlay | Centered play button over bg image, modal on click |
| Featured Tours | `.top-this-week-about-us` (370px/218px padding), tour listing cards | 3-column grid of simplified tour cards with image, badge, title, rating, price |
| CTA Banner | `.callt-to-action` (green bg, rounded 7px) | Same green CTA box from ContactTailwind |
| Services Grid | `.widget-service-h5` with icon-box-style1 cards | 4-column grid of service cards with icon, title, description, booking link |
| Stats Counter | `.counter-to` (bg image, gradient overlay, mask triangles) | Background image with overlay, 4-column grid of stats with icons, large numbers, white text |
| Instagram | `.instagram-post-grid` (5-col, 22px gap) | 5-column grid of images with hover overlay |
| Brands | `.brand-logo-widget` (Swiper carousel) | Horizontal scroll or static row of brand logos |
| CTA | Same as first CTA but with negative margin bottom | Green CTA box with `-mb-[93px]` |

Keep the Swiper script for brand logos and the video modal from the original. Use lucide-vue-next icons instead of icon font classes.

### 5. Add `/about2` route
**File:** `routes/web.php`

```php
Route::inertia('/about2', 'AboutTailwind')->name('about2');
```

Add import for the new route in the Tailwind header.

### 6. Register layout in `app.ts`
**File:** `resources/js/app.ts`

Add `case name === 'AboutTailwind': return TailwindPublicLayout;` to the layout switch (it's already separated from the original pages).

### 7. Run Wayfinder
```bash
php artisan wayfinder:generate --no-interaction
```

### 8. Lint
```bash
npx eslint resources/js/pages/AboutTailwind.vue resources/js/components/public/TailwindPublicHeader.vue resources/js/components/public/TailwindPublicFooter.vue resources/js/layouts/TailwindPublicLayout.vue
vendor/bin/pint --dirty --format agent
```

---

## Files summary

| File | Action |
|------|--------|
| `resources/js/components/public/TailwindPublicHeader.vue` | **Create** — Tailwind header |
| `resources/js/components/public/TailwindPublicFooter.vue` | **Create** — Tailwind footer |
| `resources/js/layouts/TailwindPublicLayout.vue` | **Edit** — swap to new header/footer |
| `resources/js/pages/AboutTailwind.vue` | **Create** — Tailwind about page |
| `resources/js/app.ts` | **Edit** — add AboutTailwind layout case |
| `routes/web.php` | **Edit** — add /about2 route |

No changes to `PublicHeader.vue`, `PublicFooter.vue`, `PublicLayout.vue`, `About.vue`, or `Contact.vue` — original pages remain fully intact.
