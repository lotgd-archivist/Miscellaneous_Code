<?php
require_once "common.php";
page_header("Kutschenstation");
switch($_GET['op'])
{

        case 'gratis':
        {
    if ($session['user']['turns']>=1) {
    output("`tDu entscheidest dich, nichts auszugeben. Aber dafür bist du auch einige Zeit unterwegs und brauchst so eine Runde.");
    addnav("Zylyma","zylyma-reise2.php");
    $session[user][turns]-=1;
 
    } else { 
    output("`4Leider bist du schon zu müde, um den Weg heute noch zu machen. Daher machst du dich auf den doch kurzen Weg nach Arda.");
    addnav("nach Arda","dorftor.php"); 
    }
    break;
    }
    case 'geld':
    {    
    if ($session['user']['gold']>=50) {
    output("Du bezahlst die 50 Gold und kommst so komfortabel an, daß du erholt bist.");
    $session['user']['gold']-=50;
    addnav("Zylyma","zylyma-reise2.php");
    } else {
    output("`4Leider hast du nicht genug Gold dabei, deshalb wirft der Kutscher dich am Dorftor von Arda raus.");
    $session['user']['hitpoints']*0.98;
    addnav("nach Arda","dorftor.php"); 
    }
    break;
    }

default:
{

output("Hier kannst du auswählen, wie du reisen willst.`n`n");
if ($session['user']['level']==1) {
output("`6Der Kutscher hat Mitleid mit dir und meint, da du so pimpfig bist, nimmt er dich kostenlos mit.");
addnav("Zylyma","zylyma-reise2.php"); 
} else {
    


    addnav("Kostenlos reisen","zylyma-reise.php?op=gratis");
    addnav("Für Geld reisen","zylyma-reise.php?op=geld");
}
break;
}
}
page_footer();
?>