
<?php

require_once("common.php");

page_header("Navfix");
$sql = "UPDATE accounts SET allowednavs='',output='' WHERE acctid=$_GET[userid]";
addnav("Weiter","list.php");
//echo "<a href='village.php'>Deine erlaubten Navs waren beschädigt. Zurück zum Dorf.</a>";
page_footer();
?>

