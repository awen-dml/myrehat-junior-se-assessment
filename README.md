# MyRehat Junior SE Assessment

This repository contains the implementation updates and supporting deliverables for the MyRehat Junior Software Engineer assessment.

## Project Files

- `functions.php` — WordPress theme functions, booking request handling, and trail-view counter logic.
- `page-trails.php` — Trail listing page and booking form template.
- `trails.js` — Listing search and filtering, booking form interactions, and dynamic pricing.
- `style.css` — Theme styles and responsive layout.
- `elementor/` — Exported Elementor template JSON.
- `prompt-log.md` — Summary of AI assistance used during the assessment.
- `verification-notes.md` — Syntax-check results and outstanding functional verification.

## Booking Pricing

- Daily: RM 350
- Monthly: RM 8,400

The booking form includes client-side validation, dynamic pricing, and a demo confirmation message. A demo confirmation should not be interpreted as proof that a booking has been persisted.

## Verification

The following local PHP syntax checks passed:

```bash
php -l functions.php
php -l page-trails.php
```

Functional behavior should be verified against the assessment environment before considering the implementation complete.

## Deliverables

- Source code: this repository
- Elementor template: `elementor/elementor-18-2026-10-23.json`
- AI assistance summary: `prompt-log.md`
- Verification notes: `verification-notes.md`