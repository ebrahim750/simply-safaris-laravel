# Plan: Fix Tailwind Blog/Tours Visual Gaps + Add Refactored Menu to Old Header

## 1. Add "Refactored" dropdown to `PublicHeader.vue` (old header)

**File:** `resources/js/components/public/PublicHeader.vue`

Add a dropdown `<li class="dropdown2">` after the Contact 2 `<li>` (around line 459), matching the pattern of the Destinations/Tours dropdowns:

```html
<li class="dropdown2">
    <a href="#">Refactored</a>
    <ul>
        <li><Link href="/about2" @click="closeMobileMenu">About 2</Link></li>
        <li><Link :href="contact2()" @click="closeMobileMenu">Contact 2</Link></li>
        <li><Link href="/blog2" @click="closeMobileMenu">Blog 2</Link></li>
        <li><Link href="/tours2" @click="closeMobileMenu">Tours 2</Link></li>
    </ul>
</li>
```

The `.dropdown2` class is a template CSS class that automatically handles dropdown behavior for both desktop and mobile.

---

## 2. Fix blog/IndexTailwind.vue visual gaps

| # | Issue | Fix |
|---|-------|-----|
| a | **Missing breadcrumb mask image** | Add `<img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />` inside the breadcrumb `text-center` div |
| b | **Sidebar widget background** wrong color | Change from `bg-white` to `bg-[#F4F6F8]` (matching `.sidebar-widget`) |
| c | **Sidebar widget padding** mismatch | Change from `p-8` to `px-[30px] py-[34px]` |
| d | **Block heading** missing green accent bar | Add `::before` pseudo-element via scoped CSS: a 2px-wide green bar on the left, or use Tailwind with proper classes and a scoped style |
| e | **Block heading font size** | Change from `text-[18px]` to `text-[20px]` |
| f | **Category list dashed borders** | Add `border-b border-dashed border-gray-200 pb-4 mb-2.5` to each category link item |
| g | **Read More `::after` arrow** | Change the arrow from a `<span>` element to a CSS `::after` pseudo-element (matching the original `.blog-card-link::after` styling) for exact visual match |
| h | **Sidebar container spacing** | Change `lg:pl-[30px]` to `lg:pl-[33px]` to match original `.side-bar-right` |
| i | **Blog card border color** | Change `border-gray-100` to `border-[#f0f0f0]` |
| j | **Blog card border radius** | Change `rounded-xl` to `rounded-[12px]` |

Apply same fixes (a, b, c, d, e, f, h) to `blog/ShowTailwind.vue`.

---

## 3. Fix tours/IndexTailwind.vue visual gaps

| # | Issue | Fix |
|---|-------|-----|
| a | **Section padding** | Change from `py-[80px]` to `pt-[120px] pb-[105px]` (matching `.archieve-tour`) |
| b | **Filter widget border/padding** | Change `rounded-2xl border border-gray-100 p-6` to `rounded-[10px] border border-[#EDEEEE] px-10 py-9` |
| c | **Filter title** needs green accent bar | Add `::before` pseudo-element via scoped CSS: 1px × 17px green bar, or inline style |
| d | **Filter title font + spacing** | Change `text-[18px] mb-5` to `text-[20px] mb-[30px]` |
| e | **Accordion trigger padding** | Change `px-4 py-3` to `pl-[53px] pr-10 py-3` to match the left icon offset |
| f | **mb-22 spacing** | Change `mb-4` to `mb-[22px]` on accordion wrappers |
| g | **Missing breadcrumb mask image** | Same as blog — add mask image to breadcrumb |
| h | **Filter sidebar right margin** | Change `lg:pr-[30px]` to `lg:mr-[30px] lg:pr-0` |

---

## 4. Fix tours/ShowTailwind.vue visual gaps

| # | Issue | Fix |
|---|-------|-----|
| a | **Sidebar widget bg + border** | Change from white to `bg-[#F4F6F8]` with `border-[#f0f0f0]` |
| b | **Block heading** missing green accent bar | Same `::before` fix as blog |
| c | **Hero breadcrumb** missing mask image | Add mask image |
| d | **Tour tabs** styling gap | Original `.tab-tour-single` has specific styles (justify-between, padding). Verify current matches. |

---

## 5. Verify & lint

```bash
npx eslint resources/js/components/public/PublicHeader.vue resources/js/pages/blog/IndexTailwind.vue resources/js/pages/blog/ShowTailwind.vue resources/js/pages/tours/IndexTailwind.vue resources/js/pages/tours/ShowTailwind.vue
```

---

## Files changed

| File | Action |
|------|--------|
| `resources/js/components/public/PublicHeader.vue` | Edit — add Refactored dropdown |
| `resources/js/pages/blog/IndexTailwind.vue` | Edit — ~10 visual fixes |
| `resources/js/pages/blog/ShowTailwind.vue` | Edit — ~8 visual fixes |
| `resources/js/pages/tours/IndexTailwind.vue` | Edit — ~8 visual fixes |
| `resources/js/pages/tours/ShowTailwind.vue` | Edit — ~4 visual fixes |
