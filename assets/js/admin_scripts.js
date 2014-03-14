jQuery(document).ready(function(){
  jQuery('#conversait_activation_date').datetimepicker({
    buttonImageOnly: true,
    dateFormat: "yy-mm-dd",
    timeFormat: "hh:mm TT",
    controlType: "slider",
    minDateTime: new Date(0)
  });
  conv_setup_exports();
});

conv_setup_exports = function() {
  var $ = jQuery;
  $('#conv-export-comments a.button').unbind().click(function() {
    $('#conv-export-comments a.button').addClass('display_none');
    $('#conv-export-status').addClass("export_loading").removeClass('export_finished').html('<i class="fa fa-spinner fa-spin"></i> Exporting...');
    do_export_comments();
    return false;
  });
}

do_export_comments = function() {
  var $ = jQuery;
  var btn_export = $('#conv-export-comments a.button')
  var status = $('#conv-export-status');
  var export_info = (status.attr('rel') || '0|' + (new Date().getTime()/1000)).split('|');
  $.get(
    wp_index_url,
    {
      conv_action: 'export-comment',
      post_id: export_info[0],
      timestamp: export_info[1]
    },
    function(response) {
      switch (response.result) {
        case 'success':
          status.html(response.msg).attr('rel', response.post_id + '|' + response.timestamp);
          switch (response.status) {
            case 'partial':
              do_export_comments();
              break;
            case 'complete':
              btn_export.removeClass('display_none');
              status.addClass('export_finished').removeClass('export_loading').html('Export finished!');
              break;
          }
          break;
        case 'fail':
          btn_export.removeClass('display_none');
          status.addClass('export_error').removeClass('export_loading').html('There was an error exporting the comments: ' + response.msg + '.')
          break;
      }
    },
    'json'
  );
}

