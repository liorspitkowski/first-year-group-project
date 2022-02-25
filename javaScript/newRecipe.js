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
  autoCompleteFood(input1);
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

function autoCompleteFood(input){

  var focus = -1;

  /*adds event listener to send a request to the database when a letter is entered or removed
  from the input box*/
  input.addEventListener('input', function(e){
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
           if (inputVal.length < 1) return;
           var foods = data.split(",");
           if (foods[0] == "") return;
           list = document.createElement("DIV");
           list.setAttribute("id", e.target.id + "-autocomplete-list");
           list.setAttribute("class", "autocomplete-items");
           e.target.parentNode.appendChild(list);

           console.log(foods);

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
  });

  /*adds event listener to allow scrolling through aoutfill options with arrow keys */
  input.addEventListener('keydown', function(e){
    var list = document.getElementById(this.id + "-autocomplete-list");
    if (list) list = list.getElementsByTagName("div");

    if (e.keyCode == 40) {
      focus++;
      addActive(list);
    }
    if (e.keyCode == 38) {
      focus--;
      addActive(list);
    }
    if (e.keyCode == 13) {
      e.preventDefault();
        if (focus > -1) {
          /*simulate a click on the "active" item:*/
          if (list) list[focus].click();
        }
    }
  });

  function addActive(list) {
    if (!list){
      return false;
    }
    removeActive(list);
    if (focus >= list.length) focus = 0;
    if (focus < 0) focus = (list.length - 1);
    list[focus].classList.add("autocomplete-active");
  }

  function removeActive(list) {
    for (var i = 0; i < list.length; i++) {
      list[i].classList.remove("autocomplete-active");
    }
  }

   function closeLists(){
     var list = document.getElementsByClassName("autocomplete-items");
     for (var i = 0; i < list.length; i++) {
       list[i].parentNode.removeChild(list[i]);
     }
   }

   document.addEventListener("click", function (e) {
       closeLists();
   });

}

function init(){

  input = document.getElementById("ingredient1");
  autoCompleteFood(input);

  addIngredientFeild();
  addIngredientFeild();

}

window.onload = init;