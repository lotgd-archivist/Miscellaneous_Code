
<?php

// 09072004

/*************************************************************
 * HUNTER'S LODGE for LoGD 0.9.7 ext (GER)
 * by weasel and anpera
 *************************************************************/

require_once "common.php";

$cost=array(
'charm'        =>  10, //Charmepunkte erwerben
'reputation' => 10
);

addcommentary();
place();
page_header("Jägerhütte");
$session['user']['standort'] = "Jägerhütte";
addnav("Zurück zum Stadtplatz", "village.php");
addnav("Hexfarben Generator Atrahor", "https://www.atrahor.de/static/farbverlauf_hc.html", false, false, true);



if ($_GET[op] != "points") addnav("Punkte", "lodge.php?op=points");
if ($_GET[op] == "points") addnav("Empfehlungen", "referral.php");

$config = unserialize($session['user']['donationconfig']);
$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];

if ($_GET['op'] == "") {

    output("`c<table width=600'><tr><td><div align=justify>
Du machst dich außerhalb Eassos, über einen  kleinen Waldweg, in Richtung der Jägerhütte auf.`n
Es dauert auch nicht lange, da siehst du sie vor dir zwischen ein paar Bäumen auftauchen. Dir ist bewusst, dass nicht jeder
dort hinein kann, du musst schon ein wahrer Held sein. Daher versuchst du möglichst imposant auszusehen.</div></tr></td></table>`c`n`n", true);
    if ($session['user']['donation'] <= 10 && $session['user']['rp_char'] > 1) {
        output("`c<table width='600'><tr><td>
Jedoch geht dies erfolglos aus. Du kommst hier erst rein, wenn du etwas erfolgreicher bist.`n
Ma gibt dir den Tipp, dass du mindestens `410 Donationspunkte`0 besitzen solltest...
</tr></td></table>`c`n`n", true);
        addnav("Empfehlungen", "referral.php");
    }
    if ($session['user']['donation'] >= 10 || $session['user']['rp_char'] == 1) {
        output("`c<table width='600'><tr><td><div align=justify>
Auf einer großen Liste an der Wand siehst du auch wie viele Punkte du noch hast. Dort steht:`n
Du besitzt noch `4`b$pointsavailable`b`0 Donationpuunkte.`n
Damit bist du eigentlich ganz zufrieden, aber warum nicht ein größerer Held werden?`n
`n
</div></tr></td></table>`c`n", true);
        viewcommentary("hunterlodge", "`nSprich mit den Helden:`n", 25);
        addnav("Punkte einsetzen");

        if ($config['namechange'] == 1) {
            if ($session['user']['rp_char'] == 1)
                addnav("Farbiger Name", "lodge.php?op=namechange");
            else
                addnav("Farbiger Name (50 Punkte)", "lodge.php?op=namechange");
        } else {
            if ($session['user']['rp_char'] == 1)
                addnav("Farbiger Name", "lodge.php?op=namechange");
            else
                addnav("Farbiger Name (500 Punkte)", "lodge.php?op=namechange");
        }
        
        addnav('Charmepunkte ('.$cost['charm'].' Punkte)','lodge.php?op=charm');
        //addnav("Charmepunkte abfragen (20 Punkte)", "lodge.php?op=charm");
        if ($session['user']['rp_char'] > 1) addnav("10 Nächte in der Kneipe (50 Punkte)", "lodge.php?op=innstays");
        addnav("Edelsteine kaufen (je 2 für 50 Punkte)", "lodge.php?op=gems");
        if ($session['user']['rp_char'] > 1) addnav("Extra Waldkämpfe für 30 Tage (150 Punkte)", "lodge.php?op=forestfights");
        if ($session['user']['rp_char'] > 1) addnav("Heilerin Golinda für 30 Tage (150 Punkte)", "lodge.php?op=golinda");
        addnav("Zur Burg reiten (100 Punkte)", "lodge.php?op=reiten1");
        if ($session['user']['rp_char'] > 1) addnav("PvP-Immunität (300 Punkte)", "lodge.php?op=immun");
        if ($session[user][house] > 0 && $session[user][housekey] == $session[user][house]) addnav("Hausschlüssel", "lodge.php?op=keys1");

        //if ($session['user']['donation'] >= 2000 && $pointsavailable >= 100 || $session['user']['rp_char'] == 1) {
        addnav("Sonderbonus");
        //    if ($session['user']['rp_char'] == 1)
        addnav("Titel ändern", "lodge.php?op=titeel1");
        addnav("Login ändern", "lodge.php?op=loginchange");
        //    else
        //        addnav("Titel ändern (100 Punkte)", "lodge.php?op=titeel1");
        //} else {
        //    if ($pointsavailable >= 100)
        //       addnav('Farbloser Titel (100 Punkte)', 'lodge.php?op=nocotit1');
        //}
    } else {
        output("`cDu ziehst die Karte deines Lieblingsgasthauses heraus, wo 9 von 10 Slots mit dem kleinen Profil von Cederik abgestempelt sind. 
                `n`n 
                Der Türsteher schaut flüchtig auf deine Karte, rät dir nicht soviel zu trinken und weist dir den Weg zurück.`c");
    }
} else if ($_GET['op'] == "points") {
    addnav("Zurück zur Lodge", "lodge.php");
    output("`bPunkte:`b`n`n
       Legend of the Green Dragon bietet dir die Möglichkeit, spezielle \"Donationpoints\" zu sammeln, mit denen du Sonderfunktionen freischalten kannst.`n
      Diese Punkte gibt es für besondere (geheime) Leistungen, sie können gekauft werden und für sogenannte \"Referrals\" (Empfehlungen) als Belohnung gesammelt werden. Erst wenn du mindestens 10 Donationpoints hast, kommst du in die Jägerhütte.`n`n
    Klicke im Eingangsbereich der Jägerhütte auf \"Empfehlungen\", wenn du wissen willst, wie du auf diesem Weg an Donationpoints kommst.");
    output("`n`nUm Punkte zu kaufen, überweise `b1 Euro pro 100 Punkte`b auf das Konto des Admins.`n`bSchicke dazu bitte eine Hilfsanfrage  oder eine E-Mail an " . getsetting("gameadminemail", "") . " ab, um die Bankverbindung zu erfahren");
    if (getsetting("paypalemail", "")) output(", oder benutze den PayPal Link \"ADMIN\" oben rechts");
    output(".`b`nDas Geld wird ausschliesslich zur Finanzierung des Servers verwendet.`n");
//output("`nWenn du den Programmierer von LoGD belohnen willst, kannst du pro gespendetem US-\$ ebenfalls 100 Punkte kassieren.
//Schicke dazu irgendeinen Beweis deiner Spende, z.B. einen Screenshot der PayPal-Bestätigung, an ".getsetting("gameadminemail","").". Für eine Spende an den Programmierer (Eric Stevens a.k.a. MightyE) benutze den PayPal-Link, der auf jeder Seite oben rechts zu finden ist.");
    output("`n`n
       `bDas kannst du mit diesen Punkten anstellen:`b`n
        - Umsonst in der Kneipe wohnen (10 Nächte für 50 Punkte).`n 
    - Edelsteine kaufen (2 Stück für 50 Punkte)`n
        - Zusätzliche Waldkämpfe kaufen (150 Punkte für 30 Tage lang 1 extra Kampf; maximal 5 mehr pro Tag)!`n
    - 'Zur Burg reiten' im Wald freischalten (100 Punkte),`n
    - PvP-Immunität kaufen (300 Punkte für permanente Immunität)`n
        - Einen farbigen Namen machen (500 Punkte). Umfärben kostet nur noch 50 Punkte. `n
    - Anzeige der Charmepunkte (20 Punkte)`n
    - Ersatzschlüssel (10) und zusätzliche Schlüssel (50) für dein Haus kaufen.`n 
    - Ab 2000 gesammelten Punkten (ob ausgegeben oder nicht) kannst du dir für 50 Punkte einen eigenen Titel aussuchen.`n
    `n`n`7Du hast noch `\$`b$pointsavailable`b`7 Punkte von insgesamt `4" . $session[user][donation] . " `7gesammelten Punkten übrig. 
        ");
} elseif ($_GET['op'] == "golinda") {
    output("30 Tage Zugang zu Golinda der Heilerin kosten 100 Punkte. Golinda heilt zum halben Preis.");
    if ($pointsavailable < 150) {
        output("`n`n`\$Du hast nicht genug Punkte!`0");
    } else {
        addnav("Betätige Zugang zu Golinda");
        addnav("JA", "lodge.php?op=golindaconfirm");
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "golindaconfirm") {
    if ($pointsavailable >= 150) {
        $config['healer'] += 30;
        output("J. C. Peterson gibt dir eine Karte und sagt \"Mit dieser Karte kannst du an 30 verschiedenen Tagen bei Golinda vorstellig werden.\"");
        $session['user']['donationspent'] += 150;
    }
    addnav("Zurück zur Lodge", "lodge.php");

} elseif ($_GET['op'] == "reiten1") {
    if ($config['castle']) {
        output("Du hast diese Option bereits gekauft. Um zur Burg zu kommen, brauchst du ansonsten nur ein `bPferd`b. Ein `iPferd`i ist ein Tier der Kategorie 'Pferde' in Mericks Stall.");
    } else {
        output("Hiermit schaffst du dir die Möglichkeit, mit einem Reittier im Wald auch zur Burg reiten zu können. Du kannst nur auf Pferden reiten, also die Tiere in Merick's Stall, die in der Kategorie 'Pferde' stehen.");
        if ($pointsavailable < 100) {
            output("`n`n`\$Du hast nicht genug Punkte!`0");
        } else {
            addnav("Betätige Freischaltung");
            addnav("JA", "lodge.php?op=reiten2");
        }
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "reiten2") {
    if ($pointsavailable >= 100) {
        $config['castle'] = 100;
        output("J. C. Peterson gibt dir eine Karte und sagt \"Mit dieser Karte findest du den Weg zur Burg, wenn du ein Pferd hast.\"");
        $session['user']['donationspent'] += 100;
    }
    addnav("Zurück zur Lodge", "lodge.php");

} elseif ($_GET['op'] == "forestfights") {
    if (!is_array($config['forestfights'])) $config['forestfights'] = array();
    output("1 Extra Waldkampf pro Tag für 30 Tage kostet 100 Punkte. Du bekommst einen extra Waldkampf an jedem Tag, an dem du spielst.`n");
    if ($pointsavailable < 150) {
        output("`n`n`\$Du hast nicht genug Punkte!`0");
    } else {
        addnav("Bestätige Extra Waldkämpfe");
        addnav("JA", "lodge.php?op=fightbuy");
    }
    addnav("Zurück zur Lodge", "lodge.php");
    reset($config['forestfights']);
    while (list($key, $val) = each($config['forestfights'])) {
        //output("Du hast noch {$val['left']} Tage, an denen zu einen zusätzlichen Waldkampf für deine am {$val['bought]} bekommst.`n");
        output("Du hast noch {$val['left']} Tage, an denen zu einen zusätzlichen Waldkampf für deine am {$val['bought']} bekommst.`n");
    }
} elseif ($_GET['op'] == "fightbuy") {
    if (count($config['forestfights']) >= 5) {
        output("Du Kannst maximal 5 extra Waldkämpfe haben pro Tag.`n");
    } else {
        if ($pointsavailable > 0) {
            array_push($config['forestfights'], array("left" => 30, "bought" => date("M d")));
            output("Du wirst in den nächsten 30 Tagen, an denen du spielst, einen extra Waldkampf haben.");
            $session['user']['donationspent'] += 150;
        } else {
            output("Extra Waldkämpfe zu kaufen kostet 150 Punkte, aber du hast nicht so viele.");
        }
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "innstays") {
    output("10 freie Übernachtungen in der Kneipe kosten 50 Punkte. Bist du dir sicher, dass du das willst?");
    if ($pointsavailable < 50) {
        output("`n`n`\$Du hast nicht genug Punkte!`0");
    } else {
        addnav("Bestätige 10 freie Übernachtungen");
        addnav("JA", "lodge.php?op=innconfirm");
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "innconfirm") {
    if ($pointsavailable >= 50) {
        output("J. C. Petersen gibt dir eine Karte und sagt \"Coupon: Gut für 10 Übernachtungen in der Boar's Head Kneipe\"");
        $config['innstays'] += 10;
        $session['user']['donationspent'] += 50;
    }
    addnav("Zurück zur Lodge", "lodge.php");
} /*elseif ($_GET['op'] == "charm") {
    output("Du fragst J. C. Petersen, ob er dein Aussehen beurteilen kann. Er mustert dich kurz und verspricht dir dann, dass er dir für die Kleinigkeit von 20 Punkten eine ehrliche Antwort geben wird.");
    if ($pointsavailable < 50) {
        output("`n`n`\$Du hast nicht genug Punkte!`0");
    } else {
        addnav("Bestätige Charmepunkt-Anzeige");
        addnav("JA", "lodge.php?op=charmconfirm");
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "charmconfirm") {
    if ($pointsavailable >= 20) {
        if ($session['user']['charm'] <= 0) output("J. C. Petersen schaut dich angewidert an und sagt \"Du bist hässlich wie die Nacht, ich kann einfach nichts Schönes an dir finden.\"");
        elseif ($session['user']['charm'] == 1) output("J. C. Petersen schaut dich kurz an und sagt \"Du bist genauso häßlich wie jeder gemeine Bürger, mehr als `^1 Punkt`0 wird dir kein Preisrichter geben.\"");
        else output("J. C. Petersen mustert dich noch einmal ganz genau und sagt \"Du bist `^" . $session['user']['charm'] . "`0 mal so schön wie der gemeine Bürger.\"");
        $session['user']['donationspent'] += 20;
    }*/
elseif ($_GET['op']== "charm") //Charmpunkte erwerben
{
    output('2 Charmepunkte für `&'.($cost['charm']*2).' Punkte`0. Bist du dir sicher, dass du das willst?');
    if ($pointsavailable<$cost['charm']*2)
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige 2 Charmepunkte');
        addnav('JA','lodge.php?op=charmconfirm');
        if($pointsavailable>$cost['charm']*3)
        {
            allownav('lodge.php?op=charmconfirm');
            output('`n`nDu kannst auch mehr Charme (bis zu '.floor($pointsavailable/$cost['charm']).') erwerben.
                                                <form action="lodge.php?op=charmconfirm" method="post">
                                                <input type="text" name="amount" size=3 maxlength=3>
                                                <input type="submit" class="button" value="Charmpunkte erwerben">
                                                </form>', true);
        }
    }
}
elseif ($_GET['op']=='charmconfirm') //Edelsteinkauf bestätigen
{
    $amount=max(2,intval($_POST['amount']));
    $dp_cost=$amount*$cost['charm'];

    if ($pointsavailable>=$dp_cost)
    {
        output('Du erhältst '.$amount.' Charmepunkte.');
        $session['user']['charm']+=$amount;
        $session['user']['donationspent']+=$dp_cost;
        debuglog('Gab '.$dp_cost.'DP für Charmepunkte');
    }
    else
    {
        output('J. C. Petersen nimmt einen Zettel mit der Überschrift "`$Schlechtschein`0", schreibt deinen Namen und die Zahl '.($dp_cost-$pointsavailable).' darauf.
                                            `nEin Schlechtschein!? Es gibt doch gar keinen Schlechtschein! Da fällt dir ein, dass du dir für '.$pointsavailable.' Punkte ja `0höchstens '.floor($pointsavailable/$cost['charm']).'`0 Edelsteine leisten kannst.');
        addnav('Nochmal versuchen','lodge.php?op=charm');
    }
}    
elseif ($_GET['op']=='reputation') //Ansehen erwerben
{
    output('2 Ansehens-unkte für `&'.($cost['reputation']*2).' Punkte`0. Bist du dir sicher, dass du das willst?');
    if ($pointsavailable<$cost['reputation']*2)
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige 2 Ansehenspunkte');
        addnav('JA','lodge.php?op=reputationconfirm');
        if($pointsavailable>$cost['reputation']*3)
        {
            allownav('lodge.php?op=reputationconfirm');
            output('`n`nDu kannst auch mehr Ansehen (bis zu '.floor($pointsavailable/$cost['reputation']).') erwerben.
                                                <form action="lodge.php?op=reputationconfirm" method="post">
                                                <input type="text" name="amount" size=3 maxlength=3>
                                                <input type="submit" class="button" value="Ansehen erwerben">
                                                </form>', true);
        }
    }
}

elseif ($_GET['op']=='reputationconfirm') //Edelsteinkauf bestätigen
{
    $amount=max(2,intval($_POST['amount']));
    $dp_cost=$amount*$cost['reputation'];

    if ($pointsavailable>=$dp_cost)
    {
        output('Du erhältst '.$amount.' Ansehen.');
        $session['user']['reputation']+=$amount;
        $session['user']['donationspent']+=$dp_cost;
        debuglog('Gab '.$dp_cost.'DP für Ansehen');
    }
    else
    {
        output('J. C. Petersen nimmt einen Zettel mit der Überschrift "`$Schlechtschein`0", schreibt deinen Namen und die Zahl '.($dp_cost-$pointsavailable).' darauf.
                                            `nEin Schlechtschein!? Es gibt doch gar keinen Schlechtschein! Da fällt dir ein, dass du dir für '.$pointsavailable.' Punkte ja `0höchstens '.floor($pointsavailable/$cost['reputation']).'`0 Edelsteine leisten kannst.');
        addnav('Nochmal versuchen','lodge.php?op=reputation');
    }    
    
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "gems") {
    if ($pointsavailable < 50)
        output("`n`n`\$Du hast nicht genug Punkte!`0");

    else {
        $maxgems = floor($pointsavailable / 50) * 2;
        output("Du kannst maximal " . $maxgems . " Edelsteine für " . ($maxgems * 25) . " Donationpoints erstehen.`nWie viele Edelsteine möchtest du kaufen?`n");
        output('<form action="lodge.php?op=gemsconfirm" method="POST">
               <input name="gems">
               <input type="submit" class="button" value="kaufen">
               </form>', true);
        addnav('', 'lodge.php?op=gemsconfirm');
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "gemsconfirm") {
    if (!is_numeric($_POST['gems']) || $_POST['gems'] <= 0)
        output('`$Fehler bei der Eingabe.`0');

    else if ($_POST['gems'] > (floor($pointsavailable / 50) * 2))
        output('`$Nana, du wirst Petersen doch wohl nicht betrügen wollen?`0');

    else {
        output("J. C. Petersen gibt dir " . floor($_POST['gems']) . " Edelsteine und sagt \"Damit, mein Freund, wird dein Leben leichter werden.\"");
        $session['user']['gems'] += floor($_POST['gems']);
        $session['user']['donationspent'] += floor($_POST['gems']) * 25;
    }
    addnav("Zurück zur Lodge", "lodge.php");
} else if ($_GET['op'] == "titeel1") {
    addnav('Zurück zur Lodge', 'lodge.php');

    $n = $session['user']['name'];
    if ($session['user']['ctitle']) {
        $teil = $session['user']['ctitle'];
    } else {
        $teil = $session['user']['title'];
    }

    output("Dein kompletter Name lautet: `b" . $n . "`b`n`nWie soll dein Titel von nun an lauten?`n(Sende ein leeres Feld ab, wenn du deinen regulären Titel wieder haben willst.)`n", true);
    rawoutput("<form action='lodge.php?op=titeel2' method='POST'>
                    <input name='teil' value=\"" . HTMLEntities($teil) . "\"> 
                    <input type='submit' value='Vorschau'><br>
                    <input type='radio' id='none' value='1' name='title-option'>
                    <label for='none'> Kein Titel</label> 
                    <input type='radio' id='before' value='2' name='title-option'>
                    <label for='none'> Vor dem Namen</label>
                    <input type='radio' id='after' value='3' name='title-option'>
                    <label for='after'> Nach dem Namen</label>
                </form>");
    addnav("", "lodge.php?op=titeel2");
} elseif ($_GET['op'] == "titeel2") {
    addnav("Zurück zur Lodge", "lodge.php");

    $falsetitle = false;

    $opt = isset($_POST['title-option']) ? $_POST['title-option'] : 2;

    if ($opt > 1) {
        # Titel von nervigen Backslashes befreien
        $teil = stripslashes($_POST['teil']);

        # Titel von Farben befreien (für überprüfung ob es der gleiche wie ein regulär durch DKs zu bekommender Titel ist)
        $teil = preg_replace("/`[^" . $appoencode_str . "]/", "", $teil);

        // Anführungszeichen machen nur Probleme...
        $teil = str_replace('\'', '', $teil);
        $teil = str_replace('"', '', $teil);

        // Titel nicht leer, aber auch nix reguläres drin?
        if (trim(preg_replace('/`./', '', $teil)) == '') {
            $teil = $session['user']['title'];
            $_POST['teil'] = '';
        } /* else {
            # Überprüfen ob der Titel mit einem regulär durch DKs erhaltbaren Titel gleich ist
            $cleartitle = strtolower(preg_replace("/`./", "", $teil));
            foreach ($titles AS $this2) {
                if (strtolower($this2[0]) == $cleartitle || strtolower($this2[1]) == $cleartitle) {
                    $falsetitle = true;
                    break;
                }
            }
        } */
    }

    # Titelbegrenzung erhöht - überprüfung ob nicht mehr als 5000 Zeichen (wer das schafft ist krank!)
    if (strlen($teil) > 5000) {
        output("Du hast dir zwar einen neuen Titel verdient, aber so lang muss er ja nun wirklich nicht sein.");
        output("`n`n<a href='lodge.php?op=titeel1'>Lass es mich nochmal probieren</a>", true);
        addnav("", "lodge.php?op=titeel1");
    } /* elseif ($falsetitle) {
        output('Diesen Titel hast du nicht verdient. Bitte wähle einen eigenen.');
        output("`n`n<a href='lodge.php?op=titeel1'>Lass es mich nochmal probieren</a>", true);
        addnav("", "lodge.php?op=titeel1");
    } */ else {
        $n = $session['user']['name'];
        /*if($session['user']['ctitle'] == "") {
            $neu = $teil.substr($n, strlen($session['user']['title']));
        } 
        else {
            $neu = $teil.substr($n, strlen($session['user']['ctitle']));
        }*/
        if ($session['user']['ctitle'] == '')
            $n_only = trim(str_replace($session['user']['title'], '', $n));
        else
            $n_only = trim(str_replace($session['user']['ctitle'], '', $n));

        $new = appoencode($teil, true);

        switch ($opt) {
            case 1:
                $neu = appoencode($n_only);
                break;
            case 3:
                $neu = appoencode($n_only . " " . $new);
                break;
            case 2:
            default:
                $neu = appoencode($new . " " . $n_only);
        }

        if ($opt == 1) {
            output("Du möchtest also keinen Titel tragen`0 ?", true);
            $output .= "<form action=\"lodge.php?op=titeel3&opt=$opt\" method='POST'><input type='hidden' name='teil' value=\"$teil\"><input type='submit' value='Ja' class='button'>, ändere meinen Titel für 50 Punkte.</form>";
        } else {
            output("Dein neuer Titel soll " . $new . "`0 sein, dein Name also " . $neu . "`0 ?", true);
            $output .= "<form action=\"lodge.php?op=titeel3&opt=$opt\" method='POST'><input type='hidden' name='teil' value=\"$teil\"><input type='submit' value='Ja' class='button'>, ändere meinen Titel auf $new für 50 Punkte.</form>";
        }

        output("`n`n<a href='lodge.php?op=titeel1'>Nein, lass es mich nochmal probieren</a>", true);
        addnav("", "lodge.php?op=titeel1&opt=$opt");
        addnav("", "lodge.php?op=titeel3&opt=$opt");
    }
} elseif ($_GET['op'] == "titeel3") {
    addnav("Zurück zur Lodge", "lodge.php");

    $opt = $_GET['opt'] ? $_GET['opt'] : 2;
    //if ($pointsavailable >= 50 || $session['user']['rp_char'] == 1) {
    # Backslashes entfernen
    $teil = stripslashes($_POST['teil']);
    # Farben entfernen
    $teil = preg_replace("/`[^" . $appoencode_str . "]/", "", $teil);
    // Anführungszeichen machen nur Probleme...
    $teil = str_replace('\'', '', $teil);
    $teil = str_replace('"', '', $teil);

    // Titel nicht leer, aber auch nix reguläres drin?
    if (trim(preg_replace('/`./', '', $teil)) == '') {
        if ($opt == 1)
            $teil = $session['user']['title'];
        $_POST['teil'] = '';
    }

    if (strlen($teil) > 5200) {
        output("Du hast dir zwar einen neuen Titel verdient, aber so lang muss er ja nun wirklich nicht sein.");
        output("`n`n<a href='lodge.php?op=titeel1'>Lass es mich nochmal probieren</a>", true);
        addnav("", "lodge.php?op=titeel1");
    } else {
        $news = "`&{$session['user']['name']}`^ ist nun bekannt als `^";
        $n = $session['user']['name'];

        $teil = trim($teil);
        if ($session['user']['ctitle'] == '')
            $n_only = trim(str_replace($session['user']['title'], '', $n));
        else
            $n_only = trim(str_replace($session['user']['ctitle'], '', $n));

        switch ($opt) {
            case 1:
                $neu = $n_only;
                break;
            case 3:
                $neu = $n_only . " " . $teil;
                break;
            case 2:
            default:
                $neu = $teil . " " . $n_only;
        }

        //if ($session['user']['rp_char'] > 1)
        //    $session['user']['donationspent'] += 100;

        $session['user']['name'] = $neu;
        if ($teil > "") {
            $session['user']['ctitle'] = $teil;
        } else {
            /*if($session['user']['ctitle'] == "") {
                $neu2 = substr($n, strlen($session['user']['title']));
            }
            else {
                $neu2 = substr($n, strlen($session['user']['ctitle']));
            }*/
            /*
            if ($session['user']['ctitle'] == '')
                $n_only = str_replace($session['user']['title'], '', $n);
            else
                $n_only = str_replace($session['user']['ctitle'], '', $n);
            $session['user']['name'] = $session['user']['title'] . $n_only;
            */
            $session['user']['ctitle'] = "";
        }

        $news .= "{$session['user']['name']}`&!";
        addnews($news);
        output("Gratulation, dein neuer Name ist jetzt  {$session['user']['name']}`0!`n`n", true);
    }
    //} else {
    //    output("Den Titel zu ändern kostet 100 Punkte, aber du hast nur $pointsavailable Punkte.");
    //}

} elseif ($_GET['op'] == "loginchange") {
    addnav("Zurück zur Lodge", "lodge.php");

    output("`&Möchtest du deinen `bNamen`b ändern, so kannst du dies hier auch ohne kompletten Verlust der weltlichen Güter tun.`n`n");
    output("`\$`bWICHTIG`b: Zum einloggen musst du dann natürlich deinen neuen Namen verwenden ;)!`0");

    addnav("", "lodge.php?op=loginchange2");
    output("<form action='lodge.php?op=loginchange2' method='POST'>", true);
    output("<table border='0' cellpadding='1' cellspacing='1' align='left'><tr>", true);
    output("<tr><td>Wie soll dein neuer Name lauten?</td><td><input name='name' value='".$session['user']['login']."'></td></tr>", true);
    output("<tr><td colspan='2'>`c<input type='submit' class='button' value='Namen ändern'>`c", true);
    output("</table></form>", true);
} elseif ($_GET['op'] == "loginchange2") {
    $shortname = trim(strip_tags($_POST['name']));
    $shortname = preg_replace("/`[" . $appoencode_str . "]/", "", $shortname);

    if (soap($shortname) != $shortname) {
        output("`\$Fehler`^: Unzulässiger Name. Bitte überdenke deinen Namen nochmal.");
        $_GET['op'] = "";
    } else {
        $blockaccount = false;
        $sql = sprintf("SELECT login FROM accounts WHERE login='%s'", $_POST['name']);
        $result = db_query($sql);
        if (db_num_rows($result) > 0) {
            $blockaccount = true;
            $msg .= "Ein Charakter mit diesem Namen existiert bereits.`n";
        }
        if (strlen($shortname) < 3) {
            $msg .= "Dein Name muss mindestens 3 Buchstaben lang sein.`n";
            $blockaccount = true;
        }
        if (strlen($shortname) > 25) {
            $msg .= "Der Name ist zu lang. Maximal 25 Buchstaben zugelassen.`n";
            $blockaccount = true;
        }
    }

    if (!$blockaccount) {
        $newtitle = $titles[$session['user']['dragonkills']][$session['user']['sex']];
        $newname = $newtitle . " " . $shortname;
        output("Dein neuer Name wäre ".$newname."`n");
        output("<a href='lodge.php?op=loginchange3&login=".urlencode($shortname)."&name=".urlencode($newname)."'>`3Bestätigen`0</a>", true);
        output("<a href='lodge.php?op=loginchange'>`4Abbrechen`0</a>", true);

        addnav("", "lodge.php?op=loginchange");
        addnav("", "lodge.php?op=loginchange3&login=".urlencode($shortname)."&name=".urlencode($newname));
    } else {
        output("`\$Fehler`^:`n$msg`&");
        $_GET['op'] = "";
        addnav("Nochmal versuchen", "lodge.php?op=loginchange");
    }
    addnav("Zurück");
    addnav("Zurück zur Lodge", "lodge.php");

} elseif($_GET['op'] == "loginchange3") {
    $shortname = urldecode($_GET['login']);
    $name = urldecode($_GET['name']);

    output("Herzlichen Glückwunsch, dein neuer Login ist ".$shortname);

    $oldname = $session['user']['name'];
    $session['user']['login'] = $shortname;
    $session['user']['name'] = $name;
    $session['user']['title'] = $titles[$session['user']['dragonkills']][$session['user']['sex']];
    $session['user']['ctitle'] = "";

    addnews($oldname . " is nun bekannt als ". $session['user']['name']);

    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "namechange") {
    addnav("Zurück zur Lodge", "lodge.php");
    output("`bNamens Farbe ändern`b`n`n");

    if ($session['user']['rp_char'] > 1) {
        if ($config['namechange'] == 1) {
            output("Da du schon vorher viele Punkte für die Farbänderung gegeben hast kostet es dich diesmal nur 50 Punkte .");
        } else {
            output("Da es deine erste Farbänderung ist kostet es dich 500 Punkte . Beim nächsten Wechsel fallen nur 50 Punkte Kosten an");
        }
    }
    output("`n`nDein geänderter Name muss der selbe Name sein wie vor der Farbänderung, nur dass er jetzt die Farbcodes enthalten darf.`n`n");
    $n = $session[user][name];
    if ($session[user][ctitle] == "") {
        //$x = strpos($n,$session[user][title])+1;
        //$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][title]))));
        $regname = str_replace(array($session['user']['title'] . ' ', '`0'), '', $n);
    } else {
        //$x = strpos($n,$session[user][ctitle])+1;
        //$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][ctitle]))));
        $regname = str_replace(array($session['user']['ctitle'] . ' ', '`0'), '', $n);
    }
    output("Deine Name bisher ist: $regname", true);
    output(", und so wird er aussehen: $regname", true);
    output("`n`n`0Wie soll dein Name aussehen ?`n");
    $output .= "<form action='lodge.php?op=namepreview' method='POST'><input name='newname' value=\"" . HTMLEntities($regname) . "\"> <input type='submit' value='Vorschau'></form>";
    addnav("", "lodge.php?op=namepreview");
} elseif ($_GET['op'] == "namepreview") {
    addnav("Zurück zur Lodge", "lodge.php");
    $n = $session[user][name];
    if ($session[user][ctitle] == "") {
        //$x = strpos($n,$session[user][title])+1;
        //$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][title]))));
        $regname = str_replace(array($session['user']['title'] . ' ', '`0'), '', $n);
    } else {
        //$x = strpos($n,$session[user][ctitle])+1;
        //$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][ctitle]))));
        $regname = str_replace(array($session['user']['ctitle'] . ' ', '`0'), '', $n);
    }
    $_POST['newname'] = str_replace("`0", "", $_POST['newname']);
    //        $comp1 = strtolower($session['user']['login']);
    $comp1 = strtolower(preg_replace("/\s*|#[0-9a-f]{6}|[`][" . $appoencode_str . "]/i", "", $regname)); // no black, no background colors
    $comp2 = strtolower(preg_replace("/\s*|#[0-9a-f]{6}|[`][" . $appoencode_str . "]/i", "", $_POST['newname']));

    //$output.="[$comp1] compared to [$comp2]";
    if ($comp1 != $comp2) $msg .= "Dein neuer Name muss genau so bleiben, wie dein alter Name. Du kannst die Gross-/Kleinschreibung ändern, Farbcodes entfernen oder hinzufügen, aber ansonsten muss alles so bleiben. Du wählst {$_POST['newname']}`0`n";
    if (strlen($_POST['newname']) > 5000) $msg .= "Dein neuer name ist zu lang, inklusive Farbcodes darf er nicht länger als 5000 Zeichen sein.`n";
    $colorcount = 0;
    for ($x = 0; $x < strlen($_POST['newname']); $x++) {
        if (substr($_POST['newname'], $x, 1) == "`") {
            $x++;
            $colorcount++;
        }
    }
    if ($colorcount > getsetting("maxcolors", 120)) {
        $msg .= "Du hast zu viele Farben in deinem Namen benutzt. Du kannst maximal " . getsetting("maxcolors", 120) . " Farbcodes benutzen.`n";
    }
    if ($msg == "") {
        output("Deine Name wird so aussehen: " . $_POST['newname'], true);
        output("`n`n`0Ist es das was du willst?`n`n", true);
        if ($session['user']['rp_char'] > 1)
            $p = ($config['namechange'] == 1 ? 50 : 500);
        $output .= "<form action=\"lodge.php?op=changename\" method='POST'><input type='hidden' name='name' value=\"" . HTMLEntities($_POST['newname']) . "\"><input type='submit' value='Ja' class='button'>, ändere meinen Namen auf " . appoencode("{$_POST['newname']}`0", true) . " für $p Punkte.</form>";
        output("`n`n<a href='lodge.php?op=namechange'>Nein, lass es mich nochmal probieren</a>", true);
        addnav("", "lodge.php?op=namechange");
        addnav("", "lodge.php?op=changename");
    } else {
        output("`bFalscher Name`b`n$msg", true);
        output("`n`nDeine Name bisher ist: $regname", true);
        output("`0, und wird so aussehen $regname", true);
        output("`n`nWie soll dein Name aussehen?`n");
        $output .= "<form action='lodge.php?op=namepreview' method='POST'><input name='newname' value=\"" . HTMLEntities($regname) . "\"> <input type='submit' value='Vorschau'></form>";
        addnav("", "lodge.php?op=namepreview");
    }
} elseif ($_GET['op'] == "immun") {
    if ($session['user']['pvpflag'] == "5013-10-06 00:42:00") {
        output("J. C. Petersen nickt dir zu und gibt dir zu verstehen, dass du noch immer unter seinem Schutz stehst.");
    } elseif ($session['user']['pvpflag'] == "1986-10-06 00:42:00") {
        output("J. C. Petersen zeigt dir einen Vogel und macht dir sehr schnell klar, dass er vorerst nichts mehr für dich tun kann. Er kann niemanden schützen, der selbst mordend durchs Land zieht.");
    } else {
        output("Du fragst J. C. Petersen, ob er deinen Aufenthaltsort vor herumstreifenden Dieben und Mördern verbergen kann.");
        output(" Er nickt und verspricht dir, dass dir für die Kleinigkeit von 300 Punkten niemand mehr ein Haar krümmen wird. Er wird auch mit Dag Durnick reden. Allerdings kann er für nichts mehr garantieren, wenn du selbst einen Mord begehst!`n`n");
        output("300 Punkte für permanente PvP Immunität ausgeben?`n(Die Immunität verfällt, sobald du selbst PvP machst, oder ein Kopfgeld auf jemanden aussetzt und kann dann `bnicht`b mehr so schnell erneuert werden!)");
        addnav("Immunität bestätigen?");
        addnav("JA", "lodge.php?op=immunconfirm");
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "immunconfirm") {
    if ($pointsavailable >= 300) {
        output("J. C. Petersen nutzt seinen Einfluss, um dich für PvP-Spieler unangreifbar zu machen. Es kann auch kein (weiteres) Kopfgeld auf dich ausgesetzt werden.`nDenke daran, dass du nur so lange geschützt bist, bis du selbst jemanden angreifst, oder jemanden auf Dag's ");
        output(" Kopfgeldliste setzt. Tust du das, kann selbst Petersen dir in Zukunft nicht mehr helfen.");
        $session[user][pvpflag] = "5013-10-06 00:42:00";
        $session['user']['donationspent'] += 300;
    } else {
        output("Du hast nicht genug Punkte!");
    }

} elseif ($_GET['op'] == "changename") {
    $p = ($config['namechange'] == 1 ? 50 : 500);
    if ($pointsavailable >= $p || $session['user']['rp_char'] == 1) {
        if ($session['user']['rp_char'] > 1) {
            $session['user']['donationspent'] += $p;
            $config['namechange'] = 1;
        }

        $news = "{$session['user']['name']}`^ ist nun bekannt als `0";

        if ($session['user']['ctitle'] == "")
            $session['user']['name'] = $session['user']['title'] . " " . $_POST['name'] . "`0";
        else
            $session['user']['name'] = $session['user']['ctitle'] . " " . $_POST['name'] . "`0";

        $news .= "{$session['user']['name']}`^!";
        addnews($news);
        output("Gratulation, dein neuer Name ist jetzt  {$session['user']['name']}`0!`n`n", true);
    } else {
        output("Eine Farbänderung kostet $p Punkte, aber du hast nur $pointsavailable Punkte.");
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "keys1") {
    $sql = "SELECT * FROM items WHERE owner=0 AND class='Schlüssel' AND value1=" . $session[user][house] . " ORDER BY id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)) {
        output("`b`c`&Verlorene Schlüssel:`c`b<table cellpadding=2 align='center'><tr><td>`bNr.`b</td><td>`bAktion`b</td></tr>", true);
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
            output("<tr class='$bgcolor'><td align='center'>$row[value2]</td><td><a href='lodge.php?op=keys2&id=$row[id]'>Ersetzen (10 Punkte)</a></td></tr>", true);
            addnav("", "lodge.php?op=keys2&id=$row[id]");
        }
        output("</table>", true);
    } else {
        output("Der Schlüsselsatz für dein Haus ist komplett. Willst du einen zusätzlichen Schlüssel für 50 Punkte kaufen?");
        addnav("Zusätzlicher Schlüssel (50 Punkte)", "lodge.php?op=keys2&id=new");
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "keys2") {
    if ($_GET[id] == "new") {
        output("`b50`b ");
    } else {
        output("`b10`b ");
    }
    output("Punkte für diesen Schlüssel ausgeben?");
    addnav("Schlüsselkauf bestätigen?");
    addnav("JA", "lodge.php?op=keys3&id=" . $_GET[id] . "");
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == "keys3") {
    if ($_GET[id] == "new") {
        if ($pointsavailable < 50) {
            output("Du hast nicht genug Punkte übrig.");
        } else {
            $sql = "SELECT * FROM items WHERE class='Schlüssel' AND value1=" . $session[user][house] . " ORDER BY id ASC";
            $result = db_query($sql) or die(db_error(LINK));
            $nummer = db_num_rows($result) + 1;
            db_free_result($result);
            $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel'," . $session[user][acctid] . ",'Schlüssel'," . $session[user][house] . ",$nummer,0,0,'Schlüssel für Haus Nummer " . $session[user][house] . "')";
            db_query($sql) or die(db_error(LINK));
            $session['user']['donationspent'] += 50;
            output("Du hast jetzt `b$nummer`b Schlüssel für dein Haus! Überlege gut, an wen du sie vergibst.");
        }
    } else {
        if ($pointsavailable < 10) {
            output("Du hast nicht genug Punkte übrig.");
        } else {
            $nummer = $_GET[id];
            $sql = "UPDATE items SET owner=" . $session[user][acctid] . ",hvalue=0 WHERE id=$nummer";
            db_query($sql);
            $session['user']['donationspent'] += 10;
            output("Der Schlüssel wurde ersetzt.");
        }
    }
    addnav("Zurück zur Lodge", "lodge.php");
} elseif ($_GET['op'] == 'nocotit1') {
    if (isset($_POST['newnocotit'])) {
        if (empty($_POST['newnocotit'])) {
            output('Du hast keinen Titel eingegeben. Bitte gib einen Titel ein!');

            addnav('Aktionen');
            addnav('Noch einmal', 'lodge.php?op=nocotit1');
        } elseif (is_numeric($_POST['newnocotit'])) {
            output('Dein Titel sollte aus Buchstaben bestehen und nicht aus Zahlen! Bitte wähle einen anderen Titel.');

            addnav('Aktionen');
            addnav('Noch einmal', 'lodge.php?op=nocotit1');
        } else {
            // Name ohne Titel erzeugen
            if (strpos($session['user']['name'], $session['user']['title']) === false && $session['user']['ctitle'] != '')
                $notitname = str_replace($session['user']['ctitle'] . ' ', '', $session['user']['name']);
            elseif ($session['user']['title'] != '')
                $notitname = str_replace($session['user']['title'] . ' ', '', $session['user']['name']);
            else
                $notitname = $session['user']['name'];

            // Titel bearbeiten
            $title = stripcolors($_POST['newnocotit']);
            $dont = array('`b', '`c', '`i', '\'', '"');
            $title = str_replace($dont, '', $title);

            // überprüfen ob der Titel leer ist. Wenn ja, zurück auf Systemtitel setzen.
            if ($title == '' || empty($title) || strlen(Str_replace(' ', '', $title)) == 0)
                $itlte = $session['user']['title'];

            // Ausgabe
            output('Dein Neuer Titel lautet ' . $title . '. Mit deinem Namen zusammen wäre das ' . $title . ' ' . $notitname . '`0. `n
                    Willst du den Titel wirklich ändern? `n`n
                    <center><table border="0" cellpadding="3" cellspacing="0">
                    <tr>
                        <td width="100" align="left">
                            <input type="button" class="button" value="Ja, ändern." onClick="location.href=\'lodge.php?op=nocotit2\'">
                        </td>
                        <td width="100" align="right">
                            <input type="button" class="button" value="Nein, nicht ändern" onClick="location.href=\'lodge.php?op=nocotit1\'">
                        </td>
                    </tr>
                    </table></center>', true);
            addnav('', 'lodge.php?op=nocotit2');
            addnav('', 'lodge.php?op=nocotit1');

            addnav('Aktionen');
            addnav('Ändern', 'lodge.php?op=nocotit2');
            addnav('Nicht ändern', 'lodge.php?op=nocotit1');

            $session['TitleNameData'] = array('name' => $notitname, 'title' => $title);
        }
    } else {
        if ($pointsavailable < 100)
            redirect('lodge.php');

        output('Gib hier den gewünschten Titel ein. (Farbcodes nicht erlaubt!) `n `n
                <form action="lodge.php?op=nocotit1" method="POST">
                    <input name="newnocotit">
                    <input type="submit" class="button" value="Abschicken">', true);

        addnav('', 'lodge.php?op=nocotit1');
    }

    addnav('Umkehren');
    addnav('zur Jägerhütte', 'lodge.php');
} elseif ($_GET['op'] == 'nocotit2') {
    $session['user']['name'] = $session['TitleNameData']['title'] . ' ' . $session['TitleNameData']['name'];
    $session['user']['ctitle'] = $session['TitleNameData']['title'];

    unset($session['TitleNameData']);

    output('Dein Name wurde erfolgreich zu ' . $session['user']['name'] . '`0 geändert!');

    $session['user']['donationspent'] += 100;

    addnav('Umkehren');
    addnav('zur Jägerhütte', 'lodge.php');
}


$session['user']['donationconfig'] = serialize($config);

page_footer();

?>

