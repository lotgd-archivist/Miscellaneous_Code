
<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance)

require_once "common.php";
addcommentary();
checkday();

page_header("Pegasus Rüstungen");

$tradeinvalue = round(($session[user][armorvalue]*.75),0);
if ($_GET[op]==""){

place(1);

    addnav("Pegasus' Waren durchstöbern","armor.php?op=browse");
    //addnav("Inventar anzeigen","prefs.php?op=inventory&back=armor.php");
    addnav("Zurück");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
}else if ($_GET[op]=="browse"){
    $sql = "SELECT max(level) AS level FROM armor WHERE level<=".$session[user][dragonkills];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

      $sql = "SELECT * FROM armor WHERE level=$row[level] ORDER BY value";
    $result = db_query($sql) or die(db_error(LINK));

    output("`n`n`ÚDu blickst über die verschiedenen Kleidungsstücke und fragst dich, ob `GPegasus`Ú einige davon für dich ");
    output("anprobieren würde. Aber dann bemerkst du, dass sie damit beschäftigt ist, `KMightyE`Ú verträumt durch das Fenster seines Ladens dabei zu beobachten, ");
    output("wie er gerade mit nacktem Oberkörper einem Kunden den Gebrauch einer seiner Waren demonstriert. Als sie kurz wahrnimmt, dass du ");
    output("ihre Waren durchstöberst, blickt sie auf dein(e/n) `§".$session[user][armor]."`Ú und bietet dir dafür im Tausch `§$tradeinvalue`Ú Gold an.`n`n`n`n");
    if($session['user']['reputation']<=-10) output("`n`ÚSie sieht dich misstrauisch an, als ob sie wüsste, dass du hier hin und wieder versuchst, ihr ihre schönen Rüstungen zu klauen.`n`n`n`n");

    output("<table border='0' cellpadding='0'>",true);
    output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bVerteidigung`b</td><td align='right'>`bPreis`b</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
          $row = db_fetch_assoc($result);
        $bgcolor=($i%2==1?"trlight":"trdark");
        if ($row[value]<=($session[user][gold]+$tradeinvalue)){
            output("<tr class='$bgcolor'><td>Kaufe <a href='armor.php?op=buy&id=$row[armorid]'>$row[armorname]</a></td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
            addnav("","armor.php?op=buy&id=$row[armorid]");
        }else{
//            output("<tr class='$bgcolor'><td>$row[armorname]</td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
//            addnav("","armor.php?op=buy&id=$row[armorid]");
            output("<tr class='$bgcolor'><td>- - - - <a href='armor.php?op=buy&id=$row[armorid]'>$row[armorname]</a></td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
            addnav("","armor.php?op=buy&id=$row[armorid]");

        }
    }
    output("</table>",true);

        addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

}else if ($_GET[op]=="buy"){
      $sql = "SELECT * FROM armor WHERE armorid='$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){

          output("`n`n`GPegasus`Ú schaut dich ein paar Sekunden verwirrt an, entschließt sich dann aber zu glauben, dass du wohl ein paar Schläge zu viel auf den Kopf bekommen hast und nickt lächelnd.`n`n`n`n");

        addnav("Nochmal?","armor.php");
        addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
    }else{
          $row = db_fetch_assoc($result);
        if ($row[value]>($session[user][gold]+$tradeinvalue)){
            if ($session[user][thievery]>=2) {
                $klau=e_rand(1,15);
            } else {
                $klau=e_rand(2,18);
            }
            if ($session['user']['reputation']<=-10){
                if ($session['user']['reputation']<=-20) $klau=10;
                $session['user']['reputation']-=10;
                if ($klau==1){ // Fall nur für Diebe

                    output("`n`n`ÚMit den Fertigkeiten eines erfahrenen Diebes tauschst du `§$row[armorname]`Ú gegen `§".$session[user][armor]."`Ú aus und verlässt fröhlich pfeifend den Laden. ");
                    output(" `bGlück gehabt!`b `GPegasus`Ú starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine ");
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");
                     $session[user][armor] = $row[armorname];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    $session[user][defence]-=$session[user][armordef];
                    $session[user][armordef] = $row[defense];
                    $session[user][defence]+=$session[user][armordef];
                    $session[user][armorvalue] = $row[value];
                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
                    output("`n`n`ÚDu grapschst dir `§$row[armorname]`Ú und tauschst `§".$session[user][armor]."`Ú unauffällig dagegen aus. ");
                    output(" `bGlück gehabt!`b `GPegasus`Ú starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine ");
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");
                     $session[user][armor] = $row[armorname];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    $session[user][defence]-=$session[user][armordef];
                    $session[user][armordef] = $row[defense];
                    $session[user][defence]+=$session[user][armordef];
                    $session[user][armorvalue] = $row[value];
                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt

                    output("`n`n`ÚDu grapschst dir `§$row[armorname]`Ú und tauschst `§".$session[user][armor]."`Ú unauffällig dagegen aus. ");
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem ");
                    output("Augenwinkel `GPegasus`Ú knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen reißt sie das Preisschild ab, das noch immer von deiner neuen Rüstung baumelt...`n`n`n`n");

                    if ($session[user][goldinbank]<0){

                        output("`n`n`ÚDa du jedoch schon Schulden bei der Bank hast, bekam `GPegasus`Ú von dort nicht, was sie verlangte.`n");
                        output("Als du dein(e/n) `§$row[armorname]`Ú stolz auf dem Dorfplatz präsentierst, packt dich von hinten `KMightyE`Ú's starke Hand. Er entreißt dir `§$row[armorname] gewaltsam, ");
                        output(" drückt dir dein(e/n) alte(n/s) `§".$session[user][armor]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl");
                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
                        output(" `GPegasus`Ú wird dir sowas nicht nochmal durchgehen lassen!`n`n`n`n");

                        $session[user][hitpoints]=round($session[user][hitpoints]/2);
                    }else{

                        output("`n`n`GPegasus`Ú hat sich die `§".($row['value']-$tradeinvalue)." `ÚGold, die du ihr schuldest, von der Bank geholt!");
                        output(" Sie wird dir sowas nicht nochmal durchgehen lassen.`n`n`n`n");

                        $session[user][goldinbank]-=($row[value]-$tradeinvalue);

                        if ($session[user][goldinbank]<0) output("`n`n`n`ÚDu hast dadurch jetzt `§".abs($session[user][goldinbank])." Gold`Ú Schulden bei der Bank!!`n`n`n`n");

                        //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor");
                         $session[user][armor] = $row[armorname];
                        $session[user][defence]-=$session[user][armordef];
                        $session[user][armordef] = $row[defense];
                        $session[user][defence]+=$session[user][armordef];
                        $session[user][armorvalue] = $row[value];
                    }

                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

                } else { // Diebstahl gelingt nicht

                      output("`n`n`ÚDu wartest, bis `GPegasus`Ú wieder abgelenkt ist. Dann näherst du dich vorsichtig `§$row[armorname]`Ú und lässt die Rüstung leise vom Stapel verschwinden, auf dem sie lag. ");
                    output("Deiner Beute sicher drehst du dich um ... nur um festzustellen, dass du dich nicht ganz umdrehen kannst, weil sich zwei Hände fest um deinen ");
                    output("Hals schliessen. Du schaust runter, verfolgst die Hände bis zu einem Arm, an dem sie befestigt sind, der wiederum an einem äußerst muskulösen `KMightyE`Ú befestigt ist. Du versuchst ");
                    output("zu erklären, was hier passiert ist, aber dein Hals scheint nicht in der Lage zu sein, deine Stimme oder gar den so dringend benötigten Sauerstoff hindurch zu lassen.  ");
                    output("`n`nAls langsam Dunkelheit in deine Wahrnehmung schlüpft, schaust du flehend zu `§Pegasus`Ú, doch die starrt nur völlig verträumt und mit den Händen seitlich auf dem lächelnden Gesicht ");
                    output("auf `KMightyE`Ú.`n`n`n`n");

                    $session[user][alive]=false;
                    //debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus");
                    $session[user][gold]=0;
                    $session[user][hitpoints]=0;
                    $session[user][experience]=round($session[user][experience]*.9,0);
                    $session[user][gravefights]=round($session[user][gravefights]*.75);

                    output("`n`n`b`&Du wurdest von `KMightyE`& umgebracht!!!`n");
                    output("`ÚDas Gold, das du dabei hattest, hast du verloren!`n");
                    output("`ÚDu hast 10% deiner Erfahrung verloren!`n");
                    output("`ÚDu kannst morgen wieder kämpfen.`n");
                    output("`n`ÚWegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!`n`n`n`n");

                    addnav("Tägliche News","news.php");
                    addnews("`§".$session[user][name]."``Ú wurde von `KMightyE`Ú für den Versuch, bei `GPegasus``Ú zu stehlen, erwürgt.");
                }
            }else{
                $session['user']['reputation']-=10;
                if ($klau==1){ // Fall nur für Diebe

                    output("`n`n`ÚMit den Fertigkeiten eines erfahrenen Diebes tauschst du `§$row[armorname]`Ú gegen `§".$session[user][armor]."`Ú aus und verlässt fröhlich pfeifend den Laden. ");
                    output(" `bGlück gehabt!`b `GPegasus`Ú starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Trotzdem wird sie den Diebstahl früher oder später bemerken und in Zukunft besser aufpassen! Stolz auf deine ");
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");

                     $session[user][armor] = $row[armorname];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    $session[user][defence]-=$session[user][armordef];
                    $session[user][armordef] = $row[defense];
                    $session[user][defence]+=$session[user][armordef];
                    $session[user][armorvalue] = $row[value];

                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt

                    output("`n`n`ÚDu grapschst dir `§$row[armorname]`Ú und tauschst `§".$session[user][armor]."`Ú unauffällig dagegen aus. ");
                    output(" `bGlück gehabt!`b `GPegasus`Ú starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Trotzdem wird sie den Diebstahl früher oder später bemerken und in Zukunft besser aufpassen! Stolz auf deine ");
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");

                     $session[user][armor] = $row[armorname];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    $session[user][defence]-=$session[user][armordef];
                    $session[user][armordef] = $row[defense];
                    $session[user][defence]+=$session[user][armordef];
                    $session[user][armorvalue] = $row[value];

                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt

                    output("`n`n`ÚDu grapschst dir `§$row[armorname]`Ú und tauschst `§".$session[user][armor]."`Ú unauffällig dagegen aus. ");
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem ");
                    output("Augenwinkel `GPegasus`Ú knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen reißt sie das Preisschild ab, das noch immer von deiner neuen Rüstung baumelt...`n`n`n`n");
                    if ($session[user][goldinbank]<0){

                        output("`n`nDa du jedoch schon Schulden bei der Bank hast, bekam `GPegasus`Ú von dort nicht was sie verlangte.`n");
                        output("Als du dein(e/n) `§$row[armorname]`Ú stolz auf dem Dorfplatz präsentierst, packt dich von hinten `KMightyE`Ú's starke Hand. Er entreißt dir `§ $row[armorname] `Úgewaltsam, ");
                        output(" drückt dir dein(e/n) alte(n/s) `§".$session[user][armor]." `Úin die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl");
                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
                        output(" `GPegasus`Ú wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst.`n`n`n`n");

                        $session[user][hitpoints]=round($session[user][hitpoints]/2);
                    }else{

                        output("`n`n`GPegasus`Ú hat sich die `§".($row['value']-$tradeinvalue)." `ÚGold, die du ihr schuldest, von der Bank geholt!");
                        output(" Sie wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst.`n`n`n`n");

                        $session[user][goldinbank]-=($row[value]-$tradeinvalue);

                        if ($session[user][goldinbank]<0) output("`n`n`n`ÚDu hast dadurch jetzt `§".abs($session[user][goldinbank])." Gold`Ú Schulden bei der Bank!!`n`n`n`n");

                        //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor");
                         $session[user][armor] = $row[armorname];
                        $session[user][defence]-=$session[user][armordef];
                        $session[user][armordef] = $row[defense];
                        $session[user][defence]+=$session[user][armordef];
                        $session[user][armorvalue] = $row[value];
                    }

                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

                } else { // Diebstahl gelingt nicht

                    output("`n`n`ÚDu grapschst dir `§$row[armorname]`Ú und tauschst `§".$session[user][armor]."`Ú unauffällig dagegen aus. ");
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! ");
                    output("Als du dein(e/n) `§$row[armorname]`Ú stolz auf dem Dorfplatz präsentierst, packt dich von hinten `KMightyE`Ú's starke Hand. Er entreißt dir`§ $row[armorname] `Úgewaltsam, ");
                    output(" drückt dir dein(e/n) alte(n/s)`§ ".$session[user][armor]." `Úin die Hand und schlägt dich nieder. Er raunzt noch etwas, daß er dich beim nächsten Diebstahl");
                    output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n`n`n`n`n");

                    $session[user][hitpoints]=1;
                    if ($session[user][turns]>0){

                        output("`n`n`ÚDu verlierst einen Waldkampf und fast alle Lebenspunkte.`n`n");

                        $session[user][turns]-=1;
                    }else{

                        output("`n`n``ÚMightyE hat dich so schlimm erwischt, dass eine Narbe bleiben wird.`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.`n`n");

                        $session[user][charm]-=3;
                        if ($session[user][charm]<0) $session[user][charm]=0;
                    }

                    addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

                }
            }
        }else{

            output("`n`n`GPegasus`Ú  nimmt dein Gold und sehr zu deiner Überraschung nimmt sie auch dein(e/n) `§".$session[user][armor]."`Ú  hängt ein Preisschild dran und legt die Rüstung hübsch zu den anderen. ");
            output("`n`nIm Gegenzug händigt sie dir deine wunderbare neue Rüstung `§$row[armorname]`Ú aus.");
            output("`n`nDu fängst an zu protestieren: \"`ÍWerde ich nicht albern aussehen, wenn ich nichts außer `§$row[armorname]`Í trage?`Ú\" Du denkst einen Augenblick darüber nach, dann wird dir klar, dass jeder in der  ");
            output("Stadt ja das Selbe macht.    \"`ÍNa gut. Andere Länder, andere Sitten`Ú\"`n`n`n`n");

            //debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['armorname'] . " armor");
              $session['user']['gold']-=$row['value'];
            $session['user']['armor'] = $row['armorname'];
            $session['user']['gold']+=$tradeinvalue;
            $session['user']['defence']-=$session['user']['armordef'];
            $session['user']['armordef'] = $row['defense'];
            $session['user']['defence']+=$session['user']['armordef'];
            $session['user']['armorvalue'] = $row['value'];
            
            //Permanente Rüstungswerte zurücksetzen, falls Spieler eine solche gerade verkauft hat
            if($session['user']['spec_arm_name'] != "")
            {
                $session['user']['spec_arm_name'] = "";
                $session['user']['spec_arm_def']  = 0;
            }

            addnav("Zurück");
        addnav("Zum Rüstungshandel","armor.php");
        addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");

        }
    }
}

viewcommentary("Rüstungsladen","Unterhalte dich mit Käufern oder Händlern:`n`n");

page_footer();
?>


