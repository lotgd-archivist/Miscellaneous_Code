
<?php
/**
 * Der Dorfplatz ist die zentrale Anlaufstelle des Spiels.
 * Von hier aus kommt man zu allen weiteren Spielebenen
 */

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

if ($session['user']['alive']==0)
{
    redirect('shades.php');
}
if($session['user']['prangerdays']>0){
    redirect("pranger.php");
}

//if($session['user']['namecheck']== 0){
//   redirect("dorftor.php");
//}


$sql='SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1='.$session['user']['acctid'].' OR acctid2='.$session['user']['acctid'];
$result = db_query($sql);
$row = db_fetch_assoc($result);
if(($row['acctid1']==$session['user']['acctid'] && $row['turn']==1) || ($row['acctid2']==$session['user']['acctid'] && $row['turn']==2))
{
    redirect('pvparena.php');
}

if (getsetting('automaster',1) && $session['user']['seenmaster']!=2)
{
    $expreqd = get_exp_required($session['user']['level'],$session['user']['dragonkills'],true);
    if ($session['user']['experience']>$expreqd && $session['user']['level']<15)
    {
        redirect('train.php?op=autochallenge');
    }
    elseif ($session['user']['experience']>$expreqd && $session['user']['level']>=15 && e_rand(1,3) == 3 )
    {
        redirect('boss.php?boss=green_dragon&op=autochallenge');
    }
}

//Load specials
spc_get_special('village',1);

//    $session['user']['specialinc']='';
$session['user']['specialmisc']='';//salator: Das bitte drinlassen, nutze ich für Dunkle Gasse und Grabraub

clearoutput();

// Muss nach clearoutput stehen!
music_set('dorfplatz');

$w = Weather::get_weather();
addnav('');
addnav('Das Tor','dorftor.php');
addnav('Waldwege','weg.php');
addnav('Strandwege','swege.php');
addnav('Treffpunkte');
addnav('U?Unterschlupf','houses.php');
addnav('P?Das Paradies','gardens.php');
addnav('B?Basar','market.php');
addnav('Altstadt','stadt.php');
//addnav('Rummel','rummel.php');

if (($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ENTER)) || ($session['user']['expedition']>0))
{
    addnav('Expedition','expedition.php');
}



addnav('u?Raum des Lernens','library.php?op=rp_train');
    
addnav('Klingengasse');
addnav('T?Trainingslager','train.php');
addnav("c?Warchilds Akademie","academy.php");
if (getsetting('pvp',1))
{
    addnav('A?Die Arena','pvparena.php');
}
addnav('K?Der Kerker','prison.php');

addnav('Tavernenstraße');
addnav('Schenke zum glücklichen Wolf','inn.php',true);
addnav('J?Jägerhütte','lodge.php');
addnav('F?Seltsamer Felsen', 'rock.php');

addnav('Abenteurergasse');
addnav('R?Rudelviertel','dg_main.php');
//addnav('V?Verlassenes Schloss','abandoncastle.php',true);
addnav('s?Waldsee','pool.php');

addnav('`bSonstige Orte`b');
//Adding the Villageparty
if((getsetting ('lastparty',0)>time()) || getsetting('party_force_party',0) == 1)
{
    addnav('P?Das Fest','dorffest.php');
}
else
{
    addnav('i?Die Festwiese','dorffest.php?op=meadow');
}
addnav('Information');
addnav('altes Rathaus','rhaus.php');
addnav('Stadtamt','dorfamt.php');
addnav('`^Wolfsbücherei`0','library.php');
addnav('l?Wolfliste','list.php');
addnav('T?Wolf Times','news.php');
addnav('OoC-Raum','ooc.php?op=ooc');
if($session['user']['prefs']['showinvent'])
{
    addnav('B?Dein Beutel','invent.php?r=1');
    addnav('Profileinstellungen','prefs.php',false,true);
}

if($access_control->su_check(access_control::SU_RIGHT_GROTTO)) {
    addnav('Admin');
    addnav('X?`bAdmin Grotte`b','superuser.php');
}

if($access_control->su_check(access_control::SU_RIGHT_LIVE_DIE))
{
    addnav('Lemming spielen','superuser.php?op=iwilldie',false,false,false,false,'Möchtest Du Dich wirklich von der hohen Klippe gen Ramius Stürzen?');
}

if ($access_control->su_check(access_control::SU_RIGHT_DEV)) {
    if (@file_exists('test.php'))
    {
        addnav('Test','test.php',false,false,false,false);
    }
}
//if($access_control->su_check(access_control::SU_RIGHT_NEWDAY)) {
//    addnav('Neuer Tag (für SU)','superuser.php?op=newday',false,false,false,false,'Möchtest Du wirklich einen neuen Tag beginnen?');
//}
addnav('Logout');
addnav('#?In die Felder','login.php?op=logout',true);

