<?php

//
//  Foundation Base Child Theme Functions
//  (mostly from Responsive Base)
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


add_editor_style('editor-style.css');


// kills the 4 scripts for the drop downs, combined and reloaded by the script manager (dropdowns-js)
function childtheme_override_head_scripts() {
    // silence
}



// script manager template for registering and enqueuing files
// http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes
// * changed to use Foundation 3.0.6 JS
function childtheme_script_manager() {
    // wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );

    // registers modernizr script, stylesheet local path, no dependency, no version, loads in header
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/javascripts/foundation/modernizr.foundation.js', false, false, false);

    // registers additional scripts, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('foundation-js', get_stylesheet_directory_uri() . '/javascripts/foundation/foundation.min.js', array('jquery'), false, true);

    // registers app script, local stylesheet path, yes dependency is jquery, no version, loads in footer
    wp_register_script('app-js', get_stylesheet_directory_uri() . '/javascripts/foundation/app.min.js', array('jquery'), false, true);

    // enqueue the scripts for use in theme
    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script ('foundation-js');

    //always enqueue this last, helps with conflicts
    wp_enqueue_script ('app-js');

}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');



// add favicon to site, add 16x16 "favicon.ico" image to child themes main folder
// * added Foundation favicon and various sizes
function childtheme_add_favicon() { ?>
  <!-- For third-generation iPad with high-resolution Retina display: -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-144x144-precomposed.png">
  <!-- For iPhone with high-resolution Retina display: -->
  <link rel="apple-touch-icon-precomposed" sizes="114x114"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-114x114-precomposed.png">
  <!-- For first- and second-generation iPad: -->
  <link rel="apple-touch-icon-precomposed" sizes="72x72"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-72x72-precomposed.png">
  <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
  <link rel="apple-touch-icon-precomposed"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-precomposed.png">
  <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
  <link rel="shortcut icon"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/favicon.ico" type="image/x-icon" />
<?php }

add_action('wp_head', 'childtheme_add_favicon');


// add foundation-specific classes to menu - UL nav-bar
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
		'depth'				=> 2,
		'walker'			=> new foundation_Walker_Nav_Menu,
		'echo'				=> false
	);

	return $args;

}

add_filter('thematic_nav_menu_args', 'childtheme_nav_menu_args');


// add foundation-specific classes to menu - LI has-flyout and active

add_filter('wp_nav_menu_objects', 'foundation_menu_class');

function has_Sub($menu_item_id, &$items) {
	    foreach ($items as $item) {
	        if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
	            return true;
	        }
	    }
	    return false;
	}

function foundation_menu_class($items) {

    foreach ($items as &$item) {
        if ( has_Sub($item->ID, $items) ) {
            $item->classes[] = 'has-flyout';
            $item->hasFlyout = true;
        }

        if (in_array ('current-menu-item', $item->classes) || in_array ('current-menu-ancestor', $item->classes)) {
            $item->classes[] = 'active';
        }
    }
    return $items;
}


add_filter('walker_nav_menu_start_el', 'flyout_toggle_walker_nav_menu_start_el', 10, 4);

function flyout_toggle_walker_nav_menu_start_el($item_output, $item, $depth, $args)
{
	if (isset($item->hasFlyout)) {
		return $item_output . '<a href="#" class="flyout-toggle"><span> </span></a>' ;
	} else {
		return $item_output;
	}
}


// add foundation-specific classes to menu - UL sub-menu -> flyout
class foundation_Walker_Nav_Menu extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"flyout\">\n";
	}

}

// alternative walker for wp_list_pages - and related stuff

function childtheme_override_access() {
    ?>

    <div id="access">

    	<div class="skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip navigation to the content', 'thematic' ); ?>"><?php _e('Skip to content', 'thematic'); ?></a></div><!-- .skip-link -->

    	<?php
    	if ( ( function_exists("has_nav_menu") ) && ( has_nav_menu( apply_filters('thematic_primary_menu_id', 'primary-menu') ) ) ) {
    	    echo  wp_nav_menu(thematic_nav_menu_args());
    	} else {
    	    echo  childtheme_add_menuclass(wp_page_menu(thematic_page_menu_args()));
    	}
    	?>

    </div><!-- #access -->
    <?php
}

function childtheme_add_menuclass($ulclass) {
	return preg_replace( '/<ul>/', '<ul class="nav-bar">', $ulclass, 1 );
}

