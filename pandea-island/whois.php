<?php
require_once "common.php";
output("`n`QAnwesend:`0");
$ort=$session[user][whereis];
$sql="SELECT * FROM accounts WHERE whereis = '".$ort."'";
$result = db_query($sql) or die(db_error(LINK));
$text="";
for($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($text=="") $text="".$row['name'].",";
    if ($text!="") $text.="".$row['name'].",";
}
output($text);
?>