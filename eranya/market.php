
<?php

// Splittet den Dorfplatz
// by Maris (Maraxxus@gmx.de)

define('MARKETCOLORTEXT','`X');
define('MARKETCOLORHEADER','`Y');
define('MARKETCOLORTIME','`F');
define('MARKETCOLORWEATHER','`F');
define('MARKETCOLORWALL','`&');

require_once 'common.php';

$show_ooc = true;
$show_invent = true;

addcommentary();
checkday();

if ($session['user']['alive']==0)
{
    redirect('shades.php');
}

$sql='SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1='.$session['user']['acctid'].' OR acctid2='.$session['user']['acctid'];
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if (($row['acctid1']==$session['user']['acctid'] && $row['turn']==1) || ($row['acctid2']==$session['user']['acctid'] && $row['turn']==2))
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
    else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15 && e_rand(1,3) == 3 )
    {
        redirect('dragon.php?op=autochallenge');
    }
}
$session['user']['specialinc']='';
$session['user']['specialmisc']='';
addnav('');
addnav('W?Wald','forest.php');
addnav('o?Wohnviertel','houses.php');
addnav('S?Stadtplatz','village.php');
addnav('H?Hafen','harbor.php');
addnav('G?Gildenviertel','dg_main.php');
addnav('U?User-Orte','uplaces.php');

addnav('Marktplatz');
addnav('h?Riyads Wagenstand','vendor.php');
addnav('W?Yaris\' Waffen','weapons.php');
addnav('R?Thoyas Rüstungen','armor.php');
addnav('S?Keldorns Ställe','stables.php');
addnav('c?Bäcker','baecker.php');
addnav('l?Blumenmädchen','blumenmaedchen.php');
addnav('B?Die alte Bank','bank.php');
addnav('Z?Scythas Zelt','gypsy.php');
addnav("Bettelstein","beggar.php");
if ($session['user']['superuser']) addnav('F?Nimbus\' Friseursalon','barber.php');

//if($session['user']['acctid'] == 979 || $session['user']['acctid'] == 985) {
addnav('Zwielichtiger Händler','baecker.php?op=market_inside');//}
                  
//Adding the Villageparty
if (getsetting('lastparty',0)>time())
{
    addnav('P?Das Stadtfest','dorffest.php');
}
else {addnav('P?Zur Festwiese','dorffest.php?op=nofestival');}

addnav('Information');
addnav('D?`^Drachenbücherei`0','library.php');
addnav('l?Einwohnerliste','list.php');
addnav('N?Neuigkeiten','news.php');
addnav('Wegweiser','guide.php');

if(su_check(SU_RIGHT_GROTTO)) {
    addnav('Admin-Grotte');
    addnav('X?`bAdmin-Grotte`b','superuser.php');
}

addnav('Stadt verlassen');
addnav('-?Vor die Stadt','fields.php');

