/*
    Written by: Hanmin Liu;
    send registration form, expect return value -0: exist user -1: success and redirect to login;
*/
function submitReg() {
    // alert('function called');
    var url = "../PHP/register.php", data = $('#registration_form').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            let flag = getValue("flag", data);
            let userid = getValue("username", data);
            // alert(data);
            // alert("flag= "+flag+" userid= "+userid);
            if (flag == "0") {
                alert('user already exists\nplease go to login page');
            }
            else if ( flag == "1") {
                alert('Registered successfully!\nJumping to login page...');
                location.href = '../html/login.html';
            }
            else {
                alert('server response invalid value: ' + data);
            }
            /*
                returning warning message at current version:
                line 2, 3: undefined array key ""
            */
        }
    });
    return false;
}