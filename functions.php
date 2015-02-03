<?php

if ( ! defined( 'ABSPATH' ) ) exit;

//
//  Child Theme Functions - customize your theme here,
//  all Foundation Base code kept in foundation-base.php for easy upgrades
//

// IE8 support uses solutions from http://foundation.zurb.com/forum/posts/241-foundation-5-and-ie8
// and reference implementation from http://www.kycosoftware.com/blog/article/getting-foundation-5-to-work-in-ie8
// with updated shims that are assembled into ie8-head.min.js
define( 'IE8_F5_SUPPORT', true );
define( 'GOOGLE_JQUERY', true );

require_once "library/foundation-base.php";
require_once "library/foundation-top-bar.php";
require_once "library/foundation-sidenav.php";

// main menu (top bar) settings


add_filter('foundationbase_topbar_args', 'krt_foundationbase_topbar_args');

function krt_foundationbase_topbar_args ( $args ) {
//	$args['has_search'] = false;
	return $args;
}



// theme support (for taxonomy, post type etc code templates - use http://generatewp.com/ )

// set_post_thumbnail_size( 82, 82, true ); // Normal post thumbnails
// add_image_size( 'something-thumb', 250, 250, true );

// add_theme_support( 'html5', array( 'search-form' ) );

// add_filter('widget_text', 'do_shortcode');

// add_post_type_support( 'page', 'excerpt' );

add_editor_style('editor-style.css');


// remove from header information that is not needed

// remove pingback link
add_filter ('thematic_show_pingback', '__return_false');

// remove RSS feeds (posts feed might be good to keep)
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed

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

// remove recent comments inline-style

add_action('widgets_init', 'krt_remove_recent_comments_style');

function krt_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

// remove WPML styles

define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);

// Remove Thematic header components
/*

function remove_stuff() {
    remove_action('thematic_header', 'thematic_brandingopen', 1);
    remove_action('thematic_header', 'thematic_blogtitle', 3);
    remove_action('thematic_header', 'thematic_blogdescription', 5);
    remove_action('thematic_header', 'thematic_brandingclose', 7);
    //remove_action('thematic_header', 'thematic_access', 9);
	remove_action('thematic_footer', 'thematic_siteinfoopen', 20);
	remove_action('thematic_footer', 'thematic_siteinfo', 30);
	remove_action('thematic_footer', 'thematic_siteinfoclose', 40);
}

add_action('init', 'remove_stuff');

*/

// hide unused widget areas inside the WordPress admin
/*

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
*/


// disable or modify post header/footer/navigation
/*
function childtheme_override_postheader_postmeta() {
	// silence
}

function childtheme_override_nav_above() {
    // silence
}

function childtheme_override_nav_below() {
	// silence
}

function childtheme_override_postfooter() {
	// silence
}

function childtheme_override_postfooter_postcategory() {
	// silence
}
*/

// remove Thematic comments component
/*

add_action('template_redirect', 'remove_comments');

function remove_comments () {
	remove_action('thematic_comments_template','thematic_include_comments',5);
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

// ACF empty <p> tags are removed

/*
function ptobr($string)
{
	return preg_replace("/<\/p>[^<]*<p>/", "<br /><br />", $string);
}

function stripp($string){
	return preg_replace('/(<p>|<\/p>)/i','',$string) ;
}
*/

// Header logo

/*
function krt_logo_image() {
	echo '<div id="branding"><a href="'.get_option( 'home' ).'"><img id="logo" src="'.get_bloginfo( 'stylesheet_directory' ).'/images/logo.png" /></a></div>';
}

add_action( 'thematic_header', 'krt_logo_image', 3 );
*/

// Header language selector

