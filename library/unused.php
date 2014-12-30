<?php

// add foundation-specific classes to menu - UL nav-bar

function childtheme_nav_menu_args() {

		$args = array (
		'theme_location'	=> apply_filters('thematic_primary_menu_id', 'primary-menu'),
		'menu'				=> '',
		'container'			=> 'div',
		'container_class'	=> 'menu',
		'menu_class'		=> 'button-group',
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

// add_filter('thematic_nav_menu_args', 'childtheme_nav_menu_args');


// add foundation-specific classes to menu - LI has-flyout and active

// add_filter('wp_nav_menu_objects', 'foundation_menu_class');

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



/* remove?
function Xchildtheme_add_menuclass($ulclass) {
	return preg_replace( '/<ul>/', '<ul class="button-group">', $ulclass, 1 );
}

function Xchildtheme_wp_page_menu_args($args) {

	$args['walker'] = new foundation_Walker_Page();
	return $args;

}

// add_filter( 'wp_page_menu_args', 'childtheme_wp_page_menu_args');

*/



class foundation_Walker_Page extends Walker_Page {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<a href='#' class='button dropdown' data-dropdown='drop1' aria-controls='drop1' aria-expanded='false'></a>\n$indent<ul id='drop1' data-dropdown-content class='f-dropdown' aria-hidden='true' tabindex='-1'>\n";
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

		$output .= $indent . '<li class="' . $css_class . '" ><a href="' . get_permalink($page->ID) . '" class="button">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

		if ( !empty($show_date) ) {
			if ( 'modified' == $show_date )
				$time = $page->post_modified;
			else
				$time = $page->post_date;

			$output .= " " . mysql2date($date_format, $time);
		}
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








// add_filter( 'wp_get_nav_menu_items', 'foundationbase_addtomenu', 10, 3 );

function foundationbase_addtomenu( $items, $menu, $args ) {

	$new_item = new stdClass;
	$new_item->url = "#";
	$new_item->title = "tekst";
	$new_item->menu_order = 9999;

	$items[] = $new_item;

	return $items;
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
		// var_dump($args);
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