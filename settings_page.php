<?php

// create custom plugin settings menu
add_action('admin_menu', 'create_menu');

//call register settings function
add_action('admin_init', 'conv_register_settings');

function create_menu() {
  //create new top-level menu
  add_options_page('Burnzone Conversait Plugin Settings', 'Burnzone Settings', 'administrator', 'conversait', 'conv_settings_page');
  add_options_page('Conversait Moderator', 'Burnzone Moderator', 'administrator', 'conversait_mod', 'conv_mod_page');
}

function conv_register_settings() {
  global $conv_opt_name_enabled, $conv_opt_name_site_name, $conv_opt_name_sso_logo;
  //register our settings
  register_setting('conv_settings_group', $conv_opt_name_enabled, 'conv_validate_enabled');
  register_setting('conv_settings_group', $conv_opt_name_site_name, 'conv_validate_site_name');
  register_setting('conv_settings_group', $conv_opt_name_sso_logo);
  
  add_settings_section('conv_settings_main', 'Main settings', 'conv_settings_main_title', 'conversait');
  add_settings_field($conv_opt_name_enabled, 'Enabled', 'conv_render_setting_enabled', 'conversait', 'conv_settings_main');
  add_settings_field($conv_opt_name_site_name, 'Site Name', 'conv_render_setting_site_name', 'conversait', 'conv_settings_main');
  
  add_settings_section('conv_settings_sso', 'Single Sign On', 'conv_settings_sso_title', 'conversait');
  add_settings_field($conv_opt_name_sso_logo, 'Logo', 'conv_render_setting_sso_logo', 'conversait', 'conv_settings_sso');
}

function conv_settings_main_title() {
  echo '<p>The main settings of BurnZone Commenting</p>';
}

function conv_render_setting_enabled() {
  global $conv_opt_name_enabled;
  $enabled = get_option($conv_opt_name_enabled);
  $checked = "";
  if($enabled === "1") {
    $checked = "checked=\"true\"";
  }
  echo "<input type=\"checkbox\" id=\"$conv_opt_name_enabled\" $checked name=\"$conv_opt_name_enabled\" value=\"1\" />";
}

function conv_validate_enabled($enabled) {
  global $conv_opt_name_enabled_date, $conv_opt_name_enabled;
  if (isset($enabled) and $enabled !== "1")
    $enabled = "1";
  $prevEnabled = get_option($conv_opt_name_enabled);
  if ($enabled === "1" and $prevEnabled !== "1")
    update_option($conv_opt_name_enabled_date, time());
  return $enabled;
}

function conv_render_setting_site_name() {
  global $conv_opt_name_site_name;
  $site_name = get_option($conv_opt_name_site_name);
  echo "<input type=\"text\" id=\"$conv_opt_name_site_name\" name=\"$conv_opt_name_site_name\" value=\"$site_name\" /><div>Only alphanumeric characters ([a-z0-9])</div>";
}

function conv_validate_site_name($site_name) {
  $site_name = trim($site_name);
  if(!preg_match('/^[a-z0-9]+$/i', $site_name))
    $site_name = "";
  return strtolower($site_name);
}

function conv_settings_sso_title() {
  echo '<p>Settings related to Single Sign On</p>';
}

function conv_render_setting_sso_logo() {
  global $conv_opt_name_sso_logo;
  $sso_logo = get_option($conv_opt_name_sso_logo);
  echo "<input type=\"text\" id=\"$conv_opt_name_sso_logo\" name=\"$conv_opt_name_sso_logo\" value=\"$sso_logo\" /><div>The image that you want to be visible in the login panel of Conversait</div>";
}

function conv_settings_page() {
?>

<div class="wrap">
<h2>Conversait</h2>

<form method="post" action="options.php">
  <?php settings_fields('conv_settings_group'); ?>
  <?php do_settings_sections('conversait'); ?>
  
  <?php submit_button(); ?>

</form>
</div>

<?php }

function conv_mod_page() {
  global $conv_opt_name_site_name;
  $site_name = get_option($conv_opt_name_site_name);
?>

<div class="wrap">
<h2>Burnzone Moderator</h2>
<iframe src="<?php echo CONVERSAIT_LOGIN_ROOT . "/signin?redirect=" . urlencode("/admin/moderator?embed=true&site=" . $site_name); ?>" style="width:100%; min-height:650px;"></iframe>
</div>
<?php } ?>