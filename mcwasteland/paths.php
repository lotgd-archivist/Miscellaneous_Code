
<?php
// includes specials for normal use
// idea by Durandil

require_once("common.php");

if ($_GET['ziel']=="") redirect("village.php");

checkday();
page_header("Etwas Besonderes");
addcommentary();
$pfad="special/".urldecode($_GET['ziel']);
if (!strpos($pfad,".php")) $pfad.=".php";
include("$pfad");

page_footer();
?>


