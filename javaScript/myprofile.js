/*
    submitProfile:
    Written by Hanmin Liu;
    send userID and newUsername;
    expect return value:    flag=0 -> username taken;
                            flag=1 -> successful;
*/
function submitUsernameChangeRequest() {
    alert('function call');
    let user_id = getCookie('userid');
    alert(user_id);
    var item = document.createElement("input");
    item.type = "hidden";
    item.value = user_id;
    document.getElementById('username_form').appendChild(item);
    var url = "../PHP/changeUsername_testHanmin.php", data = $('username_form').serialize();
    console.log(data);
    alert(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            alert(data);
        }
    });
    return false;
}