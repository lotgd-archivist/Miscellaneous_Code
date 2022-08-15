
<?php

// 15082004
// 09.04.2006 Ahnenschreib-Mod by Maris (Maraxxus@gmx.de)

require_once "common.php";
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

if ($session['user']['alive'] && !isset($_GET['ringid']))
{
    redirect("village.php");
}

page_header("Der Friedhof");
checkday();

music_set('shades');

// * Buffs verwalten -> nur Totenreich-Buffs zulassen
$arr_buffsave = array();
// Buff "untoter Knappe" sichern:
$sql = "SELECT name,state FROM disciples WHERE master=".$session['user']['acctid']." AND at_home=0";
$result = db_query($sql) or die(db_error(LINK));
$rowk = db_fetch_assoc($result);
if ($rowk['state'] == 20) {
    $arr_buffsave['decbuff'] = $session['bufflist']['decbuff'];
}
// Buffs, welche im Totenreich erlaubt sind, sichern:
$sql2 = db_query("SELECT ib.name FROM items_buffs ib LEFT JOIN items_tpl it ON (ib.id = it.buff1 OR ib.id = it.buff2) WHERE (it.buff1 > 0 OR it.buff2 > 0) AND it.battle_graveyard = 1 AND ib.name IN ('".join("','",array_keys($session['bufflist']))."')");
while($rowbuff = db_fetch_assoc($sql2)) {
    $arr_buffsave[$rowbuff['name']] = $session['bufflist'][$rowbuff['name']];
}
// Buffliste überspeichern:
$session['bufflist'] = $arr_buffsave;
// end Buffs verwalten *

$session['user']['drunkenness'] = 0;
$max = $session['user']['level'] * 5 + 50;
$favortoheal = round(10 * ($max-$session['user']['soulpoints'])/$max);

