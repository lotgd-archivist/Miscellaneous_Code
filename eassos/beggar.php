
<?php

// 22072004

/*
- Beggar-Script by LionSource.com - ThunderEye
- made for LoGD 0.9.6 but should be work with newer versions
ALTER TABLE `accounts` ADD `gotfreegold` TINYINT(1) DEFAULT '0' NOT NULL ;
add "paidgold" in table "settings" and set "value" to 1
"gotfreegold"=>"Freigold genommen,bool",  - in user.php
"paidgold"=>"Gold das in Bettlergasse spendiert wurde (Wert-1),int", - in configuration.php

Änderungen by anpera:
- statt gotfreegold einzuführen, wird das von den empfangbaren Überweisungen abgezogen.
- Wert -1 entfernt
- Bild entfernt
- Für 0.9.7 ext (GER) angepasst
*/

require_once "common.php";

page_header("Bettelstein");
$session['user']['standort'] = "Bettelstein";

if ($_GET[op]=="spenden"){
    output("Von dem Elend am Bettelstein deprimiert, lässt du dich vor dem magischen Stein mit der blauen Aura nieder. Wild entschlossen, der Armut entgegen zu wirken, planst du Gold für die Bedürftigen zu spenden.`n`nJeder Verarmte kann dann von diesem Stein etwas Gold entnehmen.`0`n`n");
    
    addnav("Zurück");
    addnav("zum Bettelstein","beggar.php");
    addnav("zum Garten","garden.php");

    output("<form action='beggar.php?op=spenden2' method='POST'>Du spendest <input name='goldspende' id='goldspende' size='5' value='".$session['user']['gold']."'> `^Goldstücke `0für die Bedürftigen.`n`n",true);
    output("<input type='submit'value='Spendieren'></form>",true);
    output("<script language='javascript'>document.getElementById('goldspende').focus();</script>",true);
    addnav("","beggar.php?op=spenden2");

}else if ($_GET[op]=="spenden2"){
    $goldsumme = abs((int)$_POST['goldspende']);
    if ($session[user][gold] < $goldsumme){
        output("Du verfügst nicht über ausreichend Gold, um eine derartige Summe zu spenden.`nVersuche es erneut.`0");
    }else if ($goldsumme == 0){
        output("Du legst `^0 Goldstücke `0auf den Stein und bist verwundert, warum keiner reagiert. Hoppla, das war wohl nichts, versuche es erneut.`0");
    }else if (getsetting("paidgold","0") + $goldsumme > 25000){
        output("Du legst `^$goldsumme Goldstücke `0auf den Stein, aber nichts passiert. Scheinbar ist der Stein voll, wenn ein Stein überhaupt irgendwie voll sein kann. Enttäuscht nimmst du dein Gold wieder an dich.`0");
    }else if ($goldsumme <= 10){
        output("Du hast `^$goldsumme Gold`0 gespendet. Wow, damit wirst du eine Menge Bettler glücklich machen ...`0");
        if (e_rand(1,10) == 2){
            output("`n`n`&Du verlierst einen Charmepunkt!`0");
            $session[user][charm] -= 1;
        }
        savesetting("paidgold",getsetting("paidgold","0") + $goldsumme);
        $session[user][gold] -= $goldsumme;
    }else if ($goldsumme < $session[user][level]*2){
        output("Eine Spende für die Armen sollte mindestens das Doppelte deines Levels (`^".($session[user][level]*2)." Goldstücke`0) betragen, sonst nimmt es niemand wahr.`0");
    }else{
        output("Eine Welle der Begeisterung schwappt durch die Bettlergasse. Du hast `^$goldsumme Goldstücke `0gespendet und erntest von allen Betroffenen ein Lächeln!`nNun können sich die Bedürftigen an dem Gold erfreuen.");
            if ($goldsumme >= $session[user][level]*150 && e_rand(1,5)==2){
                output("`n`n`^Du erhältst einen Charmepunkt!`0");
                $session[user][charm]++;
            }
        savesetting("paidgold",getsetting("paidgold","0")+$goldsumme);
        $session[user][gold] -= $goldsumme;
        
        $sql = "INSERT INTO commentary (postdate,section,author,comment,emote) VALUES (now(),'beggar',".$session[user][acctid].",\"`&hat `^$goldsumme Goldstücke`& auf dem Spenden-Stein hinterlegt!\",1)";
        db_query($sql) or die(db_error(LINK));
    }
    addnav("Zurück zum Stein","beggar.php");

}else if ($_GET[op]=="goldnehmen"){
    $goldsumme=getsetting("paidgold","0");
    $golduser=round(($session[user][level]*getsetting("transferperlevel",25))/getsetting("transferreceive",3));
    $transleft = getsetting("transferreceive",3) - $session[user][transferredtoday];
    if ($transleft<=0){
        output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`)Name: `#".$session[user][name]."`n`)Gold erhalten: `^".$session[user][transferredtoday]."x`n`)Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
    }else{

        if (getsetting("paidgold","0")<1){
            output("`n`nDu trittst an den Spenden-Stein und möchtest etwas Gold wegnehmen. Zu deiner Enttäuschung musst du jedoch feststellen, dass da kein Gold mehr ist, was du nehmen könntest. Das nächste Mal solltest du schneller `Ús`Óe`Íi`Gn.`0");
        }else if ($session[user][gold]>=$session[user][level]*750){
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Gold: `^".$session[user][gold]."`# in der Hand`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
        }else if ($session[user][goldinbank]>=$session[user][level]*750){
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`)Name: `#".$session[user][name]."`n`)Gold erhalten: `^".$session[user][transferredtoday]."x`n`)Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
        }else if (($session[user][goldinbank]+$session[user][gold])>=$session[user][level]*750){
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Gold: `^".$session[user][gold]."`# in der Hand und `^".$session[user][goldinbank]."`# auf der Bank, das macht `^".($session[user][gold]+$session[user][goldinbank])."`# insgesamt`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
        }else if (($session[user][weapondmg]>=15) && ($session[user][armordef]>=15)){
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Ausrüstung: `#".$session[user][weapon]." und ".$session[user][armor]."`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
        }else if ($session[user][gems]>=$session[user][level]){
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Edelsteine: `^".$session[user][gems]."`# in der Hand`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
        }else if ($session[user][housekey]>0){
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Besitzt Haus Nummer: `^".$session[user][house]."`#`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`4Zugriff auf die Goldreserven verweigert.`0");
        }else if ($goldsumme<$golduser){
            $golduser=$goldsumme;
            output("`n`nMit einem beherzten Griff schnappst du dir das Gold von dem Stein. Nichts zu knapp, denn es waren nur noch `^$goldsumme Goldstücke`0 übrig.`0");
            $session[user][gold]+=$golduser;
            savesetting("paidgold",strval(getsetting("paidgold","0")-$golduser));
            $session[user][transferredtoday]++;
        }else{
            output("`n`nDu trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Vor dir materialisiert sich ein Häufchen Gold. Voller Dankbarkeit an den Spender, nimmst du die bereitgelegten `^$golduser Goldstücke `0weg und gehst deines Weges.`0");
            $session[user][gold]+=$golduser;
            savesetting("paidgold",strval(getsetting("paidgold","0")-$golduser));
            $session[user][transferredtoday]++;
        }
    }
    addnav("Zurück");
    addnav("zum Bettelstein","beggar.php");
    addnav("zum Garten","garden.php");

}else{
    addcommentary();
    
    place(1);
    
    $goldsumme=getsetting("paidgold","0");
    
    if (getsetting("paidgold","0")<1){
        addnav("Gold spenden","beggar.php?op=spenden");
    }else if ($session[user][transferredtoday]>=getsetting("transferreceive",3)){
        output("`n`nEs liegen noch `^$goldsumme Goldstücke `0auf dem Spenden-Stein. Da du heute schon genug Gold in Empfang genommen hast, darfst du jedoch nichts mehr davon nehmen.`0");
        addnav("Gold spenden","beggar.php?op=spenden");
    }else if (getsetting("paidgold","0")>0){
    $golduser=round(($session[user][level]*getsetting("transferperlevel",25))/getsetting("transferreceive",3));
        if ($goldsumme<$golduser){
            $golduser=$goldsumme;
            addnav("$golduser Gold wegnehmen","beggar.php?op=goldnehmen");
            addnav("Gold spenden","beggar.php?op=spenden");
            output("`n`nEs liegen noch `^".(getsetting("paidgold","0"))." Goldstücke `0auf dem Spenden-Stein, jetzt aber schnell.`0");
        }else{
        addnav("$golduser Gold wegnehmen","beggar.php?op=goldnehmen");
        addnav("Gold spenden","beggar.php?op=spenden");
        output("`n`nEs liegen noch `^".(getsetting("paidgold","0"))." Goldstücke `0auf dem Spenden-Stein bereit.`0");
        }
    }
    
    viewcommentary("beggar","Um eine milde Spende bitten:`0");
    
    addnav("Zurück");
    addnav("zum seltsamen Felsen","garden_rock.php");
    addnav("zum Garten","garden.php");
}


page_footer();

?>

