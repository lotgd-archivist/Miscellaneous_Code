<?php
// script vom portas
require_once("common.php");
page_header("cardgen");
$sql="ALTER TABLE accounts ADD maxcardhouse int(4) NOT NULL default '0';";
db_query($sql);
$sql="ALTER TABLE accounts ADD cardhouseallowed tinyint(4) NOT NULL default '0';";
db_query($sql);
$sql="ALTER TABLE accounts ADD cardhouse int(4) NOT NULL default '0';";
db_query($sql);
output("Tabellen wurden modifiziert");
addnav("Dorfplatz","village.php");
page_footer();
?>
