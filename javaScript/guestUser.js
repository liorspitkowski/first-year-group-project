function checkUser(){
    let userid = getCookie('userid');
    if(userid == '1' || userid == ''){
        return false;
    }
    return true;
}