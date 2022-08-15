
<?php
// Dorfamt von Atrahor
// Basiert auf:
// Dorfamt
// Make by Kev
// Make for www.logd.de.to
// 05-09-2004 September
// E-Mail: logd@gmx.net
// Website: www.logd.de.to
// Copyright 2004 by Kev

// Ergänzungen und Erweiterungen von Dragonslayer, Maris, Talion

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');

define("TEMPLE_SERVANT_TURNS",2);
define("TEMPLE_SERVANT_MINDAYS",5);
define("TEMPLE_SERVANT_MAX",5);

function show_servant_list ($admin_mode=0) {

        global $session;

        $sql = "SELECT a.name,a.profession,a.acctid,a.login,a.loggedin,a.daysinjail,i.temple_servant FROM accounts a
                                LEFT JOIN account_extra_info i ON i.acctid=a.acctid
                                WHERE a.profession=".PROF_TEMPLE_SERVANT;
        $sql .= " ORDER BY profession DESC, name";
        $res = db_query($sql) or die (db_error(LINK));

        if(db_num_rows($res) == 0) {
                output("`n`iEs gibt derzeit keine Strafdienstleistenden, die die ihnen auferlegte Kerkerstrafe durch ehrliche Arbeit reduzieren wollen.`i`n");
        }
        else {

                output('<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999"><tr class="trhead"><td>Nr.</td><td>Name</td><td>Haftstrafe</td><td>Arbeitstage bisher</td><td>Status</td>'.($admin_mode ? '<td>Aktionen</td>' : '').'</tr>',true);

                for($i=1; $i<=db_num_rows($res); $i++) {

                        $p = db_fetch_assoc($res);

                        $p['temple_servant'] = ($p['temple_servant'] >= 20 ? $p['temple_servant']*0.05 : $p['temple_servant']);

                        if($session['user']['prefs']['popupbio'] == 1)
                        {
                                $link = "bio_popup.php?char=".rawurlencode($p['login']);
                                $str_biolink = '<a href="'.$link.'" target="_blank" onClick="'.popup_fullsize($link).';return:false;">'.$p['name'].'</a>';
                        }
                        else
                        {
                                $link = "bio.php?char=".rawurlencode($p['login']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                                addnav("",$link);
                                $str_biolink = '<a href="'.$link.'">'.$p['name'].'</a>';
                        }

                        output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td><a href="mail.php?op=write&to='.rawurlencode($p['login']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login']) ).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>'.$str_biolink.'</td>',true);
                        output('<td>'.$p['daysinjail'].'</td><td>'.$p['temple_servant'].'</td>',true);
                        output('<td>'.(($p['loggedin'])?'`@online`&':'`4offline`&').'</td>',true);

                        if($admin_mode) {
                                output('<td><a href="dorfamt.php?op=servant_stop&id='.$p['acctid'].'">Entlassen</a></td>',true);
                                addnav("","dorfamt.php?op=servant_stop&id=".$p['acctid']);
                        }

                        output('</tr>',true);

                }        // END for

                output('</table>',true);

        }        // END Diener vorhanden

}

page_header("Das Stadtamt");

if ($_GET['op']=="")
{
    $show_ooc = true;

    output("`c`b`&Das Stadtamt`0`b`c`n`n`2
            Du trittst in eine große Halle, die an beiden Seiten von weißen Marmorsäulen gesäumt wird. Jede
            Säule ist dabei mit je einem Abbild einer perfekt ausbalancierten Waage versehen, deren in den
            Stein gemeißelte Umrisse mit Gold aufgegossen wurden, sodass sich das Symbol leuchtend von seinem
            grau-weißen, steinernen Hintergrund abhebt.
            Gegenüber des Eingangstores befindet sich ein freundlich aussehender Schreibtisch und dahinter eine
            noch freundlicher aussehende Dame, die sich mit einigen Papieren beschäftigt.
            An der Wand hinter dem Schreibtisch hängt ein Schild mit der Aufschrift:`n
            `n
            `c`p\"In der Amtskasse befinden sich `^" .number_format(getsetting("amtskasse", 0),0,'',' '). " `pGoldstücke!");
    // Welche Boni sind wie lange noch aktiv?
    $int_doppeltrpp_dauer = getsetting("doppeltrpp_dauer",0);
    $int_party_duration = ceil((getsetting("lastparty",0) - time())/86400);
    $arr_boni = array();
    if($int_doppeltrpp_dauer > 0) {
        $arr_boni['rpp'] = "`nDie RPP pro Post sind noch ".$int_doppeltrpp_dauer." ".($int_doppeltrpp_dauer == 1 ? "Tag" : "Tage")." lang verdoppelt.";
    }
    if($int_party_duration > 0) {
        $arr_boni['party'] = "`nDas Stadtfest läuft noch ".$int_party_duration." ".($int_party_duration == 1 ? "Tag" : "Tage").".";
    }
    if(!empty($arr_boni)) {
        output("`n");
        foreach($arr_boni AS $v) {
            output($v);
        }
    }
    // end
    output("\"`c`n
            `2Darunter hängt eine schlichte Wanduhr, deren Zeiger sich aber um ein Vielfaches schneller bewegen, als es bei gewöhnlichen Uhren der Fall ist. Im Moment zeigt sie `p".getgametime()." `2an.`n
            `n
            `2Als Du näher trittst hebt die Empfangsdame den Blick, sieht Dich an und fragt nach Deinem Begehr!`n
            \"`@Willkommen, bitte nicht wundern, die Amtssprache wird Euch seltsam erscheinen. Was kann ich für Euch tun?\"");

    output('`n`n`c`7Die letzten Namensänderungen in '.getsetting('townname','Atrahor').':`&`n');

    board_view('namechange',(su_check(SU_RIGHT_EDITORUSER) > 1 ? 2 : 0),'','In letzter Zeit hat niemand seinen Namen geändert!',false,false,false,true,false,false,false,3);

    output('`c`n`n');

    addnav("Steuern");
    if ($session['user']['level'] >= 5) {
        addnav("z?Steuern zahlen","dorfamt.php?op=steuernzahlen_ok");
    } else {
        addnav("z?Steuern zahlen","dorfamt.php?op=steuernzahlen");
    }

    addnav('Städtische Ämter');
    addnav("w?".$profs[PROF_GUARD_HEAD][3].$profs[PROF_GUARD_HEAD][4]." Stadtwache`0","wache.php?op=hq");
    addnav("M?".$profs[PROF_MAGE_HEAD][3].$profs[PROF_MAGE_HEAD][4]." Zirkel der Magier`0","mage.php?op=hq");
    addnav("G?".$profs[PROF_JUDGE_HEAD][3].$profs[PROF_JUDGE_HEAD][4]." Gerichtshof`0","court.php?op=court");
    addnav("H?".$profs[PROF_MERCH_HEAD][3].$profs[PROF_MERCH_HEAD][4]." Händlergilde`0","merchants.php?op=hq");
    addnav('i?Liste der Amtsträger','dorfamt.php?op=prof_list');
    addnav('A?Zur `iAmtsstube`i','dorfamt.php?op=prof_assembly');

    addnav("Magistrat");
    addnav("V?Vorzimmerdame","dorfamt.php?op=dame1");
    addnav("t?Büro des Stadtrats","dorfamt.php?op=office_entry");
    
    addnav('Strafdienst');
    if($session['user']['profession'] == 0 && $session['user']['daysinjail'] > 0) {
        addnav('Strafdienst antreten`n(Kerkerstrafe abarbeiten)','dorfamt.php?op=servant_apply',false,false,false,false);
    } elseif($session['user']['profession'] == PROF_TEMPLE_SERVANT) {
        addnav('k?Akten sortieren','dorfamt.php?op=serve');
        addnav('o?Botengänge machen','dorfamt.php?op=serve&what=kiss');
    }
    addnav('Liste der Strafdienstleistenden','dorfamt.php?op=servant_list',false,false,false,false);

    addnav("Sonstiges");
    addnav("R?Ruhmeshalle","hof.php");
    addnav("D?Diskussionsraum","diskus.php");

    addnav("Besonderes");
    addnav("l?Ballsaal","ballroom.php");

    addnav("Zur Stadt");
    addnav("S?Zurück zur Stadt","village.php");

    output('`2In der Nähe unterhalten sich einige Bewohner:`n');

    addcommentary();
    viewcommentary("office_entrance","Sagen",30,"sagt");
}
else if ($_GET['op']=="office_entry")
{
    output("`^Die Tür zu den Räumlichkeiten des Stadtrats wird dir von zwei Wächtern geöffnet, die sich zu jeder Zeit im
            Raum befinden und loyal zu ihren Herren stehen werden, sollte es zu Unstimmigkeiten kommen. Du solltest es
            dir also gut überlegen, wie du die Ratsmitglieder ansprechen wirst. Das Erste, was dir auffällt, ist ein
            kreisrunder Tisch aus Ebenholz. Um ihn herum sind im gleichen Abstand zueinander vier Stühle verteilt, die
            alle so aussehen, als wären sie von Hand gearbeitet worden. Auch einen fünften Stuhl gibt es, der wohl für
            dich bestimmt ist - er wurde so aufgestellt, dass jedes Ratsmitglied ihn im Blickfeld hat. Du geht auf diesen
            zu, doch bevor du dich setzt, erlaubst du dir noch einen raschen Blick durch den Raum. Überall stehen Möbel,
            die auf Hochglanz poliert und reichlich mit Edelsteinen und Schmuck ausgeschmückt sind. Doch das edelste
            Stück im ganzen Zimmer ist der riesige Kronleuchter, den die Zwerge in langwähriger Arbeit gänzlich aus
            Kristallen hergestellt haben.`n`n");

    addnav('Informationen');
    addnav('Mitglieder des Stadtrats','dorfamt.php?op=office_mlist');
    addnav('Bisherige Amtshandlungen','dorfamt.php?op=office_history');

    if ($session['user']['profession'] == PROF_PRIEST_HEAD       #Hohepriester
        || $session['user']['profession'] == PROF_GUARD_HEAD     #Hauptmann
        || $session['user']['profession'] == PROF_JUDGE_HEAD     #Oberster Richter
        || $session['user']['profession'] == PROF_MAGE_HEAD      #Hohepriester der Magier
        || $session['user']['profession'] == PROF_DDL_CAPTAIN    #Kapitän der Hafenwacht
        || $session['user']['profession'] == PROF_MERCH_HEAD      #Gildenmeister der Händler
        || su_check(SU_RIGHT_GROTTO))                            # SU
    {
        addnav("Aufgabenbereiche");
        addnav("Steuern","dorfamt.php?op=office_taxes");
        addnav("Strafe für Steuersünder","dorfamt.php?op=office_prison");
        //addnav("Wanderhändler herbefehlen","dorfamt.php?op=office_vendor");
        addnav("Amtskasse","dorfamt.php?op=office_budget");
        addnav('Internes');
        addnav('Hinterzimmer','dorfamt.php?op=office_backroom');
    }
    addnav("Verlassen");
    addnav("Zurück","dorfamt.php");
    addcommentary();
    viewcommentary("office_sovereign","Sagen",30,"sagt");
}
else if ($_GET['op'] == 'office_backroom')
{
    output('`PVom Eingangsbereich führt eine schmale Tür in einen kleinen Nebenraum. Auch in diesem befindet sich ein
            runder Tisch, doch ist er weit kleiner als jener im ersten Raum und über und über mit Dokumenten
            übersät. Vier Stühle stehen um ihn herum. Sie sehen weit eingesessener aus als jene im Raum nebenan und
            weisen auch sonst schlimmere Gebrauchsspuren auf. Kein Zweifel: Hier wird hart gearbeitet.`n`n');
    
    addnav('Zurück');
    addnav('Zurück','dorfamt.php?op=office_entry');
    
    addcommentary();
    viewcommentary('office_backroom','Besprechen',20,'sagt');
}
else if ($_GET['op'] == 'office_mlist')
{
    $sql = 'SELECT accounts.name, profession, sex, login FROM accounts
                   WHERE profession = '.PROF_JUDGE_HEAD.'
                      OR profession = '.PROF_GUARD_HEAD.'
                      OR profession = '.PROF_PRIEST_HEAD.'
                      OR profession = '.PROF_MAGE_HEAD.'
                      OR profession = '.PROF_DDL_CAPTAIN.'
                      OR profession = '.PROF_MERCH_HEAD.'
                   ORDER BY profession DESC';
    $res = db_query($sql);
    $str_txt = '`c`b`FDies sind die ehrenwerten Mitglieder des Stadtrats:`b`n`n
                <table>';
    while ($a = db_fetch_assoc($res))
    {
        if($session['user']['prefs']['popupbio'] == 1)
        {
            $biolink="bio_popup.php?char=".rawurlencode($a['login']);
            $str_biolink = "<a href='".$biolink."' target='_blank' onClick='".popup_fullsize($biolink).";return:false;'>`&".$a['name']."</a>";
        }
        else
        {
            $biolink="bio.php?char=".rawurlencode($a['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
            $str_biolink = "<a href='".$biolink."'>`&".$a['name']."</a>";
            addnav("","bio.php?char=".rawurlencode($a['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
        }
        $str_txt .= '<tr class="trlight"><td><a href="mail.php?op=write&to='.rawurlencode($a['login']).'" target="_blank" onClick="'.popup('mail.php?op=write&to='.rawurlencode($a['login'])).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a> `&'.$str_biolink.'`0 ('.$profs[$a['profession']][3].$profs[$a['profession']][$a['sex']].'`0)`n</td></tr>';
    }
    $str_txt .= '</table>`c';
    output($str_txt,true);
    addnav('Zurück');
    addnav('Zurück','dorfamt.php?op=office_entry');
}
else if ($_GET['op']=="office_taxes")
{
    $taxrate=getsetting("taxrate",750);
    $doubletax=$taxrate*2;
    $taxchange=getsetting("taxchange",1);
    output("`c`&Steuern`c`n`n
                        Der Finanzminister flüstert dir zu:`n\"
                        `2Bedenkt bei Eurer Steuerpolitik stets, dass ein zu hoher Steuersatz unzufriedene Untertanen schafft.`n
                        Ein zu niedriger Steuersatz hingegen zwingt uns zu Einsparungen. Das Stadtfest könnte dann beispielweise
                        nicht mehr so oft stattfinden, was die Untertanen natürlich auch wieder unzufrieden macht.`n`n
                        Bislang gilt:`n
                        Neuankömmlinge (bis Level 4) und Auserwählte zahlen `^keine Steuern`2.`n
                        Bewohner (Level 5 bis Level 10) zahlen den einfachen Steuersatz. Dieser beträgt derzeit `^{$taxrate} Gold`2.`n
                        Alteingesessene (Level 11 bis Level 15) zahlen den doppelten Steuersatz. Dieser beträgt `^{$doubletax} Gold.`&\"`n`n`n");
    if ($taxchange==1)
    {
        output("Diesen Monat kannst du den Steuersatz `@noch einmal`& ändern!`n");
        addnav("Ändern");
        addnav("Steuersatz ändern","dorfamt.php?op=office_change_taxes");
    }
    else
    {
        output("Diesen Monat kannst du den Steuersatz `4nicht mehr`& ändern.`n");
        if ($session['user']['superuser']>0)
        {
            addnav("Mods");
            addnav("Änderung zulassen","dorfamt.php?op=mod_taxes");
        }
    }
    addnav("Zurück");
    addnav("Ins Büro","dorfamt.php?op=office_entry");
}
else if ($_GET['op']=="office_change_taxes")
{
    $taxrate=getsetting("taxrate",750);
    $taxchange=getsetting("taxchange",1);
    $maxtaxes=getsetting("maxtaxes",2000);
    if ($taxchange==1)
    {
        output("<form action='dorfamt.php?op=office_change_taxes2' method='POST'>`&Der Steuersatz liegt bei `^{$taxrate}`& Gold.`n",true);
        output("`&Wie hoch hättest du ihn gern? (Maximal {$maxtaxes} Gold)<input id='input' name='amount' width=4 value='$taxrate'> <input type='submit' class='button' value='festlegen'>`n</form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
        addnav("","dorfamt.php?op=office_change_taxes2");
        addnav("Doch nicht ändern","dorfamt.php?op=office_entry");
    }
    else
    {
        output("Du kannst diesen Monat den Steuersatz nicht mehr verändern.");
        addnav("Ins Büro","dorfamt.php?op=office_entry");
    }
}
else if ($_GET['op']=="mod_taxes")
{
    savesetting("taxchange",1);
    redirect("dorfamt.php?op=office_taxes");
}
else if ($_GET['op']=="mod_prison")
{
    savesetting("prisonchange",1);
    redirect("dorfamt.php?op=office_prison");
}
else if ($_GET['op']=="mod_vendor")
{
    savesetting("callvendor",getsetting("callvendormax",5));
    redirect("dorfamt.php?op=office_vendor");
}
else if ($_GET['op']=="office_change_taxes2")
{
    $taxrate=getsetting("taxrate",750);
    $maxtaxes=getsetting("maxtaxes",2000);
    $mintaxes=getsetting("mintaxes","0");

    // Man kann ja nie wissen...
    if ($mintaxes<0)
    {
        $mintaxes=0;
        savesetting("mintaxes","0");
    }

    if ($maxtaxes<$mintaxes)
    {
        $maxtaxes=$mintaxes;
        savesetting("maxtaxes",$mintaxes);
    }

    $_POST['amount']=floor((int)$_POST['amount']);
    if ($_POST['amount']<$mintaxes)
    {
        output("`&Der Finanzminister schaut dich skeptisch an.`n
                                \"`2Wollt Ihr etwa Gold verschenken?`&\" fragt er ungläubig.");
        addnav("Nochmal","dorfamt.php?op=office_change_taxes");
    }
    else if ($_POST['amount']>$maxtaxes)
    {
        output("`&Der Finanzminister schaut dich skeptisch an.`n
                                \"`2Wollt Ihr eine Revolte provozieren?`&\" fragt er ungläubig.");
        addnav("Nochmal","dorfamt.php?op=office_change_taxes");
    }
    else if ($_POST['amount']==$taxrate)
    {
        output("`&Der Finanzminister nick bestätigend.`n
                                \"`2Damit bliebe also alles beim Alten.`&\" sagt er.");
        addnav("Ins Büro","dorfamt.php?op=office_entry");
    }
    else
    {
        output("`&Der Finanzminister fragt nochmal nach.`n
                                \"`2Seid Ihr Euch sicher, dass Ihr den Steuersatz auf `^{$_POST['amount']}
                                Gold`2 ändern wollt?`&\"");
        addnav("Ja","dorfamt.php?op=office_change_taxes3&amount=$_POST[amount]");
        addnav("Nein","dorfamt.php?op=office_change_taxes");
    }
}
else if ($_GET['op']=="office_change_taxes3")
{
    $taxrate=getsetting("taxrate",750);
    $newtax=$_GET['amount'];
    output("`&Der neue Steuersatz beträgt von nun an `^{$newtax} Gold`&!");
    savesetting("taxrate",$newtax);
    savesetting("taxchange","0");
    if ($newtax>0)
    {
        $str_msg = "`^Ratsmitglied ".$session['user']['name']."
         `^hat heute den Steuersatz auf {$newtax}
        Gold ".($newtax>$taxrate?"erhöht":"gesenkt").".";
    }
    else
    {
        $str_msg = "`^Ratsmitglied ".$session['user']['name']." `^hat heute die Steuern abgeschafft!";
    }

    addnews($str_msg);
        board_add('council_act',31,0,$str_msg);

    addnav("Ins Büro","dorfamt.php?op=office_entry");
}
else if ($_GET['op']=="office_prison")
{
    $taxprison=getsetting("taxprison",1);
    $prisonchange=getsetting("prisonchange",1);
    output("`c`&Steuerhinterziehung`c`n`n
                        Der Finanzminister raunt dir zu:`n\"
                        `2Steuerhinterzieher wandern derzeit ");
    if ($taxprison==0)
    {
        output("nicht ");
    }
    if ($taxprison==1)
    {
        output("für einen Tag ");
    }
    if ($taxprison>1)
    {
        output("für {$taxprison} Tage ");
    }
    output("hinter Gitter.`nViel zu wenig wenn Ihr mich fragt.`&\"`n`n`n");
    if ($prisonchange==1)
    {
        output("Diesen Monat kannst du das Strafmaß für Steuerhinterziehung `@noch einmal`& ändern!`n");
        addnav("Ändern");
        addnav("Strafmaß ändern","dorfamt.php?op=office_change_prison");
    }
    else
    {
        output("Diesen Monat kannst du das Strafmaß für Steuerhinterziehung `4nicht mehr`& ändern.`n");
        if ($session['user']['superuser']>0)
        {
            addnav("Mods");
            addnav("Änderung zulassen","dorfamt.php?op=mod_prison");
        }
    }
    addnav("Zurück");
    addnav("Ins Büro","dorfamt.php?op=office_entry");
}
else if ($_GET['op']=="office_change_prison")
{
    $prisonchange=getsetting("prisonchange",1);
    $maxprison=getsetting("maxprison",2);
    $taxprison=getsetting("taxprison",1);
    if ($prisonchange==1)
    {
        output("<form action='dorfamt.php?op=office_change_prison2' method='POST'>`&Das Strafmaß liegt bei `^{$taxprison}`& Tagen Haft. Darüberhinaus wird das Doppelte der hinterzogenen Steuer gepfändet.`n",true);
        output("`&Wie hoch hättest du das Strafmaß gern? (Maximal {$maxprison} Tage)<input id='input' name='amount' width=4 value='$taxprison'> <input type='submit' class='button' value='festlegen'>`n</form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
        addnav("","dorfamt.php?op=office_change_prison2");
        addnav("Doch nicht ändern","dorfamt.php?op=office_entry");
    }
    else
    {
        output("Du kannst diesen Monat das Strafmaß für Steuerhinterziehung nicht mehr verändern.");
        addnav("Ins Büro","dorfamt.php?op=office_entry");
    }
}
else if ($_GET['op']=="office_change_prison2")
{
    $prisonchange=getsetting("prisonchange",1);
    $maxprison=getsetting("maxprison",3);
    $taxprison=getsetting("taxprison",1);

    $_POST['amount']=floor((int)$_POST['amount']);
    if ($_POST['amount']<0)
    {
        output("`&Der Finanzminister schaut dich skeptisch an.`n
                                \"`2Wollt Ihr die Verbrecher auch noch belohnen?`&\" fragt er ungläubig.");
        addnav("Nochmal","dorfamt.php?op=office_change_prison");
    }
    else if ($_POST['amount']>$maxprison)
    {
        output("`&Der Finanzminister seufzt.`n
                                \"`2Das lässt sich mit der allgemeinen Gesetzgebung nicht vereinbaren.`&\" sagt er missmutig.");
        addnav("Nochmal","dorfamt.php?op=office_change_prison");
    }
    else if ($_POST['amount']==$taxprison)
    {
        output("`&Der Finanzminister nick bestätigend.`n
                                \"`2Damit bliebe also alles beim alten.`&\" sagt er.");
        addnav("Ins Büro","dorfamt.php?op=office_entry");
    }
    else
    {
        output("`&Der Finanzminister fragt nochmal nach.`n
                                \"`2Seid Ihr Euch sicher, dass Ihr das Strafmaß für Steuerhinterziehung auf `^{$_POST['amount']}
                                Tage`2 ändern wollt?`&\"");
        addnav("Ja","dorfamt.php?op=office_change_prison3&amount=$_POST[amount]");
        addnav("Nein","dorfamt.php?op=office_change_prison");
    }
}
else if ($_GET['op']=="office_change_prison3")
{
    $taxprison=getsetting("taxprison",1);
    $newprison=$_GET['amount'];
    output("`&Das neue Stafmaß beträgt von nun an `^{$newprison} Tage Kerker`&!");
    savesetting("taxprison",$newprison);
    savesetting("prisonchange","0");
    if ($newprison == 1)
    {
        $str_msg = "`^Ratsmitglied ".$session['user']['name']." `^hat heute das Strafmaß für Steuerhinterziehung auf 1 Tag Kerker ".($newprison>$taxprison?"erhöht":"gesenkt").".";
    }
    else if ($newprison > 1)
    {
        $str_msg = "`^Ratsmitglied ".$session['user']['name']." `^hat heute das Strafmaß für Steuerhinterziehung auf {$newprison} Tage Kerker ".($newprison>$taxprison?"erhöht":"gesenkt").".";
    }
    else
    {
        $str_msg = "`^Ratsmitglied ".$session['user']['name']." `^hat heute die Kerkerhaft für Steuerhinterziehung abgeschafft!";
    }

    addnews($str_msg);
    board_add('council_act',31,0,$str_msg);

    addnav("Ins Büro","dorfamt.php?op=office_entry");
}
else if ($_GET['op']=="office_vendor")
{
    output("`c`&Wanderhändler`c`n
                        Hier kannst du einen Eilboten in die umliegenden Städte schicken und Riyad damit drohen, ihm die Lizens zu entziehen, wenn er sich nicht augenblicklich in der Stadt sehen lässt.`n`n");
    if (getsetting("vendor",0)==1)
    {
        output("Aber da der Wanderhändler derzeit auf dem Markplatz seine Zelte aufgeschlagen hat, würde eine solche Drohung nichts nützen.`n`n");
        addnav("Ins Büro","dorfamt.php?op=office_entry");
    }
    else
    {
        $callvendor=getsetting("callvendor",5);
        if ($callvendor>0)
        {
            output("`&Du kannst dies in diesem Monat noch `^{$callvendor}`&mal tun.");
            addnav("Herbeordern");
            addnav("Wanderhändler rufen","dorfamt.php?op=office_call_vendor");
            addnav("Zurück");
            addnav("Ins Büro","dorfamt.php?op=office_entry");
        }
        else
        {
            output("`&Leider hast du dies schon so oft gemacht, dass er es gar nicht mehr einsieht, auf deine Drohungen einzugegen. In der Nachbarstadt verdient er sowieso mehr!");
            addnav("Ins Büro","dorfamt.php?op=office_entry");
            if ($session['user']['superuser']>0)
            {
                addnav("Mods");
                addnav("Rufen zulassen","dorfamt.php?op=mod_vendor");
            }
        }
    }
}
else if ($_GET['op']=="office_call_vendor")
{
    $callvendor=getsetting("callvendor",5);
    output("`&Dein schnellster Bote macht sich auf den Weg und schleift den Wanderhändler mitsamt seinem Gerümpel auf den Marktplatz.`n`n");
    $callvendor--;
    savesetting("callvendor","$callvendor");
    savesetting("vendor",1);

    board_add('council_act',31,0,'`2Ratsmitglied '.$session['user']['name'].' `2ruft den Wanderhändler herbei!');

    addnav("Ins Büro","dorfamt.php?op=office_entry");
}
else if ($_GET['op']=="office_budget")
{
    $party=getsetting("min_party_level", 500000);
    $stone=getsetting("paidgold","0");
    $stonemax=getsetting("beggarmax","25000");
    $budget=getsetting("amtskasse","0");
    $amtsgems=getsetting("amtsgems","0");
    $lurevendor=getsetting("lurevendor","40000");
    $freeorkburg=getsetting("freeorkburg","30000");
    $doppeltrpp = getsetting("doppeltrpp","100000");
    output("`n`2Die Amtskasse ist mit `^".$budget. " `2Goldstücken gefüllt.`n
                        Die Truhen fassen maximal `^".getsetting("maxbudget","2000000")." `2Gold.`n`n
                        In den Tresoren lagern `^".$amtsgems." `2Edelsteine.`n
                        Maximal fassen die Tresore `^".getsetting("maxamtsgems","100")." `2Edelsteine.`n`n`n`n
                        Auf dem Bettelstein sind derzeit `^".$stone." `2Gold hinterlegt.`n
                        Sein Fassungsvermögen beträgt `^".$stonemax." `2Gold.`n`n
                        Den Weg zur Orkburg freizuräumen kostet `^".$freeorkburg." `2Gold.`n
                        Ein Stadtfest kostet `^".$party." `2Gold.`n
                        Die Anzahl an RPP für 7 Tage zu verdoppeln, kostet `^".$doppeltrpp." `2Gold.`n`n");
    if ($budget>=$party)
    {
        addnav("Stadtfest");
        addnav("Stadtfest ausrichten","dorfamt.php?op=office_budget_party");
    }
    /* Aussortiert: output("Du kannst den Wanderhändler für `^".$lurevendor." `2Gold herlocken.`n");
    if ($budget>=$lurevendor)
    {
        addnav("Wanderhändler");
        addnav("Herlocken","dorfamt.php?op=office_budget_lurevendor");
    } */
    if ($budget>=$freeorkburg)
    {
        addnav("Weg zur Orkburg");
        addnav("Freilegen lassen","dorfamt.php?op=office_budget_orkburg");
    }
    if($budget >= $doppeltrpp) {
        addnav('Doppelte RPP');
        addnav('RPP pro Post verdoppeln',"dorfamt.php?op=office_budget_doppeltrpp");
    }
    if ($budget>=5000)
    {
        addnav("Auf den Bettelstein");
        addnav("5000 Gold","dorfamt.php?op=office_budget2&amount=5000");
        if ($budget>=10000)
        {
            addnav("10000 Gold","dorfamt.php?op=office_budget2&amount=10000");
        }
    }
    if($budget > 0 || $amtsgems > 0) {
            addnav('Belohnung');
            if($budget > 0) {
                addnav('Gold','dorfamt.php?op=office_donate&what=gold');
            }
            if($amtsgems > 0) {
                addnav('Edelsteine','dorfamt.php?op=office_donate&what=gems');
            }
    }
    else
    {
        addnav("Wir sind pleite!");
    }

    $selledgems=getsetting("selledgems",0);
    $costs=(4000-3*$selledgems);
    if (($budget>=$costs && $selledgems>0) || ($amtsgems>0 && $selledgems<100))
    {
        addnav("Edelsteine");
        if ($budget>=$costs && $selledgems>0)
        {
            addnav("Kaufen","dorfamt.php?op=office_budget_buygems");
        }
        if ($amtsgems>0 && $selledgems<100)
        {
            addnav("Verkaufen","dorfamt.php?op=office_budget_sellgems");
        }
    }

    addnav("Zurück");
    addnav("Ins Büro","dorfamt.php?op=office_entry");
}
elseif ($_GET['op'] == 'office_history') {

        output('`^An einer eigens dafür aufgestellten Wand verkündet das Büro des Stadtrats die bisherigen Amtshandlungen in diesem Monat:`n`n');

        board_view('council_act',0,'','Bisher wurden in dieser Amtsperiode keine Anordnungen vernommen.',false,true,false,false,false);

        addnav("Zurück");
    addnav("Ins Büro","dorfamt.php?op=office_entry");

}
elseif ($_GET['op'] == 'office_donate') {

        output('`c`b`&Belohnung aus der Amtskasse`b`c`2`n`n');

        $int_donations = getsetting('council_donations','0');
        $int_max_donations = 100000;
        $int_gem_factor = 2500;

        if($int_donations >= $int_max_donations) {
                output('`$Das Volk murrt:`n
                                `3Hoher Rat! Ihr wart schon großzügig genug.`n
                                Wir wollen ja gar nicht so sehr verwöhnt werden und, wisst Ihr, Zeiten ohne Geschenke sind auch mal was Schönes.`n
                                Dafür immer an die arme, hart arbeitende Arbeiterklasse denken und lieber mit den Steuern runter! Jawoll!');
                addnav("Zurück");
            addnav("Ins Büro","dorfamt.php?op=office_entry");
            page_footer();
            exit();
        }

        if($_GET['act'] == 'finished') {
                output($session['office_donate_msg']);
                unset($session['office_donate_msg']);
                addnav("Zurück");
            addnav("Ins Büro","dorfamt.php?op=office_entry");
            page_footer();
            exit();
        }

        $int_acctid = (int)$_REQUEST['acctid'];
        $str_what = ($_GET['what'] == 'gems' ? 'gems' : 'gold');
        $str_whatname = ($str_what == 'gems' ? 'Edelsteine' : 'Gold');

        $str_lnk = 'dorfamt.php?op=office_donate&what='.$str_what;
        addnav('',$str_lnk);
        output('<form method="POST" action="'.$str_lnk.'">');

        // AcctID ist gegeben, Menge eingeben
        if(!empty($int_acctid)) {

                // Account abrufen
                $sql = 'SELECT gold,gems,level,dragonkills,login,name,acctid FROM accounts WHERE acctid='.$int_acctid;
                $res = db_query($sql);
                if(!db_num_rows($res)) {

                }
                $arr_target = db_fetch_assoc($res);

                // Vorräte abrufen
                if($str_what == 'gems') {
                        $int_available = (int)getsetting('amtsgems',0);
                        $int_max_amount = 3;
                }
                else {
                        $int_available = (int)getsetting('amtskasse',0);
                        $int_max_amount = 2000;
                }

                $int_amount = (int)$_POST['amount'];

                // Menge gegeben
                if(!empty($int_amount)) {

                        // Validieren
                        if($int_amount > $int_max_amount) {
                                output('`$Nana, solche Mengen wären reichlich ungerecht gegenüber der hart arbeitenden Arbeiterklasse!`n`n');
                        }
                        elseif($int_amount > $int_available) {
                                output('`$Wo soll das denn bitte herkommen? Aus deiner privaten Tasche?`n`n');
                        }
                        elseif($int_amount == 0) {
                                output('`$Was du auch versuchst, irgendwie macht Null '.$str_whatname.' nicht viel Sinn..`n`n');
                        }
                        elseif($int_acctid == $session['user']['acctid']) {
                                output('`$Schummler! Elender! Sei froh, dass die Götter heute gute Laune haben.. Dich selbst beschenken zu wollen.. also wirklich.`n`n');
                        }
                        else {

                                if($int_amount == 1 && $str_what == 'gems') {
                                        $str_whatname = 'Edelstein';
                                }

                                debuglog(' vergab durch Ratsamt '.$int_amount.' '.$str_whatname.' an ',$int_acctid);
                                systemmail($int_acctid,'`2Belohnung vom `^Ratsamt`2!',
                                                        '`2Ratsmitglied '.$session['user']['name'].'`2 hat dir soeben eine Belohnung in Höhe von `^'.$int_amount.'
                                                        '.$str_whatname.'`2 aus der Amtskasse zukommen lassen!');

                                $sql = 'UPDATE accounts SET '.$str_what.' = '.$str_what.' + '.$int_amount.' WHERE acctid = '.$int_acctid;
                                db_query($sql);

                                board_add('council_act',31,0,$session['user']['name'].'`^ hat soeben '.$arr_target['name'].'`^ '.$int_amount.' '.$str_whatname.' aus der Amtskasse zukommen lassen!');

                                if($str_what == 'gems') {
                                        savesetting('amtsgems',$int_available-$int_amount);
                                }
                                else {
                                        savesetting('amtskasse',$int_available-$int_amount);
                                }

                                $int_donations += $int_amount * ($str_type == 'gems' ? $int_gem_factor : 1);

                                savesetting('council_donations',$int_donations);

                                // Fertig: redirect auf Meldung
                                $session['office_donate_msg'] = '`2Du übergibst einem Boten den Auftrag, '.$int_amount.' '.$str_whatname.' '.$arr_target['name'].'`2 zu überreichen. Sofort eilt dieser los, um deinen Auftrag auszuführen!';
                                redirect('dorfamt.php?op=office_donate&act=finished');
                        }
                }

                addnav('Jemand anderes verdient es eher..','dorfamt.php?op=office_donate');

                // Sonst: Eingabefeld
                output('`2Nun, du willst also ein Geschenk an '.$arr_target['name'].'`2 vergeben. Wieviel darf\'s denn sein?
                                `n`nAnzahl an '.$str_whatname.' (maximal '.$int_max_amount.', in der Amtskasse liegen zur Zeit '.$int_available.' '.$str_whatname.'):
                                <input type="text" maxlength="4" size="4" name="amount" id="amount" value="'.$int_amount.'">
                                <input type="hidden" name="acctid" value="'.$int_acctid.'">`n`n
                                <input type="submit" value="Vergeben!">',true);

        }
        // Sonst: Suchformular
        else {

                $str_search_in = stripslashes($_POST['search']);

                // Suchwort gegeben
                if(strlen($str_search_in) > 2) {

                        $str_search = str_create_search_string($str_search_in);

                        $sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$str_search.'" ORDER BY IF(login="'.addslashes($str_search_in).'",1,0) DESC, name ASC LIMIT 100';
                        $res = db_query($sql);

                        if(!db_num_rows($res)) {
                                output('`nEs wurden keine Bürger gefunden, die auf deine Eingabe passen!');
                                addnav('Lass mich nochmal suchen!','dorfamt.php?op=office_donate');
                        }
                        else {
                                $str_out = 'Folgende Bürger passen auf deine Eingabe:
                                                        <select name="acctid">';
                                while($a = db_fetch_assoc($res)) {
                                        $str_out .= '<option value="'.$a['acctid'].'">'.$a['name'].'</option>';
                                }
                                $str_out .= '</select>
                                                        <input type="submit" value="Genau den mein ich!">';
                                output($str_out,true);
                        }

                }
                // Sonst: Suchfeld
                else {

                        output('Welchen Bürger '.getsetting('townname','Atrahor').'s willst du mit '.$str_whatname.' aus der Amtskasse bedenken?`n`n <input type="text" maxlength="40" size="20" name="search" id="search">`n`n
                                <input type="submit" value="Suchen!">',true);

                }

        }

        output('</form>',true);

        addnav("Zurück");
    addnav("Ins Büro","dorfamt.php?op=office_entry");

}
else if ($_GET['op']=="office_budget2")
{
    $amount=$_GET['amount'];
    $budget=getsetting("amtskasse" ,0);
    $stone=getsetting("paidgold","0");
    $max=getsetting("beggarmax","25000");
    if ($budget>=$amount)
    {
        if ($stone+$amount>$max)
        {
            $amount=$max-$stone;
            output("`2Der Bettelstein kann leider nur `^{$max}`2 Gold fassen.`n");
            if ($amount>0)
            {
                output("`2Also transferierst du lediglich `^{$amount}`2 Gold!");
            }
            else
            {
                output("`2Demnach kannst du auch nichts mehr auf ihn transferieren.");
            }
        }
        else
        {
            output("`2Du transferierst `2{$amount}`^ Gold auf den Bettelstein.");
        }

        if ($amount>0)
        {
                $str_msg = "`@Armenspeisung!`2 Ratsmitglied {$session['user']['name']}`2 hat soeben `^{$amount}`2 Gold auf den Bettelstein transferiert.";
            addnews($str_msg);
            board_add('council_act',31,0,$str_msg);
            savesetting("amtskasse",$budget-$amount);
            savesetting("paidgold",$stone+$amount);

        }
    }
    else
    {
        output("Hoppla, das können wir uns aber gerade überhaupt nicht leisten.");
    }
    addnav("Zurück");
    addnav("Zur Kasse","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_party")
{
    $amtskasse = getsetting("amtskasse", 0);
    $min_party_level = getsetting("min_party_level", 500000);
    $lastparty = getsetting("lastparty", 0);
    $party_duration= getsetting("party_duration", 1);
    if ($amtskasse>=$min_party_level)
    {
        savesetting("amtskasse",$amtskasse- $min_party_level);
        savesetting("lastparty",time()+86400*$party_duration);
        output("`2So sei es! Möge das Stadtfest beginnen!");
        $str_msg = "`^Ratsmitglied {$session['user']['name']}
        `^ hat heute ein Stadtfest veranstaltet!";
        addnews($str_msg);
        board_add('council_act',31,0,$str_msg);
    }
    else
    {
        output("Hoppla, das können wir uns aber gerade gar nicht leisten.");
    }
    addnav("Zurück");
    addnav("Zur Kasse","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_lurevendor")
{
    $budget=getsetting("amtskasse" ,0);
    $lurevendor=getsetting("lurevendor","40000");
    $vendor=getsetting("vendor","0");
    if ($budget>=$lurevendor)
    {
        if ($vendor==1)
        {
            output("`2Nicht nötig, er ist doch schon da.`n
                                        Oder willst du ihm etwa die hart verdienten Steuergelder auch noch in den Rachen werfen?`n`n");
        }
        else
        {
            output("`2Du schickst deinen schnellsten Boten in die Nachbardörfer und bietest dem Wanderhändler `^{$lurevendor}`2 Gold an, wenn er sich sofort auf deinem Marktplatz blicken lässt.`n
                                        Das Angebot lässt er sich natürlich nicht zweimal machen.");
            savesetting("amtskasse",$budget-$lurevendor);
            savesetting("vendor","1");

            $str_msg = "`^Ratsmitglied {$session['user']['name']}
            `^ hat den Wanderhändler in die Stadt gelockt!";

            addnews($str_msg);
            board_add('council_act',31,0,$str_msg);
        }
    }
    else
    {
        output("Hoppla, das können wir uns jetzt aber gar nicht leisten...");
    }
    addnav("Zurück");
    addnav("Zur Kasse","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_orkburg")
{
    $budget=getsetting("amtskasse" ,0);
    $freeorkburg=getsetting("freeorkburg","30000");
    $orkburg=getsetting("dailyspecial","Keines");
    if ($budget>=$lurevendor)
    {
        if ($orkburg=="Orkburg")
        {
            output("`2Nicht nötig, der Weg ist gut freigetreten.`n
                                        Oder willst du die hart verdienten Steuergelder unnötig an Waldarbeiter verfeuern?`n`n");
        }
        else
        {
            output("`2Du schickst eine Horde Waldarbeiter mit den `^{$freeorkburg}`2 Gold zum Toilettenhäuschen, die sich in Windeseile durch das Unterholz hacken und einen schönen, breiten Weg zur Orkburg freilegen.`n
                                        Leider wird dieser schon morgen wieder total zugewuchert sein.");
            savesetting("amtskasse",$budget-$freeorkburg);
            savesetting("dailyspecial","Orkburg");

            $str_msg = "`^Ratsmitglied {$session['user']['name']}
            `^ hat den Weg zur Orkburg freilegen lassen!";

            addnews($str_msg);
            board_add('council_act',31,0,$str_msg);
        }
    }
    else
    {
        output("Hoppla, das können wir uns jetzt aber gar nicht leisten...");
    }
    addnav("Zurück");
    addnav("Zur Kasse","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_doppeltrpp")
{
    $budget = getsetting("amtskasse" ,0);
    $doppeltrpp = getsetting("doppeltrpp","100000");
    $doppeltrpp_dauer = (int)getsetting("doppeltrpp_dauer",0);
    if ($budget >= $doppeltrpp)
    {
        if ($doppeltrpp_dauer > 0)
        {
            output("`2Die Anzahl an RP-Punkten pro Post wurde bereits verdoppelt, der Bonus läuft noch ".$doppeltrpp_dauer." Tage.`n`n");
        }
        else
        {
            output("`2Du drückst dem nächstbesten Sklav-, äh, Angestellten das unterschriebene Formular in die Hand und trägst ihm auf, sich um die Umsetzung zu
                    kümmern. Der Spaß kostet die Stadt ".$doppeltrpp." Gold.`n`n");
            savesetting("amtskasse",$budget-$doppeltrpp);
            savesetting("doppeltrpp_dauer",7);

            $str_msg = "`^Ratsmitglied {$session['user']['name']}`^ hat die Anzahl an RPP pro Post für die nächsten 7 Tage verdoppeln lassen!";

            addnews($str_msg);
            board_add('council_act',31,0,$str_msg);
        }
    }
    else
    {
        output("Hoppla, das können wir uns jetzt aber gar nicht leisten...");
    }
    addnav("Zurück");
    addnav("Zur Kasse","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_buygems")
{
    $budget=getsetting("amtskasse" ,0);
    $amtsgems=getsetting("amtsgems","0");
    $selledgems=getsetting("selledgems",0);
    $costs=(4000-3*$selledgems);
    $maxgems=getsetting("maxamtsgems","100");
    $spaceleft=$maxgems-$amtsgems;
    output("<form action='dorfamt.php?op=office_budget_buygems2' method='POST'>`2Die Zigeunerin hat derzeit `^{$selledgems}`2 Edelsteine auf Lager, zu einem Preis von jeweils `^{$costs} `2Gold.`n",true);
    output("`2Wieviele Edelsteine hättest du gern? (Die Tresore fassen noch {$spaceleft}
    Edelsteine)<input id='input' name='amount' width=4> <input type='submit' class='button' value='kaufen'>`n</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
    addnav("","dorfamt.php?op=office_budget_buygems2");
    addnav("Doch nichts kaufen","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_buygems2")
{
    $budget=getsetting("amtskasse" ,0);
    $amtsgems=getsetting("amtsgems","0");
    $selledgems=getsetting("selledgems",0);
    $costs=(4000-3*$selledgems);
    $maxgems=getsetting("maxamtsgems","100");
    $spaceleft=$maxgems-$amtsgems;
    $_POST['amount']=floor((int)$_POST['amount']);

    if ($_POST['amount']<0)
    {
        output("`2Du kannst auf diese Art keine Edelsteine verkaufen!");
    }
    else if ($_POST['amount']==0)
    {
        output("`2Du entscheidest dich doch nichts zu kaufen.");
    }
    else if ($_POST['amount']>$selledgems)
    {
        output("`2So viele Edelsteine hat die Zigeunerin im Moment nicht.");
    }
    else if (($_POST['amount']*$costs)>$budget)
    {
        output("`2Das übersteigt deine finanziellen Fähigkeiten!");
    }
    else if ($_POST['amount']>$spaceleft)
    {
        output("`2So viele Edelsteine können die Tresore leider nicht mehr fassen!");
    }
    else
    {
        $amount=$_POST['amount'];

        board_add('council_act',31,0,'`^Ratsmitglied '.$session['user']['name'].'`^ kauft '.$amount.' Edelsteine!');

        output("`2Du kaufst `^{$amount} `2Edelsteine von der Zigeunerin und deponierst sie in den Tresoren.");
        $selledgems-=$amount;
        if ($selledgems>0)
        {
            savesetting("selledgems",$selledgems);
        }
        else
        {
            savesetting("selledgems","0");
        }
        $amtsgems+=$amount;
        savesetting("amtsgems",$amtsgems);
        $budget-=$amount*$costs;
        savesetting("amtskasse",$budget);
    }
    addnav("Zurück","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_sellgems")
{
    $budget=getsetting("amtskasse","0");
    $amtsgems=getsetting("amtsgems","0");
    $selledgems=getsetting("selledgems","0");
    $spaceleft=100-$selledgems;
    $scost=(3000-$selledgems);
    output("<form action='dorfamt.php?op=office_budget_sellgems2' method='POST'>`2Die Zigeunerin hat derzeit `^{$selledgems}`2 Edelsteine auf Lager und kauft bis zu `^{$spaceleft}`2 weitere Steine zu einem Preis von jeweils `^{$scost} `2Gold an.`n",true);
    output("`2Wieviele Edelsteine willst du verkaufen? (Du hast noch {$amtsgems}
    Edelsteine)<input id='input' name='amount' width=4> <input type='submit' class='button' value='verkaufen'>`n</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
    addnav("","dorfamt.php?op=office_budget_sellgems2");
    addnav("Doch nichts kaufen","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="office_budget_sellgems2")
{
    $budget=getsetting("amtskasse","0");
    $amtsgems=getsetting("amtsgems","0");
    $selledgems=getsetting("selledgems","0");
    $scost=(3000-$selledgems);
    $spaceleft=100-$selledgems;
    $_POST['amount']=floor((int)$_POST['amount']);

    if ($_POST['amount']<0)
    {
        output("`2Du kannst auf diese Art keine Edelsteine kaufen!");
    }
    else if ($_POST['amount']==0)
    {
        output("`2Du entscheidest dich doch nichts zu verkaufen.");
    }
    else if ($_POST['amount']>$spaceleft)
    {
        output("`2So viele Edelsteine will die Zigeunerin im Moment nicht.");
    }
    else if ($_POST['amount']>$amtsgems)
    {
        output("`2So viele Edelsteine hast du gar nicht!");
    }
    else
    {
        $amount=$_POST['amount'];

        board_add('council_act',31,0,'`^Ratsmitglied '.$session['user']['name'].'`^ verkauft '.$amount.' Edelsteine!');

        output("`2Du verkaufst der Zigeunerin `^{$amount} `2Edelsteine.");
        $selledgems+=$amount;
        savesetting("selledgems",$selledgems);
        $amtsgems-=$amount;
        if ($amtsgems>0)
        {
            savesetting("amtsgems",$amtsgems);
        }
        else
        {
            savesetting("amtsgems","0");
        }
        $budget+=$amount*$scost;
        savesetting("amtskasse",$budget);
    }
    addnav("Zurück","dorfamt.php?op=office_budget");
}
else if ($_GET['op']=="dame1")
{
    output("`&Du schaust dich ein wenig in den Vorzimmern der hohen Herren um und entdeckst, hübsch geschminkt und über und über mit Ringen, Ketten und Broschen behangen, das furchteinflößendsde und gefährlichste Wesen, dass dir je begegnet ist: `^die Vorzimmerdame`&!`n");
    output("`&Sie ist es, die in vornehmen Kreisen die neuesten Gerüchte an den Mann bringt und dabei auch gut und gern ihr schlechtes Gedächtnis mit ihrer Phantasie unterstützt.`nDir bleibt fast das Herz stehen, als sie dich ansieht und erwartungsvoll mit den Wimpern klimpert.");
    addnav("Was nun ?");
    addnav("Ansehen steigern","dorfamt.php?op=dame2");
    addnav("Gerüchte streuen","dorfamt.php?op=dame3");
    addnav("Laufen!","dorfamt.php");
}
else if ($_GET['op']=="dame2")
{
    output("Nachdem du der Vorzimmerdame mitgeteilt hast, dass du gern ein wenig beliebter wärst und dass dich keiner so richtig leiden kann, wischt sie sich demonstrativ ein Tränchen von der Wange und schaut dich an. \"`#Na das dürfte nicht allzu schwer sein. Ich kann den Leuten ja mal erzählen was für ein tolle".($session['user']['sex']?"s Mädel ":"r Bursche ")."Du bist.`nSo etwas aber seinen Preis... Einen Edelstein für zwei nette Heldengeschichten !\"`n`n");
    output("`&Wieviele Edelsteine willst du ihr geben?");
    output("<form action='dorfamt.php?op=dame21' method='POST'><input name='buy' id='buy'><input type='submit' class='button' value='Geben'></form>",true);
    output("<script language='JavaScript'>document.getElementById('buy').focus();</script>",true);
    addnav("","dorfamt.php?op=dame21");
    addnav("Lieber doch nicht","dorfamt.php?op=dame1");
}
else if ($_GET['op']=="dame21")
{
    $buy = $_POST['buy'];
    if (($buy>$session['user']['gems']) || ($buy<1))
    {
        output("`&Na das ging nach hinten los... Du bietest ihr Edelsteine an, die du nicht hast. In der Hoffnung, dass sie nun keine Gerüchte über deine Armut streut, eilst du davon.");
        addnav("Weg hier!","village.php");
    }
    else
    {
        $eff=$buy*2;
        output("`&Die Dame lässt deine $buy Edelsteine in ihrem Handtäschchen verschwinden und lächelt dich an. Dein Ansehen steigt um $eff.`n");
        $session['user']['gems']-=$buy;
        if ($buy>4)
        {
            debuglog("Gibt $amt Edelsteine im Stadtamt für Ansehen.");
        }
        $session['user']['reputation']+=$eff;
        if ($session['user']['reputation']>50)
        {
            $session['user']['reputation']=50;
        }
        addnav("Zurück","dorfamt.php?op=dame1");
    }
}
else if ($_GET['op']=="dame3")
{
    output("`&Die Frau schaut dich an. \"`#Sooo... und um wen geht es?`&\" fragt sie.`n`n");

    if ($_GET['who']=="")
    {
        addnav("Äh.. um niemanden!","dorfamt.php");
        if ($_GET['subop']!="search")
        {
            output("<form action='dorfamt.php?op=dame3&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
            addnav("","dorfamt.php?op=dame3&subop=search");
        }
        else
        {
            addnav("Neue Suche","dorfamt.php?op=dame3");
            $search = str_create_search_string($_POST['name']);
            $sql = "SELECT name,alive,location,sex,level,reputation,laston,loggedin,login FROM accounts WHERE (locked=0 AND name LIKE '".addslashes($search)."') ORDER BY level DESC";
            $result = db_query($sql) or die(db_error(LINK));
            $max = db_num_rows($result);
            if ($max > 50)
            {
                output("`n`n\"`#Na... damit könnte ja jeder gemeint sein..`&`n");
                $max = 50;
            }
            output("<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>",true);
            for ($i=0; $i<$max; $i++)
            {
                $row = db_fetch_assoc($result);
                output("<tr><td><a href='dorfamt.php?op=dame3&who=".rawurlencode($row['login'])."'>$row[name]</a></td><td>$row[level]</td></tr>",true);
                addnav("","dorfamt.php?op=dame3&who=".rawurlencode($row['login']));
            }
            output("</table>",true);
        }
    }
    else
    {

        $sql = "SELECT acctid,login,name,reputation FROM accounts WHERE login='".$_GET['who']."'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0)
        {
            $row = db_fetch_assoc($result);

            output("`&Die Vorzimmerdame lächelt. \"`#Aber natürlich! ".($row['name'])." `#! Der Name ist mir ein Begriff... Ich denke dass ich sicherlich ein paar alte Geschichten bekannt werden lassen kann.`nDie Leute sollen ruhig wissen mit wem sie es da zu tun haben! Aber... die Sache wird nicht ganz billig werden, denn ich muss sehr viel in den Akten suchen... und...so.`nZwei kleine Gerüchte würde einen Edelsteine kosten..\"`&`n`n");
            output("`n`&Wieviele Edelsteine willst du ihr geben?");
            output("<form action='dorfamt.php?op=dame31&who=".rawurlencode($row['login'])."' method='POST'><input name='buy' id='buy'><input type='submit' class='button' value='Geben'></form>",true);
            output("<script language='JavaScript'>document.getElementById('buy').focus();</script>",true);
            addnav("","dorfamt.php?op=dame31&who=".rawurlencode($row['login'])."");
            addnav("Lieber doch nicht","dorfamt.php?op=dame1");
        }
        else
        {
            output("\"`#Ich kenne niemanden mit diesem Namen.`&\"");
        }
    }
}
else if ($_GET['op']=="dame31")
{
    $buy = $_POST['buy'];
    $sql = "SELECT acctid,name,reputation,login,sex FROM accounts WHERE login='".$_GET['who']."'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0)
    {
        $row = db_fetch_assoc($result);

        if (($buy>$session['user']['gems']) || ($buy<1))
        {
            output("`&Na das ging nach hinten los... Du bietest ihr Edelsteine an, die du nicht hast. In der Hoffnung, dass sie nun keine Gerüchte über DICH verstreut, eilst du davon.");
            addnav("Weg hier!","village.php");
        }
        else
        {
            $eff=$buy*2;
            output("`&Die Dame lässt deine $buy Edelsteine in ihrem Handtäschchen verschwinden und lächelt dich an. Das Ansehen von ".($row['name'])."`& sinkt um $eff.`n");
            $session['user']['gems']-=$buy;
            debuglog("Gibt $amt Edelsteine im Stadtamt für Gerüchte.");
            $rep=$row['reputation']-$eff;
            if ($rep<-50)
            {
                $rep=-50;
            }

            $sql = "UPDATE accounts SET reputation=$rep WHERE acctid = ".$row['acctid']."";
            db_query($sql) or die(sql_error($sql));

            $chance=e_rand(1,3);
            if ($chance==1)
            {
                systemmail($row['acctid'],"`\$Gerüchte!`0","`@{$session['user']['name']}`& hat die Vorzimmerdame im Stadtamt bestochen, damit diese üble Gerüchte über dich verbreitet! Dein Ansehen ist um $eff Punkte gesunken! Willst du dir sowas gefallen lassen ?");
            }
            else
            {
                systemmail($row['acctid'],"`\$Gerüchte!`0","`&Jemand hat die Vorzimmerdame im Stadtamt bestochen, damit diese üble Gerüchte über dich verbreitet! Dein Ansehen ist um $eff Punkte gesunken! Willst du dir sowas gefallen lassen ?");
            }
            if ($buy >= 5)
            {
                $news="`@Gerüchte besagen, dass `^".$row['name']."";
                switch (e_rand(1,15))
                {
                case 1 :
                    $news=$news." `@heimlich in der Nase bohrt!";
                    break;
                case 2 :
                    $news=$news." `@nicht ohne ".($row['sex']?"ihr":"sein")." Kuscheltier einschlafen kann!";
                    break;
                case 3 :
                    $news=$news." `@etwas mit ".($row['sex']?"Ophelía":"Silas")." am Laufen haben soll!";
                    break;
                case 4 :
                    $news=$news." `@ganz übel aus dem Mund riechen soll.";
                    break;
                case 5 :
                    $news=$news." `@mehr Haare ".($row['sex']?"an den Beinen ":"auf dem Rücken ")."haben soll als ein Bär!";
                    break;
                case 6 :
                    $news=$news." `@sich regelmäßig am Bettelstein bedienen soll!";
                    break;
                case 7 :
                    $news=$news." `@sich bei Angst die Hosen vollmachen soll!";
                    break;
                case 8 :
                    $news=$news." `@im Bett eine Niete sein soll!";
                    break;
                case 9 :
                    $news=$news." `@für Geld die Hüllen fallen lassen soll!";
                    break;
                case 10 :
                    $news=$news." `@ein Alkoholproblem haben soll!";
                    break;
                case 11 :
                    $news=$news." `@Angst im Dunkeln haben soll!";
                    break;
                case 12 :
                    $news=$news." `@einen Hintern wie ein Ackergaul haben soll!";
                    break;
                case 13 :
                    $news=$news." `@sehr oft bitterlich weinen soll!";
                    break;
                case 14 :
                    $news=$news." `@eine feuchte Aussprache haben soll!";
                    break;
                case 15 :
                    $news=$news." `@eine Perücke tragen soll!";
                    break;
                }

                // In die News und in die Bio des Opfers
                $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".addslashes($news)."',NOW(),".$row['acctid'].")";
                db_query($sql) or die(sql_error($sql));
            }
            addnav("Zurück","dorfamt.php?op=dame1");
        }

    }
}
else if ($_GET['op']=='passier1')
{
    output('Möchtest du den Schein wirklich beantragen?');
    addnav('Ja, klar!','dorfamt.php?op=passier2');
    addnav('Nein!','dorfamt.php');
}
else if ($_GET['op']=='passier2')
{
    include_once(LIB_PATH.'communityinterface.lib.php');
    $aUser = array();
    $aUser[ 0 ] = array('id'        => $session['user']['acctid'],
                                                'name'        => $session['user']['login'],
                                                'pass'        => $session['user']['password'],
                                                'mail'        => $session['user']['emailaddress']
                                                );

    //die( 'Alucard testet: '.ci_importusers($aUser));
    if (ci_importusers($aUser) )
    {
        $count = db_fetch_assoc(db_query("SELECT COUNT(incommunity) AS cinc FROM account_extra_info WHERE incommunity<>0"));
        $count = $count['cinc'];

        $out  = '`2Die Dame Stempelt die Nummer '.$count.' auf ein grünes Blatt Papier und drückt es Dir in die Hand. "`@Bitte sehr.`2"';
        $out .= '`n`nDu ließt dir die Angaben durch:`n';
        $out .= '`n<big>`7Passierschein A38</big>';
        $out .= '`n`&Nummer: #'.$count;
        $out .= '`nAntragssteller: '.$session['user']['name'];
        $out .= '`n`&Zugangsname: '.$session['user']['login'];
        $out .= '`nZugangspasswort: ';
        if (!getsetting('ci_std_pw_active',0) )
        {
            $out .= '`n`2Du schaust das Feld erschrocken an und wendest Dich der Dame zu: `n"`@Entschuldigt, aber Ihr habt ein Feld vergessen.`2"';
            $out .= '`nSie schaut Dich an: `n"`@Dieses Feld wird aus Sicherheitsgründen nicht ausgefüllt und außerdem wisst Ihr es schon!`2"';
            $out .= '`nDu wendest Dich wieder dem Formular zu "`@Aha....`2" und ließt:';
        }
        else
        {
            $out .= getsetting('ci_std_pw','');
        }
        $out .= '`n`&Portal: forum.atrahor.de';
        $out .= '`n<big><big><big>`4`bGENEHMIGT</big></big></big>';
        debuglog('hat sich ins Forum eingetragen!');
    }
    else
    {
        $out = '`2Die Dame schaut Dich an: "`@Es tut mir Leid. Ich habe keine Formulare zur Zeit`2"';
    }

    output($out);
    addnav('Zurück','dorfamt.php');
}
else if ($_GET['op']=='irc1')
{
    output('`2Die Vorzimmerdame schaut dich ungläubig an und sagt "`@Du willst Ih-Err-Zeh? Bis jetzt hat noch niemand in der Stadt herausgefunden was das überhaupt ist. Dieser Antrag wird auch sehr selten verlangt, da muß ich erstmal nachschauen, ob noch welche da sind..."`2`n Möchtest du den Schein wirklich beantragen?');
    addnav('Ja, klar!','dorfamt.php?op=irc2');
    addnav('Nein!','dorfamt.php');
}
else if ($_GET['op']=='irc2')
{
    if(user_set_aei(array('chatallowed'=>1)))
    {
        output('`2Die Dame stempelt das heutige Datum auf ein rötliches Blatt Papier und sagt "`@Hier hast Du! War das letzte, also pass auf daß es Dir keiner wegnimmt.`2"`nDu liest auf dem Zettel etwas von `@Chatiquette`2 und `@Banns wenn man sich nicht daran hält`2. Da bist du froh daß sich dein Spieler mit dem Text, der auf <a href="http://chatiquette.de" target=_blank>www.chatiquette.de</a> zu finden ist, herumschlagen muß, und gehst wieder in die Stadt.');
        debuglog('hat sich für IRC angemeldet');
    }
    else
    {
        $output('`2Die Dame schaut Dich an: "`@Es tut mir leid. Ich habe keine Formulare zur Zeit`2"');
    }

    addnav('S?Zurück in die Stadt','village.php');
}
else if ($_GET['op']=="steuernzahlen")
{
    output("\"`@Steuern zahlen könnt Ihr dritten Gang rechts...\"`n
`2Als Du zu einem kleinen alten Mann kommst, blickt dieser auf und sagt:`n
`@\"Also du willst steuern Zahlen?`n
Hm, ich guck ma deine Akte durch! Moment bitte...Da ist sie ja\"`n");

    if ($session['user']['marks']<31)
    {

        output("`^Privatakte...`n`n");
        output("`2Name: `^".$session['user']['name']."`n");
        output("`2Alter: `^".$session['user']['age']."`^ Tage`n");
        output("`2Level: `^".$session['user']['level']."`n`n");

        output("`^Sonstige Informationen...`n`n");
        output("`2Gold: `^".$session['user']['gold']."`n");
        output("`2Edelsteine: `^".$session['user']['gems']."`n");
        output("`2Gold auf der Bank: `^".$session['user']['goldinbank']."`n`n`n");

        $taxrate=getsetting("taxrate",750);
    $doubletax=2*$taxrate;
    $taxprison=getsetting("taxprison",1);

    if ($taxrate>0)
    {
        output("`^Steuern für Neuankömmlinge und Auserwählte:`n`2
                                Es müssen keine Steuern entrichtet werden!
                                `n`n
                                `^Steuern zwischen Level 5 und 10:`n`2
                                Die Steuer beträgt derzeit `^{$taxrate} Gold`2!
                                `n`n
                                `^Steuern über Level 10:`n`2
                                Die Steuer beträgt derzeit `^{$doubletax} Gold`2!
                                `n`n");
        if ($taxprison==1)
        {
            output("`4Auf Steuerhinterziehung steht ein Tag Kerker!`0");
        }
        else
        {
            output("`4Auf Steuerhinterziehung stehen {$taxprison} Tage Kerker!`0");
        }
        output('`n`n`^Solltest du über nicht genügend Gold verfügen, so kannst du dich dieses Mal von den Steuern befreien lassen.');
    }
    else
    {
        output("`^Derzeit werden keine Steuern erhoben!`n`n");
    }

        addnav("Steuern");

    }
    else
    {
        output("`n`n`2Der alte Mann lächelt dich plötzlich ganz fürsorglich an und sagt:`n");
        output("        `@\"Euren Großmut in Ehren, aber Auserwählte zahlen keine Steuern...\"`n");
    }
    addnav("Wege");
    addnav("Zurück","dorfamt.php");
}
else if ($_GET['op']=="steuernzahlen_ok")
{
    $taxrate=getsetting("taxrate",750);

    $cost = ($session['user']['level'] >= 11) ? $taxrate*2 : $taxrate;

    if ($cost>0)
    {

        if ($session['user']['steuertage']<=1)
        {
            if ($session['user']['gold']>=$cost)
            {
                output("`2Du zahlst deine `^".$cost." Gold`2 ein!`n
                                `^Wenigstens einer der die Steuern hier bezahlt...`n
                                `2Der Kassier grinst dich an und verabschiedet dich! ");
                $session['user']['gold']-=$cost;
                savesetting("amtskasse" ,getsetting("amtskasse",0)+ $cost);

            }
            else
            {
                output("`2Der Mann sagt: `^Du hast nicht genug Gold dabei, wie willst Du da die ".$cost." zahlen?`n");
                output("`^Gut, dann nehmen wir halt etwas von der Bank, hm?`n");
                if ($session['user']['goldinbank']<$cost)
                {
                    output("`^Auch nicht? Dann halt Edelsteine!`n");
                    if ($session['user']['gems']<1)
                    {
                        output("`^Du armer Tropf, Du hast ja gar nichts! Na gut, dieses mal sehe ich noch darüber hinweg! Troll Dich`n");
                    }
                    else
                    {
                        output("`^Na wenigstens etwas...jetzt troll Dich!`n");
                        $session['user']['gems']--;
                        savesetting("amtskasse" ,getsetting("amtskasse",0)+ $cost);
                    }
                }
                else
                {
                    output("`^Na wenigstens etwas...jetzt troll Dich!`n");
                    $session['user']['goldinbank']-=$cost;
                    savesetting("amtskasse" ,getsetting("amtskasse",0)+ $cost);
                }

            }
            // END nicht genug Gold in Hand

            debuglog('zahlte Steuern');
            if (getsetting("amtskasse","0")>getsetting("maxbudget","2000000"))
            {
                savesetting("amtskasse",getsetting("maxbudget","2000000"));
            }

            $session['user']['steuertage']=7;

        }
        else
        {
            output("`2Der Mann sagt: `^Du brauchst heute noch keine Steuern zahlen");
        }
    }
    else
    {
        output("`^Derzeit werden keine Steuern erhoben!`0`n");
        $session['user']['steuertage']=7;
    }
    addnav("Zurück","dorfamt.php");
}
else if ($_GET['op']=='serve')
{
    $sql = 'SELECT temple_servant FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $res = db_query($sql);
    $info = db_fetch_assoc($res);
    $info['daysinjail'] = $session['user']['daysinjail'];

    output('`2Entschlossen machst du dich daran, dein Strafregister abzuarbeiten.');

    if($session['user']['turns'] < TEMPLE_SERVANT_TURNS) {
            output('`nDoch leider musst du feststellen, dass du schon zu erschöpft dafür bist.');
    }
    else if($info['temple_servant'] >= 20) {
            output('`nDoch dann denkst du dir, dass du heute schon genug geschuftet hast, und kehrst wieder um.');
    }
    else {
            $session['user']['turns'] -= TEMPLE_SERVANT_TURNS;
            $info['temple_servant'] *= 20; // harte Arbeit markieren

            if($_GET['what'] == 'kiss') {

                    $sql = 'SELECT name,acctid,sex,profession FROM accounts WHERE profession='.PROF_GUARD.' OR profession='.PROF_GUARD_HEAD.' OR profession='.PROF_JUDGE.' OR profession='.PROF_JUDGE_HEAD.' ORDER BY RAND() LIMIT 1';
                    $res = db_query($sql);

                    if(db_num_rows($res)) {
                            $acc = db_fetch_assoc($res);
                            $acc['name'] = ($prof[$acc['profession']][$acc['sex']]).' '.$acc['name'];
                    } else {   # falls es keine besetzten Ämter gibt:
                            $acc['name'] = 'den Hauptmann';
                            $acc['sex'] = 0;
                            $acc['acctid'] = 0;
                            $acc['profession'] = 0;
                    }
                    output('`nDu suchst kurzerhand das '.($acc['profession'] < 10 ? 'Hauptquartier der Stadtwache' : 'Gericht').' auf und beginnst damit, Botendienste für '.$acc['name'].'`2 zu erledigen. ');

                    if(e_rand(1,3) == 1) {
                            output( ($acc['sex'] ? 'Sie':'Er').' ist mit Sicherheit zufrieden und gewährt dir eine zusätzliche Verringerung deines Strafmaßes.');
                            if($acc['acctid'] > 0 && e_rand(1,2) == 1) {
                                    systemmail($acc['acctid'],'`VGute Arbeit eines Strafdienstleistenden!',$session['user']['name'].'`V hat mit Freude Botendienste für dich erledigt.');
                            }
                            $lose = 2;
                    } else {
                            output( ($acc['sex'] ? 'Sie':'Er').' scheint allerdings etwas unzufrieden mit deiner Leistung zu sein. Da musst du dich beim nächsten Mal wohl noch mehr anstrengen!');
                            $lose = 1;
                    }
            } else {        // Kehren
                    output('`nNach Stunden mühsamer Arbeit qualmen dir die Finger, und dein Hals kratzt fürchterlich von der staubtrockenen Luft in den Archiven. Doch das Ergebnis kann
                            sich sehen lassen. Das wird zukünftige Archiv-Besucher mit Sicherheit freuen.`n');
                    $lose = 1;
            }

            $info['daysinjail']-=$lose;

            $sql = 'UPDATE account_extra_info SET temple_servant='.$info['temple_servant'].' WHERE acctid='.$session['user']['acctid'];
            db_query($sql);

            $session['user']['daysinjail'] = $info['daysinjail'];

            output('`nDu verlierst '.TEMPLE_SERVANT_TURNS.' Waldkämpfe und dein Strafregister vermindert sich um '.$lose.' Tagesabschnitt'.($lose > 1 ? 'e' : '').'! Es verbleiben '.($info['daysinjail']).' Tagesabschnitte. Noch genug zu tun..');
    }

    addnav('Zurück');
    addnav('Zum Eingangsbereich','dorfamt.php');
}
else if ($_GET['op']=='servant_apply')
{
    $sql = 'SELECT temple_servant FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $res = db_query($sql);
    $info = db_fetch_assoc($res);

    $info['daysinjail'] = $session['user']['daysinjail'];

    $allowed = true;

    if($info['temple_servant'] > 0) {

            output('`2Die Angestellten der Stadt wollen dich nicht schon wieder im Stadtamt sehen. Sie erklären dir, dass
                            du noch mindestens '.$info['temple_servant'].' Sonnenumläufe auf eine neuerliche Gelegenheit
                            warten musst.');
            $allowed = false;

    }

    if($session['user']['profession'] != 0) {
            $allowed = false;
    }

    if($info['daysinjail'] < TEMPLE_SERVANT_MINDAYS) {
            $allowed = false;
            output('`(Deine zu verbüßende Kerkerstrafe sind wohl nicht hoch genug. Jedenfalls weigern sich die Angestellten der Stadt hartnäckig, deine Hilfe anzunehmen.');
    }

    if($allowed) {

            $sql = 'SELECT acctid FROM accounts WHERE profession='.PROF_TEMPLE_SERVANT;
            $res = db_query($sql);

            if(db_num_rows($res) > TEMPLE_SERVANT_MAX) {
                    $allowed = false;
                    output('`(Leider, so erfährst du, gibt es bereits zu viele Strafdienstleistende. Versuche es später noch einmal.');
            }

    }

    if($allowed) {

            output('`2Du lässt dich mit einem Laufzettel ausstatten und bist von nun an das Mädchen für alles. Ob Botendienste innerhalb des Gebäudes, stundenlanges Aktensortieren oder auch
                    der eine oder andere private Gefallen für diejenigen, die dich überhaupt erst mit einer Haftstrafe versehen haben - alle kleinen Arbeiten fallen fortan dir zu, auf dass
                    deine Kerkertage möglichst schnell reduziert werden.`n
                    Es versteht sich von selbst, dass du in der Zwischenzeit keinerlei Straftaten begehen darfst.');

            $session['user']['profession'] = PROF_TEMPLE_SERVANT;
            addnews($session['user']['name'].'`8 wird nun einige Zeit lang Strafdienst im Stadtamt verrichten.');
            $sql = 'UPDATE account_extra_info SET temple_servant=1 WHERE acctid='.$session['user']['acctid'];
            db_query($sql);
    }

    addnav('Zurück');
    addnav('Zum Eingangsbereich','dorfamt.php');
}
else if ($_GET['op']=='servant_stop')
{
    $sql = 'SELECT name FROM accounts WHERE acctid='.(int)$_GET['id'];
    $acc = db_fetch_assoc(db_query($sql));

    $sql = 'UPDATE accounts SET profession = 0 WHERE acctid='.(int)$_GET['id'];
    db_query($sql);

    $sql = 'UPDATE account_extra_info SET temple_servant = 20 WHERE acctid='.(int)$_GET['id'];
    db_query($sql);

    systemmail($_GET['id'],'`4Entlassung',$session['user']['name'].'`4 hat deinen Strafdienst im Stadtamt vorzeitig für beendet erklärt.');

    $sql = 'INSERT INTO news SET newstext = "'.addslashes($acc['name']).'`8s Strafdienst im Stadtamt wurde vorzeitig für beendet erklärt.",newsdate=NOW(),accountid='.$_GET['id'];
    db_query($sql) or die (db_error(LINK));

    redirect('dorfamt.php?op=servant_list');
    exit;

}
else if ($_GET['op']=='servant_list')
{
    addnav('Zurück');
    if(in_array($session['user']['profession'],array(PROF_PRIEST_HEAD,PROF_GUARD_HEAD,PROF_JUDGE_HEAD,PROF_MAGE_HEAD,PROF_DDL_CAPTAIN,PROF_MERCH_HEAD)) || su_check(SU_RIGHT_DEBUG)) {
            show_servant_list(true);
    } else {
            show_servant_list();
    }
    addnav('Zum Eingangsbereich','dorfamt.php');
} else if ($_GET['op'] == 'prof_list') {
    $arr_prof_list = array();

    $str_judges = '<tr class="trhead"><td>'.$profs[PROF_JUDGE_HEAD][3].$profs[PROF_JUDGE_HEAD][4].'`0 `bDie ehrenwerten Richter:`b</td></tr>';
    //$str_priests = '<tr class="trhead"><td>`bDie würdigen Priester:`b</td></tr>';
    $str_guards = '<tr class="trdark"><td style="height: 10px;"></td></tr><tr class="trhead"><td>'.$profs[PROF_GUARD_HEAD][3].$profs[PROF_GUARD_HEAD][4].'`0 `bDie tapferen Wachen:`b</td></tr>';
    $str_mages = '<tr class="trdark"><td style="height: 10px;"></td></tr><tr class="trhead"><td>'.$profs[PROF_MAGE_HEAD][3].$profs[PROF_MAGE_HEAD][4].'`0 `bDie weisen Magier:`b</td></tr>';
    //$str_harbor = '<tr class="trdark"><td style="height: 10px;"></td></tr><tr class="trhead"><td>'.$profs[PROF_DDL_CAPTAIN][3].$profs[PROF_DDL_CAPTAIN][4].'`0 `bDie furchtlosen Hafenwächter:`b</td></tr>';
    $str_merchants = '<tr class="trdark"><td style="height: 10px;"></td></tr><tr class="trhead"><td>'.$profs[PROF_MERCH_HEAD][3].$profs[PROF_MERCH_HEAD][4].'`0 `bDie angesehenen Händler:`b</td></tr>';
    $str_txt = '';

    $sql = 'SELECT accounts.name, profession, sex, login FROM accounts WHERE profession > 0 ORDER BY profession DESC, dragonkills DESC, acctid ASC';
    $res = db_query($sql);
    while ($a = db_fetch_assoc($res))
    {
        // Wenn Beruf öffentlich angezeigt werden soll
        if ($profs[$a['profession']][2])
        {
            if($session['user']['prefs']['popupbio'] == 1)
                {
                    $biolink="bio_popup.php?char=".rawurlencode($a['login']);
                    $str_biolink = "<a href='".$biolink."' target='_blank' onClick='".popup_fullsize($biolink).";return:false;'>`&".$a['name']."</a>";
                }
                else
                {
                    $biolink="bio.php?char=".rawurlencode($a['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
                    $str_biolink = "<a href='".$biolink."'>`&".$a['name']."</a>";
                    addnav("","bio.php?char=".rawurlencode($a['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                }

            $str_txt = '<tr class="trlight"><td><a href="mail.php?op=write&to='.rawurlencode($a['login']).'" target="_blank" onClick="'.popup('mail.php?op=write&to='.rawurlencode($a['login'])).';return false;"><img src="images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>'.$profs[$a['profession']][3].$profs[$a['profession']][$a['sex']].' `0'.$str_biolink.'`0</td></tr>';

            switch ($a['profession'])
            {
                case PROF_JUDGE:
                case PROF_JUDGE_HEAD:
                    $str_judges .= $str_txt;
                    break;

                case PROF_GUARD:
                case PROF_GUARD_SUB:
                case PROF_GUARD_HEAD:
                    $str_guards .= $str_txt;
                    break;

                /*case PROF_PRIEST:
                case PROF_PRIEST_HEAD:
                    $str_priests .= $str_txt;
                    break;*/

                case PROF_MAGE:
                case PROF_MAGE_HEAD:
                    $str_mages .= $str_txt;
                    break;

                /*case PROF_DDL_RECRUIT:
                case PROF_DDL_MATE:
                case PROF_DDL_LIEUTENANT:
                case PROF_DDL_CAPTAIN:
                    $str_harbor .= $str_txt;
                    break;*/
                    
                case PROF_MERCH:
                case PROF_MERCH_HEAD:
                    $str_merchants .= $str_txt;
                    break;

            }
        }
    }

    output('`c`b`&Helden dieser Stadt, die ein offizielles Amt innehaben:`c`b`n');

    $out = '`c<table cellspacing="2" cellpadding="2" align="center">';

    //$out .= $str_judges.$str_priests.$str_mages.$str_guards.$str_harbor;
    $out .= $str_judges.$str_mages.$str_guards.$str_merchants;

    $out .= '</table>`c';

    output($out,true);
    
    addnav('Weitere Infos');
    addnav('Beschreibung der Berufsgruppen','library.php?op=book&bookid=51');
    addnav('Zurück');
    addnav('Zum Eingangsbereich','dorfamt.php');
} else if ($_GET['op'] == 'prof_assembly') {
    // * array: IDs der Berufsgruppen-Chefs
    $arr_leaders = array(PROF_GUARD_HEAD,PROF_PRIEST_HEAD,PROF_JUDGE_HEAD,PROF_MAGE_HEAD,PROF_DDL_CAPTAIN,PROF_MERCH_HEAD);
    // array: IDs der restlichen Amtsträger
    $arr_officials = array(PROF_GUARD,PROF_PRIEST,PROF_JUDGE,PROF_MAGE,PROF_DDL_RECRUIT,PROF_DDL_MATE,PROF_DDL_LIEUTENANT,PROF_MERCH);
    // end *
    $str_link = 'dorfamt.php?op=prof_assembly';
    $str_what = (isset($_GET['what']) ? $_GET['what'] : '');
    switch($str_what) {
        // Besprechungsraum
        case '':
            output('`c`b°#FFE600;In der `i°#FFE600;Amtsstube`i`b`c`n
                    `FVor dir öffnet sich ein etwas größerer Raum, der durch drei hohe Fenster lichtdurchflutet ist. Einige aneinandergereihte Tische bilden ein Quadrat und nehmen den
                    Großteil des Raumes ein. Holzstühle stehen überall herum, nur wenige sind noch ordentlich unter die Tische geschoben. Sie verraten dir - zusammen mit dem guten Dutzend
                    halb herabgebrannter Kerzenstummel, die sich auf die Tische verteilen -, dass die letzte Nutzung des Raums wohl noch nicht allzu lang zurück liegen kann.`n
                    Gegenüber der Fensterseite hängen insgesamt vier mannshohe Banner an der Wand: ein dunkelrotes mit aufgesticktem Symbol der
                   '.$profs[PROF_GUARD_HEAD][3].$profs[PROF_GUARD_HEAD][4].' Stadtwache`F, ein braunes mitsamt dem Wappen für die '.$profs[PROF_JUDGE_HEAD][3]
                    .$profs[PROF_JUDGE_HEAD][4].' Richter`F, ein blaues, welches durch sein Symbol an die '.$profs[PROF_MAGE_HEAD][3].$profs[PROF_MAGE_HEAD][4].' Magier `Ferinnert,
                    und ein grünes, auf dem das Emblem der '.$profs[PROF_MERCH_HEAD][3].$profs[PROF_MERCH_HEAD][4].' Händlergilde`F zu sehen ist. Damit ist wohl jede städtische
                    Berufsgruppe berechtigt, diesen Raum für sich nutzen zu dürfen, sei es für Absprachen untereinander oder für Konferenzen mit Bürgern, die kein städtisches Amt
                    bekleiden.`n`n');
            /*// Per Zufall eine Nachricht vom Nachrichtenbrett anzeigen
            $sql = db_query("SELECT b.message,b.postdate,b.comments,a.name AS author FROM boards b LEFT JOIN accounts a ON b.author = a.acctid
                                    WHERE b.section = 'prof_assembly' ORDER BY RAND() LIMIT 1");
            if(db_num_rows($sql) > 0) {
                $rowb = db_fetch_assoc($sql);
                output('`c<div style="border: 2px ridge #D38828; border-radius: 5px; width: 50em; padding: 1em; text-align: justify;">
                        '.$rowb['message'].'`n
                        `n
                        `7Verfasser: '.$rowb['author'].'`n
                        `7Betrifft: '.$rowb['comments'].'`7
                        </div>`c`n`n');
            }
            // end */
            addcommentary();
            viewcommentary('p_assembly','Sagen',20,'sagt');
            //addnav('Aktuelles');
            //addnav('Nachrichtenbrett',$str_link.'&what=board');
            // Zutritt nur für Amtsträger:
            if(in_array($session['user']['profession'],$arr_leaders) || in_array($session['user']['profession'],$arr_officials) || su_check(SU_RIGHT_DEBUG)) {
                addnav('Für Amtsträger');
                addnav('H?Ins Hinterzimmer',$str_link.'&what=backroom');
            }
            // end
            addnav('Zurück');
        break;
        // Besprechungsraum
        case 'backroom':
            output('`c`b`YAmtsträger unter sich`b`c`n
                    `XEine unscheinbare Tür führt vom eigentlichen Konferenzraum in einen etwas kleineren Nebenraum, in welchem ebenfalls bestuhlte Tische stehen, doch diesmal bilden sie
                    ein langgezogenes Rechteck. Alles wirkt weniger förmlich, dafür umso praktischer: Auf den Sitzflächen der Stühle liegen Kissen, es stehen weit mehr Kerzenständer herum,
                    und auf jedem dritten Tisch liegen Papierstapel direkt neben kleinen Tintenfässchen und gut gepflegten Federkielen. Kein Zweifel: In diesem Raum finden die eigentlichen
                    Gespräche zwischen den Amtsträgern statt, hier entscheiden sie über gemeinsame Projekte und Kooperationen zum Wohle der Stadt.`n
                    Dir als Amtsträger ist es gestattet, dich hier aufzuhalten. Alle anderen Bürger Eranyas haben dagegen keinen Zutritt zu diesem Raum.`n
                    `n
                    `i`7(Dieser Ort kann für OOC-Gespräche und/oder Absprachen genutzt werden.)`i`n`n');
            addcommentary();
            viewcommentary('p_assembly_priv','Sagen',20,'sagt');
            //addnav('Aktuelles');
            //addnav('Nachrichtenbrett',$str_link.'&what=board');
            addnav('Zurück');
            addnav('K?Zum Konferenzraum',$str_link);
        break;
        // Nachrichtenbrett mit Plot-Angeboten
        case 'board':
            $bool_is_leader = (in_array($session['user']['profession'],$arr_leaders) || su_check(SU_RIGHT_DEBUG) ? true : false);
            output('`c`b°#FFE600;Aktuelles`b`c`n
                    `FDu trittst an das Nachrichtenbrett heran und siehst nach, was gerade alles in der Stadt passiert:`n`n`c');
            $sql = db_query("SELECT b.id,b.message,b.postdate,b.comments,a.name AS author FROM boards b LEFT JOIN accounts a ON b.author = a.acctid
                                    WHERE b.section = 'prof_assembly' ORDER BY b.postdate DESC");
            $int_c = db_num_rows($sql);
            if($int_c > 0) {
                for($i=0;$i<$int_c;$i++) {
                    $rowb = db_fetch_assoc($sql);
                    output('<div style="border: 2px ridge #D38828; border-radius: 5px; width: 50em; padding: 1em; text-align: justify;">
                            '.$rowb['message'].'`n
                            `n
                            `7Verfasser: '.$rowb['author'].'`n
                            `7Betrifft: '.$rowb['comments'].'`7');
                    if($bool_is_leader) {
                        output('`n`&[<a href="'.$str_link.'&what=boarddel&msgid='.$rowb['id'].'">Entfernen</a>`&]');
                        addnav('',$str_link.'&what=boarddel&msgid='.$rowb['id']);
                    }
                    output('</div>`n');
                }
            } else {
                output('`i`7Es wurden noch keine Meldungen verfasst.`i');
            }
            if($bool_is_leader) {
                addnav('Nachrichtenbrett');
                addnav('Neue Meldung verfassen',$str_link.'&what=boardadd');
            }
            addnav('Zurück');
            // Zutritt nur für Amtsträger:
            if(in_array($session['user']['profession'],$arr_leaders) || in_array($session['user']['profession'],$arr_officials) || su_check(SU_RIGHT_DEBUG)) {
                addnav('H?Ins Hinterzimmer',$str_link.'&what=backroom');
            }
            // end
            addnav('K?Zum Konferenzraum',$str_link);
        break;
        // Nachrichtenbrett: Nachricht hinzufügen
        case 'boardadd':
            if($_GET['act'] == 'save') {
                $str_message = closetags(soap(strip_tags($_POST['msg'])),'`i`b`c');
                if(strlen($str_message) >= 10) {
                    $arr_involved = array();
                    if($_POST['guards']) $arr_involved['guards'] = '`4Stadtwache';
                    if($_POST['ddl']) $arr_involved['ddl'] = '`2Hafenwacht';
                    if($_POST['mages']) $arr_involved['mages'] = '`wMagier';
                    if($_POST['judges']) $arr_involved['judges'] = '`TRichter';
                    $str_involved = implode('`7, ',$arr_involved);
                    if(strlen($str_involved) > 3) {
                        db_query("INSERT INTO boards(message,author,postdate,section,expire,comments)
                                              VALUES('".db_real_escape_string($str_message)."',".$session['user']['acctid'].",NOW(),'prof_assembly',(NOW()+INTERVAL 1 YEAR),'".$str_involved."')");
                        output('`@Die Nachricht wurde erstellt.');
                    } else {
                        output('`CFehler! `FDeine Nachricht muss sich an mind. eine Berufsgruppe richten.');
                        $session['prof_msg'] = $str_message;
                    }
                } else {
                    output('`CFehler! `FDeine Nachricht darf nicht kürzer als 10 Zeichen sein.');
                    $session['prof_msg'] = $str_message;
                }
                addnav('Zurück');
                addnav('Zur Übersicht',$str_link.'&what=board');
            } else {
                output('`FGib hier eine Nachricht ein, die für alle anderen Amtsträger sichtbar wird: (kein HTML erlaubt)`n
                        `n
                        <form action="'.$str_link.'&what=boardadd&act=save" method="post">');
                addnav('',$str_link.'&what=boardadd&act=save');
                $arr_form = array('msg'=>'Nachricht:,textarea,60,10,1500'
                                 ,'An wen richtet sich die Nachricht? An die...,divider'
                                 ,'guards'=>appoencode('`4Stadtwache').',checkbox,1,checked'
                                 ,'ddl'=>appoencode('`2Hafenwacht').',checkbox,1,checked'
                                 ,'mages'=>appoencode('`wMagier').',checkbox,1,checked'
                                 ,'judges'=>appoencode('`TRichter').',checkbox,1,checked'
                                 );
                showform($arr_form,array(),false,'Abschicken');
                output('</form>');
                addnav('Zurück');
                addnav('Zur Übersicht',$str_link.'&what=board');
            }
        break;
        // Nachrichtenbrett: Nachricht löschen
        case 'boarddel':
            if($_GET['act'] == 'confirm') {
                db_query("DELETE FROM boards WHERE id=".$_GET['msgid']);
                output('`CDie Nachricht wurde entfernt.');
                addnav('Weiter');
                addnav('Zur Übersicht',$str_link.'&what=board');
            } else {
                $rowb = db_fetch_assoc(db_query("SELECT b.message,b.postdate,b.comments,a.name AS author FROM boards b LEFT JOIN accounts a ON b.author = a.acctid
                                                        WHERE b.section = 'prof_assembly' AND b.id = ".$_GET['msgid']));
                output('`^Bist du dir sicher, dass du die folgende Nachricht entfernen möchtest?`n`n
                        `c<div style="border: 2px ridge #D38828; border-radius: 5px; width: 50em; padding: 1em; text-align: justify;">
                        '.$rowb['message'].'`n
                        `n
                        `7Verfasser: '.$rowb['author'].'`n
                        `7Betrifft: '.$rowb['comments'].'`7
                        </div>`c`n`n');
                addnav('Nachrichtenbrett');
                addnav('Ja, entfernen',$str_link.'&what=boarddel&act=confirm&msgid='.$_GET['msgid']);
                addnav('A?Nein, Abbruch',$str_link.'&what=board');
                addnav('Zurück');
            }
        break;
        // debug
        default:
            debug_message('fehlendes what: '.$str_what.' in '.$str_link);
        break;
    }
    addnav('E?Zur Eingangshalle','dorfamt.php');
}

page_footer();
?>

