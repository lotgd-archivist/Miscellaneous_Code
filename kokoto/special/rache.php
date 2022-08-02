<?php
// 16.09.05 Rache by Deedlit v.1.0.
// Waldspecial Prog. for Arith www.logd-shadow.de/logd

/*********************************************
* some updates by: Drazaar                   *
* mail to: drazaar@legend-of-vinestra.de     *
* play us: http://logd.legend-of-vinestra.de *
* Rache v.1.1                                *
**********************************************/
//Überarbeitet und Angepasst by Tidus für www.kokoto.de Rache v1.2
require_once "common.php";
if (!isset($session)) exit();

output('`b`c`n`#Der Verwirrte alte Mann`b`c`n`n`0');
if ($_GET['op']=='weg'){
output('`@Du nimmst ihm den Stock weg und schaust ihn, mit einem Funkeln in den Augen, an.');
addnav('Stock zerbrechen','forest.php?op=brechen');
addnav('Eine überziehen','forest.php?op=ziehen');
addnav('Stock mitnehmen','forest.php?op=nehmen');
$session['user']['specialinc']='rache.php';
}else if ($_GET['op']=="ruhe"){
    output('`@Du läufst an ihm vorbei ohne ihm etwas zu tun. Er ist ja auch nur ein vewirrter alter Mann.');
addnav('Weiter','forest.php');
$session['user']['specialinc']='';
}else if ($_GET['op']=='brechen'){
    output('`@Du nimmst seinen Stock und brichst ihn mit voller Wucht über deinem Knie in zwei Stücke.`n `@Der alte Mann schaut dich fassungslos an und stammelt wütend:`n `9"`iMein Stock! Mein schöner alter Gehstock! Das wirst du mir büßen!"`i`n`n `@Du lässt ihn einfach stehen und gehst weiter, mit einem Grinsen im Gesicht und der Gewissheit, dass er mit diesem Stock keinen mehr schlägt und der Hoffnung, dass er keine mehr Zuhause hat....');
    switch(mt_rand(1,2)){
        case 1:
              output('`@`n`nBei der Hälfte des Weges fängst du an darüber nachzudenken, ob die Entscheidung nicht falsch war. Und noch bevor du zu Ende gedacht hast, holt dich ein unbesiegbares schlechtes Gewissen ein, welches dich wohl einige Zeit lang behindern wird!!');
              $buff = array( "name" => "`4schlechtes `\$Gewissen`0","roundmsg" => "`\$Dein schlechtes Gewissen lässt dich nicht in Ruhe...`0","wearoff" => "`4Endlich ist dein schlechtes Gewissen weg...`0","rounds" => "35","atkmod" => "0.9","survivenewday" => "0","activate" => "offense");
              $session['bufflist']['schlechtes Gewissen']=$buff;
              break;
        case 2:
              output('`@`n`nNachdem du eine Weile gelaufen bist, fällt dir auf, dass das Gefühl der Genugtuung dich richtig durchfließt! Du weißt instinktiv, dass du jetzt doppelt so gut kämpfen wirst!!');
              $buff = array( "name" => "`@Genugtuung`0","roundmsg" => "`@Das schöne Gefühl der Rache durchließt dich und lässt dich stärker zuschlagen!!`0","wearoff" => "`2Das gefühl der Rache schwindet...`0","rounds" => "35","atkmod" => "1.2","activate" => "offense");
              $session['bufflist']['Genugtuung']=$buff;
              break;
    }
    addnews($session['user']['name']."`@ hat den Stock eines alten Mannes zerbrochen!`0");
    addnav('Weiter','forest.php');
    $session['user']['specialinc']='';
}else if ($_GET['op']=='ziehen'){
    output('`@Du nimmst den Stock und ziehst ihm damit mit voller Wucht eins über!`n `n`^Der alte Mann `%bekommt`^ einen Charmepunkt! `n`n`g"Nanu? Was war das denn nun?" `@fragst du dich. Misstrauisch betrachtest du den Stock und holst auch schon aus. `g"Was bei ihm wirkt, wirkt auch bei mir" `@denkst du dir und schlägst zu!');
    switch(mt_rand(1,2)){
      case 1:
            $session['user']['charm']+=1;
            output('`n`nAutsch...das tat weh! Aber tatsächlich, der Stock scheint zu wirken! Du hast dir selbst eine übergebraten!`n`n`^Du `%bekommst `^einen Charmepunkt!`0`n`n`@Fröhlich wirst du den Stock dem alten Mann wieder hin und verlässt die Gegend!`0');
            break;
      case 2:
            $session['user']['charm']-=1;
            output('`n`nAutsch...das tat weh! Und dieser verdammte Stock scheint auch nicht zu funktionieren wie er sollte!!`n`n`^Du `%verlierst `^einen Charmpunkt!`n`n`@Wütend schmeißt du den Stock auf den Boden und verschwindest wieder tiefer im Wald!`0');
            break;
      }
    addnews($session['user']['name']."`@ hat einen alten Mann mit dessen Stock geschlagen und danach sich selbst!`0");
    addnav('Weiter','forest.php');
    $session['user']['specialinc']='';
}else if ($_GET['op']=='nehmen'){
    output('`@Du klemmst den Stock unter deinen Arm und gehst, den alten Mann ignorierend, weiter deines Weges.`n `@Der alte Mann dreht sich zu dir um und schreit dir nach:`n `9"`iDa wo der herkommt, gibt es noch viel mehr!"`i`n`n `@Du schüttelst leicht den Kopf und gehst weiter. Gerade willst du den Stock wegwerfen, als ein zwielichtiger Wanderhändler deinen Weg kreuzt.`n`n `i`$"Einen schönen Stock hast du da"`i `@murmelt er `i`$"Ich gebe dir 5000 Gold für den Stock!!"`i`n `i`n`g"5000 GOLD???"`i `@stammelst du fassungslos? Heute ist wohl dein Glückstag. Bevor du weiter nachgedacht hast, drückst du dem Typ bereits den Stock in die Hand.');
    switch(mt_rand(1,3)){
    case 1:
       output('`n`nDoch bevor du dich richtig umschaust, brät dir der Wanderhändler auch schon eines mit dem Stock über! `n`n`^Du `%verlierst `^einen Charmepunkt!');
       $session['user']['charm']-=1;
       output('`@`n`nAls du K.O. gehst, nimmt dir der nette Mann auch noch dein gesamtes Gold ab! `^`n`nDu verlierst all dein Gold!');
       $session['user']['gold']=0;
       break;
    case 2:
       output('`n`nDoch bevor du dich richtig umschaust, brät dir der Wanderhändler auch schon eines mit dem Stock über! `n`n`^Du `%bekommst `^einen Charmepunkt!');
       $session['user']['charm']+=1;
       output('`@`n`nAls du K.O. gehst, nimmt dir der nette Mann auch noch dein gesamtes Gold ab! `^`n`nDu verlierst all dein Gold!');
       $session['user']['gold']=0;
       break;
    case 3:
       output('`n`n`@Der Wanderhändler bedankt sich höflich und gibt dir einen Sack Gold.`nNachdem du jede Münze zwei mal umgedreht hast, stellst du fest, dass es sich wirklich um 5000 Gold handelt! Zufrieden ziehst du weiter... `^`n`nDu bekommst 5000 Gold!');
       $session['user']['gold']+=5000;
       break;
    }
addnews($session['user']['name']."`2 hat den Stock eines alten Mannes gestohlen!`0");
addnav('Weiter','forest.php');
$session['user']['specialinc']='';
}else{
    output('`@Langsam spazierst du einen Weg entlang durch den Wald, als du plötzlich, einige Meter vor dir, einen alten Mann auf dich zu laufen sehen kannst.`n `@Du erkennst ihn sofort! Es ist der alte Mann, der dir schon mehr als einmal mit seinem Stock eine übergezogen hat.`n `@Du gehst auf ihn zu und überlegst, ob du dich an ihm rächen sollst oder nicht.`n');
addnav('In Ruhe lassen','forest.php?op=ruhe');
addnav('Stock wegnehmen','forest.php?op=weg');
$session['user']['specialinc']='rache.php';
}
?>