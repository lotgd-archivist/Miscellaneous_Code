
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    $hp = round($session[user][hitpoints] * 0.1);
    output("`nPlötzllich tippt Dir jemand von hinten auf die Schulter. Du bekommst
    fast einen Herzinfarkt, was leider Deine Gesundheit um `5$hp`0 Punkte schwinden
    lässt. Du bewegst Dich nicht, versuchst aber aus den Augenwinkeln etwas zu
    erhaschen. Du siehst nur einen `2grünen Schatten`0 und denkst sofort an den grausamen
    Drachen. Du bist Dir sicher, dass Du Dein Leben ausgehaucht hast. Sollst Du Dich
    noch umdrehen?`0");
    $session[user][hitpoints]-= $hp;
    //abschluss intro
    addnav("furchtsam umdrehen","forest.php?op=turn");
    addnav("lieber sterben","forest.php?op=die");
    addnav("fliehen","forest.php?op=run");
    $session[user][specialinc] = "goblin.php";
}
else if ($HTTP_GET_VARS[op]=="run"){   // fliehen
    $text = "";
    if ( $session[user][gold] > 0 ) $text = ". Auf der wilden Flucht über Stock und Stein verlierst Du `%all Dein Gold";
    if ( $session[user][gems] > 0 ) {
        if ( $text == "" ) $text = ". Auf der wilden Flucht über Stock und Stein verlierst Du `% alle Deine Edelsteine";
        else $text." und alle Deine Edelsteine";
    }
    $text = $text.".`0";
    output("`n".($session[user][sex]?"Du ängstliches Häschen":"Du feige Memme").
    " nimmst die Beine in die Hand und fliehst vor der Gefahr. Du bemerkst, dass Dich
    etwas `2grünes`0 verfolgt$text Aber Du kommst mit dem Leben davon.`0");
    $session[user][gold] = 0;
    $session[user][gems] = 0;
}
else if ($HTTP_GET_VARS[op]=="die"){   // sterben
    output("`nDu hast eine wahnsinnige Angst, der `2grüne Schatten`0 könnte Dir wehtun.
    Um allen Schmerzen zu entgehen nimmst Du lieber Deine Waffe ".$session[user][weapon]."
    und bringst Dich selbst um.`n
    `QRamius wird diese Art des Todes gar nicht mögen - und lässt Dich drei mal weniger Seelen
    quälen.`0");
    $session[user][alive]=false;
    $session[user][gold]=0;
    $session[user][hitpoints]=0;
    $session[user][gravefights]-=3;
    addnews("`%".$session[user][name]."`5 hat den Tod gewählt, statt mit dem `2Grünen`5 zu kämpfen!");
    addnav("Tägliche News","news.php");
}
else if ($HTTP_GET_VARS[op]=="turn"){   // umdrehen
    output("`nDu bist erleichtert, als Du einen `2grünen Goblin`0 siehst - auch wenn er
    eine Keule in der Hand hat. Du wunderst Dich noch, weil seine Keule auch `2grün`0 ist.
    `n`n`@Und schon zieht er Dir eine mit seiner Keule über.`0
    `n`nSeltsamerweise spürst Du keinen Schmerz, Du fühlst keine Wunde und Du bist
    Dir auch ganz sicher, dass Du noch lebst. Was geht hier vor?`n`n\""
    .($session[user][sex]?"Liebe ":"Lieber ").$session[user][name].", mehr Gutes kann
    ich Dir nicht tun\" sagt der `2grüne Goblin`0.`n`n\"Wieso Gutes\" fragst Du Dich und
    dann spürst Du plötzlich eine neue Kraft in Dir.`0");
    switch(e_rand(1,2)){
        case 1:
        if (strchr($session[user][weapon],"Goblin")){
            output("`n`n`2Der Schlag mit der grünen Keule lässt Dich vollständig regenerieren!`0");
            if ($session[user][hitpoints] < $session[user][maxhitpoints])
               $session[user][hitpoints]=$session[user][maxhitpoints];
        }
        else {
            output("`n`n`2Deine Waffe ".$session[user][weapon]." färbt sich grün und ist
            nun um 3 Punkte mächtiger!`0");
            $newweapon = "`2Goblin ".$session[user][weapon]."`0";
            $session[user][weapon]= $newweapon;
            $session[user][weapondmg]+=3;
            $session[user][attack]+=3;
        }
        break;
        
        case 2:
        if (strchr($session[user][armor],"Goblin")){
            output("`n`n`2Der Schlag mit der grünen Keule lässt Dich vollständig regenerieren!`0");
            if ($session[user][hitpoints] < $session[user][maxhitpoints])
               $session[user][hitpoints]=$session[user][maxhitpoints];
        }
        else {
            output("`n`n`2Deine Rüstung ".$session[user][armor]." färbt sich grün und ist
            nun um 3 Punkte stärker!`0");
            $newarmor = "`2Goblin ".$session[user][armor]."`0";
            $session[user][armor]= $newarmor;
            $session[user][armordef]+=3;
            $session[user][defence]+=3;
        }
        break;
    }
    $session[user][specialinc] = "";
}
?>


