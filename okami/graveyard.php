
<?php

// 15082004
// 09.04.2006 Ahnenschreib-Mod by Maris (Maraxxus [-[at]-] gmx.de)

require_once 'common.php';
require_once(LIB_PATH.'disciples.lib.php');

define(RP_RESURRECTION_COST, 25);

function healnav($favortoheal)
{

    addnav('Seele wiederherstellen');
    addnav('s?Vollständig ('.$favortoheal.' Gefallen)','graveyard.php?op=restore&amount=100');
    addnav('7?Zu 75% ('.ceil($favortoheal*0.75).' Gefallen)','graveyard.php?op=restore&amount=75');
    addnav('5?Zu 50% ('.ceil($favortoheal*0.5).' Gefallen)','graveyard.php?op=restore&amount=50');
    addnav('2?Zu 25% ('.ceil($favortoheal*0.25).' Gefallen)','graveyard.php?op=restore&amount=25');
}

function gravenav()
{
    global $session, $access_control;
    if ($session['user']['gravefights']>0)
    {
        addnav('Gefallen erwerben');
        addnav('Etwas zum Quälen suchen','graveyard.php?op=search');
    }
    addnav('Besondere Orte');
    addnav('M?Mausoleum betreten','graveyard.php?op=enter');
    addnav('A?Zum Ahnenschrein','graveyard.php?op=shrine');
    if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
    {
        addnav('Geistschrein(SU)','spiritshrine.php?su=1',false,false,false,false);
        //addnav('Styx(SU)','styx.php',false,false,false,false);
    }
    //addnav('Kriegerliste','list.php');
    addnav('Zurück');
    addnav('Zu den Schatten','shades.php');
}

if ($session['user']['alive'])
{
    redirect('village.php');
}

page_header('Der Friedhof');
checkday();

music_set('unterwelt');

$str_output = '';

$buffsave=$session['bufflist'];
$session['bufflist']=array();
if(isset($buffsave['decbuff']) && ($buffsave['decbuff']['state'] == 20 || $buffsave['decbuff']['state'] == 21) && $buffsave['decbuff']['rounds'] > 0) {    // untoter+besessener Knappe
    $session['bufflist']['decbuff']=$buffsave['decbuff'];
}
if(isset($buffsave['headache'])) {    // Malus-Buff vom Totsaufen
    $session['bufflist']['headache']=$buffsave['headache'];
}

$session['user']['drunkenness'] = 0;
$max = $session['user']['level'] * 5 + 50;
$favortoheal = round(10 * ($max-$session['user']['soulpoints'])/$max);

if ($_GET['op']=='')
{
    if($session['user']['gravefights']>0)
    {
        spc_get_special('graveyard',50,'',array('op'));
    }

    $str_output .= '`c`b`)Der Friedhof`0`b`c
    `n`)Dein Geist wandert auf einen einsamen mit Unkraut überwucherten Friedhof. Die Pflanzen scheinen nach deinem Geist im Vorbeischweben zu greifen.
    Du bist umgeben von den Überresten alter Grabsteine. Einige liegen auf dem Gesicht, andere sind in Stücke zerbrochen. Fast kannst du das Wehklagen
    der hier gefangenen Seelen hören.
    `n`nMitten auf dem Friedhof steht ein altertümliches Mausoleum, dem die Spuren ungezählter Jahre deutlich anzusehen sind.
    Ein böse schauender Steingargoyle ziert die Dachspitze; seine Augen scheinen dir zu folgen und sein aufklaffender Mund ist gespickt mit scharfen Steinzähnen.
    Auf der Gedenktafel über der Tür ist zu lesen: `$Ramius, Herr über den Tod`).';
    gravenav();
}

elseif ($_GET['op']=='search')
{
    if ($session['user']['gravefights']<=0)
    {
        $str_output .= '`b`$Deine Seele kann keine weiteren Qualen in diesem Nachleben mehr ertragen.`0`b';
        gravenav();
    }
    else
    {
        $session['user']['gravefights']--;
        $battle=true;
        $sql = 'SELECT * FROM creatures WHERE location=1 ORDER BY rand('.e_rand().') LIMIT 1';
        $result = db_query($sql);
        $badguy = db_fetch_assoc($result);
        $level = $session['user']['level'];
        $shift = 0;
        if ($level < 5)
        {
            $shift = -1;
        }
        $badguy['creatureattack'] = 9 + $shift + (int)(($level-1) * 1.5);
        // Make graveyard creatures easier.
        $badguy['creaturedefense'] = (int)((9 + $shift + (($level-1) * 1.5)) *0.7);
        $badguy['creaturehealth'] = $level * 5 + 50;
        $badguy['creatureexp'] = e_rand(10 + round($level/3),20 + round($level/3));
        $badguy['creaturelevel'] = $level;

        $session['user']['badguy']=createstring($badguy);
    }
}

