<?php
//Erstellt von Tidus (www.kokoto.de)
//Copyright by Tidus und darf nicht Entfernt werden.
//Darf nur verwendet werden wenn der Source einsehbar ist :P (hinzugefügt am 01.02.2009)
require_once "common.php";
addcommentary();
page_header ("Mystischer See");

output("`&Du kommst an den `~Mystischen See`& an dem es immer Dunkel ist da ein Zauber auf ihm liegt, der Mond und die Sterne strahlen auf den See. Es liegt eine Mystisch Romantische Stimmung in der Luft, hier wäre der Perfekte ort um mit deiner Freundin/ deinem Freund zu reden. Auf dem `~Mystischen See`& liegt ein zauberhafter schimmer... `n`n `$ Eine Fee erinnert dich daran das hier Rollenspiel pflicht herrscht. `n`n`n");
if($session['user']['marriedto']>0) {
    if($session['user']['sex']==0) { 
        $male=$session['user']['acctid']; 
        $female=$session['user']['marriedto']; 
    } else { 
        $male=$session['user']['marriedto']; 
        $female=$session['user']['acctid']; 
    } 
    $gesammt="mystischersee_".$male."_".$female; 
    viewcommentary($gesammt,"Mit deinem Schatz reden",10,"Flüstert"); 
}else if($session['user']['liebe']>1) {
    if($session['user']['sex']==0) { 
        $male=$session['user']['acctid']; 
        $female=$session['user']['partner']; 
    } else { 
        $male=$session['user']['partner']; 
        $female=$session['user']['acctid']; 
    } 
    $gesammt="mystischersee_".$male."_".$female; 
    viewcommentary($gesammt,"Mit deinem Schatz reden",10,"Flüstert"); 
}else{
output("Da auf dem `~Mystischen See`& ein Zauber liegt können ihn nur diejenigen betreten die Verheiratet sind oder kurz vor der Vermählung stehen.");
}
addnav("Dorfplatz","village.php");
page_footer();
?>