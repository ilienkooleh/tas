$('document').ready(function () {
    var buttonStatus = 1;
    function sendRequest(){
        if (buttonStatus) {
            $.ajax({
                url: 'index.php?page=userInfo',
                dataType: "json",
                success: function (data) {
                    var result = $('#result');
                    var proxy = 'Вы не используете proxy <br>';
                    result.append('Ваш IP:' + data.ip + '<br>');
                    result.append('Страна:' + data.geo + '<br>');
                    if (data.proxy) {
                        proxy = 'Вы используете proxy <br>';
                    }
                    result.append(proxy);
                    $('#button').addClass('disabled');
                    buttonStatus = 0
                }
            });
        }
    }
    $('#button').on('click', sendRequest);
});


