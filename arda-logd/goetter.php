<?php

//Turm der Götter

require_once "common.php";
addcommentary();

page_header("Turm der Götter");

addnav("Turm");
addnav("Im Turm übernachten","login.php?op=logout",true);
addnav("Wege");
addnav("Zurück teleportieren","turm.php");


output("`^`c`bTurm der Götter`c`bDu teleportierst dich in den Turm der Götter. Als du oben angelangt bist schaust du dich um was es hier alles gibt. Du siehst das kleine Portal was dich wieder zurück zum Turm der Elemente bringt, und viele andere Sachen. Was wirst du tun?`n`n`5Die Krieger unterhalten sich:`n");


viewcommentary("götter","Hinzufügen",15);
page_footer();
?> 