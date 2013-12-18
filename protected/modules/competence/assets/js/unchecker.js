$(function(){
  $('input[type="checkbox"][data-unchecker]').bind('change', function(event){
    var target = $(event.currentTarget);
    var unchecker = target.data('unchecker');
    var group = target.data('group');

    if (target.prop('checked'))
    {
      var checkboxes = $('input[type="checkbox"][data-unchecker!='+unchecker+'][data-group='+group+']');
      checkboxes.prop('checked', false);
      checkboxes.trigger('change');
    }
  });

  $('input[type="checkbox"][data-target]').change(function (e) {
    var target = $(e.currentTarget);
    var dataTarget = target.data('target');
    $(dataTarget).prop('disabled', !target.prop('checked'));
  });


  $('input[type="radio"][data-group]').change(function (e) {
    var target = $(e.currentTarget);
    var group = target.data('group');
    $('input[type="text"][data-group="'+group+'"]').attr('disabled', 'disabled');
    if (typeof target.data('target') != "undefined") {
      $(target.data('target')).removeAttr('disabled');
    }
  });

  $('input[type="text"][data-group]').attr('disabled', 'disabled');
  $('input[type="radio"][data-group]:checked').trigger('change');
  $('input[type="checkbox"][data-target]:checked').trigger('change');
  $('input[type="checkbox"][data-unchecker]:checked').trigger('change');
});