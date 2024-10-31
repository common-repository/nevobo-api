=== Nevobo API ===
Contributors: danie192
Tags: nevobo, feed, rss, competitie, nederlandse, volleybal, bond, volleyball, sport, api
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FFYL27WAJ4DY4&source=url
Requires at least: 4.6
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: 1.2.2
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Show the results, fixtures and standings of a RSS Feeds from the Dutch Volleyball Federation (Nevobo) on your Wordpress website.

== Description ==
Show the results, fixtures and standings of a RSS Feeds from the Dutch Volleyball Federation (Nevobo) on your Wordpress website.

"Based on the orginal Nevobo-feed plugin made by Harold Masselink – https://masselink.net/projects/nevobo-feed/"

[nevobo feed='URL-of-the-feed']

This plugin detects which kind of RSS feed you want to read. Use the shortcode somewhere on the Wordpress website.
The default values are found on the admin page. Read the documentation for more information about how to use the shortcode.

Example: [nevobo feed='URL-of-the-feed' aantal=3 sporthal=1 nevobo_maps=1]

== Installation ==
1. Upload the plugin in the "/wp-content/plugins/" folder.
2. Activate the plugin in the "Plugins" menu.
3. Add the following shortcode [nevobo feed='URL-of-the-feed'] in a textfield.
4. For more information take a look at the "Information" tab on the admin page.

== Frequently Asked Questions ==
= Questions? Sugestions? =
Please contact me if you have any questions or sugestions.

== Screenshots ==
1. The admin page
2. Example Style 1
3. Example Style 2

== Changelog ==
= 1.0.0 =
* Initial release.

= 1.0.2 =
* Replaced SimplePie with SimpleXML functions.

= 1.0.3 =
* Final release for public.

= 1.1.3 =
* Several bugs are fixed.
* Added setsresults in table.

= 1.1.4 =
* Updated the "Information" tab
* Added flexible 

= 1.1.5 =
* Created multiple language support

= 1.1.6 =
* Fixed error on admin page due to the new language support

= 1.1.7 =
* Fixed some RSS related bugs.

= 1.1.8 =
* Fixed some translation errors.
* Upgraded PHP with functions and classes

= 1.1.9 =
* Some minor bug fixes.

= 1.2.0 =
* Updated admin, better responsive design, added better legacy support 'nevobo-feed'

= 1.2.2 =
* Several bugfixes, Thanks for passing on.

== Upgrade Notice ==
= 1.0.0 =
This version fixes a few security related bugs. Upgrade immediately.

= 1.1.3 =
This version fixes a couple of bugs. Upgrade immediately.

= 1.1.6 =
Fixed error on admin page due to the new language support. 1.1.5 Users upgrade immediately.

= 1.2.0 =
BETA VERSION - UPDATE ONLY IF YOU HAVE PROBLEMS