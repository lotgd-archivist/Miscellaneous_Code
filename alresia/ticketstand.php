<?php
header('Content-Type: text/html; charset=utf-8');
// 1508004

require_once "common.php";
addcommentary();

    checkday();
    page_header("Ticketstand");
    output("`q`n`n`c`bTicketstand`b`n`n`c");
    output("`kMitten auf dem Brunnenplatz steht ein Stand, um den sich eine kleine Schar verschiedener Personen versammelt. Eine junge Dryade verkauft hier die heißbegehrten Karten zur Krönungszeremonie. Wohl sind die Karten durchaus teuer, doch sollen die Einnahmen zugunsten der Sanierungsarbeiten für die Einrichtungen der Stadt genutzt werden
    `nAußerdem lässt sich niemand eine Krönung entgehen, weil man nie wissen kann, wann man nochmal die Chance dazu hätte.
    `n`nViele Karten sind nicht mehr verfügbar und lange würde es nicht mehr dauern, bis auch die letzte vergriffen wäre. Möchtest du deine Chance nicht jetzt ergreifen, bevor es zu spät ist? 
    `n
    `nMomentan hat sie noch `Q".getsetting("selledtickets",0)."`k Karten auf Lager.`n`n`n");
    
    /*$cost = $session[user][gold] > 15000;
$tickets=array(1=>1);


    addnav("Karten");
    if ($session['user']['gold'] > 15000) addnav("Eintrittskarte zur Krönung - 15000 Gold","ticketstand.php?op=buy&op2=ticket");

    
    if ($session[user][gold] > 15000){
        output("<a href=\"ticketstand.php?op=buy&op2=ticket\">Eintrittskarte zur Krönung - 15000 Gold</a><br>",true);
        addnav("","ticketstand.php?op=buy&op2=ticket");
    }
    
        
        if ($_GET[op2]=="ticket"){
        $ticket="Eintrittskarte";
        db_query("INSERT INTO items (name,owner,class,gold,description) VALUES ('Eintrittskarte zur Krönung','Ticket',12,'Eine Eintrittskarte zur Krönungszeremonie')");
        $session[user][gold]-=15000;
    }else{
        
    
    
    
    addnav("Zurück");*/
    addnav("Zurück zum Dorf","village.php");
//}




page_footer();
?> 