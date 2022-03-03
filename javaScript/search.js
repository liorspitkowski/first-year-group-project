/*
    Written by: Hanmin Liu;
    send registration form, expect return value -0: exist user -1: success and redirect to login;
*/
function submitSearch() {
    alert('function called');
    var url = "../PHP/SearchRecipe.php", data = $('#search_form').serialize();
    console.log(data);
    $.ajax({
        async:false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            if(data == "0" | data =="1"){
                alert('username or password incorrect, \nplease check again');
            }
            else if(data == "2"){
                alert('welcome back to Foogle');
            }
            else{
                alert('server response invalid value: ' + data);
            }
        }
    });
    return false;
}