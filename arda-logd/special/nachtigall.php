<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Die Nachtigall
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////

if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);




$session['user']['specialinc']="";
        output("`&`n`nEs ist schon recht spät als du tief in Gedanken versunken durch den Wald gehst. Die Gesänge der Vögel verfolgen dich bei deinen Schritten, doch dann fällt dir eine ganz besondere Stimme unter den vielen auf.
Langsam schleichst du dich heran und kannst so die Nachtigall erblicken, die mit lieblichem Klang ihr Lied webt. Durch die Schönheit des Gesanges gewinnst du einen Charmepunkt!`n`n");
$session[user][charm]+=1;
addnav("Zurück in den Wald","forest.php");

?> 