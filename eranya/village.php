
<?php

// 21072004

define('VILLAGECOLORTEXT','`2');
define('VILLAGECOLORHEADER','`G');
define('VILLAGECOLORTIME','`g');
define('VILLAGECOLORWEATHER','`g');
define('VILLAGECOLORDISCIPLE','`p');

require_once 'common.php';

$w = get_weather();

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
addnav('M?Marktplatz','market.php');
addnav('H?Hafen','harbor.php');
addnav('G?Gildenviertel','dg_main.php');
addnav('U?User-Orte','uplaces.php');
if(date('m') == 12 && date('j') < 27) {
        addnav('Besonderes');
        addnav('c?`_Zum Weihnachtsmarkt','weihnachtsmarkt.php');
}
addnav('Klingengasse');
addnav('T?Trainingslager','train.php');
addnav("Z?Zerindos Akademie","academy.php");
if (getsetting('pvp',1))
{
        addnav('n?Ritterturnier','pvparena.php');
}

addnav('Tavernenstrasse');
addnav('F?Zum Lachenden Fass','inn.php',true);
addnav('l?Veteranenkeller', 'rock.php');
addnav('r?Stadtgarten', 'gardens.php');
addnav('J?Jägerhütte','lodge.php');
addnav('a?Seiteneingang (RPP-Shop)','rpp_shop.php');
//if ($session['user']['superuser']>0) addnav('Brunnenmonster','villageevents.php?event=2');
//if ($session['user']['superuser']>0) addnav('In die Gasse','villageevents.php?event=1');

addnav('Abenteurergasse');
addnav('V?Verlassenes Schloss','abandoncastle.php',true);
addnav('S?Stadtamt','dorfamt.php');
addnav('K?Kerker','prison.php');
addnav('e?Weiher','pool.php');
//Adding the Villageparty
if(getsetting ('lastparty',0)>time())
{
        addnav('P?Das Stadtfest','dorffest.php');
}
else {addnav('P?Zur Festwiese','dorffest.php?op=nofestival');}

addnav('Information');
addnav('D?`^Drachenbücherei`0','library.php');
addnav('i?Einwohnerliste','list.php');
addnav('N?Neuigkeiten','news.php');
addnav('Wegweiser','guide.php');
addnav('Profil','prefs.php',false,false,true,false);

if(su_check(SU_RIGHT_GROTTO)) {
    addnav('Admin-Grotte');
    addnav('X?`bAdmin-Grotte`b','superuser.php');
}

addnav('Stadt verlassen');
addnav('-?Vor die Stadt','fields.php');

