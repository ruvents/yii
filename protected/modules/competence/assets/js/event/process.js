$(function () {
    var $questions = $('.interview .question');
    var $progress  = $('div.interview-progress .progress');

    $questions.find('input,select,textarea').change(function () {
       fillProgressBar();
    });

    function fillProgressBar() {
        var count = 0,
            answered = 0;

        $questions.each(function () {
            var isAnswered = false;
            if ($(this).find('input[type="checkbox"]:checked, input[type="radio"]:checked').size() > 0) {
                isAnswered = true;
            } else if ($(this).find('select').size() > 0) {
                $(this).find('select').each(function () {
                    var $option = $(this).find('option:selected');
                    if ($option.val() != "" && $option.val() != 0) {
                        isAnswered = true;
                        return false;
                    }
                });
            } else if ($(this).find('textarea').size() > 0 && $(this).find('textarea').val() != "") {
                isAnswered = true;
            }

            if (isAnswered) {
                answered++;
            }
            count++;
        });

        $progress.find('.bar').width((answered / count * 100) + '%').find('span').text(answered + ' из ' + count);
        if (answered == count) {
            $progress.addClass('progress-success')
        } else {
            $progress.removeClass('progress-success');
        }
    };


    fillProgressBar();
});