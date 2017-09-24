
updateLineNumber(location.hash);

$('.line-number').click(function(){
    updateLineNumber('#'+$(this).attr('id'));
});

function updateLineNumber(id) {
    var element = $(id);
    if(element.length === 1 && element.hasClass("line-number")) {
        $('.line-active').removeClass('line-active');
        element.addClass('line-active');
    }
}

$('.show-suggestions').click(function(){
    $('.suggestions').slideToggle(100, function(){
        if($('.suggestions').is(':visible')) {
            $('#suggestion-toggle-text').text("Hide suggestions");
        } else {
            $('#suggestion-toggle-text').text("Show suggestions");
        }
    });
});