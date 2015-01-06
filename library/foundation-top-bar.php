<?php

/* ==============================================
	Main menu using top-bar (from menu or pages)
   ============================================== */

function childtheme_override_access() {

	$topbar_args = apply_filters ( 'foundationbase_topbar_args',
			array ( 'has_name' => true,
					'name_text' => get_bloginfo( 'name' ),
					'name_title' => get_bloginfo( 'description' ),
					'align' => 'right',
					'has_search' => true,
					'search_placeholder' => __( 'To search, type and hit enter', 'thematic' ),
					'search_button' => __( 'Search', 'thematic' ),
					'depth' => 0,
					'show_home' => false
				)
			);

?>
    <div id="access" role="navigation">
    	<div class=""><a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'thematic' ); ?></a></div><!-- .skip-link -->
    	<?php
		foundationbase_topbar( $topbar_args );
?>
    </div><!-- #access -->
    <?php
}

// navigation menu function and walker

function foundationbase_topbar ( $topbar_args ) {

	if ( $topbar_args['has_search'] === true ) {
		$topbar_args['search_html'] = '
				<li class="has-form search">
				  <form id="searchform" method="get" action="' . home_url() . '">
					  <div class="row collapse">
					    <div class="large-8 small-9 columns">
					      <input type="text" id="s" name="s" placeholder="'. $topbar_args['search_placeholder'] .'" value="'.get_search_query().'">
					    </div>
					    <div class="large-4 small-3 columns">
							<input id="searchsubmit" type="submit" value="'. $topbar_args['search_button'] .'" tabindex="2" class="alert button expand">
					    </div>
					  </div>
				  </form>
				</li>';
	} else $topbar_args['search_html'] = '';

	if ( $topbar_args['has_name'] === true ) {
		$name = '<a href="' . get_bloginfo( 'url' ) . '" title="' . $topbar_args['name_title'] . '">' . $topbar_args['name_text'] . '</a>';
	} else $name = '';

	$args = array (
		'container' => false,
		'container_class' => '',
		'menu' => '',
		'menu_class' => 'top-bar-menu ' . $topbar_args['align'],
		'theme_location' => 'primary-menu',
		'before' => '',
		'after' => '',
		'link_before' => '',
		'link_after' => '',
		'depth' => $topbar_args['depth'],
		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $topbar_args['search_html'] . '</ul>',
		'fallback_cb' => false,
		'walker' => new Foundationbase_Topbar_Navmenu_Walker()
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
		echo  foundationbase_page_menu( $topbar_args );
	}

?>
	  </section>
	</nav>
<?php
}



class Foundationbase_Topbar_Navmenu_Walker extends Walker_Nav_Menu {

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $element->has_children = !empty( $children_elements[$element->ID] );
        $element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'active' : '';
        $element->classes[] = ( $element->has_children && $max_depth !== $depth+1 ) ? 'has-dropdown' : '';

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $item_html = '';
        parent::start_el( $item_html, $object, $depth, $args );
		// add divider
        $output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';

        $classes = empty( $object->classes ) ? array() : (array) $object->classes;

        if( in_array('label', $classes) ) {
            $output .= '<li class="divider"></li>';
            $item_html = preg_replace( '/<a[^>]*>(.*)<\/a>/iU', '<label>$1</label>', $item_html );
        }

	    if ( in_array('divider', $classes) ) {
	        $item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '', $item_html );
	    }

	        $output .= $item_html;
	    }

	    function start_lvl( &$output, $depth = 0, $args = array() ) {
	        $output .= "\n<ul class=\"sub-menu dropdown\">\n";
	    }

}

if ( ! function_exists( 'add_menuclass') ) {
	function add_menuclass($ulclass) {
	    $find = array('/<a rel="button"/', '/<a title=".*?" rel="button"/');
	    $replace = array('<a rel="button" class="button"', '<a rel="button" class="button"');

	    return preg_replace($find, $replace, $ulclass, 1);
	}
	add_filter('wp_nav_menu','add_menuclass');
}


// pagemenu function and walker

function foundationbase_page_menu( $topbar_args ) {

	// based on wp 4.0 wp_page_menu
	// (added button-group class to ul and button class to homepage a)

	$args = array (
		'depth'    => $topbar_args['depth'],
		'sort_column' => 'menu_order',
		'menu_class'  => 'top-bar-menu right',
		'include'     => '',
		'exclude'     => '',
		'echo'        => FALSE,
		'show_home'   => $topbar_args['show_home'],
		'link_before' => '',
		'link_after'  => '',
		'walker' => new Foundationbase_Topbar_Pagemenu_Walker()
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

	if ( $menu && $topbar_args['has_search'] ) $menu .= $topbar_args['search_html'];

	if ( $menu )
		$menu = '<ul id="menu-testmenuu" class="' . esc_attr($args['menu_class']) . '">' . $menu . '</ul>';

	// $menu = '<div class="' . esc_attr($args['menu_class']) . '">' . $menu . "</div>\n";

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}


class Foundationbase_Topbar_Pagemenu_Walker extends Walker_Page {

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		$element->has_children = !empty( $children_elements[$element->ID] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {

		$item_html = '';

		parent::start_el( $item_html, $page, $depth, $args, $current_page );
		if ( $page->has_children && $depth !== $args['depth']-1 ) {
			$item_html = str_replace('page_item_has_children', 'page_item_has_children has-dropdown', $item_html);
		}
		$item_html = str_replace('current_page_item', 'current_page_item active', $item_html);
		$item_html = str_replace('current_page_ancestor', 'current_page_ancestor active', $item_html);

		$output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';

		$output .= $item_html;

	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "\n<ul class=\"sub-menu dropdown\">\n";
	}

}
