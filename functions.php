<?php

//
//  Child Theme Functions - customize your theme here,
//  all Foundation Base code kept in foundation-base.php for easy upgrades
//

require "foundation-base.php";

/*
// add language class to body

add_filter('body_class', 'krt_add_body_class_language');

function krt_add_body_class_language($c) {
	if ( defined('ICL_LANGUAGE_CODE') ) {
		$c[] = 'lang-' . ICL_LANGUAGE_CODE;
	}
	return $c;
}
*/

/*
// add post name class to body

add_filter( 'body_class', 'krt_add_body_class_postname' );

function krt_add_body_class_postname( $classes ) {

	global $post;

	if ( isset( $post ) && is_singular() ) {
		$classes[] = $post->post_name;
	}

	return $classes;
}
*/

/*
// Remove hooks'n stuff

function remove_stuff() {
    remove_action('thematic_header', 'thematic_brandingopen', 1);
    remove_action('thematic_header', 'thematic_blogtitle', 3);
    remove_action('thematic_header', 'thematic_blogdescription', 5);
    remove_action('thematic_header', 'thematic_brandingclose', 7);
    //remove_action('thematic_header', 'thematic_access', 9);
    remove_action('thematic_footer', 'thematic_siteinfo', 30);
}

add_action('init', 'remove_stuff');

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

/*
// disable comments

add_action('template_redirect', 'remove_comments');

function remove_comments () {
	remove_action('thematic_comments_template','thematic_include_comments',5);
}
*/

/*
// add 4th subsidiary aside, currently set up to be a footer widget underneath the 3 subs

add_filter('thematic_widgetized_areas', 'childtheme_add_subsidiary', 50);

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

add_filter('thematic_widgetized_areas', 'childtheme_hide_areas');

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
/*

/*
// Add http://alefeuvre.github.com/foundation-grid-displayer/ classes
// (must use google-hosted jquery, wp-included loads in no-conflict mode)

add_action('init', 'modify_jquery');

function modify_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false, '1.8.2');
		wp_enqueue_script('jquery');
	}
}


function childtheme_override_body(){
	echo '<body ';
	body_class();
	echo ' data-grid-framework="f3" data-grid-color="blue" data-grid-opacity="0.2" data-grid-zindex="10" data-grid-nbcols="12">' . "\n\n";
}

*/

/*
// Remove Front page title

add_filter('thematic_postheader_posttitle', 'krt_remove_postheader_posttitle');

function krt_remove_postheader_posttitle($title) {
	if (is_front_page() || is_page_template('filter.php')) {
		return ('');
	} else {
		return ($title);
	}
}
*/


/*
// Custom Excerpts - needs fix to use mb_ versions of string functions

function excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	return $excerpt;
}

function content($limit) {
	$content = explode(' ', get_the_content(), $limit);
	if (count($content)>=$limit) {
		array_pop($content);
		$content = implode(" ",$content).'...';
	} else {
		$content = implode(" ",$content);
	}
	$content = preg_replace('/[+]/','', $content);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}
*/


// Simple stuff

// add_image_size( 'something-thumb', 250, 250, true );

// add_theme_support( 'html5', array( 'search-form' ) );


/*
//disable comments output

add_action('template_redirect', 'krt_remove_comments');

function krt_remove_comments () {
	remove_action('thematic_comments_template','thematic_include_comments',5);
}
*/
