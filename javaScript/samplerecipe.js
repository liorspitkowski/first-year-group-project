function submitSample() {
    alert('function called');
    var url = "#",
        data = $('samole_form').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            //alert(data);  show response from the php script.
            if (data == "0" | data == "1") {
                alert('username or password incorrect, \nplease check again');
            } else if (data == "2") {
                alert('welcome back to Foogle');
            } else {
                alert('server response invalid value: ' + data);
            }
        }
    });
    // prevent page reload, dunno the reason
    return false;
}

function add_to_shopping_list() {
    data = $('#add_to_list').serialize();
    console.log(data);
}

function remove_from_shopping_list() {
    console.log("remove list");
}

function remove_from_inventory() {
    console.log("remove inventory");
}

/*
Search Bar -- abandoned
*/

function searchRecipe_notSearchPage() {
    let user_id = getCookie('userid');
    let data = $().serialize();
    var url = "../html/search.html";
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            console.log(data);
        }
    });
    return false;
}