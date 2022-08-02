<?php
/*
Brunnen by dso 10/2004
*/

require_once("common.php");
addcommentary();
checkday();

page_header("Der kleine Dorfbrunnen");

addnav("Zum Dorf","village.php");

    output('`@Als du dich über den Brunnen beugst, kannst du nur ein Stückweit in seine nachtschwarze Tiefe blicken. Von weit entfernt magst du ein leises Plätschern vernehmen. Sofort wird dir klar, dass was dort hineinfällt nie wieder gesehen wird. Du überlegst, ob du dir wirklich sicher bist, dich dort deiner Dinge zu entledigen.');


output('`n`n`@Um den Brunnen herum stehen einige Leute.`n');
viewcommentary("well","Mit Umstehenden reden:",25,"sagt");

page_footer();
?> 