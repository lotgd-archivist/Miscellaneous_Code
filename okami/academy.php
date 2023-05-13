
<?php

// 25072004

/* Warchild's Magic Academy of the Dark Arts
* Die Akademie der geheimen Künste
* coded by Kriegskind/Warchild
* Email: warchild [-[at]-] gmx.org
* February/March 2004
* v0.961dt
*
* Modifikationen von LotGD nötig:
* DB - Neues Feld seenAcademy(TINYINT(3)) in accounts, default 0
* newday.php - Zurücksetzen von $session['user']['seenAcademy'] = 0 an jedem Tag
* village.php - Link auf die Akademie einbauen
*
* letzte Modifikation
* 18.3.2004, 17:35 Bibliothekserfolgswahrscheinlichkeit wieder auf 33% erhöht (Warchild)
* Adminzugang entfernt
* Zauberladen eingebaut (25.07.2004, anpera)
*/

// Talion: Anpassung ans Gildensystem (Rabatte)

require_once("common.php");
//Ätsch
addcommentary();
// Entscheidungsvariablen op1: Akademie betreten oder nicht, op2: Bezahlungsart und Studienart

page_header("Warchilds Akademie der geheimen Künste");

$sql = "SELECT * FROM specialty WHERE specid='".$session['user']['specialty']."'";
$row = db_fetch_assoc(db_query($sql));

if(file_exists("./module/specialty_modules/".$row['filename'].".php"))
{
  require_once "./module/specialty_modules/".$row['filename'].".php";
  $f1 = $row['filename']."_info";
  $f1();
  $f2 = $row['filename']."_run";
}
else
{
  function blank(){ return false;}
  $f2 = "blank";
}
// Kosten: gestaffelt nach Skillevel
$skills = array($row['specid']=>$row['usename']);
$akt_magiclevel = (int)($session['user']['specialtyuses'][$skills[$session['user']['specialty']]] + 1); // man faengt bei 0 an ;o)

$cost_low = (($akt_magiclevel + 1) * 50)-$session['user']['reputation'];
$cost_medium =(($akt_magiclevel + 1)* 60)-$session['user']['reputation']; //plus ein Edelstein
$cost_high = (($akt_magiclevel + 1) * 75)-$session['user']['reputation']; //plus 2 Edelsteine

// Gesamtanzahl an Anwendungen darf 20 nicht überschreiten
foreach($session['user']['specialtyuses'] as $key=>$val)
{
    if(strpos($key,'uses'))
    {
        $uses_ges += $val;
    }
}

$min_dk = 1; // wieviele DKs muss ein User haben um eintreten zu dürfen?

$rowe = user_get_aei('seenacademy');