elseif ($_GET['op']=='fight' || $_GET['op']=='run')
{
    if ($_GET['op']=='run')
    {
        if (e_rand(0,2)==1)
        {
            $str_output .= '`$Ramius`) verflucht dich für deine Feigheit.`n`n';
            $favor = 5 + e_rand(0, $session['user']['level']);
            if ($favor > $session['user']['deathpower'])
            {
                $favor = $session['user']['deathpower'];
            }
            if ($favor > 0)
            {
                $str_output .= '`)Du hast `^'.$favor.'`) Gefallen bei `$Ramius VERLOREN`).';
                $session['user']['deathpower']-=$favor;
            }
            addnav('Zurück zum Friedhof','graveyard.php');
            $session['user']['reputation']--;
        }
        else
        {
            $str_output .= '`)Als du zu fliehen versuchst, wirst du zum Kampf zurückberufen!`n`n';
            $battle=true;
        }
    }
    else
    {
        $battle = true;
    }
}

else if ($_GET['op']=='shrine')
{
    $rowe = user_get_aei('dpower');
    $dpower = $rowe['dpower'];
    if($dpower>30000) //Erwecken am seltsamen Felsen ist fehlgeschlagen
    {
        $session['user']['lasthit']=date('Y-m-d H:i:s',strtotime(date('r').'-'.(86500/getsetting('daysperday',4)).' seconds'));
        $session['user']['alive']=1;
        user_set_aei(array('dpower' => 0));        
        checkday();
    }
    $str_output .= '`c`b`)Der Ahnenschrein`0`b`c
    `n`&Du begibst dich zum Ahnenschrein, in der Hoffnung, dass einer deiner Hinterbliebenen deine Seele mit einem Gebet bedacht hat.`n';
    if ($dpower > 0)
    {
        $str_output .= '`&Und tatsächlich bemerkst du, dass man dir insgesamt `^'.$dpower.'`& Gefallen überlassen hat.';
        addnav('Gefallen abholen');
        addnav('M?Meditieren','graveyard.php?op=shrine_normal');
        addnav('Risiko');
        addnav('D?Doppelt oder nichts','graveyard.php?op=shrine_risk');
    }
    else
    {
        $str_output .= '`&Doch enttäuscht musst du feststellen, dass dem nicht so ist.';
    }
    if($session['user']['pqtemp']=='1000 weiße Lilien')
    {
        $str_output .= '`n`nDir fällt auf dass der Schrein völlig mit `Tverwelkten`& Lilien bedeckt ist.';
        addnav('Sonstiges');
        addnav('Blumen untersuchen','graveyard.php?op=lilies');
    }
    addnav('Zurück');
    addnav('F?Zum Friedhof','graveyard.php');

}

else if ($_GET['op']=='shrine_normal')
{
    $rowe = user_get_aei('dpower');
    $dpower = $rowe['dpower'];
    if($dpower>30000) //Erwecken am seltsamen Felsen ist fehlgeschlagen
    {
        $session['user']['lasthit']=date('Y-m-d H:i:s',strtotime(date('r').'-'.(86500/getsetting('daysperday',4)).' seconds'));
        $session['user']['alive']=1;
        checkday();
    }
    $str_output .= '`c`b`)Der Ahnenschrein`0`b`c
    `n`&Du kniest dich vor den Schrein und empfängst die `^'.$dpower.'`& Gefallen, die man dir zukommen ließ.';
    $session['user']['deathpower']+=$dpower;
    user_set_aei(array('dpower' => 0));
    addnav('Zurück');
    addnav('F?Zum Friedhof','graveyard.php');

}

