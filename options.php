<?php

  $conv_site_name_default = '';
  $conv_sso_logo_default = admin_url('/images/logo.gif');
  $conv_sso_key_default = ''; //SSO KEY
  $conv_opt_name_enabledfor_default = array(
    'post' => "1",
    'page' => "1"
  );
  $conv_opt_name_activation_date_default = time(); //activation
  $conv_opt_name_activation_type_default = 'all';

  $conv_opt_name_site_name = 'conversait_site_name';
  $conv_opt_name_enabled = 'conversait_enabled';
  $conv_opt_name_enabled_date = 'conversait_enabled_date';
  $conv_opt_name_sso_logo = 'conversait_sso_logo';
  $conv_opt_name_sso_key = 'conversait_sso_key'; //SSO KEY
  $conv_opt_name_enabledfor = 'conversait_post_type';
  $conv_opt_name = 'conversait_options';
  $conv_opt_name_activation_type = 'conversait_activation_type'; //activation
  $conv_opt_name_activation_date = 'conversait_activation_date'; //activation
  $conv_opt_name_demo_site = 'conversait_demo_site';
  $conv_opt_name_demo_sso = 'conversait_demo_sso';

  function conv_ensure_options() {
    global $conv_opt_name_site_name, $conv_site_name_default,
      $conv_opt_name_enabled_date, $conv_opt_name_sso_logo, $conv_sso_logo_default,
      $conv_opt_name_sso_key, $conv_sso_key_default, //SSO KEY
      $conv_opt_name_enabledfor_default, $conv_opt_name_enabledfor,
      $conv_opt_name_activation_type, $conv_opt_name_activation_type_default, //activation
      $conv_opt_name_activation_date, $conv_opt_name_activation_date_default, //activation
      $conv_opt_name, $conv_opt_name_demo_site, $conv_opt_name_demo_sso;

    // migrate to array options in v0.3
    if (is_null(get_option($conv_opt_name_site_name))) {
      $options = array(
        $conv_opt_name_site_name => $conv_site_name_default,
        $conv_opt_name_sso_logo => $conv_sso_logo_default,
        $conv_opt_name_sso_key => $conv_sso_key_default, //SSO KEY
        $conv_opt_name_enabledfor => $conv_opt_name_enabledfor_default,
        $conv_opt_name_activation_type => $conv_opt_name_activation_type_default, //activation
        $conv_opt_name_activation_date => $conv_opt_name_activation_date_default, //activation
        $conv_opt_name_demo_site => $conv_site_name_default,
        $conv_opt_name_demo_sso => $conv_sso_key_default
      );
    }
    else
    {
      $options = array(
        $conv_opt_name_site_name => get_option($conv_opt_name_site_name),
        $conv_opt_name_sso_logo => get_option($conv_opt_name_sso_logo),
        $conv_opt_name_sso_key => get_option($conv_opt_name_sso_key), //SSO KEY
        $conv_opt_name_enabledfor => $conv_opt_name_enabledfor_default,
        $conv_opt_name_activation_type => "since", //activation
        $conv_opt_name_activation_date => get_option($conv_opt_name_enabled_date) //activation
      );
    }
    add_option($conv_opt_name, $options);
    return get_option($conv_opt_name);
  }

  function conv_remove_options() {
    global $conv_opt_name_site_name, $conv_opt_name_enabled,
      $conv_opt_name_enabled_date, $conv_opt_name_sso_logo,
      $conv_opt_name_sso_key,
      $conv_opt_name;

    delete_option($conv_opt_name);

    //delete options for v < 0.2
    delete_option($conv_opt_name_site_name);
    delete_option($conv_opt_name_enabled);
    delete_option($conv_opt_name_enabled_date);
    delete_option($conv_opt_name_sso_logo);
  }

  define('CONVERSAIT_SERVER_HOST', 'http://www.theburn-zone.com');
  define('CONVERSAIT_DOMAIN', 'theburn-zone.com');
  define('CONVERSAIT_LOGIN_ROOT', 'http://www.theburn-zone.com/auth');
  define('CONVERSAIT_CDN_ROOT', 'http://cdn.theburn-zone.com/web');
  define('CONVERSAIT_DOMAIN_PORT', 'theburn-zone.com');

  if (file_exists(CONVERSAIT_PATH . 'site.php')) {
    include(CONVERSAIT_PATH . 'site.php');
  }

?>
