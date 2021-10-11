var titles = ["Paste", "Share", "Analyse"];
var currentTitle = 0;
var speed = 30;
var pause = 3000;

setTimeout(nextTitle, pause);
function nextTitle() {
    currentTitle++;
    if(typeof(titles[currentTitle]) === "undefined") {
        currentTitle = 0;
    }

    var title = $('.title-verb').text();
    for (var i = 0; i < title.length - 1; i++) {
        setTimeout(function() {
            $('.title-verb').text($('.title-verb').text().substr(0, $('.title-verb').text().length - 1));
        }, i * speed);
    }

    var newTitle = titles[currentTitle];
    for (var i = 1; i <= newTitle.length; i++) {
        setTimeout(function(){
            $('.title-verb').text(newTitle.substr(0, $('.title-verb').text().length + 1));
        }, title.length * speed + i * speed);
    }

    setTimeout(nextTitle, title.length * speed + newTitle.length * speed + pause);
}

$('#paste').focus();

$('.paste-save').click(sendLog);
$(document).keydown(function(event) {
    if (!(String.fromCharCode(event.which).toLowerCase() === 's' && event.ctrlKey) && !(event.which === 19)) return true;
    sendLog();
    event.preventDefault();
    return false;
});

function sendLog() {
    if($('#paste').val() === "") {
        return false;
    }

    $('.paste-save').addClass("btn-working");
    $.post('http'+((location.protocol === "https:") ? "s" : "")+'://api.'+location.host+'/1/log', {content: $('#paste').val()}, function(data){
        location.href = "/" + data.id;
    });
}