page_header('Stadtplatz');
output(VILLAGECOLORHEADER.'`c`bDer Stadtplatz '.getsetting('townname','Eranya').'s`b`c`n');
output(VILLAGECOLORTEXT.'Der Stadtplatz ist der symbolische Mittelpunkt einer Stadt, auch wenn dies nicht immer von den Bewohnern so aufgegriffen wird.
Hier in Eranya kann man es allerdings durchaus spüren, denn auch wenn sich die Bewohner hier gern aufhalten und es so meistens recht lebendig zugeht,
hat der Platz etwas Erhabenes. Denn auch wenn viel geschäftiges Treiben neben den müßigen Besuchen herrscht, so scheint auch der eiligste Bote seine
Schritte auf dem Platz ein wenig zu verlangsamen, wie um die Präsenz des Ortes nicht zu stören.`n
Viele Straßen und Gassen führen hierhin, sodass man sich schlecht verlaufen kann in dieser Stadt, denn meist findet man ja doch wieder hierhin zurück.
In der Mitte des dunkelgrau gepflasterten Platzes erhebt sich ein hoher Brunnen, ein Kunstwerk, das der Stadt einmal gespendet worden ist. Ein großer,
schlanker Baum aus hellem Marmor, dessen Äste und Zweige recht dicht belaubt sind, doch man vermag trotz allem die vielen Vögel und das ein oder andere
Eichhörnchen zu erkennen, aus dessen Schnäbeln und Mündern das Wasser hinunterplätschert. Das Geräusch vermengt sich mit den Stimmen der Besucher, die in
kleinen Grüppchen zusammen stehen oder langsam und gemütlich umherflanieren. Der ein oder andere sitzt auch auf den Bänken, die aus dem gleichen Stein
wie der Brunnen sind und breite, hohe Lehnen, sowie geschwungene Armstützen besitzen. Sie sind in einem Halbkreis am Rand des Platzes um den Brunnen
gestellt und werden von ein paar großen Laubbäumen flankiert. Auch sie schmückt dasselbe Symbol wie den Brunnenrand: ein breites Band, das sich zu einem
- mal mehr, mal weniger komplexen - Unendlichkeitsknoten verformt.`n
Die Öffnung dieses Halbkreises ist direkt auf das Stadtamt gerichtet, ein großes, beinahe herrisches Gebäude, mit leichtem Zierrat und hohen
Bogenfenstern, was allein bei seinem Anblick Respekt zu erheischen scheint. Hier arbeiten viele wichtige Bürger der Stadt und unzählige Entscheidungen
werden jeden Tag getroffen.`n
Die anderen Gebäude gehen ein wenig unter, da das Stadtamt die Blicke stets auf sich zieht, doch auch hier lässt sich einiges durchaus Interessantes
finden, wenn man nur einmal genauer hinblicken wollte. Und auch eine andere Kleinigkeit geht ein wenig unter, die man aber durchaus entdecken könnte,
wenn man aufmerksam genug wäre: Am Rand steht ein seltsam leuchtender Felsen, auf dem ständig neue Nachrichten aufflackern, wie von Zauberhand...`0');

// RP-News abrufen & anzeigen:
$rownewsrp = db_fetch_assoc(db_query("SELECT newsrptext FROM newsrp WHERE archiv = 0 ORDER BY RAND() LIMIT 1"));
output('`n`n`c`i`t'.$rownewsrp['newsrptext'].'`i`c`n');
// end

switch(e_rand(1,1500))
{
        case 50 :
                //  case 51 :
                redirect('villageevents.php');
                break;
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
}

if (getsetting('activategamedate','0')==1) output(''.VILLAGECOLORTEXT.'Wir schreiben den '.VILLAGECOLORTIME.''.rpdate().''.VILLAGECOLORTEXT.' im Zeitalter des Drachen.`n');
output(''.VILLAGECOLORTEXT.'Die magische Sonnenuhr zeigt '.VILLAGECOLORTIME.''.date('G:i').''.VILLAGECOLORTEXT.'. ');
output(''.VILLAGECOLORTEXT.'Das heutige Wetter: '.VILLAGECOLORWEATHER.''.$w['name'].''.VILLAGECOLORTEXT.'. ');
/*// Ostern-Worträtsel:
if((date('d.m.') == '27.03.' && date('G') >= '10') || date('d.m.') == '28.03.') {
    $str_ostern_section = getsetting('ostern_section','market');
    $arr_oldsections = explode(';',getsetting('ostern_sections_alt',' '));
    output('`n`n`c`&Ein Mann im weißen Hasenkostüm stolziert über den Platz und hält dabei ein Schild in die Höhe:`n
            `^"`2Rä`@ts`^el`Qsp`Caß `%zu `VOs`9te`1rn! `^Tipp zum neusten Ort:`n`i'.$arr_section_eggs[$str_ostern_section]['hint'].'`i"');
    if(count($arr_oldsections) > 0) {
        output('<script type="text/javascript" language="JavaScript" src="templates/plumi.js"></script>
                `n`n<a href="#"'.set_plumi_onclick('easter').'>'.set_plumi_img('easter').' `&Bisherige Tipps:</a>`n
                <div style="border: none; padding: 5px; text-align: center; display: none;" id="easter">');
        foreach($arr_oldsections AS $v) {
            output('`s'.$arr_section_eggs[$v]['hint'].';`n');
        }
        output('</div>');
    }
    output('`c');
}
// end */

// Superhelden/-schurken-Special
if(getsetting('forest_fightagainstspecialmonsters',0) == 1) {
    $int_beaten_heroes = getsetting('forest_fightagainstspecialmonsters_beaten_heroes',0);
    $int_beaten_villains = getsetting('forest_fightagainstspecialmonsters_beaten_villains',0);
    output('`n`n`c`^Zwischenstand in der`n`bSchlacht zwischen `9Gut `^und `4Böse`b`^:`n`n<table><tr><td style="border: 2px ridge #ffff00; padding: 0.5em 1em;">`FErschlagene Helden: `9'.$int_beaten_heroes.'</td><td style="border: 2px ridge #ffff00; padding: 0.5em 1em;">`FErschlagene Schurken: `4'.$int_beaten_villains.'</td></tr></table>`c');
}
// end

$sql = 'SELECT disciples.name AS kname,disciples.level AS klevel,disciples.sex AS ksex,accounts.name AS master FROM disciples LEFT JOIN accounts ON accounts.acctid=disciples.master WHERE best_one > 0 OR disciples.level = 45 ORDER BY RAND() LIMIT 1';
$result = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result)>0) {
        $rowk = db_fetch_assoc($result);
        output("`n`n".VILLAGECOLORTEXT."Eine kleine Statue ehrt ".VILLAGECOLORDISCIPLE."".$rowk['kname']."".VILLAGECOLORTEXT.", eine".($rowk['ksex'] ? " Knappin" : "n Knappen")." der ".$rowk['klevel'].". Stufe, ".($rowk['ksex'] ? "die" : "der")." zusammen mit ".$rowk['master']."".VILLAGECOLORTEXT." gegen den grünen Drachen auszog.");
}

output('`n`n'.VILLAGECOLORTEXT.'In der Nähe reden einige Bewohner:`n');
viewcommentary('village','Hinzufügen',25);
output('`i`7(Info: Dieser RP-Ort kann nicht durch einen cut für andere Spieler gesperrt werden.)`i`n`n');
page_footer();
?> 
