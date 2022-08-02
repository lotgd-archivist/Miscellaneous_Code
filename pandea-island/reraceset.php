<?php

require_once "common.php";

page_header("Rasse neu Setzen");

$session[user][race]="Echsenwesen";

output("deine Rasse wurde auf Echsenwesen gesetzt");

addnav("Zurück zum Dorfplatz","village.php");

page_footer();
?>