else if ($_GET['op']=='shrine_risk')
{
    $rowe = user_get_aei('dpower');
    $dpower = $rowe['dpower'];
    if($dpower>30000)
    {
        $session['user']['lasthit']=date('Y-m-d H:i:s',strtotime(date('r').'-'.(86500/getsetting('daysperday',4)).' seconds'));
        $session['user']['alive']=1;
        checkday();
    }
    $str_output .= '`c`b`)Der Ahnenschrein`0`b`c
    `n`&Du kniest dich vor den Schrein und meditierst. Da du ein Spieler bist, gehst du auf volles Risiko und versuchst dir mehr Gefallen anzueignen, als die eigentlich zustehen.`n';
    if (e_rand(1,10)>5)
    {
        $dpower=$dpower*2;
        user_set_aei(array('dpower' => 0));
        $str_output .= '`&Dies gelingt dir auch ganz gut. Du kannst `^'.$dpower.'`& Gefallen abstauben!';
        $session['user']['deathpower']+=$dpower;
    }
    else
    {
        $str_output .= '`&Doch Ramius, der sich nur ungern hinters Licht führen lässt, schaut dir schon eine ganze Weile über die Schulter und findet es gar nicht gut, was du da versuchst.
        `nZur Strafe nimmt er dir sowohl die Gefallen die du bei ihm gut hast, wie auch jene, die auf dem Ahnenstein warten und verdammt dich dazu, sein Mausoleum von Grund auf zu reinigen.';
        user_set_aei(array('dpower' => 0));
        $session['user']['deathpower']=0;
        addnews('`&'.$session['user']['name'].'`& wurde von Ramius dazu verdammt, sein Mausoleum gründlichst zu reinigen.');
        debuglog('verlor alle Gefallen am Ahnenschrein');
    }
    addnav('Zurück');
    addnav('F?Zum Friedhof','graveyard.php');

}

else if ($_GET['op']=='lotto')
{
    $jp=getsetting('deathjackpot','200');
    $str_output .= '`c`b`)Tot-o-Lotto`0`b`c
    `n`c`&Im Jackpot von `^Tot-o-Lotto`& befinden sich gerade `^'.$jp.'`& Gefallen!`0`c
    `n`n`&Du schleichst zu Ramius und bittest ihn um ein Los.`n';
    if ($session['user']['deathpower']<10)
    {
        $str_output .= '`&Doch dieser lacht dich nur spöttisch aus, da du mindestens `^10 Gefallen`& brauchst, um mitzuspielen.';
    }
    else
    {
        $str_output .= '`&Dieser teilt dir knapp mit, dass dich dies `^deine gesamten Gefallen`&, die du bei ihm gut hast, kosten würde.
        `nWillst du immer noch mitspielen?';
        addnav('Mitspielen');
        addnav('JA','graveyard.php?op=lotto2');
    }
    addnav('Zurück');
    addnav('M?Zum Mausoleum','graveyard.php?op=enter');

}

else if ($_GET['op']=='lotto2')
{
    $str_output .= '`c`b`)Tot-o-Lotto`0`b`c`n';
    if ($session['user']['deathpower']>=10)
    {
        $jp=getsetting('deathjackpot','200');
        $dpsave=floor($session['user']['deathpower']*0.5);
        $session['user']['deathpower']=0;
        $win=e_rand(1,500);
        if ($win>=492)
        {
            $str_output .= '`^JACKPOT!`n'.$jp.'`& Gefallen sind dein!';
            $session['user']['deathpower']+=$jp;
            savesetting('deathjackpot','200');
            addnews('`&Lauter Jubel war heute aus dem Totenreich zu hören, als `^'.$session['user']['name'].'`& den Jackpot im Tot-o-Lotto knackte und `^'.$jp.'`& Gefallen gewann.');
        }
        else
        {
            $str_output .= '`&Du ziehst eine Niete.`nDas war wohl nichts, du hast all deine Gefallen bei Ramius verloren.';
            debuglog('verlor alle Gefallen beim Tot-o-Lotto');

            $jackpot=getsetting('deathjackpot','200');
            $jpmax=getsetting('deathjackpotmax','10000');
            if ($jackpot+$dpsave<=$jpmax)
            {
                savesetting('deathjackpot',getsetting('deathjackpot','200')+$dpsave);
            }
            else
            {
                savesetting('deathjackpot',$jpmax);
            }
        }
    }
    else // refresh ?
    {
        $str_output .= '`&Hier stimmt was nicht...';
    }
    addnav('Zurück');
    addnav('M?Zum Mausoleum','graveyard.php?op=enter');

}

