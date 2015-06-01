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
        if (!target.prop('checked')) {
            $(dataTarget).val('');
        }
    });

    $("input:radio").each(function() {
        $(this).data('state-checked', $(this).is(':checked'));
    }).click(function(e) {
        var $target = $(e.currentTarget);
        if ($target.data('state-checked')) {
            $target.prop('checked', false).data('state-checked', false);
        } else {
            $('[data-group="'+ $target.data('group') +'"]:radio').data('state-checked', false);
            $target.prop('checked', true).data('state-checked', true);
        }
        $target.trigger('change');
        return;
    });

    $('input[type="radio"][data-group]').change(function (e) {
        var target = $(e.currentTarget);
        var group = target.data('group');
        var otherInputs = $('input[type="text"][data-group="'+group+'"]');
        otherInputs.prop('disabled', true);

        var otherTarget = target.data('target');
        if (typeof otherTarget != "undefined" && target.is(':checked')) {
            $(target.data('target')).prop('disabled', false);
            var id = otherTarget.substring(1);
            otherInputs.each(function(index, element) {
                element = $(element);
                if (element.attr('id') != id) {
                    element.val('');
                }
            });
        } else {
            otherInputs.val('');
        }
    });

    $('input[type="text"][data-group]').attr('disabled', 'disabled');
    $('input[type="radio"][data-group]:checked').trigger('change');
    $('input[type="checkbox"][data-target]:checked').trigger('change');
    $('input[type="checkbox"][data-unchecker]:checked').trigger('change');
});