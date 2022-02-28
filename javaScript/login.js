function submitLogin() {
    var url = "../PHP/login.php", data = $('#login_form').serialize();
    const formData = new FormData(document.querySelector('#login_form'))
    alert(data);
    $.ajax({
        // prevent page reload, dunno the reason
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            //alert(data);  show response from the php script.
            if (data == "0" | data == "1") {
                alert('username or password incorrect, \nplease check again');
            }
            else if (data[0] == "2") {
                alert('welcome back to Foogle' + data[1]);
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                    if(pair[0] == 'user_name'){
                        document.cookie = 'username=' + pair[1] + '; expires=18 Dec 2025 12:00:00 UTC;path=/';
                    }
                }
                document.cookie = 'userid=' + data[1] + '; expires=18 Dec 2025 12:00:00 UTC;path=/';
                window.location.pathname('./menu.html');
            }
            else {
                alert('server response invalid value: ' + data);
            }
        }
    });
    // prevent page reload, dunno the reason
    return false;
}