else if ($_GET['op']=='enter')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c
    `n`)Du betrittst das Mausoleum und siehst dich in einer kalten, kahlen Kammer aus Marmor. Die Luft um dich herum trägt die Kälte des Todes selbst.
    Aus der Dunkelheit starren zwei schwarze Augen direkt in deine Seele. Ein feuchtkalter Griff scheint deine Seele zu umklammern und sie mit den Worten des Todesgottes `$Ramius`) höchstpersönlich zu erfüllen.`n`n
    "`0Dein sterblicher Körper hat dich im Stich gelassen. Und jetzt wendest du dich an mich. Es gibt in diesem Land diejenigen, die sich meinem Griff entziehen konnten und ein Leben über das Leben hinaus besitzen. Um mir deinen Wert für mich zu beweisen
    und dir Gefallen zu verdienen, gehe raus und quäle deren Seelen. Solltest du mir genug Gefallen getan haben, werde ich dich belohnen.`)"';
    if ($session['user']['marks']>=31)
    {
        $str_output .= '`n`&Ramius nickt dir wohlwollend zu, erkennend dass du zu den Auserwählten gehörst.';
    }

    if (item_count(' (i.tpl_id="drstb") AND owner='.$session['user']['acctid']) >= 1 )
    {

        $sql = 'SELECT a.name FROM items LEFT JOIN accounts a ON owner=acctid WHERE tpl_id="drrel_ksn"';
        $res = db_query($sql);
        $int_count = db_num_rows($res);

        if (0 == $int_count)
        {
            // Noch keiner hat die Reliquie
            // value1 enthält Preis
            $arr_item = item_get_tpl(' tpl_id="drrel_ksn" ');

            if ($_GET['act'] == 'buy_rel')
            {
                $session['user']['deathpower'] -= $arr_item['tpl_value1'];
                debuglog('gab '.$arr_item['tpl_value1'].' Gefallen für Drachenreliquie');

                $arr_item['tpl_value1'] = time();

                item_add($session['user']['acctid'],0,$arr_item);
                item_delete(' (tpl_id="drstb") AND owner='.$session['user']['acctid']);

                addnews('`!Soeben wurde '.$session['user']['name'].'`!\'s Geist dabei beobachtet, wie er Ramius eine Drachenreliquie abschwatzte!');
                $sql = 'UPDATE account_extra_info SET treasure_f=treasure_f+1 WHERE acctid='.$session['user']['acctid'];
                db_query($sql);

                $str_output .= '`n`n`0Urplötzlich hältst du ein eher ziemlich zerrissenes und verfranstes Ding in der Hand, das dich mit einem starken Schwefelgeruch betäubt.
                Doch trotz ihres schlechten Zustands kannst du immer noch die magische Kraft der Drachenschuppe spüren!
                Schnell packst du sie weg, um sie nicht zu beschädigen.';
            }
            else
            {

                $str_output .= '`n`n`0Auf deine zaghafte Nachfrage, wo denn nun die Drachenreliquie sei, antwortet Ramius mit schallendem Lachen, das dir ein kaltes Schaudern über den Rücken jagt:`n
                "Ihr Sterblichen seid verrückt.. Ich frage mich, was diese Spinner mit den hässlichen Dingern anfangen wollen. Nun.. du kannst es haben - für `b'.$arr_item['tpl_value1'].'`b Gefallen!';

                if ($session['user']['deathpower'] >= $arr_item['tpl_value1'])
                {
                    addnav($arr_item['tpl_name'].' ('.$arr_item['tpl_value1'].' Gefallen)','graveyard.php?op=enter&act=buy_rel');
                }
            }
        }
        // END noch keiner hat Rel
        else
        {

            $arr_owner = db_fetch_assoc($res);

            $str_output .= '`n`n`0Fast höhnisch raunen dir die verlorenen Seelen zu, dass sich '.$arr_owner['name'].'`0 noch
            vor dir die Drachenreliquie unter den Nagel gerissen hat.';
        }
    }

    addnav('Frage Ramius nach dem Wert deiner Seele','graveyard.php?op=question');
    if($session['user']['dragonkills']==0 && $session['user']['deathpower']<100) addnav('C?Neue Chance','graveyard.php?op=free_resurrect');
    healnav($favortoheal);
    addnav('Tot-o-Lotto');
    addnav('Spielen','graveyard.php?op=lotto');
    addnav('Zurück');
    addnav('F?Zum Friedhof','graveyard.php');

}

