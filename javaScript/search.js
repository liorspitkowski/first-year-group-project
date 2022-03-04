
function searchResult(submitButton){

  submitButton.addEventListener("click", function(e){

    e.preventDefault();
    submitSearchRequest();

  });

  function submitSearchRequest(){
    var url = "../PHP/SearchRecipe.php",
    data = $('#search-form').serialize();
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function(data)
         {
           console.log(data);
         }
     });
  }

}

function init(){

  submit = document.getElementById("searchRecipe");
  searchResult(submit);

}

window.onload = init;