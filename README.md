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

## Prerequisites
Before setting up the plugin, ensure you have the following installed on your system:
- **WordPress** (version 5.0 or higher)
- **PHP** (version 7.4 or higher)
- **MySQL** (version 5.7 or higher)
- **Node.js & npm** (for building Tailwind CSS assets)
- **Git** (for cloning the repository)

## Installation & Setup Guide

### Step 1: Clone the Repository

Navigate to your WordPress plugins directory and clone this repository:

```bash
cd wp-content/plugins
git clone https://github.com/CodeArunJ/WP-Performance-Regression-Detector.git
cd WP-Performance-Regression-Detector
```

### Step 2: Install Node.js Dependencies

Install the required npm packages for Tailwind CSS compilation:

```bash
npm install
```

This will install all dependencies defined in `package.json`, including Tailwind CSS.

### Step 3: Build Tailwind CSS Styles

Compile the Tailwind CSS input file to generate the admin stylesheet:

```bash
npx tailwindcss -i ./assets/tailwind.input.css -o ./assets/css/admin.css
```

**Note:** If you want to rebuild styles automatically during development, use the watch mode:

```bash
npx tailwindcss -i ./assets/tailwind.input.css -o ./assets/css/admin.css --watch
```

### Step 4: Activate the Plugin

You can activate the plugin using one of the following methods:

**Option A: WordPress Admin Dashboard**
1. Log in to your WordPress admin panel
2. Navigate to **Plugins > Installed Plugins**
3. Find "WP Performance Regression Detector"
4. Click **Activate**

**Option B: WP-CLI**
```bash
wp plugin activate wp-performance-regression-detector
```

### Step 5: Verify Database Tables

The plugin automatically creates the required MySQL tables on activation:
- `wp_wprd_baseline_metrics` - Stores performance baselines
- `wp_wprd_recent_events` - Stores recent tracked events
- `wp_wprd_regression_events` - Stores regression data

You can verify the tables were created by checking your WordPress database using phpMyAdmin or any MySQL client.

### Step 6: Access the Plugin Dashboard

1. Log in to your WordPress admin panel
2. Navigate to **Tools > Performance Regression Detector**
3. The dashboard will display performance metrics and regression alerts

### Step 7: Generate Baseline Metrics

The plugin needs baseline metrics to compare against:

1. Browse your website (frontend pages) normally
2. The plugin will automatically capture metrics on each page load
3. After 20-30 page loads, the baseline will be established
4. You'll start seeing performance comparison data in the dashboard

## Local Setup Steps (Quick Reference)

```bash
# 1. Clone the repository
git clone https://github.com/CodeArunJ/WP-Performance-Regression-Detector.git wp-content/plugins/wp-performance-regression-detector
cd wp-content/plugins/wp-performance-regression-detector

# 2. Install dependencies
npm install

# 3. Build Tailwind CSS
npx tailwindcss -i ./assets/tailwind.input.css -o ./assets/css/admin.css

# 4. Activate the plugin (via WordPress Admin or WP-CLI)
wp plugin activate wp-performance-regression-detector

# 5. Visit the dashboard
# Navigate to: Tools > Performance Regression Detector

# 6. Start browsing your site to generate baseline metrics
```

## Configuration

### Database Storage
This plugin uses **MySQL custom tables** for data storage:
- Baseline metrics stored in `wp_wprd_baseline_metrics`
- Recent events (max 10) stored in `wp_wprd_recent_events`
- Regression events (max 50) stored in `wp_wprd_regression_events`

### Performance Thresholds
Default regression thresholds (configurable in future versions):
- Load Time: **30% increase**
- Database Queries: **25% increase**
- Memory Usage: **20% increase**

## Tailwind Usage Justification
This plugin uses **Tailwind CSS** to provide a modern, enterprise-grade, and responsive dashboard that stands out from standard WP-Admin interfaces.
- **Scoped**: All styles are strictly scoped to `#wprd-app` using Tailwind's `important` configuration to prevent any conflict with WordPress core styles or other plugins.
- **Lightweight**: Only the used classes are bundled via JIT compilation.
- **Developer Experience**: Allows for rapid UI iterations and a consistent design system.

## Performance Overhead Philosophy
> "This plugin favors lightweight observability over exhaustive logging to avoid introducing performance noise."

We intentionally avoid complex tracing or heavy database writes on every request. Metrics are captured in memory and stored using MySQL tables with a limited history size.

## Troubleshooting

### Database Tables Not Created
**Issue**: Tables not appearing in database after activation.

**Solution:**
1. Ensure your WordPress installation can write to the database
2. Check `wp-config.php` has correct database credentials
3. Try reactivating the plugin
4. Check WordPress debug log for errors

### Tailwind Styles Not Loading
**Issue**: Dashboard looks unstyled or broken.

**Solution:**
```bash
# Rebuild Tailwind CSS
npx tailwindcss -i ./assets/tailwind.input.css -o ./assets/css/admin.css
```

### Plugin Not Showing in Admin
**Issue**: Plugin doesn't appear in Tools menu.

**Solution:**
1. Verify the plugin is activated: `wp plugin list`
2. Check WordPress error logs
3. Ensure PHP version is 7.4+

## Limitations
- **Local Dev Focused**: Designed primarily for local development environments.
- **Single Threaded**: Does not handle high-concurrency race conditions for baseline updates (acceptable for local dev).
- **Limited History**: Recent events (max 10) and regression events (max 50) are retained.

## Future Scope
- Configurable thresholds.
- Exclusion of specific paths (e.g., admin-ajax.php).
- Slack/Email notifications on regression.
- Long-term trend analysis and reports.

## Uninstalling the Plugin

To remove the plugin completely:

**Option A: WordPress Admin**
1. Navigate to **Plugins > Installed Plugins**
2. Click **Deactivate** on the plugin
3. Click **Delete**

**Option B: WP-CLI**
```bash
wp plugin deactivate wp-performance-regression-detector
wp plugin delete wp-performance-regression-detector
```

**Note:** Deactivating the plugin preserves data. Deleting/uninstalling will remove all custom database tables and data.

## Support & Contribution
For issues, feature requests, or contributions, please visit: [GitHub Repository](https://github.com/CodeArunJ/WP-Performance-Regression-Detector)

