# GMW Easy Manage

Structured content management for WordPress sites — bars, restaurants, and
hospitality venues. Hours, specials, menus, events, gallery, contact info, and
social links, all managed via shortcodes with no custom post types or page
builders.

## Features

- **Shortcode-driven** — `[gmw_hours]`, `[gmw_menu]`, `[gmw_events]`, and more.
  Full reference on the Docs admin page.
- **Portal registration** — Optional handshake with the GMW portal on activation
  for centralized site management. Uses a per-install activation secret with
  Ed25519-signed update verification.
- **Varnish auto-purge** — Purges cached pages when content is saved.
- **WP-CLI support** — Manage data from the command line via `wp gmw-easy-manage`.
- **No custom post types** — All data stored in `wp_options` as serialized arrays.
  Clean, portable, no schema migrations.
- **Frontend CSS** — Included stylesheets (`gmw-frontend.css`, `gmw-themes.css`)
  for consistent output.
- **Signed updates** — Plugin update integrity verified via Ed25519 signatures
  against a public key embedded in the plugin; no shared secrets.

## Shortcodes

| Shortcode | Displays |
|-----------|----------|
| `[gmw_hours]` | Business hours schedule |
| `[gmw_specials]` | Daily specials |
| `[gmw_menu]` | Menu items (categories + items) |
| `[gmw_events]` | Upcoming events |
| `[gmw_gallery]` | Image gallery |
| `[gmw_contact]` | Contact info and social links |

See **Easy Manage > Docs** in wp-admin for the complete reference with examples.

## Requirements

- WordPress 6.0+
- PHP 8.0+
- PHP sodium extension (for Ed25519 update signature verification)

## Installation

Copy the plugin directory into `wp-content/plugins/`:

```bash
cp -r gmw-easy-manage /path/to/wordpress/wp-content/plugins/
```

Activate via wp-admin (Plugins → Installed Plugins → GMW Easy Manage → Activate)
or wp-cli:

```bash
wp plugin activate gmw-easy-manage
```

Data is managed from the **Easy Manage** menu in wp-admin.

## Portal Registration (Optional)

On activation, the plugin attempts a handshake with the GMW portal at
`apps.gmwsys.com`. If your site is registered, the portal responds with an
`AppToken` that authorizes future registrations. Registration is idempotent —
the plugin retries on failure and stops once confirmed.

This feature requires outbound HTTPS from your server to `apps.gmwsys.com`.
If the portal is unreachable, the plugin continues to work; registration will
retry on subsequent admin page loads.

## EasyForms Module

Embed **GMW EasyForms** contact forms inline on any page — no WP form plugin,
no per-form licensing. The form renders from a static fragment (fetched from the
EasyForms host once, cached locally to `wp-content/uploads/gmw-forms/`), so page
renders never call the host — Varnish-safe. All validation, anti-spam (Altcha
proof-of-work), rate limiting, and storage happen host-side.

```php
// wp-config.php — point at the EasyForms host
define('GMW_EF_HOST', 'https://easyforms.gmwsys.com');
```

Shortcode:

```
[gmw_easyform org="whisperslounge" form="contact-form"]
[gmw_easyform org="whisperslounge" form="contact-form" refresh="1"]   # re-fetch the fragment
```

- The host returns a `{ok: true, id: N}` JSON response; the page swaps in a
  thank-you inline (no navigation).
- **PII is encrypted at rest on the host** — never in the WP database (see the
  EasyForms PII baseline: hard-PII fields must be marked `pii: true` in the form
  JSON, enforced by the host's form linter).
- Form definitions, themes, and notification emails are configured on the host
  per org — no settings surface in the plugin.
- Uses the same update/security model as the rest of the plugin (Ed25519-signed).

### EasyForms module version history

- **1.9.6** — module added (`includes/easyforms.php`, `assets/gmw-easyform.{js,css}`)
- **1.9.7–1.9.8** — verify-step flow + button mode (superseded), per-form themes
- **1.9.9–1.9.10** — embed-only refactor; static fragment cached to
  `uploads/gmw-forms/` (zero host calls at render)
- **1.9.11–1.9.13** — CSS hardening: theme-interference fixes, hard-scoped rules,
  `.gmw-easyform` centered full-width; inline thank-you + preflight fix

## Update Security

Plugin update integrity is verified using Ed25519 digital signatures (via PHP's
sodium extension). The public key is embedded in the plugin; updates are signed
with the corresponding private key, which is never committed to this repository.
No shared secrets are used for update verification.

## Development

Clone the repo:

```bash
git clone git@github.com:gmwnet/gmw-easy-manage.git
```

The plugin uses multiple files in a standard WordPress plugin structure. No
build step or package manager is required.

```bash
# After making changes, copy to your test site:
cp -r gmw-easy-manage /path/to/wp-content/plugins/
```

Signing updates requires the Ed25519 private key (`ed25519-secret.key`, not in
repo). See `sign-update.sh`.

## License

MIT
