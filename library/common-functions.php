<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// this partial contains functions that are always used in our WordPress implementation, but not related to Thematic or Foundation


// based on WP Nice Slug http://wordpress.org/extend/plugins/wp-nice-slug/ by Spectraweb s.r.o. www.spectraweb.cz
// using translit class (c) YURiQUE (Yuriy Malchenko), 2005 jmalchenko@gmail.com

add_filter('sanitize_title', 'krt_sanitize_title', 0, 3);

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

// sanitize filenames to avoid case, umlaut and cyrillic issues

add_filter('sanitize_file_name', 'krt_sanitize_filename', 10, 2);

function krt_sanitize_filename ($filename, $filename_raw) {
    $t = new Translit();
    return $t->Transliterate(remove_accents(mb_strtolower($filename)), "utf-8", "utf-8");
}


// add child theme version to css, js

add_filter('style_loader_tag', 'krt_versioned_uri');
add_filter('script_loader_src', 'krt_versioned_uri');

function krt_versioned_uri($s) {
	$my_theme = wp_get_theme();
	return str_replace( get_bloginfo( 'version' ), $my_theme->get( 'Version' ) , $s );
}

// is_child_of Copyright 2009 GPL Luke Williams (email : luke@red-root.com)

function is_child_of($topid, $thispageid = null) {
	global $post;

	if($thispageid == null)
		$thispageid = $post->ID; # no id set so get the post object's id.

	$current = get_page($thispageid);

	if($current->post_parent != 0) # so there is a parent
		{
		if($current->post_parent != $topid)
			return is_child_of($topid, $current->post_parent); # not that page, run again
		else
			return true; # are so it is
	}
	else
	{
		return false; # no parent page so return false
	}
}

function icl_is_child_of($topid, $thispageid = null) {
	return is_child_of ( icl_object_id( $topid, 'page', true ), $thispageid);
}
