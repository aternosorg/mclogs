updateLineNumber(location.hash);

$('.line-number').click(function () {
    updateLineNumber('#' + $(this).attr('id'));
});

$('.down-button').click(function () {
    $("html, body").scrollTop($(document).height());
});

$('.up-button').click(function () {
    $("html, body").scrollTop(0);
});

function updateLineNumber(id) {
    var element = $(id);
    if (element.length === 1 && element.hasClass("line-number")) {
        $('.line-active').removeClass('line-active');
        element.addClass('line-active');
    }
}

var errorsToggled = false;
$('.error-toggle').click(function () {
    $(this).removeClass("btn-red").addClass("btn-black");
    var firstNoErrorLine = false;
    if (!errorsToggled) {
        errorsToggled = true;
        var totalLines = $('.log tr').length;
        $('.log tr').each(function (i, line) {
            var lineNumber = $(line).find(".line-number").text();
            if ($(line).hasClass("entry-no-error")) {
                $(line).hide();

                if (firstNoErrorLine === false) {
                    firstNoErrorLine = lineNumber;
                }

                if (i + 1 === totalLines && firstNoErrorLine) {
                    generateCollapsedLines(firstNoErrorLine, lineNumber).insertAfter(line);
                }
            } else {
                if (firstNoErrorLine) {
                    generateCollapsedLines(firstNoErrorLine, lineNumber - 1).insertBefore(line);
                    firstNoErrorLine = false;
                }
            }
        });
    } else {
        errorsToggled = false;
        $(this).removeClass("btn-black").addClass("btn-red");
        $('.entry-no-error').show();
        $('.collapsed-lines').remove();
    }

    $('.collapsed-lines-count').click(function () {
        let positionElement = $('#L' + ($(this).data("end") + 1));
        let position;
        if (positionElement.length > 0) {
            position = positionElement.position().top - $(window).scrollTop();
        }
        for (var i = $(this).data("start"); i <= $(this).data("end"); i++) {
            $('#L' + i).parent().parent().show();
        }
        if (positionElement.length > 0) {
            $(window).scrollTop(positionElement.position().top - position - $(this).outerHeight());
        }
        $(this).remove();
    });
});

function generateCollapsedLines(start, end) {
    var count = end - start + 1;
    var string = count === 1 ? "line" : "lines";
    return $('<tr class="collapsed-lines"><td></td><td class="collapsed-lines-count" data-start="' + start + '" data-end="' + end + '"><i class="fa fa-angle-up"></i> ' + count + " " + string + ' <i class="fa fa-angle-up"></i></td></tr>');
}