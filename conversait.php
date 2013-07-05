<?php

/*
Plugin Name: BurnZone Commenting Wordpress Plugin
Plugin URI: http://www.theburn-zone.com
Description: Integrates the BurnZone commenting engine
Version: 0.3.1
Author: The Burnzone team
Author URI: http://www.theburn-zone.com
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

$conv_opt = conv_ensure_options();

/*
* Function to check whether to replace the default commenting platform or not.
*/
function should_replace_comments($post) {
  global $conv_opt_name_enabledfor, 
    $conv_opt_name_activation_type, $conv_opt_name_activation_date, //activation
    $conv_opt;
  if (is_null($post))
    return false;
  $post_time = strtotime($post->post_date);
  if ($conv_opt[$conv_opt_name_enabledfor][$post->post_type] !== "1")
    return false;
  if ($conv_opt[$conv_opt_name_activation_type] === "all")
    return true;
  if ($conv_opt[$conv_opt_name_activation_type] === "wpcomments_closed")
    return ($post->comment_status === "closed");
  if ($conv_opt[$conv_opt_name_activation_type] === "since") {
    if ($post_time >= $conv_opt[$conv_opt_name_activation_date])
      return true;
  }
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

/**
* If comment_status == 'closed' then Wordpress does not call 'comments_number'. We can't display the number of comments if 
* the plugin is enabled for posts with comment_status == 'closed'. This hook overrides comments_open when we're not in admin mode.
*/
function conv_comments_open($open, $post_id = null) {
  $post = get_post($post_id);
  if (!$open && should_replace_comments($post) && !is_admin())
    $open = true;
  return $open;
}

function conv_get_comments_number($count, $post_id = null) {
  $post = get_post($post_id);
  if (should_replace_comments($post))
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
  wp_enqueue_script('convcommentscount', CONVERSAIT_SERVER_HOST . '/web/js/counts.js');
}

function conv_head() {
  global $conv_opt_name_site_name, $conv_opt;
  $site_name = $conv_opt[$conv_opt_name_site_name];
  echo '<script type="text/javascript">var conversait_sitename = "' . $site_name . '";</script>';
}

$site_name = $conv_opt[$conv_opt_name_site_name];

if (isset($site_name) and $site_name !== '') {
  add_filter('comments_template', 'conv_comments_template', 20);
  add_filter('comments_open', 'conv_comments_open', 20);
  add_filter('get_comments_number', 'conv_get_comments_number', 20);
  add_filter('comments_number', 'conv_comments_number', 20);
  add_action('wp_enqueue_scripts', 'conv_enqueue_scripts');
  add_action('wp_head', 'conv_head');
}

/**
* Add dashboard widget
*/
add_action( 'wp_dashboard_setup', 'conv_dashboard_widget' );
function conv_dashboard_widget() {
    add_meta_box(
        'conv-dashboard-widget',
        'BurnZone Commenting Widget',
        'conv_dashboard_content',
        'dashboard',
        'normal',
        'high'
    );
}

function conv_dashboard_content(){
  global $conv_opt_name_site_name, $conv_opt;
    $site_name = $conv_opt[$conv_opt_name_site_name];
  ?>

  <div class="wrap">
  <iframe src="<?php echo CONVERSAIT_LOGIN_ROOT . "/signin?redirect=" . urlencode("/admin/moderator?embed=true&site=" . $site_name); ?>" style="width:100%; min-height:500px;"></iframe>
  </div>
<?php }

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
