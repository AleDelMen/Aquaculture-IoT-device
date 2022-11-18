function Encode(fPort, obj, variables) {
  
  var appBytes = [];
  if (Object.keys(obj).length == 2) {
    appBytes[0] = (obj["action"] & 0xFF00) >> 8;
    appBytes[1] = (obj["action"] & 0x00FF);
    appBytes[2] = (obj["time"] & 0xFF00) >> 8;
    appBytes[3] = (obj["time"] & 0x00FF);
  } 
  else {
    appBytes[0] = (obj["action"] & 0xFF00) >> 8;
    appBytes[1] = (obj["action"] & 0x00FF);
    appBytes[2] = (obj["sensor"] & 0xFF00) >> 8;
    appBytes[3] = (obj["sensor"] & 0x00FF);
    appBytes[4] = (obj["valor"] & 0xFF00) >> 8;
    appBytes[5] = (obj["valor"] & 0x00FF);
  }
  
  /*
  //Esta funcion funciona correctamente, pero el foreach no asegura que los elementos estén ordenados.
  Object.keys(obj).forEach(function(element, index, array) {
    appBytes[2*index] = (obj[element] & 0xFF00) >> 8;
    appBytes[2*index + 1] = (obj[element] & 0x00FF);
  });
 */
  return appBytes;
}