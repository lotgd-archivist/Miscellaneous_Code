
<?php
/*
Weihnachtsmarkt
Ursprünglich für: isarya-logd.de.vu von Naria Talcyr
Idee: Nymh, Inistha, Rhao
Texte: Jezebel, Nymh

Features:
- Gebäck kaufen
- Getränke kaufen
- Schlittschuhe ausleihen, um zum gefrorenem See zu kommen (neuer RP Ort)
- Gebäck verschicken (+Nachricht)
- Geschenk vom Weihnachtsbaum nehmen

Original erhältlich auf anpera.net
Für Atrahor überarbeitet und erweitert von Salator
Schneeballwerfen Idee von plueschdrache.de

Auszuführende SQL:
ALTER TABLE `account_extra_info` ADD `xmas_special_taken` SMALLINT UNSIGNED NOT NULL DEFAULT '0';

*/
require_once "common.php";

//Sachen für den Glühweinstand, einfach eintragen, fügen sich selber hinzu
$drinks=array('gluh'=> array('name'=>'Glühwein','price'=>50)
,'apfel'=>array('name'=>'Heißer Apfelwein','price'=>55)
,'met'=>array('name'=>'Warmer Met','price'=>60)
);

//Sachen für den Gebäckstand, einfach eintragen, fügen sich selber hinzu
$kekse=array('lebherz'=> array('name'=>'Lebkuchenherzen','price'=>50,'send'=>'ein Lebkuchenherz')
,'zimt'=>array('name'=>'Zimtsterne','price'=>50,'send'=>'eine Packung Zimtsterne')
,'lebhaus'=>array('name'=>'ein Lebkuchenhaus','price'=>200,'send'=>'ein Lebkuchenhaus')
,'stollen'=>array('name'=>'Rosinenstollen','price'=>80,'send'=>'ein Stück Rosinenstollen')
,'schokokeks'=>array('name'=>'Schokoladenkekse','price'=>75,'send'=>'eine Packung Schokoladenkekse')
,'printen'=>array('name'=>'Printen','price'=>60,'send'=>'eine Handvoll Printen')
,'mandel'=>array('name'=>'gebrannte Mandeln','price'=>30,'send'=>'ein Beutel gebrannter Mandeln')
,'zucker'=>array('name'=>'eine Zuckerstange','price'=>20,'send'=>'eine Zuckerstange')
//,'kl_schokosalator'=>array('name'=>'ein kleiner Schokosalator','price'=>500,'send'=>'ein kleiner Schokosalator')
//,'marzipankartoffel'=>array('name'=>'Marzipankartoffeln','price'=>20,'send'=>'Marzipankartoffeln')
);

//Preis fürs Schlittschuhlaufen
$schlittschuh=150;

//Geschenkmenge
$gold=3000;
$gems=1;
$charme=50;
$year=date('Y');
$tpl_class_beute=3;

checkday();
addcommentary();
music_set(xmasmarket,5);
$str_filename = basename(__FILE__);
$author_info='Naria Talcyr (isarya-logd.de.vu)';
$emotecolor=$session['user']['prefs']['commentemotecolor']>''?'`'.$session['user']['prefs']['commentemotecolor']:'`&';

