/*
    Written by: Hanmin Liu;
    send search form, expect a list of buttons to be displayed in the search bar.
*/
var page_num/* total pages num */, current_page/* current_page/page_num */, recipe_array/* results string array */, max_show = 5/* max elements per page */;
function submitSearch() {
    // console.log(document.querySelector('#search_form'));
    var url = "../PHP/SearchRecipe.php", data = $('#search_form').serialize();
    console.log(data);
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            console.log(data);
            // 16 beans on toast;fancy beans on toast;beans in toast;beanz;beanz+;test recipe;Chicken Korma
            // initiate recipe_array, page_num, current_page
            recipe_array = getValue_noName(data);
            // let totalresult = recipe_array.length;
            recipe_array = listToMatrix(recipe_array, max_show);
            // Get how many pages
            page_num = recipe_array.length;
            /* DEBUG START */
            // console.log("num=" + totalresult);
            // let totalpage = Math.ceil(totalresult / max_show);
            // console.log("totalpage=" + page_num);
            console.log(recipe_array);
            /* DEBUG END */
            current_page = 1;
            clearPage();
            for (let i = 1; i <= max_show; i++) {
                // write recipes;
                addElement(i, "button", "result-" + i, recipe_array[current_page - 1][i - 1], "search-results");
            }
            // write index;
            indexUpdate();
        }
    });
    return false;
}
/* create search results */
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
    newDiv.onclick = function () {
        // when the recipe is clicked.
        console.log("this is the page " + current_page + " number " + id);
        console.log("recipe name is:" + content);
        let url = '../html/samplerecipe_testHanmin.html';
        let urlphp = '../PHP/DisplayRecipe.php';
        location.href = urlphp + '?recipeName=' + content;
    };
    let newContent = document.createTextNode(content);
    newDiv.appendChild(newContent);
    let currentDiv = document.getElementById(parentdiv);
    currentDiv.appendChild(newDiv);
}

/* delete current results */
function delElement(deldivname, parentdiv) {
    // error: not 'node'
    console.log("deleting " + deldivname);
    var d = document.getElementsByName(deldivname);
    console.log("d is " + d);
    for (var i = 0; i < d.length; ++i) {
        var item = d[i];  // 调用 myNodeList.item(i) 是没有必要的
        console.log(item);
        document.getElementById(parentdiv).removeChild(item);
    }

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
function nextPage() {
    if (current_page + 1 > page_num) {
        console.log("end of pages");
        return false;
    }
    clearPage();
    current_page += 1;
    for (let i = 1; i <= max_show; i++) {
        // write recipes;
        addElement(i, "button", "result-" + i, recipe_array[current_page - 1][i - 1], "search-results");
    }
    // write index;
    indexUpdate();
}
function prevPage() {
    if (current_page - 1 <= 0) {
        console.log("head of pages");
        return false;
    }
    clearPage();
    current_page -= 1;
    for (let i = 1; i <= max_show; i++) {
        // write recipes;
        addElement(i, "button", "result-" + i, recipe_array[current_page - 1][i - 1], "search-results");
    }
    // write index;
    indexUpdate();
}
function clearPage() {
    for (let i = 1; i <= max_show; i++) {
        // delete recipes;
        delElement("result-" + i, "search-results");
    }
}
function indexUpdate() {
    document.getElementById('index').innerHTML = current_page + '/' + page_num;
}