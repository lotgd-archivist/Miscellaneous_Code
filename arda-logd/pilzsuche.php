<?php

/*********************************
*                                *
*  Der Pilzwald (pilzsuche.php)  *
*        Idee: Veskara           *
*    Programmierung: Linus       *
*   für alvion-logd.de/logd      *
*      im Dezember 2007          *
*                                *
**********************************/

/* SQL:
CREATE TABLE `pilze` (acctid INT(11) NOT NULL DEFAULT '0',
            `hasel` INT(11) NOT NULL DEFAULT '0',
            `gift` INT(11) NOT NULL DEFAULT '0',
            `feigen` INT(11) NOT NULL DEFAULT '0',
            `hasen` INT(11) NOT NULL DEFAULT '0',
            `baum` INT(11) NOT NULL DEFAULT '0',
            `insekt` INT(11) NOT NULL DEFAULT '0',
            `leucht` INT(11) NOT NULL DEFAULT '0',
            `alvion` INT(11) NOT NULL DEFAULT '0',
            `goetter` INT(11) NOT NULL DEFAULT '0',
            `gold` INT(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`acctid`(
            ) TYPE=MyISAM COMMENT='Tabelle für die Pilzsuche';


ALTER TABLE `accounts` ADD pilzsuche ENUM('0','1') NOT NULL DEFAULT '0',
        ADD kristalle INT(11) NOT NULL DEFAULT '0';

PHP:

newday.php:
-----------
suche:
        $session['user']['transferredtoday'] = 0;

füge danach ein:
        $session['user']['pilzsuche']='0';


dragon.php:
-----------
im nochange-Array zweimal einfügen:
                        ,"kristalle"=>1


setnewday.php:
--------------
an geeigneter Stelle einfügen:

// Pilzsuche by Linus & Veskara
if(mt_rand(1,5)==2) savesetting('steintroll','0');
else savesetting('steintroll','1');

*/

require_once "common.php";
global $session;
page_header("Der Pilzwald");

switch($_GET['op']) {
    case "":
        $out="Dir ist ein wenig unheimlich, wie du hier so durch die wohl dunkelste Ecke des Waldes  streifst, die du hattest finden können. Aber dieses Pech kanntest du ja bereits und bleibst erst einmal seufzend stehen. Warum nicht einfach das Beste daraus machen? Immerhin hattest du nichts Besseres zu tun und auf einmal sticht dir auch etwas ins Auge.  `n
Direkt neben dir wächst ein prächtiger Fliegenpilz aus dem Boden und erweckt in dir die Frage ob es hier wohl noch andere und vor allem essbare Pilze gibt.  `n
Du bist dir dessen bewusst, dass es dich etwas Zeit kosten würde aber eine schlechte Idee wäre es bestimmt nicht auf Pilzsuche zu gehen, nicht wahr?`n `n";
        if($session['user']['pilzsuche']){
            $out.="Du hast heute schon Pilze gesucht. Du bist zu müde weiter durch den Pilzwald zu streifen.`n";
            addnav("Zurück","forest.php");
        } else {
            if($session['user']['turns']>=3){
                $out.="Du hast noch ".$session['user']['turns']." Runden übrig. Wieviele Runden möchtest du einsetzen? Bedenke das jeder Schritt durch den Pilzwald drei Runden kosten wird!`n";
                $out.="<form action='pilzsuche.php?op=start' method='POST'>Wieviel <u>R</u>unden möchtest du einsetzen? <input name='runden' id='runden' accesskey='r' width='3'> (mindestens 3 Runden)`n";
                addnav("","pilzsuche.php?op=start");
                addnav("Zurück","forest.php");
            } else {
                $out.="Du hast nicht genügend Runden übrig um Pilze suchen zu können.`n";
                addnav("Zurück","forest.php");
            }
        }
        break;
    case "start":
        $out="Auf einmal schreckst du zurück. Irgendetwas hatte sich doch eben in deinem Augenwinkel bewegt oder bildest du dir das etwa nur ein? `n
