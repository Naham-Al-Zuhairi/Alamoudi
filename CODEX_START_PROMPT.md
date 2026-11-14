# Initial implementation prompt for Codex

You are the lead Senior Laravel/Vue Engineer, architect, UI/UX designer and QA engineer for a complete rebuild of the Al-Amoudi company website.

**Source material:** The old static PHP site is supplied as `alamoudi.zip`. Locate it in my working directory (or ask me for its local path _only if actually absent_). It contains `alamoudi/index.php`, `alamoudi/en/index.php`, `alamoudi/projects.php`, `alamoudi/en/projects.php`, `alamoudi/team.php`, `alamoudi/en/team.php`, `alamoudi/contact.php`, `alamoudi/en/contact.php`, `alamoudi/language.php`, images, CSS and old JavaScript. Do not assume the zip is already extracted. Do not overwrite or delete original source; preserve it under `legacy/` or in a separate directory.

**Before coding:** Read `AGENTS.md`, `docs/LEGACY_AUDIT.md`, `docs/ACCEPTANCE_CRITERIA.md`, and the relevant `.agents/skills/*/SKILL.md`. Audit the actual legacy project and update the audit if needed. Check installed PHP/Composer/Node/MySQL versions and the target Laravel compatibility. Present a concise architecture plan, then **proceed to implementation in manageable end-to-end phases without waiting for approval unless blocked by a destructive action, missing credentials or material ambiguity**.

**Tech stack:** Supported stable Laravel, Vue 3, TypeScript, Inertia.js, Vite, Tailwind CSS, MySQL. Prefer the official Laravel Vue starter kit and Laravel-native auth. Use one monolithic Laravel web application, not two independent projects. Design a secure `/admin` content management control panel using Inertia/Vue and separate public layout. Implement domain-appropriate models, migrations, seeders, policies, Form Requests, small services/actions as needed, and tests.

**Database:** For my LOCAL DEVELOPMENT ONLY, configure untracked `.env` for MySQL host `127.0.0.1`, port `3306`, database `alamoudi`, username `root`, password `root`. If the database does not exist, create it only when local MySQL is accessible and permissions allow. Never put credentials in version control, code, logs or `.env.example`. Never use root for production. Create the admin account via a secure command or interactive setup; never hardcode a known default admin password.

**Preserve and improve site structure:** Keep the company's authentic logo, contact details and bilingual content. Rebuild the Arabic/English home (3 hero slides, about/services, achievements/statistics, featured projects, header/footer), projects/category gallery with proper detail/gallery interactions, team/organization chart, and contact page. The original About Us is a home section; retain it; do not accidentally drop it. Use real images/copy from original source; no fabricated team profiles, project facts or counters. Make everything appropriate editable through `/admin`: home hero, section content, services, achievements/counters, projects and images/categories, team chart images, addresses/contact details, navigation/footer settings and SEO where appropriate. Support draft/published or visibility states and ordering where useful. The site must have working Arabic RTL and English LTR, including locale switch and bilingual admin-managed text.

**Contact upgrade:** Keep existing published contact information, add a professional responsive contact form collecting name, email, optional phone, subject and message. Use Laravel Form Request validation, CSRF protection, rate limiting and spam mitigation. Store messages in `contact_messages` with nullable `read_at` (new = unread). In the protected admin panel implement an inbox with unread/read counters and filters, details, mark read and mark unread, pagination, and appropriate access control. Do not expose messages to the public, send email unnecessarily, or store contact fields in logs. Add tests for all of this.

**UI/UX and performance:** Deliver a modern, premium but credible construction-company identity, responsive across device widths, semantic accessible HTML, mobile menu, usable forms, tasteful motion and proper empty/loading/error states. Replace legacy jQuery/old sliders with lightweight Vue-native interactions. Optimize original hero JPEGs (currently approximately 17.6MB, 22.2MB and 8.2MB) into responsive modern formats with lazy loading for below-fold images, prioritized first hero image, appropriate image dimensions, caching and small bundle sizes. Add per-language SEO metadata. Benchmark real built pages where tools permit, report actual numbers rather than promises.

**Sequence:**

1. Audit old source and produce file/section mapping and a concise delivery plan.
2. Scaffold Laravel + Vue/Inertia app; configure local MySQL, auth, admin protection, shared bilingual layouts.
3. Create content schema, seed migrated bilingual content and media without duplicates.
4. Build public pages and optimized modern UI.
5. Build complete admin CMS with working saved edits reflected on public pages.
6. Implement contact form + secure read/unread admin inbox.
7. Add tests, finalize accessibility/SEO/performance, build and document setup and deployment.

**Definition of done:** The new Laravel website can be installed locally, migrated and seeded, operated in Arabic and English, visually contains all important legacy content, is editable from admin, saves submissions in MySQL, filters/updates read/unread messages securely, passes available tests/builds and has setup/documentation. Do not report work complete until verified. At the end of each phase list changed files, commands run, results, and remaining work.

Start inspecting the original source and environment now and carry out the migration in the specified phases.
