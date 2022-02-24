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
  input1.id = "ingredient" + lenTable;
  input1.placeholder = "food";
  input1.autocomplete = "off";
  var input2 = document.createElement("input");
  input2.required = true;
  input2.type = "number";
  input2.name = "amount" + lenTable;
  input2.className = "amount";
  input2.min = "0";
  input2.step = "any";
  input2.placeholder = "quantity";
  var input3 = document.createElement("input");
  input3.type = "text";
  input3.name = "unit" + lenTable;
  input3.className = "unit";
  input3.placeholder = "unit";
  input3.autocomplete = "off";

  cell1.appendChild(input1);
  cell2.appendChild(input2);
  cell3.appendChild(input3);
}

function removeIngredientFeild(){
  var table = document.getElementById("ingredientTable");
  var lenTable = table.rows.length;
  if (lenTable > 1){
    var row = table.deleteRow(lenTable-1);
  }
  else {
    alert("recipe must contain at least one ingredient");
  }
}

function submitRecipe(){
  var url = "../PHP/addRecipe.php",
  data = $('#newRecipe').serialize();
  $.ajax({
      url: url,
      type: 'post',
      data: data,
      success: function(data)
       {
         alert(data);
       }
   });
}

const autoComplete = function autoCompleteFood(e){
  //console.log(e.target.value);

  var url = "../PHP/GetIngridientParts.php",
  data = e.target.value;
  $.ajax({
      url: url,
      type: 'post',
      data: {input: data, function: "autofill"},
      success: function(data)
       {
         console.log(data);
       }
   });

}

function init(){
  input = document.getElementById("ingredient1");
  if (input){
    input.addEventListener('input', autoComplete);
    input.addEventListener('propertychange', autoComplete);
  }
}

window.onload = init;