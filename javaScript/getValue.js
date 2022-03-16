/*
  Written by Hanmin Liu;
  get value named as 'cname' from data stream 'inputdata';
  example:
    let x = getValue('userid', data);
*/
function getValue(cname, inputdata) {
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
/*
  get all value names from data stream 'input data' as a array;
  example:
    let x = getValue_noName(data);
*/
function getValue_noName(inputdata) {
  let ca = inputdata.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    ca[i] = c;
  }
  return ca;
}