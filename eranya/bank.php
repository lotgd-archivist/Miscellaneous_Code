
<?php

// 22062004

require_once "common.php";

page_header("Die alte Bank");
output("`^`c`bDie alte Stadtbank`b`c`n`x");
if ($_GET['op']=="")
{
    $show_invent = true;

    checkday();
    output("In einem der ältesten Gebäude Eranyas ist die stadteigene Bank untergebracht. Vom Marktplatz aus gut erreichbar, tritt man ein durch eine eisenbeschlagene Holztür und
            landet in einem quadratischen Raum mit hohen Decken, welcher allerdings nur wenig größer ist als ein gewöhnliches Büro. Zu deiner Linken füllen hohe Regale die Wände; sie sind
            vollgestopft mit Büchern und losen Blättersammlungen, die zu kleinen Paketen zusammengebunden sind. Rechts spenden kleine, von außen vergitterte Fenster gerade genug Licht, um
            bei Sonnenschein auf Lampen verzichten zu können. Gegenüber der Tür nimmt ein langer und ungewöhnlich breiter Schreibtisch fast ein Viertel der gesamten Bodenfläche ein. Mehrere
            Tintenfässchen mitsamt Federkielen stehen dort herum, und wichtig aussehende Dokumente oder auch leeres Papier liegen in keinerlei erkennbarer Ordnung dort verteilt. Dahinter
            führt eine weitere eisenbeschlagene Tür zum nächsten Raum, doch ein großes Schloss verhindert, dass Unbefugte dorthin gelangen.`n
            Auf dem Schreibtisch entdeckst du eine halb von dir weggedrehte Holztafel. Ein darauf aufgespanntes Stück Papier ist vollgeschrieben mit allerlei Grammzahlen: Acht Dutzend Gramm
            Kupfer entsprechen einer Silbermünze, und acht Dutzend Gramm Silber entsprechen einer Goldmünze. Das muss wohl der heutige Kurs der herzoglichen Währung sein. Wie die vielen
            fremdländischen Währungen gehandhabt werden, die durch die Handelsschiffe nach Eranya gelangen, kannst du aus dem Gekritzel allerdings nicht herauslesen.`n
            Ein kleiner Mann in teuer wirkender Kleidung sieht von seinem Sitzplatz hinter dem Schreibtisch auf und mustert dich durch seine Lesebrille
            hindurch. \"`3Willkommen`x\", grüßt er knapp und wartet dann, bis du dich ihm gegenüber auf den freien Stuhl niedergelassen hast. Du nennst ihm
            deinen Namen und bittest ihn um Auskunft über deine Finanzen. Sofort erhebt sich der Mann und zieht einen dicken Wälzer aus einem Regal. Die geübten Finger
            blättern kurz, dann meint der Bankier zu dir:`n`n");
    if ($session['user']['goldinbank']>=0)
    {
        output("\"`3Hier ist es. Ihr habt `^".$session['user']['goldinbank']." Gold`3 bei unserer renommierten Bank. Kann ich sonst noch etwas für Euch tun?`x\"`n`n");
    }
    else
    {
        output("\"`3Hier ist es. Ihr habt `&Schulden`3 in Höhe von `^".abs($session['user']['goldinbank'])." Gold`3 bei unserer ");
        output("renommierten Bank. Kann ich sonst noch etwas für Euch tun?`x\"`n`n(`iSchulden verfallen durch einen Drachenkill nicht!`i)`n`n");
    }

}
else if ($_GET['op']=="transfer")
{
    output("`x`bGold überweisen`b:`n`n");
    if ($session['user']['goldinbank']>=0)
    {

        $rowe = user_get_aei('goldout');

        output("Du kannst maximal `^".getsetting("transferperlevel",25)."`x Gold pro Level des Empfängers überweisen.`n");
        $maxout = $session['user']['level']*getsetting("maxtransferout",25);
        $minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));
        output("Du musst mindestens `^$minfer`x Gold überweisen.`n");
        output("Du kannst nicht mehr als insgesamt `^$maxout`x Gold überweisen.");
        if ($rowe['goldout'] > 0)
        {
            output("(Du hast heute schon `^{$rowe['goldout']}`x Gold überwiesen.)`n`n");
        }
        else
        {
            output("`n`n");
        }
        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5'>`n",true);
        output("A<u>n</u>: <input name='to' accesskey='n'> (Unvollständige Namen werden automatisch ergänzt. Du wirst noch einmal zum Bestätigen aufgefordert).`n`n",true);
        output("<input type='submit' class='button' value='Vorschau'></form>",true);
        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
        addnav("","bank.php?op=transfer2");
    }
    else
    {
        output("`xDer Bankier weigert sich, Geld für jemanden zu überweisen, der Schulden hat.");
    }
}
else if ($_GET['op']=="transfer2")
{
    output("`x`bÜberweisung bestätigen`b:`n`n");

    $string = str_create_search_string($_POST['to']);
    $sql = "SELECT name,login,acctid FROM accounts WHERE name LIKE '".$string."'";
    $result = db_query($sql);
    $amt = abs((int)$_POST['amount']);
    if (db_num_rows($result)>100)
    {
        output("Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");
        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5' value='$amt'>`n",true);
        output("A<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt. Du wirst noch einmal zum Bestätigen aufgefordert).`n`n",true);
        output("<input type='submit' class='button' value='Vorschau'></form>",true);
        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
        addnav("","bank.php?op=transfer2");
    }
    else if (db_num_rows($result)>=1)
    {
        output("<form action='bank.php?op=transfer3' method='POST'>",true);
        output("`xÜberweise `^$amt`x an <select name='to' class='input'>",true);
        for ($i=0; $i<db_num_rows($result); $i++)
        {
            $row = db_fetch_assoc($result);
            //output($row[name]." ".$row[login]."`n");
            output("<option value=\"".$row['acctid']."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select>`n`n<input type='submit' class='button' value='Überweisung abschließen'></form>",true);
        addnav("","bank.php?op=transfer3");
        $session['bank_amt'] = $amt;
    }
    else
    {
        output("`xEs konnte niemand mit diesem Namen gefunden werden. Bitte versuche es noch einmal.");
    }
}
else if ($_GET['op']=="transfer3")
{
    $amt = abs((int)$session['bank_amt']);
    unset($session['bank_amt']);
    output("`x`bÜberweisung abschließen`b`n");
    if ($session['user']['gold']+$session['user']['goldinbank']<$amt)
    {
        output("`xWie willst du `^$amt`x Gold überweisen, wenn du nur ".($session['user']['gold']+$session['user']['goldinbank'])."`x Gold hast?");
    }
    else
    {

        $rowe = user_get_aei('goldout');

        $sql = "SELECT name,accounts.acctid,level,goldin,lastip,emailaddress,uniqueid
        FROM accounts
        LEFT JOIN account_extra_info USING(acctid)
        WHERE accounts.acctid='{$_POST['to']}'";
        $result = db_query($sql);
        unset($session['bank_to']);
        if (db_num_rows($result)==1)
        {
            $row = db_fetch_assoc($result);
            $maxout = $session['user']['level']*getsetting("maxtransferout",25);
            $maxtfer = $row['level']*getsetting("transferperlevel",25);
            $minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));
            if ($rowe['goldout']+$amt > $maxout)
            {
                output("`xDie Überweisung wurde nicht durchgeführt: Du darfst nicht mehr als `^$maxout`x Gold pro Tagesabschnitt überweisen.");
            }
            else if ($maxtfer<$amt)
            {
                output("`xDie Überweisung wurde nicht durchgeführt; `&{$row['name']}`x darf maximal `^$maxtfer`x Gold pro Tagesabschnitt empfangen.");
            }
            else if ($row['goldin']>=getsetting("transferreceive",3))
            {
                output("`&{$row['name']}`x hat heute schon zu viele Überweisungen erhalten. Du wirst bis morgen warten müssen.");
            }
            else if ($amt<$minfer)
            {
                output("`xDu solltest etwas überweisen, das sich auch lohnt. Wenigstens `^$minfer`x Gold.");
            }
            else if ($row['acctid']==$session['user']['acctid'])
            {
                output("`xDu kannst dir nicht selbst Gold überweisen. Das macht keinen Sinn!");
            }
            else if (ac_check($row))
            {
                //             } else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){
                output("`$`bNicht erlaubt!`b Du darfst kein Gold an deine eigenen Charaktere überweisen!");
            }
            else
            {
                //debuglog("transferred $amt gold to", $row['acctid']);
                $session['user']['gold']-=$amt;
                if ($session['user']['gold']<0)
                {
                    //withdraw in case they don't have enough on hand.
                    $session['user']['goldinbank']+=$session['user']['gold'];
                    $session['user']['gold']=0;
                }

                user_set_aei(array('goldout'=>$rowe['goldout']+$amt));

                user_set_aei(array('goldin'=>$row['goldin']+$amt),$row['acctid']);

                $sql = "UPDATE accounts SET goldinbank=goldinbank+$amt WHERE acctid='{$row['acctid']}'";
                db_query($sql);

                output("`xTransfer vollständig!");
                //$session['user']['donation']+=1;
                systemmail($row['acctid'],"`^Du hast eine Überweisung erhalten!`0","`&{$session['user']['name']}`x hat dir `^$amt`x Gold auf dein Konto überwiesen!");
            }
        }
        else
        {
            output("`xDie Überweisung hat nicht geklappt. Bitte versuche es noch einmal.");
        }
    }



}
else if ($_GET['op']=="gemtrans")
{
    output("`x`bEdelstein versenden`b:`n`n");
    if (($session['user']['gold']>=100 || $session['user']['gold']+$session['user']['goldinbank']>=100) && $session['user']['gems']>0)
    {
        $int_maxgemstrf = getsetting("bankmaxgemstrf",4);
        output("Du kannst einem beliebigen Charakter mit mindestens Level 3 max. `#".$int_maxgemstrf." Edelstein".($int_maxgemstrf == 1 ? "" : "e")."`x für eine Versandgebühr von je `^100 Gold`x verschenken.`n`n
                <form action='bank.php?op=gemtrans2' method='POST'>
                <select name='gemsanz' class='input'>");
        for($i=1;$i<=$int_maxgemstrf;$i++) {
                output("<option value='".$i."'>".$i."</option>");
        }
        output("</select> Edelsteine versenden an: <input name='to' accesskey='n'> `i(Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert)`i.`n`n
                <input type='submit' class='button' value='Vorschau'></form>");
        addnav("","bank.php?op=gemtrans2");
    }
    else if ($session['user']['gold']+$session['user']['goldinbank']<100)
    {
        output("`xDer Bankier weigert sich, einen Edelstein kostenlos zu versenden.`nDu besitzt nicht einmal genug Gold, um `ieinen`i Edelstein zu versenden!");
    }
    else
    {
        output("`xDer Bankier erklärt dir lange und umständlich, dass du keine Edelsteine verschenken kannst, wenn du keine hast!");
    }
}
else if ($_GET['op']=="gemtrans2")
{
    output("`x`bVersand bestätigen`b:`n`n");
    $int_maxgemstrf = getsetting("bankmaxgemstrf",4);

    $string = str_create_search_string($_POST['to']);
    $sql = "SELECT name,login,acctid FROM accounts WHERE name LIKE '".$string."'";
    $result = db_query($sql);
    if (db_num_rows($result)>100)
    {
        output("Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n
                <form action='bank.php?op=gemtrans2' method='POST'>
                <select name='gemsanz' class='input'>");
        for($i=1;$i<=$int_maxgemstrf;$i++) {
                output("<option value='".$i."'".($i == $_POST['gemsanz'] ? " selected" : "").">".$i."</option>");
        }
        output("</select> Edelsteine versenden an: <input name='to' accesskey='n'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n`n
                <input type='submit' class='button' value='Vorschau'></form>");
        addnav("","bank.php?op=gemtrans2");
    }
    else if (db_num_rows($result)>=1)
    {
        output("<form action='bank.php?op=gemtrans3' method='POST'>",true);
        output("`xVerschenke `#<select name='gemsanz' class='input'>");
        for($i=1;$i<=$int_maxgemstrf;$i++) {
                output("<option value='".$i."'".($i == $_POST['gemsanz'] ? " selected" : "").">".$i."</option>");
        }
        output("</select> Edelsteine`x für eine Versandgebühr von insgesamt `^".($_POST['gemsanz']*100)." Gold`x an <select name='to' class='input'>",true);
        for ($i=0; $i<db_num_rows($result); $i++)
        {
            $row = db_fetch_assoc($result);
            output("<option value=\"".$row['acctid']."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select>`n`n<input type='submit' class='button' value='Versand abschließen'></form>",true);
        addnav("","bank.php?op=gemtrans3");
    }
    else
    {
        output("`xEs konnte niemand mit diesem Namen gefunden werden. Bitte versuche es noch einmal.");
    }
}
else if ($_GET['op']=="gemtrans3")
{
    $rowe = user_get_aei('gemsout');
    $int_gemsanz = $_POST['gemsanz'];
    $int_maxgemstrf = getsetting("bankmaxgemstrf",4);

    output("`x`bVersand abschließen`b`n");
    $sql = "SELECT name,accounts.acctid,level,lastip,emailaddress,gemsin,uniqueid
            FROM accounts LEFT JOIN account_extra_info USING(acctid)
            WHERE accounts.acctid='{$_POST['to']}'";
    $result = db_query($sql);
    if (db_num_rows($result)==1)
    {
        $row = db_fetch_assoc($result);
        if ($row['level']<getsetting('bankgemtrflvl',3))
        {
            output("`&{$row['name']}`x kann noch keine Edelsteine in Empfang nehmen. Der Empfänger muss mindestens Level ".getsetting('bankgemtrflvl',3)." sein.");
        }
        else if ($row['acctid']==$session['user']['acctid'])
        {
            output("`xDu kannst dir nicht selbst einen Edelstein schenken. Das macht keinen Sinn!");
        }
        else if ($row['gemsin']>=$int_maxgemstrf)
        {
            output("`&{$row['name']}`x hat heute schon zu viele Edelsteine erhalten. Du wirst bis morgen warten müssen.");
        }
        else if (ac_check($row))
        {
            output("`$`bNicht erlaubt!`b Du darfst keine Edelsteine an deine eigenen Charaktere versenden!");
        }
        else
        {
            if(($row['gemsin']+$int_gemsanz) > $int_maxgemstrf) {
                $int_gemsanz = $int_maxgemstrf - $row['gemsin'];
                output('`&'.$row['name'].' `^kann heute noch max. '.$int_gemsanz.' Edelsteine erhalten.`n`n');
            }
            if ($session['user']['gold'] < $int_gemsanz*100 && $session['user']['goldinbank']+$session['user']['gold'] < $int_gemsanz*100) {
                output('`4Leider besitzt du nicht genügend Gold, um '.$int_gemsanz.' Edelsteine zu verschenken.');
            } else {
                $session['user']['gold'] -= $int_gemsanz*100;
                $session['user']['gems'] -= $int_gemsanz;
                debuglog('Versandte '.$int_gemsanz.' Edelsteine an ',$row['acctid']);
                user_set_aei(array('gemsout'=>$rowe['gemsout']+$int_gemsanz));
                user_set_aei(array('gemsin'=>$row['gemsin']+$int_gemsanz),$row['acctid']);
                $sql = "UPDATE accounts SET gems=gems+".$int_gemsanz." WHERE acctid='{$row['acctid']}'";
                db_query($sql);
                output("`xDer Versand von ".$int_gemsanz." Edelsteinen an `&".$row['name']." `xwar erfolgreich.");
                systemmail($row['acctid'],"`#Du hast ".$int_gemsanz." Edelsteine geschenkt bekommen!`0","`&{$session['user']['name']}`x war so freundlich und hat dir `#".$int_gemsanz." Edelsteine`x geschenkt!");
            }
        }
    }
    else
    {
        output("`xDer Versand hat nicht geklappt. Bitte versuche es noch einmal.");
    }




}
else if ($_GET['op']=="deposit")
{
    output("<form action='bank.php?op=depositfinish' method='POST'>Du hast ".($session['user']['goldinbank']>=0?"ein Guthaben von":"Schulden in Höhe von")." `^".abs($session['user']['goldinbank'])." `xGold bei der Bank.`n`n",true);
    output("`xWie <u>v</u>iel Gold möchtest du ".($session['user']['goldinbank']>=0?"einzahlen":"zurückzahlen")."? <input id='input' name='amount' width=5 accesskey='v'> <input type='submit' class='button' value='Einzahlen'>`n`i(Gib 0 oder gar nichts ein, um alles einzuzahlen.)`i</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
    addnav("","bank.php?op=depositfinish");
}
else if ($_GET['op']=="depositfinish")
{
    $_POST['amount']=abs((int)$_POST['amount']);
    if ($_POST['amount']==0)
    {
        $_POST['amount']=$session['user']['gold'];
    }
    if ($_POST['amount']>$session['user']['gold'])
    {
        output("`\$FEHLER: Soviel Gold trägst du nicht bei dir.`^`n`n");
        output("Du wirfst deine `^".$session['user']['gold']."`x Gold auf den Schreibtisch und erklärst, dass du die ganzen `^{$_POST['amount']}`x Gold einzahlen möchtest.`n
                Der kleine alte Mann schaut dich nur verständnislos an. Durch diesen seltsamen Blick verunsichert, zählst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu
                soll ein Krieger auch rechnen können?");
    }
    else
    {
        output("`xDu zahlst `^{$_POST['amount']}`x Gold auf dein Konto ein. ");
        //debuglog("deposited " . $_POST['amount'] . " gold in the bank");
        $session['user']['goldinbank']+=$_POST['amount'];
        $session['user']['gold']-=$_POST['amount'];
        output("`xDu hast damit ".($session['user']['goldinbank']>=0?"ein Guthaben von":"Schulden in Höhe von")." `^".abs($session['user']['goldinbank'])."`x Gold auf deinem Konto und `^".$session['user']['gold']."`x Gold hast du bei dir.");
    }
}
else if ($_GET['op']=="borrow")
{
    if ($session['user']['reputation']<-35)
    {
        output("Misstrauisch schaut dich der Bankier eine Weile an. Dann, als ob er dein Gesicht erkannt hätte, atmet er ein und erklärt dir vorsichtig, dass er es nicht für klug hält,
                Leuten von deinem Schlag Geld zu leihen. Offenbar ist ihm dein schlechter Ruf zu Ohren gekommen, und er ist nun um den Ruf (und das Gold) seiner Bank besorgt...");
    }
    else
    {
        $maxborrow = $session['user']['level']*getsetting("borrowperlevel",20);
        output("<form action='bank.php?op=withdrawfinish' method='POST'>`xDu hast ".($session['user']['goldinbank']>=0?"ein Guthaben von":"Schulden in Höhe von")." `^".abs($session['user']['goldinbank'])." `xGold bei der Bank.`n`n",true);
        output("`xWieviel Gold möchtest du dir <u>l</u>eihen? <input id='input' name='amount' width=5 accesskey='l'> <input type='hidden' name='borrow' value='x'><input type='submit' class='button' value='Leihen'>`n`i(Mit deinem Level kannst du maximal `^$maxborrow `xGold leihen. Es wird zuerst Gold abgehoben, bis dein Konto leer ist. Der Restbetrag wird dann geliehen.)`i</form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
        addnav("","bank.php?op=withdrawfinish");
    }
}
else if ($_GET['op']=="withdraw")
{
    output("<form action='bank.php?op=withdrawfinish' method='POST'>`xDu hast `^".$session['user']['goldinbank']." `xGold bei der Bank.`n`n",true);
    output("`xWieviel Gold möchtest du a<u>b</u>heben? <input id='input' name='amount' width=5 accesskey='b'> <input type='submit' class='button' value='Abheben'>`n`i(Gib 0 oder gar nichts ein, um alles abzuheben.)`i</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
    addnav("","bank.php?op=withdrawfinish");
}
else if ($_GET['op']=="withdrawfinish")
{
    $_POST['amount']=abs((int)$_POST['amount']);
    if ($_POST['amount']==0)
    {
        $_POST['amount']=abs($session['user']['goldinbank']);
    }
    if ($_POST['amount']>$session['user']['goldinbank'] && $_POST['borrow']=="")
    {
        output("`\$FEHLER: Nicht genug Gold auf dem Konto.`^`n`n");
        output("Nachdem du darüber informiert wurdest, dass du `&".$session['user']['goldinbank']."`^ Gold auf dem Konto hast, erklärst du dem Männlein mit der Lesebrille, dass du
                gerne `&{$_POST['amount']}`^ davon abheben würdest.`n
                Der Bankier schaut dich bedauernd an und erklärt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und würdest es gerne nochmal versuchen.");
    }
    else if ($_POST['amount']>$session['user']['goldinbank'])
    {
        $lefttoborrow = $_POST['amount'];
        $maxborrow = $session['user']['level']*getsetting("borrowperlevel",20);
        if ($lefttoborrow<=$session['user']['goldinbank']+$maxborrow)
        {
            if ($session['user']['goldinbank']>0)
            {
                output("`xDu nimmst deine verbleibenden `^".$session['user']['goldinbank']."`x Gold und ");
                $lefttoborrow-=$session['user']['goldinbank'];
                $session['user']['gold']+=$session['user']['goldinbank'];
                $session['user']['goldinbank']=0;
                //debuglog("withdrew " . $_POST['amount'] . " gold from the bank");
            }
            else
            {
                output("`xDu ");
            }
            if ($lefttoborrow-$session['user']['goldinbank'] > $maxborrow)
            {
                output("fragst, ob du `^$lefttoborrow`x Gold leihen kannst. Der Bankier informiert dich darüber, dass er dir in deiner gegenwärtigen Situation nur `^$maxborrow`x Gold geben kann.");
            }
            else
            {
                output("leihst dir `^$lefttoborrow`x Gold.");
                $session['user']['goldinbank']-=$lefttoborrow;
                $session['user']['gold']+=$lefttoborrow;
                //debuglog("borrows $lefttoborrow gold from the bank");
            }
        }
        else
        {
            output("`xMit den schlappen `^{$session['user']['goldinbank']}`x Gold auf deinem Konto bittest du um einen Kredit von `^".($lefttoborrow-$session['user']['goldinbank'])."`x
                    Gold, aber der Bankier informiert dich darüber, dass du mit deinem Level höchstens `^$maxborrow`x Gold leihen kannst.");
        }
    }
    else
    {
        output("`xDu hast `^{$_POST['amount']}`x Gold von deinem Bankkonto abgehoben. ");
        $session['user']['goldinbank']-=$_POST['amount'];
        $session['user']['gold']+=$_POST['amount'];
        //debuglog("withdrew " . $_POST['amount'] . " gold from the bank");
        output("Du hast damit `^".$session['user']['goldinbank']."`x Gold auf deinem Konto und `^".$session['user']['gold']."`x Gold hast du bei dir.`b");
    }
}
// Weiterführende Links
addnav('Finanzen');
if ($session['user']['goldinbank']>=0) {
    addnav("Abheben","bank.php?op=withdraw");
    addnav("Einzahlen","bank.php?op=deposit");
    if (getsetting("borrowperlevel",20)) {
        addnav("Kredit aufnehmen","bank.php?op=borrow");
    }
} else {
    addnav("Schulden begleichen","bank.php?op=deposit");
    if (getsetting("borrowperlevel",20)) {
        addnav("Mehr leihen","bank.php?op=borrow");
    }
}
addnav('Schließfachverwaltung','bankfach.php');
if (getsetting("allowgoldtransfer",1)) {
    addnav('Versand');
    if ($session['user']['level']>=getsetting("mintransferlev",3) || $session['user']['dragonkills']>0) {
        addnav("Gold überweisen","bank.php?op=transfer");
    }
    addnav("Edelstein versenden","bank.php?op=gemtrans");
}
addnav('Zurück');
addnav("Z?Zum Marktplatz","market.php");

page_footer();

?>

