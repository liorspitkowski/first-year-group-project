/*
    Written by: Hanmin Liu;
    send login form, expect return value -0/1: incorrect username and password -2: correct username and password;
*/
function submitLogin() {
    var url = "../PHP/login.php", data = $('#login_form').serialize();
    const formData = new FormData(document.querySelector('#login_form'))
    console.log(data);
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            let flag = getValue("flag", data);
            let userid = getValue("username", data);
            // alert(data);
            // alert("flag= "+flag+" userid= "+userid);
            if (flag == "0" | flag == "1") {
                alert('username or password incorrect, \nplease check again');
            }
            else if (flag == "2") {
                alert('welcome back to Foogle ' + userid);
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                    if (pair[0] == 'user_name') {
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
    return false;
}

/*
    Hanmin Liu;
    send a reset password request to php file;
    Send 1st: userid, email;
    receive:    flag = 0 -> wrong;
                flag = 1 -> right; code = 6 digit code;
    send if code match: userid, newPassword;
    receive:    flag = 0 -> server error;
                flag = 1 -> successful;  
*/
/* create the form and delete the button */
function setupResetPassword() {
    console.log("setupResetPassword()");
    var parentform = document.getElementById('forgetpasswordform')
    // remove the button
    //parentform.removeChild(document.getElementById('removedbutton'));
    // add form begin
    // form
    let newDiv = document.createElement("form");
    newDiv.name = "resetpw_form";
    newDiv.id = "resetpw_form";
    // when submiting the form, it should call the function 'submitResetPassword()'
    newDiv.addEventListener("submit",submitResetPassword);
    //newDiv.onsubmit = "return submitResetPassword()";

    parentform.appendChild(newDiv);
    // label1
    let label1 = document.createElement("label");
    label1.appendChild(document.createTextNode("Username: "));
    label1.for = "username";

    newDiv.appendChild(label1);
    // input1
    let input1 = document.createElement("input");
    input1.type = "text";
    input1.name = "username";
    input1.id = "username";
    input1.required;
    input1.placeholder = "your username";

    newDiv.appendChild(input1);
    // new line
    newDiv.appendChild(document.createElement("br"));
    // label2
    let label2 = document.createElement("label");
    label2.appendChild(document.createTextNode("Email: "));
    label2.for = "username";

    newDiv.appendChild(label2);
    // input2
    let input2 = document.createElement("input");
    input2.type = "text";
    input2.name = "email";
    input2.id = "email";
    input2.required;
    input2.placeholder = "example@example.com";

    newDiv.appendChild(input2);
    newDiv.appendChild(document.createElement("br"));
    // submit
    let input3 = document.createElement("input");
    input3.type = "submit";

    newDiv.appendChild(input3);
    newDiv.appendChild(document.createElement("br"));

    newDiv.appendChild(document.createTextNode("We will send you a email include a 6-digit confirmation code"));
    // add form end
    return false;
}

function submitResetPassword() {
    alert("function called");
    if (email != null) {
        var url = "../PHP/forgotPassword.php", data = $('#resetpw_form').serialize();
        console.log(data);
        $.ajax({
            // prevent page reload, dunno the reason
            async: false,
            url: url,
            type: 'POST',
            data: data,
            success: function (data) {
                alert(data);
                let flag = getValue('flag', data);
                if (flag == '1') {
                    let server_code = getValue('code', data);
                    let user_code = prompt("Please enter the code we sent to you", "123456");
                    if (user_code == server_code) {
                        return submitCode();
                    }
                }
                else if (flag == '0') {
                    alert('No such email');
                }
                else {
                    alert('server respond invald value: ' + data);
                }
            }
        });
        alert("end of the function");
        return false;
    }
    alert('please enter a valid email address');
    
    return false;
}

function submitCode() {
    let reset_pw = prompt("Please enter a new password", "your new password");

    if (reset_pw != null) {
        let user_id = getCookie('userid');
        // add user_id into data stream.
        var url = "../PHP/forgotPassword.php", data = 'newPassword=' + reser_pw + "&user_id=" + user_id;
        console.log(data);
        $.ajax({
            // prevent page reload, dunno the reason
            async: false,
            url: url,
            type: 'POST',
            data: data,
            success: function (data) {
                alert(data);
                let flag = getValue('flag', data);
                if (flag == '1') {
                    alert("successful, a confirmation email should be in your inbox\n remember to check spam.");
                }
                else if (flag == '0') {
                    alert('Falty, try again?');
                }
                else {
                    alert('server respond invald value: ' + data);
                }
            }
        });
        return false;
    }
    alert('something wrong, check again.');
    return false;
}