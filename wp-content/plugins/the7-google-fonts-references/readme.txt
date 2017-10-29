=== Remove Google Fonts References ===
Contributors: xiaoxu125634
Donate link: http://www.brunoxu.com/
Tags: Open Sans, Google Fonts, Google Web Fonts, Remove Google Fonts, Disable Google Fonts
Requires at least: 3.0
Tested up to: 4.5.2
Stable tag: trunk

Remove Open Sans and other google fonts references from all pages.

== Description ==

[Plugin homepage](http://www.brunoxu.com/remove-google-fonts-references.html) | [Plugin author](http://www.brunoxu.com/)

This plugin stops loading of Open Sans and other fonts used by WordPress and other themes (such as Twenty Twelve, Twenty Thirteen, Twenty Fourteen etc.) from Google Fonts.

Reasons for not using Google Fonts might be privacy and security, local development or production, blocking of Google's servers, characters not supported by font, performance.

Remove Google Fonts References is a very lightweight, it has no settings, just activate it and it works immediately.

Suggestion: 
From version 2.0, CSS files detecting has been added to this plugin to avoid google fonts loading inside CSS files. Although it helps stop loading google fonts more thoroughly, it will causes more resource consumption at first runing as well, may cause some minor problems(Like space or the host is not unobstructed access to extranet resources will lead to the first response time too long). If you don't need CSS files detecting feature, [Remove Google Fonts References Version 1.2](http://www.brunoxu.com/wp-content/uploads/2014/09/remove-google-fonts-references_v1.2.zip) only contains the simple removal of the full-text search function, maybe a better choice for you.

== Installation ==

1. Upload `remove-google-fonts-references` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress background.

== Changelog ==

= 2.6 =
* 2016-06-17
* Fix compatibility with Avada theme.

= 2.5 =
* 2015-07-24
* Add two filters: "remove_google_fonts_content_filter_before" and "remove_google_fonts_content_filter_after", used for handling page content.

= 2.5 =
* 2014-10-03
* Fix a bug in some situation: 'resource huge consumption'.
* Change file obtaining function from 'file_get_contents' to 'wp_remote_fopen'.

= 2.4 =
* 2014-09-25
* Add 'remove_google_fonts_priority' filter, used to change sequencing with 'Useso take over Google' plugin.
* Stop loading google fonts imported by 'Web Font Loader'.

= 2.3 =
* 2014-09-20
* Add theme 'pinnacle'(use redux framework) compatibility.

= 2.2 =
* 2014-09-19
* Remove some redundant codes.
* Keep silence when error occurred during file_get_contents() runing.
* Add "Useso take over Google" plugin compatibility.

= 2.1 =
* 2014-09-17
* fix bug: can't load resources defined in the cached css files with relative path.

= 2.0 =
* 2014-09-17
* Remove google fonts references inside css files.
* Add plugin activation handler and uninstall handler.

= 1.2 =
* 2014-09-10
* Cover another reference method like '@import url(...)'.
* Optimized action hooks.

= 1.1 =
* 2014-08-26
* Cover login and register page.

= 1.0 =
* 2014-08-19
* First released version.
