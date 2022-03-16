/*
    Written by Hanmin Liu;
    send inventory form, append userid;


    display:
    RECEIVE :
        - "user"
    RETURN :
        - Returns the information in one string.
        - in the order : Name, qty, units
        - one record after another and each record separated by #
        - so e.g. "Chicken#2#kg#rice#3#kg


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
            alert(data);
            let allInventory = listToMatrix(data.split('#'), 3);
            console.log("received:"+allInventory);
            maxrow = allInventory.length;
            displaydiv = document.getElementsByClassName('displayinventory');
            for (let i = 0; i < maxrow; ++i) {
                addElement(i, "p", "result-" + i, allInventory[i], "displayinventory");
            }

        }
    });
    return false;
}
/* create inventory results */
function addElement(
    id, // unique identifier in the page
    divtype, // this case, button.
    newdivname, //the created div name
    content, // the content inside
    parentdiv // where to insert
) {
    let newDiv = document.createElement(divtype);
    newDiv.name = newdivname;
    newDiv.style.cssText = 'width:90%;height:18%;margin:0.5% auto;';
    let newContent = document.createTextNode(content);
    newDiv.appendChild(newContent);
    let currentDiv = document.getElementById(parentdiv);
    currentDiv.appendChild(newDiv);
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