Foundation Base, child theme for the Thematic 2.0
=================================================

Integrating Thematic 2.0 (pre-release) & Zurb Foundation 5.5 to enable easy path from prototyping to fully functional WordPress site.

Installation:

* download & unzip Thematic 2.0 from https://github.com/ThematicTheme/Thematic into /wp-content/themes
* download & unzip Foundation Base into /wp-content/themes
* activate Foundation Base child theme
* enjoy, with attitude

Features:

* using semantic mixins with Thematic IDs
* everything done in SASS
* javascripts nicely joined and minified with @codekit-append's
* supporting wp_nav_menu and wp_page_menu (if custom menu is not defined builds nav-bar from page hierarchy)
* ... and a Foundationbase sidenav widget that creates vertical navigation, listing pages from current page's topmost ancestor (unless on home page)

pets@tehnokratt.net / skype: petskratt

More Info
---------

http://thematictheme.com/

http://foundation.zurb.com/

Changelog
---------

2015-02-03

* things that were in bower_components are now in /lib - please note, that hidden file .bowerrc is required for that to work (you might miss it if you just download/expand ZIP)
* if any included components are referenced from scss or php - these point to something in /lib now
* /lib/foundation-base-lib is separate GIT submodule now (no need to worry, unless you plan to contribute)
* all php files are required from functions.php (previously some were included from foundation-base.php)
* this means, that if you change some part in /lib - move it to your foundation-base folder first and change require
* codekit project now defaults to compiling with libsass (faaaaaaaast) + creating map file for scss (and css is compressed by default)
* config.codekit.template includes these changes, overwrite your config.codekit with that
* @print styles, include at the very end of styles.scss
* editor styles

Updating foundation and foundation-base-lib is simple: go to the childtheme base folder and type:
`bower update`

This updates both foundation and foundation-base-lib (and creates some additional components in /lib ... that should not be needed in web, so you can safely remove everything, except foundation and foundation-base-lib, of course)

What are needed changes in existing projects, should you decide to bring them up to today's changes?

* download a copy of new foundation-base
* take requires from the start of functions.php, use in your current functions.php
* same for items at the beginning of style.scss and _settings.scss - foundation and thematic rows are important
* copy hidden .bowwerrc, not-hidden bower.json, codekit.config
* delete current /bower_components and /library (unless you have made local changes there), from scss _thematic.scss (that is now in lib)
* do `bower update` to have new /lib created

sorry for the mess, I hope that next upates will be way easier :-)

2015-02-02

* move all bower-updatable foundation-base components to foundation-base-lib
* introduce .bowerrc, use /lib instead of bower_components
* start gitignoring foundation components that are not needed during runtime (as everything needed is in vendor anyway)
* default scss processing to libsass, compressed, with sourcemaps

2015-02-02

* remove umlauts + cyrillic from filenames, also lowercase all filenames on upload
* possibility to switch off using Google jQuery (new constant, not mandatory)
* possibility to tell which version of jQuery to use from Google (new constants, not mandatory)
* "dummy" jquery registered for IE8 case - to avoid problems with plugins dependent on jquery (as IE8 fix adds jQuery not through official means but IE conditional in footer)
* all php files have now if ( ! defined( 'ABSPATH' ) ) exit; at the beginning to avoid direct execution attempts
* Foundation & dependencies upgrade to 5.5.1