if($_GET['op'] == 'buy_do') {

    $_GET['op1'] = 'blank';

    $item = item_get_tpl(' tpl_id="'.$_GET['tpl_id'].'" ');

    $name = $item['tpl_name'];

    $goldprice = round($item['tpl_gold'] * $_GET['gold_r']);
    $gemsprice = round($item['tpl_gems'] * $_GET['gems_r']);

    $item['tpl_gold'] = round($goldprice * 0.8);
    $item['tpl_gems'] = 0;

    if ( $item['battle_mode'] && item_count( ' tpl_id="'.$_GET['tpl_id'].'" AND owner='.$session['user']['acctid'] ) > 0){
        output("`vDiesen Zauber hast du schon. Du musst ihn entweder aufbrauchen, oder verkaufen, bevor du ihn neu kaufen kannst.");
        addnav("Etwas anderes kaufen","academy.php?op1=bringmetolife&action=buy");
    }
    else{
        output("`vDu deutest auf den Namen in der Liste. Bis auf den Namen des Zaubers \"`V$name`v\" verschwinden alle anderen Worte von der Liste und der Zauberer gibt dir, was du verlangst. Gerade, als du bezahlen willst, schweben ".($goldprice?"`^".$goldprice." `vGold":"")." ".($gemsprice?"`#".$gemsprice."`v Edelsteine":"")." aus deinen Vorräten in die Hand des Zauberers. ");

        $session['user']['gold'] -= $goldprice;
        $session['user']['gems'] -= $gemsprice;

        item_add($session['user']['acctid'],0,$item);

        addnav("Mehr kaufen","academy.php?op1=bringmetolife&action=buy");
        addnav('Zurück');
        addnav('Zum Zauberladen','academy.php?op1=bringmetolife');
        addnav('A?Zur Akademie','academy.php?op1=enter&op2=0');
        addnav('D?Zum Dorf','village.php');
    }

}
else if($_GET['op'] == 'sell_do') {

    $_GET['op1'] = 'blank';

    $show_invent = false;
    $arr_items = array();

    // Multiselect
    if(!empty($_POST['ids']) && is_array($_POST['ids'])) {
        $str_ids = implode(',',$_POST['ids']);
        $res_items = item_list_get(' id IN ('.addslashes(stripslashes($str_ids)).') AND owner='.$session['user']['acctid'].' AND (it.spellshop = 2 OR it.spellshop = 3) ','',true,'name,id,it.tpl_id,gold,gems');

        if(db_num_rows($res_items) == 0) {
            redirect('academy.php?op1=bringmetolife&action=sell');
        }

        $arr_items = db_create_list($res_items);
        $name='die Ware';

    }
    else {

        if(empty($_GET['id'])) {
            redirect('academy.php?op1=bringmetolife&action=sell');
        }

        $arr_items = array(item_get(' id="'.$_GET['id'].'" '));
        $name=$arr_items[0]['name'];
    }

    $goldprice = 0;
    $gemsprice = 0;


    foreach ($arr_items as $item) {

        $goldprice += round($item['gold'] * $_GET['gold_r']);
        $gemsprice += round($item['gems'] * $_GET['gems_r']);
        item_delete(' id='.$item['id']);
    }

    $session['user']['gold'] += $goldprice;
    $session['user']['gems'] += $gemsprice;

    output('`vDer alte Zauberer begutachtet '.$name.'`v. Dann überreicht er dir sorgfältig abgezählt '.($goldprice?'`^'.$goldprice.' `vGold':'').' '.($gemsprice?'`#'.$gemsprice.'`v Edelsteine':'').' und lässt den Zauber verschwinden. Wörtlich. ');

    addnav('Mehr verkaufen','academy.php?op1=bringmetolife&action=sell');
    addnav('Zurück');
    addnav('Zum Zauberladen','academy.php?op1=bringmetolife');
    addnav('A?Zur Akademie','academy.php?op1=enter&op2=0');
    addnav('D?Zum Dorf','village.php');
}

