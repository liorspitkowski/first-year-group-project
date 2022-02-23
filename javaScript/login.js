function submitLogin() {
    var url = "../PHP/login.php",
        data = $('#login_form').serialize();
    console.log(data);
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            alert(data); // show response from the php script.
        }
    });
    alert("making AJAX Call");
    return false;
}