<?php


header('Content-Type: text/html; charset=utf-8');
/*
Weihnachtsmarkt
Ursprünglich für: isarya-logd.de.vu von Naria Talcyr
Idee: Nymh, Inistha, Rhao
Texte: Jezebel, Nymh

Features:
- Gebäck kaufen
- Getränke kaufen
- Schlittschuhe ausleihen, um zum gefrorenem See zu kommen (neuer RP Ort)
- Gebäck verschicken (+Nachricht)
- Geschenk vom Weihnachtsbaum nehmen

Geplant:
-/-

Auszuführende SQL:
ALTER TABLE `accounts` ADD `special_taken` TINYINT ( 1 ) UNSIGNED NOT NULL DEFAULT '0';

*/
require_once "common.php";

addcommentary();

//Sachen für den Glühweinstand, einfach eintragen, fügen sich selber hinzu
$drinks=array(
                'gluh'=> array('name'=>'Glühwein','price'=>150)
        ,'gluham'=> array('name'=>'Glühwein mit Schuss','price'=>200)
                ,'apfel'=>array('name'=>'heißen Apfelwein','price'=>200)
                ,'met'=>array('name'=>'warmen Met','price'=>100)
                );

//Sachen für den Gebäckstand, einfach eintragen, fügen sich selber hinzu         
$kekse=array(
                'lebherz'=> array('name'=>'Lebkuchenherzen','price'=>50,'send'=>'ein Lebkuchenherz')
                ,'zimt'=>array('name'=>'Zimtsterne','price'=>50,'send'=>'eine Packung Zimtsterne')
                ,'lebhaus'=>array('name'=>'ein Lebkuchenhaus','price'=>200,'send'=>'ein Lebkuchenhaus')
                ,'schokokeks'=>array('name'=>'Schokoladenkekse','price'=>75,'send'=>'eine Packung Schokoladenkekse')                
                ,'printen'=>array('name'=>'Printen','price'=>60,'send'=>'eine Handvoll Printen')       
                ,'mandel'=>array('name'=>'gebrannte Mandeln','price'=>30,'send'=>'ein Beutel gebrannter Mandeln')
                ,'zucker'=>array('name'=>'eine Zuckerstange','price'=>20,'send'=>'eine Zuckerstange')          
                );
                
//Sachen für den Geschenkestandstand, einfach eintragen, fügen sich selber hinzu         
$weihnachtsgeschenk=array(
        'leuchtstern'=>array('name'=>'Leuchtstern','price'=>825,'send'=>'ein Leuchtstern')
                ,'pullover'=> array('name'=>'Weihnachtssweater','price'=>750,'send'=>'ein dicker Pulli mit Weihnachtsmotiven')
                ,'wein'=>array('name'=>'edler Wein','price'=>500,'send'=>'eine Flasche edler Wein')
                ,'haushaltsgerät'=>array('name'=>'Haushaltsgerät','price'=>450,'send'=>'ein Haushaltsgerät')
                ,'holzspiel'=>array('name'=>'Holzspielzeug','price'=>200,'send'=>'ein Holzspielzeug')
                ,'schal'=>array('name'=>'dicker Schal','price'=>175,'send'=>'ein dicker Schal')                
                ,'handschuh'=>array('name'=>'Handschuhe','price'=>160,'send'=>'ein paar dicke Wollhandschuhe')       
                ,'fotoalbum'=>array('name'=>'Fotoalbum','price'=>100,'send'=>'ein Fotoalbum')
                ,'kerze'=>array('name'=>'eine Duftkerze','price'=>50,'send'=>'eine Duftkerze')          
                );



//Preis fürs Schlittschuhlaufen
$schlittschuh=10; 

//Spieler pro Seite
$player=20;

//Geschenkmenge
$gold=3000;
$gems=10;
$charme=100;

