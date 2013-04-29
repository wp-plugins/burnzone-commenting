<?php

/*
Plugin Name: Conversait Wordpress Plugin
Plugin URI: http://theburn-zone.com
Description: Integrates the Conversait commenting engine
Version: 0.1.1
Author: The Burnzone team
Author URI: http://theburn-zone.com
License: GPL2
*/

/*  Copyright 2012  theburn-zone.com  (email : info@theburn-zone.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('CONVERSAIT_PATH', plugin_dir_path(__FILE__));

include(CONVERSAIT_PATH . 'options.php');
include(CONVERSAIT_PATH . 'sso.php');

conv_ensure_options();

function should_replace_comments($post) {
  global $conv_opt_name_enabled_date;
  $post_time = strtotime($post->post_date);
  $enabled_time = (int)get_option($conv_opt_name_enabled_date);
  if ($enabled_time < $post_time)
    return true;
  return false;
}

/**
* Embed the conversait script in the post body.
*/

function conv_comments_template($file) {
  global $post;
  if (should_replace_comments($post))
    return CONVERSAIT_PATH . 'comments.php';
  return $file;
}

function conv_get_comments_number($count) {
  global $post;
  $this_post = NULL;
  if (func_num_args() > 1)
    $this_post = get_post(func_get_arg(1));
  else
    $this_post = $post;
  if (should_replace_comments($this_post))
    return 0;
  return $count;
}

function conv_comments_number($output) {
  global $post;
  if (should_replace_comments($post))
    return '<span data-conversation-id="' . $post->ID . '" data-conversation-url="' . get_permalink($post->ID) . '"></span>';
  else
    return $output;
}

function conv_enqueue_scripts() {
  wp_enqueue_script('convcommentscount', CONVERSAIT_SERVER_HOST . '/web/javascripts/counts.js');
}

function conv_head() {
  global $conv_opt_name_site_name;
  $site_name = get_option($conv_opt_name_site_name);
  echo '<script type="text/javascript">var conversait_sitename = "' . $site_name . '";</script>';
}

$enabled = get_option($conv_opt_name_enabled);
$site_name = get_option($conv_opt_name_site_name);

if ($enabled === '1' and isset($site_name) and $site_name !== '') {
  add_filter('comments_template', 'conv_comments_template', 20);
  add_filter('get_comments_number', 'conv_get_comments_number', 20);
  add_filter('comments_number', 'conv_comments_number', 20);
  add_action('wp_enqueue_scripts', 'conv_enqueue_scripts');
  add_action('wp_head', 'conv_head');
}

/**
* Allow redirects to external sites
*/

function conv_allow_redirect($allowed)
{
  $allowed[] = CONVERSAIT_DOMAIN;
  return $allowed;
}

if (ssoEnabled()) {
  add_filter('allowed_redirect_hosts', 'conv_allow_redirect');
}

include(CONVERSAIT_PATH . 'settings_page.php');
?>
