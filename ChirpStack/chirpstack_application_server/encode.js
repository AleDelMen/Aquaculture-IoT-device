function Decode(fPort, bytes, variables) {
  var appData = {};
  var frame_size = bytes.length / 3;
  
  for (var i = 0; i < frame_size; i++) {
    appData[bytes[(3*i)+0]] = ((bytes[(3*i)+1] << 8) |(bytes[(3*i)+2])) / 1000;
  }
  return appData;
}

//Example
/*
bytes = [1,253,252,2,251,250]
frame_size = 2

for i = 0
appData[1] = (253 << 8 | 252) / 1000;

for i = 1
appData[2] = (251 << 8 | 250) / 1000;

Result
appData=
{
  1:(253 << 8 | 252) / 1000,
  2:(251 << 8 | 250) / 1000,
}
*/
