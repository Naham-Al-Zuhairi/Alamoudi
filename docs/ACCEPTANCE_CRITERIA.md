# Acceptance checklist

## Public site

- [ ] Both Arabic (RTL) and English (LTR), switch locale while staying on equivalent page when possible.
- [ ] Home: 3 migrated editable hero slides; about/services; achievements and statistics; featured projects; header/footer.
- [ ] Projects: published category-filtered listing, pagination if necessary, project details/gallery, meaningful links.
- [ ] Team: existing Arabic/English organization chart images editable in admin; responsive display with appropriate alt text.
- [ ] Contact: accurate editable branch contact details and working accessible form (name, email, optional phone, subject, message).
- [ ] Responsive mobile/tablet/desktop, keyboard navigation, focus indicators, no horizontal overflow, meaningful empty states.
- [ ] Per-locale titles/descriptions, canonical/hreflang where appropriate, sitemap, robots directives, open graph metadata.

## Admin

- [ ] Secure login, logout, session handling, no public registration unless required; enforced role/policy checks.
- [ ] Dashboard summary, content sections, hero slides, projects/categories/galleries, team chart, contact details, media and basic SEO management.
- [ ] Persist content in MySQL; validation + permissions on every write; safe upload/delete/replacement policy.
- [ ] Contact inbox: paginated newest-first, unread/read filters and counts, message detail, mark read and unread; `read_at` nullable timestamp persisted.
- [ ] Optional delete/archive action guarded, confirmed and audited. Contact content cannot be edited as if authored by admin.
- [ ] CMS supports Arabic/English; draft/visible flags and ordered content where useful.

## Contact submission

- [ ] Valid request, throttling and honeypot or another appropriate anti-spam defense.
- [ ] CSRF protected, server-side validation, no over-posting, safe rendering and limits.
- [ ] Create message unread (`read_at=NULL`), display acknowledgement without exposing sensitive details.
- [ ] Concurrent admins can read/unread reliably; database tests verify inbox filtering and access restrictions.
- [ ] Avoid collecting excess PII; establish retention/deletion behavior and privacy notice.

## Delivery and quality

- [ ] Migrations/factories/seeders import real existing assets/text; rerunnable without duplicate records.
- [ ] `.env.example` has no real passwords; local `.env` may hold user-specified root/root only.
- [ ] `php artisan test`, `vendor/bin/pint --test`, TypeScript check, frontend tests and `npm run build` executed or blockers documented.
- [ ] Image sizes optimized and responsive; no legacy jquery dependencies shipped; review Lighthouse/Core Web Vitals on key pages.
- [ ] `README.md` includes installation, DB setup, seeding, administrator bootstrap, assets, tests, deployment checklist.
