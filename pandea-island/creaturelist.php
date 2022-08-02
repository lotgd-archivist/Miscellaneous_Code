<?php
require_once "common.php";

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Gegnerliste</title>
<style type="text/css">
<!--
body {
    font-family: Tahoma, sans-serif;
    font-size: 80%;
    color: #FFFFFF;
    background-color: #352D20;
}
h3 {
    text-align: center;
}
table {
    margin-left: auto;
    margin-right: auto;
    border: 1px solid black;
    color: #FFFFFF;
}
th {
    background-color: #000000;
}
tr.dark {
    background-color: #000000;
}
tr.light {
    background-color: #330000;
}
img.noborder {
    border-style: none;
}
-->
</style>

</head>
<body>
<h3>Auflistung aller Ingame-Gegner<br /></h3>
<table cellspacing="0" cellpadding="4" summary="Beinhaltet die Auflistung aller Ingame-Gegner">
  <tr>
    <th>
      Level
    </th>
    <th>
      Kreaturname
    </th>
    <th>
      Kreaturwaffe
    </th>
    <th>
      Spruch beim Ableben
    </th>
    <th>
      Friedhof
    </th>
  </tr>
<?php

$sql = "SELECT creaturename,creaturelevel,creatureweapon,creaturelose,location FROM creatures WHERE 1 ORDER BY creaturelevel";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    echo "  <tr class=\"".($i % 2==0?"light":"dark")."\">\r\n";
    echo "    <td>\r\n      ".$row['creaturelevel']."\r\n    </td>\r\n";
    echo "    <td>\r\n      ".HTMLEntities($row['creaturename'])."\r\n    </td>\r\n";
    echo "    <td>\r\n      ".HTMLEntities($row['creatureweapon'])."\r\n    </td>\r\n";
    echo "    <td>\r\n      ".HTMLEntities($row['creaturelose'])."\r\n    </td>\r\n";
    echo "    <td>\r\n      ".($row['location']?"Ja":"Nein")."\r\n    </td>\r\n";
    echo "  </tr>\r\n";
}
?>
</table>
<p>
  <br />Dieses Dokument ist:
  <!-- picture from www.antipixel.com -->
  <a href="http://validator.w3.org/check/referer"><img src="images/valid_xhtml.gif" alt="valid XHTML 1.0" class="noborder" style="vertical-align:middle" /></a>
  <!-- picture from www.antipixel.com -->
</p>
</body>
</html>