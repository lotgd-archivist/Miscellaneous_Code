
<?php
// Webfind
// Translation by Gargamel @ www.rabenthal.de
if (!isset($session)) exit();
require_once "func/increment_specialty.php";

if ($_GET[op]==""){
    output("`5Ein Stückchen entfernt sitzt ein Hellseher auf einem Baumstumpf. Er
    hat einige Kerzen angezündet und schon aus dieser Entfernung erkennst Du die
    grosse Kristallkugel, die vor ihm im Moos liegt.`0");
    //abschluss intro
    addnav("Zukunft erfragen","berge.php?op=ask");
    addnav("Weg weitergehen","berge.php?op=cont");
    $session[user][specialinc] = "fortune.php";
}
else if ($_GET[op]=="ask"){
    output("`5Du gehst zum Hellseher und folgst seiner einladenden Geste. Als Du
    ihm gegenüber sitzt, blickt er konzentriert in seine Kristallkugel und meint nach einiger Zeit ...`n`n`0");
    $fortune = e_rand(1,21);
    switch ($fortune) {
     case 1:
     output("\"Du wirst einen schlechten Tag haben!\"`n`n`0");
     $session[user][hitpoints]=1;
     $session[user][gold]-= e_rand(0,$session[user][gold]);
     $session[user][charm]-=1;
     break;
     case 2:
     output("\"Du wirst einen guten Tag haben!\"`n`n`0");
     $session[user][hitpoints]+=10;
     $session[user][gold]+=100;
     $session[user][charm]+=1;
     break;
     case 3:
     output("\"Du wirst viele Kinder haben!\"`n`n`0");
     $session[user][charm]+=1;
     break;
     case 4:
     output("\"Du bist komisch und wirst alberne Kleider tragen!\"`n`n`0");
     if ($session[user][hitpoints] > 1) $session[user][hitpoints]-=1;
     break;
     case 5:
     output("\"Du wirst einmal reich sein!\"`n`n`0");
     $session[user][gold]+=1000;
     break;
     case 6:
     output("\"Du teilst das Schicksal einer armen Kirchenmaus!\"`n`n`0");
     $session[user][gold]=0;
     break;
     case 7:
     output("\"Du wirst noch einen Kampf austragen!\"`n`n`0");
     $session[user][turns]+=1;
     break;
     case 8:
     output("\"Du wirst nicht mehr so gut kämpfen können!\"`n`n`0");
     $session[user][turns]-=1;
     break;
     case 9:
     output("\"Du wirst einen Edelstein finden!\"`n`n`0");
     $session[user][gems]+=1;
     break;
     case 10:
     output("\"Du wirst einen Edelstein verlieren!\"`n`n`0");
     $session[user][gems]-=1;
     break;
     case 11:
     output("\"Deine Schönheit wird noch zunehmen!\"`n`n`0");
     $session[user][charm]+=1;
     break;
     case 12:
     output("\"Dein Leben wird kurz sein!\"`n`n`0");
     $session[user][hitpoints]=1;
     break;
     case 13:
     output("\"Du wirst ein langes Leben führen!\"`n`n`0");
     $session[user][hitpoints]+=200;
     break;
     case 14:
     output("\"Du wirst Dich schlecht fühlen!\"`n`n`0");
     $session[user][hitpoints]=1;
     break;
     case 15:
     output("\"Du wirst sehr ausgelassen sein!\"`n`n`0");
     $session[user][drunkenness]+=80;
     break;
     case 16:
     output("\"Du wirst stärker werden!\"`n`n`0");
     $session[user][maxhitpoints]+=1;
     break;
     case 17:
     output("\"Du wirst schwächer werden!\"`n`n`0");
     $session[user][maxhitpoints]-=1;
     break;
     case 18:
     output("\"Du wirst guter Gesundheit sein!\"`n`n`0");
     $session[user][hitpoints]+=50;
     break;
     case 19:
     output("\"Du wirst unter Deiner Gesundheit leiden!\"`n`n`0");
     $von = round($session[user][hitpoints]*.5);
     $session[user][hitpoints]-= e_rand($von,$session[user][hitpoints]);
     break;
     case 20:
     output("\"Du wirst eine Kampfeslust spüren!\"`n`n`0");
     $session[user][turns]+=1;
     break;
     case 21:
     output("\"Du wirst Deine Kampfeslust verlieren!\"`n`n`0");
     $session[user][turns]+=0;
     break;
    }
    $session[user][specialinc]="";
}

else if ($_GET[op]=="flowers"){   // Waldblumen pflücken
    // Current balance:
    // 30% -2 turn
    // 30% +1 turn and healing
    // 30% nothing
    // 10% + 1 specialty
    output("`nDu pflückst Dir einen Straus der seltenen Waldblumen und riechst
    voller Freunde dran. Plötzlich merkst Du, dass die Blumen nicht nur hübsch
    aussehen, sondern auch eine recht `9eigenartige`0 Wirkung auf Dich haben.`0");
    switch(e_rand(1,10)){
        case 1:
        case 2:
        case 3:
        output("`n`nDu fühlst Dich plötzlich matt und etwas benebelt. Du must
        Dich etwas ausruhen und `3verlierst deswegen 2 Waldkämpfe.`0");
        $session[user][turns]-=2;
        break;
        case 4:
        case 5:
        case 6:
        output("`n`nDu fühlst Dich belebt und machst Dich voller Abenteuerlust
        wieder auf den Weg. `3Du hast einen zusätzlichen Waldkampf erhalten und
        bist vollständig gesund.`0");
        $session[user][turns]+=1;
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
            $session[user][hitpoints]=$session[user][maxhitpoints];
        break;
        case 7:
        case 8:
        case 9:
        output("`n`nOder hast Du Dich getäuscht? Nein, von einer seltsamen Wirkung
        spürst Du `9nichts`0 mehr. `3Du gehst mit Deinen Blumen weiter.`0");
        break;
        case 10:
        output("`n`n ");
        increment_specialty();
        break;
        }
    $session[user][specialinc]="";
}
else if ($_GET[op]=="cont"){   // einfach weitergehen
    output("`5Du macht einen kleinen Bogen um den Hellseher und gehst weiter. Quatschkopf ...");
    $session[user][specialinc]="";
}
?>