else if ($_GET['op']=='restore')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c`n';

    $int_amount = max($_GET['amount'],25) / 100;

    $favortoheal = ceil($favortoheal * $int_amount);

    if ($session['user']['soulpoints']<$max)
    {
        if ($session['user']['deathpower']>=$favortoheal)
        {
            $str_output .= '`$Ramius`) nennt dich einen Schwächling, weil du nach Wiederherstellung deiner Seele fragst. Aber da du genug Gefallen bei ihm gut hast, gibt er deiner Bitte zum Preis von `4'.$favortoheal.'`) Gefallen nach.';
            $session['user']['deathpower']-=$favortoheal;

            $diff = round(($max-$session['user']['soulpoints'])*$int_amount, 0 );

            $session['user']['soulpoints'] += $diff;
        }
        else
        {
            $str_output .= '`$Ramius`) verflucht dich und wirft dich aus dem Mausoleum. Du mußt ihm erst genug Gefallen getan haben, bevor er dir die Wiederherstellung deiner Seele gewährt.';
        }
    }
    else
    {
        $str_output .= '`$Ramius`) seufzt und murmelt etwas von "`7Nur weil sie tot sind, heißt das doch nicht, dass sie nicht zu denken brauchen, oder?`)"`n`n';
        $str_output .= 'Vielleicht solltest du erstmal eine Wiederherstellung `inötig`i haben, bevor du danach fragst.';
    }
    addnav('Frage Ramius nach dem Wert deiner Seele','graveyard.php?op=question');

    addnav('Zurück zum Friedhof','graveyard.php');
}

else if ($_GET['op']=='question')
{
    // Preise festlegen
    if ($session['user']['marks']>=31)
    {
        $rcost=80;
        $hcost=20;
    }
    else
    {
        $rcost=100;
        $hcost=25;
    }

    // Meldung ausgeben
    $str_output .= '`c`b`)Das Mausoleum`0`b`c`n';
    if ($session['user']['deathpower']>=$rcost)
    {
        $str_output .= '`$Ramius`) spricht: "`7Du hast mich tatsächlich beeindruckt. Ich sollte dir die Möglichkeit gewähren, deine Feinde in der Welt der Sterblichen zu besuchen.`)"';

        if ($session['user']['reputation']<=-10)
        {
            $str_output .= ' Er weist dich noch darauf hin, dass er keinen Einfluss auf das Gedächtnis der Lebenden - und besonders der Händler -  hat.';
        }
        if ($session['user']['reputation']<=-40)
        {
            $str_output .= '`n`n"`7Wegen der Unehrenhaftigkeit deines Lebens kann ich dir nicht erlauben, vorzeitig zu den Lebenden zurückzukehren, obwohl du mir gute Dienste geleistet hast.`)"';
        }
        addnav('Ramius Gefallen');
    }
    else if($session['user']['deathpower']>=$hcost) {
        $str_output .= '`$Ramius`) spricht: "`7Ich bin nicht wirklich beeindruckt von deinen Bemühungen, aber einen kleinen Gefallen werde ich dir gewähren. Führe meine Arbeit fort und ich kann dir vielleicht mehr meiner Kraft anbieten.`)"';
        addnav('Ramius Gefallen');
    }
    else {
        $str_output .= '`$Ramius`) spricht: "`7Ich bin von deinen Bemühungen noch nicht beeindruckt. Führe meine Arbeit fort und wir können weiter reden.`)"';
        if (!$session['user']['prefs']['nosounds'])
        {
            $str_output .= '<embed src="media/lachen.wav" width=10 height=10 autostart=true loop=false hidden=true volume=100>';
        }
    }

    if ($session['user']['deathpower']>=$rcost)
    {

        if ($session['user']['reputation']>-40)
        {
            addnav('e?Wiedererwecken ('.$rcost.' Gefallen)','newday.php?resurrection=true');
        }

    }
    // RP - Wiedererweckung
    if ($session['user']['deathpower'] >= RP_RESURRECTION_COST)
    {
        addnav('Besuch der Oberwelt ('.RP_RESURRECTION_COST.' Gefallen)','graveyard.php?op=rp_resurrect');
    }
    if ($session['user']['deathpower']>=100)
    {
        addnav('5 Donationpoints (100 Gefallen)','graveyard.php?op=dona');
    }
    if ($session['user']['deathpower']>=$hcost)
    {
        addnav('h?Feind heimsuchen ('.$hcost.' Gefallen)','graveyard.php?op=haunt');
    }

    addnav('Sonstiges');

    $str_output .= '`n`nDu hast `6'.$session['user']['deathpower'].'`) Gefallen bei `$Ramius`).';
    addnav('Frage Ramius nach dem Wert deiner Seele','graveyard.php?op=question');
    healnav($favortoheal);
    addnav('Tot-o-Lotto');
    addnav('Spielen','graveyard.php?op=lotto');
    addnav('Zurück');
    addnav('F?Zum Friedhof','graveyard.php');
}

else if ($_GET['op']=='dona')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c
    `n`$Ramius`)\' Gelächter lässt den Boden erbeben. "`7Du verzichtest für ein paar Punkte auf das Leben? Bitte, soll mir nur Recht sein.`)" Mit diesen Worten gibt er deiner Bitte nach.`nDu bekommst 5 Donationpoints.';
    $session['user']['deathpower']-=100;
    $session['user']['donation']+=5;
    addnav('Zurück zum Mausoleum','graveyard.php?op=enter');
    addnav('Zurück zum Friedhof','graveyard.php');
}