if ($_GET['op']=="search")
{
    if ($session['user']['gravefights']<=0)
    {
        output("`\$`bDeine Seele kann keine weiteren Qualen in diesem Nachleben mehr ertragen.`b`0");
        $_GET['op']="";
    }
    else
    {
        $session['user']['gravefights']--;
        $battle=true;
        $sql = "SELECT * FROM creatures WHERE location=1 ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $badguy = db_fetch_assoc($result);
        $level = $session['user']['level'];
        $shift = 0;
        if ($level < 5)
        {
            $shift = -1;
        }
        $badguy['creatureattack'] = 9 + $shift + (int)(($level-1) * 1.5);
        // Make graveyard creatures easier.
        $badguy['creaturedefense'] = (int)((9 + $shift + (($level-1) * 1.5)) * .7);
        $badguy['creaturehealth'] = $level * 5 + 50;
        $badguy['creatureexp'] = e_rand(10 + round($level/3),20 + round($level/3));
        $badguy['creaturelevel'] = $level;

        $session['user']['badguy']=createstring($badguy);
    }
}
if ($_GET['op']=="fight" || $_GET['op']=="run")
{
    if ($_GET['op']=="run")
    {
        if (e_rand(0,2)==1)
        {
            output('`²J`ça`Âr`Îc`Âa`çt`²h`) verflucht dich für deine Feigheit.`n`n');
            $favor = 5 + e_rand(0, $session['user']['level']);
            if ($favor > $session['user']['deathpower'])
            {
                $favor = $session['user']['deathpower'];
            }
            if ($favor > 0)
            {
                output("`)Du hast `^$favor`) Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h `)VERLOREN`).");
                $session['user']['deathpower']-=$favor;
            }
            addnav("Zurück zum Friedhof","graveyard.php");
            $session['user']['reputation']--;
        }
        else
        {
            output("`)Als du zu fliehen versuchst, wirst du zum Kampf zurückberufen!`n`n");
            $battle=true;
        }
    }
    else
    {
        $battle = true;
    }
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
    include("battle.php");
    //reverse those adjustments, battle calculations are over.
    $session['user']['attack'] = $originalattack;
    $session['user']['defence'] = $originaldefense;
    $session['user']['soulpoints'] = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $originalhitpoints;
    if ($victory)
    {
        output("`n`b`&{$badguy['creaturelose']}`0`b`n`n
                `b`ôDu hast `_{$badguy['creaturename']} `ôerniedrigt!`0`b`n`n
                `²Du bekommst `ã{$badguy['creatureexp']} `²Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h!`n`0");
        // * Zufällig Item finden (eher unwahrscheinlich)
        $int_rand = e_rand(0,10);
        if($int_rand == 2) {
            $item_hook_info['chance'] = item_get_chance();
            // Wie viele Items gibt es insgesamt, die infrage kommen?
            $c = db_fetch_assoc(db_query("SELECT COUNT(tpl_id) AS count FROM items_tpl WHERE find_graveyard = ".$item_hook_info['chance']));
            $c = (int)$c['count']-1;
            // Ein Item zufällig aus DB laden:
            $res = item_tpl_list_get('find_graveyard = '.$item_hook_info['chance'],'ORDER BY RAND('.e_rand(0,$c).') LIMIT 1');
            if(db_num_rows($res)) {
                $item = db_fetch_assoc($res);
                // Falls Totenreichbeute-Hook angegeben: Hook laden
                if(!empty($item['find_graveyard_hook'])) {
                    item_load_hook($item['find_graveyard_hook'],'find_graveyard',$item);
                }
                // Ansonsten Finden-Nachricht ausgeben...
                if(!$item_hook_info['hookstop']) {
                    // ...aber nur, wenn der Eintrag in die DB erfolgreich war:
                    if (item_add($session['user']['acctid'],0,$item)) {
                            output('`n`ãAußerdem findest du `²'.$item['tpl_name'].' `ãauf dem Boden! ('.$item['tpl_description'].'`ã)`0`n');
                    }
                }
            }
        }
        // end Item finden *
        $session['user']['deathpower']+=$badguy['creatureexp'];
        $badguy=array();
        $_GET['op']="";
        if (e_rand(1,7)==3)
        {
            addnav("Fluss der Seelen","styx.php");
        }
        else if ((e_rand(1,30)==3) && ($session['user']['dragonkills']>10))
        {
            addnav("Geistschrein","spiritshrine.php");
        }
    }
    else
    {
        if ($defeat)
        {
            addnews("`)".$session['user']['name']."`) wurde auf dem Friedhof von {$badguy['creaturename']} `)erniedrigt.");
            output("`n`b`ôDu wurdest von `_{$badguy['creaturename']} `ôerniedrigt!`n`n");
            output("`&Du kannst heute keine weiteren Seelen mehr quälen.`n");
            $session['user']['gravefights']=0;

            // Knappe verlieren
            $sql = 'SELECT name,state FROM disciples WHERE master='.$session['user']['acctid'].' AND at_home=0';
            $result = db_query($sql) or die(db_error(LINK));
            $rowk = db_fetch_assoc($result);
            $kname=$rowk['name'];
            $kstate=$rowk['state'];
            if ($kstate==20)
            {
                output('`^'.$kname.' `4wird von `%'.$badguy['creaturename'].'`4 versklavt!`n`n');
                disciple_remove();
                debuglog("Verlor einen Knappen bei einer Niederlage im Totenreich.");
            }

            addnav("F?Zurück zum Friedhof","graveyard.php");
        }
        else
        {
            addnav("Q?Quälen","graveyard.php?op=fight");
            addnav("F?Fliehen","graveyard.php?op=run");
            if (getsetting("autofight",0))
            {
                addnav("AutoFight");
                addnav("5 Runden quälen","graveyard.php?op=fight&auto=five");
                addnav("Bis zum bitteren Ende","graveyard.php?op=fight&auto=full");
            }
            // spells by anpera, modded by talion
            $result = item_list_get( ' owner='.$session['user']['acctid'].' AND value1>0 AND (deposit1=0 OR deposit1='.ITEM_LOC_EQUIPPED.')
                            AND battle_graveyard = 1 ', ' GROUP BY name ORDER BY value1 ASC, name ASC, id ASC', true , ' SUM(value1) AS anzahl, name, id ' );

            $int_count = db_num_rows($result);

            if ($int_count>0) addnav(' ~ Dein Beutel ~ ');

            for ($i=1;$i<=$int_count;$i++) {
                    $row = db_fetch_assoc($result);
                    addnav($row['name'].' `0('.$row['anzahl'].'x)','graveyard.php?op=fight&skill=zauber&itemid='.$row['id'],false,false,false,false);
            }
            // end spells
        }
    }
}

