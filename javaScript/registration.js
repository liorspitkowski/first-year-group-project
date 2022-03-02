function submitReg() {
    alert('function called');
    var url = "../PHP/register.php", data = $('#registration_form').serialize();
    console.log(data);
    $.ajax({
        // prevent page reload, dunno the reason
        async: false,
        url: url,
        type: 'POST',
        data: data,
        /*
            return:
            if user already exists: 0
            if register successful: 1
        */
        success: function (data) {
            //alert(data);  show response from the php script.
            let flag = getValue("flag", data);
            let userid = getValue("username", data);
            alert(data);
            alert("flag= "+flag+" userid= "+userid);
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
    // prevent page reload, dunno the reason
    return false;
}