function addToSL() {
  alert('function call');
  let user_id = getCookie('userid');
  let recipe_id = document.getElementById('recipe_id').value;
  alert(user_id);
  alert(recipe_id);
  $('#add_to_list').append('<input type="hidden" name="user_id" value="'+user_id+'" /> ');
  $('#add_to_list').append('<input type="hidden" name="recipe_id" value="'+recipe_id+'" /> ');
  var url = "../PHP/addRecipeToList.php";
  var data = $('#add_to_list').serialize();
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

function removeFromSL() {
  alert('function call');
  let user_id = getCookie('userid');
  let recipe_id = document.getElementById('recipe_id')
  alert(user_id);
  alert(recipe_id);
  $('#remove_from_list').append('<input type="hidden" name="user_id" value="'+user_id+'" /> ');
  $('#remove_from_list').append('<input type="hidden" name="recipe_id" value="'+recipe_id+'" /> ');
  var url = "../PHP/removeRecipeFromList.php", data = $('#remove_from_list').serialize();
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