else if ($_GET['op']=='haunt')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c
    `n`$Ramius`)  ist von deinen Aktionen beeindruckt und gewährt dir die Macht, einen Feind heimzusuchen.`n`n`0';
    $str_output .= '<form action="graveyard.php?op=haunt2" method="POST">';
    addnav('','graveyard.php?op=haunt2');
    $str_output .= 'Wen willst du heimsuchen? <input name="name" id="name"> <input type="submit" class="button" value="Suchen">';
    $str_output .= '</form>';
    $str_output .= focus_form_element('name');
    addnav('Zurück zum Mausoleum','graveyard.php?op=enter');
}

else if ($_GET['op']=='haunt2')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c`n';
    $string = str_create_search_string($_POST['name']);

    $sql = 'SELECT acctid,name,level
        FROM accounts
        WHERE name LIKE "'.$string.'"
        AND locked=0
        ORDER BY login="'.db_real_escape_string($_POST['name']).'" DESC, level,login';
    $result = db_query($sql);
    if (db_num_rows($result)<=0)
    {
        $str_output .= '`$Ramius`)  kann niemanden mit einem solchen Namen finden.';
    }
    else if (db_num_rows($result)>100)
    {
        $str_output .= '`$Ramius`) denkt, du solltest die Zahl derer, die du heimsuchen willst, etwas einschränken.';
        $str_output .= '<form action="graveyard.php?op=haunt2" method="POST">';
        addnav('','graveyard.php?op=haunt2');
        $str_output .= 'Wen willst du heimsuchen? <input name="name" id="name"> <input type="submit" class="button" value="Suchen">';
        $str_output .= '</form>';
        $str_output .= '<script language="JavaScript">document.getElementById("name").focus()</script>';
    }
    else
    {
        $str_output .= '`$Ramius`) wird dir gestatten, eine der folgenden Personen heimzusuchen:
        `n`n`0<table cellpadding="3" cellspacing="0" border="0">
        <tr class="trhead">
        <th>Name</th>
        <th>Level</th>
        </tr>';
        $int_count = db_num_rows($result);
        for ($i=0; $i<$int_count; $i++)
        {
            $row = db_fetch_assoc($result);
            $str_output .= '<tr class="'.($i%2?'trlight':'trdark').'">
            <td>'.create_lnk($row['name'],'graveyard.php?op=haunt3&who='.($row['acctid'])).'</td>
            <td>'.$row['level'].'</td>
            </tr>';
        }
        $str_output .= '</table>';
    }
    addnav('Frage Ramius nach dem Wert deiner Seele','graveyard.php?op=question');
    healnav($favortoheal);
    addnav('Zurück');
    addnav('M?Zum Mausoleum','graveyard.php?op=enter');
}

