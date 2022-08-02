<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Sílanna – Das Waldviertel"); 
output("`)`c`b`kDer `qS`Uo`Mn`^n`Ûe`Ôn`Ûp`^l`Ma`Ut`qz`b`c`k"); 
addnav("Zum Brunnenplatz","village.php");
addnav("Wald","forest.php");
if (@file_exists("houses.php")) addnav("Wohnviertel","houses.php?location=3");

addnav("Kamorans Ställe","stables.php");
if (@file_exists("lodge.php"))    addnav("J?Jägerhütte","lodge.php");
addnav("Zur Sternendeuterin","minx.php");
//addnav("Krankenhaus","nuvola_krankenhaus.php");
addnav("Krankenhaus","");
addnav("G?Der Garten", "gardens.php");
addnav("F?Seltsamer Felsen", "rock.php");


if ($_GET[op]==""){
output("`c`n`n`n<table><tr><td><img src='./images/silanna.png'></td></tr></table>`c`n", true);
output("`n`n");
output ("`n`c`kZwischen Nuvola und Essos befindet sich der grüne Stadtteil Sílanna, dessen bewaldete Ausläufer noch über die Stadtmauern hinausreichen. 
`nEin eher unauffälliges Tor in der Stadtmauer soll dazu dienen, bei akuter Gefahr aus der Stadt fliehen zu können. 
`nIn Friedenszeiten wird dieses aber eher dazu genutzt, um in die Tiefen des Waldes mit seinen bezaubernden Lichtungen zu gelangen.
`nInnerhalb der Stadtmauern geht es eher geschäftsmäßig zu und auch hier weiß dieser Stadtteil mit seiner einzigartigen Flora und Fauna zu punkten.
  `c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("silanna_village");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 