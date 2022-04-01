function loadDiscription() {
    console.log('loading');
    // Initialize the page;
    loadDefault();
    // Add listeners to buttons;
    AddListenerToBu(
        'link_recipe',
        'Wanna create your own recipe?<br>Join us<br>And see what you can make with us.',
        'url(../src/chef-cooking-food-restaurant-kitchen.jpg)'
    );
    AddListenerToBu(
        'link_inventory',
        'We know exactly what is in our stock<br>What about you?',
        'url(../src/portrait-supermarket-worker-standing-by-freezer-with-food.jpg)'
    );
    AddListenerToBu(
        'link_search',
        'Search for a recipe<br>And get started<br>Right now!',
        'url(../src/grandparents-niece-searching-recipe-christmas.jpg)'
    );
    AddListenerToBu(
        'link_profile',
        'Check your info<br>and what you have created with us.',
        'url(../src/sign-user-password-privacy-concept.jpg)'
    );
}

function loadDefault() {
    console.log("loading default");
    let parentp = document.getElementById('contentLoadParagraph');
    parentp.innerHTML = 'Move your mouse on a button<br>to see what it does.<br>';
    parentp.style.backgroundImage = "url(../src/cooked-food.jpg)";
}

function AddListenerToBu(id, content, imgUrl) {
    let button = document.getElementById(id);
    button.addEventListener('mouseover', function() {
        mouseOver(content, imgUrl);
    });
    button.addEventListener('mouseout', function() {
        mouseOut();
    });
}

function mouseOver(content, imgUrl) {
    let parentp = document.getElementById('contentLoadParagraph');
    parentp.innerHTML = content;
    parentp.style.backgroundImage = imgUrl;
}

function mouseOut() {
    let parentp = document.getElementById('contentLoadParagraph');
    parentp.innerHTML = 'Move your mouse on a button<br>to see what it does.<br>';
    parentp.style.backgroundImage = "url(../src/cooked-food.jpg)";
}