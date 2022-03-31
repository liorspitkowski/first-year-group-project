// JS for new Recipe page

function addIngredientFeild() {

    var table = document.getElementById("ingredientTable");
    var lenTable = table.rows.length + 1;
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

function removeIngredientFeild() {
    var table = document.getElementById("ingredientTable");
    var lenTable = table.rows.length;
    if (lenTable > 1) {
        var row = table.deleteRow(lenTable - 1);
    } else {
        alert("recipe must contain at least one ingredient");
    }
}

function submitRecipe(submitButton) {

    //adds event listeners for form submit
    submitButton.addEventListener("click", function(e) {

        e.preventDefault();
        submitRequest();

    });

    document.addEventListener("keypress", function(e) {
        if (e.keyCode === 13 && e.target.type != "submit") {
            e.preventDefault
            submitRequest();
        }
    });

    document.getElementById("clearForm").addEventListener("click", function(e) {
        clearMessages();
        var form = document.getElementById('newRecipe');
        for (var i = 0; i < form.elements.length; i++) {
            form.elements[i].style.backgroundColor = "white";
        }
    });

    function submitRequest() {

        //validation
        var form = document.getElementById('newRecipe');
        var validation = true;
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].hasAttribute('required')) {
                form.elements[i].style.backgroundColor = "white";
                if (form.elements[i].value === '') {
                    form.elements[i].style.backgroundColor = "#ffdede";
                    validation = false;
                }
            }
        }

        if (!validation) {
            displayMessage("-1", "missing some required fields");
            return false;
        }

        var url = "../PHP/AddRecipe.php";
        var data = $('#newRecipe').serialize() + "&userId=" + getCookie('userid');
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function(data) {
                console.log(data);
                result = data.split(" | ");
                displayMessage(result[0], result[1]);
            }
        });
    }

    var url = "../PHP/addRecipe.php";
    var data = $('#newRecipe').serialize() + "&userId=" + getCookie('userid');
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function(data)
         {
           console.log(data);
           result = data.split(" | ");
           displayMessage(result[0], result[1]);
         }
     });
  }

  function displayMessage(type, message){

    var colour;
    switch (type) {
      case "-1":
        colour = "#ffdede";
        break;
      case "0":
        colour = "#ffb861";
        break;
      case "1":
        document.getElementById("clearForm").click();
        colour = "#83ff7a";
        break;
      case "99":
        return;
      default:
        colour = "white";
        break;

        }

        const container = document.getElementById("recipeFormDiv");

        clearMessages();

        var requestMessage = document.createElement("DIV");
        requestMessage.className = "message";
        requestMessage.style.backgroundColor = colour;
        requestMessage.innerHTML = message;
        container.appendChild(requestMessage);
    }

    function clearMessages() {
        const container = document.getElementById("recipeFormDiv");

        var messages = container.getElementsByClassName("message");
        while (messages[0]) {
            messages[0].parentNode.removeChild(messages[0]);
        }
    }

}

function autoCompleteFood(input) {

    var focus = -1;

    /*adds event listener to send a request to the database when a letter is entered or removed
    from the input box*/
    input.addEventListener('input', function(e) {
        var url = "../PHP/GetIngridientParts.php",
            input = e.target;
        inputVal = e.target.value;
        $.ajax({
            url: url,
            type: 'post',
            data: { input: inputVal, function: "autofill" },
            success: function(data) {
                closeLists();
                if (inputVal.length < 1) return;
                var foods = data.split(",");
                if (foods[0] == "") return;
                list = document.createElement("DIV");
                list.setAttribute("id", e.target.id + "-autocomplete-list");
                list.setAttribute("class", "autocomplete-items");
                e.target.parentNode.appendChild(list);

                for (i = 0; i < foods.length && i <= 5; i++) {
                    item = document.createElement("DIV");
                    item.innerHTML = "<strong>" + foods[i].substr(0, inputVal.length) + "</strong>";
                    item.innerHTML += foods[i].substr(inputVal.length);
                    item.innerHTML += "<input type='hidden' value='" + foods[i] + "'>";
                    item.addEventListener("click", function(event) {
                        //autofills value clicked by user
                        const food = this.getElementsByTagName("input")[0].value;
                        input.value = food;
                        //gets default units for the selected food
                        $.ajax({
                            url: "../PHP/GetIngridientParts.php",
                            type: 'post',
                            data: { input: food, function: "defaultUnits" },
                            success: function(unitVals) {
                                var units = unitVals.split(",");
                                if (units.length == 1) {
                                    input.parentNode.parentNode.getElementsByClassName("unit")[0].value = units[0];
                                } else {
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
    input.addEventListener('keydown', function(e) {
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
        if (!list) {
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

    function closeLists() {
        var list = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < list.length; i++) {
            list[i].parentNode.removeChild(list[i]);
        }
    }

    document.addEventListener("click", function(e) {
        closeLists();
    });

}

function init() {

    input = document.getElementById("ingredient1");
    autoCompleteFood(input);

    addIngredientFeild();
    addIngredientFeild();

    submit = document.getElementById("submitRecipe");
    submitRecipe(submit);

}