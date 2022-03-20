/*
    - Returns the ingredients needed so that a recipe can be made
- this will be compared with the user's inventory

RECEIVE :
- "userId"
- "recipeName"
RETURN :
- Return each property one after another as shown in the previous example
- e.g. Chicken#4#kg......


*/

function getShoppingList(){
    alert('getting shopping list');
    var url = "../PHP/generateList.php", data = 'userId='+getCookie('userid');
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            var shoppinglist = seperateBy('#',data);
            console.log(shoppinglist);
            
        }
    });
    return false;
}

function submitShoppinglist() {
    alert('function called');
    var url = "#", data = $('#shopping_form').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            //alert(data);  show response from the php script.
            if (data == "0" | data == "1") {
                alert('username or password incorrect, \nplease check again');
            }
            else if (data == "2") {
                alert('welcome back to Foogle');
            }
            else {
                alert('server response invalid value: ' + data);
            }
        }
    });
    return false;
}