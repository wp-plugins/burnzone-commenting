jQuery(document).ready(function(){
  jQuery('#conversait_activation_date').datetimepicker({
    buttonImageOnly: true,
    dateFormat: "yy-mm-dd",
    timeFormat: "hh:mm TT",
    controlType: "slider",
    minDateTime: new Date(0)
  });
});
