# Contributing to WP Performance Regression Detector

We welcome contributions to make WordPress performance monitoring better!

## Getting Started
1.  Fork the repository.
2.  Clone locally: `git clone ...`
3.  Install dependencies: `npm install`
4.  Build CSS: `npx tailwindcss -i ./assets/tailwind.input.css -o ./assets/css/admin.css --watch`

## Coding Standards
- **PHP**: Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
- **CSS**: Use Tailwind utility classes. Avoid custom CSS unless absolutely necessary.
- **Architecture**: Keep logic in `includes/` and UI in `admin/`. Adhere to the single responsibility principle.

## Pull Request Process
1.  Ensure your code is clean and commented.
2.  Verify that no global styles are leaked (everything must be inside `#wprd-app`).
3.  Submit a PR with a description of the change and the problem it solves.

## License
GPLv2 or later.
