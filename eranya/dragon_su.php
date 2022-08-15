
<?php
/**
* dragon.php: Endgegner
* @author LOGD-Core, modded by Drachenserver-Team
* @version DS-E V/2
*/

// 24072004

require_once('common.php');
require_once(LIB_PATH.'disciples.lib.php');

page_header("Der Grüne Drache! .. oder so");

if ($_GET['op']=="")
{
    $row_su = db_fetch_assoc(db_query("SELECT name,sex,prefs,superuser FROM accounts WHERE superuser = 1 OR (superuser = 2 AND acctid != 8) ORDER BY RAND() LIMIT 1"));
    $row_su['prefs'] = unserialize($row_su['prefs']);
    $str_su_talkcolor = get_fc($row_su['prefs']['commenttalkcolor']);
    if(!$str_su_talkcolor) $str_su_talkcolor = '`#';
    $bool_sex = ($row_su['sex'] ? true : false);
    $bool_su = ($row_su['superuser'] == 2 ? true : false);
    $str_weapon = ($bool_su ? "Codeschleuder" : "Adminhammer");
    output("`1Du unterdrückst den Drang zu fliehen und folgst dem Gang tiefer in das Höhlensystem. Schließlich betrittst du eine geheime Grotte, die erst wenige
            Sterbliche vor dir gesehen haben. Dass dies jedoch nicht die Höhle des grünen Drachen sein kann, wird dir schlagartig bewusst, als du zu deiner
            Rechten eine".($bool_su ? " riesige" : "n riesigen")." ".$str_weapon." an die Wand gelehnt stehen siehst - und direkt daneben ein Regal entdeckst, auf dem viele kleine
            Fluffpuffel-Figürchen in allen möglichen Farben aufgereiht sind. Muskulöse Männer mit nacktem Oberkörper und leicht bekleidete Frauen tragen
            Weinkaraffen und Tabletts mit Weintrauben hin und her, ohne dir Beachtung zu schenken.`n
            Du biegst um eine Ecke und entdeckst ".$row_su['name']."`1, ".($bool_sex ? "die" : "der")." auf einer römisch-griechischen Liege faulenzt und sich die
            Zähne mit etwas reinigt, das wie eine Rippe aussieht. Zuerst bemerkt ".($bool_sex ? "sie" : "er")." dich gar nicht, doch als du dich hastig umdrehen
            und fliehen willst, stößt du versehentlich ein leeres Tablett von einem nahe stehenden Tisch, das laut scheppernd zu Boden fällt. Sofort springt
            ".$row_su['name']." `1auf, schnappt sich ".($bool_su ? "die" : "den")." ".$str_weapon." und `\$stürzt sich auf dich!`n`n");
    $badguy = array("creaturename"=>$row_su['name'],"creaturesex"=>$row_su['sex'],"creaturesu"=>$row_su['superuser'],"creaturetalkcolor"=>$str_su_talkcolor,"creaturelevel"=>18,"creatureweapon"=>$str_weapon,"creatureattack"=>45,"creaturedefense"=>25,"creaturehealth"=>300, "diddamage"=>0);
    //toughen up each consecutive dragon.
    //      $atkflux = e_rand(0,$session['user']['dragonkills']*2);
    //      $defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
    //      $hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
    //      $badguy['creatureattack']+=$atkflux;
    //      $badguy['creaturedefense']+=$defflux;
    //      $badguy['creaturehealth']+=$hpflux;

    // First, find out how each dragonpoint has been spent and count those
    // used on attack and defense.
    // Coded by JT, based on collaboration with MightyE
    $points = 0;
    while (list($key,$val)=each($session['user']['dragonpoints']))
    {
        if ($val=="at" || $val == "de")
        {
            $points++;
        }
    }
    // Now, add points for hitpoint buffs that have been done by the dragon
    // or by potions!
    $points += (int)(($session['user']['maxhitpoints']-150)/5);

    // Okay.. *now* buff the dragon a bit.
    if ($beta)
    {
        $points = round($points*1.5,0);
    }
    else
    {
        $points = round($points*.85,0);
    }

    $atkflux = e_rand(0, $points);
    $defflux = e_rand(0,$points-$atkflux);
    $hpflux = ($points - ($atkflux+$defflux)) * 5;
    $badguy['creatureattack']+=$atkflux;
    $badguy['creaturedefense']+=$defflux;
    $badguy['creaturehealth']+=$hpflux;
    $badguy['creaturehealth']*=1.65;

    // Endgegner
    $badguy['boss'] = true;

    $float_forest_bal = getsetting('forestbal',1.5);

    $badguy['creatureattack'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
    $badguy['creaturedefense'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
    $badguy['creaturehealth'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];

    $badguy['creaturehealth'] = round($badguy['creaturehealth']);

    $session['user']['badguy']=createstring($badguy);
    $battle=true;
}
else if ($_GET['op']=="autochallenge")
{
    output("`\$Auf dem Weg zum Stadtplatz hörst du ein seltsames Geräusch aus Richtung Wald und spürst ein ebenso seltsames Verlangen, der Ursache für das Geräusch nachzugehen. ");
    output("Die Leute auf dem Stadtplatz scheinen in ihrer Unterhaltung nichts davon mitbekommen zu haben, also machst du dich alleine auf den Weg. Kaum im Wald hörst du das Geräusch erneut, diesmal schon wesentlich näher. ");
    output("`nIn der Ferne siehst du ihn: Den `@grünen Drachen`$! Gerade dabei, eine Höhle zu betreten. Er scheint müde zu sein. Das ist `bDIE`b Gelegenheit! Nie hast du dich stärker gefühlt...");
    addnav("Weiter...","dragon.php");
}
else if ($_GET['op']=="prologue1")
{
        music_set('boss',0);

    output("`@Sieg!`n`n");
    $flawless = 0;
    if ($_GET['flawless'])
    {
        $flawless = 1;
        output("`b`c`&~~ Perfekter Kampf! ~~`0`c`b`n`n");
    }
    $comtalkcol = get_fc($session['user']['prefs']['commenttalkcolor']);
    if(!$comtalkcol) $comtalkcol = '`#';
    $row_su = db_fetch_assoc(db_query("SELECT name,sex FROM accounts WHERE superuser = 3 ORDER BY RAND() LIMIT 1"));
    $badguy = $_SESSION['badguy'];        # badguy-Daten aus Zwischenspeicher laden..
    unset($_SESSION['badguy']);           # ..und Zwischenspeicher leeren
    $str_su_title_nom = ($badguy['creaturesu'] == 2 ? ($badguy['creaturesex'] ? 'ie Entwicklerin' : 'er Entwickler') : ($badguy['creaturesex'] ? 'ie Admina' : 'er Admin'));
    $str_su_title_gen = ($badguy['creaturesu'] == 2 ? ($badguy['creaturesex'] ? 'er Entwicklerin' : 'es Entwicklers') : ($badguy['creaturesex'] ? 'er Admina' : 'es Admins'));
    output('`2'.$badguy['creaturename'].' `2liegt schwer atmend vor dir, besiegt. Erschöpft lässt du deine Waffe sinken - und reißt sie dann aber doch wieder hoch,
            als d'.$str_su_title_nom.' plötzlich zu sprechen beginnt:`n'.$badguy['creaturetalkcolor'].'
            "Warum bist du hierher gekommen, Sterblicher? Was habe ich dir getan?"`2, fragt '.($badguy['creaturesex'] ? 'sie' : 'er').' mit sichtlicher
            Anstrengung. '.$badguy['creaturetalkcolor'].'"Meinesgleichen wurde schon immer
            gesucht, um vernichtet zu werden. Warum? Wegen Geschichten aus fernen Ländern, die von Admins und Entwicklern erzählen, die Jagd auf die Schwachen machen? Ich sage
            dir, dass diese Märchen nur durch Missverständnisse über uns entstehen und nicht, weil wir unsere Rechte missbrauchen oder eure Kinder fressen."`n
            `2D'.$str_su_title_nom.' macht eine Pause, um schwer zu atmen, dann fährt
            '.($badguy['creaturesex'] ? 'sie' : 'er').' fort: '.$badguy['creaturetalkcolor'].'"Ich werde dir jetzt ein Geheimnis verraten:
            Im Nebenraum war die ganze Zeit schon '.$row_su['name'].$badguy['creaturetalkcolor'].'. Noch ist '.($row_su['sex'] ? 'sie' : 'er').' klein und hat
            begrenzte Rechte, doch '.($row_su['sex'] ? 'sie' : 'er').' wird
            kämpfen und lernen und irgendwann stark genug sein, um dich zu besiegen." `2Der Atem d'.$str_su_title_gen.' wird kürzer und flacher.`n
            Du fragst: '.$comtalkcol.'"Warum erzählst du mir das? Kannst du dir nicht denken, dass ich '.$row_su['name'].$comtalkcol.' jetzt auch vernichten werde?"`n
            `2Mit einem schwachen Lächeln schüttelt '.$badguy['creaturename'].' `2den Kopf. '.$badguy['creaturetalkcolor'].'"Nein, das wirst du nicht, denn '.($row_su['sex'] ? 'sie' : 'er').' hat unseren Kampf verfolgt und steht nun direkt
            hinter dir..."`n
            `2Du reißt die Augen auf, als du den Trick durchschaust, und willst schon herumwirbeln, da... trifft dich etwas hart am Kopf. Deine Waffe fällt dir
            aus der Hand, und du spürst noch, wie dein Körper zusammensackt, ehe dir schwarz vor Augen wird.`n`n');
    if ($flawless)
    {
        output('Im Fallen erinnerst du dich, dass du es im letzten Moment doch noch geschafft hast, etwas von '.$badguy['creaturename'].'`2s Schatz
                einzustecken. Vielleicht war das alles ja doch kein totaler Verlust.`n`n');
    }
    
    // Account Extra Info laden
    $row_extra = user_get_aei();
    // END Account Extra Info laden

    // Knappe laden und steigern
    $sql = 'SELECT name,state,level FROM disciples WHERE master='.$session['user']['acctid'].' AND at_home = 0';
    $result = db_query($sql) or die(db_error(LINK));
    $rowk = db_fetch_assoc($result);
    if ($rowk['state']>0)
    {
        $newlevel=$rowk['level']+1;
        output("`n`n`^Dein Knappe ".$rowk['name']."`^ steigt auf Level ".$newlevel."`^ auf!`n`n");
        disciple_levelup();
    }

    addnav("Neuer Tagesabschnitt","news.php");
    $sql = "describe accounts";
    $result = db_query($sql) or die(db_error(LINK));
    $hpgain = $session['user']['maxhitpoints'] - ($session['user']['level']*10);

    // Ausrüstung entfernen
    item_set_weapon('`&Fäuste',0,0,0,0,2);
    item_set_armor('`&Hemd',0,0,0,0,2);

    if ($session['user']['goldinbank']<0)
    {
        $session['user']['goldinbank']=round($session['user']['goldinbank']/10);
    }

    $nochange=array("acctid"=>1
    ,"name"=>1
    ,"sex"=>1
    ,"race"=>1
    ,"crace"=>1
    ,"password"=>1
    ,"marriedto"=>1
    ,"charisma"=>1
    ,"title"=>1
    ,"login"=>1
    ,"dragonkills"=>1
    ,"locked"=>1
    ,"loggedin"=>1
    ,"superuser"=>1
    ,"gems"=>1
    ,"gemsfach"=>1
    ,"gemsinbank"=>1
    ,"hashorse"=>1
    ,"gentime"=>1
    ,"gentimecount"=>1
    ,"lastip"=>1
    ,"uniqueid"=>1
    ,"dragonpoints"=>1
    ,"goldinbank"=>($session['user']['goldinbank']<0)?1:0
    ,"laston"=>1
    ,"prefs"=>1
    ,"lastmotd"=>1
    ,"lastmotc"=>1
    ,"emailaddress"=>1
    ,"emailvalidation"=>1
    ,"gensize"=>1
    ,"dragonage"=>1
    ,"donation"=>1
    ,"donationspent"=>1
    ,"donationconfig"=>1
    ,"pvpflag"=>1
    ,"charm"=>1
    ,"house"=>1
    ,"housekey"=>1
    ,"banoverride"=>1 // jt
    ,"beta"=>1
    ,"punch"=>1
    ,"battlepoints"=>1
    ,"reputation"=>1
    ,"petid"=>1
    ,"petfeed"=>1
    ,"rename_weapons"=>1
    ,"marks"=>1
    ,"profession"=>1
    ,"activated"=>1
    ,"guildid"=>1
    ,"guildfunc"=>1
    ,"guildrank"=>1
    ,"expedition"=>1
    ,"balance_dragon"=>1
    ,"surights"=>1
    ,"rpbulb"=>1
    ,"pumpkin_coins"=>1
    ,"witch_coins"=>1
    ,"schillerstr"=>1
    );


    $bestage=$row_extra['bestdragonage'];

    $session['user']['dragonage'] = $session['user']['age'];
    if ($session['user']['dragonage'] <  $row_extra['bestdragonage'] ||        $row_extra['bestdragonage'] == 0)
    {
        $bestage = $session['user']['dragonage'];
    }
    for ($i=0; $i<db_num_rows($result); $i++)
    {
        $row = db_fetch_assoc($result);
        if ($nochange[$row['Field']])
        {

        }
        else
        {
            $session['user'][$row['Field']] = $row["Default"];
        }
    }

    $session['bufflist'] = array();
    $session['user']['gold']=        getsetting("newplayerstartgold",50);

    $session['user']['gold']+=getsetting("newplayerstartgold",50)*$session['user']['dragonkills'];
    if ($session['user']['gold']>(6*getsetting("newplayerstartgold",50)))
    {
        $session['user']['gold']=6*getsetting("newplayerstartgold",50);
        //        $session[user][gems]+=($session[user][dragonkills]-5);
    }

    $points = min($session['user']['dragonkills'], getsetting('maxdp_dk',50) );

    $log = 'DK: Erhält '.$points.' Punkte. Davor: '.$session['user']['donation'];

    $session['user']['donation'] += $points;

    $log .= ' Danach: '.$session['user']['donation'];

    if ($flawless)
    {
        $session['user']['gold'] += 3*getsetting("newplayerstartgold",50);
        $session['user']['gems'] += 1;
        $session['user']['donation']+=$points+5;
        $log .= ' +'.$points.' Zusatzpunkte für Perfekten Kampf';

        if ($session['user']['balance_dragon'] < 0)
        {
            $session['user']['balance_dragon'] = 1;
        }
        else
        {
            $session['user']['balance_dragon']+=2;
        }
        $session['user']['balance_dragon'] = min(20,$session['user']['balance_dragon']);
    }

    debuglog($log);

    // GILDENMOD
    require_once(LIB_PATH.'dg_funcs.lib.php');
    if ($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT)
    {
        $g = &dg_load_guild($session['user']['guildid'],array('points','type','build_list'));
        $session['user']['gold'] = dg_calc_boni($session['user']['guildid'],'player_dkgold',$session['user']['gold']);
        $g['points'] += $dg_points['dk'];
        dg_log($session['user']['login'].' DK: '.$dg_points['dk'].' GP');
        dg_save_guild();
    }
    // END GILDENMOD

    // Drachenkillszähler gesamt inkrementieren
    savesetting('dkcounterges',getsetting('dkcounterges',0)+1);

    $session['user']['maxhitpoints']+=$hpgain;
    // Nach dem DK sollte man nicht tot sein..
    if ($session['user']['maxhitpoints'] < 6) $session['user']['maxhitpoints'] = 6;
    $session['user']['hitpoints']=$session['user']['maxhitpoints'];

    // Handle titles (modded by talion, get rid of these odd name / color code problems by adding additional backup fields for name and title in account_extra_info)

    $newtitle=$titles[$session['user']['dragonkills']][$session['user']['sex']];
    if (empty($newtitle))
    {
        $newtitle = $titles[sizeof($titles)-1][$session['user']['sex']];
    }

    $session['user']['title'] = $newtitle;

    // Name aktualisieren
    user_set_name(0);

    // END handle titles

    while (list($key,$val)=each($session['user']['dragonpoints']))
    {
        if ($val=="at" || $val=='atk')
        {
            $session['user']['attack']++;
        }
        if ($val=="de" || $val == 'def')
        {
            $session['user']['defence']++;
        }
    }

    $session['user']['laston']=date("Y-m-d H:i:s",time());

    output("`2Als du wieder erwachst, bist du umgeben von Bäumen - ein Wald? Du stellst fest, dass eine ziemlich große Beule deinen Hinterkopf ziert, doch wie es
            dazu kam, daran kannst du dich beim besten Willen nicht erinnern. In der Nähe hörst du die Geräusche einer Stadt. Dunkel erinnerst du dich daran,
            dass du ein neuer Krieger bist und irgendwas von einem gefährlichen grünen Drachen gehört hast, der die Gegend heimsucht. Du beschließt, dass du dir
            einen Namen verdienen könntest, wenn du dich vielleicht eines Tages dieser abscheulichen Kreatur stellst.`n
            `n
            `^Du bist von nun an bekannt als `&".$session['user']['name']."`^!`n
            `n
            `&Da du über ".$badguy['creaturename']." `&gesiegt hast, startest du mit einigen Extras. Ausserdem behältst du alle zusätzlichen
            Lebenspunkte, die du verdient oder gekauft hast.`n
            `^Du bekommst FÜNF Charmepunkte für deinen Sieg über ".$badguy['creaturename']."`^!`n");
    addnews("`#".$session['user']['login']."`# hat sich den Titel `&".$session['user']['title']."`# für den erfolgreichen Kampf gegen ".$badguy['creaturename']."`# verdient!");
    $session['user']['charm']+=5;
    $dkname = $session['user']['name'];
    savesetting("newdragonkill",addslashes($dkname));

    // ACCOUNT extra speichern
    user_set_aei(array('sentence'=>0,'mastertrain'=>0,'worms'=>0,'minnows'=>0,'boatcoupons'=>0,'bestdragonage'=>$bestage) );

    // dragonkill ends arenafight
    $sql = "DELETE FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid'];
    db_query($sql) or die(db_error(LINK));


    $res = item_list_get(' owner='.$session['user']['acctid'].' AND (
(loose_dragon = 1 AND (deposit1='.ITEM_LOC_EQUIPPED.' OR deposit1=0))
OR
(loose_dragon = 2)) ' );
    $list = '-1';
    while ($i = db_fetch_assoc($res) )
    {
        $list .= ','.$i['id'];
    }
    item_delete(' id IN ( '.$list.' ) ' );

    if (getsetting("ci_active",0) && getsetting("ci_dk_mail_active",0) && $session['user']['superuser'] >= getsetting("ci_su",0))
    {
        if (getsetting("ci_dk",1) == $session['user']['dragonkills'] )
        {
            systemmail($session['user']['acctid'],getsetting("ci_dk_mail_head","`4Forum"), getsetting("ci_dk_mail_text",""));
        }
    }

    if ($session['user']['dragonkills'] == 1)
    {
        addhistory('`^Erster Drachenkill');
        // Temporär deaktiviert bis wir soweit sind

        //systemmail($session['user']['acctid'],"`4Forum","`&Herzlichen Glückwunsch zum ersten DK!`n`@Du hast jetzt die Möglichkeit Dich fürs Forum anmelden zu lassen.`n`nFalls Du dies willst, gehe einfach zur Vorzimmerdame des Magistrats im Stadtamt und hole dir den Passierschein A38.`n`nmfg Drachenserverteam");
    }
    else if ($session['user']['dragonkills'] == 10)
    {
        addhistory('`^Zehnter Drachenkill');
    }
    else if ($session['user']['dragonkills'] == 100)
    {
        addhistory('`^Hundertster Drachenkill');
    }

    if(!empty($session['user']['race']))
    {
        $arr_race = race_get($session['user']['race'],true);
        race_set_boni(true,false,$session['user']);
    }
}

if ($_GET['op']=="run")
{
    if($badguy['creaturesu'] == 2) {
        output("Du versuchst zu fliehen, doch als etwas mit hoher Geschwindigkeit dicht neben dir in den Boden einschlägt, geht dir auf, dass an Flucht nicht zu denken ist!");
    } else {
        output("Du versuchst zu fliehen, doch als dicht neben dir der Adminhammer in den Boden einschlägt, geht dir auf, dass an Flucht nicht zu denken ist!");
    }
    $_GET['op']="fight";
}
if ($_GET['op']=="fight" || $_GET['op']=="run")
{
    $battle=true;
}
if ($battle)
{
    include("battle.php");
    if ($victory)
    {

            music_set('boss',0);

        $flawless = 0;
        if ($badguy['diddamage'] != 1)
        {
            $flawless = 1;
        }
        $session['user']['badguy']="";
        $session['user']['dragonkills']++;
        $session['user']['reputation']+=2;
        output("`n`FMit einem erstickten Schrei lässt ".$badguy['creaturename']." `F".($badguy['creaturesu'] == 2 ? "die Codeschleuder" : "den Adminhammer")." fallen und sackt zu Boden.");
        addnews("`&".$session['user']['name']."`& hat den Kampf gegen ".$badguy['creaturename']." `&gewagt und gesiegt!");
        addnav("Weiter","dragon_su.php?op=prologue1&flawless=".$flawless);
        $_SESSION['badguy'] = $badguy; #zwischenspeichern
        $badguy=array();
    }
    else
    {
        if ($defeat)
        {

            if ($session['user']['balance_dragon'] > 0)
            {
                $session['user']['balance_dragon']=round($session['user']['balance_dragon']*0.5);
            }
            else
            {
                $session['user']['balance_dragon']--;
            }
            $session['user']['balance_dragon'] = max(-10,$session['user']['balance_dragon']);

            addnav("Tägliche News","news.php");
            $session['user']['reputation']--;
            addnews("`%".$session['user']['name']."`5 hat sich mit ".$badguy['creaturename']." `5angelegt und verloren.");
            $session['user']['alive']=false;

            $str_loose_log = 'Gld: '.$session['user']['gold'];

            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['experience'] *= 0.97;
            output("`b`%".$badguy['creaturename']."`& hat dich besiegt!`n
                    `4Du hast dein ganzes Gold verloren!`n
                    Du kannst morgen wieder kämpfen.`0");

            // item
            $item_hook_info ['min_chance'] = item_get_chance();

            $res = item_list_get(' owner='.$session['user']['acctid'].' AND deposit1=0 AND loose_dragon_death='.$item_hook_info ['min_chance'] , 'ORDER BY RAND() LIMIT 1' );

            if (db_num_rows($res) )
            {

                $item = db_fetch_assoc($res);

                if (item_delete(' id='.$item['id'] ) )
                {
                                        $str_loose_log .= ',Item: '.$item['name'];
                    output('`n`4Du verlierst `^'.$item['name'].'`4!`n');
                }

            }

            $sql = 'SELECT name,sex,state,level FROM disciples WHERE master='.$session['user']['acctid'].' AND at_home=0';
            $result = db_query($sql) or die(db_error(LINK));
            $rowk = db_fetch_assoc($result);

            $kname=$rowk['name'];
            $kstate=$rowk['state'];

            if (($kstate>0) && ($kstate<20))
            {
                output(" `^".$kname." `4 wird nun ".($rowk['name'] ? "ihr" : "sein")." Leben als ".$badguy['creaturename']."`4s Sklave verbringen!`n`n");
                disciple_remove();
                $str_loose_log = ', Knappe';
            }

            debuglog("Drachentod: ".$str_loose_log);

            page_footer();
        }
        else
        {
            fightnav(true,false);
        }
    }
}
page_footer();
?>

