<textarea id="log">

</textarea>
<button id="send">Send</button>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    $('#send').click(function () {
        $.post('http://api.mclogs.dev/1/log', {content: $('#log').val()}, function(data){
            location.href = "/" + data.id;
        });
    });
</script>