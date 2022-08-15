
<?php

// Rohgerüst für ein späteres Add-on
// by Maris (Maraxxus@gmx.de)
// Änderungen: Rohgerüst aufgefüllt

require_once 'common.php';
require_once(LIB_PATH.'profession.lib.php');
addcommentary(false);
checkday();

if ($session['user']['alive']==0)
{
redirect('shades.php');
}

switch ($_GET['op'])
{
    case 'explore':
        page_header('Erkundung des Küstengebiets');
        output('`2Du gehst hinunter zu den Piers und steuerst auf ein kleines Segelboot zu, dessen Außenseiten grün gestrichen sind. Nur wenige gekonnte
                Handgriffe später ist es seeklar, und schon treibst du mit Hilfe einer sanften Brise hinaus aufs Meer.`n
                Du entscheidest dich dazu, die Küste abzufahren, um dir ein wenig die Landschaft anzusehen. Insbesondere die steilen Klippen fangen deinen Blick
                ein - du entdeckst sogar den einen oder anderen Tunneleingang, von dem du bisher gar nichts wusstest. Auch der Wald, der sich hinter den
                Steilhängen dunkel erstreckt, präsentiert sich vom Meer aus ganz anders: Teilweise kannst du Tiere aus dem Dickicht herauslinsen sehen, die
                du noch nie zuvor gesehen hast. Und nicht nur zu Lande, auch im Wasser erkennst du den einen und anderen Meeresbewohner, wie er sich deinem
                Boot nähert, nur, um dann anschließend doch wieder ins tiefe Blau hinabzutauchen.`n
                Du begreifst, dass, je länger du mit dem Boot unterwegs bist, du umso mehr neue Entdeckungen machen und somit mehr Wissen und Erfahrung
                sammeln wirst.`n');
        if ($session['user']['turns'] < 1)
        {
            output("`n`n`2Leider hast du nicht mehr die Kraft, um heute noch auf Erkundungsfahrt zu gehen.");
        }
        else
        {
            output("`2Wie viele Runden lang willst du erkunden gehen?`n`n
                    <form action='expedition.php?op=explore2' method='POST'><input name='eround' id='eround' value='".$session['user']['turns']."'> <input type='submit' class='button' value='Erkunden gehen'></form>
                    <script language='JavaScript'>document.getElementById('eround').focus();</script>",true);
            addnav("","expedition.php?op=explore2");
        }
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        break;
    case 'explore2':
        page_header('Erkundung des Küstengebiets');
        $eround = abs((int)$_GET['eround'] + (int)$_POST['eround']);
        if ($session['user']['turns'] <= $eround)
        {
            $eround = $session['user']['turns'];
        }
        $session['user']['turns']-=$eround;
        $exp = (($session['user']['level']*0.4)+2)*e_rand(10,20)+e_rand(5,10);
        $totalexp = (int)($exp*$eround);
        $session['user']['experience']+=$totalexp;
        output("`2Du kommst von deiner Erkundungsfahrt zurück und fühlst dich deutlich erfahrener!`n");
        output("`2Du hast `^".$totalexp."`2 Erfahrung bekommen!`n");
        debuglog('`Ihat die Erkundung genutzt, um Erfahrung zu sammeln');
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        break;
    case 'search' :

        page_header('Auf Schatzsuche');
        output('`2Jemand in der Stadt hat dir zugeflüstert, dass das Meer vor Eranya nur so wimmelt von kleinen und größeren Schätzen - manches wurde
                achtlos fort geworfen, doch anderes wiederum stammt aus gesunkenen Schiffen, herangetragen von der Meeresströmung. Nun warten all diese Dinge nur
                auf den, der zu ihnen hinab taucht und sie birgt.`n
                Das lässt du dir natürlich nicht zweimal sagen. Schnell nimmst du dir eins der kleinen Segelboote und fährst hinaus aufs Meer, obwohl du gar
                nicht genau weißt, wo die Schiffe denn genau gesunken sind. Nur grob hat man dir den Ort verraten. Das hilft dir zwar, in etwa die richtige Gegend
                zu finden, wird dich aber trotzdem nicht davor bewahren, mehrmals hinabtauchen zu müssen, wodurch du definitiv eine Runde verlieren würdest.`n');
        addnav('Aktionen');
        addnav('Schätze suchen','expedition.php?op=search2');
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        break;

    case 'search2' :
        page_header('Auf Schatzsuche');
        if ($session['user']['turns']>0)
        {
            $findit=e_rand(1,25);

            // Beutebuff
                        if($session['bufflist']['beutegeier']) {
                                $str_out .= $session['bufflist']['beutegeier']['effectmsg'].'`n';

                                if(e_rand(1,5) == 1) {
                                        $findit = 6;
                                }
                                else {
                                        $str_out .= $session['bufflist']['beutegeier']['failmsg'].'`n';
                                }

                                $session['bufflist']['beutegeier']['rounds']--;
                                if($session['bufflist']['beutegeier']['rounds'] <= 0) {
                                        $str_out .= $session['bufflist']['beutegeier']['wearoff'].'`n';
                                        unset($session['bufflist']['beutegeier']);
                                }

                        }

            if ($findit == 2)
            {
                //gem
                output('`^Du findest EINEN EDELSTEIN!`n`&');
                $session['user']['gems']++;
            }
            else if ($findit == 4)
            {
                //donation
                output('`&Du findest zwar keinen Schatz, aber die Götter meinen es gut mit dir und gewähren dir `^2 Donation-Punkte`&.');
                $session['user']['donation']+=2;
            }
            else if ($findit == 6)
            {

                // item
                $item_hook_info['chance'] = item_get_chance();

                if($session['bufflist']['beutegeier']) {
                                        $item_hook_info['chance'] = max($item_hook_info['chance']-1,1);
                                }

                                $res = item_tpl_list_get( 'find_forest='.$item_hook_info['chance'] , 'ORDER BY RAND('.e_rand().') LIMIT 1' );

                if (db_num_rows($res) )
                {

                    $item = db_fetch_assoc($res);

                    if (!empty($item['find_forest_hook']))
                    {
                        item_load_hook($item['find_forest_hook'] , 'find_forest' , $item );
                    }

                    if(!$item_hook_info['hookstop'])
                    {
                        if (item_add($session['user']['acctid'], 0, $item ) )
                        {
                            output('`&Du hast das Beutestück `q'.$item['tpl_name'].'`& gefunden! ('.$item['tpl_description'].')!`n`n`&');
                        }
                    }

                }

            }
            else if ($findit == 8 || $findit == 9)
            {
                // bone

                item_add($session['user']['acctid'],'abgnkno');

                output('`&Du hast einen `qabgenagten Knochen`& geborgen...`n`n`&');
            }
            else if ($findit == 10 && e_rand(1,4)==2)
            {
                // armor
                $sql = 'SELECT * FROM armor WHERE defense<='.($session['user']['level']+5).' ORDER BY rand('.e_rand().') LIMIT 1';
                $result2 = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result2)>0)
                {
                    $row2 = db_fetch_assoc($result2);
                    $row2['value']=round($row2['value']/10);

                    $item['tpl_name'] = addslashes($row2['armorname']);
                    $item['tpl_value1'] = addslashes($row2['defense']);
                    $item['tpl_gold'] = addslashes($row2['value']);
                    $item['tpl_description'] = 'Gebrauchte Level '.$row2['level'].' Rüstung mit '.$row2['defense'].' Verteidigung.';

                    item_add($session['user']['acctid'],'rstdummy',$item);

                    output('`n`&Du findest die Rüstung `%'.$row2['armorname'].'`&!`n`n`#');
                }
            }
            else if ($findit == 12 && e_rand(1,4)==2)
            {
                // weapon
                $sql = 'SELECT * FROM weapons WHERE damage<='.($session['user']['level']+5).' ORDER BY rand('.e_rand().') LIMIT 1';
                $result2 = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result2)>0)
                {
                    $row2 = db_fetch_assoc($result2);
                    $row2['value']=round($row2['value']/10);

                    $item['tpl_name'] = addslashes($row2['weaponname']);
                    $item['tpl_value1'] = addslashes($row2['attack']);
                    $item['tpl_gold'] = addslashes($row2['value']);
                    $item['tpl_description'] = 'Gebrauchte Level '.$row2['level'].' Waffe mit '.$row2['attack'].' Angriff.';

                    item_add($session['user']['acctid'],'waffedummy',$item);
                    output('`n`&Du findest die Waffe `%'.$row2['weaponname'].'`Q!`n`n`#');
                }
            }
            else if ($findit == 18 && e_rand(1,5) == 5)
            {
                // antidote
                output("`6Du findest eine seltene Shurisa-Alge, die eine starke Gift neutralisierende Wirkung hat. Du zögerst keinen Moment,
                        ihren Saft zu gewinnen, und erzeugst somit eine Phiole Truhenfallen-Antiserum!`n`n");

                item_add($session['user']['acctid'],'antiserum');

            }
            else
            {
                addnav('Aktionen');
                addnav('Nochmal!','expedition.php?op=search2');
                output('`&Leider hast du bei deinem Tauchgang nichts von Wert gefunden...`n');
            }
            $session['user']['turns']--;
        }
        else
        {
            output('`2Heute nicht mehr, du fühlst dich einfach zu müde.');
        }
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        break;

    case 'claim' :

        page_header('Meeresregionen untersuchen');
        output('`2Neulich in der Stadt hast du einige Fischer darüber reden hören, wie wenig erforscht die Gewässer vor Eranya noch sind. Dabei baut die Stadt auf
                regen Fischhandel - Grund genug, die Geheimnisse des Meeres näher zu erkunden, hm? Dem kannst du nur zustimmen, und so leihst du dir eines der
                Segelboote der Hafenwacht, um damit ein wenig durch die Gegend zu fahren. Vielleicht machst du ja eine Entdeckung, die den Fischern Eranyas
                von Nutzen ist?`n
                `n
                `i`S(Pro Fahrt wird dich das eine Schlossrunde kosten.)`i`n');
        addnav('Untersuchen');
        addnav('Seichtes Gewässer','expedition.php?op=claim2&what=1');
        addnav('Nahes Küstenmeer','expedition.php?op=claim2&what=2');
        addnav('Fernes Küstenmeer','expedition.php?op=claim2&what=3');
        addnav('Hohe See','expedition.php?op=claim2&what=4');
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        break;

    case 'claim2' :
        page_header('Meeresregionen auskundschaften');
        if ($session['user']['castleturns']>0)
        {
            $what=$_GET['what'];
            switch ($what)
            {
            case '1':
                $limit=70;
                $gold=500;
                $gems=0;
                $text="`2Du hast eine Stelle entdeckt, an der eine essbare Algensorte wächst! Das wird die Fischer Eranyas sicherlich freuen.`0`n";
                break;
            case '2':
                $limit=50;
                $gold=1000;
                $gems=1;
                $text="`2Du hast einen kleinen Fischschwarm entdeckt! Das wird die Fischer Eranyas sicherlich freuen.`0`n";
                break;
            case '3':
                $limit=30;
                $gold=2000;
                $gems=2;
                $text="`2Du hast einen großen Fischschwarm entdeckt! Das wird die Fischer Eranyas sicherlich freuen.`0`n";
                break;
            case '4':
                $limit=10;
                $gold=5000;
                $gems=8;
                $text="`2Du hast ein Schiffswrack voller Ladung entdeckt! Schnell schaffst du dessen Schätze zurück ins Quartier.`0`n";
                break;
            }
            $chance=e_rand(1,100);
            if ($chance<=$limit)
            {
                output('`@Glückwunsch!`n'.$text);
                output('`2Die Leitung der Hafenwacht ist mit deiner Leistung derart zufrieden, dass sie dir eine `@Belohnung von '.$gold.' Gold und '.$gems.' Edelsteinen `2überreicht!`n`n');
                $session['user']['gold']+=$gold;
                $session['user']['gems']+=$gems;
            }
            else
            {
                output('`2Du segelst eine Weile durch die Gegend, doch trotz wiederholter Tauchgänge findest du nichts von Wert. Schade.`n');
            }
            addnav('Auskundschaften');
            addnav('Weiter erkunden','expedition.php?op=claim');
        }
        else
        {
            output('`2Du kannst heute keine Entdeckungsfahrten mehr durchführen!`n');
        }
        $session['user']['castleturns']--;
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        break;

    case 'chief' :
        page_header('Leitung der Hafenwacht');
        output('`c`b`pBüro der Hafenwacht-Leitung`b`c`n
                `2Nach höflichem Klopfen und kurzem Warten drückst du die Klinke nach unten und trittst in den Raum hinein. Er ist nicht allzu groß und wirkt nur
                noch kleiner beim Anblick der vielen vollgestellten Regale und Glasschränke. Auch der Schreibtisch, seinerseits so lang wie ein erwachsener Mann
                hoch, nimmt viel Platz ein - und ist ebenfalls übersäht mit Dokumenten und Instrumenten aller Art. Kein Zweifel, hier wird hart gearbeitet.`n`n');
        viewcommentary('expedition_chief','Sagen',25,"sagt");
        if ($session['user']['profession'] == PROF__DDL_CAPTAIN || $session['user']['profession'] == PROF__DDL_LIEUTENANT || su_check(SU_RIGHT_DEBUG))
        {
                addnav('Hafenwacht');
                addnav('Massenmail','expedition.php?op=massmail');
        }
        addnav('Zurück');
        addnav('Zum Eingangsbereich','expedition.php');
        break;

    case 'massmail': // Massenmail (im wohnviertel by mikay)
        page_header('Massenmail verschicken');
        $str_out .= "`c`b`2Posthörnchenkobel unter dem Dach des Quartiers der Hafenwacht`b`c`n`n";

        addnav('Abbrechen','expedition.php?op=chief');

        $sql='SELECT acctid, name, login, profession
                FROM accounts
                WHERE profession>='.PROF_DDL_RECRUIT.'
                AND profession<='.PROF_DDL_CAPTAIN.'
                AND acctid!='.(int)$session['user']['acctid'].'
                ORDER BY profession DESC';
        $result=db_query($sql);
        $users=array();
        $keys=0;

        while($row=db_fetch_assoc($result))
        {
                $profs[0][0]='Zivilist';
                if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

                $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" onclick="chk()" checked> '.$row['name'].'<br>';
                $keys++;
                $lastprofession=$row['profession'];

                if ($_POST['title']!='' && $_POST['maintext']!='' && in_array($row['acctid'],$_POST['msg']))
                {
                        $users[]=$row['acctid'];
                }
        }

        $mailsends=count($users);

        if ($mailsends<=5)
        {
                $gemcost=1;
        }
        elseif ($mailsends<=15)
        {
                $gemcost=2;
        }
        elseif ($mailsends<=25)
        {
                $gemcost=3;
        }
        elseif ($mailsends>25)
        {
                $gemcost=4;
        }
        $gemcost=0;

        if ($session['user']['gems']>=$gemcost AND $mailsends>0)
        {
                foreach($users as $id)
                {
                        systemmail($id, $_POST['title'], $_POST['maintext'], $session['user']['acctid']);
                }

                $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Hörnchenpost erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
                $session['user']['gems']-=$gemcost;
        }
        elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
        {
                $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Hörnchenpost erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
        }

        if ($keys>0)
        {
                $str_out .= form_header('expedition.php?op=massmail')
                .$sendresult.'
                <table border="0" cellpadding="0" cellspacing="10">
                        <tr>
                                <td><b>Betreff:</b></td>
                                <td><input type="text" name="title" id="title" value="" onkeydown="chk()" onfocus="chk()"></td>
                        </tr>
                        <tr>
                                <td valign="top"><b>Nachricht:</b></td>
                                <td><textarea name="maintext" id="maintext" rows="15" cols="50" class="input" onkeydown="chk()" onfocus="chk()"></textarea></td>
                        </tr>
                        <tr>
                                <td valign="top"><b>Senden an:</b></td>
                                <td>'.$residents.'
                                        `bKosten bis jetzt:`b <span id="cost">0</span> Edelstein(e)!
                                </td>
                        </tr>
                        <tr>
                                <td></td>
                                <td>
                                        <span id="but" style="visibility:hidden;"><input type="submit" value="Posthörnchen auf die Reise schicken!" class="button"><br></span>
                                        <span id="msg">Bitte verfasse nun deine Botschaft und wähle die Empfänger!</span></td>
                        </tr>
                </table>
                </form>
                <script type="text/javascript">
                var els = document.getElementsByName("msg[]");
                function chk () {
                        var ok = false;
                        var c = 0;
                        for(i=0;i<els.length;i++) {
                                if(els[i].checked) {
                                        ok = true;
                                        c++;
                                }
                        }

                        if(!document.getElementById("title").value && !document.getElementById("maintext").value) {
                                ok = false;
                        }

                        document.getElementById("msg").style.visibility = (ok ? "hidden" : "visible");
                        document.getElementById("but").style.visibility = (ok ? "visible" : "hidden");

                        if(c <= 3) {
                                c = 1;
                        }
                        else if(c <= 10) {
                                c = 2;
                        }
                        else if(c <= 25) {
                                c = 3;
                        }
                        else {
                                c = 4;
                        }
                        c = 0;

                        document.getElementById("cost").innerHTML = c;
                }
                </script>
                ';
        }
        else
        {
                $str_out .= '`c`bEs wurden noch keine Bürger ernannt - und ja, explosive Hörnchenpost an missliebige Nachbarn sind gegen das Gesetz.`b`c';
        }
        output($str_out);
        break;
    // END massmail

    case 'inn' :
        page_header('Der Gemeinschaftsraum');
        output('`p`c`bIm Gemeinschaftsraum`b`c`n
                `2Du näherst dich der Tür zum Gemeinschaftsraum - und kannst, noch bevor du sie öffnest, bereits das Raunen mehrerer Stimmen hören, die sich
                zu unterhalten scheinen. Und tatsächlich: Viele der Tischgruppen, die über den ganzen Raum verteilt wurden, sind belegt, überall sitzen andere
                Mitglieder der Hafenwacht zusammen, machmal in lockerer Runde, manchmal aber auch über irgendwelche Pläne gebeugt und hochkonzentriert.
                Du grüßt das eine oder andere bekannte Gesicht und suchst dir dann selbst einen guten Platz.`n`n');
        viewcommentary('expedition_inn','Sagen',25,"sagt");
        addnav('Hafenwacht');
        addnav('Mein Rang','expedition.php?op=myrank');
        addnav('Zurück');
        addnav('Zum Eingangsbereich','expedition.php');
        break;

    case 'ranks' :
        page_header('Im Gemeinschaftsraum');
        output('`^`cFolgende Mitglieder der Hafenwacht setzen sich tagtäglich für die Sicherheit der Stadt ein:`0`n`n');

        $sql = "SELECT acctid,name,level,login,loggedin,dragonkills,sex,profession FROM accounts JOIN account_extra_info USING (acctid) WHERE expedition!=0 AND profession > 40 AND profession < 45 ORDER BY profession DESC,dragonkills DESC, level DESC LIMIT 50";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
        output("<tr class='trhead' style='text-align: center;'><td><b>DKs</b></td><td><b>Level</b></td><td><b>Name</b></td><td><b><img src=\"images/female.png\">/<img src=\"images/male.png\"></b></td><td><b>Status</b></td><td><b>Rang</b></td>".
               (($session['user']['profession']==PROF_DDL_CAPTAIN || $session['user']['profession']==PROF_DDL_LIEUTENANT || su_check(SU_RIGHT_EXPEDITION_ADMIN)) ?
                       "<td><b>Verwalten</b></td>" : "").
               "</tr>",true);
        $max = db_num_rows($result);
        for ($i=0; $i<$max; $i++)
        {
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trdark":"trlight")."' style='text-align: center;'><td>
                    `^$row[dragonkills]`0</td><td>
                    `^$row[level]`0</td><td align='left'>
                    <a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
            if ($session['user']['prefs']['popupbio'] == 1) {
                output("<a href='bio_popup.php?id=".$row['acctid']."' target='_blank' onClick='".popup_fullsize($link).";return:false;'>`&".$row['name']."`0</a>");
            } else {
                output("<a href='bio.php?id=".$row['acctid']."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>`&".$row['name']."`0</a>");
                addnav("","bio.php?id=".$row['acctid']."&ret=".URLEncode($_SERVER['REQUEST_URI'])."");
            }
            output("</td><td align=\"center\">",true);
            output($row['sex']?"<img src=\"images/female.png\">":"<img src=\"images/male.png\">",true);
            output("</td><td>",true);
            output($row['loggedin']?"`@online`0":"`4offline",true);
            output("</td><td>",true);
            $rank=getprofession($row['profession']);
            output('`^'.$rank.'`0',true);
            output("</td>",true);
            if($session['user']['profession']==PROF_DDL_CAPTAIN || $session['user']['profession']==PROF_DDL_LIEUTENANT || su_check(SU_RIGHT_EXPEDITION_ADMIN)) {
                $c = false;
                $str_promotelink = '';
                $str_degradelink = '';
                $str_firelink = '';
                if($row['profession'] < PROF_DDL_CAPTAIN && ($session['user']['profession'] > $row['profession'] || su_check(SU_RIGHT_EXPEDITION_ADMIN))) {
                    $str_promotelink = "<a href='expedition.php?op=promote&id=".$row['acctid']."'>`@Befördern</a>";
                    addnav('','expedition.php?op=promote&id='.$row['acctid']);
                    $c = true;
                }
                if($row['profession'] > PROF_DDL_RECRUIT && ($session['user']['profession'] >= $row['profession'] || su_check(SU_RIGHT_EXPEDITION_ADMIN))) {
                    $str_degradelink = ($c ? " `0| " : "")."<a href='expedition.php?op=degrade&id=".$row['acctid']."'>`^Degradieren</a>";
                    addnav('','expedition.php?op=degrade&id='.$row['acctid']);
                }
                if($session['user']['profession'] == PROF_DDL_CAPTAIN || su_check(SU_RIGHT_EXPEDITION_ADMIN)) {
                    $str_firelink = "`n<a href='expedition.php?op=fire&id=".$row['acctid']."' onClick='return confirm(\"Mitglied wirklich aus der Hafenwacht entlassen?\")'>`\$Entlassen</a>";
                }
                output("<td>".$str_promotelink.$str_degradelink.$str_firelink."</td>");
                addnav('','expedition.php?op=fire&id='.$row['acctid']);
            }

        }
        output("</tr></table>`n`n`n",true);
        $str_css = ' style="line-height: 1.5; padding: 5px;"';
        output('`n`@Verfügbare Ränge:`n`n
                <table style="background-color: #aaff99; border: none;" cellpadding=1 cellspacing=1>
                <tr class="trdark"><td'.$str_css.' align="right"><img src="images/star.png" alt="star"><img src="images/star.png" alt="star"><img src="images/star.png" alt="star"></td><td'.$str_css.'>`uKapitän `i(Leitung der Hafenwacht)`i</td></tr>
                <tr class="trdark"><td'.$str_css.' align="right"><img src="images/star.png" alt="star"><img src="images/star.png" alt="star"></td><td'.$str_css.'>`2Leutnant `i(rechte Hand des Kapitäns)`i</td></tr>
                <tr class="trdark"><td'.$str_css.' align="right"><img src="images/star.png" alt="star"></td><td'.$str_css.'>`GMaat `i(Ausbilder/in der Gefreiten)`i</td></tr>
                <tr class="trdark"><td'.$str_css.' align="right">&nbsp;</td><td'.$str_css.'>`pGefreite/r `i(Teil der Crew)`i</td></tr>
                </table>`n`n`c');
        if(($session['user']['profession'] >= PROF_DDL_RECRUIT && $session['user']['profession'] <= PROF_DDL_CAPTAIN) || su_check(SU_RIGHT_EXPEDITION_ADMIN)) {
            addnav('Neue Mitglieder');
            addnav('Neues Mitglied vorschlagen','expedition.php?op=member_suggest');
            $row = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS ddl_count FROM accounts WHERE profession > 40 AND profession < 45"));
            if(($session['user']['profession'] == PROF_DDL_CAPTAIN || su_check(SU_RIGHT_EXPEDITION_ADMIN)) && $row['ddl_count'] < 16) {
                addnav('Mitglied aufnehmen','expedition.php?op=recruit');
            }
            addnav('Austritt');
            addnav('Amt niederlegen','expedition.php?op=quit');
        }
        addnav('Zurück');
        addnav("Zum Eingangsbereich","expedition.php");
        break;

    case 'member_suggest':
        page_header('Im Gemeinschaftsraum');
        require_once(LIB_PATH.'board.lib.php');

        if($_GET['board_action'] == 'add') {
            board_add('expi_guard');
        }
        output('`c`b`&Mögliche Kandidaten für die Hafenwacht`b`c`n');

        $int_del = (su_check(SU_RIGHT_EXPEDITION_ADMIN) ? 2 : 1);

        board_view('expi_guard',$int_del,'Folgende Botschaften wurden von der Hafenwacht-Leitung hier verkündet:','Es wurden noch keine Botschaften verkündet!',true,true);

        if($session['user']['profession'] == PROF_DDL_CAPTAIN || $int_del == 2) {
                output('`n`n`&Möchtest du etwas Wichtiges kundtun? Dann verfasse eine Nachricht und häng sie hier auf:');
                board_view_form('Vorschlagen!','');
        }
        output('`n`n');
        viewcommentary('expedition_msuggest','Über Vorschläge diskutieren',20,'meint');
        addnav('Zurück');
        addnav('Zur Mitglieder-Liste','expedition.php?op=ranks');
        break;

    case 'recruit':
        page_header('Rekrutieren');
        $str_act = (isset($_GET['act']) ? $_GET['act'] : '');
        if($str_act == 'search' && strlen($_POST['getuser']) > 2) {
            $sql = db_query("SELECT acctid,name FROM accounts WHERE profession = 0 AND acctid != ".$session['user']['acctid']." AND login LIKE '".str_create_search_string($_POST['getuser'])."' LIMIT 50");
            $row_count = db_num_rows($sql);
            output("`^Folgende Bürger wurden gefunden:`n`n");
            if($row_count == 0) {
                output("`i`7keinen Bürger gefunden`i");
            } else {
                output("<form action='expedition.php?op=recruit&act=confirm' method='post'>");
                for($i=0;$i<$row_count;$i++) {
                    $row = db_fetch_assoc($sql);
                    output("<input type='radio' name='id' value='".$row['acctid']."'> ".$row['name']."`0`n`n");
                }
                output("<input type='submit' class='button' value='Rekrutieren'></form>");
                addnav('','expedition.php?op=recruit&act=confirm');
            }
            addnav('Rekrutieren');
            addnav('Nochmal versuchen','expedition.php?op=recruit');
        } elseif($str_act == 'confirm') {
            $row = db_fetch_assoc(db_query("SELECT acctid,name FROM accounts WHERE acctid = ".$_POST['id']." LIMIT 1"));
            output("`2Du presst dein Siegel in das Wachs und gibst den Brief anschließend einem Rekruten mit.`nDamit ist `&".$row['name']." `2von nun an ein
                    offizielles Mitglied der Hafenwacht.");
            db_query("UPDATE accounts SET profession = 41,expedition = 1 WHERE acctid = ".$row['acctid']);
            systemmail($row['acctid'],'`@Aufnahme in die Hafenwacht!',$session['user']['name'].' `2hat dich heute offiziell in die Hafenwacht aufgenommen! Von
                                                                       nun an ist es deine Pflicht, den Frieden in deiner Stadt zu bewahren.');
        } else {
            output("`^Welchen Bürger Eranyas möchtest du für die Hafenwacht rekrutieren?`n`n
                    <form action='expedition.php?op=recruit&act=search' method='post'>
                    <input name='getuser' class='input'> <input type='submit' class='button' value='Suchen'>
                    </form>");
            addnav('','expedition.php?op=recruit&act=search');
        }
        addnav('Zurück');
        addnav('Zur Mitgliederliste','expedition.php?op=ranks');
        break;

    case 'promote':
        $row = db_fetch_assoc(db_query("SELECT acctid,profession FROM accounts WHERE acctid=".$_GET['id']." LIMIT 1"));
        $profession=$row['profession'];
        $profession++;
        $rank=getprofession($profession);
        $sql = "UPDATE accounts SET profession=".$profession." WHERE acctid = ".$row['acctid']."";
        db_query($sql) or die(sql_error($sql));
        systemmail($row['acctid'],"`@Hafenwacht: Beförderung!`0","`2{$session['user']['name']}`& hat dich zum {$rank} der Hafenwacht befördert!");
        redirect("expedition.php?op=ranks");
        break;

    case 'degrade':
        if($_GET['id'] == $session['user']['acctid']) {
            $session['user']['profession']--;
        } else {
            $row = db_fetch_assoc(db_query("SELECT acctid,profession FROM accounts WHERE acctid=".$_GET['id']." LIMIT 1"));
            $profession=$row['profession'];
            $profession--;
            $rank=getprofession($profession);
            $sql = "UPDATE accounts SET profession=".$profession." WHERE acctid = ".$row['acctid']."";
            db_query($sql) or die(sql_error($sql));
            systemmail($row['acctid'],"`^Hafenwacht: Degradierung!`0","`2{$session['user']['name']}`& hat dich zum {$rank} der Hafenwacht degradiert!");
        }
        redirect("expedition.php?op=ranks");
        break;

    case 'fire':
        if($_GET['id'] == $session['user']['acctid']) {
            $session['user']['profession'] = 0;
        } else {
            $sql = db_query("SELECT rppoints rpp FROM account_stats WHERE acctid = ".$_GET['id']);
            $row = db_fetch_assoc($sql);
            $str_reset_inv = ($row['rpp'] < 700 ? ", expedition = 0" : "");
            $sql = "UPDATE accounts SET profession=0".$str_reset_inv." WHERE acctid = ".$_GET['id']."";
            db_query($sql) or die(sql_error($sql));
            systemmail($_GET['id'],"`\$Hafenwacht: Entlassung!`0","`4{$session['user']['name']}`& hat dich aus der Hafenwacht entlassen!");
        }
        redirect("expedition.php");
        break;

    case 'quit':
        page_header('Austritt');
        if($_GET['what'] == 'confirm') {
            $session['user']['profession'] = 0;
            $sql = db_query("SELECT rppoints rpp FROM account_stats WHERE acctid = ".$session['user']['acctid']);
            $row = db_fetch_assoc($sql);
            if($row['rpp'] < 700) {
                $session['user']['expedition'] = 0;
            }
            addhistory('`2Aufgabe des Hafenwächteramts');
            addnews($session['user']['name']." `&ist seit dem heutigen Tage nicht mehr in der Hafenwacht!");
            output('`2Du gibt deine Uniform zurück und kehrst der Hafenwacht den Rücken.`n`n');
            addnav('Weiter');
            addnav('Zum Hafen','harbor.php');
            // Kapitän informieren
            $sql = db_query("SELECT acctid FROM accounts WHERE profession = ".PROF_DDL_CAPTAIN);
            if(db_num_rows($sql) > 0) {
                while($row = db_fetch_assoc($sql)) {
                    systemmail($row['acctid'],'`^Austritt aus der Hafenwacht','`@'.$session['user']['name'].' `2ist aus der Hafenwacht ausgetreten.');
                }
            }
        } else {
            output('`^Möchtest du wirklich aus der Hafenwacht austreten?');
            addnav('Wirklich austreten?');
            addnav('Ja, möchte ich','expedition.php?op=quit&what=confirm');
            addnav('Nein, doch nicht','expedition.php?op=ranks');
        }
        break;

    case 'myrank' :
        page_header('Im Gemeinschaftsraum');
        output('`2Du wendest dich einem der sich hier aufhaltenden Leutnants zu und fragst ihn, was er denn so von dir und deinem Rang hält.`n`n
                "`@Soso`2", brummt er,');

        switch ($session['user']['profession'])
        {
        case 41 :
            output('`2"`@Du bistn Gefreiter... Tut mir echt leid. Musst dich von jedem rumscheuchen lassen, hast überhaupt nichts zu sagen und musst für alle die
                    Drecksarbeit erledigen. Und dann ist da noch die Ausbildung... Oh je, wenn ich an meine Zeit zurückdenke, dann wird mir Angst und Bange.`n
                    Und jetzt geh zurück an die Arbeit und schrubbe die Schiffsdecks! Ich kontrolliere das gleich...`2"`n`n');
            break;
        case 42 :
            output('`2"`@Ein Maat, so, so. Du bist mit der Ausbildung der Gefreiten betraut, die kannst du
                    rumscheuchen, wie du es willst. Den Zivilisten hast du jedoch nichts zu sagen. Und wenn du mal nicht mit der Ausbildung beschäftigt bist,
                    dann nimmt man dich auch gern für unliebsame Wachschichten her. Oh, ich glaube da hinten stehen ein paar Gefreite rum und haben nichts zu
                    tun... Los, los, oder willst du das tolerieren?`2"`n`n');
            break;
        case 43 :
            output('`2"`@Du bist\'n Leutnant, so wie ich. Tja... Unser Job ist es, Fragen aller Art zu beantworten und zu schauen, ob die Maate unsre neuen
                    Gefreiten nicht rumgammeln lassen. Wir können Gefreiten, Maaten und Zivilisten Befehle erteilen. Soweit alles klar?`2"`n`n');
            break;
        case 44 :
            output('`2"`@Ahoi, Kapitän! Als Chef der Hafenwacht habt Ihr Befehlsgewalt über alle anderen Ränge
                    und die Zivilisten, und könnt bis hin zum Rang des Leutnants Beförderungen und Degradierungen durchführen. Allerdings solltet Ihr Euch gut
                    überlegen, wen Ihr zum Leutnant ernennt, schließlich braucht es auch immer ein paar Maate und Gefreite - wen soll man denn schließlich sonst
                    herumscheuchen, eh?`2"`n`n');
            break;
        default :
            output('`2"`@Du bist\'n Zivilist. Tolle Sache. Zwar kann dir außer jemand mit Rang Leutnant oder Kapitän keiner hier groß was befehlen, jedoch bist du
                    auch ein ziemlicher Außenseiter, was die Mitglieder hier betrifft. Pass bloss auf, dass man dich nicht zum Rekruten macht, denn dann haste
                    ausgelacht!`n`n');
            break;
        }
        addnav("Zurück","expedition.php?op=inn");
        break;

    case 'rproom':
        // Daten abrufen: Ortsname...
        $str_rproom_name = getsetting('expedition_rproom_name','');
        $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
        // .. und Ortsbeschreibung
        $str_rproom_desc = getsetting('expedition_rproom_desc','');
        $str_rproom_desc = (strlen($str_rproom_desc) > 14 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
        // end
        page_header(Strip_appoencode($str_rproom_name,3));
        output('`c`b'.$str_rproom_name.'`0`b`c`n'.$str_rproom_desc.'`n`n');
        viewcommentary('expedition_rproom','Sagen',15,'sagt');
        if($session['user']['profession'] == PROF_DDL_CAPTAIN || $session['user']['profession'] == PROF_DDL_LIEUTENANT || su_check(SU_RIGHT_EXPEDITION_ADMIN)) {
                addnav('Verwaltung');
                addnav('Ort verwalten','expedition.php?op=rproom_edit');
        }
        addnav('Zurück');
        addnav('Zum Eingangsbereich','expedition.php');
        break;

    case 'rproom_edit':
        // Daten abrufen: Ortsname...
        $str_rproom_name = getsetting('expedition_rproom_name','');
        $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
        // .. und Ortsbeschreibung
        $str_rproom_desc = getsetting('expedition_rproom_desc','');
        $str_rproom_desc = (strlen($str_rproom_desc) > 1 ? stripslashes($str_rproom_desc) : '`ikeine Beschreibung vorhanden`i');
        // end
        page_header(Strip_appoencode($str_rproom_name,3));
        if($_GET['act'] == 'save') {
                $bool_nochmal = false;
                // Ortsbeschreibung
                if($_POST['rproom_name'] != $str_rproom_name) {
                        if(strlen(trim($_POST['rproom_name'])) >= 2) {
                                $_POST['rproom_name'] = html_entity_decode(strip_appoencode(strip_tags($_POST['rproom_name']),2));
                                savesetting('expedition_rproom_name',$_POST['rproom_name']);
                        } else {
                                output('`4Fehler! Der Ortsname muss mindestens 2 Zeichen lang sein.`n`n');
                                $bool_nochmal = true;
                        }
                }
                // Ortsbeschreibung
                if($_POST['rproom_desc'] != $str_rproom_desc) {
                        if (strlen(trim($_POST['rproom_desc'])) >= 15) {
                                $_POST['rproom_desc'] = html_entity_decode(closetags(strip_tags($_POST['rproom_desc']),'`i`b`c'));
                                savesetting('expedition_rproom_desc',$_POST['rproom_desc']);
                        } else {
                                output('`4Fehler! Die Ortsbeschreibung muss mindestens 15 Zeichen lang sein.`n`n');
                                $bool_nochmal = true;
                        }
                }
                // Speichervorgang nicht erfolgreich?
                if($bool_nochmal) {
                        addnav('Nochmal','expedition.php?op=rproom_edit');
                        addnav('Zurück');
                } else {
                        savesetting('expedition_rproom_active',$_POST['rproom_active']);
                        redirect('expedition.php?op=rproom');
                }
        } else {
                $form = array('rproom_active'=>'RP-Ort für die Öffentlichkeit zugänglich?,bool'
                             ,'rproom_name'=>'Name des RP-Orts:'
                             ,'rproom_name_prev'=>'Vorschau:,preview,rproom_name'
                             ,'rproom_desc'=>'Ortsbeschreibung:,textarea,45,8'
                             ,'rproom_desc_prev'=>'Vorschau:,preview,rproom_desc');
                $data = array('rproom_active'=>getsetting('expedition_rproom_active',0)
                             ,'rproom_name'=>$str_rproom_name
                             ,'rproom_desc'=>$str_rproom_desc);
                output("<form action='expedition.php?op=rproom_edit&act=save' method='POST'>",true);
                showform($form,$data,false,'Speichern');
                output("</form>",true);
                addnav('','expedition.php?op=rproom_edit&act=save');
                addnav('Zurück');
                addnav('Zum RP-Ort','expedition.php?op=rproom');
        }
        addnav('Zum Eingangsbereich','expedition.php');
        break;

    default :
        page_header('Das Quartier der Hafenwacht');
        output("`c`&".$profs[PROF_DDL_CAPTAIN][4]." `bDas Quartier der Hafenwacht`b`c`n
                `2Direkt angrenzend an die Piers befindet sich das Quartier der Hafenwacht. Im ersten Moment könnte man es mit einem herkömmlichen
                - und sichtbar herabgekommenen - Einfamilienhaus
                verwechseln, wären da nicht zwei Fahnen links und rechts über der Flügeltür angebracht, beide grün eingefärbt und weiß mit einem weißen
                Schiff hinter zwei gekreuzten Schwertern bestickt. Eine der Türen steht leicht offen, was du kurzerhand als Einladung auffasst und über die
                Schwelle in die Eingangshalle trittst. Sofort fällt dir der Empfangsbereich zu deiner Linken ins Auge; doch auch Türen im hinteren rechten
                Bereich kannst du erblicken, ");
        if($session['user']['expedition'] == 1 || su_check(SU_RIGHT_DEBUG)) {
                addnav('Aktionen');
                addnav('Erkundung','expedition.php?op=explore');
                addnav('Schatzsuche','expedition.php?op=search');
                addnav('Küste auskundschaften','expedition.php?op=claim');
        }
        addnav('Hafenwacht');
        if(($session['user']['profession'] >= PROF_DDL_RECRUIT && $session['user']['profession'] <= PROF_DDL_CAPTAIN) || su_check(SU_RIGHT_DEBUG)) {
                output("die, wie du weißt, zum Gemeinschaftsraum und den Räumlichkeiten der Hafenwacht-Leitung führen.`n");
                addnav('Büro der Leitung','expedition.php?op=chief');
                addnav('Gemeinschaftsraum','expedition.php?op=inn');
        } else {
                output("welche allerdings allesamt verschlossen sind - dort hast du zweifelsohne als Zivilist keinen Zutritt.`n");
        }
        output('Langsam läufst du auf den Empfangsbereich zu - und kommst nicht umhin festzustellen, dass trotz der üppigen Einrichtung sämtliche Möbel
                sichtbar abgenutzt wirken. Auch die Holzdielen zu deinen Füßen sind schon ganz stumpf und grau und weisen Laufstraßen auf. Einzig die große,
                eiserne Tafel, die hinter dem Empfang an die Wand geschlagen worden ist, glänzt und funkelt, als wäre sie gerade erst aufgehängt worden.
                Sie verkündet den Kodex der Hafenwacht:`n`n
                `c`g"Ehrenhaft im Verhalten, tapfer im Kampf, gerecht und gütig, jedoch gnadenlos gegenüber allen Feinden der Stadt!`n
                Möge ein jedes Mitglied der Hafenwacht sich um Frieden und Sicherheit bemühen, auf dass es den Bürgern Eranyas wohl ergehe!"`c`n`n');
        addnav('Mitglieder-Liste','expedition.php?op=ranks');
        addnav('Sonstiges');
        if(getsetting('expedition_rproom_active',0) == 1 || $session['user']['profession'] == PROF_DDL_CAPTAIN || $session['user']['profession'] == PROF_DDL_LIEUTENANT || su_check(SU_RIGHT_EXPEDITION_ADMIN)) {
                $str_rproom_name = getsetting('expedition_rproom_name','');
                $str_rproom_name = (strlen($str_rproom_name) > 1 ? stripslashes($str_rproom_name) : 'Ort ohne Namen');
                $str_rproom_active = (getsetting('expedition_rproom_active',0) == 1 ? '' : ' (gesperrt)');
                addnav($str_rproom_name.$str_rproom_active,'expedition.php?op=rproom',false,false,false,false);
        }
        addnav('Beschreibung aller Berufsgruppen','library.php?op=book&bookid=51');
        addnav('Zurück');
        addnav('Zum Hafen','harbor.php');
        viewcommentary('expedition_main','Mitreden',25);
        break;
}

page_footer();
?>

