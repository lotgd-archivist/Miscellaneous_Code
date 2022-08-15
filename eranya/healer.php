
<?php

//Der Heiler

require_once 'common.php';
$str_output .=  '';
$config = unserialize($session['user']['donationconfig']);
if ($config['healer'] || $session['user']['marks']>30 || $session['user']['acctid']==getsetting('hasegg',0))
{
    $alyssa = true;
}

page_header('Bei den zwei Heilern');
$str_output .= '`#`b`cBei den zwei Heilern`c`b`n';
$loglev = log($session['user']['level']);
// MaxHP-Wert ermitteln
$mount_buff = $session['bufflist']['mount'];
$max_hp = ($mount_buff['liferaise'] > 0 ? ($session['user']['maxhitpoints']*$mount_buff['liferaise']) : $session['user']['maxhitpoints']);
// end
$cost = ($loglev * ($max_hp-$session['user']['hitpoints'])) + ($loglev*10);
if ($alyssa)
{
    //Bitshift ist viel schneller als Division durch 2
    $cost = $cost >> 1 ;
}
$cost = round($cost,0);

if ($_GET['op']=='')
{
    checkday();
    if ($session['user']['hitpoints'] < $max_hp)
    {
        $str_output .= '`3Die Stadt liegt schon ein ganzes Stück weit weg, als dir mit einem Mal eine kleine Rauchfahne zwischen den Bäumen auffällt, noch recht nahe
                        am Waldrand. Neugierig näherst du dich und entdeckst einen alten Wagen, der dich im ersten Moment an eine Zirkustruppe denken lässt, wäre da
                        nicht ein deutlicher Hinweis in Form eines ins Wagenholz geschnitzten Stabs, um den sich zwei Schlangen winden. Die Räder sind festgemacht und es macht
                        nicht den Eindruck, als hätte jemand den Wagen in der letzten Zeit bewegt, dafür ist die Vegetation ringsum schon zu sehr um die hölzernen
                        Räder geschlungen. Von der offen stehenden Tür des Wagens führt eine kleine Treppe hinab. Auf dieser sitzt ein junger Mann mit rotblondem
                        Haar und Sommersprossen und sieht zu dir hinüber. Du hast schon von ihm gehört, es muss sich bei ihm um den Heiler handeln. Wie war noch gleich der
                        Name?`n
                        Ehe du darauf eine Antwort findest, erklingt eine glockenhelle Stimme, die dich freundlich begrüßt. Sie gehört Alyssa, wie sich die junge
                        Frau dann vorstellt. Du musst sie glatt übersehen haben. Oder ist sie erst gerade zwischen den Bäumen hervor getreten?`n
                        In jedem Fall sind die beiden wohl darauf spezialisiert, Verletzungen zu heilen, und würden auch dir diese Dienste gegen das entsprechende
                        Entgelt zur Verfügung stellen. ';
        if ($alyssa)
        {

            $str_output .=  'Du zählst zu Alyssas exklusiver Kundschaft, deshalb würde dich eine vollständige Heilung `^'.$cost.' Goldstücke `3kosten. ';
        }
        else
        {
            $str_output .=  'Da dich Alyssa allerdings kaum beachtet, wendest du dich dem Heiler zu. Hier würde dich eine vollständige Heilung `^'.$cost.'
                             Goldstücke `3kosten. ';
        }
        $str_output .= 'Du kannst jedoch auch einen günstigeren und dafür weniger wirkungsvollen Trank kaufen.`n`n';
        output($str_output);
        unset($str_output);
        addnav('Heiltränke');
        addnav('`^Komplette Heilung`0','healer.php?op=buy&pct=100');
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        /*addnav('RP');
        addnav('In den Behandlungsraum','healer.php?op=rport');*/
        addnav('`bZurück`b');
        addnav('..in den Wald','forest.php');
        addnav('..in die Stadt','village.php');
        addnav('..zum Marktplatz','market.php');
        addnav('..zur Arena','pvparena.php');
    }
    else
    {
        $str_output .= '`3Die Stadt liegt schon ein ganzes Stück weit weg, als dir mit einem Mal eine kleine Rauchfahne zwischen den Bäumen auffällt, noch recht
                        nahe am Waldrand. Neugierig näherst du dich und entdeckst einen alten Wagen, der dich im ersten Moment an eine Zirkustruppe denken lässt, wäre da
                        nicht ein deutlicher Hinweis in Form eines ins Wagenholz geschnitzten Stabs, um den sich zwei Schlangen winden. Die Räder sind festgemacht und es
                        macht nicht den Eindruck, als hätte jemand den Wagen in der letzten Zeit bewegt, dafür ist die vegetation ringsum schon zu sehr um die
                        hölzernen Räder geschlungen. Von der offen stehenden Tür des Wagens führt eine kleine Treppe hinab. Auf dieser sitzt ein junger Mann mit
                        rotblondem Haar und Sommersprossen und sieht zu dir hinüber. Du hast schon von ihm gehört, es muss sich bei ihm um den Heiler handeln. Wie war
                        noch gleich der Name?`n
                        Ehe du darauf eine Antwort findest, erklingt eine glockenhelle Stimme, die dich freundlich begrüßt. Sie gehört Alyssa, wie sich die junge
                        Frau dann vorstellt. Du musst sie glatt übersehen haben. Oder ist sie erst gerade zwischen den Bäumen hervor getreten?`n
                        In jedem Fall sind die beiden wohl darauf spezialisiert, Verletzungen zu heilen und würden auch dir diese Dienste gegen das entsprechende
                        Entgelt zur Verfügung stellen.`n`n
                        Allerdings brauchst du aktuell gar keine Hilfe mit Verletzungen. Also bleibst du noch einen Moment bei den beiden, ehe du dich
                        wieder auf deinen Weg machst. So oder so ist es beruhigend zu wissen, dass sie da sind, nicht wahr?`n`n';
        /*addnav('RP');
        addnav('In den Behandlungsraum','healer.php?op=rport');
        addnav('Heiler');*/
        output($str_output);
        unset($str_output);
        forest(true);
    }
/*} elseif($_GET['op'] == 'rport') {
    output($str_output.'`3Du wendest dich einem Raum zu, von dem du weißt, dass es einer der wenigen Behandlungsräume ist. In dessen Inneren findest du zuerst lauter gläserne
            Gefäße mit Flüssigkeiten jeglicher Farbe, manche brodelnd und manche dampfend. In der hinteren linken Ecke befindet sich ein sporadisch aufgebautes
            Nachtlager, für etwaige Notfälle. Lediglich an der gegenüberliegenden Seite ist ein kleines Fenster, welches nur wenig Sonnenlicht hinein lässt.
            In der Mitte des Raumes ist eine kleine Feuerstelle, meistens mit einem Kessel, in dem etwas merkwürdiges vor sich hin köchelt, darüber. Außerdem
            gibt es noch einen kleinen, durch lange, weiße Tücher abgetrennten Bereich für den Heiler selbst.`n`n');
    unset($str_output);
    addcommentary();
    viewcommentary('healer_rp','Sagen',15,'sagt');
    addnav('Zurück');
    addnav('In den Hauptraum','healer.php');
    addnav('In den Wald','forest.php');
    addnav('Zur Stadt','village.php'); */
}
else
{
    $newcost=round($_GET['pct']*$cost/100,0);
    if ($session['user']['gold']>=$newcost)
    {
        $session['user']['gold']-=$newcost;
        //debuglog("spent $newcost gold on healing");
        $diff = round(($max_hp-$session['user']['hitpoints'])*(intval($_GET['pct'])/100),0);
        $session['user']['hitpoints'] += $diff;
        $str_output .= '`3Du zahlst den Preis von `^'.$newcost.' Goldstücken `3und bekommst deinen Trank ausgehändigt, den du direkt leerst. Sofort fühlst du dich
                        besser';
        if ($alyssa)
        {
            $str_output .= '`3, deshalb dankst du Alyssa für ihre Dienste';
        }
        $str_output .=  ' und ziehst mit neuen Kräften deiner Wege.`n`n`#Du wurdest um '.$diff.' Punkte geheilt!';
        if ($_GET['pct']==100 && $session['user']['dragonkills']>3 && e_rand(1,2)==2 && $session['user']['reputation']>0)
        {
            $session['user']['reputation']--;
        }
        output($str_output);
        unset($str_output);
        forest(true);
    }
    else
    {
        $str_output .= '`3Du bittest um '.($alyssa ? 'Alyssas Hilfe' : 'die Hilfe des Heilers').', allerdings übersteigt der Preis von `^'.$newcost.' Goldstücken
                        `3deine finanziellen Mittel. Da bleibt dir nichts anderes übrig, als entweder auf eine Heilung zu verzichten oder einen weniger wirksamen
                        und dafür billigeren Trank zu kaufen.`n`n
                        Eine vollständige Heilung würde dich '.$cost.' Goldstücke kosten, allerdings kannst du auch einen der weniger wirkungsvollen, dafür aber
                        günstigeren Tränke kaufen.';
        addnav('Heiltränke');
        addnav('`^Komplette Heilung`0','healer.php?op=buy&pct=100');
        for ($i=90;$i>0;$i-=10)
        {
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav('`bZurück`b');
        addnav('..in den Wald','forest.php');
        addnav('..in die Stadt','village.php');
        addnav('..zum Marktplatz','market.php');
        addnav('..zur Arena','pvparena.php');
    }
}
if(isset($str_output))
{
    output($str_output);
}
page_footer();
?>

