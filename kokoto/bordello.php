<?
/* The House of Sin
** By Phudgee - phudgee@oldschoolpunk.com
** Written Aug. 10, 2004 in about 3 hours.
** Website: http://www.worldwidepunks.com
** Green Dragon Game: http://www.worldwidepunks.com/logd
** angepasst für www.silienta-logd.de und bugfixe durch Rikkarda@silienta-logd.de
** & Gargamel@silienta-logd.de
**  German Translation by Beleggrodion
**
** Sanft überarbeitet von Tidus für www.kokoto.de
*/


require_once "common.php";
addcommentary();
checkday();
$madam = 'Black';
$returnvillage = 'village.php?op=vergnueg';
page_header("Madam ".$madam."'s Haus der Sinne");
rawoutput("<span style='color: #9900FF'>");
output('`c`bDas Haus der Sinne`b`c');
$costone = 100;
$costtwo = 250;
$costthree = 500;
$costfour = 750;
$costfive = 1000;

if ($_GET['op']==''){
    output("`c`b`%Madam ".$madam." begrüsst euch an der Türe.`0`b`c");
    if ($session['user']['bordello'] == 1)    {
        output('`n`n`^Eigentlich reicht es dir für heute!`n `^Vielleicht solltest du dich für den Rest des Tages in der Kneipe erholen.`n`n');
        addnav('In die Schenke','inn.php');
    }
    else {
        if ($session['user']['gold'] < $costone)    {
            output("`n`n`^'Was denkst du!?!?' hörst du Madam ".$madam." schreien!`n");
            output("`^'Meine ".($session['user']['sex']?"`5Jungs`^ ":"`5Mädchen`^ ")." sind nicht billig, vorallem nicht gratis! Verschwinde!'`n`n");
        } else {
        output("`n`nSie bietet dir jemand von ihren ".($session['user']['sex']?"`^Jungs`0 ":"`5Mädchen`0 ")." für dein Vergnügen an`n`n");
        }
        if ($session['user']['gold'] >= $costone)    {
            addnav(($session['user']['sex']?"`^Tor`0 ":"`5Tiri`0 ")." (".$costone."Gold)",'bordello.php?op=one');
        }
        if ($session['user']['gold'] >= $costtwo)    {
        addnav(($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ")." (".$costtwo."Gold)",'bordello.php?op=two');
        }
        if ($session['user']['gold'] >= $costthree)    {
        addnav(($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ")." (".$costthree."Gold)",'bordello.php?op=three');
        }
        if ($session['user']['gold'] >= $costfour)    {
        addnav(($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." (".$costfour."Gold)",'bordello.php?op=four');
        }
        if ($session['user']['gold'] >= $costfive)    {
        addnav(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." (".$costfive."Gold)",'bordello.php?op=five');
        }
    }
}else if ($_GET['op']=='one'){
    $session['user']['gold']-=$costone;
    $session['user']['bordello']=1;
    output("`n`%Du gibst Madam ".$madam." aus deinem Beutel `6".$costone."`0Gold, und steuerst die Treppe mit ".($session['user']['sex']?"`^Tor`0 ":"`5Tiri`0 ")." an.`n`n");
    if (e_rand(0,2)==0){
        output("`n`%".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." hat offensichtlich einen schlechten Tag, und bringt dir kein grosses Vergnügen`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." ist frustriert und sagt dir das du gehen sollst.`n`n");
        output('Du sagst Madam ".$madam." das du dein Geld zurück möchtest!`n Sie sagt dir das es hinter dem Tisch ist... als du dich auf den Weg machst es zu holen, schlägt sie dich mit einem Stab über den Kopf nieder.`n`nDu wachst später auf der Strasse wieder auf und stellst fest das du eine grosse Beule an deinem Hinterkopf hast.`n`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du bist die Witzfigur des Tages im Dorf! Du verlierst Zwei Charmepunkte)`n');
                addnews("`%".$session['user']['name']."`@ wurde bewusstlos geschlagen und auf die Strasse geworfen von `%Madam ".$madam."`@");
                $session['user']['charm']-=2;
                break;
            case '2':
                output('`n`b`^(DU hast viele Lebenspunkte verloren)`n');
                addnews("`%".$session['user']['name']."`@ wurde bewusstlos geschlagen und auf die Strasse geworfen von `%Madam ".$madam."`@");
                $session['user']['hitpoints']-=round($session['user']['hitpoints'].5);
                break;
            case '3':
                output('`n`b`^(Du bemerkst das du eine Zeit lang auf der Strasse gelegen hast. Du verlierst 2 Waldkämpfe)`n');
                addnews("`%".$session['user']['name']."`@ wurde bewusstlos geschlagen und auf die Strasse geworfen von `%Madam ".$madam."`@");
                $session['user']['turns']-=2;
        }
    } else {
        output('`n`%Das war das beste was du für dein Geld ausgegeben hast!`n');
        output(($session['user']['sex']?"`^Tor`0 ":"`5Tiri`0 ")." weiss total was du magst.`n`n ".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." macht Dinge die du dir nie hättest vorstellen können!`n");
        output('Nach ein paar Momenten des höchsten Vergnügen, verlässt du das Gebäude mit einem grossen Grinsen Du hast genug für die nächsten paar Jahre!`n`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du hast nun ein leichtes "glühen"! Du bekommst (1) Charmepunkt)`n');
                addnews("`%".$session['user']['name']."`@ verlässt das Bordell mit einem grossen Grinsen in ".($session['user']['sex']?"`^ihrem`0 ":"`5seinem`0 ")." Gesicht!`@");
                $session['user']['charm']+=1;
                break;
            case '2':
                output('`n`b`^(Du fühlst dich als könntest du die gesammte Welt erobern. Du gewinnst 2 Waldkämpfe)`n');
                addnews("`%".$session['user']['name']."`@ verlässt das Bordell mit einem grossen Grinsen in ".($session['user']['sex']?"`^ihrem`0 ":"`5seinem`0 ")." Gesicht!`@");
                $session['user']['turns']+=2;
                break;
            case '3':
                output('`n`b`^(Du fühlst dich total erfrischt. Du wurdest komplett geheilt!)`n');
                addnews("`%".$session['user']['name']."`@ verlässt das Bordell mit einem grossen Grinsen in ".($session['user']['sex']?"`^ihrem`0 ":"`5seinem`0 ")." Gesicht!`@");
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }
}else if ($_GET['op']=='two'){
    $session['user']['bordello']=1;
    $session['user']['gold']-=$costtwo;
    output("`n`%Du gibst Madam ".$madam." aus deinem Beutel `6".$costtwo."`0Gold, und steuerst die Treppe mit ".($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ")." an.`n`n");
    if (e_rand(0,2)==0){
        output("`n`%".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." weiss absolut was ".($session['user']['sex']?"`^eine Frau`0 ":"`5ein Mann`0 ")." sich wünscht.`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." macht wundervolle Dinge mit dir für ein paar Minuten.....`n`n");
        output('Total befriedigt verlässt du das Bordell mit einem Lächeln!`n');
        output('Drei Tage später lächelst du nicht mehr so....`n`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du hast dir eine Krankheit zugezogen!)`n');
                $buff = array("name"=>"`4Krankheit`0","rounds"=>60,"wearoff"=>"`5`bDeine Krankheit lässt nach!.`b`0","atkmod"=>.95,"roundmsg"=>"Deine Krankheit behindert deine Kampffähigkeit!","activate"=>"offense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ fing sich eine Krankheit von ".($session['user']['sex']?"`5einer männlichen Dirne`0 ":"`^einer Dirne`0 ")." ein!`@");
                break;
            case '2':
                output('`n`b`^(Du hast dir eine Krankheit zugezogen!)`n');
                $buff = array("name"=>"`4Krankheit`0","rounds"=>60,"wearoff"=>"`5`bDeine Krankheit lässt nach!.`b`0","defmod"=>.95,"roundmsg"=>"Deine Krankheit behindert deine Kampffähigkeit!","activate"=>"defense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ fing sich eine Krankheit von ".($session['user']['sex']?"`5einer männlichen Dirne`0 ":"`^einer Dirne`0 ")." ein!`@");
                break;
            case '3':
                output('`n`b`^(Du hast dir eine Krankheit zugezogen!)`n');
                $buff = array("name"=>"`4Krankheit`0","rounds"=>60,"wearoff"=>"`5`bDeine Krankheit lässt nach!.`b`0","atkmod"=>.95,"roundmsg"=>"Deine Krankheit behindert deine Kampffähigkeit!","activate"=>"offense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ fing sich eine Krankheit von ".($session['user']['sex']?"`5einer männlichen Dirne`0 ":"`^einer Dirne`0 ")." ein!`@");
        }
    } else {
        output('`n`%Das war das beste was du für dein Geld ausgegeben hast!`n');
        output(($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ")." weiss absolut was du magst.`n`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." macht Dinge mit dir, die du dir nie hättest vorstellen können!`n");
        output('Total befriedigt verlässt du das Bordell mit einer total neu Entdeckter Kraft!`n`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Die neu gefundene Kraft  ist 10 zusätzliche Lebenspunkte!)`n');
                addnews("`%".$session['user']['name']."`@ kommt gut gelaunt aus dem Bordell gehüpft!`@");
                $buff = array("name"=>"`8Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!.`b`0","atkmod"=>1.05,"roundmsg"=>"Deine kürzlich erhaltene Befriedigung erhöht deine Kampffähigkeiten!","activate"=>"offense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['hitpoints'] += 10;
                break;
            case '2':
                output('`n`b`^(Die neu gewonnene Kraft gibt die 2 zusätzliche Waldkämpfe)`n');
                addnews("`%".$session['user']['name']."`@ kommt gut gelaunt aus dem Bordell gehüpft!`@");
                $buff = array("name"=>"`8Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!.`b`0","defmod"=>1.05,"roundmsg"=>"Deine kürzlich erhaltene Befriedigung erhöht deine Kampffähigkeiten!","activate"=>"defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 2;
                break;
            case '3':
                output('`n`b`^(Deine neu gewonnene Kraft fügt dir eine Angriff und Verteidigungs Bonus, 10 Zusätzliche Lebenspunkte und einen zusätzlichen Waldkampf!)`n');
                addnews("`%".$session['user']['name']."`@ kommt gut gelaunt aus dem Bordell gehüpft!`@");
                $buff = array("name"=>"`8Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!.`b`0","atkmod"=>1.05,"defmod"=>1.05,"roundmsg"=>"Deine kürzlich erhaltene Befriedigung erhöht deine Kampffähigkeiten!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['hitpoints'] += 10;
                $session['user']['turns'] += 1;
        }
    }
} else if ($_GET['op']=='three'){
    $session['user']['bordello']=1;
    $session['user']['gold']-=$costthree;
    output("`n`%Du gibst Madam ".$madam." aus deinem Beutel `6".$costthree."`0Gold, und steuerst die Treppe mit ".($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ")." an.`n`n");
    if (e_rand(0,2)==0){
        output("`n`%".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." weiss absolut was ".($session['user']['sex']?"`^eine Frau`0 ":"`5ein Mann`0 ")." sich wünscht.`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." versucht etwas unerhörtes zu tun!`n`n");
        output('Du wilst die Treppe runter und dich bei der Madam beschweren!`n');
        output(($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ")." schlägt dir was über den Kopf...`n`n");
        output('Du wachst auf der Strasse auf mit einer etwas leichteren Geldbörse...`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du wurdest ausgeraubt! Die Nachricht deiner Lage spricht sich schnell rum. Du bist nicht fähig dich zu verteidigen!)`n');
                $buff = array("name"=>"`4Demütigung`0","rounds"=>60,"wearoff"=>"`5`bDu bekommst deinen Stolz zurück!.`b`0","defmod"=>.95,"roundmsg"=>"Du bist zu fest beschämt um dich ganz zu verteidigen!","activate"=>"defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['gold'] = round($session['user']['gold']  .5);
                addnews("`%".$session['user']['name']."`@ wurde von Dirnen ausgeraubt!`@");
                break;
            case '2':
                output('`n`b`^(Du wurdest ausgeraubt! Die Nachricht deiner Lage spricht sich schnell rum. Du kannst nicht mehr so gut kämpfen!)`n');
                $buff = array("name"=>"`4Demütigung`0","rounds"=>60,"wearoff"=>"`5`bDu bekommst deinen Stolz zurück!.`b`0","atkmod"=>.95,"roundmsg"=>"Du bist zu fest beschämt um dich ganz zu verteidigen!","activate"=>"offense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['gold'] = round($session['user']['gold']  .5);
                addnews("`%".$session['user']['name']."`@ wurde von Dirnen ausgeraubt!`@");
                break;
            case '3':
                output('`n`b`^(Du wurdest ausgeraubt! Die Neuigkeit spricht sich schnell im Dorf rum, du kannst nicht mehr so gut kämpfen!)`n');
                $buff = array("name"=>"`4Demütigung`0","rounds"=>70,"wearoff"=>"`5`bDu bekommst deinen Stolz zurück!.`b`0","defmod"=>.95,"atkmod"=>.95,"roundmsg"=>"Du bist zu fest beschämt um dich ganz zu verteidigen!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['gold'] = round($session['user']['gold']  .5);
                $session['user']['gems'] = round($session['user']['gems']  .5);
                addnews("`%".$session['user']['name']."`@ wurde von Dirnen ausgeraubt!`@");
        }
    } else {
        output('`n`%Das war das beste was du für dein Geld ausgegeben hast!`n');
        output(($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ")." weiss absolut was du magst.`n`n ".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." macht Dinge mit dir, die du dir nie hättest vorstellen können!`n");
        output('Nach einer Zeit der Befriedigung, verlässt du das Gebäude mit einem noch nie dagewesenen Gefühl!`n`n');
        switch (e_rand(1,3)){
            case '1':
                addnews("`%".$session['user']['name']."`@ hat sich im Bordell befriedigt!`@");
                output('`n`b`^(Diese Erfahrung hat dich total Befriedigt! Deine Sinne sind an der Grenze ihres Könnens. Deine Verteidigung steigt, und du erhälst einen zusätzlichen Waldkampf!)`n');
                $buff = array("name"=>"`4Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriefigt!`b`0","defmod"=>1.5,"roundmsg"=>"Deine Sinne sind an der Spitze!","activate"=>"defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 1;
                break;
            case '2':
                addnews("`%".$session['user']['name']."`@ hat sich im Bordell befriedigt!`@");
                output('`n`b`^(Diese Erfahrung hat dich total Befriedigt! Deine Sinne sind an der Grenze ihres Könnens. Dein Angriff steigt, und du erhälst einen zusätzlichen Waldkampf!)`n');
                $buff = array("name"=>"`4Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!`b`0","atkmod"=>1.5,"roundmsg"=>"Deine Sinne sind an der Spitze!","activate"=>"offense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 1;
                break;
            case '3':
                addnews("`%".$session['user']['name']."`@ hat sich im Bordell befriedigt!`@");
                output('`n`b`^(Diese Erfahrung hat dich total befriedigt! Deine Sinne sind an der Grenze ihres Könnens. Dein Angriff und deine Verteidigung steigt!)`n');
                $buff = array("name"=>"`4Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!`b`0","defmod"=>1.5,"atkmod"=>1.5,"roundmsg"=>"Deine Sinne sind an der Spitze!","activate"=>"offense");
                $session['bufflist']['bordello']=$buff;
        }
    }
} else if ($_GET['op']=='four'){
    $session['user']['bordello']=1;
    $session['user']['gold']-=$costfour;
    output("`n`%Du gibst Madam ".$madam." aus deinem Beutel `6".$costfour."`0Gold, und steuerst die Treppe mit ".($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." an.`n`n");
    if (e_rand(0,2)==0){
        output("`n`%".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." hat überhaupt kein Gefühl für sich selber.`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." sie sitzt in der Ecke und lehnt es ab dich zu berühren!`n`n");
        output('Sie sagt du siehst abscheulich aus...`nVerärgert trinkst du deinen Drink, nimmst deine Sachen und verlässt das Bordell....`nEine Menschenansammlung steht vor der Türe und zeigt auf dich....`n');
        output("Du bist dir nicht sicher wieso sie lachen, aber ".($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." führt die Menge an.`n");
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du wurdest im Bordell mit Komischen Kräutern versorgt. Dein Angriff und deine Verteidigung sind ein wenig niedriger.)`n');
                $buff = array("name"=>"`4Komische Kräuter`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!.`b`0","defmod"=>.95,"atkmod"=>.95,"roundmsg"=>"Du hast Drogen genommen.Es ist schwer zu kämpfent!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ wurde mit Komischen Kräutern versorgt von einer Dirne!`@");
                break;
            case '2':
                output('`n`b`^(Du wurdest im Bordell mit Komischen Kräutern versorgt. Dein Angriff und deine Verteidigung sind ein niedriger.)`n');
                $buff = array("name"=>"`4Komische Kräuter`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!.`b`0","defmod"=>.85,"atkmod"=>.85,"roundmsg"=>"Du hast Drogen genommen.Es ist schwer zu kämpfen!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ wurde mit Komischen Kräutern versorgt von einer Dirne!`@");
                break;
            case '3':
                output('`n`b`^(Du wurdest im Bordell mit Komischen Kräutern versorgt. Dein Angriff und deine Verteidigung sind ein viel niedriger.)`n');
                $buff = array("name"=>"`4Komische Kräuter`0","rounds"=>50,"wearoff"=>"`5`bDie Drogen sind weg!.`b`0","defmod"=>.75,"atkmod"=>.75,"roundmsg"=>"Du hast Drogen genommen.Es ist schwer zu kämpfen!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ wurde mit Komischen Kräutern versorgt von einer Dirne!`@");
        }
    } else {
        output('`n`%Das war das beste was du für dein Geld ausgegeben hast!`n');
        output(($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." weiss absolut was du magst.`n`n ".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." bearbeitet dich richtig!`n");
        output('Nach ein paar Momenten der Befriedigung, verlässt du das Bordell mit einer grossen Befriedigung!`n`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du bist in Begeisterung. Dein Angriff und deine Verteidigung sind etwas höher.)`n');
                $buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!.`b`0","defmod"=>1.05,"atkmod"=>1.05,"roundmsg"=>"Deine Körper ist mit Freudentaumel gefüllt!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ fand Befriedigung im Bordell!`@");
                break;
            case '2':
                output('`n`b`^(Du bist in Begeisterung. Dein Angriff und deine Verteidigung sind höher.)`n');
                $buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!.`b`0","defmod"=>1.15,"atkmod"=>1.15,"roundmsg"=>"Deine Körper ist mit Freudentaumel gefüllt!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                addnews("`%".$session['user']['name']."`@ fand Befriedigung im Bordell!`@");
                break;
            case '3':
                output('`n`b`^(Du bist in Begeisterung. Dein Angriff und deine Verteidigung sind höher und du erhälst einen zusätzlichen Waldkampf.)`n');
                $buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!.`b`0","defmod"=>1.15,"atkmod"=>1.15,"roundmsg"=>"Deine Körper ist mit Freudentaumel gefüllt!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 1;
                addnews("`%".$session['user']['name']."`@ fand Befriedigung im Bordell!`@");
        }
    }
} else if ($_GET['op']=='five'){
    $session['user']['bordello']=1;
    $session['user']['gold']-=$costfive;
    output("`n`%Du gibst Madam ".$madam." aus deinem Beutel `6".$costfive."`0Gold, und steuerst die Treppe mit ".($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." an.`n`n");
    if (e_rand(0,2)==0){
        output("`n`%".($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." beginnt dich zu verwöhnen....`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." lässt dich zurück mit geschlossenen Augen...`n`n");
        output('Du hattest noch nie soviel Vergnügen in deinem Leben!`n');
        output("Du beginnst ".($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." betasten und bemerkst du etwas seltsames.....`n");
        output(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." ist in Wirklichkeit  ".($session['user']['sex']?"`^EINE FRAU`0 ":"`5EIN MANN`0 ")."!`n");
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Dir ist es so peinlich und schaust dich im Dorf um, das du Waldkämpfe verlierst!)`n');
                $buff = array("name"=>"`4Peinlichkeit`0","rounds"=>30,"wearoff"=>"`5`bDu hast deine Peinlichkeit überwunden!.`b`0","defmod"=>.75,"atkmod"=>.75,"roundmsg"=>"Du bist zu Verlegen um voll zu kämpfen!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] -= round($session['user']['turns'].5);
                addnews("`%".$session['user']['name']."`@ versuchte es mit eine".($session['user']['sex']?"r `^weiblichen`0 ":"r `5männlichen`0 ")." Dirne!`@");
                break;
            case '2':
                output("`n`b`^(Dir ist es so peinlich und schaust dich im Dorf um, das du Waldkämpfe verlierst!)`n");
                $buff = array("name"=>"`4Peinlichkeit`0","rounds"=>30,"wearoff"=>"`5`bDu hast deine Peinlichkeit überwunden!.`b`0","defmod"=>.65,"atkmod"=>.65,"roundmsg"=>"Du bist zu Verlegen um voll zu kämpfen!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] -= round($session['user']['turns'].5);
                addnews("`%".$session['user']['name']."`@ versuchte es mit eine".($session['user']['sex']?"r `^weiblichen`0 ":"r `5männlichen`0 ")." Dirne!`@");
                break;
            case '3':
                output("`n`b`^(Dir ist es so peinlich und schaust dich im Dorf um, das du Waldkämpfe verlierst!)`n");
                $buff = array("name"=>"`4Peinlichkeit`0","rounds"=>20,"wearoff"=>"`5`bDu hast dein Peinlichkeit überwunden!.`b`0","defmod"=>.55,"atkmod"=>.55,"roundmsg"=>"Du bist zu Verlegen um voll zu kämpfen!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] -= round($session['user']['turns'].5);
                addnews("`%".$session['user']['name']."`@ versuchte es mit eine".($session['user']['sex']?"r `^weiblichen`0 ":"r `5männlichen`0 ")." Dirne!`@");
        }
    } else {
        output("`n`%Du bist  ".($session['user']['sex']?"`^DIE FRAU`0 ":"`5DER MANN`0 ")."!`n");
        output(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." hatte noch nie Erfahrungen mit so jemandem wie dir in  ".($session['user']['sex']?"`^seinem`0 ":"`5ihrem`0 ")." Leben!`n`n");
        output(($session['user']['sex']?"`^Er`0 ":"`5Sie`0 ")." erzählt im ganzen Dorf wie gut du im Bett bist!`n");
        output('Du verlässt das Bordell mit erhobenen Hauptes!`n`n');
        switch (e_rand(1,3)){
            case '1':
                output('`n`b`^(Du bist die meist verehrte Person im Dorf! Du erhälst ein paar Waldkämpfe!)`n');
                $buff = array("name"=>"`4Grosser Liebhaber`0","rounds"=>60,"wearoff"=>"`5`bDu fühlst dich nicht mehr länger hochmütig!.`b`0","defmod"=>1.35,"atkmod"=>1.35,"roundmsg"=>"Der Stolz fliesst durch deine Adern!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 5;
                addnews("`%".$session['user']['name']."`@ ist `#Weltbeste".($session['user']['sex']?" `^Liebhaberin`0 ":"r `Liebhaber`0 ")."!`@");
                break;
            case '2':
                output('`n`b`^(Du bist die meist verehrte Person im Dorf! Du erhälst ein paar Waldkämpfe!)`n');
                $buff = array("name"=>"`4Grosser Liebhaber`0","rounds"=>60,"wearoff"=>"`5`bDu fühlst dich nicht mehr länger hochmütig!.`b`0","defmod"=>1.45,"atkmod"=>1.45,"roundmsg"=>"Der Stolz fliesst durch deine Adern!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 5;
                addnews("`%".$session['user']['name']."`@ ist `#Weltbeste".($session['user']['sex']?" `^Liebhaberin`0 ":"r `Liebhaber`0 ")."!`@");
                break;
            case '3':
                output('`n`b`^(Du bist die meist verehrte Person im Dorf! Du erhälst ein paar Waldkämpfe!)`n');
                $buff = array("name"=>"`4Grosser Liebhaber`0","rounds"=>60,"wearoff"=>"`5`bDu fühlst dich nicht mehr länger hochmütig!.`b`0","defmod"=>1.55,"atkmod"=>1.55,"roundmsg"=>"Der Stolz fliesst durch deine Adern!","activate"=>"offense,defense");
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 5;
                addnews("`%".$session['user']['name']."`@ ist `#Weltbeste".($session['user']['sex']?" `^Liebhaberin`0 ":"r `Liebhaber`0 ")."!`@");
        }
    }
}
    addnav('Zuruck',$returnvillage);

page_footer ();
?>