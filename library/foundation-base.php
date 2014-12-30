<?php

//
//  Foundation Base Child Theme Functions
//

/* ============================
	MISC TUNING
   ============================ */


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

// remove recent comments inline-style

add_action('widgets_init', 'krt_remove_recent_comments_style');

function krt_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

// add no-js class to html

add_filter('thematic_html_class', 'krt_add_html_class_nojs');

function krt_add_html_class_nojs($classes) {
	if ( $classes ) {
		$classes .= " ";
	}
	$classes .= 'no-js';
	return $classes;
}

// kill scripts for Thematic drop downs, combined and reloaded by the script manager (dropdowns-js)

function childtheme_override_head_scripts() {
	// silence
}

// script manager template for registering and enqueuing files

function childtheme_script_manager() {
	// wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );

	// registers modernizr script, stylesheet local path, no dependency, no version, loads in header
	wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/js/modernizr.js', false, false, false);

	// registers app script, local stylesheet path, yes dependency is jquery, no version, loads in footer
	wp_register_script('app-js', get_stylesheet_directory_uri() . '/js/app.min.js', array('jquery'), false, true);

	// enqueue the scripts for use in theme
	wp_enqueue_script ('modernizr-js');

	//always enqueue this last, helps with conflicts
	wp_enqueue_script ('app-js');

}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');


// add favicon to site, add 16x16 "favicon.ico" image to child themes main folder

function childtheme_add_favicon() { ?>
  <link rel="apple-touch-icon-precomposed" sizes="144x144"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-144x144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon-precomposed.png">
  <link rel="shortcut icon"
  href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/favicon.ico" type="image/x-icon" />
<?php }

add_action('wp_head', 'childtheme_add_favicon');


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
	return str_replace( get_bloginfo( 'version' ), $my_theme->get( 'Version' ) , $s );
}

add_filter('style_loader_tag', 'krt_versioned_uri');
add_filter('script_loader_src', 'krt_versioned_uri');


// remove vcard from elements - to avoid Foundation vcard styling

add_filter('thematic_postmeta_authorlink', 'krt_remove_vcard');
add_filter('thematic_list_comments_arg', 'foundationbase_list_comments_arg');

function krt_remove_vcard($s) {
	return str_replace( 'vcard', '' , $s );
}

function foundationbase_list_comments_arg() {
	$content = array(
		'type' => 'comment',
		'callback' => 'foundationbase_comments'
	);
	return $content;
}

function foundationbase_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
?>

	   	<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>

			<?php
	// action hook for inserting content above #comment
	thematic_abovecomment();
?>

			<article id="comment-body-<?php comment_ID() ?>" class="comment-body">
				<footer class="comment-utility">
					<div class="comment-author"><?php thematic_commenter_link() ?></div><!-- .comment-author -->

						<?php thematic_commentmeta( true ); ?>

						<?php
	if ( $comment->comment_approved == '0' ) {
		echo "\t\t\t\t\t" . '<span class="unapproved">';
		_e( 'Your comment is awaiting moderation', 'thematic' );
		echo ".</span>\n";
	}
?>
				</footer><!-- .comment-utility -->

		        <div class="comment-content">
		    		<?php comment_text() ?>
				</div><!-- .comment-content -->

				<?php // echo the comment reply link with help from Justin Tadlock http://justintadlock.com/ and Will Norris http://willnorris.com/

	if( $args['type'] == 'all' || get_comment_type() == 'comment' ) {
		comment_reply_link( array_merge( $args, array(
					'add_below'  => 'comment-body',
					'reply_text' => __( 'Reply','thematic' ),
					'login_text' => __( 'Log in to reply.','thematic' ),
					'depth'      => $depth,
					'before'     => '<div class="comment-replylink">',
					'after'      => '</div>'
				)));
	}
?>

			</article><!-- .comment-body -->

			<?php
	// action hook for inserting content above #comment
	thematic_belowcomment()
?>

<?php }

// add Orbit slider support to NextGEN Gallery

add_filter('ngg_render_template', 'ngg_orbit_template', 10, 2);

function ngg_orbit_template( $path, $template_name = false) {
	if ($template_name == 'gallery-orbit')
		$path = dirname( __FILE__ ) . '/addons/gallery-orbit.php';
	return $path;
}


/* ============================
	TOP MENU
   ============================ */

