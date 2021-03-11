jQuery(document).ready(function($){

  $('.switcher').on('change', function(e) {
    var href = window.location.href;
    e.preventDefault();
    var value = $(this).val();
    //alert(value)

    $.ajax({
//      url: 'index.php?option=com_ajax&ignoreMessages&plugin=template_switch&method=tester&format=json',
      url: 'index.php?option=com_ajax&group=system&plugin=SetThemeState&format=json',

      type: 'POST',
      async: true,
      cache: false,
      data: {value, href},
      success: function(result){
        result = JSON.parse(result.data);
      }
    });

  });

  $(document).ajaxStop(function() {
    setTimeout(function() {
      location.reload();
    }, 100);
  });
});


