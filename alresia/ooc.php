<?php
require_once "common.php";
isnewday(2);

addcommentary();
page_header("Blob");
output("`n`n`n`n");
viewcommentary("ooc",200,"sagt");
addnav("Ältestenrat","superuser.php");


$_SESSION['session']['user']['standort'] = "Ältestenrat"; 



page_footer();
?> 