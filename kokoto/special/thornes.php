<?php
// idea of gargamel @ www.rabenthal.de
// small textchanges by Tidus (www.kokoto.de)
if (!isset($session)) exit(); 

    output("`n`%Du erreichst einen Teil des Waldes, in dem dornige Büsche wuchern.`0 Du 
    weisst, dass es nun nicht angenehm wird, aber Du musst da durch....`0"); 
    switch (mt_rand(1,2)) { 
        case 1: // Turn weg 
        output("`n`nDu kämpfst Dich mit Deiner Waffe ".$session['user']['weapon']." durch 
        das widerspenstige Unterholz. `n`3Du kommst nur langsam voran und verlierst dadurch 
        `#einen Waldkampf.`0"); 
        $session['user']['turns']--; 
        break; 
         
        case 2: // Gesundheit weg 
        $hpweg = round($session['user']['hitpoints']  0.33); 
        output("`n`nDu kämpfst Dich durch das dornige Gestrüpp und kommst gut voran, 
        leider schützt Dich Deine Rüstung ".$session['user']['armor']." nicht ganz so gut, 
        wie es Dir versprochen wurde.`0 `n`n`3Die Wunden kosten Dich `#$hpweg Lebenspunkte.`0"); 
        $session['user']['hitpoints']-=$hpweg; 
        break; 
    } 
    //abschluss 
    $session['user']['specialinc'] = ''; 

?>