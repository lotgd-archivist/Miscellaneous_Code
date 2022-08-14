
<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance) and 'pay from bank'

require_once "common.php";
addcommentary();
checkday();

page_header("MightyE's Waffenladen");
$tradeinvalue = round(($session[user][weaponvalue]*.75),0);
if ($_GET[op]==""){

place();

  addnav("Waffen anschauen","weapons.php?op=peruse");
    //addnav("Inventar anzeigen","prefs.php?op=inventory&back=weapons.php");
        addnav("Zurück");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
}else if ($_GET[op]=="peruse"){
    $sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session[user][dragonkills];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    
  $sql = "SELECT * FROM weapons WHERE level = ".(int)$row[level]." ORDER BY damage ASC";
    $result = db_query($sql) or die(db_error(LINK));

    output("`n`n`üDu schlenderst durch den Laden und tust dein Bestes, so auszusehen, als ob Du wüsstest, was die meisten dieser Objekte machen. ");
    output("`KMightyE`ü schaut Dich an und sagt \"`~Ich gebe dir `T$tradeinvalue ");
    output(" Gold `~für `T".$session[user][weapon]."`~. Klicke einfach auf die Waffe, die du kaufen willst... was auch immer 'klick' bedeuten mag`~.\" ");
    output("`üDabei schaut er völlig verwirrt. Er steht ein paar Sekunden nur da, schnippt mit den Fingern und fragt sich, ob das ");
    output("`ümit 'klicken' gemeint sein könnte, bevor er sich wieder seiner Arbeit zuwendet: Herumstehen und gut aussehen.`n`n`n`n");
    if($session[user][reputation]<=-10) output("`n`üEr sieht Dich misstrauisch an, als ob er wüsste, dass Du hier hin und wieder versuchst, ihm seine schönen Waffen zu klauen.`n`n`n`n");

    output("<table border='0' cellpadding='0'>",true);
    output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bSchaden`b</td><td align='right'>`bPreis`b</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
          $row = db_fetch_assoc($result);
        $bgcolor=($i%2==1?"trlight":"trdark");
        if ($row[value]<=($session[user][gold]+$tradeinvalue)){
            output("<tr class='$bgcolor'><td>Kaufe <a href='weapons.php?op=buy&id=$row[weaponid]'>$row[weaponname]</a></td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
            addnav("","weapons.php?op=buy&id=$row[weaponid]");
        }else{
//            output("<tr class='$bgcolor'><td>$row[weaponname]</td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
//            addnav("","weapons.php?op=buy&id=$row[weaponid]");
            output("<tr class='$bgcolor'><td>- - - - <a href='weapons.php?op=buy&id=$row[weaponid]'>$row[weaponname]</a></td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
            addnav("","weapons.php?op=buy&id=$row[weaponid]");
        }
    }
    output("</table>",true);
    addnav("Zurück");
        addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
}else if ($_GET[op]=="buy"){
      $sql = "SELECT * FROM weapons WHERE weaponid='$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){

          output("`n`n`KMightyE`ü schaut Dich eine Sekunde lang verwirrt an und kommt zu dem Schluss, dass Du ein paar Schläge zuviel auf den Kopf bekommen hast. Schließlich nickt er und grinst.`n`n");

        addnav("Nochmal versuchen?","weapons.php");
        addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
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
            $session[user][reputation]-=10;
            if ($session[user][reputation]<=-10){
                if ($session[user][reputation]<=-20) $klau=10;
                if ($klau==1){ // Fall nur für Diebe

                    output("`n`n`üMit den Fertigkeiten eines erfahrenen Diebes tauschst Du `T$row[weaponname]`ü gegen `T".$session[user][weapon]."`ü aus und verlässt fröhlich pfeifend den Laden. ");
                    output(" `n`b`üGlück gehabt!`b  `KMightyE`ü war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal passiert ihm das nicht! Stolz auf Deine ");
                    output("`üfette Beute stolzierst Du über den Stadtplatz - bis Dir jemand mitteilt, dass da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");

                    $session[user][weapon] = $row[weaponname];
                    $session[user][attack]-=$session[user][weapondmg];
                    $session[user][weapondmg] = $row[damage];
                    $session[user][attack]+=$session[user][weapondmg];
                    $session[user][weaponvalue] = $row[value];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt

                    output("`n`n`üDa Dir das nötige Kleingold fehlt, grapschst Du Dir `T$row[weaponname]`ü und tauschst `T".$session[user][weapon]."`ü unauffällig dagegen aus. ");
                    output(" `n`b`üGlück gehabt!`b `KMightyE`ü war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal wird ihm das nicht passieren! Stolz auf Deine ");
                    output("`üfette Beute stolzierst Du über den Stadtplatz - bis Dir jemand mitteilt, dass da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n");

                    $session[user][weapon] = $row[weaponname];
                    $session[user][attack]-=$session[user][weapondmg];
                    $session[user][weapondmg] = $row[damage];
                    $session[user][attack]+=$session[user][weapondmg];
                    $session[user][weaponvalue] = $row[value];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt

                    output("`n`n`üDu grapschst Dir `T$row[weaponname]`ü und tauschst `T".$session[user][weapon]."`ü unauffällig dagegen aus. ");
                    output(" `üSo schnell und unauffällig wie Du kannst verlässt Du den Laden. Geschafft! Als Du mit Deiner Beute über den Stadtplatz stolzierst, siehst Du aus dem ");
                    output("`üAugenwinkel `KMightyE`ü auf Dich zurauschen. Er packt Dich mit einer Hand an `T".$session[user][armor]." und zerrt Dich mit zur Stadtbank...`n`n");
                    output("`KMightyE`ü zwingt Dich mit seinen Händen eng um Deinen Hals geschlungen dazu, die `T".($row['value']-$tradeinvalue)."`ü Gold, die Du ihm schuldest, von der Bank zu zahlen!`n`n`n`n");
                    
                                        if ($session[user][goldinbank]<0){

                        output("`n`n`üDa Du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n");
                        output("`üEr entreißt Dir `T$row[weaponname] `ügewaltsam, ");
                        output(" `üdrückt Dir Dein(e/n) alte(n/s) `T".$session[user][weapon]." `üin die Hand und schlägt Dich nieder. Er raunzt noch etwas, dass Du Glück hast, so arm zu sein, sonst hätte er Dich umgebracht und dass er Dich beim nächsten Diebstahl");
                        output(" `üganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n`n`n`n");

                        $session[user][hitpoints]=round($session[user][hitpoints]/2);
                    }else{
                        $session[user][goldinbank]-=($row[value]-$tradeinvalue);

                        if ($session[user][goldinbank]<0) output("`n`n`üDu hast dadurch jetzt `T".abs($session[user][goldinbank])." Gold`ü Schulden bei der Bank!!");
                        output("`n`üDas nächste Mal bringt er dich um. Da bist du ganz sicher.");

                        //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon");
                        $session[user][weapon] = $row[weaponname];
                        $session[user][attack]-=$session[user][weapondmg];
                        $session[user][weapondmg] = $row[damage];
                        $session[user][attack]+=$session[user][weapondmg];
                        $session[user][weaponvalue] = $row[value];
                    }
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
         addnav("nach Astaros","village.php");
                } else { // Diebstahl gelingt nicht

                      output("`n`n`üWährend Du wartest, bis `KMightyE`ü in eine andere Richtung schaut, näherst Du Dich vorsichtig dem `T$row[weaponname]`ü und nimmst es leise vom Regal. ");
                    output("`üDeiner fetten Beute gewiss drehst Du Dich leise, vorsichtig, wie ein Ninja, zur Tür, nur um zu entdecken, ");
                    output("`üdass `KMightyE`ü drohend in der Tür steht und Dir den Weg abschneidet. Du versuchst einen Flugtritt. Mitten im Flug hörst Du das \"SCHING\" eines Schwerts, ");
                    output("`üdas seine Scheide verlässt.... Dein Fuß ist weg. Du landest auf dem Beinstumpf und `KMightyE`ü steht immer noch im Torbogen, das Schwert ohne Gebrauchsspuren wieder im Halfter und mit ");
                    output("`üvor der stämmigen Brust bedrohlich verschränkten Armen. \"`~Vielleicht willst du dafür bezahlen?`ü\" ist alles, was er sagt, ");
                    output("`üwährend Du vor seinen Füßen zusammen brichst und Deinen Lebenssaft unter Deinem Dir verbliebenen Fuß über den Boden ausschüttest.`n`n`n`n");

                    $session[user][alive]=false;
                    //debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus");
                    $session[user][gold]=0;
                    $session[user][hitpoints]=0;
                    $session[user][experience]=round($session[user][experience]*.9,0);
                    $session[user][gravefights]=round($session[user][gravefights]*0.75);

                    output("`n`n`b`üDu wurdest von `KMightyE`ü umgebracht!!!`n");
                    output("`üDas Gold, das Du dabei hattest, hast Du verloren!`n");
                    output("`üDu hast 10% Deiner Erfahrung verloren!`n");
                    output("`üDu kannst Morgen wieder kämpfen.`n");
                    output("`n`üWegen der Unehrenhaftigkeit Deines Todes landest Du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!`n`n`n`n");

                    addnav("Tägliche News","news.php");
                    addnews("`T".$session[user][name]."`ü wurde beim Versuch, in `KMightyE`ü's Waffenladen zu stehlen, niedergemetzelt.");
                }
                if ($session[user][reputation]<=-10) $session[user][reputation]-=10;
            }else{
                $session[user][reputation]-=10;
                if ($klau==1){ // Fall nur für Diebe
                    output("`n`n`üMit den Fertigkeiten eines erfahrenen Diebes tauschst Du `T$row[weaponname]`ü gegen `T".$session[user][weapon]."`ü aus und verlässt fröhlich pfeifend den Laden. ");
                    output(" `b`üGlück gehabt!`b  `KMightyE`ü war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft wesentlich besser aufpassen! Stolz auf Deine ");
                    output("`üfette Beute stolzierst Du über den Stadtplatz - bis Dir jemand mitteilt, dass da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");

                    $session[user][weapon] = $row[weaponname];
                    $session[user][attack]-=$session[user][weapondmg];
                    $session[user][weapondmg] = $row[damage];
                    $session[user][attack]+=$session[user][weapondmg];
                    $session[user][weaponvalue] = $row[value];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt

                    output("`n`n`üDa Dir das nötige Kleingold fehlt, grapschst Du Dir `T$row[weaponname]`ü und tauschst `T".$session[user][weapon]."`ü unauffällig dagegen aus. ");
                    output(" `b`üGlück gehabt!`b `KMightyE`ü war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft besser aufpassen. Stolz auf Deine ");
                    output("`üfette Beute stolzierst Du über den Stadtplatz - bis Dir jemand mitteilt, dass da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!`n`n`n`n");

                    $session[user][weapon] = $row[weaponname];
                    $session[user][attack]-=$session[user][weapondmg];
                    $session[user][weapondmg] = $row[damage];
                    $session[user][attack]+=$session[user][weapondmg];
                    $session[user][weaponvalue] = $row[value];
                    if ($session[user][charm]) $session[user][charm]-=1;
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt

                    output("`n`n`üDu grapschst Dir `T$row[weaponname]`ü und tauschst `T".$session[user][weapon]."`ü unauffällig dagegen aus. ");
                    output(" `üSo schnell und unauffällig wie Du kannst verlässt du den Laden. Geschafft! Als Du mit Deiner Beute über den Stadtplatz stolzierst, siehst Du aus dem ");
                    output("`üAugenwinkel `KMightyE`ü auf Dich zurauschen. Er packt Dich mit einer Hand an `T".$session[user][armor]." `üund zerrt Dich mit zur Stadtbank...`n`n");
                    output("`KMightyE`ü zwingt Dich mit seinen Händen eng um Deinen Hals geschlungen dazu, die `T".($row['value']-$tradeinvalue)."`ü Gold, die Du ihm schuldest, von der Bank zu zahlen!`n`n`n`n");

                    if ($session[user][goldinbank]<0){

                        output("`n`n`üDa Du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n");
                        output("`üEr entreißt Dir `T$row[weaponname] `ügewaltsam, ");
                        output(" `üdrückt Dir Dein(e/n) alte(n/s) `T".$session[user][weapon]." `üin die Hand und schlägt Dich nieder. Er raunzt noch etwas, dass Du Glück hast, so arm zu sein, sonst hätte er Dich umgebracht und dass er Dich beim nächsten Diebstahl");
                        output(" `üganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n`n`n`n");

                        $session[user][hitpoints]=round($session[user][hitpoints]/2);
                    }else{
                        $session[user][goldinbank]-=($row[value]-$tradeinvalue);

                        if ($session[user][goldinbank]<0) output("`n`n`üDu hast dadurch jetzt `T".abs($session[user][goldinbank])." Gold`ü Schulden bei der Bank!!!`n`n");
                        //debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon");
                        output("`n`üDas nächste Mal bringt er dich wahrscheinlich um.`n`n`n`n");

                        $session[user][weapon] = $row[weaponname];
                        $session[user][attack]-=$session[user][weapondmg];
                        $session[user][weapondmg] = $row[damage];
                        $session[user][attack]+=$session[user][weapondmg];
                        $session[user][weaponvalue] = $row[value];
                    }
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
                } else { // Diebstahl gelingt nicht

                    output("`n`n`üDu grapschst Dir `T$row[weaponname]`ü und tauschst `T".$session[user][weapon]."`ü unauffällig dagegen aus. ");
                    output(" `üSo schnell und unauffällig wie Du kannst verlässt Du den Laden. Geschafft! Als Du mit Deiner Beute über den Stadtplatz stolzierst, siehst Du aus dem ");
                    output("`üAugenwinkel `TMightyE`ü auf Dich zurauschen. Er packt Dich mit einer Hand an `T".$session[user][armor]."`ü.`n`n");
                    output("`üEr entreißt Dir `T$row[weaponname] `ügewaltsam, ");
                    output(" `üdrückt Dir Dein(e/n) alte(n/s) `T".$session[user][weapon]." `üin die Hand und schlägt Dich nieder. Er raunzt noch etwas, dass er Dich beim nächsten Diebstahl");
                    output(" `üganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n`n`n`n");

                    $session[user][hitpoints]=1;
                    if ($session[user][turns]>0){
                        output("`n`n`TDu verlierst einen Waldkampf und fast alle Lebenspunkte.`n`n");
                        $session[user][turns]-=1;
                    }else{
                        output("`n`n`KMightyE `ühat Dich so schlimm erwischt, dass eine Narbe bleiben wird.`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.`n`n`n`n");

                        $session[user][charm]-=3;
                        if ($session[user][charm]<0) $session[user][charm]=0;
                    }
                    addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
         addnav("nach Astaros","village.php");
                }
            }
        }else{
            output("`n`n`KMightyE`ü nimmt Dein `T".$session[user][weapon]."`ü stellt es aus und hängt sofort ein neues Preisschild dran.`n`n");

            //debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['weaponname'] . " weapon");
             $session['user']['gold']-=$row['value'];
            $session['user']['weapon'] = $row['weaponname'];
            $session['user']['gold']+=$tradeinvalue;
            $session['user']['attack']-=$session['user']['weapondmg'];
            $session['user']['weapondmg'] = $row['damage'];
            $session['user']['attack']+=$session['user']['weapondmg'];
            $session['user']['weaponvalue'] = $row['value'];
            
            //Permanente Waffenwerte zurücksetzen, falls Spieler eine solche gerade verkauft hat
            if($session['user']['spec_wpn_name'] != "")
            {
                $session['user']['spec_wpn_name'] = "";
                $session['user']['spec_wpn_atk']  = 0;
            }
            
            output("`n`n`üIm Gegenzug händigt er Dir ein glänzendes, neues `T$row[weaponname]`ü aus, das Du probeweise im Raum schwingst. Dabei schlägst Du `KMightyE`ü fast den Kopf ab. ");
            output("`üEr duckt sich so, als ob Du nicht der erste bist, der seine neue Waffe sofort ausprobieren will...`n`n`n`n");
addnav("Zurück");
addnav("Zum Waffenladen","weapons.php");
    addnav("Zum Markt","markt.php");
        addnav("nach Astaros","village.php");
        }
    }
}

viewcommentary("Waffenhändler","Unterhalte dich mit Händler oder Verkäufer:`n`n");
page_footer();
?>


