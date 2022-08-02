<html><head><title>Colorpicker</title></head><body>
<script type="text/javascript">
function dx (d) {
  max = 255;
  if (d > max)
    return "null";
  if (d <= -1)
    return  "null";
  var z = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
    "A", "B", "C", "D", "E", "F");
  var x = "";
  if (d == 0)
    return "00";
  var i = 1, v = d, r = 0;
  while (v > 15) {
    v = Math.floor(v / 16);
    i++;
  }
  v = d;
  for (j=i; j >= 1; j--) {
    x = x + z[Math.floor(v / Math.pow(16,j-1))];
    v = v - (Math.floor(v / Math.pow(16,j-1)) * Math.pow(16,j-1));
  }
  if (d <= 15)
    x = "0" + x;
  return x;
}

function Neu () {
  var vr = eval(document.Auswahl.VR.value);
  var vg = eval(document.Auswahl.VG.value);
  var vb = eval(document.Auswahl.VB.value);
  var vrx = dx(vr);
  var vgx = dx(vg);
  var vbx = dx(vb);
  var VString = "#" + vrx + vgx + vbx;
  var VSet = vrx + vgx + vbx;
  document.Auswahl.VHex.value = VString;
  document.Vorschau.style.backgroundColor = VString;
}
function Farb (R, G, B) {
    document.Auswahl.VR.value = R;
    document.Auswahl.VG.value = G;
    document.Auswahl.VB.value = B;
    Neu()
}
</script>
</head>
<body onLoad="Neu()">

<map name="vgmap">
<area shape="rect" coords="1,1,26,10" href="javascript:Farb('255', '128', '128')" alt="">
<area shape="rect" coords="28,1,53,10" href="javascript:Farb('255', '255', '128')" alt="">
<area shape="rect" coords="55,1,80,10" href="javascript:Farb('128', '255', '128')" alt="">
<area shape="rect" coords="82,1,107,10" href="javascript:Farb('0', '255', '128')" alt="">
<area shape="rect" coords="109,1,134,10" href="javascript:Farb('128', '255', '255')" alt="">
<area shape="rect" coords="136,1,161,10" href="javascript:Farb('0', '128', '255')" alt="">

<area shape="rect" coords="163,1,188,10" href="javascript:Farb('255', '128', '192')" alt="">
<area shape="rect" coords="190,1,215,10" href="javascript:Farb('255', '128', '255')" alt="">
<area shape="rect" coords="1,12,26,21" href="javascript:Farb('255', '0', '0')" alt="">
<area shape="rect" coords="28,12,53,21" href="javascript:Farb('255', '255', '0')" alt="">
<area shape="rect" coords="55,12,80,21" href="javascript:Farb('128', '255', '0')" alt="">
<area shape="rect" coords="82,12,107,21" href="javascript:Farb('0', '255', '64')" alt="">
<area shape="rect" coords="109,12,134,21" href="javascript:Farb('0', '255', '255')" alt="">
<area shape="rect" coords="136,12,161,21" href="javascript:Farb('0', '128', '192')" alt="">
<area shape="rect" coords="163,12,188,21" href="javascript:Farb('128', '128', '192')" alt="">
<area shape="rect" coords="190,12,215,21" href="javascript:Farb('255', '0', '255')" alt="">
<area shape="rect" coords="1,23,26,32" href="javascript:Farb('128', '64', '64')" alt="">
<area shape="rect" coords="28,23,53,32" href="javascript:Farb('255', '128', '64')" alt="">
<area shape="rect" coords="55,23,80,32" href="javascript:Farb('0', '255', '0')" alt="">
<area shape="rect" coords="82,23,107,32" href="javascript:Farb('0', '128', '128')" alt="">
<area shape="rect" coords="109,23,134,32" href="javascript:Farb('0', '64', '128')" alt="">
<area shape="rect" coords="136,23,161,32" href="javascript:Farb('128', '128', '255')" alt="">
<area shape="rect" coords="163,23,188,32" href="javascript:Farb('128', '0', '64')" alt="">

<area shape="rect" coords="190,23,215,32" href="javascript:Farb('255', '0', '128')" alt="">
<area shape="rect" coords="1,34,26,43" href="javascript:Farb('128', '0', '0')" alt="">
<area shape="rect" coords="28,34,53,43" href="javascript:Farb('255', '128', '0')" alt="">
<area shape="rect" coords="55,34,80,43" href="javascript:Farb('0', '128', '0')" alt="">
<area shape="rect" coords="82,34,107,43" href="javascript:Farb('0', '128', '64')" alt="">
<area shape="rect" coords="109,34,134,43" href="javascript:Farb('0', '0', '255')" alt="">
<area shape="rect" coords="136,34,161,43" href="javascript:Farb('0', '0', '160')" alt="">
<area shape="rect" coords="163,34,188,43" href="javascript:Farb('128', '0', '128')" alt="">
<area shape="rect" coords="190,34,215,43" href="javascript:Farb('128', '0', '255')" alt="">
<area shape="rect" coords="1,45,26,54" href="javascript:Farb('64', '0', '0')" alt="">
<area shape="rect" coords="28,45,53,54" href="javascript:Farb('128', '64', '0')" alt="">
<area shape="rect" coords="55,45,80,54" href="javascript:Farb('0', '64', '0')" alt="">
<area shape="rect" coords="82,45,107,54" href="javascript:Farb('0', '64', '64')" alt="">
<area shape="rect" coords="109,45,134,54" href="javascript:Farb('0', '0', '128')" alt="">
<area shape="rect" coords="136,45,161,54" href="javascript:Farb('0', '0', '64')" alt="">
<area shape="rect" coords="163,45,188,54" href="javascript:Farb('64', '0', '64')" alt="">
<area shape="rect" coords="190,45,215,54" href="javascript:Farb('64', '0', '128')" alt="">

<area shape="rect" coords="1,56,26,65" href="javascript:Farb('0', '0', '0')" alt="">
<area shape="rect" coords="28,56,53,65" href="javascript:Farb('128', '128', '0')" alt="">
<area shape="rect" coords="55,56,80,65" href="javascript:Farb('128', '128', '64')" alt="">
<area shape="rect" coords="82,56,107,65" href="javascript:Farb('128', '128', '128')" alt="">
<area shape="rect" coords="109,56,134,65" href="javascript:Farb('64', '128', '128')" alt="">
<area shape="rect" coords="136,56,161,65" href="javascript:Farb('192', '192', '192')" alt="">
<area shape="rect" coords="163,56,188,65" href="javascript:Farb('64', '64', '64')" alt="">
<area shape="rect" coords="190,56,215,65" href="javascript:Farb('255', '255', '255')" alt="">
</map>

<form name="Auswahl" action="">
<table><tr valign="top"><td><img src="images/farben.gif" vspace="3" alt="" usemap="#vgmap" border="0"></td>
<td><table><tr><td>Rot:</td><td><input type="text" name="VR" size="3"></td></tr>
<tr><td>Gr&uuml;n:</td><td><input type="text" name="VG" size="3"></td></tr>
<tr><td>Blau:</td><td><input type="text" name="VB" size="3"></td></tr></table>
</td><td>
HEX-Farbe: <input type="text" name="VHex" size="10"><br>
<input type="button" value="Farben anzeigen" onClick="Neu()" style="background-color:#FFEEDD; width:140px">
</td></tr></table>
</form>
</body>
</html> 