else if ($_GET['op']=='haunt3')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c`n`)';

    $sql = 'SELECT name,level,hauntedby,accounts.acctid
        FROM accounts
        LEFT JOIN account_extra_info USING(acctid)
        WHERE acctid="'.$_GET['who'].'"';
    $result = db_query($sql);

    if (db_num_rows($result)>0)
    {
        $row = db_fetch_assoc($result);
        if ($row['hauntedby']!='')
        {
            $str_output .= 'Diese Person wurde bereits heimgesucht. Wähle eine andere!';
        }
        else
        {
            $session['user']['deathpower']-=20;
            $roll1 = e_rand(0,$row['level']);
            $roll2 = e_rand(0,$session['user']['level']);
            if ($roll2>$roll1)
            {
                $str_output .= 'Du hast `7'.$row['name'].'`) erfolgreich heimgesucht!';

                user_set_aei(array('hauntedby'=>addslashes($session['user']['name'])) , $row['acctid'] );

                addnews('`7'.$session['user']['name'].'`) hat `7'.$row['name'].'`) heimgesucht!');
                $session['user']['donation']+=1;
                systemmail($row['acctid'],'`)Du wurdest heimgesucht','`)Du wurdest von '.$session['user']['name'].' heimgesucht.');
            }
            else
            {
                addnews('`7'.$session['user']['name'].'`) hat erfolglos versucht, `7'.$row['name'].'`) heimzusuchen!');
                switch (e_rand(0,5))
                {
                case 0:
                    $str_output .= 'Gerade als du `7'.$row['name'].'`) heimsuchen wolltest, versaut dir ein Niesen komplett den Erfolg.';
                    break;
                case 1:
                    $str_output .= 'Die Heimsuchung von `7'.$row['name'].'`) läuft richtig gut. Leider schläft dein Opfer tief und fest und bekommt von deiner Anwesenheit absolut nichts mit.';
                    break;
                case 2:
                    $str_output .= 'Du machst dich zur Heimsuchung von `7'.$row['name'].'`) bereit, stolperst aber über deinen Geisterschwanz und landest flach auf deinem .... ähm ... Gesicht.';
                    break;
                case 3:
                    $str_output .= 'Du willst `7'.$row['name'].'`) im Schlaf heimsuchen, doch dein Opfer dreht sich nur im Bett um und murmelt etwas von "nie wieder Würstchen so kurz vor dem Schlafengehen".';
                    break;
                case 4:
                    $str_output .= 'Du weckst `7'.$row['name'].'`) auf. Dein Opfer schaut dich kurz an, sagt "Niedlich!" und versucht dich in einem Einmachglas einzufangen.';
                    break;
                case 5:
                    $str_output .= 'Du versuchst `7'.$row['name'].'`) zu erschrecken, siehst dich dabei im Augenwinkel selbst im Spiegel und gerätst in Panik, weil du einen Geist gesehen hast!';
                    break;
                }
            }
        }
    }
    else
    {
        $str_output .= '`$Ramius`) kann sich nicht mehr auf diese Person konzentrieren. Du kannst sie jetzt nicht heimsuchen.';
    }
    addnav('Frage Ramius nach dem Wert deiner Seele','graveyard.php?op=question');

    healnav($favortoheal);

    addnav('Zurück');
    addnav('M?Zum Mausoleum','graveyard.php?op=enter');
}
// RP - Wiedererweckung by talion
elseif ($_GET['op'] == 'rp_resurrect')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c`n';
    if ($_GET['act'] == 'ok')
    {
        $session['user']['spirits'] = RP_RESURRECTION;

        user_set_aei(array('witch'=>999, 'seenacademy'=>1, 'goldin'=>1234567, 'goldout'=>1234567,
        'gemsin'=>1234567, 'gemsout'=>1234567, 'fishturn'=>0, 'dollturns'=>0, 'seenbard'=>1, 'usedouthouse'=>1,
        'gotfreeale'=>999) );

        $session['user']['hitpoints'] = 1;
        $session['user']['alive'] = true;

        $session['user']['deathpower'] -= RP_RESURRECTION_COST;

        addnews($session['user']['name'].'`& hat '.(!$session['user']['sex'] ? 'seinem' : 'ihrem').' Körper einen Ausflug in die Welt der Lebenden erkauft!');

        $str_output .= '`7Auf ein verknöchert klingendes Fingerschnipsen von Ramius hin öffnet sich genau vor dir ein hell leuchtendes Tor zur Oberwelt.
                Ohne weiter zu zögern durchschreitest du die Pforte..';

        addnav('Zu den Lebenden!','friedhof.php');
    }
    else
    {

        addnav('Zurück zu den Toten','graveyard.php');

        $str_output .= '`7Ramius offeriert dir in herablassendem Ton die Option, trotz deines körperlichen Todes unter den Lebenden zu wandeln.
                Dein geschwächter, halbtoter Körper wäre in seinen Möglichkeiten natürlich stark eingeschränkt und könnte bei so mancher
                Gelegenheit anders als gewohnt reagieren: Einzig deine geistigen Fähigkeiten
                - der Gott der Unterwelt lächelt mit Eiseskälte - stünden dir noch voll zur Verfügung.`n`n
                Für diese Prozedur verlangt Ramius `b'.RP_RESURRECTION_COST.'`b Gefallen. Willst du sie vollführen?`n`n
                [Hinweis: Diese Wiederweckung dient einzig und allein dem Rollenspiel! Waldkämpfe o.ä. sind damit NICHT durchführbar!]`n`n
                ';
        $str_output .= create_lnk('Ja, lass mich hinauf!','graveyard.php?op=rp_resurrect&act=ok', true, true);

    }

}
// Wiedererweckung durch Waldspecial whitelilies by Salator
elseif ($_GET['op'] == 'lilies')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c
    `n`7Als du die Blumen auf dem Schrein untersuchst bemerkst du eine Veränderung an dir. Deine Seele und dein Körper nähern sich einander und sind schließlich wieder vereint. Genau vor dir öffnet sich ein hell leuchtendes Tor zur Oberwelt. Ohne weiter zu zögern durchschreitest du die Pforte..`n';
    $session['user']['hitpoints'] = $session['user']['maxhitpoints'] >> 2;
    $session['user']['alive'] = true;
    $session['user']['pqtemp'] = '';
    addnews($session['user']['name'].'`0 verlässt auf mysteriöse Weise das Totenreich');
    addnav('Zu den Lebenden!','friedhof.php');
}
// freie Wiedererweckung für Neulinge by Salator (Idee von plueschdrache)
elseif ($_GET['op'] == 'free_resurrect')
{
    $str_output .= '`c`b`)Das Mausoleum`0`b`c`n';
    if($_GET['act']=='ok')
    {
        $sql='UPDATE account_extra_info SET free_resurrections=free_resurrections-1 WHERE acctid='.$session['user']['acctid'];
        db_query($sql);
        debuglog('hat eine freie Wiedererweckung genutzt');
        redirect('newday.php?resurrection=true');
    }
    else
    {
        $row=user_get_aei('free_resurrections');
        $str_output .= '`7`c`bUm dein Leben betteln`b`c`nRamius ist gnädig zu Neulingen, die noch keine Heldentat vollbracht haben. Du kannst ihn bis zu 5 mal um seine Gnade bitten. Sei dir jedoch bewusst, dass du damit einen Teil deiner Seele verkaufst!`n`n`&Du hast diese Möglichkeit bis jetzt `4'.(5-$row['free_resurrections']).'`& mal genutzt und hast noch `@'.$row['free_resurrections'].'`& Erweckungen übrig.`n';
        if($row['free_resurrections']>0)
        {
            addnav('Wiedererwecken (noch '.$row['free_resurrections'].' mal)','graveyard.php?op=free_resurrect&act=ok');
        }
    }
    addnav('F?Zum Friedhof','graveyard.php');
}

