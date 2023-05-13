
<?php

/******
* Der Weihnachtskalender
* Idee und Coding: Salator (salator [-[at]-] gmx.de)
* Idee für das Christstollenschlagen von Radio PSR (Weihnachtsbräuche wie du und ich)
* Sollte auf DS V2.5 lauffähig sein
*
Benötigte Dateien im Specials-Ordner:
-findgold.php (es sollte die aktuelle Atrahor-Version ohne redirect verwendet werden, im Wald zu landen ist unlogisch)
-oldmanmoney.php
-oldmanpretty.php

Benötigte Item-Schablonen:
-hlblkraut (Halblingskraut, etwas -ähm- rauchbares, was LP gibt oder nimmt. Hier als Weihrauch umbenannt)
-met (Met, ein lecker Getränk der alten Germanen, gibt oder nimmt einen Charmepunkt)
-futtersack oder feedcoupon (Futter für den tierischen Begleiter)

******/

if(isset($_GET['op']) && $_GET['op']=='wrongdate')
{
    echo('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <title>falsches Datum</title>
    </head>
    <body>
    <img src="images/rute.jpg" width=121 height=121 alt="Rute" align="left">
    Aber, aber!
    <br><br>Nein, das '.$_GET['d'].'. Türchen darfst Du heute nicht öffnen!
    <br><br>Oder willst du etwa die Rute spüren?
    <br><br><br><br><br><br>Wie, du willst?! Dies ist ein jugendfreier Server! Hier gibts solche Spielchen nicht.
    </body>
    </html>');
    exit;
}

require_once "common.php";

page_header('Weihnachtskalender');

//Diese Funktion ist ab DS V3 in der output.lib enthalten
/**
* @desc Färbt einen String wie den Usernamen
* @param string String, der gefärbt werden soll
* @param string Username (optional, Standard '' = aktueller User) //Todo: Möglichkeit der Array-Übergabe
* @param int Zeichengruppierung (optional, Standard 0 = gleichmäßige Verteilung)
* @return string Gefärbter String
* @author Salator
*//*
function color_from_name ($str_input, $username='', $chargroup=0)
{
    if($username=='')
    {
        global $session;
        $username=$session['user']['name'];
    }
    $username=str_replace('`0','',$username);
    $chargroup=intval($chargroup);

    $arr_colorcodes=explode('`',$username);
    array_shift($arr_colorcodes); //Löschen was vor dem ersten `steht

    if(strpos($username,'*`') || strpos($username,'¬`')) //bei Superusern mit Signum die ersten beiden Farbcodes löschen
    {
        array_shift($arr_colorcodes);
        array_shift($arr_colorcodes);
    }

    $int_allcolors=count($arr_colorcodes);
    $int_frontcolors=ceil($int_allcolors/2);

    if($int_allcolors>0) //Name ist gefärbt
    {
        if($chargroup<=0) //falls nicht angegeben, Aufteilung der Farben
        {
            $chargroup=max(1,floor(strlen($str_input)/$int_allcolors));
        }

        for($i=0; $i<$int_frontcolors; $i++) //vorderen Teil färben
        {
            $str_front.='`'.substr($arr_colorcodes[$i],0,1).substr($str_input,0,$chargroup);
            $str_input=substr($str_input,$chargroup);
        }
        for($i=$int_allcolors-1; $i>=$int_frontcolors; $i--) //hinteren Teil färben
        {
            $str_back='`'.substr($arr_colorcodes[$i],0,1).substr($str_input,-$chargroup).$str_back;
            $str_input=substr($str_input,0,-$chargroup);
        }

        $str_colored=$str_front.$str_input.$str_back.'`0';
        return($str_colored);
    }
    else //kein farbiger Name
    {
        return($str_input);
    }
}*/

//Diese Funktion ist ab DS V3.42 in der user.lib enthalten
/**
* @desc Errechnet einen Prozentsatz der Erfahrungspunkte; falls 2. Param true: DK- und Levelabhängig
* @param float_percent (float) wieviel Prozent der benötigten Erfahrung? (optional, Standard 10%)
* @param bool_nextlevel (bool) abhängig von nötiger Erfahrung für nächstes Level? (optional, Standard true)
* @author Talion, Salator
*//*
function user_percent_level_exp ($float_percent=10, $bool_nextlevel=true)
{
    global $session;
    $float_percent/=100;

    if($bool_nextlevel===true)
    { //Berechnung x% der Erfahrung die für Levelaufstieg nötig ist
        $int_rec = get_exp_required($session['user']['level']-1,$session['user']['dragonkills']);
        $int_req = get_exp_required($session['user']['level'],$session['user']['dragonkills']);
        $expplus = round(max($int_req - $int_rec,0) * $float_percent);
    }

    else
    { //einfache Berechnung x% der vorhandenen Erfahrung
        $expplus=round($session['user']['experience'] * $float_percent);
    }

    return($expplus);
}*/

$filename=basename(__FILE__);
$day=date("j");
if($_POST['day']>0) $day=(int)$_POST['day'];
output('`c`b`2Der Weihnachtskalender`0`b`c`n');

if($_GET['op']=='showpic') //Adventskalenderbild anzeigen
{
    rawoutput('<center><img src="images/adventskalender.jpg" width=395 height=555 alt="" usemap="kalmap" border=0></center>
    <map name="kalmap">
    <area href="javascript:opendoor(1)" coords="143,373,190,408">
    <area href="javascript:opendoor(2)" coords="351,33,381,68">
    <area href="javascript:opendoor(3)" coords="174,158,211,224">
    <area href="javascript:opendoor(4)" coords="349,267,381,337">
    <area href="javascript:opendoor(5)" coords="18,444,52,493">
    <area href="javascript:opendoor(6)" coords="183,11,228,50">
    <area href="javascript:opendoor(7)" coords="13,246,44,324">
    <area href="javascript:opendoor(8)" coords="109,98,150,163">
    <area href="javascript:opendoor(9)" coords="159,453,236,503">
    <area href="javascript:opendoor(10)" coords="15,11,59,68">
    <area href="javascript:opendoor(11)" coords="44,341,96,431">
    <area href="javascript:opendoor(12)" coords="233,158,272,224">
    <area href="javascript:opendoor(13)" coords="85,460,146,545">
    <area href="javascript:opendoor(14)" coords="115,259,171,344">
    <area href="javascript:opendoor(15)" coords="182,77,259,138">
    <area href="javascript:opendoor(16)" coords="212,363,268,427">
    <area href="javascript:opendoor(17)" coords="221,243,316,285">
    <area href="javascript:opendoor(18)" coords="294,147,379,201">
    <area href="javascript:opendoor(19)" coords="221,309,315,354">
    <area href="javascript:opendoor(20)" coords="276,43,311,117">
    <area href="javascript:opendoor(21)" coords="90,34,158,73">
    <area href="javascript:opendoor(22)" coords="56,178,124,242">
    <area href="javascript:opendoor(23)" coords="11,86,83,145">
    <area href="javascript:opendoor(24)" coords="301,401,382,503">
    </map>
    <script type="text/JavaScript">
    var now = new Date();
    var month = now.getMonth() + 1;
month=12;
    var date = now.getDate();
    function opendoor(tag,url,nom,params){
        if (month==12 && date == tag)
        {
            window.location.href="'.$filename.'";
        }
        else
        {
            //MessageBox.show("<div style=\'text-align:left;\'><img src=\'images/rute.jpg\' width=121 height=121 alt=\'Rute\' hspace=10 vspace=10 align=\'left\'><b>Nein, das "+tag+". Türchen darfst Du heute nicht öffnen!</b><br><br>Oder willst du etwa die Rute spüren?<br clear=\'all\'></div>Wie, du willst?! Dies ist ein jugendfreier Server! Hier gibts solche Spielchen nicht.", "Die Rute");
            msg=window.open("'.$filename.'?op=wrongdate&d="+tag, "msg", "scrollbars=no,width=570,height=130,left=120,top=250")
        }
        msg.focus()
    }
    </script>
    ');
    addnav('',$filename);
    addnav('Kein Bild zu sehen?',$filename);
}

elseif ($_GET['op']=='nutcracker')
{
    if ($session['user']['gems']>0)
    {
        output('`tDu legst einen `#Edelstein`t in den geöffneten Mund des `~Nu`&s`~s`$knackers`t und drückst den Hebel.
        `nDas darauffolgende Knirschen ist nicht zu überhören.
        `nDu betrachtest dein Werk: ein Häufchen `#Diamant`3staub`t rieselt dem zahnlosen Nussknacker aus dem Mund. Der `~zahn`&l`~ose`$ Knacker`t ist jetzt allerdings nicht mehr zu gebrauchen.
        `nSchnell kehrst du den Diamantstaub zusammen und schleichst dich davon, bevor du noch wegen der Zerstörung eines Nussknackers ins Gefängnis kommst...');
        $session['user']['gems']--;
        $item['tpl_gems']=2;
    }
    else
    {
        output('`tDu untersuchst den `~zahn`&l`~osen`$ Knacker`t näher. Offenbar hat er sich die Zähne an einem Edelstein ausgebissen, denn du kannst noch ein kleines Häufchen `#Diamant`3staub`t zusammenkratzen. Na immerhin etwas...');
        $item['tpl_gems']=1;
    }
    $item['tpl_name']='`#Diamant`3staub`0';
    $item['tpl_description']='Dieses fein gemahlene Häufchen war einmal ein wertvoller Edelstein. Leider kennst du niemanden, der Verwendung für dieses Pulver hat.';
    $item['tpl_gold']=0;
    item_add($session['user']['acctid'],'beutdummy',$item);
}

else //die Ergebnisse des Türchenöffnens
{
    output('`tDu trittst vor den Weihnachtskalender, um das '.$day.'. Türchen zu öffnen.
    `n`n');
    $row=user_get_aei('xmas_special_taken');
    if($row['xmas_special_taken']==$day)
    {
        output('`QDu suchst und suchst, aber du findest einfach kein Türchen mit der Nummer '.$day.'. Dann fällt dir ein, dass du ja heute schon ein Türchen geöffnet hast.
        `n`tNa gut, dann eben morgen wieder...');
    }
    else
    {
        switch($day)
        {
            case 1: //Item: Mistelzweig
            {
                output('Du bist voller freudiger Erwartung, was sich denn dahinter verbirgt. Dann lüftet sich das Geheimnis: Es ist ein `gMistelzweig`t.
                `nNach altem Glaube soll ein Mistelzweig Glück bringen, wenn man ihn über der Eingangstür des Hauses anbringt. Ferner ist es Brauch, dass einem/einer Eintretenden ein Kuss gestohlen werden darf.');
                $item['tpl_name']='`gMistelzweig`0';
                $item['tpl_description']='Ein Mistelzweig, der während der Weihnachtszeit über der Eingangstür des Hauses angebracht wird. Dieser Glücksbringer erlaubt es, einer eintretenden Person einen Kuss zu rauben.';
                item_add($session['user']['acctid'],'mistel',$item);
                
                //Und hier gibts für den User einen Wunschzettel+Stiefel hinzu!
                output('`n`nAls Du den Mistelzweig einsteckst, fällt dir im Fach des Adventskalenders noch etwas auf. Du schaust genauer hin und siehst ein kleines Pergament und einen Stiefel.`n
                Du betrachtest das kleine Stück Papier auf dem am oberen Rand in wundervoll kalligraphierten goldenen Lettern `bWunschzettel`b geschrieben wurde.
                Die Ränder des Wunschzettels sind mit Ornamenten versehen, die wie Tannenzweige und Lichterketten aussehen und am unteren Rand kannst du ganz klein den 
                Schriftzug: `bKnecht Ruprecht Weihnachts-GmbH`b erkennen.`n`n
                Hui, genau sowas hast du gesucht, den wirst du auf alle Fälle mal benutzen! aber wozu der Stiefel?!?');
                
                item_add($session['user']['acctid'],'xmas_wunschzettel');
                item_add($session['user']['acctid'],'xmas_stiefel');
                break;
            }

            case 2: //etwas Gold
            {
                include('special/findgold.php');
                break;
            }

            case 3: //ein paar LP für Echsen, sonst etwas Gold
            {
                output('Du findest eine Schriftrolle, die mit `^Beetleham`t überschrieben ist.
                Zunächst wunderst du dich, warum da "Betlehem" falsch geschrieben ist, beim Lesen merkst du aber, dass du hier ein angelsächsisches Rezept für Käferfleisch betrachtest. ');
                if($session['user']['race']=='ecs')
                {
                    output('Das freut dich natürlich riesig. Du suchst dir ein paar Insekten und probierst die englische Kochkunst aus.
                    `nDu bekommst ein paar Lebenspunkte hinzu.');
                    $session['user']['hitpoints']*=1.15;
                }
                else
                {
                    output('"Das ist ja widerlich" denkst du dir. Aber vielleicht kann man das Rezept ja irgendwo verkaufen...');
                    $item['tpl_name']='Beetleham-Rezept';
                    $item['tpl_description']='Diese Schriftrolle erklärt der echsischen Hausfrau, wie man Käfer schmackhaft zubereitet.';
                    $item['tpl_gold']=75;
                    item_add($session['user']['acctid'],'beutdummy',$item);
                }
                break;
            }

            case 4: //+40 Gefallen
            {
                output('Hinter diesem Türchen befindet sich ein Tannenzweig mit einer mundgeblasenen Glaskugel.
                `nDas Muster auf dieser Kugel sieht ja lustig aus. Irgendwie erinnert es dich an einen Schädel...
                `nDu bekommst `^40 Gefallen`t bei `$Ramius.');
                $session['user']['deathpower']+=40;
                break;
            }

            case 5: //Rausch-Buff
            {
                output('Noch bevor du siehst, was sich hinter diesem Türchen verbirgt, schlägt dir intensiver `5Glühweinduft`t entgegen. Dann siehst du den dampfenden Becher, er muss gerade frisch eingeschenkt worden sein.
                `nSofort nimmst du einen Schluck. `4Autsch!`t Das war heiß! Du hast dir die Lippen verbrüht, was dich ein paar Lebenspunkte kostet.
                `nAlso lässt du das Getränk etwas auskühlen, bevor du weiter trinkst und wärmst dir währenddessen die Hände am Becher. Naja, wenigstens hast du gelernt, dass Glühwein heiß ist. Du bekommst 150 Erfahrungspunkte.
                `n`n`0Hinweis: Glühwein enthält Alkohol. Nach dem Genuss von Alkohol ist es in '.getsetting('townname','Atrahor').' nicht erlaubt, eine Kutsche zu führen.');
                $session['user']['hitpoints']=round($session['user']['hitpoints']*0.9);
                $session['user']['experience']+=150;
                $session['user']['drunkenness']+=30;
                $session['bufflist']['101'] = array('name'=>'`#Rausch','rounds'=>10,'wearoff'=>'Dein Rausch verschwindet.','atkmod'=>1.25,'roundmsg'=>'Du hast einen ordentlichen Rausch am laufen.','activate'=>'offense');
                break;
            }

            case 6: //+1 Edelstein
            {
                $rowe=user_get_aei('ctitle,cname');
                $newtitle=($session['user']['sex']?'Zahnlose':'Zahnloser');
                $newtitle=color_from_name($newtitle,$rowe['ctitle']);
                $newname=($rowe['cname']?$rowe['cname']:$session['user']['login']);
                output('Eine Nuss!
                `nDu nimmst die Nuss in den Mund, um sie mit deinen Zähnen zu knacken. Zu spät bemerktst du, dass die Nuss in Wirklichkeit ein verkleideter Edelstein ist. Daran kann man sich nur die Zähne ausbeißen...
                `n`nDu bist von nun an bekannt als `^'.$newtitle.' '.$newname.'`t.
                `nDen Edelstein steckst du aber trotzdem ein.');
                $session['user']['gems']++;
                break;
            }

            case 7: //Item: Diamantstaub
            {
                output('Hinter diesem Türchen erblickst du einen `~Nu`&s`~s`$knacker`t.
                `nVerdammt, den hättest du gestern gebrauchen können!`n');
                if($session['user']['gems']>0)
                {
                    output('Aber du hast ja noch den Edelstein, an dem du dir gestern die Zähne ausgebissen hast. Das wäre doch jetzt eine hervorragende Gelegenheit, den Nussknacker zu testen...');
                    addnav('Einen Edelstein knacken',$filename.'?op=nutcracker');
                }
                else
                {
                    output('Schade, dass du den Edelstein, an dem du dir gestern die Zähne ausgebissen hast, nicht mehr bei dir trägst. Das wäre doch jetzt eine hervorragende Gelegenheit, den Nussknacker zu testen.
                    `nAber Moment mal, der Nussknacker hat ja gar keine Zähne mehr. Das solltest du dir vielleicht mal genauer ansehen...');
                    addnav('Nussknacker untersuchen',$filename.'?op=nutcracker');
                }
                break;
            }

            case 8: //+1 WK
            {
                output('Hinter diesem Türchen befindet sich eine Kerze.
                `nEine Kerze spendet Licht, wodurch du heute länger im Wald bleiben kannst.
                `n`^Du erhältst eine Extrarunde. `tPass aber auf, dass du keinen Waldbrand verursachst!');
                $session['user']['turns']++;
                break;
            }

            case 9: //+1 Charmepunkt
            {
                output('Du bist so vertieft in die Suche nach dem Türchen, dass du gar nicht merkst, was hinter dir passiert. Doch dann spürst du es: ');
                include('special/oldmanpretty.php');
                break;
            }

            case 10: //Zugang zur Goldmine
            {
                output('Als du dieses Türchen geöffnet hast, siehst du auf einem Teller ein Stück `&Christstollen`t liegen, welches mit Honig bestrichen ist.
                `nWährend du dir dieses leckere Gebäck schmecken lässt, kommt dir ein Weihnachtsbrauch in den Sinn, wie er im fernen Arzgebirg gebraucht wird: Das Christstollenschlagen. Am heiligen Abend fahren die Bergleute mit ihrer ganzen Familie in den Berg und alle gemeinsam schlagen einen neuen Stollen, den sogenannten Christstollen.
                `nDu fühlst dich angespornt, mal wieder der alten Mine einen Besuch abzustatten.
                `n`4Sei aber gewarnt, die Mine kann einstürzen! Und ob du etwas findest ist auch nicht sicher.');
                $config = unserialize($session['user']['donationconfig']); 
                $config['goldmine'] += 1; 
                $session['user']['donationconfig'] = serialize($config); 
                break;
            }

            case 11: //+50% hitpoints
            {
                output('Kinder, kommt und ratet,
                `nwas im Ofen bratet!
                `nHört, wie\'s knallt und zischt.
                `nBald wird er aufgetischt,
                `nder Zipfel, der Zapfel,
                `nder Kipfel, der Kapfel,
                `nder gelbrote Apfel.
                `n
                `nKinder, lauft schneller,
                `nholt einen Teller,
                `nholt eine Gabel!
                `nSperrt auf den Schnabel
                `nfür den Zipfel, den Zapfel,
                `nden Kipfel, den Kapfel,
                `nden goldbraunen Apfel!
                `n
                `nSie pusten und prusten,
                `nsie gucken und schlucken,
                `nsie schnalzen und schmecken,
                `nsie lecken und schlecken
                `nden Zipfel, den Zapfel,
                `nden Kipfel, den Kapfel,
                `nden knusprigen Apfel. 
                `n
                `nNach diesem Schmaus bist du gestärkt für was immer da kommen mag.');
                $session['user']['hitpoints']=round($session['user']['maxhitpoints']*1.5);
                break;
            }

            case 12: //+1 WK
            {
                $sp = array(RP_RESURRECTION => '`WHalbtot', (-6)=>'`4Auferstanden',(-2)=>'`$Sehr schlecht',(-1)=>'`qSchlecht',0=>'`7Normal',1=>'`2Gut',2=>'`@Sehr gut');
                output('Du findest hinter diesem Türchen eine Schneekugel und ein Räuchermännchen. Du zündest das Räuchermännchen an und schüttelst die Schneekugel, um dich an diesem Anblick zu erfreuen. '); //Räuchermännchen anzünden? Na egal, is halt bissl zweideutig
                if($session['user']['spirits']>-3 && $session['user']['spirits']<2)
                {
                    output('Deine Stimmung ändert sich von '.$sp[$session['user']['spirits']].'`t zu '.$sp[$session['user']['spirits']+1].'`t. Du bekommst einen Waldkampf dazu.');
                    $session['user']['spirits']++;
                    $session['user']['turns']++;
                }
                else
                {
                    output('Deine Stimmung ist '.$sp[$session['user']['spirits']].'`t. Daran ändert sich nichts und wir tun einfach mal so, als ob du das Türchen nicht geöffnet hättest. Du darfst heute nochmal...');
                    $day--;
                }
                break;
            }

            case 13: //bei Sieg: 25% der für Levelaufstieg benötigten Erfahrung, bei perfekt: +1WK
            {
                output('Hinter diesem Türchen findest du ein `TLebkuchenmännchen`t.
                `nGerade will dich ein anderer Marktbesucher darauf aufmerksam machen, warum das `T`bLeb`bkuchen`t heißt, als du die Tatsache auch schon selbst bemerkst. Natürlich `^lebt`t das Männchen und ist gar nicht davon begeistert, dass es dir als Mahlzeit dienen soll.
                `nDu nimmst die Beine in die Hand und rennst in den Wald, doch das `TLebkuchenmännchen`t verfolgt dich. Vielleicht wäre es jetzt angebracht, deine '.$session['user']['weapon'].'`t zu benutzen?');
                $badguy=array(
                    'creaturename' => '`TLebkuchenmännchen'
                    ,'creatureweapon' => '`tKuchenkrümel'
                    ,'creaturelose' => 'Und so ward es doch aufgegessen...'
                    ,'creaturewin' => '%W`5 spottet: "`6Siehst du, das passiert wenn man seine Suppe nicht aufisst.`5"'
                    ,'creaturelevel' => $session['user']['level']
                    ,'creatureattack' => round($session['user']['attack']*0.7)
                    ,'creaturedefense' => round($session['user']['defense']*0.7)
                    ,'creaturehealth' => round($session['user']['hitpoints']*0.7)
                    ,'creaturegold' => $session['user']['level']*24
                    ,'creatureexp' => user_percent_level_exp(25)
                );
                $session['user']['badguy']=createstring($badguy);
                addnav('Kämpfe','forest.php?op=fight');
                break;
            }

            case 14: //+100DP
            {
                output('Du findest ein Medaillon mit der Aufschrift `^1&euro;`t.
                `nLeider weißt du überhaupt nicht, was du mit einem solchen Medaillon anfangen sollst und wirfst es einem Bettler in die Büchse.
                `nDie Götter danken dir diese großzügige Tat, was du aber erst später bemerken wirst.');
                $session['user']['donation']+=100;
                break;
            }

            case 15: //etwas Gold oder Diebeskünste
            {
                output('Du brauchst sehr lange, um das Türchen zu finden. Das gibt den anderen Marktbesuchern Gelegenheit, dich eindringlich zu beobachten. `0');
                $gold=$session['user']['gold'];
                include('special/oldmanmoney.php');
                if($session['user']['gold']<$gold || $session['user']['gold']==0)
                {
                    output('`n`tDu beschließt, dir das Gold auf ebenso heimtückische Art wiederzuholen. Du bekommst `^3 Anwendungen Diebeskünste.`0');
                    $session['user']['specialtyuses']['thieveryuses']+=3;
                }
                break;
            }

            case 16: //Kein Tier: nimm den Hund mit, sonst: 1 perm. Extrarunde fürs Tier
            {
                output('Als du das Türchen geöffnet hast bellt dir ein `THund`t entgegen. ');
                if($session['user']['hashorse']>0)
                {
                    output('Dein '.$playermount['mountname'].'`t erschreckt sich und rennt davon. Dabei entdeckt dein Tier ungenutzte Reserven und wird in Zukunft eine Runde länger an deiner Seite kämpfen.');
                    $sql='UPDATE account_extra_info SET mountextrarounds=mountextrarounds+1 WHERE acctid='.$session['user']['acctid'];
                    db_query($sql);
                }
                else
                {
                    output('Hoffnungsvoll mit dem Schwanz wedelnd schaut er dich an. Da kannst du nicht widerstehen und nimmst das Tier mit.');
                    $session['user']['hashorse']=4;
                    getmount($session['user']['hashorse'],true);
                    $session['bufflist']['mount']=unserialize($playermount['mountbuff']);
                }
                break;
            }

            case 17: //+1 Charmepunkt und Rausch-Buff
            {
                output('Noch bevor du siehst, was sich hinter diesem Türchen verbirgt, schlägt dir intensiver `5Glühweinduft`t entgegen. Dann siehst du den dampfenden Becher, er muss gerade frisch eingeschenkt worden sein.
                `nDiesmal bist du schlauer und lässt das Getränk erstmal auskühlen, während du dir die Hände am Becher wärmst.
                `nAls du den Glühwein ausgetrunken hast, beginnen deine Wangen zu glühen. Du siehst attraktiver aus und erhältst einen Charmepunkt.
                `n`n`0Hinweis: Glühwein enthält Alkohol. Nach dem Genuss von Alkohol ist es in '.getsetting('townname','Atrahor').' nicht erlaubt, eine Kutsche zu führen.');
                $session['user']['charm']++;
                $session['user']['drunkenness']+=30;
                $session['bufflist']['101'] = array('name'=>'`#Rausch','rounds'=>10,'wearoff'=>'Dein Rausch verschwindet.','atkmod'=>1.25,'roundmsg'=>'Du hast einen ordentlichen Rausch am laufen.','activate'=>'offense');
                break;
            }

            case 18: //Futter fürs Tier, Tier sollte ja jetzt jeder haben
            {
                output('Als du das Türchen geöffnet hast leuchtet dir ein `$Weih`&nachts`$stern`t entgegen. Du überlegst, was du mit einem Weihnachsstern anstellen könntest. ');
                if($session['user']['hashorse']>0)
                {
                    output('Ob vielleicht dein '.$playermount['mountname'].'`t sowas frisst?
                    `nDu willst den Weihnachtsstern gerade an dein Tier verfüttern, als Merick angerannt kommt. "`&Ai, bischt narrisch? Weischt Du nich desch die roten Blätter giftich sin? Desch tät dein '.$playermount['mountname'].'`& nich gut tun!`t"
                    `nDu siehst ein, dass er recht hat. Und so bist du nicht abgeneigt, als dir Merick einen Futtersack im Tausch gegen den Weihnachtsstern anbietet.');
                }
                else
                {
                    output('Just in diesem Moment kommt Merick, der Tierhändler vorbei und bietet dir einen Futtersack, wenn du ihm den Weihnachtsstern gibst.
                    `nDu willigst ein.
                    `nAls dir auffällt, dass du für die Fütterung ja ein Tier bräuchtest, ist Merick schon wieder im Gedränge verschwunden.');
                }
                item_add($session['user']['acctid'],'fttrsack'); //nur in Atrahor
                //item_add($session['user']['acctid'],'feedcoupon'); //nur in Valyria
                break;
            }

            case 19: //Item: Myrrhe (umbenannte Raserei)
            {
                output('Du findest einen Beutel, der mit Kräutern gefüllt ist. `gMyrrhe`t steht darauf.
                `nDu erinnerst dich, dass Myrrhe eine schmerzverdrängende Wirkung nachgesagt wird. Sicher lässt sich das im Kampf nutzen.');
                $item['tpl_name']='`gMyrrhe`0';
                $item['tpl_description']='Mit Myrrhe spürst du weniger Schmerzen und kannst wild drauflos schlagen. Dies ist mehr eine Kampftechnik, als es mit Magie zu tun hat. Dein Angriffswert steigt, deine Verteidigung leidet allerdings unter dieser blinden Raserei. Kann 3 Tage lang 2x eingesetzt werden.';
                item_add($session['user']['acctid'],'raserei',$item);
                break;
            }

            case 20: //Item: Flasche Met
            {
                output('Hinter diesem Türchen entdeckst du eine `&Flasche`^ M`yet`t.
                `nIrgendwie findest du es passend, zum Ende der Yulzeit ein leckeres germanisches Getränk zu bekommen. Damit lässt sich die Wintersonnenwende noch viel schöner feiern...');
                item_add($session['user']['acctid'],'met');
                break;
            }

            case 21: //(Wintersonnenwende) 10 Anwendungen mystische Kräfte
            {
                output('`qEin Sonnenrad!
                `nDas erinnert dich an ein heidnisches Ritual, welches zur Wintersonnenwende zelebriert wird. Du erhältst `^10 Anwendungen in `%Mystischen Kräften`q!');
                $session['user']['specialtyuses']['magicuses']+=10;
                break;
            }

            case 22: //Item: Weihrauch (umbenanntes Halblingskraut)
            {
                output('Du findest einen Beutel, der mit Kräutern gefüllt ist. `6Weihrauch`t steht darauf.
                `nDu denkst zwar, dass Rauchen nicht gesund sein kann, steckst den Beutel aber trotzdem ein.');
                $item['tpl_name']='`tWeihrauch`0';
                $item['tpl_description']='Ein Säckchen mit einer Kräutermischung für Weihrauch. Bei genauerer Betrachtung hat es starke Ähnlichkeit mit `tHalblingskraut`0. Du ahnst, warum die kleinen Pelzfüße immer so munter sind...';
                item_add($session['user']['acctid'],'hlblkraut',$item);
                break;
            }

            case 23: //hier wird ein zufälliges Alchemie-Rezept verraten
            {
                $sql='SELECT id1,id2,id3 FROM items_combos WHERE type=2 ORDER BY rand() LIMIT 1';
                $result=db_query($sql);
                $row=db_fetch_assoc($result);
                $sql='SELECT tpl_name FROM items_tpl WHERE tpl_id IN ("'.$row['id1'].'","'.$row['id2'].'","'.$row['id3'].'")';
                $result=db_query($sql);
                while($row=db_fetch_assoc($result))
                {
                    $ingred.=$row['tpl_name'].', ';
                    $i++;
                }
                output('Als du das 23. Türchen öffnest, blickst du direkt
                `0`c`tin
                `nein
                `n`#`bAuge`b`t.
                `nAls du den
                `nersten Schreck
                `nüberwunden hast,
                `nfällt dir die Pyramide im
                `nHintergrund auf und dir wird
                `nnun klar, dass du es mit geheimen
                `nMächten zu tun hast. Und tatsächlich, da
                `nsind doch geheime Schriftzeichen zu erkennen...
                `0`c`n`n`2Du nimmst Zettel und Stift und notierst dir '.($i==1?'3x ':'').$ingred.'`2um diese Zutaten mal in einem alchemistischen Schmelztiegel zusammenzumischen.');
                break;
            }

            default:
            {
                clearoutput();
                page_header('Der Weihnachtsbaum');
                output('`c`b`2Der Weihnachtsbaum`0`b`c`n');
                //if ($row['xmas_special_taken']!=date("Y"))
                if ($row['xmas_special_taken']<24) //eigenartigerweise wurde bei einigen der Wert nicht auf das Jahr gesetzt
                {
rawoutput('<pre>
           *             ,
                       _/^\_
                      <     >
     *                 /.-.\         *
              *        `/&amp;\`                   *
                      ,@.*;@,
                     /_o.I %_\    *
        *           (`\'--:o(_@;
                   /`;--.,__ `\')             *
                  ;@`o % O,*`\'`&amp;\
            *    (`\'--)_@ ;o %\'()\      *
                 /`;--._`\'\'--._O\'@;
                /&amp;*,()~o`;-.,_ `""`)
     *          /`,@ ;+&amp; () o*`;-\';\
               (`""--.,_0 +% @\' &amp;()\
               /-.,_    ``\'\'--....-\'`)  *
          *    /@%;o`:;\'--,.__   __.\'\
              ;*,&amp;(); @ % &amp;^;~`"`o;@();         *
              /(); o^~; &amp; ().o@*&amp;`;&amp;%O\
        jgs   `"="==""==,,,.,="=="==="`
           __.----.(\-\'\'#####---...___...-----._
         \'`         \)_`"""""`
                 .--\' \')
               o(  )_-\
                 `"""` `
</pre>');
                    //Geschenkmenge
                    $gold=3000;
                    $gems=3;
                    $charme=50;
                    $bonus='eine `$Z`&uc`4k`&er`$s`&ta`4n`&ge`t, ';
                    if($session['user']['daysinjail']>20)
                    {
                        $bonus.='die wie eine Rute geformt ist, ';
                    }
                    item_add($session['user']['acctid'],'candycane');
                    
                    output('`tHinter dem letzten Türchen erblickst du einen großen, prächtig geschmückten Weihnachtsbaum, der über und über mit Geschenken behangen ist.
                    `nDu näherst dich dem Baum, streckst die Hände nach einem Paket aus und nimmst es von dem Zweig, an dem es hängt. Einen Augenblick lang betrachtest du es noch beinahe ehrfürchtig, dann packst du es aber auch schon aus.
                    `n Es liegen '.$bonus.'`^'.$gold.' `tGoldstücke und `#'.$gems.' `tEdelsteine darin.
                    `n Deine Augen leuchten und zufrieden packst du dein Geschenk ein.');
                    $session['user']['gold']+= $gold;
                    $session['user']['gems']+= $gems;
                    $session['user']['charm']+= $charme;
                    $day=date("Y");
                    
                    //Weiteres Geschenk wenn der User den Wunschzettel abgegeben hat.
                    $arr_item = item_get('tpl_id="xmas_wunschzettel" AND owner='.$session['user']['acctid'].' AND value1 = 1');
                    if($arr_item != false)
                    {
                        $arr_targets=explode(',',getsetting('snowball_persons','MightyE'));
                        $rand=e_rand(0,count($arr_targets)-1);
                        $str_output .= '`n`n`tAls du dich gerade abwenden willst, hörst du ein kleines Glöckchen klingeln, das langsam lauter wird. Innerhalb kürzester Zeit kannst du den Ursprung ausmachen. 
                        Es handelt sich um einen kleinen Schlitten, der von einer Horde Rentiere durch den weihnachtlichen Himmel gezogen wird. Als der Schlitten etwa zwei Meter vor und einen Meter über 
                        dir zum Halten kommt, siehst du einen dicken Mann mit rotem Mantel und Rauschebart über den Rand zu dir hinunter schauen.`n
                        "`y'.$session['user']['name'].'?`t"`n
                        "Ja?"`n
                        `y"Lieferung für Dich! Anscheinend warst Du brav...nunja, mehr oder minder. Auf jeden Fall wünsche ich Dir frohe Weihnachten! 
                        Sei mir nicht böse, ich muss weiter. Ich muss noch '.ceil(e_rand(3000000000,4000000000)).' andere Kunden besuchen. Der nächste heißt '.$arr_targets[$rand].' oder so...`t"`n
                        Du öffnest das Päckchen und findest darin ';
                        
                        $arr_item['content'] = unserialize($arr_item['content']);
                        switch ($arr_item['content']['wish'])
                        {
                            case 2://10CP
                            {
                                $str_output .= '`bzwanzig Charmepunkte`b';
                                $session['user']['charm'] += 20;
                                break;
                            }
                            case 3://Dracheneifragment
                            {
                                $str_output .= '`bein Dracheneifragment`b';
                                item_add($session['user']['acctid'],'b_gd_shell');
                                break;
                            }
                            case 4: //Friede in Atrahor
                            {
                                $str_output .= '`beine Schneekugel`b';
                                $item['tpl_description']='Sie zeigt eine winterliche Ansicht von '.getsetting('townname','Atrahor').'. Man kann sogar die filigrane Nachbildung des jungen Dragonslayer, der gerade von den Königen der alten Völker gesegnet wird, erkennen. `nAuf dem Sockel der Schneekugel ist `^'.date("Y").' A.D.`0 eingraviert';
                                item_add($session['user']['acctid'],'schneekug',$item);
                                break;
                            }
                            case 5://Plüschdrache
                            {
                                $str_output .= '`bein Plüschdrache`b';
                                $item['tpl_description']='`REin `@Grüner Drache`R aus Plüsch zum Kuscheln. Der ist von `&Weihnachten '.date("Y").'`R und ja sooooooo süß!!';
                                item_add($session['user']['acctid'],'plschdrn',$item);
                                break;
                            }
                            case 6://Schokosalator
                            {
                                $str_output .= '`beinen Schokoweihnachtsmann`b';
                                item_add($session['user']['acctid'],'schokosalator');
                                break;
                            }
                            case 1:
                            default:
                            {
                                $str_output .= '`bzehn Edelsteine`b';
                                $session['user']['gems'] += 10;
                                break;
                            }
                        }
                        
                        $str_output .= '.`nDanke lieber Weihnachtsmann!';
                        
                        output($str_output);
                        
                        //Quittung wird gelöscht
                        item_delete('id='.$arr_item['id']);
                        
                        //Stiefel auch löschen ?
                        //item_delete('tpl_id="xmas_stiefel" AND owner='.$session['user']['acctid']);
                    }
                    
                }
                else
                {
                    output('`tJemand klopft dir auf die Finger, als du ein weiteres Paket nehmen willst. Nur ein warnender Blick von einer Wache und du weißt, dass du es lieber unterlässt.');
                }
                break;
            }
        }
        user_set_aei(array('xmas_special_taken'=>$day));
    }

    if($access_control->su_check(access_control::SU_RIGHT_DEV))
    {
        output('`0`n<form action="'.$filename.'" method="post">
        Admin: Tag <input type="text" name="day" size=2 maxlength=2>
        <input type="submit" class="button" value="Testen">
        </form>');
        addnav('',$filename);
    }
}

addnav('Zurück');
addnav('Zum Weihnachtsmarkt','weihnachtsmarkt.php');
addnav('D?Ins Dorf','village.php');
page_footer();
?>


