# Blog Functionality Implementation Plan

## Overview
Add blog functionality with posts and categories. No comments, no tags — keeping it simple.

## What's Being Built

### Database (4 tables added to existing migration)
| Table | Columns |
|-------|---------|
| `blog_posts` | id, user_id (FK→users, author), title, slug, excerpt, content, featured_image, published_at, is_published (boolean), created_at, updated_at |
| `blog_categories` | id, name, slug, created_at, updated_at |
| `blog_category_post` (pivot) | id, blog_post_id, blog_category_id, position |

### Models
- `BlogPost` — belongsTo User (author), belongsToMany BlogCategory
- `BlogCategory` — belongsToMany BlogPost

### Controllers
- **Admin\BlogPostController** — CRUD (index, create, store, edit, update, destroy, togglePublished) following `Admin\TourController` pattern
- **BlogController** — Public: index (listing with pagination), show (single post by slug)

### Vue Pages
- `resources/js/Pages/admin/blog/Posts.vue` — Admin listing table (reuses tanstack table pattern from Tours.vue)
- `resources/js/Pages/admin/blog/Create.vue` — Create form (reuses form pattern from Create.vue)
- `resources/js/Pages/admin/blog/Edit.vue` — Edit form (reuses form pattern from Edit.vue)
- `resources/js/Pages/blog/Index.vue` — Public listing (from blog.html template)
- `resources/js/Pages/blog/Show.vue` — Public single post (from blog-details.html template)

### Routes
#### Public (in `web.php`)
- `GET /blog` → `BlogController@index` → `blog.index`
- `GET /blog/{post:slug}` → `BlogController@show` → `blog.show`

#### Admin (under `auth` + `admin` middleware)
- `GET admin/blog` → `Admin\BlogPostController@index` → `admin.blog`
- `GET admin/blog/create` → `Admin\BlogPostController@create` → `admin.blog.create`
- `POST admin/blog` → `Admin\BlogPostController@store` → `admin.blog.store`
- `GET admin/blog/{post}/edit` → `Admin\BlogPostController@edit` → `admin.blog.edit`
- `PATCH admin/blog/{post}` → `Admin\BlogPostController@update` → `admin.blog.update`
- `PATCH admin/blog/{post}/published` → `Admin\BlogPostController@togglePublished` → `admin.blog.published.toggle`
- `DELETE admin/blog/{post}` → `Admin\BlogPostController@destroy` → `admin.blog.destroy`

### Sidebar Navigation
Add "Blog" nav item (using `Newspaper` icon from lucide-vue-next) in `AppSidebar.vue` — placed between Tours and Destinations in the admin sidebar.

## Implementation Order

### Step 1: Database Migration
Add `blog_posts`, `blog_categories`, and `blog_category_post` pivot table to the same existing migration file (per project convention: single migration for dev).

### Step 2: Models
Create `BlogPost` (with User relationship, BlogCategory relationship, Fillable attribute, casts) and `BlogCategory` models. Create factories and seeders.

### Step 3: Admin Controller + Form Requests
Create `Admin\BlogPostController` with full CRUD + toggle published. Create `StoreBlogPostRequest` and `UpdateBlogPostRequest` if needed (or validate inline like some existing controllers).

### Step 4: Public Controller
Create `BlogController` with index (paginated, filtered by published) and show (by slug, only published).

### Step 5: Admin Vue Pages
- `Posts.vue` — Table with columns: title, categories, author, published status, created_at. Actions: edit, toggle publish, delete.
- `Create.vue` — Form: title, slug (auto-generated from title), excerpt, content (Editor component), featured_image (file upload), categories (checkboxes), is_published (switch).
- `Edit.vue` — Same as Create but pre-filled.

### Step 6: Public Vue Pages
Convert reference HTML templates to Vue/Inertia:
- `Index.vue` — Blog listing with hero/breadcrumb, post cards grid, sidebar (recent posts, categories), pagination
- `Show.vue` — Single post with breadcrumb, featured image, metadata, content, share section, sidebar

### Step 7: Routes & Navigation
- Add routes to `web.php`
- Add sidebar nav item in `AppSidebar.vue`

### Step 8: Run Wayfinder
Run `php artisan wayfinder:generate` to generate typed route functions after all routes are defined.

### Step 9: Tests
Write Pest tests for:
- Blog listing page loads and shows published posts
- Blog single post page loads by slug
- Unpublished posts are hidden from public
- Admin CRUD operations (create, edit, delete, toggle publish)

### Step 10: Pint Formatting
Run `vendor/bin/pint --dirty --format agent` after all PHP changes.
