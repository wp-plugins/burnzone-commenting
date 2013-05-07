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
  global $conv_opt_name_site_name, $conv_opt_name_sso_logo, $conv_opt_name_enabledfor, $conv_opt_name;
  //register our settings
  register_setting('conv_settings_group', $conv_opt_name, 'conv_validate_settings');
  
  add_settings_section('conv_settings_main', 'Main settings', 'conv_settings_main_title', 'conversait');
  add_settings_field($conv_opt_name_activation, 'Activated for', 'conv_render_setting_activation', 'conversait', 'conv_settings_main');
  add_settings_field($conv_opt_name_site_name, 'Site Name', 'conv_render_setting_site_name', 'conversait', 'conv_settings_main');
  add_settings_field($conv_opt_name_enabledfor, 'Enable options', 'conv_render_settings_enabledfor', 'conversait' , 'conv_settings_main');

  add_settings_section('conv_settings_sso', 'Single Sign On', 'conv_settings_sso_title', 'conversait');
  add_settings_field($conv_opt_name_sso_logo, 'Logo', 'conv_render_setting_sso_logo', 'conversait', 'conv_settings_sso');

}

/*
* Enabling the commenting platform based on post type.
*/

function conv_render_settings_enabledfor() { 
  global $conv_opt, $conv_opt_name_enabledfor, $conv_opt_name;
  $posttypes = get_post_types();
  foreach ($posttypes as $key => $value) { 
    $checked = "";
    if ($conv_opt[$conv_opt_name_enabledfor][$key] === "1")
      $checked = 'checked="true"';
  ?>
    <div>
      <input type="checkbox" name="<?php echo $conv_opt_name . "[$conv_opt_name_enabledfor]" ?>[]" value="<?php echo $key ?>" <?php echo $checked ?> id="conv_opt_<?php echo $key ?>"/>
      <label for="conv_opt_<?php echo $key ?>"><?php echo $value ?></label>
    </div>
  <?php 
  } ?>
  <div>Type of posts where you want the commenting platform to be activated</div>
  <?php
}

function conv_settings_main_title() {
  echo '<p>The main settings of BurnZone Commenting</p>';
}

/*
* Loading the timepicker addon dependencies. 
* http://trentrichardson.com/examples/timepicker/
*/

function conv_load_scripts_styles(){
  wp_register_style('jquery-ui-timepicker', plugin_dir_url(__FILE__ ) . 'assets/css/jquery-ui-timepicker-addon.css');
  wp_register_style('jquery-ui-smoothness', plugin_dir_url(__FILE__ ) . 'assets/css/jquery-ui-smoothness/jquery-ui-1.10.2.custom.min.css');
  wp_register_script('jquery-ui-timepicker', plugin_dir_url(__FILE__ ) . 'assets/js/jquery-ui-timepicker-addon.js');
  wp_register_script('jquery-ui-slider_access', plugin_dir_url(__FILE__ ) . 'assets/js/jquery-ui-sliderAccess.js');
  wp_register_script('conv-admin-scripts', plugin_dir_url(__FILE__ ) . 'assets/js/admin_scripts.js');
  wp_deregister_script('jquery-ui-core');
  wp_deregister_script('jquery-ui-datepicker');
  wp_register_script('jquery-ui-core', plugin_dir_url(__FILE__ ) . 'assets/js/jquery-ui-1.10.2.custom.js', array('jquery'));
  wp_enqueue_style('jquery-ui-timepicker');
  wp_enqueue_style('jquery-ui-smoothness');
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-timepicker');
  // wp_enqueue_script('jquery-ui-slider_access');
  wp_enqueue_script('conv-admin-scripts');
}

add_action('admin_enqueue_scripts', 'conv_load_scripts_styles');

function conv_acttype_checked($forvalue) {
  global $conv_opt_name_activation_type, $conv_opt;
  if ($conv_opt[$conv_opt_name_activation_type] === $forvalue)
    return 'checked="true"';
  return "";
}