page_header('Stadtplatz');
$session[user][standort]="Stadtplatz";
$str_output .= '`c`b`@Stadtplatz '.getsetting('townname','Atrahor').'s`0`b`c`n

`@Vor dir liegt der Stadtplatz: An seiner Nordseite grenzt er zwar direkt an den Wald, wird aber auch von großen Gebäuden umgeben. 
In alle Richtungen führen von verschiedensten Wesen bevölkerte Wege und Pfade, über die du zu anderen Orten und Häusern '.getsetting('townname','Atrahor').'s gelangst.
Unzählige Bänke bieten dir eine Gelegenheit zur Rast und in der Mitte des Platzes lädt ein Brunnen dazu ein, dich mit klarem Quellwasser zu erfrischen.`n
`^Ein Schild verbietet das Blankziehen von Waffen auf dem Statdplatz unter Androhung von Kerkerhaft.`n
`@Ein ungewöhnlicher Felsen am Platzrand zeigt immer die neusten Geschehnisse im ganzen Reich:';
$sql = "SELECT * FROM news WHERE onlyuser=0 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$str_output .= '`n`n`c`i'.$row['newstext'].'`0`i`c`n';

switch(e_rand(1,1500))
{
    case 100 :
    case 101 :
        if($session['user']['gems']<500)
        {
            $str_output .= '`n`^Du findest einen Edelstein vor dir auf dem Boden, den du natürlich sofort einsteckst!`n`n`@';
            $session['user']['gems']++;
        }
        else
        {
            $str_output .= '`n`$Dir fällt ein Edelstein aus der Tasche, was du jedoch erst später bemerkst. Den Edelstein zu suchen ist aussichtslos, den hat sicher schon jemand anderes gefunden.`n`n`@';
            $session['user']['gems']--;
        }
        break;
    case 150 :
    case 151 :
    case 152 :
        if ($session['user']['gold']>0)
        {
            $goldlost=ceil($session['user']['gold']*0.15);
            $str_output .= '`n`4Jemand rempelt dich an und entfernt sich unter wortreicher Entschuldigung rasch. Dann stellst du fest, dass man dir '.$goldlost.' Gold gestohlen hat!`n`n`@';
            $session['user']['gold']-=$goldlost;
            debuglog('wurde von Taschendieben um '.$goldlost.' Gold erleichtert');
        }
        break;
    case 200 :
    case 201 :
    case 202 :
        if ($session['user']['turns']>0)
        {
            $str_output .= '`n`^Jemand kommt dir gut gelaunt entgegen gelaufen und reicht dir ein Ale. Deine Laune bessert sich dadurch und du hast heute eine Runde mehr!`n`n`@';
            $session['user']['turns']++;
        }
        break;
    case 250 :
    case 251 :
        $str_output .= '`n`4Jemand rennt eilig vor einer Stadtwache davon und stößt dich grob bei Seite, da du ihm im Weg stehst. Du stürzt und landest mit dem Gesicht in einem Kuhfladen. Leute drehen sich zu dir um und zeigen lachend auf dich. Du verlierst einen Charmepunkt!`@`n`n';
        $session['user']['charm']=max(0,$session['user']['charm']-1);
        break;
}

if (getsetting('activategamedate','0')==1)
{
$realdatum = time();
$datum = date('d-m',$realdatum);
$wann = 1971; //Das Datum vor wie vielen Jahren? 
$start = mktime(0,0,0,1,1,2006); //Irgendwo solls ja anfange, ne? 
$jetzt = time(); //Momentane Zeit 
$diff = getdate($jetzt-$start);//Die Differenz  
$y = $diff['year'] - $wann;//und das Jahr 
    $str_output .= '`TWir schreiben den `l'.$datum.'-'.$y.'`T im Zeitalter des Wolfes.`n';
}
$date = date("G:i");
$str_output .= '`tDie magische Sonnenuhr zeigt mit ihren Wolfszeiger `l'.$date.'`t. `n';
$str_output .= '`gDas heutige Wetter: `Y'.$w['name'].'`g. ';

//Abfrage auf best_one=1 gesetzt, damit es keine Probleme mit best_one=2 für untote Knappen gibt
$sql = 'SELECT disciples.name AS name,disciples.level AS level ,accounts.name AS master FROM disciples LEFT JOIN accounts ON accounts.acctid=disciples.master WHERE best_one=1 LIMIT 1';
$result = db_query($sql);
if (db_num_rows($result)>0) {
    $rowk = db_fetch_assoc($result);

    $str_output .= '`n`n`0Eine kleine Statue ehrt `q'.$rowk['name'].'`0, einen Knappen der '.$rowk['level'].'. Stufe, der zusammen mit '.$rowk['master'].'`0 eine Heldentat vollbrachte.';
}

$str_output .= '`n`n`0In der Nähe reden einige Bewohner:`n';

output($str_output);
viewcommentary('village','Hinzufügen',5);
page_footer();
?>

