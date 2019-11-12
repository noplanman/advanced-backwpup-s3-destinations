=== Advanced S3 Destinations for BackWPup ===

Contributors: noplanman
Donate link: https://noplanman.ch/donate
Tags: backwpup, s3, storage, destination, backup
Requires at least: 4.6
Tested up to: 5.3
Requires PHP: 5.3.3
Stable tag: 1.1.0
Author URI: https://noplanman.ch
Plugin URI: https://git.feneas.org/noplanman/advanced-backwpup-s3-destinations
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily add custom S3 destinations for <a href="https://backwpup.com">BackWPup</a>.

== Description ==

This plugin requires **[BackWPup](https://wordpress.org/plugins/backwpup/)** or **[BackWPup Pro](https://backwpup.com/)**!

After installing this plugin, you will find a new tab called "S3 Destinations" on the "BackWPup -> Settings" page.
There you can add additional S3 destinations which can then be used in your jobs.

*(Logo is based on the original BackWPup logo)*

= Requirements =

* WordPress 4.6 and PHP 5.3.3 required!

= Development =

This plugin is completely open source and a work of passion.
If you would like to be part of it and join in, make your way over to the [project page](https://git.feneas.org/noplanman/advanced-backwpup-s3-destinations) now.
Also, if you have an idea you would like to see in this plugin or if you've found a bug, please [let me know](https://git.feneas.org/noplanman/advanced-backwpup-s3-destinations/issues/new).

= Donate / Support =

All [donations](https://noplanman.ch/donate) are much appreciated, thank you 🙏

[Get professional support for this plugin with a Tidelift subscription](https://tidelift.com/subscription/pkg/wordpress-advanced-backwpup-s3-destinations?utm_source=wordpress-advanced-backwpup-s3-destinations&utm_medium=referral&utm_campaign=readme)
*Tidelift helps make open source sustainable for maintainers while giving companies assurances about security, maintenance, and licensing for their dependencies.*

= Security =

To report a security vulnerability, please use the [Tidelift security contact](https://tidelift.com/security). Tidelift will coordinate the fix and disclosure.

== Frequently Asked Questions ==

= How do I remove any defined S3 destinations? =

Simply leave the "Unique ID" input field empty to remove that entry.

== Screenshots ==

1. Custom S3 destinations on the BackWPup Settings page.

== Installation ==

You can either use the built-in WordPress installer or install the plugin manually.

For an automated installation:

1. Go to 'Plugins -> Add New' on your WordPress Admin page.
2. Search for the 'Advanced S3 Destinations for BackWPup' plugin.
3. Install by clicking the 'Install Now' button.
4. Activate the plugin on the 'Plugins' page in your WordPress Admin.

For a manual installation:

1. Upload the 'advanced-backwpup-s3-destinations' folder to the plugins directory of your WordPress installation.
2. Activate the plugin on the 'Plugins' page in your WordPress Admin.

== Changelog ==

= Version 1.1.0 =
Release Date: 2019-11-12

* Fixed admin notices for network installations
* Minimum WP is now 4.6
* Tested up to WP 5.3

= Version 1.0.0 =
Release Date: 2019-08-04

* Add new "S3 Destinations" tab to "BackWPup -> Settings" page
* Initial version
