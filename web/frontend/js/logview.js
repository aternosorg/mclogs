
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