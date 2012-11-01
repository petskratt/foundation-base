<?php

//
//  Child Theme Functions - customize your theme here,
//  all Foundation Base code kept in foundation-base.php for easy upgrades
//

require "foundation-base.php";


// Unleash the power of Thematic's dynamic classes
// define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
// define('THEMATIC_COMPATIBLE_POST_CLASS', true);
// Unleash the power of Thematic's page comments
// define('THEMATIC_COMPATIBLE_COMMENT_HANDLING', true);
// Unleash the power of Thematic's comment form
// define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);
// Unleash the power of Thematic's feed link functions
// define('THEMATIC_COMPATIBLE_FEEDLINKS', true);


/* register two additional custom menu slots
function childtheme_register_menus() {
    if (function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('init', 'childtheme_register_menus');
*/


/* completely remove nav above functionality
function childtheme_override_nav_above() {
    // silence
}
*/


/* add 4th subsidiary aside, currently set up to be a footer widget underneath the 3 subs
function childtheme_add_subsidiary($content) {
    $content['Footer Widget Aside'] = array(
            'admin_menu_order' => 550,
            'args' => array (
            'name' => 'Footer Aside',
            'id' => '4th-subsidiary-aside',
            'description' => __('The 4th bottom widget area in the footer.', 'thematic'),
            'before_widget' => thematic_before_widget(),
            'after_widget' => thematic_after_widget(),
            'before_title' => thematic_before_title(),
            'after_title' => thematic_after_title(),
                ),
            'action_hook'   => 'widget_area_subsidiaries',
            'function'      => 'childtheme_4th_subsidiary_aside',
            'priority'      => 90
        );
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_add_subsidiary', 50);

// set structure for the 4th subsidiary aside
function childtheme_4th_subsidiary_aside() {
    if (is_active_sidebar('4th-subsidiary-aside')) {
        echo "\n".'<div id="fourth" class="aside footer-aside">' . "\n" . "\t" . '<ul class="xoxo">' . "\n";
        dynamic_sidebar('4th-subsidiary-aside');
        echo "\n" . "\t" . '</ul>' ."\n" . '</div><!-- #fourth .footer-aside -->' ."\n";
    }
}
*/


/*
// hide unused widget areas inside the WordPress admin
function childtheme_hide_areas($content) {
    unset($content['Index Top']);
    unset($content['Index Insert']);
    unset($content['Index Bottom']);
    unset($content['Single Top']);
    unset($content['Single Insert']);
    unset($content['Single Bottom']);
    unset($content['Page Top']);
    unset($content['Page Bottom']);
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_hide_areas');
*/

/*
// load google analytics
// optimized version http://mathiasbynens.be/notes/async-analytics-snippet
function snix_google_analytics(){ ?>
<script>var _gaq=[['_setAccount','UA-xxxxxxx-x'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src='//www.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s)}(document,'script'))</script>
<?php }
add_action('wp_footer', 'snix_google_analytics');
*/

/*
// Add http://alefeuvre.github.com/foundation-grid-displayer/ classes
// (must use google-hosted jquery, wp-included loads in no-conflict mode)

function modify_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false, '1.8.2');
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'modify_jquery');

function childtheme_override_body(){
    echo '<body ';
    body_class();
  	echo ' data-grid-framework="f3" data-grid-color="blue" data-grid-opacity="0.2" data-grid-zindex="10" data-grid-nbcols="12">' . "\n\n";
}
*/

?>