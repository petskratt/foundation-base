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
