/*
  Written by Hanmin Liu;
  get value named as 'cname' from data stream 'inputdata';
  example:
    let x = getValue('userid', data);
*/
function getValue(cname, inputdata) {
    inputdata = String(inputdata);
    cname = String(cname);
    let name = cname + "=";
    let ca = inputdata.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getValue_S(cname, inputdata, seperate) {
    inputdata = String(inputdata);
    cname = String(cname);
    console.log("running function shouldn't be running ");
    let name = cname + "=";
    let ca = inputdata.split(seperate);
    console.log(ca);
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
/*
  get all value names from data stream 'input data' as a array;
  example:
    let x = getValue_noName(data);
*/
function seperateBy(seperate, inputdata) {
    inputdata = String(inputdata);
    let ca = inputdata.split(seperate);
    return ca;
}

function getValue_noName(seperate, inputdata) {
    inputdata = String(inputdata);
    let ca = inputdata.split(seperate);
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        ca[i] = c;
    }
    return ca;
}