function submitLogin() {
    var url = "../PHP/addRecipe.php",
        data = $('#newRecipe').serialize();
    console.log(data);
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function (data) {
            alert(data); // show response from the php script.
        }
    });
    alert("Code to make AJAX Call");
    return false;
}