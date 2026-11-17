# Custom Favicon

Add a custom favicon to your WordPress site from the admin, with support for Apple touch icon and Windows tile image tags.

WordPress.org: https://wordpress.org/plugins/custom-favicon/

## Releasing

The plugin is published to WordPress.org from this repo.

- Push a tag like `1.1.1` (no `v`). The tag, the `Version` in `custom-favicon.php` and `Stable tag` in `readme.txt` must match.
- Banners and icons live in `.wordpress-org/`. Pushing changes there to `master` updates the directory assets.
- Editing only `readme.txt` on `master` updates it in SVN trunk and in the current Stable tag folder, without a new release. This is skipped until the Stable tag has been released.
