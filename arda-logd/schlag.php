<?php

// Schmiede Erweiterung
// Idee von Des
// erstellt by Tweety
// 27052005

require_once "common.php";
checkday();
page_header("Beschlagen lassen");

addcommentary();

if ($_GET[op] == ""){
        output("`b`c`qDie Schmiede`0`c`b");
        output("`n`^Du hast beschlossen das dir deine Waffe/Rüstung zu Schwach ist und fragst den Schmied ob er deine Waffe/Rüstung beschlagen könnte. Der Schmied schaut dich an und sagt `q''Ich könnte aber das kostet dich natürlich etwas''`0");
        output("`n");
    output("`n`QWas möchtest Du machen?`0");
    output("`n`n");

        viewcommentary("schlagen","Hinzufügen",15);


    addnav("Beschlagen lassen");
    addnav("Waffe beschlagen lassen `^10000`0gold `^1`0Edelstein","schlag.php?op=waffe");
    addnav("Rüstung beschlagen lassen `^10000`0gold `^1`0Edelstein","schlag.php?op=armor");
    addnav("Sonstiges");
    addnav("Zurück","xshop.php");

    }

    else if ($HTTP_GET_VARS[op] == "waffe") {
    page_header("Waffe beschlagen");
        if ($session['user']['weapondmg'] < 67) {
    output("`c`b`&Der Schmied am Arbeiten`0`b`c");
    output("`n`@Du hast dich entschlossen deine Waffe machen zu lassen. `n`n");
    output("`n`9Du bezahlst dem Schmied seine 10000 gold und seinen Edelstein und staunst Faziniert über das was der Schmied mit deiner Waffe macht!`n`2");
    $session['user']['gold']-=10000;
    $session['user']['gems']-=1;
    switch (e_rand(1,11)){
         case 1:
         case 2:
         case 3:
         case 4:
         case 5:
         case 6:
         case 7:
         case 8:
         case 9:
        output("Zufrieden schaust du dir deine Waffe an. Lächelst den Schmied an. Gute Arbeit Danke.");
         $session['user']['weapondmg']++;
        $session[user][attack]++;
         addnav("Zurück","xshop.php");
         break;
         case 10:
         case 11:
         output("Du schaust dem Schmied genau auf die Finger da passiert es auch schon. KNACKS. Deine Waffe ist Kaputt.");
         $session[user][attack]--;
         $session['user']['weapondmg']--;
         addnav("Zurück","xshop.php");
         break;
         }
        }else{
        output("`n`@Der Schmied sieht sich deine Waffe genau an und gibt sie dir wieder zurück. `qLeider kann ich da nichts mehr machen...");
        addnav("Zurück","xshop.php");
        }
         }else if ($HTTP_GET_VARS[op] == "armor") {
    page_header("Rüstung beschlagen");
    if ($session['user']['armordef'] < 67) {
    output("`c`b`&Der Schmied am Arbeiten`0`b`c");
    output("`n`@Du hast dich entschlossen deine Rüstung machen zu lassen. `n`n");
    output("`n`9Du bezahlst dem Schmied seine 10000 gold und seinen Edelstein und staunst Faziniert über das was der Schmied mit deiner Rüstung macht!`n`2");
    $session['user']['gold']-=10000;
    $session['user']['gems']-=1;
    switch (e_rand(1,11)){
         case 1:
         case 2:
         case 3:
         case 4:
         case 5:
         case 6:
         case 7:
        case 8:
         case 9:
        output("Zufrieden schaust du dir deine Rüstung an. Lächelst den Schmied an. Gute Arbeit Danke.");
         $session[user][defence]++;
        $session['user']['armordef']++;
         addnav("Zurück","xshop.php");
         break;
         case 10:
         case 11:
         output("Du schaust dem Schmied genau auf die Finger da passiert es auch schon. KNACKS. Deine Rüstung ist Kaputt.");
        $session['user']['armordef']--;
         $session[user][defence]--;
         addnav("Zurück","xshop.php");
         break;
        }
        }else{
        output("`n`@Der Schmied sieht sich deine Rüstung genau an und gibt sie dir wieder zurück. `qLeider kann ich da nichts mehr machen...");
        addnav("Zurück","xshop.php");
        }
        }
        page_footer();

?> 