function submitInventory() {
    alert('function called');
    let user_id = getCookie('userid');
    // add user_id into data stream.
    var url = "#", data = $('#inventory_form').serialize()+"&user_id="+user_id;
    console.log(data);
    $.ajax({
        // prevent page reload, dunno the reason
        async:false,
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            
        }
    });
    alert('');
    // prevent page reload, dunno the reason
    return false;
}