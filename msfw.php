<?php
/*
Plugin Name: Make Safe For Work
Plugin URI: http://innerdvations.com/plugins/make-safe-for-work/
Description: Adds a shortcode for not-safe-for-work content that doesn't even load the inappropriate content from the server until requested by the user. Just wrap any offending content in [nsfw][/nsfw] and your post will bypass even restrictive work filters.  Users can then click a link that will reload the full uncensored version of the page.
Version: 0.1
Author: Ben Irvin
Author URI: http://innerdvations.com/
Tags: nsfw, nsw, not safe for work, msfw, make safe for work, spoiler, comment, hide, censor, redact
Wordpress URI: http://wordpress.org/extend/plugins/make-safe-for-work/
License: GPLv3
*/

// shortcode function
// with "pad='false'" you can disable padding of censored content to original size
// [nsfw pad='false']		don't pad content
// with 'type', you can choose method of censorship
// [nsfw type='reload']		default. click reloads page and reveals content
// [nsfw type='deleted'] 	NEVER show content. note that it still may appear in areas that don't filter shortcodes.
// [nsfw type='spoiler'] 	rollover reveals content
//
// NOT YET IMPLEMENTED:
// [nsfw type='click']		click reveals content via javascript. not yet implemented, do not use.

$msfw_total_count = 0;
function msfw($atts,$content='') {
	global $msfw_total_count;
	
	// set our defaults if they aren't already set elsewhere
	if(!defined('MSFW_DEFAULT_TYPE')) {
		define('MSFW_DEFAULT_TYPE','reload');
	}
	if(!defined('MSFW_PADDING')) {
		define('MSFW_PADDING',true);
	}
	if(!defined('MSFW_USE_FORM')) {
		define('MSFW_USE_FORM','once');
	}
	if(!defined('MSFW_LONG_FORM')) {
		define('MSFW_LONG_FORM',__('[Not Safe for Work. Click to View.]'));
	}
	if(!defined('MSFW_SHORT_FORM')) {
		define('MSFW_SHORT_FORM',__('[NSFW]'));
	}
	if(!defined('MSFW_DELETED')) {
		define('MSFW_DELETED',__('[redacted]'));
	}
	if(!defined('MSFW_PAD_STR')) {
		define('MSFW_PAD_STR',__('&nbsp; '));
	}
	
	// get shortcode arguments
	extract( shortcode_atts( array(
      'type' => MSFW_DEFAULT_TYPE,
      'pad' => MSFW_PADDING,
      ), $atts ) );
	 
	
	// set default type if something invalid was passed in
	$valid_types = array('reload','deleted','spoiler','click','comment');
	if(!in_array($type,$valid_types)) {
		$type = MSFW_DEFAULT_TYPE;
	}
	
	// get original content and its length
	$original_content = do_shortcode($content);
	$original_content_length = strlen(strip_tags($original_content));
	$censored = '';
	
	// check if we should show the full uncensored content
	if( (isset($_GET['nsfw']) && $_GET['nsfw'] == 'true' && $type == 'reload') || !$content) {
		return $original_content;
	}
	
	
	if($type == 'deleted') {
		$censored = MSFW_DELETED;
		if($pad) {
			$censored = str_pad($censored, $original_content_length, '*', STR_PAD_BOTH);
			$censored = str_replace('*',MSFW_PAD_STR,$censored);
		}
	}
	else if($type == 'comment') {
		$safe = htmlentities($original_content, ENT_NOQUOTES);
		$censored = "<!--- {$safe} -->";
	}
	else if($type=='spoiler') {
		$censored = "<span class='msfw-spoiler-outer'><span class='msfw-spoiler-inner'>{$original_content}</span></span>";
	}
	else if($type == "reload" || $type == "click") {
		$type = 'reload';
		if( ($msfw_total_count == 0 && MSFW_USE_FORM == 'once') || MSFW_USE_FORM == 'long') { 
			$censored = MSFW_LONG_FORM;
		}
		else {
			$censored = MSFW_SHORT_FORM;
		}
		if($pad) {
			$censored = str_pad($censored, $original_content_length, '*', STR_PAD_BOTH);
			$censored = str_replace('*',MSFW_PAD_STR,$censored);
		}
		$url = "?nsfw=true";
		foreach($_GET as $key=>$value) {
			$url .= "&" . urlencode($key) . '=' . urlencode($value);
			$url = htmlentities($url, ENT_QUOTES);
		}
		$censored = "<a href='{$url}'>{$censored}</a>";
	}
	
	$censored = "<span class='msfw msfw-{$type}'>{$censored}</span>";
	$msfw_total_count++;
	return $censored;
}

function msfw_init() {
	global $msfw_shortcodes;
	if(!isset($msfw_shortcodes)) {
		// is_plugin_active only works if we include plugin.php, and even then
		// only works if we know its exact directory, just see if the NSFW
		// plugin class exists
		if(class_exists('NotSafeForWork')) {
			$msfw_shortcodes = array('msfw');
		}
		else {
			$msfw_shortcodes = array('nsfw','msfw');
		}
	}
	
	foreach($msfw_shortcodes as $sc) {
		add_shortcode($sc,'msfw');
	}
	wp_register_style('msfw-stylesheet', plugins_url('styles.css', __FILE__));
	wp_enqueue_style( 'msfw-stylesheet');
	
	load_plugin_textdomain('msfwplugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action('init','msfw_init', 50);
