function addIngredientFeild(){

  var table = document.getElementById("ingredientTable");
  var lenTable = table.rows.length;
  var row = table.insertRow();

  var cell1 = row.insertCell(0);
  cell1.className = "foodColumn";
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);

  var input1 = document.createElement("input");
  input1.required = true;
  input1.type = "text";
  input1.className = "ingredient";
  input1.name = "ingredient" + lenTable;
  input1.id = "ingredient" + lenTable;
  input1.placeholder = "food";
  input1.autocomplete = "off";
  input1.addEventListener('input', autoComplete);
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

  document.addEventListener("click", function (e) {
      closeLists();
  });

  var url = "../PHP/GetIngridientParts.php",
  input = e.target;
  inputVal = e.target.value;
  $.ajax({
      url: url,
      type: 'post',
      data: {input: inputVal, function: "autofill"},
      success: function(data)
       {
         closeLists();
         if(inputVal.length < 1){return;}
         var foods = data.split(",");
         list = document.createElement("DIV");
         list.setAttribute("id", e.target.id + "-autocomplete-list");
         list.setAttribute("class", "autocomplete-items");
         e.target.parentNode.appendChild(list);

         for (i = 0; i < foods.length && i <= 5; i++){
           item = document.createElement("DIV");
           item.innerHTML = "<strong>" + foods[i].substr(0, inputVal.length) + "</strong>";
           item.innerHTML += foods[i].substr(inputVal.length);
           item.innerHTML += "<input type='hidden' value='" + foods[i] + "'>";
           item.addEventListener("click", function(event){
             //autofills value clicked by user
             const food = this.getElementsByTagName("input")[0].value;
             input.value = food;
             //gets default units for the selected food
             $.ajax({
                 url: "../PHP/GetIngridientParts.php",
                 type: 'post',
                 data: {input: food, function: "defaultUnits"},
                 success: function(unitVals) {
                   var units = unitVals.split(",");
                   if (units.length == 1){
                     input.parentNode.parentNode.getElementsByClassName("unit")[0].value = units[0];
                   }
                   else{
                     console.log(units);
                   }
                 }
             });
             closeLists();
           });
           list.appendChild(item);
         }
       }
   });


   function closeLists(){
     var x = document.getElementsByClassName("autocomplete-items");
     for (var i = 0; i < x.length; i++) {
       x[i].parentNode.removeChild(x[i]);
     }
   }

}

function init(){
  input = document.getElementById("ingredient1");
  if (input){
    input.addEventListener('input', autoComplete);
  }

  addIngredientFeild();
  addIngredientFeild();

}

window.onload = init;