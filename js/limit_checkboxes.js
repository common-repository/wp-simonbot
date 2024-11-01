jQuery(document).ready(function($){
  $("#monitor_urls :checkbox").click(function() {
    if($("#monitor_urls :checkbox:checked").length >= $('#max_urls').val()) {
      $("#monitor_urls :checkbox:not(:checked)").attr("disabled", "disabled");
    }else{
      $("#monitor_urls :checkbox").removeAttr("disabled");
    }
  });
});
