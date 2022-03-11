/*
    Written by Hanmin Liu;
    send inventory form, append userid;
*/
function submitInventory() {
    alert('function called');
    let user_id = getCookie('userid');
    // add user_id into data stream.
    var url = "../php/addIngredient.php", data = $('#inventory_form').serialize()+"&user_id="+user_id;
    console.log(data);
    $.ajax({
        // prevent page reload, dunno the reason
        async:false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            alert(data);
            let flag = getValue('flag', data);
            if(flag == '1'){
                alert('Added successfully');
            }
            else if(flag == '0'){
                alert('Falty, no such food');
            }
            else{
                alert('server respond invald value: '+ data);
            }
        }
    });
    return false;
}