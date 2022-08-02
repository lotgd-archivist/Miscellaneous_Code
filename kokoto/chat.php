<?php
require_once "common.php";
addcommentary();
popup_header("Chat");

output('`$Hier ist der Offtopic bereich, Hier könnt ihr über das Richtige Leben etc. schreiben, aber Bleidigungen und Spamm werden dennoch nicht geduldet. ;)`n`n');
viewcommentary("offtopic","",10);
popup_footer();
?>