page_header('Marktplatz');
output(''.MARKETCOLORHEADER.'`c`bDer Marktplatz '.getsetting('townname','Atrahor').'s`b`c`n');
output(MARKETCOLORTEXT.'Der Marktplatz ist wohl das Zentrum des Lebens eines jeden Ortes. Hier versammeln sich die Bewohner um zu kaufen und zu
verkaufen, sich zu treffen, zu klatschen und zu tratschen und um einfach andere Leute beobachten zu können. Den ein oder anderen wird es auch mit
unlautereren Absichten hierhin verschlagen, doch das ist eine andere Sache.`n
Dieser Marktplatz ist wie die meisten anderen auch oftmals belebt, zu fast jeder Stunde bietet jemand seine Waren feil, wobei zur Morgenstund natürlich
die meisten Geschäfte getätigt werden, wie es bei redlich arbeitendem Volke üblich ist. Viele verschiedene Händler streiten sich um die besten
Standplätze, die meist eher ein wenig chaotisch verteilt werden und vielleicht findet sich hier und da am Rande ein kleines Blumenmädchen, was
schüchtern versucht kleine Sträußchen mitleidigen Passanten für ein paar Münzen zu verkaufen.`n
Der Boden des ganzen Platzes besteht nicht, wie bei ärmeren Städtchen und Dörfern üblich, aus festgetretener Erde, sondern wurde mit dunkel braun-roten
Pflastersteinen belegt, sodass man die Schritte der hochhackigen Schuhe der Damen weithin zu hören vermag. Außen herum gibt es viele Geschäfte im
Erdgeschoss der vielen zwei und dreistöckigen Häuser, deren Besitzer sich mehr leisten können, als einen Marktstand. Besonders jene Geschäftsinhaber,
die ein wenig mit ihrem Reichtum prahlen wollen, haben sich die eigene Profession verewigen lassen - in Form eines Symbols mit kleinen, bunten Steinen
vor den Boden ihres Eingangs gelegt. So sind die alt eingesessenen Geschäfte, die einen guten Ruf haben leicht zu erkennen - denn nicht jedem wird die
Erlaubnis gegeben, ein solches Emblem legen zu lassen, nur weil er genügend Geld hat.`n
Natürlich herrscht auf dem Platz fast immer ein mehr oder minder reges Treiben, was viele Geräusche und Gerüche mit sich bringt. Wer etwas erleben will
und dabei nicht gerade Kopf und Kragen riskieren will, ist beim Marktplatz gewiss nicht an der falschen Adresse.`n
Und eine kleine Besonderheit wird einem gewiss irgendwann ins Auge stechen - ');
$sql = "SELECT * FROM news ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output(''.MARKETCOLORTEXT.'am Rande des Platzes steht ein seltsam leuchtender Felsen, auf dem ständig neue Nachrichten aufflackern, wie von
       Zauberhand...`0`n`n`c`i'.$row['newstext'].'`i`c`n');

switch (e_rand(1,1500))
{
    //  case 50 :
    //  case 51 :
    //  redirect('villageevents.php');
    //  break;
case 100 :
case 101 :
    output('`n`^Du findest einen Edelstein vor dir auf dem Boden, den du natürlich sofort einsteckst!`n`n`@');
    $session['user']['gems']++;
    break;
case 150 :
case 151 :
case 152 :
    if ($session['user']['gold']>0)
    {
        output('`n`4Jemand rempelt dich an und entfernt sich unter wortreicher Entschuldigung rasch. Dann stellst du fest, dass man dir '.(int)($session['user']['gold']*0.15).' Gold gestohlen hat!`n`n`@');
    }
    (int)$session['user']['gold']*=0.85;
    break;
case 200 :
case 201 :
case 202 :
    if ($session['user']['turns']>0)
    {
        output('`n`^Jemand kommt dir gut gelaunt entgegen gelaufen und reicht dir ein Ale. Deine Laune bessert sich dadurch und du hast heute eine Runde mehr!`n`n`@');
        $session['user']['turns']++;
    }
    break;
case 250 :
case 251 :
    output('`n`4Jemand rennt eilig vor einer Stadtwache davon und stößt dich grob bei Seite, da du ihm im Weg stehst. Du stürzt und landest mit dem Gesicht in einem Kuhfladen. Leute drehen sich zu dir um und zeigen lachend auf dich. Du verlierst einen Charmepunkt!`@`n`n');
    $session['user']['charm']--;
    break;

default:
    if($session['user']['age'] == 1 && !$session['reloffered'] && e_rand(1,4) == 1) {    // Gerade Drachenkill gemacht
        // Überprüfen, ob noch nicht alle Reliquien vergeben, unser Freund noch keinen Steckbrief und auch noch kein Angebot bekommen hat
        if( item_count('tpl_id="drstb" AND owner='.$session['user']['acctid']) == 0 && item_count('tpl_id="drrel_ksn" OR tpl_id="drrel_gld"') < 2) {
            redirect('marketevents.php?op=rel');    
        }
    }
    
}

$w = get_weather();

if (getsetting('activategamedate','0')==1)
{
    output(''.MARKETCOLORTEXT.'Wir schreiben den '.MARKETCOLORTIME.''.rpdate().''.MARKETCOLORTEXT.' im Zeitalter des Drachen.`n');
}
output(''.MARKETCOLORTEXT.'Die Uhr an einer großen Säule zeigt '.MARKETCOLORTIME.''.date('G:i').''.MARKETCOLORTEXT.'. ');
output(''.MARKETCOLORTEXT.'Das heutige Wetter: '.MARKETCOLORWEATHER.''.$w['name'].''.MARKETCOLORTEXT.'.');


// Die Mauer (by Maris)
$message=getsetting('wall_msg','0');
$time=getsetting('wall_chgtime','0');

$oldtime=(strtotime($time));
$acttime=(strtotime(date('H:i:s')));
$newtime=$acttime-$oldtime;
//Farbe bereits trocken ?

$wallchangetime=getsetting('wallchangetime','300');
//Zeit zwischen den Änderungen

output('`n`n'.MARKETCOLORWALL.'Dein Blick fällt auf eine hüfthohe Mauer aus weißen Ziegeln. ');
if ($message=='0')
{
    output('Sie muss gerade frisch angestrichen worden sein.`n');
    if ($newtime>$wallchangetime)
    {
        output("<a href='whitewall.php?op=write'>Die Mauer beschmieren</a>");
        addnav('','whitewall.php?op=write');
    }
}
else
{
    output('Jemand hat Folgendes in großen Buchstaben darauf geschmiert:`n`^'.$message.'`n`0');
    if ($newtime>$wallchangetime)
    {
        output('<a href="whitewall.php?op=write">Überschmieren</a>');
        $author=getsetting('wall_author','0');
        if ($session['user']['login']!=$author)
        {
            output(' | ');
            output('<a href="whitewall.php?op=change">Verändern</a>');
            addnav('','whitewall.php?op=change');
        }
        addnav('','whitewall.php?op=write');
    }
}
if($newtime>7200) //Nach 2 Stunden wird die Mauer neu gestrichen (gelöscht)
{
    savesetting('wall_author','0');
    savesetting('wall_chgtime',date('Y-m-d H:i:s'));
    savesetting('wall_msg','0');
}
if ($_GET['op']=='toolate')
{
    if ($newtime<60)
    {
        output('`4Es muss dir jemand zuvor gekommen sein. Die Farbe ist zu feucht um jetzt überschrieben zu werden.`0`n');
    }
    else
    {
        redirect('market.php');
    }
}
// Mauer Ende

output('`n`n'.MARKETCOLORTEXT.'In der Nähe hörst du einige Leute schwatzen:`n');
viewcommentary('marketplace','Hinzufügen',25);
output('`i`7(Info: Dieser RP-Ort kann nicht durch einen cut für andere Spieler gesperrt werden.)`i`n`n');
page_footer();
?> 