switch ($_GET['op'])
{
case 'see':
    page_header('Der gefrorene See');
    output('`c`b`*Der `ege`&frore`ene `*See`0`b`c
`n`fFröhlich fast glitzert die Eisdecke auf dem großen See des Dorfes. Auf der festen Schicht sind kleine Linien zu sehen, verursacht durch Eisenschienen, befestigt an ledernen Schuhen, die die Kinder tragen, wenn sie sich auf das Eis wagen um ihre Runden zu fahren.
`nDoch irrst du dich, wenn du denkst, dass sich nur Kinder diesen Spaß erlauben. So manches Liebespaar zieht gemeinsam seine Kreise, aber auch Freunde sind zusammen auf dem spiegelglatten Eis unterwegs, lachen ausgelassen. Schließ\' dich ihnen doch an!`n`n');
    viewcommentary('gefrorener_See','`n`hLachen und Scherzen:`n',15);
    addnav('Wege');
    addnav('Weihnachtsmarkt',$str_filename);
    break;
    
case 'schlittschuh':
    page_header('Schlittschuhe');    
    switch ($_GET['act'])
    {
    case 'ausleihen':
        output(get_title('`*Schlittschuhe ausleihen'));
        if ($session['user']['gold']>=$schlittschuh)
        {
            output('`fDu suchst dir ein Paar aus und bezahlst die Verleihgebühr von `^'.$schlittschuh.' Gold `fdirekt. Du läufst mit dem Paar zum See, setzt dich auf eine Bank und ziehst sie an. Ein wenig wackelig kommst du dir vor, doch wagst du dich dennoch aufs Eis.`n');
            $session['user']['gold']-=$schlittschuh;
            $session['daily']['schlittschuh']=1;
            addnav('Zum See',$str_filename.'?op=see');
        }
        else
        {
            output('`fDu suchst dir ein Paar aus, doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einer Runde Schlittschuhlaufen.');
            addnav('Wege');
            addnav('Weihnachtsmarkt',$str_filename);
        }
        break;
        
        default:
        output(get_title('`*Sc`ehl`sit`&ts`sc`ehu`*he'));
        output('`fKinder drängen sich an dir vorbei, lachend und ausgelassen rennend, in den Händen Schlittschuhe haltend, mit  denen sie in Richtung des gefrorenen Sees davon laufen um darauf, wie es bereits schon viele andere tun, ihre  Runden zu drehen. Lächelnd siehst du ihnen dabei zu, wie sie sich die Schuhe anziehen und dann auf dem Eis erst wackelig zum stehen kommen um sogleich flink ihre Runden zu drehen.`n');
        if($session['daily']['schlittschuh']==1)
        {
            output('Du überlegst nicht lange, ob du auch noch ein paar Runden drehen solltest, sondern schnallst dir die Schlittschuhe wieder an.');
            addnav('Wege');
            addnav('S?Zum See',$str_filename.'?op=see');
        }
        else
        {
            output('"`dWillst Du auch ein paar Runden auf dem Eis drehen?`f" Eine rauhe Stimme lässt dich den Blick abwenden und auf eine kleine Holzhütte blicken, in der ein Zwerg steht. An der Wand hinter ihm hängen Schlittschuhe, in allen erdenklichen Größen. "`dFür ein kleines Entgeld leihe ich Dir eines meiner Schlittschuhpaare!`f" Die Stirn gerunzelt überlegst du, ob du das Angebot annehmen sollst.');
            addnav('Schlittschuh leihen `^('.$schlittschuh.' Gold)`0',$str_filename.'?op=schlittschuh&act=ausleihen');
            addnav('Wege');
        }
        addnav('Weihnachtsmarkt',$str_filename);
        break;
    }
    //switch act end
    break;
    
case 'geback':
    page_header('Gebäckstand');
    switch ($_GET['act'])
    {
    case 'send':
        if (isset($_POST['message']))
        {
            if ($session['user']['gold']>=$kekse[$_GET['eat']]['price'])
            {
                output("`}Du suchst dir ".$kekse[$_GET['eat']]['name'] ."`} aus und bezahlst direkt. Dann wird es schon einem Boten übergeben, der sich aufmacht, das Paket zum Empfänger zu bringen.`n");
                $session['user']['gold']-=$kekse[$_GET['eat']]['price'];
                $message='`^Ein in goldenes Papier eingeschlagenes Paket wird dir von einem Boten überreicht. Neugierig geworden, packst du es aus. Es ist etwas vom Weihnachtsmarkt, '.$kekse[$_GET['eat']]['send'].'.';
                if ($_GET['eat']!='lebherz')
                {
                    $message .=  '`n`nEinige Worte sind auf einem beiliegenden Zettel geschrieben: `n`n';
                }
                else
                {
                    $message .=  '`n`nEinige Worte sind mit Zuckerschrift auf dieses geschrieben: `n`n';
                }
                $message .= closetags(trim($_POST['message']),'`c`b`i');
                $message .= '`n`n`kFrohes Fest';
                $to=(int)$_GET['to'];
                $from = $session['user']['acctid'];
                systemmail($to,'`4Etwas vom Weihnachtsmarkt',$message,$from);
                addnav('W?Zum Weihnachtsmarkt',$str_filename);
            }
            else
            {
                output("`qDu suchst dir ".$kekse[$_GET['eat']]['name']." aus, doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem leckeren Gebäckstück.");
                addnav('W?Zum Weihnachtsmarkt',$str_filename);
            }
        }
        else
        {
            if ($_GET['eat']=='lebherz')
            {
                output('`}Willst du etwas auf dein Lebkuchenherz schreiben lassen?');
            }
            else
            {
                output('`}Willst du eine Nachricht mitschicken?');
            }
            output('`n`n`0Nachricht(max. 50 Zeichen):');
            rawoutput('<br><br><form action="'.$str_filename.'?op=geback&act=send&eat='.$_GET['eat'].'&to='.$_GET['to'].'" method="POST">
            <input name="message" class="input" maxlength=50>
            <input type="submit" class="button" value="Verschicken">
            </form><br>');
            addnav('',$str_filename.'?op=geback&act=send&eat='.$_GET['eat'].'&to='.$_GET['to']);
            addnav('W?Zum Weihnachtsmarkt',$str_filename);
        }
        //$_POST['message'] end
        break;
        
    case 'ask':
        output('`}Du suchst dir '.$kekse[$_GET['eat']]['name'].' aus. Die freundliche Tante hinter der Theke fragt dich: "`QSoll ich es jemandem als Geschenk einpacken oder willst du es selber essen?`}"');
        output('`n`n`0An jemand verschenken:<form action="'.$str_filename.'?op=geback&act=ask&eat='.$_GET['eat'].'&to='.$_GET['to'].'" method="POST">
        <input name="name" class="input">
        <input type="submit" class="button" value="Suchen">
        </form><br>');
        addnav('',$str_filename.'?op=geback&act=ask&eat='.$_GET['eat'].'&to='.$_GET['to']);
        if(isset($_POST['name']))
        {
            //Gesamtzahl aller angemeldeter Spieler bestimmen
            $search=str_create_search_string($_POST['name']);
            $sql='SELECT acctid,name,login,sex FROM accounts WHERE name LIKE "'.$search.'" ORDER BY login="'.db_real_escape_string($_POST['name']).'" DESC, login ASC LIMIT 100';
            $result = db_query($sql);
            
            $zahl = db_num_rows($result);
            
            if ($zahl>0)
            {
                output('<table>
                <tr class="trhead">
                <th>Name</th>
                <th>m/w</th>
                </tr>');
                //Spieler auflisten
                for ($i=0; $i<$zahl; $i++)
                {
                    $row=db_fetch_assoc($result);
                    output('<tr class="'.($i%2?"trdark":"trlight").'">
                    <td><a href="'.$str_filename.'?op=geback&act=send&eat='.$_GET['eat'].'&to='.$row['acctid'].'">`&'.$row['name'].'`0</a></td>
                    <td align="center"><img src="images/'.($row['sex']?'female':'male').'.gif" alt="m/w"></td>
                    </tr>');
                    addnav('',$str_filename.'?op=geback&act=send&eat='.$_GET['eat'].'&to='.$row['acctid']);
                }
                output('<table><br><br>');
            }
            else
            {
                //Keine Spieler gefunden $zahl<=0
                output('`4Keine Spieler gefunden.');
            }
        }
        //Navigation
        addnav('Selber essen',$str_filename.'?op=geback&act=essen&eat='.$_GET['eat']);
        addnav('An den Baum',$str_filename.'?op=geback&act=dekor&eat='.$_GET['eat']);
        
        break;
    
    case 'essen':
        if ($session['user']['gold']>=$kekse[$_GET['eat']]['price'])
        {
            output('`}Du legst '.$kekse[$_GET['eat']]['price'].' Goldstücke auf den Tresen und bekommst '.$kekse[$_GET['eat']]['name'] .' überreicht. Der Duft steigt dir unwiderstehlich in die Nase, also beginnst du, genüßlich zu essen.`n');
            $session['user']['gold']-=$kekse[$_GET['eat']]['price'];
        }
        else
        {
            output('`qDu suchst dir '.$kekse[$_GET['eat']]['name'].' aus, doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem leckeren Gebäckstück.');
        }
        //Navigation
        addnav('Wege');
        addnav('Gebäckstand',$str_filename.'?op=geback');
        addnav('Weihnachtsmarkt',$str_filename);
        break;
    
    case 'dekor':
        if ($session['user']['gold']>=$kekse[$_GET['eat']]['price'])
        {
            output('`}Du legst '.$kekse[$_GET['eat']]['price'].' Goldstücke auf den Tresen und bekommst '.$kekse[$_GET['eat']]['name'] .' überreicht. Sogleich begibst du dich zum Tannenbaum und hängst es dran.`n');
            $session['user']['gold']-=$kekse[$_GET['eat']]['price'];
            insertcommentary($session['user']['acctid'],': '.$emotecolor.'hängt '.$kekse[$_GET['eat']]['name'].$emotecolor.' an den Baum.'.$message,'Weihnachtsbaumschmuk');
        }
        else
        {
            output('`qDu suchst dir '.$kekse[$_GET['eat']]['name'].' aus, doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem leckeren Gebäckstück.');
        }
        //Navigation
        addnav('Wege');
        addnav('Gebäckstand',$str_filename.'?op=geback');
        addnav('Weihnachtsmarkt',$str_filename);
        break;
        
        default:
        output('`c`b`&W`Ue`tih`Una`}chts`Uge`tbä`Uc`&k`b`c
        `n`}"`QLebkuchenhäuschen, Plätzchen, oder einen Zimtstern?`}" Der Ruf erreicht dein Ohr, noch bevor die feinen Düfte der Köstlichkeiten deine Nase kitzeln können. Du hältst bei deinem Streifzug über den Markt inne und schlenderst an den kleinen Verkaufsstand, um verträumt die vielen Köstlichkeiten zu betrachten. Lebkuchenhäuschen stehen da, mit weißem Zuckerguss bedeckt und gar wunderbar verziert. Aber auch die Plätzchen, deren süßer Geruch nach Schokolade und Zimt in deine Nase steigt, haben es dir angetan. Oder doch einen der Zimtsterne, die gar lieblich angerichtet auf einem Teller auch dem Auge einen herrlichen Anblick bieten? Wenn du etwas Geld in deiner Tasche hast, dann greif\' doch zu und kauf\' eines der köstlichen Gebäcke.');
        addnav('Was suchst du dir aus?');
        //Auflistung der möglichen Optionen
        foreach ($kekse as $key=> $val)
        {
            addnav($val['name'].' - `^'.$val['price'].' Gold`0',$str_filename.'?op=geback&act=ask&eat='.$key);
        }
        //Navigation
        addnav('Wege');
        addnav('Weihnachtsmarkt',$str_filename);
        break;
    }
    //switch act end
    break;
    
case 'gluh':
    page_header('Glühweinstand');
        output('`c`b`$Gl`4üh`Lwe`Xin`Lst`4an`$d`b`c`n');
    switch ($_GET['act'])
    {
    case 'trinken':
        if ($session['user']['gold']>=$drinks[$_GET['drink']]['price'])
        {
            output('`IDu bestellst dir '.$drinks[$_GET['drink']]['name'] .' `Iund schon wird dir ein Becher mit dem Gewünschten gereicht. Du legst das Gold auf die Theke und wendest dich den anderen Leuten hier zu, um mit ihnen zu reden.`n`n');
            $session['user']['gold']-=$drinks[$_GET['drink']]['price'];
            $session['user']['drunkenness']+=2;
        }
        else
        {
            output('`IDu bestellst dir '.$drinks[$_GET['drink']]['name'].', doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem leckeren Getränk.`n`n');
        }
        //Navigation
        addnav('Wege');
        addnav('Glühweinstand',$str_filename.'?op=gluh');
        addnav('Weihnachtsmarkt',$str_filename);
        break;
        
        default:
        output('`IFein liegt der Duft von Orangen, Zimt und Zitrone in der Luft, als du dich dem kleinen Stand näherst, um welchen sich bereits eine kleine Menschenmenge versammelt hat. Die Menschen halten tönerne Becher in den Händen. Dampf steigt aus ihnen hervor, der herrlich weihnachtlich duftet und deine Nase kitzelt. Zu schmecken scheint das heiße Gebräu auch, nippen die Menschen doch gar genüsslich an ihren Krügen.
        `nMit flinken Schritten schlängelst du dich an ein paar der Menschen vorbei und trittst an die Theke des kleinen Standes. Der Verkäufer lächelt dir zu und schnappt sich einen der Krüge, wobei er dich erwartungsvoll ansieht. "`XWas darf ich Dir zu trinken anbieten. Ich habe vieles, was bei dem kalten Winterwetter den Körper wärmt.`I" Mit einem Nicken weist er auf ein Schild, auf welchem mit weißer Kreide die verschiedenen Getränke geschrieben stehen, die im Angebot sind. Frohlockend betrachtest du die Liste und überlegst, was du dir einverleiben sollst, klingt doch alles wahrlich köstlich.`n`n`0');
        addnav('Was willst du bestellen?');
        //Auflistung der möglichen Optionen
        foreach ($drinks as $key=> $val)
        {
            addnav($val['name'].' - `^'.$val['price'].' Gold`0',$str_filename.'?op=gluh&act=trinken&drink='.$key);
        }
        //Navigation
        addnav('Wege');
        addnav('Weihnachtsmarkt',$str_filename);
        
        break;
    }
    //switch act end
    viewcommentary('gluhweinstand','`n`n`6Ausgelassen reden:`n',15);
    break;
    
case 'snowman': //Schneemann bauen
    page_header('Schneemann bauen');
    $author_info='Salator (atrahor.de)';
    output(get_title('`fSchneeman bauen'));
    output('`sEtwas abseits, wo noch frischer Schnee liegt, ist ein Platz auf dem viele Schneemänner stehen.'.'
    `nDer Schneemann von '.getsetting('best_snowman_name','Salator').'`s sticht als größter Schneemann deutlich hervor. Auch du kannst einen Schneemann bauen, das wird dich aber bestimmt einiges an Zeit kosten.
    `nWieviele Waldrunden möchtest du opfern?`0
    `n`n<form action="'.$str_filename.'?op=snowman2" method="POST">
    Für <input type="text" size=2 maxlength=2 name="turns"> Runden 
    <input type="submit" class="button" value="Schneeman bauen">
    </form>
    <img src="templates/dawn/fog_bottom_left-winter.png" align="right" alt="Schneemann">
    `n`n`n`n`n');
    addnav('',$str_filename.'?op=snowman2');
    addnav('Wege');
    addnav('Weihnachtsmarkt',$str_filename);
    break;

case 'snowman2': //Schneemann bauen fertig
    page_header('Schneemann bauen');
    $author_info='Salator (atrahor.de)';
    output(get_title('`fSchneeman bauen'));
    if($_POST['turns']>$session['user']['turns'] || $_POST['turns']<1){
        output('`qIrgendwie hast du dich etwas überschätzt. Das was du bis zum Abend geleistet hast gleicht eher einem Krater wie er bei fehlgeschlagenen Zauber-Versuchen entsteht.');
        $session['user']['turns']=0;
    }
    else
    {
        $best=getsetting('best_snowman_height',1);
        $session['user']['turns']-=$_POST['turns'];
        if ($best>1)
        {
            $best --;
        }
        output('Nachdem du '.$_POST['turns'].' Stunden an deinem Schneemann gebaut hast kannst du ein wirklich schönes Exemplar vorweisen. ');
        if(item_count('tpl_id = "schokosalator" AND owner ='.$session['user']['acctid']) > 0 && mt_rand(1,4) == 4)
        {
            output('`nMit einem Male kommt dir eine finstere kleine Idee, denn du erinnerst dich an den Schokosalator in deiner Tasche. Der wäre die perfekte Vorlage. Kurzerhand schälst du den kleinen Mann aus seiner silbrigen Folienverpackung. Mit solch einer tollen Vorlage ist deine Motivation natürlich extra groß und du baust deinen Schneemann gleich um einen Meter größer als zuvor! Zufrieden mit deinem Werk beisst du dem Schokosalator den Kopf ab. Etwas kalt, dennoch köstlich! ');
            $_POST['turns'] += 10;
            item_delete('tpl_id = "schokosalator" AND owner ='.$session['user']['acctid'],1);
        }
        if($best<=$_POST['turns'])
        {
            output('Damit hast du im Moment den `bgrößten Schneemann`b gebaut! Schade nur, dass dieser Sieg nicht von Dauer ist. Aber dein Charme steigt.');
            savesetting('best_snowman_name',$session['user']['name']);
            $best=$_POST['turns'];
            $session['user']['charm']++;
        }
        else
        {
            output('Aber leider kannst du damit bei diesem Wettbewerb nicht gewinnen.');
        }
        savesetting('best_snowman_height',$best);        
    }
    addnav('Wege');
    addnav('Weihnachtsmarkt',$str_filename);
    break;

case 'snowball': //Schneeballschlacht
    page_header('Schneeball werfen');
    $author_info='Idee by Thoram (plueschdrache.de)';
    $arr_targets=explode(',',getsetting('snowball_persons','Violet,Seth,Dag Durnick,Cedrik,Pegasus,Thorim,Merik,Vessa,Petersen,`^Schneemann`0,einen Spatz'));
    $arr_targets['9']='`*'.strip_appoencode(getsetting('best_snowman_name','Urstrahlung')).'`*s Schneemann';
    if($_GET['act']=='throw' && $session['daily']['snowball']>0)
    { //Schneeball geworfen
        $session['daily']['snowball']--;
        if(in_array($session['user']['name'],$arr_targets))
        { //User ist schon ein Ziel
            $dice=e_rand(1,9);
            if($dice==1)
            {
                insertcommentary($session['user']['acctid'],':`7 wirft einen Schneeball so hoch, dass ein Moorhuhn vom Himmel stürzt.','Schneeballwerfen');
                $str_output='`0Weil du so dicht am Schneemann stehst denkst du dir, du musst sehr steil nach oben werfen um ihn zu treffen. Dein Schneeball fliegt höher und höher, bis er mit irgendwas zusammenstößt. Wenig später fällt dir ein Moorhuhn vor die Füße.`n`n';
            }
            elseif($dice==2)
            {
                insertcommentary(1,'/msg`QIrgendjemand hat gerade ein Lebkuchenmännchen mit einem Eiszapfen durchbohrt.','Schneeballwerfen');
                $str_output='`QDu wirfst deinen Schneeball und triffst die Gebäck-Bude. Ein Eiszapfen löst sich vom Dach und durchbohrt ein Lebkuchenmännchen!`n`n';
            }
            elseif($dice==8)
            {
                insertcommentary($session['user']['acctid'],':`7 wirft sich selbst einen Schneeball ins Gesicht.','Schneeballwerfen');
                $str_output='`QDu wirfst deinen Schneeball und triffst das Ziel, welches dir am nächsten ist - DICH SELBST!`n`n';
            }
            elseif($dice==9)
            {
                insertcommentary($session['user']['acctid'],':`6 verfehlt Thoram um Haaresbreite!','Schneeballwerfen');
                $str_output='`^Glückwunsch!`0 Du hast Thorams Stand getroffen! Und das ist dein Preis: Der Anblick eines wild schimpfenden Zwerges.`n`n';
            }
            else
            {
                $str_output='`0Du wirfst deinen Schneeball in Richtung Markt und er schlägt auf einer freien Fläche ein.`n`n';
            }
        }
        else
        { //User ist nicht selbst Ziel
            $arr_where=array('s Pudelmütze.',' genau auf die Nase.',' am Oberkörper.','s rechten Arm.',' voll in den Schritt!');
            $dice=e_rand(0,9);
            $dice2=e_rand(0,4);
            if($dice<9)
            {
                insertcommentary($session['user']['acctid'],':`7 trifft '.$arr_targets[$dice].'`7'.$arr_where[$dice2],'Schneeballwerfen');
                $str_output='Ach du Sch...ande! Du hast '.$arr_targets[$dice].'`y getroffen! Gesenkten Hauptes nimmst du dessen Platz ein.`n`n';
                $arr_targets[$dice]=$session['user']['name'];
                savesetting('snowball_persons',implode(',',$arr_targets));
            }
            elseif($dice==9)
            {
                insertcommentary($session['user']['acctid'],':`^ trifft zielsicher auf '.$arr_targets[$dice],'Schneeballwerfen');
                $str_output='`^Glückwunsch!`0 Du hast den Schneemann getroffen! Und das ist dein Preis: 200 Goldstücke.`n`n';
                $session['user']['gold']+=200;
            }
            else
            {
                insertcommentary($session['user']['acctid'],':`7 wirft einen Schneeball so hoch, dass ein Moorhuhn vom Himmel stürzt.','Schneeballwerfen');
            }
        }
    }
    else
    { //kein Schneeball geworfen
        $str_output='"'.$emotecolor.'Das ist ja einfach!`y" denkst du dir.`n`n';
    }
    output('`c`b`fSchneeball werfen`0`b
    `n`n<table border="0" width=90%>
    <colgroup width="12.5%" span="8">
    <tr>
        <td colspan=3>&nbsp;</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[0].'`0</td>
        <td colspan=3>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=2>&nbsp;</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[1].'`0</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[2].'`0</td>
        <td colspan=2>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=1>&nbsp;</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[3].'`0</td>
        <td colspan=2 style="border:1px solid red;background-color:#440000;text-align:center;">`*'.$arr_targets[9].'`0</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[4].'`0</td>
        <td colspan=1>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[7].'`0</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[5].'`0</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[6].'`0</td>
        <td colspan=2 style="border:1px solid grey;text-align:center;">`t'.$arr_targets[8].'`0</td>
    </tr>
    </table>
    `c
    `n`QTh`qor`yam, der Bruder des Waffenhändlers, hat hier seinen Stand aufgebaut und verkauft Schneebälle. Drei Schneebälle kosten `^50 Gold`y.
    `nDie Regeln sind einfach: Wirf deinen Schneeball auf den Schneemann! Wenn du einen der umstehenden Bewacher triffst, musst du dessen Stelle einnehmen. Für einen Treffer auf den Scheemann gibt dir der Zwerg `^200 Gold`y.
    `n`n'.$str_output);
    viewcommentary('Schneeballwerfen');
    addnav('Aktionen');
    addnav('k?Schneebälle kaufen',$str_filename.'?op=buysnowball');
    if($session['daily']['snowball']>0)
    {
        addnav('w?Schneeball werfen ('.(int)$session['daily']['snowball'].'x)',$str_filename.'?op=snowball&act=throw');
    }
    addnav('Wege');
    addnav('Weihnachtsmarkt',$str_filename);
    break;

case 'buysnowball': //Schneebälle kaufen
    page_header('Schneebälle kaufen');
    $author_info='Idee by Thoram (plueschdrache.de)';
    addnav('Wege');
    output('`c`b`fSchneebälle kaufen`0`b`c`n');
    if($session['user']['gold']>=50)
    {
        output('`QTh`qor`yam`s verlangt für 3 Schneebälle `^50 Goldstücke`s. Du fragst dich, warum du nicht einfach selbst Schneebälle formen solltest. Schließlich wäre das kostenlos. Aber dann fällt dir auf dass du der einzige Geizkragen hier wärst und auch kein Preisgeld bekommen würdest. Also greifst in deinen Goldbeutel und bezahlst.
        `nUnd mal ehrlich, Thorams Schneebälle sehen einfach perfekt aus. Die möchte man am liebsten gar nicht werfen.
        `nJedoch würde es dir nichts nützen, die Schneebälle aufzuheben, denn Schnee schmilzt nun mal.');
        $session['user']['gold']-=50;
        $session['daily']['snowball']+=3;
        addnav('w?Schneeball werfen',$str_filename.'?op=snowball&act=throw');
    }
    else
    {
        output('`IDu bestellst dir 3 Schneebälle, doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einer lustigen Schneeballschlacht.`n`n');
    }
    addnav('Weihnachtsmarkt',$str_filename);
    break;

case 'xmastree': //Weihnachtsbaum schmücken
    page_header('Weihnachtsbaum schmücken');
    $author_info='Salator (atrahor.de)';
    output(get_title('`2Der We`$i`2hnachtsbaum').'
    `jDu trittst noch näher an den wohlgeschmückten Baum. Da hat doch jemand... Also wirklich, einen Fischkopf an den Weihnachtsbaum zu hängen... 
    `nDu denkst jedoch nicht weiter über den Sinn eines Fischkopfes nach, sondern überlegst, welches deiner Fundstücke du an den Baum hängen könntest.');

    $lastuser=db_fetch_assoc(db_query('SELECT author FROM commentary WHERE section="Weihnachtsbaumschmuk" AND self=0 ORDER BY commentid DESC'));
    if($lastuser['author']==$session['user']['acctid'])
    {
        output('`n`UDu hast aber gerade erst etwas an den Baum gehängt, du darfst jetzt nicht schonwieder. Oder willst du, dass der Baum zusammenbricht?`n`n');
    }
    else
    {
        $sql='SELECT id,name,gold,gems 
            FROM items i 
            LEFT JOIN items_tpl it USING( tpl_id ) 
            WHERE it.tpl_class='.$tpl_class_beute.'
                AND i.owner='.$session['user']['acctid'].'
                AND deposit1=0
                AND deposit2=0
            ORDER BY i.special_info="Christbaumschmuck" DESC, i.name ASC
            LIMIT 100';
        $result=db_query($sql);
        output('`n`n`0
        <table border=0 align="center">
        <tr class="trhead">
        <th>Ding</th>
        <th>Wert</th>
        <th>Aktion</th>
        </tr>');
        $str_output='<tr class="trlight">
        <td>`&Schneeflocke`0</td>
            <td align="center">`^0`0+`#0`0</td>
            <td>'.create_lnk('Aufhängen',$str_filename.'?op=xmastree2&id=0').'</td>
        </tr>';
        while ($row=db_fetch_assoc($result))
        {
            $trclass=$trclass=='trdark'?'trlight':'trdark';
            $str_output.='<tr class="'.$trclass.'">
            <td>'.$row['name'].'`0</td>
            <td align="center">`^'.$row['gold'].'`0+`#'.$row['gems'].'`0</td>
            <td>'.create_lnk('Aufhängen',$str_filename.'?op=xmastree2&id='.$row['id']).'</td>
            </tr>';
        }
        output($str_output.'</table></form>`n`n');
    }
    viewcommentary('Weihnachtsbaumschmuk');
    addnav('Wege');
    addnav('Weihnachtsmarkt',$str_filename);
    break;

case 'xmastree2': //Dinge aufhängen fertig
    page_header('Weihnachtsbaum schmücken');
    $author_info='Salator (atrahor.de)';
    output(get_title('`2We`$i`2hnachtsbaum schm`$ü`2cken'));
    $item['id']=intval($_GET['id']);
    if($item['id']>0)
    {
        $item=item_get('id='.$item['id']);
        output('`jDu greifst in deinen Beutel und findest darin '.$item['name'].'`j. "'.$emotecolor.'Perfekt!`j" denkst du dir und suchst eine hübsche Stelle um es aufzuhängen.`n`n');
        if($item['special_info']!='Christbaumschmuck')
        {
            $trashitems=(int)getsetting('xmas_trashitems',0);
            $trashitems++;
            if($trashitems>=250)
            {
                $message=' `$Mit einem lauten Krachen bricht der Weihnachtsbaum zusammen. Offenbar wurde `DZU VIEL`$ drangehängt.';
                $trashitems=0;
            }
            elseif($trashitems==240)
            {
                $message=' `4Der Weihnachtsbaum schwankt bedrohlich.';
            }
            savesetting('xmas_trashitems',$trashitems);
        }
        insertcommentary($session['user']['acctid'],': '.$emotecolor.'hängt '.$item['name'].$emotecolor.' an den Baum.'.$message,'Weihnachtsbaumschmuk');
        item_delete('id="'.$item['id'].'"');
    }
    else
    {
        output('`sDu verzierst den Baum mit ein paar Schneeflocken. Weiter so, '.($session['user']['sex']?'Frau':'Herr').' Holle!`n`n');
    }
    viewcommentary('Weihnachtsbaumschmuk');
    addnav('Wege');
    addnav('Weihnachtsmarkt',$str_filename);
    break;

default:
    page_header('Weihnachtsmarkt');
    $session[user][standort]="Weihnachtsmarkt";
    output('`c`b`2We`kih`In`da`Dc`$ht`Ds`dm`Ia`krk`2t`b`c
    `n`2Schon beim Betreten des Weihnachtsmarktes, auf welchem sich die kleinen Buden dicht aneinander drängen, bemerkst du den edlen Baum, der wohlgeschmückt die Mitte des Platzes ziert. Engelshaar glitzert silbern im Licht, große rote Kugeln, saftige Äpfel und Lebkuchenherzen sind an die nadelbesetzten Äste gebunden, während rote Kerzen das Tannengrün in ein edles Licht tauchen. Viele Kinder haben sich hier versammelt, betrachten mit großen Augen den prächtigen Baum. Und jetzt, wo du näher herangetreten bist, entdeckst du die kleinen Pakete, die in dem Baum versteckt sind.
    `n`n`gAuf einer freien Fläche, wo noch viel frischer Schnee liegt, läuft ein Wettbewerb wer den `bgrößten Schneemann`b baut. Das beste Exemplar ist zur Zeit von '.getsetting('best_snowman_name','Urstrahlung').'`g mit '.(getsetting('best_snowman_height',10)/10).'m Höhe.
    `n`n`2Tief atmest du die kalte Luft ein, während deine Schuhe knirschende Geräusche im Schnee verursachen. Von weitem dringt der Gesang von einem Kinderchor an deine Ohren. Doch deine Augen bleiben an dem Baum hängen. Jedem ist es erlaubt hier ein Geschenk heraus zu nehmen. Aber streng nur eines!');
    //Navigation
    addnav('Aktionen');
    if(date('d')>=23)
    {
        addnav('v?Baum verzieren',$str_filename.'?op=xmastree');
    }
    if(date('d')<=24)
    {
        addnav('Türchen öffnen','weihnachtskalender.php?op=showpic');
    }
    else
    {
        addnav('Türchen öffnen','weihnachtskalender.php');
    }
    addnav('Stände');
    addnav('w?Glühweinstand',$str_filename.'?op=gluh');
    addnav('b?Gebäckstand',$str_filename.'?op=geback');
    addnav('S?Gefrorener See',$str_filename.'?op=schlittschuh');
    addnav('m?Schneemann bauen',$str_filename.'?op=snowman');
    addnav('Schneeballschlacht',$str_filename.'?op=snowball');
    addnav('');
    break;
}
//switch op end

output('`0<br><br><br><br><span style="font-size:x-small;  text-align: center;">`1Original &copy; '.$author_info.'`0</span>');
addnav('Zurück zum Markt','market.php');
page_footer();
?>

