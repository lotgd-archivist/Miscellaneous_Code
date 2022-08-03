<?php

/*
Superuser können mit diesem Editor auf alle aktiven Spielorte zugreifen und Kommentare lesen/schreiben/löschen 

-- benötigt zusätzlich placelist.php (Hauptdatei) !!! ---

Die Einbauanleitung ist in der placelist.php

Autor:   Blackfin
Datum: Oktober 2005 
Email:   blackfin@elfenherz.de
Für:       http://logd.elfenherz.de
*/

require_once "common.php";
isnewday(2);
addcommentary();


if($_GET[which]!="") {

    $plac = $_GET[which] ;
    $schreib = $_GET[schreiben] ;


    if($schreib == 1) { 
        page_header("Auf  \"$plac\" schreiben") ;
        viewcommentary($plac,"Hinzufügen:",20);
    }        
          else  {         
              page_header(" \"$plac\" Bereinigen") ;
              viewcommentary($plac,"X",20);
    
    }

}else {
    page_header("Unbekannter Ort") ;
}

addnav("W?Zurück zum Weltlichen","village.php");
addnav("-----") ;
addnav("Neuen Ort auswählen","placelist.php?op=viewcom");
addnav("-----") ;
addnav("G?Zurück zur Grotte","superuser.php");


page_footer() ;


?> 