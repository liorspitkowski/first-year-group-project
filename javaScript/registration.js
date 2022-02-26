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
            if (data == "0") {
                alert('user already exists\nplease go to login page');
            }
            else if ( data == "1") {
                alert('welcome back to Foogle');
            }
            else {
                alert('server response invalid value: ' + data);
            }
        }
    });
    // prevent page reload, dunno the reason
    return false;
}