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
            let flag = getValue("flag", data);
            let userid = getValue("userid", data);
            alert("flag= "+flag);
            alert("userid= "+userid);
            //alert(data);  show response from the php script.
            if (flag == "0" | flag == "1") {
                alert('username or password incorrect, \nplease check again');
            }
            else if (flag == "2") {
                //flag=2;username=sjdf;
                alert('welcome back to Foogle ' + userid);
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                    if(pair[0] == 'user_name'){
                        document.cookie = 'username=' + pair[1] + '; expires=18 Dec 2025 12:00:00 UTC;path=/';
                    }
                }
                document.cookie = 'userid=' + userid + '; expires=18 Dec 2025 12:00:00 UTC;path=/';
                location.href = "../html/menu.html";
            }
            else {
                alert('server response invalid value: ' + data);
            }
        }
    });
    // prevent page reload, dunno the reason
    return false;
}