function childtheme_wp_page_menu_args($args) {

	$args['walker'] = new foundation_Walker_Page();
	return $args;

}

add_filter( 'wp_page_menu_args', 'childtheme_wp_page_menu_args');



class foundation_Walker_Page extends Walker_Page {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<a href='#' class='flyout-toggle'></a>\n$indent<ul class='flyout'>\n";
	}

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';

		extract($args, EXTR_SKIP);
		$css_class = array('page_item', 'page-item-'.$page->ID);
		if ( !empty($current_page) ) {
			$_current_page = get_post( $current_page );
			if ( in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current_page_ancestor';
				$css_class[] = 'active';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item';
				$css_class[] = 'active';
			}
			elseif ( $_current_page && $page->ID == $_current_page->post_parent )
				$css_class[] = 'current_page_parent';
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current_page_parent';
			$css_class[] = 'active';
		}

		if ( $args['has_children'] ) {
			$css_class[] = 'has-flyout';
		}

		$css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		$output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

		if ( !empty($show_date) ) {
			if ( 'modified' == $show_date )
				$time = $page->post_modified;
			else
				$time = $page->post_date;

			$output .= " " . mysql2date($date_format, $time);
		}
	}


}

class Foundation_Widget_Sidenav extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'foundation_widget_sidenav', 'description' => __( 'Lists pages from current page ancestor down.') );
		parent::__construct('foundation_sidenav', __('Foundation Sidebar Navigation', 'foundation'), $widget_ops);
	}

	function widget( $args, $instance ) {

		if(!is_page() && !is_home()) return;

		global $post;

		if($post == null) {
            return;
        }

        if(is_home() || !$post->ancestors){
                $pid = $post->ID;
            }else{
                $pid = end(array_values($post->ancestors));
        }


		extract( $args );

		$title = '<a href="' . get_permalink ($pid) . '">' . apply_filters('widget_title', get_the_title($pid), $instance, $this->id_base) . '</a>';
		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

		if ( $sortby == 'menu_order' )
			$sortby = 'menu_order, post_title';

		$args = array (
			'title_li' => '',
			'child_of' => $pid,
			'depth' => 2,
			'echo' => 0,
			'sort_column' => $sortby,
			'exclude' => $exclude,
			'walker' => new foundation_Walker_Page()
		);

		$out =  wp_list_pages(  $args ) ;

		if ( !empty( $out ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title ;
		?>
		<ul class="nav-bar vertical">
			<?php echo $out; ?>
		</ul>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID' ) ) ) {
			$instance['sortby'] = $new_instance['sortby'];
		} else {
			$instance['sortby'] = 'menu_order';
		}

		$instance['exclude'] = strip_tags( $new_instance['exclude'] );

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'sortby' => 'post_title', 'title' => '', 'exclude' => '') );
		$title = esc_attr( $instance['title'] );
		$exclude = esc_attr( $instance['exclude'] );
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>
			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
				<option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php _e('Page title'); ?></option>
				<option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php _e('Page order'); ?></option>
				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Page ID' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label> <input type="text" value="<?php echo $exclude; ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Page IDs, separated by commas.' ); ?></small>
		</p>
<?php
	}

}

function foundation_register_widgets() {
	register_widget( 'Foundation_Widget_Sidenav' );
}

add_action( 'widgets_init', 'foundation_register_widgets' );


// based on WP Nice Slug http://wordpress.org/extend/plugins/wp-nice-slug/ by Spectraweb s.r.o. www.spectraweb.cz
// using translit class (c) YURiQUE (Yuriy Malchenko), 2005 jmalchenko@gmail.com

class Translit {
	var $cyr=array(
		"Щ",  "Ш", "Ч", "Ц", "Ю", "Я", "Ж", "А", "Б", "В", "Г", "Д", "Е", "Ё", "З", "И", "Й", "К",
		"Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ь", "Ы", "Ъ", "Э", "Є", "Ї",
		"щ",  "ш", "ч", "ц", "ю", "я", "ж", "а", "б", "в", "г", "д", "е", "ё", "з", "и", "й", "к",
		"л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ь", "ы", "ъ", "э", "є", "ї");
	var $lat=array(
		"Shh", "Sh", "Ch", "C", "Ju", "Ja", "Zh", "A", "B", "V", "G", "D", "Je", "Jo", "Z", "I", "J", "K",
		"L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "Kh", "'", "Y", "`", "E", "Je", "Ji",
		"shh", "sh", "ch", "c", "ju", "ja", "zh", "a", "b", "v", "g", "d", "je", "jo", "z", "i", "j", "k",
		"l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "kh", "'", "y", "`", "e", "je", "ji"
	);

