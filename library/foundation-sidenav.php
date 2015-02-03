<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/* ==================
	Side menu widget
   ================== */

add_action( 'widgets_init', 'foundationbase_register_widgets' );

function foundationbase_register_widgets() {
	register_widget( 'Foundationbase_Widget_Sidenav' );
}

class Foundationbase_Widget_Sidenav extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'foundationbase_widget_sidenav', 'description' => __( 'Lists pages from current page ancestor down.') );
		parent::__construct('foundationbase_sidenav', __('Foundationbase Sidebar Navigation', 'foundation'), $widget_ops);
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
			'depth' => 0,
			'echo' => 0,
			'sort_column' => $sortby,
			'exclude' => $exclude,
			'walker' => new Foundationbase_Sidenav_Pagemenu_Walker()
		);

		$out =  wp_list_pages(  $args ) ;

		if ( !empty( $out ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title ;
?>
		<ul class="sidebar-nav">
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

class Foundationbase_Sidenav_Pagemenu_Walker extends Walker_Page {

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		$item_html = '';

		$current_page = get_post( $args[1] );

		$args[0]['had_children'] = false;

		if ( $depth === 0 && !empty( $children_elements[$element->ID] ) && ( ( $args[1] !== $element->ID ) && ( !in_array( $element->ID, $current_page->ancestors ) )  ) )  {
			$this->unset_children( $element, $children_elements );
			$args[0]['had_children'] = true;
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $item_html );

		$output .= $item_html;

	}

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		$item_html = '';
		parent::start_el( $item_html, $page, $depth, $args, $current_page );

		if ( ($depth == 0) && ( $args['has_children'] || $args['had_children'] ) ) {
			$item_html = str_replace('class="', 'class="has-pages ', $item_html);
		}

//		$item_html .= ( ($depth == 0) && ( $args['has_children'] || $args['had_children'] ) ) ? " »" : "";
		$output .= $item_html;
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "\n<ul class=\"sub-menu dropdown\">\n";
	}

}