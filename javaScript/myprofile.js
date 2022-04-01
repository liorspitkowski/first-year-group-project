/*
    submitProfile:
    Written by Hanmin Liu / Ziggy Hughes;

    display profile:
    send userID;
    expect return value: Username, Firstname, Lastname, users recipies;
*/

function populate() {
    let user_id = getCookie('userid');
    console.log(user_id);
    var url = "../PHP/getProfile.php";
    var data = $('<input type="hidden" name="user_id" value="' + user_id + '" /> ').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            console.log(data);
            let info = data.split('#');
            var un = info[0];
            var fn = info[1];
            var ln = info[2];
            document.getElementById('user_changed_name').value = un;
            document.getElementById('user_changed_fname').value = fn;
            document.getElementById('user_changed_lname').value = ln;
            if (info.length > 3) {
                for (var i = 3; i < info.length; i++) {
                    var node = document.createElement('li');
                    var textnode = document.createTextNode(info[i]);
                    node.appendChild(textnode);
                    document.getElementById("displayRecipiesList").appendChild(node);
                }
            } else {

            }
        }
    });
    return false;
}

function submitUsernameChangeRequest() {
    let user_id = getCookie('userid');
    console.log(user_id);
    $('#username_form').append('<input type="hidden" name="user_id" value="' + user_id + '" /> ');
    var url = "../PHP/changeUsername.php";
    var data = $('#username_form').serialize();
    console.log(data);
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert("changed successful.");
            } else {
                alert("server respond invalid value: " + data);
            }
        }
    });
    return false;
}

function submitFirstnameChangeRequest() {
    let user_id = getCookie('userid');
    $('#firstname_form').append('<input type="hidden" name="user_id" value="' + user_id + '" /> ');
    var url = "../PHP/changeFirstname.php",
        data = $('#firstname_form').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert("changed successful.");
            } else {
                alert("server respond invalid value: " + data);
            }
        }
    });
    return false;
}

function submitLastnameChangeRequest() {
    let user_id = getCookie('userid');
    $('#lastname_form').append('<input type="hidden" name="user_id" value="' + user_id + '" /> ');
    var url = "../PHP/changeLastname.php",
        data = $('#lastname_form').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert("changed successful.");
            } else {
                alert("server respond invalid value: " + data);
            }
        }
    });
    return false;
}

function submitDeleteRequest() {
    let user_id = getCookie('userid');
    $('#delete_form').append('<input type="hidden" name="user_id" value="' + user_id + '" /> ');
    var url = "../PHP/deleteUser.php",
        data = $('#delete_form').serialize();
    console.log(data);
    var temp = data;
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            let flag = getValue("flag", data);
            if (flag == "1") {
                var url = "../PHP/deleteUserIngredients.php";
                $.ajax({
                    async: false,
                    url: url,
                    type: 'POST',
                    data: temp,
                    success: function(data) {
                        alert("successful")
                        setCookieasGuest();
                    }
                });
                window.location.replace("../index.html");
            } else {
                alert("Unabe to delete this user");
            }
        }
    });
    return false;
}

function setCookieasGuest() {
    console.log("setting guestuser");
    document.cookie = "username=guestUser; expires=18 Dec 2025 12:00:00 UTC; path=/;";
    document.cookie = "userid=1; expires=18 Dec 2025 12:00:00 UTC; path=/;";
}