switch($_GET['op']){
case 'see':
    page_header('Der gefrorene See');
    output('`c`b`9Der gefrorene See`b`c`n`n`oFröhlich fast glitzert die Eisdecke auf dem Großen See des Dorfes. Auf der festen Schicht sind kleine Linien zu sehen, verursacht durch '
                .'Eisenschienen, befestigt an ledernen Schuhen, die die Kinder tragen, wenn sie sich auf das Eis wagen um ihre Runden zu fahren.`nDoch ihr irrt euch, denkt ihr, dass nur '
                .'Kinder sich diesen Spaß erlauben. So manches Liebespaar zieht gemeinsam seine Kreise aber auch Freunde sind zusammen auf dem spiegelglatten Eis unterwegs, '
                .'lachen ausgelassen. Schließ dich doch ihnen an!`n`n`n');
    viewcommentary('gefrorener See','`n`n`n`§Lachen und Scherzen:`n`n',15);
output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");
    addnav('Wege');
    addnav('Weihnachtsmarkt','weihnachten.php');
    addnav('Zurück ins Dorf','village.php');
    addnav('');
    addnav('+?Aktualisieren','weihnachten.php?op=see');

break;
case 'schlittschuh':
    page_header('Schlittschuhe');
    switch($_GET['act']){
    case 'ausleihen':
             if($session['user']['gold']>=$schlittschuh){
            output("`kDu suchst dir ein Paar aus und bezahlst die Verleihgebühr von `^".$schlittschuh." `0direkt. Du läufst mit dem Paar zum See, setzt dich auf eine Bank und ziehst sie an. "
            ."Ein wenig wackelig kommst du dir vor, doch wagst du dich dennoch aufs Eis.`n`n`n");



output("`n`n`%`mIn der Nähe reden einige Besucher:`n");
viewcommentary("schlittschuh","Hinzufügen",25);





output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");
            $session['user']['gold']-=$schlittschuh;
            addnav('Zum See','weihnachten.php?op=see');     
            }else{
            output("`kDu suchst dir ein Paar aus,doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einer Runde "
            ."Runde Schlittschuhlaufen.");
            addnav('Wege');     
            addnav('Weihnachtsmarkt','weihnachten.php');
            addnav('Zurück ins Dorf','village.php'); 
            }

    break;
    default:
        output('`c`b`&Sc`khl`Sit`)ts`7ch`Kuhe`b`c`n`n`kKinder drängen sich an dir vorbei, lachend und ausgelassen rennend, in den Händen Schlittschuhe haltend, mit  denen sie in Richtung '
                    .'des gefrorenen Sees davon laufen um darauf, wie es bereits schon viele andere tun, ihre  Runden zu drehen. Lächelnd siehst du ihnen dabei zu, wie sie sich die Schuhe anziehen '
                    .'und dann auf dem Eis erst  wackelig zum stehen kommen um sogleich flink ihre Runden zu drehen.`n"Wollt Ihr auch Eure Runden auf dem Eis drehen?" Eine rauhe Stimme lässt '
                    .'dich den Blick abwenden und auf eine kleine Holzhütte blicken, in der ein Zwerg steht, an der Wand hinter ihm hängen Schlittschuhe, in allen erdenklichen Größen.  "Für ein kleines '
                    .'Entgeld leihe ich dir eines meiner Schlittschuhpaare!" Die Stirn gerunzelt überlegst du, ob du das Angebot annehmen sollst.`n `n');






        addnav('Ausleihen - `^'.$schlittschuh.' Gold`0','weihnachten.php?op=schlittschuh&act=ausleihen');
            //Navigation
        addnav('Wege');
        addnav('Weihnachtsmarkt','weihnachten.php');
        addnav('Zurück ins Dorf','village.php');
    break;    
    }//switch act end
break;
case 'geback':
    page_header('Gebäckstand');
    switch($_GET['act']){
    case 'send':
            if(isset($_POST['message'])){
                if($session['user']['gold']>=$kekse[$_GET['eat']]['price']){
                   output("Du suchst dir ".$kekse[$_GET['eat']]['name'] ." aus und bezahlst direkt. Dann wird es schon einem Boten übergeben, der sich aufmacht das Paket zum Empfänger zu bringen.`n");
                   $session['user']['gold']-=$kekse[$_GET['eat']]['price'];
                   $message='Ein in goldene Papier eingeschlagenes Paket wird dir von einem Boten überreicht. Neugierig geworden, packst du es aus. Es ist etwas vom '
                                         .'Weihnachtsmarkt,'.$kekse[$_GET['eat']]['send'].'.'; 
                   if($_GET['eat']!='lebherz')$message .=  '`n`nEinige Worte sind auf einem Zettel dabei geschrieben: `n`n';
                   else $message .=  '`n`nEinige Worte sind mit Zuckerschrift auf diese geschrieben.: `n`n';
                   $message .= strip_tags(trim($_POST['message']));
                   $message .= '`n`nFrohes Fest';
                   $to=(int)$_GET['to'];
                   $from = $session['user']['acctid'];
                   systemmail($to,'`4Etwas vom Weihnachtsmarkt',$message,$from);
                   addnav('Zum Markt','weihnachten.php');
                }else{
                    output("Du suchst dir ".$kekse[$_GET['eat']]['name']." aus,doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem "
                    ."leckeren Gebäckstück.");
                    addnav('Zum Markt','weihnachten.php');
                }    
            }else{
                if($_GET['eat']=='lebherz') output('Willst du etwas auf dein Lebkuchenherz schreiben lassen?');
                else output('Willst du eine Nachricht mitschicken?');
                output('`n`nNachricht(max. 300 Zeichen):');
                rawoutput('<br><br><form action="weihnachten.php?op=geback&act=send&eat='.$_GET['eat'].'&to='.$_GET['to'].'" method="POST">'
                             .'<input name="message" class="input" maxlength=300><input type="submit" class="button" value="Verschicken"></form><br>');
                addnav('',"weihnachten.php?op=geback&act=send&eat=".$_GET['eat'].'&to='.$_GET['to']);
                addnav('Zum Markt','weihnachten.php');
            }//$_POST['message'] end
    break;
    case 'ask':
            output("Du suchst dir ".$kekse[$_GET['eat']]['name']." aus. Was willst du nun damit tun? Jemandem schicken oder selber essen? `n`n");
            rawoutput('<br><br><form action="weihnachten.php?op=geback&act=ask&eat='.$_GET['eat'].'&to='.$_GET['to'].'" method="POST">'
                             .'<input name="name" class="input"><input type="submit" class="button" value="Suchen"></form><br>');
           addnav('',"weihnachten.php?op=geback&act=ask&eat=".$_GET['eat'].'&to='.$_GET['to']);
            //Gesamtzahl aller angemeldeter Spieler bestimmen
            $anzahl=db_query("SELECT `acctid` FROM `accounts`");
            $ges=db_num_rows($anzahl);
            $search="%";
            for ($x=0;$x<strlen($_POST['name']);$x++){
                $search .= substr($_POST['name'],$x,1)."%";
            }
            $search=" AND name LIKE '".addslashes($search)."' ";
            $result = db_query($sql) or die(sql_error($sql));
            $max = db_num_rows($result);  

            if($_GET['offset']!='' && ($_POST['name']=='' || $max<=0)){            
                $result=db_query("SELECT `name`,`acctid`,`login` FROM `accounts` WHERE locked=0 ORDER BY name,acctid ASC LIMIT ".$_GET['offset']." , ".$player);
            }else{
                $result=db_query('SELECT `name`,`acctid`,`login` FROM `accounts` WHERE locked=0 '.$search.' ORDER BY name,acctid ASC LIMIT 0,'.$player);
            }
            $zahl = db_num_rows($result);

            if($zahl>0){
                rawoutput('<table><tr class="trhead"><td>Name</td></tr>');
                  //Spieler auflisten
                for($i=0;$i<$zahl;$i++){
                     $row=db_fetch_assoc($result);
                     rawoutput('<tr class="'.($i%2?"trdark":"trlight").'"><td>');
                     output("<a href='weihnachten.php?op=geback&act=send&eat=".$_GET['eat']."&to=".$row['acctid']."'>`&".$row['name']."`0</a>",true);
                     rawoutput('</td></tr>');
                     addnav('',"weihnachten.php?op=geback&act=send&eat=".$_GET['eat']."&to=".$row['acctid']);
                }
                rawoutput('<table><br><br>'); 

                  
                 // Zurück Link                  
                if($_GET['offset']>0){
                     $offset=$_GET['offset']-$player;
                     if($offset<1)$offset=0;
                     rawoutput("<a href='weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']."&offset=".$offset."'><|Vorherige Seite</a>");

                     addnav('',"weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']."&offset=".$offset);
                }            
                  output('`& |----|`0');                
                 $offset=$_GET['offset']+$player;
                 //Vor Link
                if($_GET['offset']!='' && ($offset+1)<=$ges){
                     rawoutput("<a href='weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']."&offset=".$offset."'>Nächste Seite|></a>");
                     addnav('',"weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']."&offset=".$offset);
                }elseif(($offset+1)<=$ges){
                     rawoutput("<a href='weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']."&offset=".$player."'>Nächste Seite|></a>");
                     addnav('',"weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']."&offset=".$player);
                }                    
            }else{ //Keine Spieler gefunden $zahl<=0
                output('`4Keine Spieler gefunden, bitte dem Admin Bescheid geben');
            }        
            //Navigation
            addnav('Alle anzeigen',"weihnachten.php?op=geback&act=ask&eat=".$_GET['eat']);
            addnav('Selber essen',"weihnachten.php?op=geback&act=essen&eat=".$_GET['eat']);
           
    break;
    case 'essen':
            if($session['user']['gold']>=$kekse[$_GET['eat']]['price']){
                output("Du suchst dir ".$kekse[$_GET['eat']]['name'] ." aus und bezahlst direkt. Sorgfältig eingepackt wendest du dich wieder dem Markt zu, während du genüsslich "
                ."beginnst zu essen.`n");
                $session['user']['gold']-=$kekse[$_GET['eat']]['price'];
            }else{
                output("Du suchst dir ".$kekse[$_GET['eat']]['name']." aus,doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem "
                ."leckeren Gebäckstück.");
            }    
                //Navigation
            addnav('Wege');
            addnav('Gebäckstand','weihnachten.php?op=geback'); 
            addnav('Geschenkestand','weihnachten.php?op=geschenk');    
            addnav('Weihnachtsmarkt','weihnachten.php');
            addnav('Zurück ins Dorf','village.php');
    break;
    default:  
    output('`c`b`ÝW`Ge`Xi`Ýh`Gn`Xa`Ýc`Gh`Xt`Ýs`Gg`Xe`Ýb`Gä`Xc`Ýk`b`c`n`n`F"Lebkuchenhäuschen, Plätzchen, oder einen Zimtstern?" Der Ruf erreicht dein Ohr noch bevor der feine Duft der Köstlichkeiten deine '
     .'Nase kitzeln können. Du hälst bei deinem Streifzug über den Markt inne und schlenderst an den kleinen Verkaufsstand um verträumt die vielen Köstlichkeiten zu betrachten. ' 
     .'Lebkuchenhäuschen stehen da, mit weißem Zuckerguss bedeckt und gar wunderbar verziert. Aber auch die Plätzchen, deren süßer Geruch nach Schokolade und Zimt in deine ' 
     .'Nase steigt, haben es dir angetan. Oder doch ein Stück Gewurzkuchen, der gar lieblich angerichtet auf einem Teller auch dem Auge einen herrlichen Anblick bietet? Wenn du '
     .'etwas Geld in deiner Tasche hast, dann greif doch zu und kauf eines der köstlichen Gebäcke.');
    addnav('Was suchst du dir aus?');


output("`n`n`%`mIn der Nähe reden einige Besucher:`n");
viewcommentary("weihnachtsgeb","Hinzufügen",25);





output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");



    //Auflistung der möglichen Optionen
    foreach($kekse as $key=> $val){
    addnav("{$val['name']} - `^{$val['price']} Gold`0","weihnachten.php?op=geback&act=ask&eat=".$key);
    }
    //Navigation
    addnav('Wege');
    addnav('Weihnachtsmarkt','weihnachten.php');
    addnav('Zurück ins Dorf','village.php');
    break;
    }//switch act end


break;
case 'geschenk':
    page_header('Gebäckstand');
    switch($_GET['act']){
    case 'send':
            if(isset($_POST['message'])){
                if($session['user']['gold']>=$weihnachtsgeschenk[$_GET['eat']]['price']){
                   output("Du suchst dir ".$weihnachtsgeschenk[$_GET['eat']]['name'] ." aus und bezahlst direkt. Dann wird es schon einem Boten übergeben, der sich aufmacht das Paket zum Empfänger zu bringen.`n");
                   $session['user']['gold']-=$weihnachtsgeschenk[$_GET['eat']]['price'];
                   $message='Ein in goldene Papier eingeschlagenes Paket wird dir von einem Boten überreicht. Neugierig geworden, packst du es aus. Es ist etwas vom '
                                         .'Weihnachtsmarkt,'.$weihnachtsgeschenk[$_GET['eat']]['send'].'.'; 
                   if($_GET['eat']!='lebherz')$message .=  '`n`nEinige Worte sind auf einem Zettel dabei geschrieben: `n`n';
                   else $message .=  '`n`nEinige Worte sind mit Zuckerschrift auf diese geschrieben.: `n`n';
                   $message .= strip_tags(trim($_POST['message']));
                   $message .= '`n`nFrohes Fest';
                   $to=(int)$_GET['to'];
                   $from = $session['user']['acctid'];
                   systemmail($to,'`4Etwas vom Weihnachtsmarkt',$message,$from);
                   addnav('Zum Markt','weihnachten.php');
                }else{
                    output("Du suchst dir ".$weihnachtsgeschenk[$_GET['eat']]['name']." aus,doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem "
                    ."Geschenk.");
                    addnav('Zum Markt','weihnachten.php');
                }    
            }else{
                if($_GET['eat']=='lebherz') output('Willst du etwas auf dein Lebkuchenherz schreiben lassen?');
                else output('Willst du eine Nachricht mitschicken?');
                output('`n`nNachricht(max. 300 Zeichen):');
                rawoutput('<br><br><form action="weihnachten.php?op=geschenk&act=send&eat='.$_GET['eat'].'&to='.$_GET['to'].'" method="POST">'
                             .'<input name="message" class="input" maxlength=300><input type="submit" class="button" value="Verschicken"></form><br>');
                addnav('',"weihnachten.php?op=geschenk&act=send&eat=".$_GET['eat'].'&to='.$_GET['to']);
                addnav('Zum Markt','weihnachten.php');
            }//$_POST['message'] end
    break;
    case 'ask':
            output("Du suchst dir ".$weihnachtsgeschenk[$_GET['eat']]['name']." aus.  `n`n");
            rawoutput('<br><br><form action="weihnachten.php?op=geschenk&act=ask&eat='.$_GET['eat'].'&to='.$_GET['to'].'" method="POST">'
                             .'<input name="name" class="input"><input type="submit" class="button" value="Suchen"></form><br>');
           addnav('',"weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat'].'&to='.$_GET['to']);
            //Gesamtzahl aller angemeldeter Spieler bestimmen
            $anzahl=db_query("SELECT `acctid` FROM `accounts`");
            $ges=db_num_rows($anzahl);
            $search="%";
            for ($x=0;$x<strlen($_POST['name']);$x++){
                $search .= substr($_POST['name'],$x,1)."%";
            }
            $search=" AND name LIKE '".addslashes($search)."' ";
            $result = db_query($sql) or die(sql_error($sql));
            $max = db_num_rows($result);  

            if($_GET['offset']!='' && ($_POST['name']=='' || $max<=0)){            
                $result=db_query("SELECT `name`,`acctid`,`login` FROM `accounts` WHERE locked=0 ORDER BY name,acctid ASC LIMIT ".$_GET['offset']." , ".$player);
            }else{
                $result=db_query('SELECT `name`,`acctid`,`login` FROM `accounts` WHERE locked=0 '.$search.' ORDER BY name,acctid ASC LIMIT 0,'.$player);
            }
            $zahl = db_num_rows($result);

            if($zahl>0){
                rawoutput('<table><tr class="trhead"><td>Name</td></tr>');
                  //Spieler auflisten
                for($i=0;$i<$zahl;$i++){
                     $row=db_fetch_assoc($result);
                     rawoutput('<tr class="'.($i%2?"trdark":"trlight").'"><td>');
                     output("<a href='weihnachten.php?op=geschenk&act=send&eat=".$_GET['eat']."&to=".$row['acctid']."'>`&".$row['name']."`0</a>",true);
                     rawoutput('</td></tr>');
                     addnav('',"weihnachten.php?op=geschenk&act=send&eat=".$_GET['eat']."&to=".$row['acctid']);
                }
                rawoutput('<table><br><br>'); 

                  
                 // Zurück Link                  
                if($_GET['offset']>0){
                     $offset=$_GET['offset']-$player;
                     if($offset<1)$offset=0;
                     rawoutput("<a href='weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']."&offset=".$offset."'><|Vorherige Seite</a>");

                     addnav('',"weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']."&offset=".$offset);
                }            
                  output('`& |----|`0');                
                 $offset=$_GET['offset']+$player;
                 //Vor Link
                if($_GET['offset']!='' && ($offset+1)<=$ges){
                     rawoutput("<a href='weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']."&offset=".$offset."'>Nächste Seite|></a>");
                     addnav('',"weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']."&offset=".$offset);
                }elseif(($offset+1)<=$ges){
                     rawoutput("<a href='weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']."&offset=".$player."'>Nächste Seite|></a>");
                     addnav('',"weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']."&offset=".$player);
                }                    
            }else{ //Keine Spieler gefunden $zahl<=0
                output('`4Keine Spieler gefunden, bitte dem Admin Bescheid geben');
            }        
            //Navigation
            addnav('Alle anzeigen',"weihnachten.php?op=geschenk&act=ask&eat=".$_GET['eat']);
            //addnav('Selber essen',"weihnachten.php?op=geschenk&act=essen&eat=".$_GET['eat']);
           
    break;
    case 'essen':
            if($session['user']['gold']>=$weihnachtsgeschenk[$_GET['eat']]['price']){
                output("Du suchst dir ".$weihnachtsgeschenk[$_GET['eat']]['name'] ." aus und bezahlst direkt. Sorgfältig eingepackt wendest du dich wieder dem Markt zu, während du genüsslich "
                ."beginnst zu essen.`n");
                $session['user']['gold']-=$weihnachtsgeschenk[$_GET['eat']]['price'];
            }else{
                output("Du suchst dir ".$weihnachtsgeschenk[$_GET['eat']]['name']." aus,doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem "
                ."leckeren Gebäckstück.");
            }    
                //Navigation
            addnav('Wege');
            addnav('Geschenkestand','weihnachten.php?op=geschenk');    
            addnav('Weihnachtsmarkt','weihnachten.php');
            addnav('Zurück ins Dorf','village.php');
    break;
    default:  
    output('`c`b`G Geschenkestand`b`c`n`n`F'
     .'`nDu erreichst einen kleinen Stand, der allerlei Geschenke zur Auswahl hat. Der Duft von Räucherstäbchen steigt dir in die Nase, als du dich umschaust und dich schon dabei wunderst, wer diesen Kram wirklich ' 
     .'gebrauchen kann. Und dennoch findest du hier und da auch ein paar Dinge, die du ganz hübsch und praktisch findest. ');
    addnav('Was suchst du dir aus?');


output("`n`n`%`mIn der Nähe reden einige Besucher:`n");
viewcommentary("wgeschenk","Hinzufügen",25);





output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");



    //Auflistung der möglichen Optionen
    foreach($weihnachtsgeschenk as $key=> $val){
    addnav("{$val['name']} - `^{$val['price']} Gold`0","weihnachten.php?op=geschenk&act=ask&eat=".$key);
    }
    //Navigation
    addnav('Wege');
    addnav('Weihnachtsmarkt','weihnachten.php');
    addnav('Zurück ins Dorf','village.php');
    break;
    }//switch act end





break;
case 'gluh':
    page_header('Glühweinstand');
    switch($_GET['act']){
    case 'trinken':
        if($session['user']['gold']>=$drinks[$_GET['drink']]['price']){
            output("Du bestellst dir ".$drinks[$_GET['drink']]['name'] ." und schon wird dir ein Becher gereicht mit dem Gewünschten. Du legst das Gold auf die Theke und wendest dich "
            ." den anderen Leuten hier zu, um mit ihnen zu reden.`n");
            $session['user']['gold']-=$drinks[$_GET['drink']]['price'];
            }else{
            output("Du bestellst dir ".$drinks[$_GET['drink']]['name']." ,doch als du bezahlen sollst, fällt dir auf, dass du zu wenig Gold dabei hast. Das war wohl nichts mit einem "
            ."leckeren Getränk.");
            }
            //Navigation
        addnav('Wege');
        addnav('Glühweinstand','weihnachten.php?op=gluh');
            addnav('Geschenkestand','weihnachten.php?op=geschenk');     
        addnav('Weihnachtsmarkt','weihnachten.php');
        addnav('Zurück ins Dorf','village.php');

    break;
    default:
    output('`b`c`$Gl`4üh`Lwe`4in`$st`4a`$nd`c`b`n`n`IFein liegt der Duft nach Orangen, Zimt und Zitrone in der Luft, als du dich dem kleinen Stand näherst, um welchen sich bereits eine '
                .'kleine Menschenmenge versammelt hat. Die Menschen halten Tönerne Becher in den Händen. Dampf steigt aus ihnen hervor, der herrlich weihnachtlich duftet und deine '
                .'Nase kitzelt. Zu schmecken scheint das heiße Gebräu auch, nippen die Menschen doch gar genüsslich an ihren Krügen.`nMit flinken Schritten schlängelst du dich an ein paar '
                .'der Menschen vorbei und trittst an die Theke des kleinen Standes. Der Verkäufer lächelt dir zu und schnappt sich einen der Krüge, wobei er dich erwartungsvoll ansieht. "Was '
                .'darf ich dir zu trinken anbieten. Ich habe vieles, was bei dem kalten Winterwetter den Körper wärmt." Mit einem Nicken weißt er auf ein Schild, auf welchem mit weißer '
                .'Kreide die verschiedenen Getränke geschrieben stehen, die im Angebot sind. Frohlockend betrachtest du die Liste und überlegst, was du dir einverleiben sollst, '
                .'klingt doch alles wahrlich köstlich.`0');
    addnav('Was willst du bestellen?');
    //Auflistung der möglichen Optionen
    foreach($drinks as $key=> $val){
    addnav("{$val['name']} - `^{$val['price']} Gold`0","weihnachten.php?op=gluh&act=trinken&drink=".$key);
    }
        //Navigation
    addnav('Wege');
    addnav('Weihnachtsmarkt','weihnachten.php');
    addnav('Zurück zum Brunnenplatz','village.php');
  
  
    
    viewcommentary('gluhweinstand','`n`n`n`n`6Ausgelassen reden:`n',15);



output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");

    break;
    }//switch act end
break;
case 'take':
    page_header('Der Weihnachtsbaum');
    if($session['user']['special_taken']!=1){
        output("`tDu näherst dich dem Baum, streckst die Hände nach einem Paket aus und nimmst es von dem Zweig, an dem es hängt. Einen Augenblick lang betrachtest du es noch "
                    ."beinahe ehrfürchtig, dann packst du es aber auch schon aus.`n Es liegen `^".$gold." `0Goldstücke darin und `#".$gems." `0Edelsteine.`n Deine Augen leuchten und "
                    ."zufrieden packst du dein Geschenk ein. Du fühlst dich aus irgendeinem Grund wesentlich ansehnlicher nun, als ob dein Charme um `4".$charme."`0 Punkte gestiegen sei. "
                    ."Wenn sich sowas in Punkten messen ließe.");
        $session['user']['gold']+= $gold;
        $session['user']['gems']+= $gems;
        $session['user']['charm']+= $charme;
        $session['user']['special_taken']=1;
    }else{
        output('`tJemand klopft dir auf die Finger, als du ein weiteres Paket nehmen willst. Nur ein warnender Blick von einer Wache und du weißt, dass du es lieber unterlässt.');
    }
    addnav('Wege');
    addnav('Zum Weihnachtsmarkt','weihnachten.php');
    addnav('Zurück','village.php');
break;
default:
    page_header('Weihnachtsmarkt');
    output('`c`b`ÝW`Ge`Xi`Ýh`Gn`Xa`Ýc`Gh`Xt`Ýs`Gm`Xa`Ýr`Gk`Xt`b`c`n`n`2');
 output('Schon beim Betreten des Weihnachtsmarktes bemerkst du den edlen Baum, der wohlgeschmückt die Mitte des Platzes ziert, auf welchem sich die kleinen Buden dicht 
         aneinander drängen. Engelshaar glitzert silbern im Licht, große rote Kugeln, saftige Äpfel und Lebkuchenherzen sind an die nadelbesetzten Äste gebunden, während rote 
         Kerzen das Tannengrün in ein edles Licht tauchen. Viele Kindern haben sich hier versammelt, betrachten mit großen Augen den prächtigen Baum. Und jetzt, wo du näher
         herangetreten bist, entdeckst du die kleinen Pakete, die in dem Baum versteckt sind.`nTief atmest du die kalte Luft ein, während deine Schuhe knirschende Geräusche im 
         Schnee verursachen. Von Weitem dringt der Gesang eines Kinderchors an deine Ohren. Doch deine Augen bleiben an dem Baum hängen. Einem jedem ist es erlaubt hier 
         ein Geschenk heraus zu nehmen. Aber streng nur eines!');



output("`n`n`n`n`n`%`mIn der Nähe reden einige Marktbesucher:`n");
viewcommentary("weihnachtsmarkt","Hinzufügen",25);





output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");



     //Navigation
    addnav('Aktionen');
    addnav('Geschenk vom Baum nehmen','weihnachten.php?op=take');
    addnav('Wege');
    addnav('Glühweinstand','weihnachten.php?op=gluh');
    addnav('Geschenkestand','weihnachten.php?op=geschenk'); 
    addnav('Gebäckstand','weihnachten.php?op=geback');
    addnav('Schlittschuhe leihen','weihnachten.php?op=schlittschuh');
    addnav('');
    addnav('Zurück','village.php');
break;
}//switch op end


checkday();
page_footer();
?> 