Falsch gedacht. Unmittelbar vor deinem erstaunten Augen wackelt der Fliegenpilz kräftig hin und her bevor er auf einmal mit einem Satz nach oben hüpft und auf zwei dünnen Beinchen zum stehen kommt. Völlig perplex starrst du nun auf den laufenden Pilz, der dich wohl seinerseits anstarrt. Du jedoch erkennst nur einem schmalen Strich, der wohl den Mund darstellt, der Rest schien unter dem Hut versteckt zu sein. `n
Auf einmal nuschelt es dir etwas zu: Nichts da .Wer Pilze will, der soll löhnen, muss schließlich selbst von irgendwas leben. Schau nicht so entsetzt und zeig mir lieber was du mir bieten kannst, damit ich dir erlauben kann in meinem Pilzwald umher zu streifen. Meint das komische Männlein mit frechem Grinsen`n`n`c";
//        $out.="<img src='./images/pilz2.gif'>`c`n`n";

        if($_POST['runden']>$session['user']['turns']){
            $out.="So viele Runden hast du nicht mehr übrig!`n";
            addnav("Zurück","pilzsuche.php");
        } elseif ($_POST['runden']<3){
            $out.="Du musst mindestens drei Runden einsetzen!`n";
            addnav("Zurück","pilzsuche.php");
        } else {
            $schritte=floor($_POST['runden']/3);
            $abzug=$schritte*3;
            $out.="Du kannst für den Einsatz von $abzug Runden $schritte Schritte durch den Pilzwald wandern.`nMöchtest du nun Pilze suchen?`n";
            addnav("Ja, Pilze suchen","pilzsuche.php?op=start2&schritte=$schritte&abzug=$abzug");
            addnav("Nein, zurück","pilzsuche.php");

        }
        break;
    case "start2":
        $out="Nachdem du entsprechende Runden als Tribut gezahlt hast, nickt dir der Fliegenpilz auf zwei Beinen und wirkt auf einmal ganz und gar nicht mehr so unfreundlich wie vorher `n
Habt Spaß und fallt nicht über heraushängende Baumwurzeln ruft er dir noch lachend zu und ist auch schon hinter irgendeinem Busch verschwunden`n`n";

        $session['user']['turns']-=$_GET['abzug'];
        $session['user']['pilzsuche']='1';
        $sql="SELECT * FROM `pilze` where `acctid`='".$session['user']['acctid']."'";
        $result=db_query($sql) or die(db_error(LINK));
        if(!db_num_rows($result)) db_query("INSERT INTO `pilze` (`acctid`) VALUES ('".$session['user']['acctid']."')") or die(db_error(LINK));
        addnav("".$_GET['schritte']." x Pilze suchen","pilzsuche.php?op=suche&schritte=".($_GET['schritte']-1)."");

        break;
    case "suche":
        $out="";
        $pilze = array(
            1=>array("Haselröhrling","hasel"),
            2=>array("Giftmorchel","gift"),
            3=>array("Feigenfiesling","feigen"),
            4=>array("Hasenschwämmchen","hasen"),
            5=>array("Baumfungi","baum"),
            6=>array("Insektentäubling","insekt"),
            7=>array("Leuchtender Nachtpilz","leucht"),
            8=>array("Ardasteinpilz","alvion"),
            9=>array("Götterwulstling","goetter"),
            10=>array("Goldener Pilz","gold")
        );

        $sql="SELECT * FROM `pilze` WHERE `acctid`='".$session['user']['acctid']."'";
        $result=db_query($sql) or die(db_error(LINK));
        $row=db_fetch_assoc($result);

        $out.="Mit aufmerksamen Augen tastest du den Waldboden ab in der Hoffnung, dass sich dir ein Pilzhütchen zeigen würde. Es dauert nicht einmal lange bis du auf einmal versteckt zwischen den herausragenden Wurzeln eines mächtigen Baumes einen prächtig aussehenden Pilz entdeckst. Ohne zu zögern schneidest du ihn ab.`n`n ";

        switch(mt_rand(1,3)){
            case "1":
                $out.="Wovon haben wir zu Beginn noch gesprochen? Von deinem sprichwörtlichen Pech? Tja leider bewirkt dein Pech, dass du nicht einmal den kleinsten Hügel gefunden hast unter dem sich hätte ein Pilz verstecken können.`n`n";
                break;
            case "2":
            case "3":
                $zufall=mt_rand(1,100);
                if($zufall>=99)    $pilz=10;
                elseif($zufall>=97) $pilz=9;
                elseif($zufall>=94) $pilz=8;
                elseif($zufall>=89) $pilz=7;
                elseif($zufall>=83) $pilz=6;
                elseif($zufall>=76) $pilz=5;
                elseif($zufall>=64) $pilz=4;
                elseif($zufall>=50) $pilz=3;
                elseif($zufall>=30) $pilz=2;
                else $pilz=1;
                $row[$pilze[$pilz]['1']]++;
                db_query("UPDATE `pilze` SET `".$pilze[$pilz]['1']."`=`".$pilze[$pilz]['1']."`+1 WHERE `acctid`='".$session['user']['acctid']."'") or die(db_error(LINK));
                $out.="Du kannst mit Freuden feststellen, dass sich nun ein ".$pilze[$pilz]['0']." in deinem Besitz befindet.`n`n`n";
                break;
        }
        if($_GET['schritte']>0) addnav("".$_GET['schritte']." x Pilze suchen","pilzsuche.php?op=suche&schritte=".($_GET['schritte']-1)."");
        else addnav("Zurück","forest.php");

        $out.="<table align='center'>";
        for($i=1;$i<=10;$i++){
            $out.="<tr><td>".$pilze[$i]['0']."</td><td>".$row[$pilze[$i]['1']]."</td></tr>";
        }
        $out.="</table>";

        break;

}
output($out,true);

page_footer();

?>