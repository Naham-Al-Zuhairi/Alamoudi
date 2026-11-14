# Legacy source audit — `alamoudi.zip` (initial inspection)

Source directory: `alamoudi/` with PHP pages, `language.php`, `include/`, `en/`, `css/`, `js/`, `img/`, and fonts. 172 archive entries, about 64.7 MB uncompressed. Verify all findings against extracted source when Codex starts.

Verified against the extracted source in `legacy/alamoudi/` on 2026-11-13. The original archive remains at the repository root. `scripts/extract_legacy.py` reads the PHP file as text and writes `database/seeders/legacy-content.json`; no legacy code is executed. `scripts/prepare_media.py` makes smaller WebP copies in `public/media/`.

The projects page has 21 image tiles. Several tiles have only a category label and no unique verified project title; the migration keeps their image-derived keys and category labels. The English projects page also uses several Arabic category labels. The old site has no contact form or message inbox. Contact addresses and phone numbers were checked against `contact.php` and `language.php`. The legacy footer contains a dead `index-2.html` logo link and template marketing copy; these links are not used in the new navigation.

The original home page displays static counters of 17 years of experience and 27 completed projects. They are migrated into editable statistics with a visible note that these figures came from the previous site, since their present-day accuracy has not been verified.

## Pages

- Arabic home: `index.php`, English: `en/index.php`; shared headers/footers per locale.
- About: homepage section `#about` (not an existing standalone page).
- Team: `team.php`, `en/team.php`, currently embeds `img/team_ar.png` and `img/team_en.png` organizational chart images. Preserve these initially; optional structured team entries later.
- Projects: `projects.php`, `en/projects.php`; hardcoded gallery tiles for water, solar and housing projects. Several `href="#"` placeholder links; improve to meaningful project detail or lightbox behavior.
- Contact: `contact.php`, `en/contact.php`; contact details/branches but **no form**. Preserve verified contact details in editable admin settings; do not expose contact messages publicly.
- Home sections: hero with 3 slides, about/company services, achievements/testimonials-like presentation, number counters, featured project carousel, footer.
- Existing translations are PHP arrays in `language.php` for nav, hero, about, achievements, counters, projects, footer and page copy. Extract actual bilingual text instead of rewording company facts.
- Arabic uses an explicit RTL CSS set; English uses separate header and styles. New site must set `lang` and `dir` on HTML appropriately.

## Performance / quality concerns

- Hero JPEGs `img/slider-1.jpg` ~17.6 MB, `slider-2.jpg` ~22.2 MB and `slider-3.jpg` ~8.2 MB. Optimize and resize before shipping.
- Legacy jQuery 1.12.4, carousel plugins, multiple global styles and old vendor scripts. Do not port blindly.
- Static SEO description in headers includes a template placeholder; implement editable per-page metadata.
- Links and imported text should be audited; ensure external links are valid, relevant and safe.
- Audit asset licensing before publishing or replacing third-party template components. Do not copy old JS dependencies by default.

## Migration content map

| Existing data                  | New model / feature                                                                          |
| ------------------------------ | -------------------------------------------------------------------------------------------- |
| `language.php` translated copy | `site_settings`, `page_sections` / curated translated fields                                 |
| Hero titles/images             | `hero_slides` with locale fields, ordered, published                                         |
| About/features                 | `page_sections` or `services` depending on repeatable data                                   |
| Achievement text/counters      | `achievements` / `statistics`                                                                |
| Featured + gallery projects    | `project_categories`, `projects`, `project_images`                                           |
| Team images                    | editable `team_page` settings with locale-specific chart media; optional team-member records |
| Contact details                | `contact_locations` (branch/address/email/phone/fax), site settings                          |
| New contact form               | `contact_messages` inbox with `read_at`, no public read API                                  |
| Header/footer links            | admin-editable navigation/settings where appropriate                                         |

## Preserve rather than invent

Ensure Al-Amoudi brand, bilingual wording, actual services, projects, cities, logos and team chart remain accurate. Do not fabricate team biographies, customer testimonials, project dates, or counts. Confirm uncertainties explicitly.
