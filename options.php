<?php
  
  $conv_site_name_default = '';
  $conv_site_enabled_default = '1';
  $conv_sso_logo_default = admin_url('/images/logo.gif');

  $conv_opt_name_site_name = 'conversait_site_name';
  $conv_opt_name_enabled = 'conversait_enabled';
  $conv_opt_name_enabled_date = 'conversait_enabled_date';
  $conv_opt_name_sso_logo = 'conversait_sso_logo';

  function conv_ensure_options() {
    global $conv_opt_name_site_name, $conv_site_name_default, 
      $conv_opt_name_enabled, $conv_site_enabled_default,
      $conv_opt_name_enabled_date,
      $conv_opt_name_sso_logo, $conv_sso_logo_default;
      
    add_option($conv_opt_name_site_name, $conv_site_name_default);
    add_option($conv_opt_name_enabled, $conv_site_enabled_default);
    $activation_date = '';
    if ($conv_site_enabled_default === '1')
      $activation_date = time();
    add_option($conv_opt_name_enabled_date, $activation_date);
    add_option($conv_opt_name_sso_logo, $conv_sso_logo_default);
  }

  define('CONVERSAIT_SERVER_HOST', 'http://commenting.theburn-zone.com');
  define('CONVERSAIT_DOMAIN', 'theburn-zone.com');
  define('CONVERSAIT_LOGIN_ROOT', 'http://theburn-zone.com/auth');

  if (file_exists(CONVERSAIT_PATH . 'site.php')) {
    include(CONVERSAIT_PATH . 'site.php');
  }

?>
