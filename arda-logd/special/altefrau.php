<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Die Alte Frau
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////

if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);




$session['user']['specialinc']="";
switch(e_rand(1,2)){
case '1':
        output("`c`n`n`pDie Bäume teilen sich wie ein Tor als eine `Ialte`p gebückt aussehende Frau heraus tritt.`n
All ihre Schritte beweisen das offensichtlich hohe Alter wie auch die unzählbar vielen Furchen und Falten im Gesicht der Frau.`n
Kalt mustern dich die Augen des gebückten Weibes, das in alte zerschlissene Kleidung gehüllt ist.`n
Ehe du auch nur Zeit gefunden hast zurück zu weichen bewirft sie dich schon mit etwas!`n
Verwundert blickst du an dir herab, als du bemerkst das es `IReis`p ist mit dem sie dich bewirft. `n
Noch während du dich fragst was das alte Weib damit bezwecken will ist sie verschwunden.`n
`UDu bekommst drei Charmepunkte!`n`n`c");
$session[user][charm]+=3;
break;
case '2':
        output("`c`n`n`pDie Bäume teilen sich wie ein Tor als eine `Ialte`p gebückt aussehende Frau heraus tritt.`n
All ihre Schritte beweisen das offensichtlich hohe Alter wie auch die unzählbar vielen Furchen und Falten im Gesicht der Frau.`n
Kalt mustern dich die Augen des gebückten Weibes, das in alte zerschlissene Kleidung gehüllt ist.`n
Ehe du auch nur Zeit gefunden hast zurück zu weichen bewirft sie dich schon mit etwas!`n
Verwundert blickst du an dir herab, als du bemerkst das es `ISalz`p ist mit dem sie dich bewirft.`n
Noch während du dich fragst was das alte Weib damit bezwecken will ist sie verschwunden.`n
`UDu verlierst drei Charmepunkte!`n`n`c");
$session[user][charm]-=3;
break;
}
addnav("Zurück in den Wald","forest.php");

?> 