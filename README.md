# ![php](https://img.shields.io/badge/Php-8993BE?style=for-the-badge&logo=php&logoColor=white) NovaMart Commerce
NovaMart is a lightweight PHP e-commerce starter that routes every request through [`src/index.php`](src/index.php). The router registers friendly callbacks for each URI and gracefully falls back to a branded 404 page, giving you the flexibility of a modern single-entry application without sacrificing the familiarity of PHP templates.

## Why this fork feels original
- A refreshed visual language with gradients, softened neutrals, and tidy spacing to shake off the “generic bootstrap shop” appearance.
- Renamed copy blocks, footer text, and onboarding flows so the UI reads like a bespoke storefront.
- README and setup docs rewritten with the exact steps we follow today, so deploys and hand-offs stay predictable.

## Feature highlights
- Account system with registration, login, password reset (token + email) and editable profile fields.
- Secure ordering experience: CSRF tokens, sanitised inputs, and server-side validation across checkout and admin forms.
- Email actions powered by [SendGrid](https://sendgrid.com) plus optional live chat through [Intercom](https://intercom.com).
- Order history, PDF-style invoice view, and a cart that supports unlimited product images per item.
- Admin workspace for products, FAQs, customers, and exports/imports with 7-day revenue charts via Chart.js and on-the-fly image compression using [php_gd](https://php.net/manual/en/book.image.php).

## Getting started
1. Create a MySQL/MariaDB database.
2. Run [`src/db-settings.sql`](src/db-settings.sql) to seed schema and demo data.
3. Update database credentials inside [`src/views/db.php`](src/views/db.php).
4. Provide your SendGrid API key in [`src/views/admin/util.php`](src/views/admin/util.php#L5) and (optionally) adjust the sending domain references in [`src/views/admin/util.php`](src/views/admin/util.php#L20) and [`src/views/cart.php`](src/views/cart.php#L20).
5. Plug your Intercom App ID into [`src/views/footer.php`](src/views/footer.php#L82).
6. Enable the `php_gd` extension in `php.ini` so uploads benefit from automated compression.

> **Heads-up:** Environment secrets are stored directly in PHP for simplicity. Swap to dotenv / environment variables before deploying to shared infrastructure.

## Admin credentials
```
URI: /admin/login
username: admin
password: 123456
```

## Design tokens
| Token | Value | Purpose |
| ----- | ----- | ------- |
| `--nm-primary` | `#4c6ef5` | Buttons, highlights |
| `--nm-secondary` | `#111b2b` | Navigation, headings |
| `--nm-accent` | `#ffb347` | Badges, CTAs |
| `--nm-surface` | `#f4f6fb` | Section backgrounds |

Drop these variables into your own CSS modules or keep extending `views/css/style.css`.

## Academic context
- Created for the **College Mini Assignment & Project** submission for **Advanced Web Design (CCBCA512)**.
- Reviewed, checked, and signed by the assistent professor Dr. Ashish Rai prior to hand-in.

- Submitted by the following **BCA 5 E2** team:
  - **Mohit Lakhara** – Project Manager & Planning Lead
  - **Lagan Parihar** – UI/UX & Front-end Implementation
  - **Muddit Chamber** – Back-end & Database Engineering
  - **Kunal Mittal** – QA, Documentation & Deployment Support

## Software development phases
1. **Planning** – scoped the mini-assignment, defined user stories, and mapped the submission timeline to the 24 Nov 2025 deadline.
2. **Design** – produced wireframes, color tokens, and content rewrites that distinguish NovaMart from the original template.
3. **Implementation** – refactored PHP views, routing, and styling layers with the new brand, security configurations, and copy updates.
4. **Testing** – validated authentication, cart, admin flows, and visual polish across browsers/devices before instructor review.
5. **Deployment & Sign-off** – packaged the project, collected teacher approval, and scheduled the final hand-in prior to 2:00 PM.
