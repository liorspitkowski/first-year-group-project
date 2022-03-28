/*
    Written by Hanmin Liu;
    Display inventory;
    Allow user add ingredients;
    Allow user delete ingredients;

    - Returns the inventory in one string.
    - in the order : Name, qty, units separated by #
    - e.g. "Chicken#2#kg#rice#3#kg

*/

let orinum = 0;

function receiveInventory() {
    let user_id = getCookie('userid');
    var url = "../PHP/displayInventory.php",
        data = "&user=" + user_id;
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            if (data != '') {
                let allInventory = listToMatrix(data.split('#'), 3);
                console.log("received: " + allInventory);

                parentTable = document.getElementById('inventory-table');
                for (i = 0; i < allInventory.length; ++i) {
                    addRow(parentTable, allInventory[i], i);
                }
            } else {
                outputEmpty();
            }

        }
    });
    return false;
}

function outputEmpty() {
    console.log("deleting");
    let parentTable = document.getElementById('displayinventory');
    let d = document.getElementById('inventory-table');
    let content = document.createTextNode("You really have a clean fridge!");
    parentTable.removeChild(d);
    parentTable.appendChild(content);

}

function addRow(parentTable, rawItem, i) {
    // name quantity unit
    let row = document.createElement("tr");
    addTdToTr(row, rawItem[0]);
    addInToTr(row, rawItem[1], i);
    if (rawItem[2] == 'unit') {
        addTdToTr(row, rawItem[0]);
    } else {
        addTdToTr(row, rawItem[2]);
    }
     //
    addBuToTr(row, rawItem, i);
    parentTable.appendChild(row);
}

function addInToTr(row, number, id) {
    let box = document.createElement("td");
    let input = document.createElement("input");
    input.type = "number";
    input.min = "0";
    input.id = "input_number-" + id;
    input.value = number;
    input.placeholder = number;
    box.appendChild(input);
    row.appendChild(box);
}

function addBuToTr(row, rawContent, id) {
    let box = document.createElement("td");
    let button = document.createElement("button");
    let content = document.createTextNode("change");
    button.addEventListener('click', function(e) {
        e.preventDefault();
        orinum = parseInt(document.getElementById("input_number-" + id).placeholder);
        changeIngredient(rawContent[0], parseInt(document.getElementById("input_number-" + id).value), rawContent[2]);
    });
    button.appendChild(content);
    box.appendChild(button);
    row.appendChild(box);
}

function addTdToTr(row, rawContent) {
    let box = document.createElement("td");
    let content = document.createTextNode(rawContent);
    box.appendChild(content);
    row.appendChild(box);
}


/* convert list to n elements per row. */
function listToMatrix(list, elementsPerSubArray) {
    var matrix = [],
        i, k;
    for (i = 0, k = -1; i < list.length; i++) {
        if (i % elementsPerSubArray === 0) {
            k++;
            matrix[k] = [];
        }
        matrix[k].push(list[i]);
    }
    return matrix;
}

function submitInventory() {
    let user_id = getCookie('userid');
    var url = "../PHP/addIngredient.php",
        data = $('#inventory_form').serialize() + "&user_id=" + user_id;
    console.log("data sent is: " + data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            console.log("data received is: " + data)
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert('Added successfully');
                window.reload();
            } else if (flag == '0') {
                alert('Falty, no such food');
            } else {
                flag = getValue_S('flag', data, '\n');
                if (flag == '1') {
                    alert('Added successfully');
                    window.reload();
                } else if (flag == '0') {
                    alert('Falty, no such food');
                } else {
                    alert('server respond invald value: ' + data);
                }
            }
        }
    });
    return false;
}


function changeIngredient(name, newnumber, unit) {
    console.log("call changeIngredient() on " + name);
    console.log(typeof(newnumber) + " " + newnumber + " " + typeof(orinum) + " " + orinum);
    if (newnumber > orinum) {
        addup = newnumber - orinum;
        addInventory(name, addup, unit);
    } else if (newnumber == orinum) {
        alert("new number the same as original one");
    } else {
        del = orinum - newnumber;
        submitDelInv(name, del, unit);
    }
}

function submitDelInv(ingredient, quantity, unit) {
    // ingredient=beans&quantity=1&unit=g&user_id=14
    let user_id = getCookie('userid');
    var url = "../PHP/removeIngredient.php",
        data = "ingredient=" + ingredient + "&quantity=" + quantity + "&user_id=" + user_id;
    console.log("minoring data sent is: " + data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            console.log("data received is: " + data)
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert('Deleted successfully');
                window.reload();
            } else if (flag == '0') {
                alert('Falty, no such food');
            } else {
                alert('server respond invald value: ' + data);
            }
        }
    });
    return false;
}

function addInventory(ingredient, quantity, unit) {
    // ingredient=beans&quantity=1&unit=g&user_id=14
    let user_id = getCookie('userid');
    var url = "../PHP/addIngredient.php",
        data = "ingredient=" + ingredient + "&quantity=" + quantity + "&unit=" + unit + "&user_id=" + user_id;
    console.log("adding data sent is: " + data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            console.log("data received is: " + data)
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert('Added successfully');
                window.location.reload();
            } else if (flag == '0') {
                alert('Falty, no such food');
            } else {
                alert('server respond invald value: ' + data);
            }
        }
    });
    return false;
}
