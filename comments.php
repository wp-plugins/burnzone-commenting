<?php
  
  global $conv_opt_name_site_name, $conv_opt_name_forum_url, $conv_opt;
  global $post;

  $conv_area_tag = "";
  $post_link = get_permalink($post->ID);
  $forum_url = $conv_opt[$conv_opt_name_forum_url];
  if (isset($forum_url) && $forum_url !== '' && $post_link == $forum_url) {
    $conv_area_tag = '';
  }
  else {
    $conv_area_tag = '<div id="conversait_area" class="conversait_area" data-conversait-app-type="article"></div>';
  }
  $conv_embed_data = '
<script type="text/javascript">
  var conversait_id = "' . conv_unique_post_id($post->ID) . '";
  var conversait_uri = "' . $post_link . '";
  var conversait_title = "' . get_the_title($post->ID) . '";
</script>
';
?>
<div id="comments">
  <div id="respond" style="background:none; width: auto; border: none">
    <?php if (ssoEnabled()) { ?>
      <script type="text/javascript">
        var conversait_sso = <?php echo '"' . conv_build_sso_string() . '"'; ?>;
        var conversait_sso_options = <?php echo conv_build_sso_options(); ?>;
      </script>
    <?php }
      echo $conv_area_tag . $conv_embed_data;
    ?>
  </div>
</div>