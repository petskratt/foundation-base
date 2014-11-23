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


function childtheme_override_access() {
?>

    <div id="access">

    	<div class="skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip navigation to the content', 'thematic' ); ?>"><?php _e('Skip to content', 'thematic'); ?></a></div><!-- .skip-link -->

    	<?php
	if ( ( function_exists("has_nav_menu") ) && ( has_nav_menu( apply_filters('thematic_primary_menu_id', 'primary-menu') ) ) ) {
		echo  wp_nav_menu(thematic_nav_menu_args());
	} else {
		echo  foundationbase_access_page_menu(foundationbase_access_page_menu_args());
	}
?>

    </div><!-- #access -->
    <?php
}

function foundationbase_access_page_menu_args() {
	$args = array (
		'depth'    => 2,
		'sort_column' => 'menu_order',
		'menu_class'  => 'menu',
		'include'     => '',
		'exclude'     => '',
		'echo'        => FALSE,
		'show_home'   => true,
		'link_before' => '',
		'link_after'  => ''
	);
	return $args;
}

function foundationbase_access_page_menu( $args = array() ) {

	// based on wp 4.0 wp_page_menu
	// (added button-group class to ul and button class to homepage a)

	$defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
	$args = wp_parse_args( $args, $defaults );

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
		$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '" class="button">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
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
	$list_args['walker'] = new foundation_access_Walker_Page();
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu )
		$menu = '<ul class="button-group">' . $menu . '</ul>';

	$menu = '<div class="' . esc_attr($args['menu_class']) . '">' . $menu . "</div>\n";

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}


class foundation_access_Walker_Page extends Walker_Page {

	/**
	 * @see Walker::start_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 * @param array $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		// <ul id="drop1" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">
		var_dump($args);
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"f-dropdown\" id=\"drop2\" data-dropdown-content aria-hidden=\"true\" tabindex=\"-1\" >\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 * @param array $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object.
	 * @param int $depth Depth of page. Used for padding.
	 * @param int $current_page Page ID.
	 * @param array $args
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'page_item', 'page-item-' . $page->ID );

		$button_class = array( 'button');
		$button_data = "";

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'page_item_has_children';
			if ( $depth <= 1) {
				$button_class[] = 'dropdown';
				$button_data = "data-dropdown=\"drop{$page->ID}\" aria-controls=\"drop{$page->ID}\" aria-expanded=\"false\"";
			}
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current_page_ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
			}
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current_page_parent';
		}

		/**
		 * Filter the list of CSS classes to include with each page item in the list.
		 *
		 * @since 2.8.0
		 *
		 * @see wp_list_pages()
		 *
		 * @param array   $css_class    An array of CSS classes to be applied
		 *                             to each list item.
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 */



		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
		$button_classes = implode ( ' ', $button_class );

		if ( '' === $page->post_title ) {
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

		/** This filter is documented in wp-includes/post-template.php */
		$output .= $indent . sprintf(
			'<li class="%s"><a href="%s" class="%s" %s>%s%s%s</a>',
			$css_classes,
			get_permalink( $page->ID ),
			$button_classes,
			$button_data,
			$args['link_before'],
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after']
		);

	}

	/**
	 * @see Walker::end_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 * @param array $args
	 */
	public function end_el( &$output, $page, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

}



/* ============================
	SIDE MENU
   ============================ */


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