function foundationbase_topbar ( $has_name = true, $align = "right", $has_search = true) {

	if ( $has_search ) {
	$search = '
				<li class="has-form">
				  <form id="searchform" method="get" action="' . home_url() . '">
					  <div class="row collapse">
					    <div class="large-8 small-9 columns">
					      <input type="text" id="s" name="s" placeholder="'.__( 'To search, type and hit enter', 'thematic' ).'" value="'.get_search_query().'">
					    </div>
					    <div class="large-4 small-3 columns">
							<input id="searchsubmit" type="submit" value="'. __( 'Search', 'thematic' ) .'" tabindex="2" class="alert button expand">
					    </div>
					  </div>
				  </form>
				</li>';
	} else $search = '';

	if ( $has_name ) {
		$name = '<a href="' . get_bloginfo( 'url' ) . '" title="' . get_bloginfo( 'description' ) . '">' . get_bloginfo( 'name' ) . '</a>';
	} else $name = '';

	$args = array (
	        'container' => false,
	        'container_class' => '',
	        'menu' => '',
	        'menu_class' => 'top-bar-menu ' . $align,
	        'theme_location' => 'primary-menu',
	        'before' => '',
	        'after' => '',
	        'link_before' => '',
	        'link_after' => '',
	        'depth' => 5,
	        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $search . '</ul>',
	        'fallback_cb' => false,
	        'walker' => new top_bar_walker()
	);


?>
	<nav class="top-bar" data-topbar role="navigation">
	  <ul class="title-area">
	    <li class="name">
	      <?php echo $name; ?>
	    </li>
	    <li class="toggle-topbar menu-icon"><a href="#"><span><?php _ex( 'Menu', 'Mobile navigation button', 'thematic' ); ?></span></a></li>
	  </ul>
	  <section class="top-bar-section">
<?php
	if ( ( function_exists( 'has_nav_menu' ) ) && ( has_nav_menu( apply_filters( 'thematic_primary_menu_id', 'primary-menu' ) ) ) ) {
		echo  wp_nav_menu( $args );
	} else {
		echo  foundationbase_page_menu( $args );
	}

?>
	  </section>
	</nav>
<?php
}


function childtheme_override_access() {
?>

    <div id="access" role="navigation">
    	<div class=""><a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'thematic' ); ?></a></div><!-- .skip-link -->
    	<?php
  	    foundationbase_topbar();
    	?>

    </div><!-- #access -->

    <?php
}


function foundationbase_page_menu( ) {

	// based on wp 4.0 wp_page_menu
	// (added button-group class to ul and button class to homepage a)

	$args = array (
		'depth'    => 3,
		'sort_column' => 'menu_order',
		'menu_class'  => 'top-bar-menu right',
		'include'     => '',
		'exclude'     => '',
		'echo'        => FALSE,
		'show_home'   => false,
		'link_before' => '',
		'link_after'  => '',
		'walker' => new top_bar_pagemenu_walker()
	);

	$args = apply_filters( 'wp_page_menu_args', $args );

	$menu = '';

	$list_args = $args;

	// Show Home in the menu
	if ( ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __('Home');
		else
			$text = $args['show_home'];
		$class = '';
		if ( is_front_page() && !is_paged() )
			$class = 'class="current_page_item"';
		$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
		// If the front page is a page, add it to the exclude list
		if (get_option('show_on_front') == 'page') {
			if ( !empty( $list_args['exclude'] ) ) {
				$list_args['exclude'] .= ',';
			} else {
				$list_args['exclude'] = '';
			}
			$list_args['exclude'] .= get_option('page_on_front');
		}
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu )
		$menu = '<ul id="menu-testmenuu" class="' . esc_attr($args['menu_class']) . '">' . $menu . '</ul>';

	// $menu = '<div class="' . esc_attr($args['menu_class']) . '">' . $menu . "</div>\n";

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}


class top_bar_pagemenu_walker extends Walker_Page {

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $element->has_children = !empty( $children_elements[$element->ID] );
        $element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'active' : '';
        $element->classes[] = ( $element->has_children && $max_depth !== 1 ) ? 'has-dropdown' : '';
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }






    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $item_html = '';
        parent::start_el( $item_html, $object, $depth, $args );
        $item_html = str_replace('page_item_has_children', 'page_item_has_children has-dropdown', $item_html);
		// add divider
        $output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';

        $output .= $item_html;

    }

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "\n<ul class=\"sub-menu dropdown\">\n";
    }

}



/* ============================
	SHORTCODES
   ============================ */

add_shortcode( 'accordion', 'accordion_shortcode_handler' );
add_shortcode( 'fold', 'accordion_fold_shortcode_handler' );

add_shortcode( 'row', 'classes_shortcode_handler' );
add_shortcode( 'column', 'classes_shortcode_handler' );
add_shortcode( 'div', 'classes_shortcode_handler' );

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
		$classes = ' class="'. trim( $classes ) . '"';
	}

	if ( $id ) {
		$id = ' id="'. $id . '"';
	}

	$content = "<div" . $classes . $id . ">" . do_shortcode( $content ) . "</div>";

	return $content;

}
