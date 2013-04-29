<?php

  function buildSSOString() {
    global $current_user;
    get_currentuserinfo();
    if ($current_user->ID) {
      $data = array(
        'id' => $current_user->ID,
        'name' => $current_user->display_name,
        'email' => $current_user->user_email
      );
    }
    else {
      $data = array();
    }
    $message = base64_encode(json_encode($data));
    $timestamp = time();
    $hmac = hash_hmac('sha1', "$message $timestamp", CONVERSAIT_SITE_SECRET);
    return "$message $hmac $timestamp";
  }

  function buildSSOOptions() {
    global $conv_opt_name_sso_logo;
    $data = array(
      'logo' => get_option($conv_opt_name_sso_logo),
      'loginUrl' => wp_login_url(CONVERSAIT_LOGIN_ROOT . '/popup_auth_ok.html'),
      'logoutUrl' => wp_logout_url(get_permalink())
    );
    return json_encode($data);
  }

  function ssoEnabled() {
    return defined('CONVERSAIT_SITE_SECRET');
  }

?>
