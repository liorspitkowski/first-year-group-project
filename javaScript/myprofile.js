/*
    submitProfile:
    Written by Hanmin Liu / Ziggy Hughes;

    display profile:
    send userID;
    expect return value: Username, Firstname, Lastname, users recipies;

    change Username:
    send userID and newUsername;
    expect return value:    flag=0 -> username taken;
                            flag=1 -> successful;
    change First name:
    send userID and newFirstname;
    expect return value:    flag=1 -> successful;

    change Last name:
    send userID and newLastname;
    expect return value:    flag=1 -> successful;

    delete User:
    send userID
    expect return value:    flag=0 -> can't delete this user (admin);
                            flag=1 -> successful;
*/
function submitUsernameChangeRequest() {
    alert('function call');
    let user_id = getCookie('userid');
    alert(user_id);
    $('#username_form').append('<input type="hidden" name="user_id" value="'+user_id+'" /> ');
    var url = "../PHP/changeUsername.php";
    var data = $('#username_form').serialize();
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
function submitFirstnameChangeRequest() {
    alert('function call');
    let user_id = getCookie('userid');
    alert(user_id);
    $('#firstname_form').append('<input type="hidden" name="user_id" value="'+user_id+'" /> ');
    var url = "../PHP/changeFirstname.php", data = $('#firstname_form').serialize();
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
function submitLastnameChangeRequest() {
    alert('function call');
    let user_id = getCookie('userid');
    alert(user_id);
    $('#lastname_form').append('<input type="hidden" name="user_id" value="'+user_id+'" /> ');
    var url = "../PHP/changeLastname.php", data = $('#lastname_form').serialize();
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

function submitDeleteRequest() {
  alert('function call');
  let user_id = getCookie('userid');
  alert(user_id);
  $('#delete_form').append('<input type="hidden" name="user_id" value="'+user_id+'" /> ');
  var url = "../PHP/deleteUser.php", data = $('#delete_form').serialize();
  console.log(data);
  alert(data);
  $.ajax({
      async: false,
      url: url,
      type: 'POST',
      data: data,
      success: function (data) {
          let flag = getValue("flag", data);
          if (flag == "1") {
            var url = "../PHP/deleteUserIngredients.php"
            $.ajax({
                async: false,
                url: url,
                type: 'POST',
                data: data,
                success: function (data) {
                    alert("successful")
                }
            });
            window.location.replace("../index.html");
          }
          else {
            alert("Unabe to delete this user");
          }
      }
  });
  return false;
}