/*
add_action( 'thematic_header', 'krt_simple_languageselector', 2 );

function krt_simple_languageselector() {
	$output="";
	$langDisplay = array("et" => "EST", "en" => "ENG", "ru" => "RUS");
	$languages = icl_get_languages('skip_missing=0');
	uasort($languages, create_function('$a,$b', 'return $a[\'language_code\'] === \'et\' ? -1 : ($b[\'language_code\'] === \'et\' ? 1 : ($a[\'language_code\'] > $b[\'language_code\']));'));
	// uasort($languages, create_function('$a,$b','return $a[\'native_name\'] > $b[\'native_name\'];'));
	if (!empty($languages)) {
		$output .= '<ul id="langmenu">';
		$pos = 0;
		foreach ($languages as $l) {
			//if ($pos > 0) {
			//	$output .= '<span>&nbsp;/&nbsp;</span>';
			//}
			if ($l['active'] == 0) {
				$output .= '<li><a href="'.$l['url'].'">';
			} else {
				$output .= '<li class="active">';
			}
			$output .=  '<span class="lang">' . $langDisplay [ $l['language_code'] ] . '</span>';
			// $output .=  $l['language_code'];
			if ($l['active'] == 0) {
				$output .= '</a></li>';
			} else {
				$output .= '</li>';
			}
			$pos++;
		}
		$output .= '</ul> <!-- #langmenu -->';
	}
	return $output;
}
*/

// add language class to body
/*
add_filter('body_class', 'krt_add_body_class_language');

function krt_add_body_class_language($c) {
	if ( defined('ICL_LANGUAGE_CODE') ) {
		$c[] = 'lang-' . ICL_LANGUAGE_CODE;
	}
	return $c;
}
*/


// add post name class to body
/*
add_filter( 'body_class', 'krt_add_body_class_postname' );

function krt_add_body_class_postname( $classes ) {

	global $post;

	if ( isset( $post ) && is_singular() ) {
		$classes[] = $post->post_name;
	}

	return $classes;
}
*/


// register two additional custom menu slots
/*
function childtheme_register_menus() {
    if (function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('init', 'childtheme_register_menus');
*/



// add 4th subsidiary aside, currently set up to be a footer widget underneath the 3 subs
/*

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

function string_limit_words($string, $word_limit) {
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit) {
  array_pop($words);
  //add a ... at last article when more than limit word count
  echo implode(' ', $words)."..."; } else {
  //otherwise
  echo implode(' ', $words); }
}


add_filter( 'excerpt_length', 'krt_custom_excerpt_length', 999 );
function krt_custom_excerpt_length( $length ) {
	return 70;
}

add_filter('excerpt_more', 'krt_excerpt_more');
function krt_excerpt_more( $more ) {
    return '...';
}

*/

// customize admin toolbar

add_action( 'wp_before_admin_bar_render', 'krt_admin_toolbar', 999 );
function krt_admin_toolbar() {

	global $wp_admin_bar;

	$wp_admin_bar->remove_node('appearance');

	$wp_admin_bar->add_group( array( 'parent' => 'site-name', 'id' => 'shortcuts' ) );


	if ( current_user_can( 'edit_posts' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'shortcuts', 'id' => 'posts', 'title' => _x('Posts', 'post type general name'), 'href' => admin_url('edit.php') ) );

	if ( current_user_can( 'edit_pages' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'shortcuts', 'id' => 'pages', 'title' => _x('Pages', 'post type general name'), 'href' => admin_url('edit.php?post_type=page') ) );

/*
	if ( current_user_can( 'edit_posts' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'shortcuts', 'id' => 'institutions', 'title' => __( 'Institutions', 'research' ), 'href' => admin_url('edit.php?post_type=institution') ) );
*/

	if ( current_user_can( 'edit_theme_options' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'shortcuts', 'id' => 'widgets', 'title' => __('Widgets'), 'href' => admin_url('widgets.php') ) );

	if ( current_user_can( 'edit_theme_options' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'shortcuts', 'id' => 'menus', 'title' => __('Menus'), 'href' => admin_url('nav-menus.php') ) );

	if ( current_user_can( 'list_users' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'shortcuts', 'id' => 'users', 'title' => __('Users'), 'href' => admin_url('users.php') ) );

}