if ($battle)
{
    //make some adjustments to the user to put them on mostly even ground with the undead guy.
    $originalhitpoints = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $session['user']['soulpoints'];
    $originalattack = $session['user']['attack'];
    $originaldefense = $session['user']['defence'];
    $session['user']['attack'] = 10 + round(($session['user']['level'] - 1) * 1.5);
    $session['user']['defence'] = 10 + round(($session['user']['level'] - 1) * 1.5);

    output($str_output, true);
    $str_output = '';
    include('battle.php');

    //reverse those adjustments, battle calculations are over.
    $session['user']['attack'] = $originalattack;
    $session['user']['defence'] = $originaldefense;
    $session['user']['soulpoints'] = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $originalhitpoints;
    if ($victory)
    {
        $str_output .= '`0`b`&'.$badguy['creaturelose'].'`0`b`n';
        $str_output .= '`b`$Du hast '.$badguy['creaturename'].' erniedrigt!`0`b`n';
        $str_output .= '`#Du bekommst `^'.$badguy['creatureexp'].'`# Gefallen bei `$Ramius`#!`n`0';
        $session['user']['deathpower']+=$badguy['creatureexp'];
        $badguy=array();
        $_GET['op']='';
        if (e_rand(1,7)==3)
        {
            addnav('Fluss der Seelen','styx.php');
        }
        else if ((e_rand(1,30)==3) && ($session['user']['dragonkills']>14))
        {
            addnav('Geistschrein','spiritshrine.php');
        }
        gravenav();
    }
    elseif ($defeat)
    {
        addnews('`)'.$session['user']['name'].'`) wurde auf dem Friedhof von '.$badguy['creaturename'].'`) erniedrigt.`n'.get_taunt(false));
        $str_output .= '`0`b`&Du wurdest von `%'.$badguy['creaturename'].' `&erniedrigt!!!`n';
        $str_output .= 'Du kannst heute keine weiteren Seelen mehr quälen.`n';
        $session['user']['gravefights']=0;
        $session['user']['soulpoints']=0;

        // Knappe verlieren
        $sql = 'SELECT name,state FROM disciples WHERE master='.$session['user']['acctid'];
        $result = db_query($sql);
        $rowk = db_fetch_assoc($result);
        $kname=$rowk['name'];
        $kstate=$rowk['state'];
        if ($kstate==20 || $kstate==21)
        {
            $str_output .= '`^'.$kname.' `4wird von `%'.$badguy['creaturename'].'`4 versklavt!`n`n';
            disciple_remove();
            debuglog('Verlor einen Knappen bei einer Niederlage im Totenreich.');
        }

        addnav('Zurück zum Friedhof','graveyard.php');
        $badguy=array();
    }
    else
    {
        addnav('Quälen','graveyard.php?op=fight');
        addnav('Fliehen','graveyard.php?op=run');
        if (getsetting('autofight',0))
        {
            addnav('AutoFight');
            addnav('5 Runden quälen','graveyard.php?op=fight&auto=5');
            addnav('Bis zum bitteren Ende','graveyard.php?op=fight&auto=100');
        }
    }
}

output($str_output,true);

page_footer();
?>

