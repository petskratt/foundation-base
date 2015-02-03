<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/* =================================================
	Shortcodes for Foundation grid and enhancements
   ================================================= */

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
