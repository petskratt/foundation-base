<?php

//
//  Responsive Base Child Theme Functions
//


// recreates the doctype section, html5boilerplate.com style with conditional classes
// http://scottnix.com/html5-header-with-thematic/
function childtheme_create_doctype() {
    $content = "<!doctype html>" . "\n";
    $content .= '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= '<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->'. "\n";
    $content .= '<!--[if IE 8]> <html class="no-js lt-ie9" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= "<!--[if gt IE 8]><!-->" . "\n";
    $content .= "<html class=\"no-js\"";
    return $content;
}
add_filter('thematic_create_doctype', 'childtheme_create_doctype');

// creates the head, meta charset, and viewport tags
function childtheme_head_profile() {
    $content = "<!--<![endif]-->";
    $content .= "\n" . "<head>" . "\n";
    $content .= "<meta charset=\"utf-8\" />" . "\n";
    $content .= "<meta name=\"viewport\" content=\"width=device-width\" />" . "\n";
    return $content;
}
add_filter('thematic_head_profile', 'childtheme_head_profile');

// remove meta charset tag, now in the above function
function childtheme_create_contenttype() {
    // silence
}
add_filter('thematic_create_contenttype', 'childtheme_create_contenttype');


// remove the index and follow tags from header since it is browser default.
// http://scottnix.com/polishing-thematics-head/
function childtheme_create_robots($content) {
    global $paged;
    if (thematic_seo()) {
        if((is_home() && ($paged < 2 )) || is_front_page() || is_single() || is_page() || is_attachment()) {
            $content = "";
        } elseif (is_search()) {
            $content = "\t";
            $content .= "<meta name=\"robots\" content=\"noindex,nofollow\" />";
            $content .= "\n\n";
        } else {
            $content = "\t";
            $content .= "<meta name=\"robots\" content=\"noindex,follow\" />";
            $content .= "\n\n";
        }
    return $content;
    }
}
add_filter('thematic_create_robots', 'childtheme_create_robots');




// clear useless garbage for a polished head
// remove really simple discovery
remove_action('wp_head', 'rsd_link');
// remove windows live writer xml
remove_action('wp_head', 'wlwmanifest_link');
// remove index relational link
remove_action('wp_head', 'index_rel_link');
// remove parent relational link
remove_action('wp_head', 'parent_post_rel_link');
// remove start relational link
remove_action('wp_head', 'start_post_rel_link');
// remove prev/next relational link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');



// kills the 4 scripts for the drop downs, combined and reloaded by the script manager (dropdowns-js)
function childtheme_override_head_scripts() {
    // silence
}



// script manager template for registering and enqueuing files
// http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes
function childtheme_script_manager() {
    // wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );
    
    // registers modernizr script, stylesheet local path, no dependency, no version, loads in header
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/javascripts/foundation/modernizr.foundation.js', false, false, false);
	  
    // registers additional scripts, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('placeholder-js', get_stylesheet_directory_uri() . '/javascripts/foundation/jquery.placeholder.min.js', array('jquery'), false, true);
    wp_register_script('tooltips-js', get_stylesheet_directory_uri() . '/javascripts/foundation/jquery.tooltips.js', array('jquery'), false, true);
    
    // registers app script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('app-js', get_stylesheet_directory_uri() . '/javascripts/app.js', array('jquery'), false, true);

    // enqueue the scripts for use in theme
    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script ('placeholder-js');
    wp_enqueue_script ('tooltips-js');

    //always enqueue this last, helps with conflicts
    wp_enqueue_script ('app-js');

}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');



// add favicon to site, add 16x16 "favicon.ico" image to child themes main folder
function childtheme_add_favicon() { ?>
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />
<?php }
add_action('wp_head', 'childtheme_add_favicon');



// register two additional custom menu slots
function childtheme_register_menus() {
    if (function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('init', 'childtheme_register_menus');



// completely remove nav above functionality
function childtheme_override_nav_above() {
    // silence
}



// add 4th subsidiary aside, currently set up to be a footer widget underneath the 3 subs
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



// change the default search box text
function childtheme_search_field_value() {
    return "Search";
}
add_filter('search_field_value', 'childtheme_search_field_value');


// add foundation-specific classes to menu
function childtheme_nav_menu_args() {

		$args = array (
		'theme_location'	=> apply_filters('thematic_primary_menu_id', 'primary-menu'),
		'menu'				=> '',
		'container'			=> 'div',
		'container_class'	=> 'menu',
		'menu_class'		=> 'nav-bar',
		'fallback_cb'		=> 'wp_page_menu',
		'before'			=> '',
		'after'				=> '',
		'link_before'		=> '',
		'link_after'		=> '',
		'depth'				=> 0,
		'walker'			=> new foundation_Walker_Nav_Menu,
		'echo'				=> false
	);
	
	return $args;

}

add_filter('thematic_nav_menu_args', 'childtheme_nav_menu_args');


add_filter('wp_nav_menu_objects', function ($items) {
    $hasSub = function ($menu_item_id, &$items) {
        foreach ($items as $item) {
            if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
                return true;
            }
        }
        return false;
    };

    foreach ($items as &$item) {
        if ($hasSub($item->ID, &$items)) {
            $item->classes[] = 'has-flyout'; // all elements of field "classes" of a menu item get join together and render to class attribute of <li> element in HTML
        }
    }
    return $items;    
});


class foundation_Walker_Nav_Menu extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"flyout\">\n";
	}

}

/*
// load google analytics
// optimized version http://mathiasbynens.be/notes/async-analytics-snippet
function snix_google_analytics(){ ?>
<script>var _gaq=[['_setAccount','UA-xxxxxxx-x'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src='//www.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s)}(document,'script'))</script>
<?php }
add_action('wp_footer', 'snix_google_analytics');
*/



// Unleash the power of Thematic's dynamic classes
// define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
// define('THEMATIC_COMPATIBLE_POST_CLASS', true);
// Unleash the power of Thematic's page comments
// define('THEMATIC_COMPATIBLE_COMMENT_HANDLING', true);
// Unleash the power of Thematic's comment form
// define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);
// Unleash the power of Thematic's feed link functions
// define('THEMATIC_COMPATIBLE_FEEDLINKS', true);


?>