/*
    Written by Hanmin Liu;

    -Display all recipes that user has added to the shopping list;
    -Allow user to remove certain recipe;
    -Display all ingredients that needed for user to cook the recipes
    on the shopping list;

*/
function loadShoppingList() {
    console.log("loading");
    getShoppingList_ingredients();
    getShoppingList_recipes();
}
function getShoppingList_ingredients() {
    console.log('getting ingredients');
    var url = "../PHP/generateList.php", data = 'userId=' + getCookie('userid');
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            console.log("received: "+data+" type: "+typeof(date));
            if(data == ''){
                displayEmptySL_ingredients();
                return;
            }
            var shoppinglist = seperateBy('#', data);
            console.log("split: "+shoppinglist);
            /*
                ['bluefish', '431', 'g\r\n', 'rice', '300', 'g\r\n', 'seaweed', '10', 'g']
                show a table of it
                name quantity unit
            */
            displayShoppingList(shoppinglist);
            console.log("shopping list display complete");
        }
    });
    return false;
}

function displayShoppingList(rawList) {
    rawList = listToMatrix(rawList, 3);
    console.log("the list being preprocessed is: " + rawList);
    for (i = 0; i < rawList.length; ++i) {
        addElementToPage(rawList[i]);
    }
}

function addElementToPage(rawItem) {
    // name quantity unit
    let parentTable = document.getElementById('ingredients-table');
    let row = document.createElement("tr");
    addThToTr(row, rawItem[0]);
    addThToTr(row, rawItem[1]);
    addThToTr(row, rawItem[2]);
    parentTable.appendChild(row);
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


function getShoppingList_recipes() {
    console.log('getting recipes');
    var url = "../PHP/displayRecipesOnList.php", data = 'userId=' + getCookie('userid');
    console.log('recipes posting: '+data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            if(data == ''){
                displayEmptySL_recipes();
                return;
            }
            var shoppinglist = seperateBy('#', data);
            console.log(shoppinglist);
            /*
                ['bluefish', '431', 'g\r\n', 'rice', '300', 'g\r\n', 'seaweed', '10', 'g']
                show a table of it
                name quantity unit
            */
            displayShoppingList_recipes(shoppinglist);
            console.log("shopping list display complete");
        }
    });
    return false;
}

function displayShoppingList_recipes(rawList) {
    rawList = listToMatrix(rawList, 2);
    console.log("[recipe] the list being preprocessed is: " + rawList);
    for (i = 0; i < rawList.length; ++i) {
        addElementToPage_recipes(rawList[i]);
    }
}

function addElementToPage_recipes(rawItem) {
    // name quantity unit
    let parentTable = document.getElementById('recipes-table');
    let row = document.createElement("tr");
    addThToTr(row, rawItem[0]);
    addThToTr(row, rawItem[1]);
    addBuToTr(row, rawItem);
    parentTable.appendChild(row);
}
function addBuToTr(row, info) {
    let box = document.createElement("th");
    let button = document.createElement("button");
    let content = document.createTextNode("delete");
    //button.onclick = "return deleteRecipe("+info[0]+")";
    button.addEventListener("click", function () { submitDelRecipe(info[0]) });
    button.appendChild(content);
    box.appendChild(button);
    row.appendChild(box);
}


function submitDelRecipe(name) {
    console.log("deleting" + name);
    var url = "removeRecipeFromList.php", data = 'userId=' + getCookie('userId') + '&recipeId=' + name;
    console.log("posting data: " + data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            let flag = getValue('flag');
            /* 
                - "flag=0" : SQL fail
                - "flag=1" : recipe not in shopping list
                - "flag=2" : successful
            
            */
            if (flag == '0') {
                alert('SQL fail');
            } else if (flag == '1') {
                alert('there is no such recipe in your shopping list');
            } else if (flag == '2') {
                alert('deleted successfully');
            } else {
                alert('server respond invalid value: ' + data);
            }
        }
    });
    return false;
}


function displayEmptySL_ingredients(){
    let parentd = document.getElementById('display-div');
    let table = document.getElementById('ingredients-table');
    parentd.removeChild(table);
    
    let content = document.createTextNode("Nothing you should Buy");
    parentd.appendChild(content);
}
function displayEmptySL_recipes(){
    let parentd = document.getElementById('display-div');
    let table = document.getElementById('recipes-table');
    parentd.removeChild(table);
    
    let content = document.createTextNode("Nothing you have chosen");
    parentd.appendChild(content);
}