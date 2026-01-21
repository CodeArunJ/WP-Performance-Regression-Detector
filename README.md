# WP Performance Regression Detector

**Detects real-time performance regressions in WordPress and correlates them with recent system actions.**

## Real-World Problem Statement
In fast-paced WordPress development environments, performance regressions often slip through unnoticed until they affect production. A new plugin update, a theme switch, or a heavy query introduced in a hotfix can degrade page load times. This plugin provides immediate, local feedback on performance shifts, allowing developers to catch regressions *before* deploying code.

## Detection Logic
The plugin correlates real-time metrics against a rolling baseline of the last 20 requests. A regression is flagged if:
- **Load Time** exceeds baseline by **30%**
- **DB Queries** exceed baseline by **25%**
- **Memory Usage** exceeds baseline by **20%**

This threshold-based logic filters out minor fluctuations while highlighting significant performance degradations.

## Local Setup Steps
1.  Clone this repository into your `wp-content/plugins/` directory.
2.  Run `npm install` and `npx tailwindcss -i ./assets/tailwind.input.css -o ./assets/css/admin.css` to build the styles (if not already bundled).
3.  Activate the plugin via WP Admin or WP-CLI: `wp plugin activate wp-performance-regression-detector`.
4.  Navigate to **Tools > Performance Regression Detector**.
5.  Browse your site to generate baseline metrics.

## Tailwind Usage Justification
This plugin uses **Tailwind CSS** to provide a modern, enterprise-grade, and responsive dashboard that stands out from standard WP-Admin interfaces.
- **Scoped**: All styles are strictly scoped to `#wprd-app` using Tailwind's `important` configuration to prevent any conflict with WordPress core styles or other plugins.
- **Lightweight**: Only the used classes are bundled via JIT compilation.
- **Developer Experience**: Allows for rapid UI iterations and a consistent design system.

## Performance Overhead Philosophy
> "This plugin favors lightweight observability over exhaustive logging to avoid introducing performance noise."

We intentionally avoid complex tracing or heavy database writes on every request. Metrics are captured in memory and stored using the Options API with a limited history size.

## Limitations
- **Local Dev Focused**: Designed primarily for local development environments.
- **Single Threaded**: Does not handle high-concurrency race conditions for baseline updates (acceptable for local dev).
- **No Persistence**: History is limited to recent events; long-term trends are not stored.

## Future Scope
- Configurable thresholds.
- Exclusion of specific paths (e.g., admin-ajax.php).
- Slack/Email notifications on regression.
