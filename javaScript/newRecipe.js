function addIngredientFeild(){

  var table = document.getElementById("ingredientTable");
  var lenTable = table.rows.length;
  var row = table.insertRow();

  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);

  var input1 = document.createElement("input");
  input1.required = true;
  input1.type = "text";
  input1.name = "ingredient" + lenTable;
  var input2 = document.createElement("input");
  input2.required = true;
  input2.type = "number";
  input2.name = "amount" + lenTable;
  input2.min = "0";
  input2.step = "any";
  var input3 = document.createElement("input");
  input3.type = "text";
  input3.name = "unit" + lenTable;

  cell1.appendChild(input1);
  cell2.appendChild(input2);
  cell3.appendChild(input3);
}

function removeIngredientFeild(){
  var table = document.getElementById("ingredientTable");
  var lenTable = table.rows.length;
  if (lenTable > 2){
    var row = table.deleteRow(lenTable-1);
  }
  else {
    alert("recipe must contain at least one ingredient");
  }
}

function submitRecipe(){
  var url = "../PHP/login.php",
  data = $('#login_form').serialize();
  console.log(data);
  $.ajax({
      url: url,
      type: 'post',
      data: data,
      success: function(data)
       {
         alert(data); // show response from the php script.
       }
   });
   alert("Code to make AJAX Call");
   return false;
}

