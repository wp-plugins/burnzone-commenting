<?php
  
  global $conv_opt_name_site_name, $conv_opt;
  global $post;

  $conv_embed_script = '<div id="conversait_area"></div>
<script type="text/javascript">
  var conversait_sitename = "' . $conv_opt[$conv_opt_name_site_name] . '";
  var conversait_id = "' . $post->ID . '";
  (function() {
    var conversait = document.createElement("script"); 
    conversait.type = "text/javascript"; 
    conversait.async = true;
    conversait.src = "' . CONVERSAIT_SERVER_HOST . '/web/js/embed.js";
    (document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(conversait);
  })();
</script>
';
?>
<div id="comments">
  <div id="respond" style="background:none; width: auto; border: none">
    <?php if (ssoEnabled()) { ?>
      <script type="text/javascript">
        var conversait_sso = <?php echo '"' . buildSSOString() . '"'; ?>;
        var conversait_sso_options = <?php echo buildSSOOptions(); ?>;
      </script>
    <?php }
      echo $conv_embed_script;
    ?>
  </div>
</div>