// zwei op-Variablen gesetzt und User erfuellt Bedingungen
if (($_GET['op1'] <> "" && $_GET['op2'] <> "") && $session['user']['dragonkills']>= $min_dk && $rowe['seenacademy'] == 0)
{
    // op1='enter' und op2=0 dann eintreten
    if ($_GET['op1'] == "enter" && $_GET['op2'] == "0")
    {
        output("`c`b`\$Das Innere der Akademie`0`b`c`n
        `^Im Inneren ist es recht kühl und ihr schreitet einen dicken schwarz/roten Teppich mit seltsamen magischen Symbolen entlang.
        `n\"`9Du kannst hier versuchen, Deine`7 `i".$info['specname']."`i `9allein zu verbessern, oder Du nimmst eine Stunde bei mir.
        `nIch werde sicherstellen, dass Du nicht versagst...`^\"
        `nWarchild führt dich zu einem kleinen Tischchen, auf dem ein dickes ledernes Buch liegt und öffnet es für dich. Es enthält eine Preisliste:
        `n`n`0<ul>");
        $f2("academy_desc");

        output("`0</ul>`n`^Direkt unter den Preisen steht sehr klein geschrieben, ein wenig verwischt und kaum lesbar:
        `n`3Da Magie `bunberechenbar`b ist, handelt jeder Schüler auf eigene Gefahr und die Akademie erstattet keine Kosten im Falle von Lernversagen oder anderen Unglücken! 
        `n`^Dir ist klar, dass du während des Lernens natürlich nicht im Wald kämpfen kannst.
        `n`n`3Falls dir das Lernen zu mühsam sein sollte, steht in der Akademie auch ein Zauberladen zur Verfügung, in dem man Fertigzauber mit begrenzter Lebensdauer, die von den Meistern des Geistes und der Materie erschaffen wurden, kaufen kann.");
        if($uses_ges > 20) {
            output("`n`n`3Leider hast DU bereits zu viel gelernt. Niemand hier kann dir noch etwas beibringen!");
        }
        elseif($session['user']['turns']<1)
        {
            output ('`n`7Du verspürst irgendwie kein sonderlich grosses Bedürfnis, heute noch die Schulbank zu drücken. ');
            addnav('- Selbststudium','');
            addnav('- Praktische Übung','');
            addnav('- Stunde bei Warchild','');
        }
        else {
            addnav("Selbststudium","academy.php?op1=enter&op2=study");
            addnav("Praktische Übung","academy.php?op1=enter&op2=practice");
            addnav("W?Stunde bei Warchild","academy.php?op1=enter&op2=warchild");
        }
        addnav("Mit anderen Studenten reden","academy.php?op1=enter&op2=chat");
        addnav("Zauberladen","academy.php?op1=bringmetolife");
        addnav("D?Zurück ins Dorf","village.php");
    }
    
    // nächster Fall: Chat in der Akademie
    if ($_GET['op1'] == "enter" && $_GET['op2'] == "chat")
    {
        output("`^Du gesellst dich zu einer Gruppe Studenten, die um ein Pentagramm herumstehen.
        `nSie erörtern die fiesen Konsequenzen einer misslungenen Dämonenbeschwörung...
        `n`nZuletzt sagten sie:
        `n`n");
        addnav("Wieder hineingehen","academy.php?op1=enter&op2=0");
        viewcommentary("academy","Sprich",25);
    }

    // check if User has enough gems/gold if he wants to learn
    // 1st Case: STUDY
    if ($_GET['op1'] == "enter" &&
    $_GET['op2'] == "study" &&
    $session['user']['gold'] < $cost_low)
    {
        output("`c`b`\$Das Innere der Akademie`0`b`c`n
        `n`\$ Leider kannst du den geforderten Preis nicht bezahlen.`^");
        addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
    }
    else if ($_GET['op1'] == "enter" &&
    $_GET['op2'] == "study" &&
    $session['user']['gold'] >= $cost_low)
    {
        // subtract costs
        $session['user']['gold'] = $session['user']['gold'] - $cost_low;
        $goldpaid = $cost_low;
        //debuglog("paid $goldpaid to the academy");

        // war heute schonmal hier...
        user_set_aei(array('seenacademy'=>1));

        $session['user']['turns']--;

        if ($session['user']['drunkenness'] > 0) // too drunk to learn
        {
            output("`c`b`\$Bibliothek der Akademie`0`b`c`n
            `^Ver*hic*dammt! Du hättest dich mit dem...`\$ ale`^... zurückhalten sollen! Du kannst dich einfach nicht genug konzentrieren um irgendetwas zu lernen.
            `nFrustriert verlässt du die Akademie nach einiger Zeit und stapfst ins Dorf zurück.");
            addnav("Zurück ins Dorf","village.php");
        }
        else // hier geht das Train los
        {
            output("`c`b`\$Bibliothek der Akademie`0`b`c`n");
            switch (e_rand(1,3))
            {
                case 1:
                output("`^Du sitzt in der Bibliothek mit dem Buch in der Hand, als es plötzlich nach dir schnappt und dir in die Hand `4beisst! `6Der Schmerz ist furchtbar!`^
                `nDu versuchst verzweifelt das Buch wieder abzuschütteln während einige andere Studenten einen kleinen Kreis um dich bilden und sich schlapplachen.
                `nFrustriert und fluchend verlässt du die Akademie.
                `n`n`5Du verlierst einige Lebenspunkte!");
                $session['user']['hitpoints'] = $session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.2;
                break;
                case 2:
                output("`^Du verbringst einige Zeit in der Akademie und liest intensiv, doch schon bald ergeben die Wörter irgendwie keinen Sinn mehr. Schliesslich gibst du auf.
                `nFrustriert verlässt du die Akademie.");
                break;
                case 3:
                output("`7Du nimmst dir einen grossen, ledergebundenen Folianten und öffnest ihn. 
                Zunächst geschieht nichts, doch plötzlich `2redet das Buch mit dir!`7
                `nFasziniert lauschst du den geheimen Worten und lernst wirklich etwas über `i".$info['specname']."`i.
                Breit grinsend und stolz auf dein neues Wissen verlässt du die Akademie.`n`n");
                increment_specialty();
                break;
            }
            addnav("Zurück ins Dorf","village.php");
        }
    }

    // 2nd Case: PRACTICE
    if ($_GET['op1'] == "enter" &&
    $_GET['op2'] == "practice" &&
    ($session['user']['gold'] < $cost_medium ||
    $session['user']['gems'] < 1
    ))
    {
        output("`c`b`\$Das Innere der Akademie`0`b`c`n
        `n`\$ Leider kannst du den geforderten Preis nicht bezahlen.`^");
        addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
    }
    else if ($_GET['op1'] == "enter" &&
    $_GET['op2'] == "practice" &&
    ($session['user']['gold'] >= $cost_medium ||
    $session['user']['gems'] >= 1))
    {
        // subtract costs
        $session['user']['gold'] = $session['user']['gold'] - $cost_medium;
        $session['user']['gems']--;
        $goldpaid = $cost_medium;
        //debuglog("paid $goldpaid and 1 gem to the academy");

        // war heute schonmal hier...
        user_set_aei(array('seenacademy'=>1));

        $session['user']['turns']--;

        if ($session['user']['drunkenness'] > 0) // too drunk to learn
        {
            output("`c`b`\$Das Innere der Akademie`0`b`c`n");
            $f2("academy_pratice");

            addnav("Zurück ins Dorf","village.php");
        }
        else // hier geht das Train los
        {
            output("`c`b`\$Bibliothek der Akademie`0`b`c`n");
            $rand = e_rand(1,3);
            switch ($rand)
            {
                case 1:
                output("`^Du verlässt den Trainingsbereich geschlagen und mit einigen blutenden Wunden.
                `nGesenkten Hauptes gehst du ins Dorf zurück.
                `n`n`5Du verlierst ein paar Lebenspunkte!");
                $session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.9;
                break;
                case 2:
                case 3:
                output("`7Nach einer forderndern Trainingsstunde, die du souverän meisterst, machst du dich auf den Heimweg.
                `nBevor du gehst, gratuliert dir Warchild zu dem erfolgreichen Training.`n`n");
                increment_specialty();
                break;
            }
            addnav("Zurück ins Dorf","village.php");
        }
    }

    // 3rd Case: WARCHILD
    if ($_GET['op1'] == "enter" &&
    $_GET['op2'] == "warchild" &&
    ($session['user']['gold'] < $cost_high ||
    $session['user']['gems'] < 2))
    {
        output("`c`b`\$Das Innere der Akademie`0`b`c`n
        `n`\$ Leider kannst du den geforderten Preis nicht bezahlen.`^");
        addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
    }
    else if ($_GET['op1'] == "enter" &&
    $_GET['op2'] == "warchild" &&
    ($session['user']['gold'] >= $cost_high ||
    $session['user']['gems'] >= 2))
    {
        // subtract costs
        $session['user']['gold'] -= $cost_high;
        $session['user']['gems'] -= 2;
        $goldpaid = $cost_high;

        // war heute schonmal hier...
        user_set_aei(array('seenacademy'=>1));

        $session['user']['turns']--;

        //debuglog("paid $goldpaid and 2 gems to the academy");
        output("`c`b`\$Das Innere der Akademie`0`b`c`n");
        if ($session['user']['drunkenness'] > 0) // too drunk to learn
        {
            output("`^Als Warchild deine Fahne riecht schaut er dich angewidert an.
            `n`i\"`9Betrunkene Kreatur! Von mir wirst Du nichts lernen!`^\"`i`^
            `nEr wirft dich hinaus und dein Geld und deine Edelsteine hinter dir her.
            `nBemüht, die kullernden Edelsteine aufzusammeln, kannst du am Ende einige Münzen nicht mehr finden.
            `n`n`5Du verlierst etwas Gold des Lehrgelds!");
            $session['user']['gold'] +=  $cost_high * 0.67;
            $session['user']['gems'] = $session['user']['gems'] + 2;
        }
        else // hier geht das Train los
        {
            output("`7Du verbringst einige Zeit im schwarzen Turm der Akademie in der höchsten Kammer und `4Warchild`7 eröffnet dir eine neue Dimension deiner Fähigkeiten.
            `nDu verlässt den Ort zufrieden und wissender als zuvor!`n`n");
            increment_specialty();
        }
        addnav("Zurück ins Dorf","village.php");
    }
}
else if($_GET['op1']=="bringmetolife") // Zauberladen (written on a cassiopeia while taking a bath)
{
    output("`c`b`VInstant-Zauber aller Art`0`b`c`n");

    $show_invent = true;

    require_once(LIB_PATH.'dg_funcs.lib.php');
    if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
        $rebate = dg_calc_boni($session['user']['guildid'],'rebates_spells',0);
    }

    if ($_GET['action']=="sell"){

        output("`vDu zeigst dem alten Zauberer alle deine Zauber und er sagt dir, was er dafür bezahlen würde.`n`n`0");

        item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_MULTI | ITEM_INVENT_HEAD_SHOP_SELL | ITEM_INVENT_HEAD_SEARCH);

        item_invent_show_data(item_invent_head(' (spellshop = 2 OR spellshop = 3) AND owner='.$session['user']['acctid'],10),'`vDu hast keine Zauber, die du dem Alten anbieten könntest.');

        addnav('Zurück');
        addnav("Zum Laden","academy.php?op1=bringmetolife");
    }
    else if ($_GET['action']=="buy"){ // ok, water's getting cold ^^

        output("`v\"`RDu willst Magie nutzen, ohne sie mühsam studieren zu müssen? Dann bist du hier genau richtig.`v\" Mit diesen Worten überreicht dir der Alte eine Liste aller Zauber, die er dir anbieten kann. \"`RBitte sehr. Wähle dir etwas aus.".($rebate?" Achja, dank deiner Gildenmitgliedschaft gewähre ich dir `^".$rebate." %`R Rabatt!":"")."`v\"`n`n`0");

        $rebate = (100 - $rebate) * 0.01;

        item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_SHOP_BUY | ITEM_INVENT_HEAD_SEARCH,$rebate,$rebate,true);

        item_invent_show_data(item_invent_head(' (spellshop = 1 OR spellshop = 3) ',10),'`v"`RTut mir Leid, mein Freund. Wir haben keine Zauber für dich.`v"');

        addnav('Zurück');
        addnav("Zum Laden","academy.php?op1=bringmetolife");
    }
    else
    {
        $arr_race = race_get($session['user']['race']);
        output("`vDurch schwere und reich verzierte Holztüren betrittst du den Zauberladen der Akademie. Hier bietet ein älterer Zauberer die Werke verschiedenster Akademie-Magier an, denen es gelungen ist, selbst magisch unbegabten ".($arr_race['name_plur'])." wie dir die Anwendung ihrer Zauber zu ermöglichen. 
        Natürlich geht bei Magiern nichts ohne entsprechende Bezahlung, so rechnest du auch hier mit saftigen Preisen, um die hohen Entwicklungskosten, die wohl durch zahlreiche Fehlschläge und unzählige zu Bruch gegangene Zauberutensilien zu erklären sind, auszugleichen.");
        addnav("k?Zauber kaufen","academy.php?op1=bringmetolife&action=buy");
        addnav("v?Zauber verkaufen","academy.php?op1=bringmetolife&action=sell");
    }
    addnav("Zur Akademie","academy.php".($session['user']['dragonkills']>$min_dk?"?op1=enter&op2=0":""));
}
// auf jeden Fall Begrüßung und Einleitung wenn keine Params gesetzt
else if($_GET['op'] != 'buy_do' && $_GET['op'] != 'sell_do')
{
    output("`c`b`\$Warchilds Akademie der geheimen Künste`0`b`c`n
    `^Vorsichtig näherst du dich dem riesigen Tor der Akademie und verharrst einen Augenblick, um die Inschrift über dem Torbogen zu betrachten.
    `n\"`8`iAuch diese Worte werden vergehen`i`^\" steht dort für die Ewigkeit in geschwungenen goldenen Lettern.
    `nDas zweiflügelige dunkelgraue Gemäuer mit vergitterten Fenstern und dem drohend in den Himmel ragenden schwarzen Turm scheint die Worte über deinem Kopf noch zu unterstreichen. 
    Ein kleines Schild neben dem Eingang warnt vor den üblen Konsequenzen von Magie und Alkohol.`n");

    // Heute schonmal hier gewesen? Dann wird's wohl nix :P
    if ($rowe['seenacademy'] == 1)
    {
        output ("`n`7Du verspürst irgendwie kein sonderlich grosses Bedürfnis, heute noch einmal die Schulbank zu drücken, also schlenderst du zum Dorf zurück.");
        addnav("Zurück ins Dorf","village.php");
    }
    elseif ($session['user']['reputation']<-5)
    {
        output ("`n`7Doch als du dich näherst, scheint dich ein Mann in einem schwarzen Mantel zu erkennen und dir auf jeden Fall den Eintritt verweigern zu wollen. 
        Dein schlechter Ruf eilt dir voraus. Es würde dir ja nichts ausmachen, den Kerl niederzumetzeln, aber er dürfte um einiges besser in ");

        if($session['user']['specialty'] == 0) {
            output('allem');
        }
        else {
            $arr_specialty = db_fetch_assoc(db_query('SELECT specname FROM specialty WHERE specid='.$session['user']['specialty']));
            output($arr_specialty['specname']);
        }

        output(" sein als du. So gehst du murrend ins Dorf zurück.");
        addnav("Zurück ins Dorf","village.php");
    }
    else    // User darf heute noch hier rein
        // Wenn User genug Dragonkills hat, Zutritt erlauben
        if ($session['user']['dragonkills']> $min_dk)
        {
            output("`^Warchild steht in der Nähe des Eingangs zur Akademie und wartet, bis du den Hof überquert hast, um dich anzusprechen.
            `n\"`9Ich hörte bereits von Deinen grossen Taten. Tritt doch ein...`^\" sagt er und lächelt dünn.
            `nDann winkt er dich herein.`n`n");
            addnav("Eintreten","academy.php?op1=enter&op2=0");
            addnav("Zurück ins Dorf","village.php");
        }
        // wenn User nicht ausreichend Dragonkills hat, Zutritt ablehnen
        else
        {
            output("In dem ausladenden Innenhof steht ein Mann in einem schwarzen Mantel, der leicht im Wind flattert. Er starrt dich so eindringlich an, dass es dir unerträglich wird, ihn weiter anzusehen. 
            Als du den Blick senkst flattert eine einzelne Krähe vom Dachfirst herunter und landet zwischen den Füssen des Mannes, wo sie einige Blumensamen aufpickt, die dort hingeweht wurden.
            `n\"`9Komm wieder, wenn Du bereit für meinen Unterricht bist.
            Bis dahin kannst du dich höchstens im Zauberladen umsehen.`^\" sagt Warchild ruhig zu dir.
            `nEingeschüchtert schleichst du zurück ins Dorf.`n`n");
            addnav("Zauberladen betreten","academy.php?op1=bringmetolife");
            addnav("Zurück ins Dorf","village.php");
        }
}
page_footer();
?>


