# StarCliqs

Company website for StarCliqs, with an admin panel for every section of the homepage.
Built on Laravel 12, with the admin layout and components taken from the `jhs_portal` backend.

## What the admin manages

| Admin screen | Shows on the website |
| --- | --- |
| Services | Homepage services grid + a page per service (`/services/{slug}`) |
| Products | Homepage product showcase (features, price, demo link) + `/products`, `/products/{slug}` |
| Projects | Homepage portfolio with category filter + `/projects`, `/projects/{slug}`. Upload a cover image per project |
| Academy programmes | The Academy page `/academy` (programme cards with level, deadline, start date, schedule and tuition) + `/trainings/{slug}` with an online registration form. The Academy page text is under Site settings → Academy |
| Clients | Homepage "Our Happy Clients" logos, linking to each client's website or to the product we built for them |
| Testimonials | Homepage client stories (photo, rating) |
| Blog | Latest three on the homepage + `/blog`, `/blog/{slug}`. Drafts and scheduled posts |
| FAQs | Homepage "Looking for help?" cards, with a live search box |
| Messages | Contact-form messages and training registrations from the site |
| Subscribers | Footer newsletter sign-ups, exportable as CSV |
| Visitors | Log of public page views (top pages, referrers, devices, browsers) and requests blocked by the rate limit |
| Site settings | Company details, hero text, about text, stats, social links, Academy text, Data Protection badge, request limits and visitor-log retention |

## Security and privacy

- **Request limits** (Site settings → Security & visitors): page requests per visitor per minute (default 120), form submissions per visitor per hour (default 10, and at most 3 a minute), and 20 admin sign-in page loads per minute. Wrong admin passwords lock that email/IP for 5 minutes after 5 attempts. Visitors over a limit see a "Please slow down" page.
- **Visitor log:** full IP addresses are never stored (the last part is removed), visitors are recognised only within the same day, bots and signed-in admins are skipped, and entries older than the retention period (default 90 days) are deleted automatically.

Uploaded images are saved in `public/uploads` (no `storage:link` needed).

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database named `starcliqs_db` (or change `DB_DATABASE` in `.env`), set `ADMIN_EMAIL` and
`ADMIN_PASSWORD` in `.env`, then:

```bash
php artisan migrate --seed
```

The seeder creates the first administrator and the starter content from the original homepage. Running it
again never overwrites anything you have edited. The trainings and blog posts it adds are samples to replace.

## Running

- XAMPP: open http://localhost/starcliqs (the root `.htaccess` forwards to `public/`).
- Or `php artisan serve` and open http://127.0.0.1:8000.

Admin panel: `/admin` (sign in at `/admin/login`). Change the seeded password under **Account & admins**.
