<?php

//09112005
/* *******************
Codierung von Ray
Ideen von Ray
ICQ:230406044
++++++++++++++++++++++++++++++++++++
inhaltlich editiert und bereinigt.
23.09.05 by -DoM http://logd.gloth.org/
******************* */

require_once"common.php";
page_header("Der Barbier");

if ($_GET['op']==""){
output("`#Du gehst ein wenig auf der Dorfmarkt umher und entdeckst plötzlich einen Barbier. Du denkst dir das es nicht schaden könnte, sich ein wenig pflegen zu lassen. Was willst du machen lassen?`n`n");

addnav("Schöner werden");
addnav("R?Einmal Rasieren - `^8000 Gold`0","barbier.php?op=gu");
addnav("N?Nasenhaare stutzen lassen - `^4000 Gold`0","barbier.php?op=ma");
addnav("Sich verunstalten lassen");
addnav("V?Von einem Affen die Haare schneiden lassen ","barbier.php?op=azubi");
addnav("Z?Zurück zum Dorfmarkt","center.php");


}
else if ($_GET['op']=="gu"){
if ($session['user']['gold']>7999){
    output("Du bezahlst `^8000 `$Gold");
    output("`#Du gehst schonmal auf deinen platz und machst es dir gemütlich, da das sowieso dauert machst du ein kleines Nickerchen.");
    output("`#Nach einigen Stunden Erhohlsamen schlafes wachst du schließlich auf und merkst das die Rasur wirklich nötig war.`n`n`&Du wirkst Charmanter und bekommst 2 Charmepunkte dazu.");
    $session['user']['charm']+=2;
    $session['user']['gold']-=8000;
    addnav("Z?Zurück zum Dorfmarkt","center.php");
    }else{
    output("`#Du hast nicht genügend Gold.");
    addnav("Z?Zurück zum Dorfmarkt","center.php");
    }

}
else if ($_GET['op']=="ma"){
if ($session['user']['gold']>3999){
    output("`#Du entschließt dich dazu deine wuchernden Nasenhaare trimmen zu lassen, du gehst zu deinen platz und wartest auf den Barbier..... Du wartest ewig aber es kommt keiner. Als du gerade aufspringen willst, um auf den Tisch zu hauen, biegt der Barbier um die Ecke..");
    output("Es ärgert dich bereits das er sich so sehr verspätet hat, also bittest du ihn direkt anzufangen.`n`n");
    output("`&Du wirkst Charmanter und bekommst 1 Charmepunkt dazu.");
    $session['user']['charm']+=1;
    $session['user']['gold']-=4000;
    addnav("Z?Zurück zum Dorfmarkt","center.php");
    }else{
    output("`#Du hast nicht genügend Gold.");
    addnav("Z?Zurück zum Dorfmarkt","center.php");
    }
}
else if ($_GET['op']=="azubi"){
    output("`#Du lässt dir von einem Affen die Haare schneiden....nur wie es aussieht muss er noch viel lernen du siehst total verunstaltet aus damit sinkt dein Charme um 1 Punkt....  Aber einen vorteil gibts, du hast dir gerade `^1500 `# Gold verdient.");
    $session['user']['charm']-=1;
    $session['user']['gold']+=1500;
    addnav("Z?Zurück zum Dorfmarkt","center.php");
    }


page_footer();
?> 