if ($_GET['op']=="")
{
    if(!$victory)
    {
    output('`7`c`bDer Friedhof`b`c`n');
    output("`)Du schwebst langsam zwischen den vielen Gräbern hindurch, auf der Suche nach anderen Seelen. Je näher du dabei `²J`ça`Âr`Îc`Âa`çt`²h`)s Residenz kommst, umso
            drängender spürst du seinen Einfluss auf deinen Geist. Andere Seelen geistern gelegentlich an dir vorbei, nehmen dich jedoch kaum wahr, so sehr
            sind sie mit sich selbst und ihrem eigenen Leid beschäftigt. Unter dem unbarmherzigen Blick des Totengottes wandern sie umher und büßen für ihre
            Taten, welche sie einst als lebende Wesen begangen haben.");
    }
    addnav("Gefallen erwerben");
    addnav("Etwas zum Quälen suchen","graveyard.php?op=search");
    addnav("Besondere Orte");
    addnav("M?Mausoleum betreten","graveyard.php?op=enter");
    addnav("A?Zum Ahnenschrein","graveyard.php?op=shrine");
    if (su_check(SU_RIGHT_DEBUG))
    {
        addnav("Geistschrein","spiritshrine.php");
    }
    //addnav("Kriegerliste","list.php");
    addnav("Zurück");
    addnav("Zu den Schatten","shades.php");

}
else if ($_GET['op']=="shrine")
{
    $rowe = user_get_aei('dpower');
    $dpower = $rowe['dpower'];
    output("`&Du begibst dich zum Ahnenschrein, in der Hoffnung, dass einer deiner Hinterbliebenen deine Seele mit einem Gebet bedacht hat.`n");
    if ($dpower > 0)
    {
        output("`&Und tatsächlich bemerkst du, dass man dir insgesamt `^{$dpower}`& Gefallen überlassen hat.");
        addnav("Gefallen abholen");
        addnav("M?Meditieren","graveyard.php?op=shrine_normal");
        addnav("Risiko");
        addnav("D?Doppelt oder nichts","graveyard.php?op=shrine_risk");
    }
    else
    {
        output("`&Doch enttäuscht musst du feststellen, dass dem nicht so ist.");
    }
    addnav('Zurück');
    addnav("F?Zum Friedhof","graveyard.php");

}
else if ($_GET['op']=="shrine_normal")
{
    $rowe = user_get_aei('dpower');
    $dpower = $rowe['dpower'];
    output("`&Du kniest dich vor den Schrein und empfängst die `^{$dpower}`& Gefallen, die man dir zukommen ließ.");
    $session['user']['deathpower']+=$dpower;
    user_set_aei(array('dpower' => 0));
    addnav('Zurück');
    addnav("F?Zum Friedhof","graveyard.php");

}
else if ($_GET['op']=="shrine_risk")
{
    $rowe = user_get_aei('dpower');
    $dpower = $rowe['dpower'];
    output("`&Du kniest dich vor den Schrein und meditierst. Da du ein Spieler bist, gehst du auf volles Risiko und versuchst dir mehr Gefallen anzueignen, als die eigentlich zustehen.`n");
    if (e_rand(1,10)>5)
    {
        $dpower=$dpower*2;
        user_set_aei(array('dpower' => 0));
        output("`&Dies gelingt dir auch ganz gut. Du kannst `^{$dpower}`& Gefallen abstauben!");
        $session['user']['deathpower']+=$dpower;
    }
    else
    {
        output("`&Doch `²J`ça`Âr`Îc`Âa`çt`²h, der sich nur ungern hinters Licht führen lässt, schaut dir schon eine ganze Weile über die Schulter und findet es gar nicht gut, was du da versuchst.`n
Zur Strafe nimmt er dir sowohl die Gefallen die du bei ihm gut hast, wie auch jene, die auf dem Ahnenstein warten und verdammt dich dazu, sein Mausoleum von Grund auf zu reinigen.");
        user_set_aei(array('dpower' => 0));
        $session['user']['deathpower']=0;
        addnews("`&{$session['user']['name']}
        `&wurde von `²J`ça`Âr`Îc`Âa`çt`²h `&dazu verdammt, sein Mausoleum gründlichst zu reinigen.");
    }
    addnav('Zurück');
    addnav("F?Zum Friedhof","graveyard.php");

}
else if ($_GET['op']=="lotto")
{
    $jp=getsetting("deathjackpot","200");
    output("`&`cIm Jackpot von `^Tot-o-Lotto`& befinden sich gerade `^{$jp}`& Gefallen!`c`n`n");
    output("`&Du schleichst zu `²J`ça`Âr`Îc`Âa`çt`²h `&und bittest ihn um ein Los.`n");
    if ($session['user']['deathpower']<10)
    {
        output("`&Doch dieser lacht dich nur spöttich aus, da du mindestens `^10 Gefallen`& brauchst, um mitzuspielen.");
    }
    else
    {
        output("`&Dieser teilt dir knapp mit, dass dich dies `^deine gesamten Gefallen`&, die du bei ihm gut hast, kosten würde.`n
Willst du immer noch mitspielen?");
        addnav("Mitspielen");
        addnav("JA","graveyard.php?op=lotto2");
    }
    addnav('Zurück');
    addnav("M?Zum Mausoleum","graveyard.php?op=enter");

}
else if ($_GET['op']=="lotto2")
{
    if ($session['user']['deathpower']>=10)
    {
        $jp=getsetting("deathjackpot","200");
        $dpsave=floor($session['user']['deathpower']*0.5);
        $session['user']['deathpower']=0;
        $win=e_rand(1,500);
        if ($win>=492)
        {
            output("`^JACKPOT!`n
{$jp}`& Gefallen sind dein!");
            $session['user']['deathpower']+=$jp;
            savesetting("deathjackpot","200");
            addnews("`&Lauter Jubel war heute aus dem Totenreich zu hören, als `^{$session['user']['name']}
            `& den Jackpot im Tot-o-Lotto knackte und `^{$jp}
            `& Gefallen gewann.");
        }
        else
        {
            output("`&Du ziehst eine Niete.`n
Das war wohl nichts, du hast all deine Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h `&verloren.");

            $jackpot=getsetting("deathjackpot","200");
            $jpmax=getsetting("deathjackpotmax","10000");
            if ($jackpot+$dpsave<=$jpmax)
            {
                savesetting("deathjackpot",getsetting("deathjackpot","200")+$dpsave);
            }
            else
            {
                savesetting("deathjackpot",$jpmax);
            }

        }
    }
    else // refresh ?
    {
        output("`&Hier stimmt was nicht...");
    }
    addnav('Zurück');
    addnav("M?Zum Mausoleum","graveyard.php?op=enter");

}
else if ($_GET['op']=="enter")
{
    output("`²`b`cDas Mausoleum`c`b`n");
    output("`UDu betrittst das Mausoleum und siehst dich in einer kalten, kahlen Kammer aus Marmor. Die Luft um dich herum trägt die Kälte des Todes selbst.
    Aus der Dunkelheit starren zwei schwarze Augen direkt in deine Seele. Ein feuchtkalter Griff scheint deine Seele zu umklammern und sie mit den Worten des Gottes der Toten, `²J`ça`Âr`Îc`Âa`çt`²h`U, höchstpersönlich zu erfüllen.
    \"`çDein sterblicher Körper hat dich im Stich gelassen. Und jetzt wendest du dich an mich. Es gibt in diesem Land diejenigen, die sich meinem Griff entziehen konnten und ein Leben über das Leben hinaus besitzen. Um mir deinen Wert für mich zu beweisen
    und dir Gefallen zu verdienen, gehe raus und quäle deren Seelen. Solltest du mir genug Gefallen getan haben, werde ich dich belohnen.`U\"`n`n");
    if ($session['user']['marks']>=31)
    {
        output("`²J`ça`Âr`Îc`Âa`çt`²h `7nickt dir wohlwollend zu, erkennend, dass du zu den Auserwählten gehörst.`n`n");
    }

    if (true) //(item_count(' (i.tpl_id="drstb") AND owner='.$session['user']['acctid']) >= 1 )
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

                addnews('`!Soeben wurde '.$session['user']['name'].'`!\' Geist dabei beobachtet, wie er `²J`ça`Âr`Îc`Âa`çt`²h `!eine Drachenreliquie abschwatzte!');

                output('`n`n`UUrplötzlich hältst du ein eher ziemlich zerrissenes und verfranstes Ding in der Hand, das dich mit einem starken Schwefelgeruch betäubt.
Doch trotz ihres schlechten Zustands kannst du immer noch die magische Kraft der Drachenschuppe spüren!
Schnell packst du sie weg, um sie nicht zu beschädigen.');
            }
            else
            {

                output('`n`n`UAuf deine zaghafte Nachfrage, wo denn nun die Drachenreliquie sei, antwortet `²J`ça`Âr`Îc`Âa`çt`²h `Umit schallendem Lachen, das dir ein kaltes Schaudern über den Rücken jagt:`n
"`çIhr Sterblichen seid verrückt.. Ich frage mich, was diese Spinner mit den hässlichen Dingern anfangen wollen. Nun.. du kannst es haben - für `b'.$arr_item['tpl_value1'].'`b Gefallen!`U"');

                if ($session['user']['deathpower'] >= $arr_item['tpl_value1'])
                {
                    addnav($arr_item['tpl_name'].' ('.$arr_item['tpl_value1'].' Gefallen)','graveyard.php?op=enter&act=buy_rel',false,false,false,false);
                }
            }
        }
        // END noch keiner hat Rel
        else
        {

            $arr_owner = db_fetch_assoc($res);

            output('`n`n`UFast höhnisch raunen dir die verlorenen Seelen zu, dass sich '.$arr_owner['name'].'`0 noch
vor dir die Drachenreliquie unter den Nagel gerissen hat.');
        }
    }

    addnav("Frage Jarcath nach dem Wert deiner Seele","graveyard.php?op=question");
    healnav($favortoheal);
    addnav("Tot-o-Lotto");
    addnav("Spielen","graveyard.php?op=lotto");
    addnav('Zurück');
    addnav("F?Zum Friedhof","graveyard.php");

}
else if ($_GET['op']=="restore")
{
    output("`²`b`cDas Mausoleum`c`b`n");

    $int_amount = max($_GET['amount'],25) / 100;

    $favortoheal = ceil($favortoheal * $int_amount);

    if ($session['user']['soulpoints']<$max)
    {
        if ($session['user']['deathpower']>=$favortoheal)
        {
            output("`²J`ça`Âr`Îc`Âa`çt`²h`U nennt dich einen Schwächling, weil du nach Wiederherstellung deiner Seele fragst. Aber da du genug Gefallen bei ihm gut hast, gibt er deiner Bitte zum Preis von `²$favortoheal`U Gefallen nach.");
            $session['user']['deathpower']-=$favortoheal;

            $diff = round(($max-$session['user']['soulpoints'])*$int_amount, 0 );

            $session['user']['soulpoints'] += $diff;
            // Klickweg-Verkürzung: nach Heilung direkt weiterquälen
            addnav("Gefallen erwerben");
            addnav("Etwas zum Quälen suchen","graveyard.php?op=search");
            addnav("Besondere Orte");
            addnav("M?Mausoleum betreten","graveyard.php?op=enter");
            addnav("A?Zum Ahnenschrein","graveyard.php?op=shrine");
            if (su_check(SU_RIGHT_DEBUG))
            {
                addnav("Geistschrein","spiritshrine.php");
            }
            //addnav("Kriegerliste","list.php");
            addnav("Zurück");
            addnav("Zu den Schatten","shades.php");
            page_footer();
            exit;
        }
        else
        {
            output("`²J`ça`Âr`Îc`Âa`çt`²h`U verflucht dich und wirft dich aus dem Mausoleum. Du mußt ihm erst genug Gefallen getan haben, bevor er dir die Wiederherstellung deiner Seele gewährt.");
        }
    }
    else
    {
        output("`²J`ça`Âr`Îc`Âa`çt`²h`U seufzt und murmelt etwas von \"`çNur, weil sie tot sind, heißt das doch nicht, dass sie nicht zu denken brauchen, oder?`U\"`n`n");
        output("`)Vielleicht solltest du erstmal eine Wiederherstellung `inötig`i haben, bevor du danach fragst.");
        // Klickweg-Verkürzung: nach Heilung direkt weiterquälen
        addnav("Gefallen erwerben");
        addnav("Etwas zum Quälen suchen","graveyard.php?op=search");
        addnav("Besondere Orte");
        addnav("M?Mausoleum betreten","graveyard.php?op=enter");
        addnav("A?Zum Ahnenschrein","graveyard.php?op=shrine");
        if (su_check(SU_RIGHT_DEBUG))
        {
            addnav("Geistschrein","spiritshrine.php");
        }
        //addnav("Kriegerliste","list.php");
        addnav("Zurück");
        addnav("Zu den Schatten","shades.php");
        page_footer();
        exit;
    }
    addnav("Zurück zum Friedhof","graveyard.php");
}
else if ($_GET['op']=="question")
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
        if ($session['user']['deathpower']>=$rcost)
    {
                output("`²J`ça`Âr`Îc`Âa`çt`²h`U spricht: \"`çDu hast mich tatsächlich beeindruckt. Ich sollte dir die Möglichkeit gewähren, deine Feinde in der Welt der Sterblichen zu besuchen.`U\"");

        if ($session['user']['reputation']<=-10)
        {
            output(" Er weist dich noch darauf hin, dass er keinen Einfluss auf das Gedächtnis der Lebenden - und besonders der Händler -  hat.");
        }
        if ($session['user']['reputation']<=-40)
        {
            output("`n`n\"`çWegen der Unehrenhaftigkeit deines Lebens kann ich dir nicht erlauben, vorzeitig zu den Lebenden zurückzukehren, obwohl du mir gute Dienste geleistet hast.`U\"");
        }
                addnav("Jarcaths Gefallen");
    }
        else if($session['user']['deathpower']>=$hcost) {
                output("`²J`ça`Âr`Îc`Âa`çt`²h`U spricht: \"`çIch bin nicht wirklich beeindruckt von deinen Bemühungen, aber einen kleinen Gefallen werde ich dir gewähren. Führe meine Arbeit fort und ich kann dir vielleicht mehr meiner Kraft anbieten.`U\"");
                addnav("Jarcaths Gefallen");
        }
        else {
                output("`²J`ça`Âr`Îc`Âa`çt`²h`U spricht: \"`çIch bin von deinen Bemühungen noch nicht beeindruckt. Führe meine Arbeit fort und wir können weiter reden.`U\"");
        if ($session['user']['prefs']['sounds'])
        {
            output("<embed src=\"media/lachen.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
        }
        }

    if ($session['user']['deathpower']>=$rcost)
    {

        if ($session['user']['reputation']>-40)
        {
            addnav("e?Wiedererwecken ($rcost Gefallen)","newday.php?resurrection=true");
        }

    }
        // RP - Wiedererweckung
    if ($session['user']['deathpower'] >= RP_RESURRECTION_COST)
    {
        addnav('Besuch der Oberwelt ('.RP_RESURRECTION_COST.' Gefallen)','graveyard.php?op=rp_resurrect');
    }
        if ($session['user']['deathpower']>=100)
        {
                addnav("5 Donationpoints (100 Gefallen)","graveyard.php?op=dona");
        }
        if ($session['user']['deathpower']>=$hcost)
        {
                 addnav("h?Feind heimsuchen ($hcost Gefallen)","graveyard.php?op=haunt");
        }

        addnav("Sonstiges");

    output("`n`n`UDu hast `²{$session['user']['deathpower']}
    `U Gefallen bei `²J`ça`Âr`Îc`Âa`çt`²h`U.");
    addnav("Frage Jarcath nach dem Wert deiner Seele","graveyard.php?op=question");
    healnav($favortoheal);
    addnav("Tot-o-Lotto");
    addnav("Spielen","graveyard.php?op=lotto");
    addnav('Zurück');
    addnav("F?Zum Friedhof","graveyard.php");
}
else if ($_GET['op']=="dona")
{
    output('`²J`ça`Âr`Îc`Âa`çt`²hs`U Gelächter lässt den Boden erbeben. "`7Du verzichtest für ein paar Punkte auf das Leben? Wie töricht! Aber nun denn, so sei es.`U" Mit diesen Worten gibt er deiner Bitte nach.`nDu bekommst 5 Donationpoints.');
    $session['user']['deathpower']-=100;
    $session['user']['donation']+=5;
    addnav("Zurück zum Mausoleum","graveyard.php?op=enter");
    addnav("Zurück zum Friedhof","graveyard.php");
}
else if ($_GET['op']=="haunt")
{
    output('`²J`ça`Âr`Îc`Âa`çt`²h`U ist von deinen Aktionen beeindruckt und gewährt dir die Macht, einen Feind heimzusuchen.`n`n');
    output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
    addnav("","graveyard.php?op=haunt2");
    output("Wen willst du heimsuchen? <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
    output("</form>",true);
    output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    addnav("Zurück zum Mausoleum","graveyard.php?op=enter");
}
else if ($_GET['op']=="haunt2")
{
        $string = str_create_search_string($_POST['name']);

    $sql = "SELECT login,name,level FROM accounts WHERE name LIKE '".$string."' AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    if (db_num_rows($result)<=0)
    {
        output('`²J`ça`Âr`Îc`Âa`çt`²h`U kann niemanden mit einem solchen Namen finden.');
    }
    else if (db_num_rows($result)>100)
    {
        output('`²J`ça`Âr`Îc`Âa`çt`²h`U denkt, du solltest die Zahl derer, die du heimsuchen willst, etwas einschränken.');
        output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
        addnav("","graveyard.php?op=haunt2");
        output("Wen willst du heimsuchen? <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
        output("</form>",true);
        output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    }
    else
    {
        output('`²J`ça`Âr`Îc`Âa`çt`²h`U wird dir gestatten, eine der folgenden Personen heimzusuchen:`n');
        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
        output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
        for ($i=0; $i<db_num_rows($result); $i++)
        {
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='graveyard.php?op=haunt3&name=".HTMLEntities($row['login'])."'>",true);
            output($row['name']);
            output("</a></td><td>",true);
            output($row['level']);
            output("</td></tr>",true);
            addnav("","graveyard.php?op=haunt3&name=".HTMLEntities($row['login']));
        }
        output("</table>",true);
    }
    addnav("Frage Jarcath nach dem Wert deiner Seele","graveyard.php?op=question");
    healnav($favortoheal);
    addnav('Zurück');
    addnav("M?Zum Mausoleum","graveyard.php?op=enter");
}
else if ($_GET['op']=="haunt3")
{
    output("`)`c`bDas Mausoleum`b`c");

    $sql = "SELECT name,level,hauntedby,accounts.acctid FROM accounts LEFT JOIN account_extra_info USING(acctid) WHERE login='{$_GET['name']}
    '";
    $result = db_query($sql);

    if (db_num_rows($result)>0)
    {
        $row = db_fetch_assoc($result);
        if ($row['hauntedby']!="")
        {
            output("Diese Person wurde bereits heimgesucht. Wähle eine andere");
        }
        else
        {

            if ($session['user']['marks']>=31)
            {
                $session['user']['deathpower']-=20;
            }
            else
            {
                $session['user']['deathpower']-=20;
            }
            $roll1 = e_rand(0,$row['level']);
            $roll2 = e_rand(0,$session['user']['level']);
            if ($roll2>$roll1)
            {
                output("Du hast `7{$row['name']}
                `) erfolgreich heimgesucht!");

                user_set_aei(array('hauntedby'=>addslashes($session['user']['name'])) , $row['acctid'] );

                addnews("`7{$session['user']['name']}
                `) hat `7{$row['name']}
                `) heimgesucht!");
                $session['user']['donation']+=1;
                systemmail($row['acctid'],"`)du wurdest heimgesucht","`)Du wurdest von {$session['user']['name']}
                heimgesucht");
            }
            else
            {
                addnews("`7{$session['user']['name']}
                `) hat erfolglos versucht, `7{$row['name']}
                `) heimzusuchen!");
                switch (e_rand(0,5))
                {
                case 0:
                    output("Gerade als du `7{$row['name']}
                    `) heimsuchen wolltest, versaut dir ein Niesen komplett den Erfolg.");
                    break;
                case 1:
                    output("Die Heimsuchung von `7{$row['name']}
                    `) läuft richtig gut. Leider schläft dein Opfer tief und fest und bekommt von deiner Anwesenheit absolut nichts mit.");
                    break;
                case 2:
                    output("Du machst dich zur Heimsuchung von `7{$row['name']}
                    `) bereit, stolperst aber über deinen Geisterschwanz und landest flach auf deinem .... ähm ... Gesicht.");
                    break;
                case 3:
                    output("Du willst `7{$row['name']}
                    `) im Schlaf heimsuchen, doch dein Opfer dreht sich nur im Bett um und murmelt etwas von 'nie wieder Würstchen so kurz vor dem Schlafengehen'.");
                    break;
                case 4:
                    output("Du weckst `7{$row['name']}
                    `) auf. Dein Opfer schaut dich kurz an, sagt \"Niedlich!\" und versucht dich in einem Einmachglas einzufangen.");
                    break;
                case 5:
                    output("Du versuchst `7{$row['name']}
                    `) zu erschrecken, siehst dich dabei im Augenwinkel selbst im Spiegel und gerätst in Panik, weil du einen Geist gesehen hast!");
                    break;
                }
            }
        }
    }
    else
    {
        output('`²J`ça`Âr`Îc`Âa`çt`²h`U kann sich nicht mehr auf diese Person konzentrieren. Du kannst sie jetzt nicht heimsuchen.');
    }
    addnav("Frage Jarcath nach dem Wert deiner Seele","graveyard.php?op=question");

    healnav($favortoheal);

    addnav('Zurück');
    addnav("M?Zum Mausoleum","graveyard.php?op=enter");
}
// RP - Wiedererweckung by talion
elseif ($_GET['op'] == 'rp_resurrect')
{
    if ($_GET['act'] == 'ok')
    {
        if($_GET['ringid'] > 0) {
            output('`IDu streifst dir den Ring über deinen Finger, woraufhin plötzlicher Nebel aufzieht. Dieser hüllt dich komplett ein...`n
                    `n
                    `h...und auf einmal spürst du einen Ruck, der deinen gesamten Körper erzittern lässt. Du merkst, wie wieder Blut durch deine Arterien fließt, und als sich der Nebel schließlich
                    lichtet, befindest du dich nicht mehr im Totenreich, sondern in der Welt der Lebenden.`n
                    Vom Ring an deinem Finger fehlt allerdings jede Spur.`n`n');

            $session['user']['alive'] = 1;
            $session['user']['hitpoints'] = 1;
            $session['user']['badguy'] = '';

            addnews($session['user']['name'].' `&ist dem Totenreich mit Hilfe eines Rings entkommen!');

            item_delete(' id='.$_GET['ringid']);
            addnav('Zum Fluss','pool.php');
        } else {
            $session['user']['spirits'] = RP_RESURRECTION;

            user_set_aei(array('witch'=>999, 'seenacademy'=>1, 'goldin'=>1234567, 'goldout'=>1234567,
            'gemsin'=>1234567, 'gemsout'=>1234567, 'fishturn'=>0, 'dollturns'=>0, 'seenbard'=>1, 'usedouthouse'=>1,
            'gotfreeale'=>999) );

            $session['user']['hitpoints'] = 1;
            $session['user']['alive'] = true;

            $session['user']['deathpower'] -= RP_RESURRECTION_COST;

            if($_GET['ankh'] == true)
            {
                addnews($session['user']['name'].'`& hat ein Ankh für eine RP-Wiederbelebung verwendet.');
                item_delete('tpl_id="ankh" AND owner='.$session['user']['acctid'],1);
                redirect('pool.php');
            }
            else
            {
                addnews($session['user']['name'].'`& hat '.(!$session['user']['sex'] ? 'seinem' : 'ihrem').' Körper einen Ausflug in die Welt der Lebenden erkauft!');
                output('`UAuf ein verknöchert klingendes Fingerschnipsen des `²J`ça`Âr`Îc`Âa`çt`²h `Uhin öffnet sich genau vor dir ein hell leuchtendes Tor zur Oberwelt.
                        Ohne weiter zu zögern durchschreitest du die Pforte..');
                addnav('Zu den Lebenden!','pool.php');
            }
        }
    }
    else
    {

        addnav('Zurück zu den Toten','graveyard.php');

        output('`²J`ça`Âr`Îc`Âa`çt`²h `Uofferiert dir in herablassendem Ton die Option, trotz deines körperlichen Todes unter den Lebenden zu wandeln.
                                Dein geschwächter, halbtoter Körper wäre in seinen Möglichkeiten natürlich stark eingeschränkt und könnte bei so mancher
                                Gelegenheit anders als gewohnt reagieren: Einzig deine geistigen Fähigkeiten
                                - der Gott der Unterwelt lächelt mit Eiseskälte - stünden dir noch voll zur Verfügung.`n`n
                                Für diese Prozedur verlangt `²J`ça`Âr`Îc`Âa`çt`²h `S`b'.RP_RESURRECTION_COST.'`b `UGefallen. Willst du sie vollführen?`n`n
                                [Hinweis: Diese Wiederweckung dient einzig und allein dem Rollenspiel! Waldkämpfe o.ä. sind damit NICHT durchführbar!]`n`n
                                ');
        output(create_lnk('Ja, lass mich hinauf!','graveyard.php?op=rp_resurrect&act=ok', true, true), true);

    }

}

page_footer();
?>

