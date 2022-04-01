function addToSL() {
    if (!checkUser()) {
        alert("You haven't logged in! You must have forget!\nOr...Try to Register?");
        return false;
    } else {
        let user_id = getCookie('userid');
        let recipe_id = document.getElementById('recipe_id').textContent;
        $('#add_to_list').append('<input type="hidden" name="user_id" value="' + user_id + '" /> ');
        $('#add_to_list').append('<input type="hidden" name="recipe_id" value="' + recipe_id + '" /> ');
        var url = "../PHP/addRecipeToList.php";
        var data = $('#add_to_list').serialize();
        console.log(data);
        $.ajax({
            async: false,
            url: url,
            type: 'POST',
            data: data,
            success: function(data) {
                alert("Success.");
            }
        });
        return false;
    }


}

function removeFromSL() {
    if (!checkUser()) {
        alert("You haven't logged in! You must have forget!\nOr...Try to Register?");
        return false;
    } else {
        let user_id = getCookie('userid');
        let recipe_name = document.getElementById('recipe_name').textContent;
        console.log('recipe_name is: ' + recipe_name);
        var url = "../PHP/removeRecipeFromList.php",
            data = "user_id=" + user_id + "&recipe_id=" + recipe_name;
        // $('#remove_from_list').serialize();
        console.log(data);
        $.ajax({
            async: false,
            url: url,
            type: 'POST',
            data: data,
            success: function(data) {
              let flag = getValue('flag', data);
              if (flag == '1') {
                alert("Success.");
              }
              else {
                alert("Error.");
              }
            }
        });
        return false;
    }


}