function conv_render_setting_activation() {
  global $conv_opt_name_activation_type, $conv_opt_name_activation_date, $conv_opt, $conv_opt_name;
  $activation_type = $conv_opt[$conv_opt_name_activation_type];
  $activation_date = date("Y-m-d h:i A", $conv_opt[$conv_opt_name_activation_date]);
  $typeRadio='type="radio"';

  echo "
    <form>
    <input $typeRadio name=\"" . $conv_opt_name . "[$conv_opt_name_activation_type] \" value=\"all\" " . conv_acttype_checked('all') . " /> All posts<br/>
    <input $typeRadio name=\"" . $conv_opt_name . "[$conv_opt_name_activation_type] \" value=\"wpcomments_closed\" " . conv_acttype_checked('wpcomments_closed') . " /> Posts with closed comments<br/>
    <input $typeRadio name=\"" . $conv_opt_name . "[$conv_opt_name_activation_type] \" value=\"since\" " . conv_acttype_checked('since') . " />
      Posts published since: 
      <input type=\"text\" id=\"$conv_opt_name_activation_date\" name=\"" . $conv_opt_name . "[$conv_opt_name_activation_date]\" value=\"$activation_date\" />
    </form>
  ";
}

function conv_render_setting_site_name() {
  global $conv_opt_name_site_name, $conv_opt, $conv_opt_name;
  $site_name = $conv_opt[$conv_opt_name_site_name];
  echo "<input type=\"text\" id=\"$conv_opt_name_site_name\" name=\"" . $conv_opt_name . "[$conv_opt_name_site_name]\" value=\"$site_name\" /><div>Only alphanumeric characters ([a-z0-9])</div>";
}

function conv_settings_sso_title() {
  echo '<p>Settings related to Single Sign On</p>';
}

function conv_render_setting_sso_logo() {
  global $conv_opt_name_sso_logo, $conv_opt, $conv_opt_name;
  $sso_logo = $conv_opt[$conv_opt_name_sso_logo];
  echo "<input type=\"text\" id=\"$conv_opt_name_sso_logo\" name=\"" . $conv_opt_name . "[$conv_opt_name_sso_logo]\" value=\"$sso_logo\" /><div>The image that you want to be visible in the login panel of Conversait</div>";
}


function conv_validate_settings($options) {
  global $conv_opt_name_site_name, $conv_opt_name_sso_logo, $conv_opt_name_enabledfor, 
   $conv_opt, $conv_opt_name_activation_type, $conv_opt_name_activation_date;

  $newOptions = array_merge(array(), (array)$conv_opt);

  /*
  * sso logo
  */
  $newOptions[$conv_opt_name_sso_logo] = $options[$conv_opt_name_sso_logo];

  /*
  * site name
  */
  $site_name = trim($options[$conv_opt_name_site_name]);
  if(!preg_match('/^[a-z0-9]+$/i', $site_name))
    $site_name = "";
  $newOptions[$conv_opt_name_site_name] = strtolower($site_name);

  /*
  * enabled for
  */
  $posttypes = get_post_types();
  $newEnabledfor = array();
  $enabledfor = $options[$conv_opt_name_enabledfor];
  for ($i=0; $i < count($enabledfor); $i++) { 
    if ($posttypes[$enabledfor[$i]])
      $newEnabledfor[$enabledfor[$i]] = "1";
  }
  $newOptions[$conv_opt_name_enabledfor] = $newEnabledfor;

  /*
  * activation type
  */
  $atype = $options[$conv_opt_name_activation_type];
  if ($atype === "all" || $atype === "since" || $atype === "wpcomments_closed") {
    $newOptions[$conv_opt_name_activation_type] = $atype;
    if ($atype === "since") {
      $activation_date = date_create_from_format("Y-m-d h:i A", $options[$conv_opt_name_activation_date]);
      if ($activation_date) {
        $activation_date = $activation_date->getTimestamp();
      }
      else
        $activation_date = time();
      $newOptions[$conv_opt_name_activation_date] = $activation_date;
    }
  }

  return $newOptions;
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
  global $conv_opt_name_site_name, $conv_opt;
  $site_name = $conv_opt[$conv_opt_name_site_name];
?>

<div class="wrap">
<h2>Burnzone Moderator</h2>
<iframe src="<?php echo CONVERSAIT_LOGIN_ROOT . "/signin?redirect=" . urlencode("/admin/moderator?embed=true&site=" . $site_name); ?>" style="width:100%; min-height:650px;"></iframe>
</div>
<?php } ?>
