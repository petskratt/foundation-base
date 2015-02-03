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