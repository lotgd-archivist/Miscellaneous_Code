<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Die Taube
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);




$session['user']['specialinc']="";
        output("`&`n`nNichts Böses ahnend spazierst du durch den Wald und lauscht lächelnd den Gesängen der Vögel. Du scheinst Frieden mit dir und deiner Umgebung geschlossen zu haben als du plötzlich etwas auf dich herabtropfen spürst. Verwundert wandert dein Blick gen Himmel, du kannst noch sehen wie etwas auf dich herabfällt, doch zum ausweichen ist es zu spät! Die Taube hat dich direkt am Auge erwischt. Du verlierst einen Charmepunkt.  `n`n");
$session[user][charm]-=1;
addnav("Zurück in den Wald","forest.php");

?> 