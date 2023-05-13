
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Mysteriöses Eck");
$session['user']['standort']="Mysteriöses Eck";


addnav("Dorfschmiede","dorfschmiede.php");
addnav("Zum Marktplatz","marktplatz.php");
addnav("Zum Rathausplatz","rathausplatz.php");

$out="`T`b`cMysteriöses Eck`b`c`n`n";
$out.="Nanu, wo bist du denn jetzt gelandet? Ziemlich dunkel ist es hier, eigentlich wolltest du ja zum Dorfplatz Alvions ";
$out.="zurück, aber jetzt macht dich diese Ecke doch neugierig, und du willst sie genauer untersuchen, also gehst du ";
$out.="vorsichtig weiter.`n`n";
$out.="Mal schauen, was sich hier vielleicht noch Interessantes verbirgt!`n`n";

// $out.="Gerade, als du den Marktplatz wieder verlassen willst in der Meinung, du hast hier ja nun Alles gesehen und erkundet, fällt die eine etwas dustere Ecke auf. ";
// $out.="Neugierig gehst du darauf zu und stellst fest, dass da eine Ecke ist zwischen zwei Gebäuden. Was sich dort wohl noch Interessantes verbergen mag?`n`n";
// $out.="Du möchtest das genauer wissen und gehst hinein.`n`n";

output($out,true);
viewcommentary("mysterioeses_eck","Hinzufügen",25,"sagt",1,1);

page_footer();

