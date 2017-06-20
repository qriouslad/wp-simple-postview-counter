<?php
/*
Plugin Name: WP Simple Post View Counter
Plugin URI: https://github.com/qriouslad/wp-simple-postview-counter
Description: Simple post view counter based on tutorial at https://www.smashingmagazine.com/2011/09/how-to-create-a-wordpress-plugin/
Version: 1.0
Author: Bowo
Author URI: https://bowo.io
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Simple Post View Counter is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP Simple Post View Counter is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WP Simple JQuery Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html
*/

function wpspvc_activation() {

}
register_activation_hook(__FILE__, 'wpspvc_activation');

function wpspvc_deactivation() {

}
register_deactivation_hook(__FILE__, 'wpspvc_deactivation');

function wpspvc_uninstall() {

}
register_uninstall_hook(__FILE__, 'wpspvc_uninstall');


/**
 * Adds a view to the post being viewed
 *
 * Finds the current views of a post and adds 1 to it by updating
 * the post meta. The meta key used is "wpspvc_views".
 *
 * @global object $post The post object
 * @return integer $new_views The number of views the post has
 *
 */

function wpspvc_add_view() {

	if (is_single()) {
		global $post;
		$current_views = get_post_meta($post->ID, "wpspvc_views", true);
		if ( !isset($current_views) OR empty($current_views) OR (!is_numeric($current_views)) ) {
			$current_views = 0;
		}
		$new_views = $current_views + 1;
		update_post_meta($post->ID, "wpspvc_views", $new_views);
		return $new_views;
	}

}
add_action('wp_head', 'wpspvc_add_view');


/**
 * Retrieve the number of views for a post
 *
 * Finds the current views for a post, returning 0 if there are none
 *
 * @global object $post The post object
 * @return integer $current_views The number of views the post has
 *
 */
function wpspvc_get_view_count() {
	global $post;
	$current_views = get_post_meta($post->ID, "wpspvc_views", true);
	if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views)) {
		$current_views = 0;
	}

	return $current_views;
}


/**
 * Shows the number of views for a post
 *
 * Finds the current views of a post and displays it together with some optional text
 * @global object $post The post object
 * @uses wpspvc_get_view_count()
 * @see http://wpdevelopers.com/adding-content-before-and-after-the_content/
 *
 * @param string $singular The singular term for the text
 * @param string $plural The plural term for the text
 * @param string $before Text to place before the counter
 * 
 * @return string $views_text The views display
 */
function wpspvc_show_views($content) {
	global $post;
	$current_views = wpspvc_get_view_count();

	$singular = "view";
	$plural = "views";
	$before = "This post has: ";

	$views_text = '<p><strong>' . $before . $current_views . " ";

	if ($current_views == 1) {
		$views_text .= $singular . '</strong></p>';
	} else {
		$views_text .= $plural . '</strong></p>';
	}

	$content .= $content . $views_text;

	return $content;

}
add_filter('the_content', 'wpspvc_show_views');