/*
    Written by: Hanmin Liu;
    send search form, expect a string to be displayed in the search bar.
*/
function submitSearch() {
    alert('function called');
    // console.log(document.querySelector('#search_form'));
    var url = "../PHP/SearchRecipe.php", data = $('#search_form').serialize();
    console.log(data);
    $.ajax({
        async:false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            console.log(data);
        }
    });
    return false;
}