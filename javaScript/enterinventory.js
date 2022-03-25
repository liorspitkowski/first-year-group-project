/*
    Written by Hanmin Liu;
    Display inventory;
    Allow user add ingredients;

    - Returns the inventory in one string.
    - in the order : Name, qty, units separated by #
    - e.g. "Chicken#2#kg#rice#3#kg

*/
function receiveInventory() {
    let user_id = getCookie('userid');
    var url = "../PHP/displayInventory.php", data = "&user=" + user_id;
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            let allInventory = listToMatrix(data.split('#'), 3);
            console.log("received: "+allInventory);
            
            parentTable = document.getElementById('inventory-table');
            for( i=0; i<allInventory.length; ++i){
                addRow(parentTable,allInventory[i]);
            }
        }
    });
    return false;
}

function addRow(parentTable, rawItem) {
    // name quantity unit
    let row = document.createElement("tr");
    addThToTr(row, rawItem[0]);
    addThToTr(row, rawItem[1]);
    addThToTr(row, rawItem[2]);
    addBuToTr(row,rawItem);
    parentTable.appendChild(row);
}
function addBuToTr(row, rawContent){
    let box = document.createElement("th");
    let button = document.createElement("button");
    let content = document.createTextNode("delete");
    button.addEventListener('click',function(e){
        e.preventDefault();
        submitDelInv(rawContent);
    });
    button.appendChild(content);
    box.appendChild(button);
    row.appendChild(box);
}
function addThToTr(row, rawContent) {
    let box = document.createElement("th");
    let content = document.createTextNode(rawContent);
    box.appendChild(content);
    row.appendChild(box);
}


/* convert list to n elements per row. */
function listToMatrix(list, elementsPerSubArray) {
    var matrix = [], i, k;
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
    var url = "../PHP/addIngredient.php", data = $('#inventory_form').serialize() + "&user_id=" + user_id;
    console.log("data sent is: "+data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            console.log("data received is: "+data)
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert('Added successfully');
            }
            else if (flag == '0') {
                alert('Falty, no such food');
            }
            else {
                alert('server respond invald value: ' + data);
            }
        }
    });
    return false;
}
function submitDelInv(info){
    let user_id = getCookie('userid');
    var url = "../PHP/addIngredient.php", data = $('#inventory_form').serialize() + "&user_id=" + user_id;
    console.log("data sent is: "+data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            console.log("data received is: "+data)
            let flag = getValue('flag', data);
            if (flag == '1') {
                alert('Added successfully');
            }
            else if (flag == '0') {
                alert('Falty, no such food');
            }
            else {
                alert('server respond invald value: ' + data);
            }
        }
    });
}