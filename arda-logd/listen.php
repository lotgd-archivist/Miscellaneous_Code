<?php

require_once "common.php";
checkday();

addnav("zurück","village.php");
//addnav("Bewerbungen schreiben", "bewerbung.php?op=submit",false,true);

page_header("Listen");

output("`cListen`nHier aufgeführt sind offizielle Jobs, die nur mit zustimmung des Teams erlangt werden können, sowie die ofizielle Götterliste von Arda.`n`c");
output("`nGötterliste:`n`n
        Sanguin        -    Gott des Lebens und des Wachstums`n
        Rui            -    Göttin des Todes, der Ruhe und der Seelenheilung`n
        Narjana        -    Göttin des Chaos, der Veränderung und des Wahnsinns`n
        Viconia        -    Göttin des Glücks und des Pechs`n
        Lilith        -    Göttin des Neuanfangs, der Gesundheit und der Liebe`n
        Aliyanna    -    Göttin des Feuers (Elementargöttin)`n
        Lea            -    Göttin des Wetters (Elementargöttin?)`n
        Thor        -    Gott des Donners und des Krieges`n`n`n`n");
output("Priester:`n`n
        Heilerin Fenya    -    Priesterin des Lebensgottes`n`n`n`n");
output("Heiler:`n`n
        Heilerin Fenya`n`n`n`n");
output("Stadtwache:`n`n
        Noch keiner`n`n`n`n");
output("Richter:`n`n
        noch keiner`n`n`n`n");
output("Zwielichtige Gestalten:`n`n
        Ximena    -    Momentane Anführerin der Zwielichtigen`n`n`n`n");
        
output("Falls interesse besteht könnt ihr euch auf die diversen noch offenen Ämter bewerben. Allerdings erwarten wir dann eine schlüssige Geschichte, sowie regelmäßige Anwesenheit, sonst hats keinen sinn.`n
        Schreibt eure Bewerbungen einfach unter den normalen Anfragen`n");        
        
page_footer();
?>        