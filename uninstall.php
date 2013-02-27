<?php

if(!defined('WP_UNINSTALL_PLUGIN')) exit();
define('CONVERSAIT_PATH', plugin_dir_path(__FILE__));
include(CONVERSAIT_PATH . 'options.php');
delete_option($conv_opt_name_site_name);
delete_option($conv_opt_name_enabled);
delete_option($conv_opt_name_enabled_date);
delete_option($conv_opt_name_sso_logo);

?>