	function Transliterate($str, $encIn, $encOut) {

		$str = iconv($encIn, "utf-8", $str);
		$original = $str;
		for ($i=0; $i<count($this->cyr); $i++) {
			$c_cyr = $this->cyr[$i];
			$c_lat = $this->lat[$i];
			$str = str_replace($c_cyr, $c_lat, $str);
		}
		if ($original != $str) {
		  	$str = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", "\${1}e", $str);
			$str = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", "\${1}'", $str);
			$str = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $str);
			$str = preg_replace("/^kh/", "h", $str);
			$str = preg_replace("/^Kh/", "H", $str);
		}

		return iconv("utf-8", $encOut, $str);
	}

}

function krt_sanitize_title($title, $raw_title, $context) {
	if ($context == 'save') {
		$t = new Translit();
		$slug = $t->Transliterate($title, "utf-8", "utf-8");
		$slug = strtolower($slug);
		$slug = preg_replace('/\s+/', ' ', $slug);
		$slug = str_replace(' ', '-', $slug);
		$slug = preg_replace('/[^a-z0-9-_]/', '', $slug);
		$slug = preg_replace('/[-]+/', '-', $slug);
		$title = trim($slug, '-');
	}
	return $title;
}

add_filter('sanitize_title', 'krt_sanitize_title', 0, 3);

// add child theme version to css, js

function krt_versioned_uri($s) {
	$my_theme = wp_get_theme();
	return str_replace( get_bloginfo( 'version' ), $my_theme->Version , $s );
}

add_filter('style_loader_tag', 'krt_versioned_uri');
add_filter('script_loader_src', 'krt_versioned_uri');

// shortcodes - row, column, div, accordion & fold

function accordion_shortcode_handler( $atts=null, $content=null, $code="" ) {

	$output = '<ul class="accordion">' . do_shortcode( $content ) . '</ul>';

	return $output;

}

function accordion_fold_shortcode_handler( $atts=null, $content=null, $code="" ) {

	extract( shortcode_atts( array( 'h1' => null, 'h2' => null, 'h3' => null, 'h4' => null  ), $atts ) );

	$output = '<div class="content">' . $content . '</div>';

	if ( $h1 ) $output = '<div class="title"><h1>' . $h1 . '</h1></div>' . $output;
	if ( $h2 ) $output = '<div class="title"><h2>' . $h2 . '</h2></div>' . $output;
	if ( $h3 ) $output = '<div class="title"><h3>' . $h3 . '</h3></div>' . $output;
	if ( $h4 ) $output = '<div class="title"><h4>' . $h4 . '</h4></div>' . $output;

	$output = '<li>' . $output . '</li>';

	return $output;

}

add_shortcode( 'accordion', 'accordion_shortcode_handler' );
add_shortcode( 'fold', 'accordion_fold_shortcode_handler' );


function classes_shortcode_handler( $atts=null, $content=null, $code="" ) {

	extract( shortcode_atts( array( 'class' => null, 'id' => null ), $atts ) );

	$classes = "";

	if ( $code != "div" ) {
		$classes .= $code . " ";
	}

	if ( $class ) {
		$classes .= $class . " ";
	}

	if ( $classes ) {
		$classes = ' class="'. trim ( $classes ) . '"';
	}

	if ( $id ) {
		$id = ' id="'. $id . '"';
	}

	$content = "<div" . $classes . $id . ">" . do_shortcode( $content ) . "</div>";

	return $content;

}

add_shortcode( 'row', 'classes_shortcode_handler' );
add_shortcode( 'column', 'classes_shortcode_handler' );
add_shortcode( 'div', 'classes_shortcode_handler' );

// add Orbit slider support to NextGEN Gallery

add_filter('ngg_render_template', 'ngg_orbit_template', 10, 2);

function ngg_orbit_template( $path, $template_name = false) {
	if ($template_name == 'gallery-orbit')
		$path = dirname( __FILE__ ) . '/addons/gallery-orbit.php';
	return $path;
}
