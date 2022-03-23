
/*
    Written and maintained by Lior Spitkowski
    Help from Hanmin's code
*/

function remove_from_inventory(){
    let user_id = getCookie('userid')
    let recipe_id = getElementById('recipe_id');
    var url = "../PHP/removeRecipeIngredients.php", data = "&user_id" + user_id + "&recipe_id" + recipe_id;
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data)
         {
           console.log(data);
           let flag = getValue('flag', data);
           if (flag == '1') {
              alert("Success")
           } else if (flag == '0') {
              alert("Failure")
           } else {
              alert("Invalid Response")
           }
         }
     });
     return false;
}
