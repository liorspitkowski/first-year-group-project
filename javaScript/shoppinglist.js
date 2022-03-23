/*
    - Returns the ingredients needed so that a recipe can be made
- this will be compared with the user's inventory

RECEIVE :
- "userId"
- "recipeName"
RETURN :
- Return each property one after another as shown in the previous example
- e.g. Chicken#4#kg......


*/

function getShoppingList(){
    alert('getting shopping list');
    var url = "../PHP/generateList.php", data = 'userid='+getCookie('userid');
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            var shoppinglist = seperateBy('#',data);
            console.log(shoppinglist);
            /*
                ['bluefish', '431', 'g\r\n', 'rice', '300', 'g\r\n', 'seaweed', '10', 'g']
                show a table of it
                name quantity unit delete_button
            */
            displayShoppingList(shoppinglist);
            console.log("shopping list display complete");
        }
    });
    return false;
}

function displayShoppingList(rawList){
    rawList = listToMatrix(rawList,3);
    console.log("the list being preprocessed is: " + rawList);
    for(i = 0; i<rawList.length;++i){
        addElementtoPage(rawList[i]);
    }
}

function addElementtoPage(rawItem){
    // name quantity unit
    let parentTable = document.getElementById('display-table');
    let row = document.createElement("tr");
    addThToTr(row,rawItem[0]);
    addThToTr(row,rawItem[1]);
    addThToTr(row,rawItem[2]);
    addBuToTr(row,rawItem);
    parentTable.appendChild(row);

}
function addThToTr(row, rawContent){
    let box = document.createElement("th");
    let content = document.createTextNode(rawContent);
    box.appendChild(content);
    row.appendChild(box);
}
function addBuToTr(row, info){
    let box = document.createElement("th");
    let button = document.createElement("button");
    let content = document.createTextNode("delete");
    button.onclick = "return deleteRecipe("+info[0]+")";
    button.appendChild(content);
    box.appendChild(button);
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

function deleteRecipe(name){
    console.log("deleting"+name);
    var url = "#", data = '#';
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            //alert(data);  show response from the php script.
            if (data == "0" | data == "1") {
                alert('username or password incorrect, \nplease check again');
            }
            else if (data == "2") {
                alert('welcome back to Foogle');
            }
            else {
                alert('server response invalid value: ' + data);
            }
        }
    });
    return false;
}