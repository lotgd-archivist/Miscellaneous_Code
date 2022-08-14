
ï»¿<?php



// 20060302



/* Warchild's Magic Academy of the Dark Arts

* Die Akademie der geheimen KÃ¼nste

* coded by Kriegskind/Warchild

* Email: warchild@gmx.org

* February/March 2004

* v0.961dt

*

* Modifikationen von LotGD nÃ¶tig:

* DB - Neues Feld seenAcademy(TINYINT(3)) in accounts, default 0

* newday.php - ZurÃ¼cksetzen von $session[user][seenAcademy] = 0 an jedem Tag

* village.php - Link auf die Akademie einbauen

*

* letzte Modifikation

* 18.3.2004, 17:35 Bibliothekserfolgswahrscheinlichkeit wieder auf 33% erhÃ¶ht (Warchild)

* Adminzugang entfernt

* Zauberladen eingebaut (25.07.2004, anpera)

*/



require_once("common.php");

addcommentary();

// Entscheidungsvariablen op1: Akademie betreten oder nicht, op2: Bezahlungsart und Studienart



page_header("Warchilds Akademie der geheimen KÃ¼nste");



// Kosten: gestaffelt nach Skillevel

$skills = $specialty;

$akt_magiclevel = $session['user'][$skills[$session['user']['specialty']]] + 1; // man faengt bei 0 an ;o)



$cost_low = round((($akt_magiclevel + 1) * 50)-$session['user']['reputation']);

$cost_medium =round((($akt_magiclevel + 1)* 60)-$session['user']['reputation']); //plus ein Edelstein

$cost_high = round((($akt_magiclevel + 1) * 75)-$session['user']['reputation']); //plus 2 Edelsteine



$min_dk = 1; // wieviele DKs muss ein User haben um eintreten zu dÃ¼rfen?



// zwei op-Variablen gesetzt und User erfuellt Bedingungen

if (($_GET['op1'] != "" && $_GET['op2'] != "") && $session['user']['dragonkills']>= $min_dk && $session['user']['seenAcademy'] == 0 && $session['user']['turns']>0)

{

    // op1='enter' und op2=0 dann eintreten

    if ($_GET['op1'] == "enter" && $_GET['op2'] == "0")

    {

        output("`\$`b`c Das Innere der Akademie`c`b`n`n");

        output("`^Im Inneren ist es recht kÃ¼hl und ihr schreitet einen dicken schwarz/roten Teppich");

        output("mit seltsamen magischen Symbolen entlang.`n");

        output("\"Du kannst hier versuchen, Deine`7`i".$specialty[$session['user']['specialty']]."`i");

        output(" `^allein zu verbessern, oder Du nimmst eine Stunde bei mir.`n");

        output("Ich werde sicherstellen, dass Du nicht versagst...\"`n");

        output("Warchild fÃ¼hrt Dich zu einem kleinen Tischchen, auf dem ein dickes ledernes Buch liegt und");

        output("&ouml;ffnet es f&uuml;r Dich. Es enth&auml;lt eine Preisliste:`n`n<ul>",true);

        if ($session['user']['specialty'] == 1)

        {

            output("`3Selbststudium der Dunklen KÃ¼nste: ");

            output("`\$".$cost_low ."`^ Gold`n");

            output("`3Praktischer Unterricht im Tiere quÃ¤len: ");

            output("`\$".$cost_medium ."`^ Gold und `\$1 Edelstein`^`n");

            output("`3Eine Lehrstunde beim Meister der dunklen KÃ¼nste, `\$ Warchild `3selbst, nehmen: ");

            output("`\$".$cost_high ."`^ Gold und `\$2 Edelsteine`^`n");

        }

        elseif ($session['user']['specialty'] == 2)

        {

            output("`3Selbststudium in der Bibliothek: ");

            output("`\$".$cost_low ."`^ Gold`n");

            output("`3Praktische Ãœbung in der Magiekammer: ");

            output("`\$".$cost_medium ."`^ Gold und `\$1 Edelstein`^`n");

            output("`\$ Warchilds `3Mystikstunde: ");

            output("`\$".$cost_high ."`^ Gold und `\$2 Edelsteine`^`n");

        }

        elseif ($session['user']['specialty'] == 3)

        {

            output("`3Selbststudium mit BÃ¼chern Ã¼ber das stille Handwerk: ");

            output("`\$".$cost_low ."`^ Gold`n");

            output("`3Praktische Ãœbung im Diebeslabyrinth: ");

            output("`\$".$cost_medium ."`^ Gold und `\$1 Edelstein`^`n");

            output("`\$ Warchilds `3Lehrstunde fÃ¼r Nachwuchsdiebe: ");

            output("`\$".$cost_high ."`^ Gold und `\$2 Edelsteine`^`n");

        }

        elseif ($session['user']['specialty'] == 4)

        {

            output("`3Ãœbungen auf dem Kampfplatz: ");

            output("`\$".$cost_low ."`^ Gold`n");

            output("`3Praktische Ãœbung in der Arena: ");

            output("`\$".$cost_medium ."`^ Gold und `\$1 Edelstein`^`n");

            output("`\$ Warchilds `3Lehrstunde fÃ¼r NachwuchskÃ¤mpfer: ");

            output("`\$".$cost_high ."`^ Gold und `\$2 Edelsteine`^`n");

        }

        elseif ($session['user']['specialty'] == 5)

        {

            output("`3Selbststudium mit BÃ¼chern Ã¼ber die Wege der GÃ¶tter: ");

            output("`\$".$cost_low ."`^ Gold`n");

            output("`3Meditation: ");

            output("`\$".$cost_medium ."`^ Gold und `\$1 Edelstein`^`n");

            output("`\$ Warchilds `3Lehrstunde fÃ¼r Nachwuchspriester: ");

            output("`\$".$cost_high ."`^ Gold und `\$2 Edelsteine`^`n");

        }

        elseif ($session['user']['specialty'] == 6)

        {

            output("`3Selbststudium mit BÃ¼chern Ã¼ber die Natur: ");

            output("`\$".$cost_low ."`^ Gold`n");

            output("`3Praktische Ãœbung im Wald: ");

            output("`\$".$cost_medium ."`^ Gold und `\$1 Edelstein`^`n");

            output(" `3Lehrstunde mit `\$Warchild`3 Ã¼ber die Natur: ");

            output("`\$".$cost_high ."`^ Gold und `\$2 Edelsteine`^`n");

        }

        output("</ul>`nDirekt unter den Preisen steht sehr klein geschrieben, ein wenig verwischt und kaum lesbar:`n",true);

        output("`3Da Magie `bunberechenbar`b ist, handelt jeder SchÃ¼ler auf eigene Gefahr und die Akademie erstattet keine Kosten im Falle von Lernversagen oder anderen UnglÃ¼cken!");

        output("`^Dir ist klar, dass du wÃ¤hrend des Lernens natÃ¼rlich nicht im Wald kÃ¤mpfen kannst.");

        output("`n`n`3Falls dir das Lernen zu mÃ¼hsam sein sollte, steht in der Akademie auch ein Zauberladen zur VerfÃ¼gung, in dem man Fertigzauber mit begrenzter Lebensdauer, die von den Meistern des Geistes und der Materie erschaffen wurden,  kaufen kann.");

        addnav("Selbststudium","academy.php?op1=enter&op2=study");

        addnav("Praktische Ãœbung","academy.php?op1=enter&op2=practice");

        addnav("Stunde bei Warchild","academy.php?op1=enter&op2=warchild");

        addnav("Mit anderen Studenten reden","academy.php?op1=enter&op2=chat");

        addnav("Zauberladen","academy.php?op1=bringmetolife");

        //addnav("Bibliothek","library.php"); //umweg Ã¼ber tunnel

        addnav("ZurÃ¼ck ins Dorf","village.php");

    }

    // nÃ¤chster Fall: Chat in der Akademie



    if ($_GET['op1'] == "enter" && $_GET['op2'] == "chat")

    {

        output("Du gesellst Dich zu einer Gruppe Studenten, die um ein Pentagramm herumstehen.`n");

        output("Sie erÃ¶rtern die fiesen Konsequenzen einer misslungenen DÃ¤monenbeschwÃ¶rung...");

        output("`n`nZuletzt sagten sie:");

        output("`n`n");

        addnav("Wieder hineingehen","academy.php?op1=enter&op2=0");

        viewcommentary("academy","Sprich",25);

    }



    // check if User has enough gems/gold if he wants to learn

    // 1st Case: STUDY

    if ($_GET['op1'] == "enter" && $_GET['op2'] == "study" && $session['user']['gold'] < $cost_low)

    {

        output("`\$`b`c Das Innere der Akademie`c`b`n`n");

        output("`n`\$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");

        addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");

    }

    elseif ($_GET['op1'] == "enter" && $_GET['op2'] == "study" && $session['user']['gold'] >= $cost_low)

    {

        // subtract costs

        $session['user']['gold'] = $session['user']['gold'] - $cost_low;

        $goldpaid = $cost_low;

        //debuglog("paid $goldpaid to the academy");



        // war heute schonmal hier...

        $session['user']['seenAcademy'] = 1;

        $session['user']['turns']--;



        if ($session['user']['drunkenness'] > 0) // too drunk to learn

        {

            output("`\$`b`c Bibliothek der Akademie`c`b`n`n");

            output("`^Ver*hic*dammt! Du hÃ¤ttest Dich mit dem...`\$ ale`^... zurÃ¼ckhalten sollen! Du kannst Dich einfach");

            output("nicht genug konzentrieren um irgendetwas zu lernen.`n");

            output("Frustriert verlÃ¤sst Du die Akademie nach einiger Zeit und stapfst ins Dorf zurÃ¼ck.");

            addnav("ZurÃ¼ck ins Dorf","village.php");

        }

        else // hier geht das Train los

        {

            output("`\$`b`c Bibliothek der Akademie`c`b`n`n");

            $rand = e_rand(1,3);

            switch ($rand)

            {

                case 1:

                output("`^Du sitzt in der Bibliothek mit dem Buch in der Hand, als es plÃ¶tzlich");

                output("nach Dir schnappt und Dir in die Hand `4beisst! `6Der Schmerz ist furchtbar!`^`n");

                output("Du versuchst verzweifelt das Buch wieder abzuschÃ¼tteln wÃ¤hrend einige andere ");

                output("Studenten einen kleinen Kreis um Dich bilden und sich schlapplachen.`n");

                output("Frustiert und fluchend verlÃ¤sst Du die Akademie.`n`n");

                output("`5Du verlierst einige Lebenspunkte!");

                $session['user']['hitpoints'] = $session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.2;

                break;

                case 2:

                output("`^Du verbringst einige Zeit in der Akademie und liest intensiv, doch schon bald ergeben");

                output("die WÃ¶rter irgendwie keinen Sinn mehr. Schliesslich gibst Du auf.`n");

                output("Frustiert verlÃ¤sst Du die Akademie.");

                break;

                case 3:

                output("`7Du nimmst Dir einen grossen, ledergebundenen Folianten und Ã¶ffnest ihn.");

                output("ZunÃ¤chst geschieht nichts, doch plÃ¶tzlich `2redet das Buch mit Dir!`7`n");

                output("Fasziniert lauschst Du den geheimen Worten und lernst wirklich etwas Ã¼ber `i");

                output($specialty[$session['user']['specialty']]."`i. Breit grinsend und stolz auf Dein neues Wissen verlÃ¤sst Du die Akademie.`n`n");

                increment_specialty();

                break;

            }

            addnav("ZurÃ¼ck ins Dorf","village.php");

        }

    }



    // 2nd Case: PRACTICE

    if ($_GET['op1'] == "enter" && $_GET['op2'] == "practice" && ($session['user']['gold'] < $cost_medium || $session['user']['gems'] < 1))

    {

        output("`\$`b`c Das Innere der Akademie`c`b`n`n");

        output("`n`\$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");

        addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");

    }

    elseif ($_GET['op1'] == "enter" && $_GET['op2'] == "practice" && ($session['user']['gold'] >= $cost_medium || $session['user']['gems'] >= 1))

    {

        // subtract costs

        $session['user']['gold'] = $session['user']['gold'] - $cost_medium;

        $session['user']['gems']--;

        $goldpaid = $cost_medium;

        //debuglog("paid $goldpaid and 1 gem to the academy");



        // war heute schonmal hier...

        $session['user']['seenAcademy'] = 1;

        $session['user']['turns']--;



        if ($session['user']['drunkenness'] > 2) // too drunk to learn

        {

            output("`\$`b`c Das Innere der Akademie`c`b`n`n");

            $session['user']['hitpoints']=$session['user']['hitpoints']-$session['user']['hitpoints']*0.2;

            if ($session['user']['specialty'] == 1)

            {

                output("`^Du betrittst den `7TierkÃ¤fig`^!`n");

                output("Ein niedlich aussehendes, weisses Kaninchen sitzt in der Mitte des KÃ¤figs und glotzt");

                output("Dich an. Du holst zum Schlag aus, doch auf einmal springt es auf Dich zu und");

                output("`$ grÃ¤bt seine ZÃ¤hne in Deine Hand!`^ GlÃ¼cklicherweise bist Du noch zu betrunken um den Schmerz zu fÃ¼hlen...`n");

                output("aber dafÃ¼r wird Deine Hand morgen hÃ¶llisch weh tun!`n");

                output("Mit einer bandagierten Hand verlÃ¤sst Du den Ort.`n`n");

            }

            elseif ($session['user']['specialty'] == 2)

            {

                output("`^Du betrittst die `7Magiekammer`^!`n");

                output("Ein Golem marschiert auf Dich zu, doch Deine Sicht ist vom Alkohol noch so verschwommen, dass Dein Spruch ihn verfehlt!`n");

                output("Statt dessen trifft er Dich mit einer grossen Keule und Du verlierst das Bewusstsein.`n");

                output("Nach ein paar Minuten wachst Du vor der Akademie mit fiesen Kopfschmerzen wieder auf und torkelst zurÃ¼ck in die Stadt.`n`n");

            }

            elseif ($session['user']['specialty'] == 3)

            {

                output("`^Du betrittst das `7Labyrinth der Fallen`^!`n");

                output("WÃ¤hrend Du, immer langsam an der Wand lang wegen des Alkohols, Dich in Richtung des Eingangs bewegst (oh Mann Du bist betrunken!), kann Warchild ein grausames LÃ¤cheln nicht unterdrÃ¼cken.`n");

                output("Um es kurz zu machen: Du wirst dreimal von einer vergifteten Nadel gestochen, schneidest Dich zweimal an einem");

                output(" versteckten Draht und einmal Ã¼bersiehst Du die grosse FalltÃ¼r, durch die man direkt in den MÃ¼llkÃ¼bel fÃ¤llt,");

                output(" der vor der Akademie steht.`n");

                output("Halbtot sammelst Du die Reste von Dir wieder zusammen und wankst zurÃ¼ck ins Dorf.`n`n");

            }

            elseif ($session['user']['specialty'] == 4)

            {

                output("`^Du betrittst den Kampfplatz`n");

                output("Die Ãœbungspuppen bewegen sich heute aber wild durch die Gegend. Und sind das doppelt so viele wie sonst?");

                output("Flink und geschickt weichen sie deinen unbeholfenen SchlÃ¤gen aus. PlÃ¶tzlich hat sich eine der im Boden fest verankerten Puppen in deinen RÃ¼cken geschlichen und greift an.`n");

                output("Halbtot sammelst Du die Reste von Dir wieder zusammen und wankst zurÃ¼ck ins Dorf.`n`n");

            }

            elseif ($session['user']['specialty'] == 5)

            {

                output("`^Du verknotest deine Glieder und versuchst, Verbindung mit deinem vernebelten Geist aufzunehmen!`n");

                output("Doch schnell merkst du, dass du dich nicht ausreichend konzentrieren kannst.");

                output("Leider kannst du dich nicht mehr aus eigener Kraft aus deiner Verknotung befreien.");

                output("Ein gÃ¶ttlicher Blitz sprengt dich auseinander. Das schmerzt!");

            }

            elseif ($session['user']['specialty'] == 6)

            {

                output("`^Du versuchst, das abgesteckte Ãœbungsgebiet im Wald zu finden.`n");

                output("Ein paar der Waldbewohner beÃ¤ugen dich misstrauisch, als du weiter und weiter in den Wald torkelst.");

                output("Nunja, es kommt, wie es kommen musste. Die Natur wendet sich gegen dich. Autsch!");                    }

            output("`5Du verlierst einige Menge Lebenspunkte!");

            addnav("ZurÃ¼ck ins Dorf","village.php");

        }

        else // hier geht das Train los

        {

            output("`\$`b`c Bibliothek der Akademie`c`b`n`n");

            $rand = e_rand(1,3);

            switch ($rand)

            {

                case 1:

                output("`^Du verlÃ¤sst den Trainingsbereich geschlagen und mit einigen blutenden Wunden.`n");

                output("Gesenkten Hauptes gehst Du ins Dorf zurÃ¼ck.`n`n");

                output("`5Du verlierst ein paar Lebenspunkte!");

                $session['user']['hitpoints']*=0.9;

                break;

                case 2:

                case 3:

                output("`7Nach einer forderndern Trainingsstunde, die Du souverÃ¤n meisterst, machst Du Dich auf den Heimweg.`n");

                output("Bevor Du gehst, gratuliert Dir Warchild zu dem erfolgreichen Training.`n`n");

                increment_specialty();

                break;

            }

            addnav("ZurÃ¼ck ins Dorf","village.php");

        }

    }



    // 3rd Case: WARCHILD

    if ($_GET['op1'] == "enter" && $_GET['op2'] == "warchild" && ($session['user']['gold'] < $cost_high || $session['user']['gems'] < 2))

    {

        output("`\$`b`c Das Innere der Akademie`c`b`n`n");

        output("`n`\$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");

        addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");

    }

    else if ($_GET['op1'] == "enter" && $_GET['op2'] == "warchild" && ($session['user']['gold'] >= $cost_high || $session['user']['gems'] >= 2))

    {

        // subtract costs

        $session['user']['gold']-=$cost_high;

        $session['user']['gems']-=2;

        $goldpaid = $cost_high;



        // war heute schonmal hier...

        $session['user']['seenAcademy'] = 1;

        $session['user']['turns']--;



        //debuglog("paid $goldpaid and 2 gems to the academy");

        output("`\$`b`c Das Innere der Akademie`c`b`n`n");

        if ($session['user']['drunkenness'] > 2) // too drunk to learn

        {

            output("`^Als Warchild Deine Fahne riecht schaut er Dich angewidert an.`n");

            output("`7`i\"Betrunkene Kreatur! Von mir wirst Du nichts lernen!\"`i`^`n");

            output("Er wirft Dich hinaus und Dein Geld und Deine Edelsteine hinter Dir her.`n");

            output("BemÃ¼ht, die kullernden Edelsteine aufzusammeln, kannst Du am Ende einige MÃ¼nzen nicht mehr finden.`n`n");

            output("`5Du verlierst etwas Gold des Lehrgelds!");

            $session['user']['gold'] +=  round($cost_high * 0.67);

            $session['user']['gems']+=2;

        }

        else // hier geht das Train los

        {

            output("`7Du verbringst einige Zeit im schwarzen Turm der Akademie in der hÃ¶chsten Kammer");

            output("und `4Warchild`7 erÃ¶ffnet Dir eine neue Dimension Deiner FÃ¤higkeiten.`n");

            output("Du verlÃ¤sst den Ort zufrieden und wissender als zuvor!`n`n");

            increment_specialty();

        }

        addnav("ZurÃ¼ck ins Dorf","village.php");

    }

}elseif($_GET['op1']=="bringmetolife"){ // Zauberladen (written on a cassiopeia while taking a bath)

    output("`b`c`VInstant-Zauber aller Art`c`b`0`n");

    if ($_GET['action']=="sell"){

        if (isset($_GET['id'])){

            $getid=(int)$_GET['id'];

            $sql="SELECT * FROM items WHERE id=$getid";

            $result=db_query($sql);

              $row = db_fetch_assoc($result);

            output("`vDer alte Zauberer begutachtet {$row['name']}`v. Dann Ã¼berreicht er dir sorgfÃ¤ltig abgezÃ¤hlt ".($row['gold']?"`^{$row['gold']} `vGold":"")." ".($row['gems']?"`#{$row['gems']}`v Edelsteine":"")." und lÃ¤sst den Zauber verschwinden. WÃ¶rtlich. ");

            addnav("Mehr verkaufen","academy.php?op1=bringmetolife&action=sell");

            if (getsetting("usedspells",0)==1){

                $sql="UPDATE items SET owner=0 WHERE id=$getid";

            }else{

                $sql="DELETE FROM items WHERE id=$getid";

            }

            $session['user']['gold']+=$row['gold'];

            $session['user']['gems']+=$row['gems'];

            db_query($sql);

        }else{

            $sql="SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND (gold>0 OR gems>0) AND class='Zauber' ORDER BY name ASC";

            $result=db_query($sql);

            if (db_num_rows($result)){

                output("`vDu zeigst dem alten Zauberer alle deine Zauber und er sagt dir, was er dafÃ¼r bezahlen wÃ¼rde.`n`n");

                output("<table border='0' cellpadding='1' cellspacing='3'>",true);

                output("<tr class='trhead'><td>`bName`b</td><td>`bPreis`b</td></tr>",true);

                for ($i=0;$i<db_num_rows($result);$i++){

                      $row = db_fetch_assoc($result);

                    $bgcolor=($i%2==1?"trlight":"trdark");

                    output("<tr class='$bgcolor'><td><a href='academy.php?op1=bringmetolife&action=sell&id={$row['id']}'>{$row['name']}</a></td><td align='right'>`^{$row['gold']}`0 Gold, `#{$row['gems']}`0 Edelsteine</td></tr><tr class='$bgcolor'><td colspan='2'>{$row['description']}</td></tr>",true);

                    addnav("","academy.php?op1=bringmetolife&action=sell&id={$row['id']}");

                }

                output("</table>",true);

            } else {

                output("`vDu hast keine Zauber, die du dem Alten anbieten kÃ¶nntest.");

            }

        }

        addnav("Zum Laden","academy.php?op1=bringmetolife");

    }else if ($_GET['action']=="buy"){ // ok, water's getting cold ^^

        if (isset($_GET['id'])){

            $getid=(int)$_GET['id'];

            $sql="SELECT * FROM items WHERE id=$getid";

            $result=db_query($sql);

              $row = db_fetch_assoc($result);

            if ($session['user']['gems']<$row['gems'] || $session['user']['gold']<$row['gold']){

                output("`vDas kannst du dir nicht leisten. Aber wie konntest du die magische Liste austricksen?");

                addnav("Etwas anderes kaufen","academy.php?op1=bringmetolife&action=buy");

            }else if (db_num_rows(db_query("SELECT id FROM items WHERE name='{$row['name']}' AND owner=".$session['user']['acctid']." AND class='Zauber'"))>0){

                output("`vDiesen Zauber hast du schon. Du musst ihn entweder aufbrauchen, oder verkaufen, bevor du ihn neu kaufen kannst.");

                addnav("Etwas anderes kaufen","academy.php?op1=bringmetolife&action=buy");

            }else{

                output("`vDu deutest auf den Namen in der Liste. Bis auf den Namen des Zaubers \"`V{$row['name']}`v\" verschwinden alle anderen Worte von der Liste und der Zauberer gibt dir, was du verlangst. Gerade, als du bezahlen willst, schweben ".($row['gold']?"`^{$row['gold']} `vGold":"")." ".($row['gems']?"`#{$row['gems']}`v Edelsteine":"")." aus deinen VorrÃ¤ten in die Hand des Zauberers. ");

                addnav("Mehr kaufen","academy.php?op1=bringmetolife&action=buy");

                if (strpos($row['class'],".Prot")>0){

                    $sql="INSERT INTO items(name,class,owner,value1,value2,hvalue,gold,gems,description,buff) VALUES ('{$row['name']}','Zauber',".$session['user']['acctid'].",{$row['value1']},{$row['value2']},{$row['hvalue']},{$row['gold']},{$row['gems']},'".addslashes($row['description'])."','".addslashes($row['buff'])."')";

                }else{

                    $sql="UPDATE items SET owner=".$session['user']['acctid']." WHERE id=$getid";

                }

                $session['user']['gold']-=$row['gold'];

                $session['user']['gems']-=$row['gems'];

                db_query($sql);

            }

        }else{

            output("`v\"`RDu willst Magie nutzen, ohne sie mÃ¼hsam studieren zu mÃ¼ssen? Dann bist du hier genau richtig.`v\" Mit diesen Worten Ã¼berreicht dir der Alte eine Liste aller Zauber, die er dir anbieten kann. \"`RBitte sehr. WÃ¤hle dir etwas aus.`v\"`n`n");

            $ppp=25; // Player Per Page to display

            if (!$_GET['limit']){

                $page=0;

            }else{

                $page=(int)$_GET['limit'];

                addnav("Vorherige Zauber","academy.php?op1=bringmetolife&action=buy&limit=".($page-1));

            }

            $limit="".($page*$ppp).",".($ppp+1);

            $gebrauchte="";

            if (getsetting("usedspells",0)==1) $gebrauchte="(owner=0 AND class='Zauber') OR ";

            $sql="SELECT * FROM items WHERE ".$gebrauchte."class='Zauber.Prot' AND gold<=".$session['user']['gold']." AND gems<=".$session['user']['gems']." ORDER BY class DESC,name ASC LIMIT $limit";

            $result=db_query($sql);

            if (db_num_rows($result)>$ppp) addnav("Mehr Zauber","academy.php?op1=bringmetolife&action=buy&limit=".($page+1));

            if (db_num_rows($result)){

                output("<table border='0' cellpadding='2' cellspacing='2'>",true);

                output("<tr class='trhead'><td>`bName`b</td><td>`bPreis`b</td></tr>",true);

                for ($i=0;$i<db_num_rows($result);$i++){

                      $row = db_fetch_assoc($result);

                    $bgcolor=($i%2==1?"trlight":"trdark");

                    output("<tr class='$bgcolor'><td><a href='academy.php?op1=bringmetolife&action=buy&id={$row['id']}'>{$row['name']}</a></td><td align='right'>`^{$row['gold']}`0 Gold, `#{$row['gems']}`0 Edelsteine</td></tr><tr class='$bgcolor'><td colspan='2'>{$row['description']}</td></tr>",true);

                    addnav("","academy.php?op1=bringmetolife&action=buy&id={$row['id']}");

                }

                output("</table>",true);



            } else {

                output("`v\"`RTut mir Leid, mein Freund. Wir haben keine Zauber fÃ¼r dich.`v\"");

            }

        }

        addnav("Zum Laden","academy.php?op1=bringmetolife");

    }else{

        if (checkpvp($session['user']['acctid'])){

            output ("`n`vNach einem schweren Treffer deines Gegners in der Arena schweifst du in Gedanken ab.");

            output(" Du stellst dir vor, wie du in der Akademie neue Zauber kaufst, mit denen du deinem Gegner so richtig einheizen kÃ¶nntest.");

            output(" Doch leider kannst du mitten in einem Kampf hier nicht einkaufen gehen. ");

            addnav("Weiter","pvparena.php");

        }else{

            output("`vDurch schwere und reich verzierte HolztÃ¼ren betrittst du den Zauberladen der Akademie. Hier bietet ein Ã¤lterer Zauberer die Werke verschiedenster Akademie-Magier an, denen es gelungen ist, selbst magisch unbegabten ".($races[$session['user']['race']])."en wie dir die Anwendung ihrer Zauber zu ermÃ¶glichen.");

            output(" NatÃ¼rlich geht bei Magiern nichts ohne entsprechende Bezahlung, so rechnest du auch hier mit saftigen Preisen, um die hohen Entwicklungskosten, die wohl durch zahlreiche FehlschlÃ¤ge und unzÃ¤hlige zu Bruch gegangene Zauberutensilien zu erklÃ¤ren sind, auszugleichen.");

            addnav("Zauber verkaufen","academy.php?op1=bringmetolife&action=sell");

            addnav("Zauber kaufen","academy.php?op1=bringmetolife&action=buy");

        }

    }

    addnav("ZurÃ¼ck zu Akademie","academy.php".($session['user']['dragonkills']>$min_dk?"?op1=enter&op2=0":"")."");

}

// auf jeden Fall BegrÃ¼ÃŸung und Einleitung wenn keine Params gesetzt

else

{

    output("`\$`b`c Warchilds Akademie der geheimen KÃ¼nste`c`b`n`n");

    output("`^Vorsichtig nÃ¤herst Du Dich dem riesigen Tor der Akademie und verharrst einen Augenblick,");

    output("um die Inschrift Ã¼ber dem Torbogen zu betrachten.`n");

    output(" \"`8`iAuch diese Worte werden vergehen`i`^\" steht dort fÃ¼r die Ewigkeit in geschwungenen goldenen Lettern.`n");

    output("Das zweiflÃ¼gelige dunkelgraue GemÃ¤uer mit vergitterten Fenstern und dem drohend in den Himmel ragenden");

    output("schwarzen Turm scheint die Worte Ã¼ber Deinem Kopf noch zu unterstreichen.");

    output("Ein kleines Schild neben dem Eingang warnt vor den Ã¼blen Konsequenzen von Magie und Alkohol.`n");



    // Heute schonmal hier gewesen? Dann wird's wohl nix :P

    if ($session['user']['seenAcademy'] == 1 || $session['user']['turns']<1)

    {

        output ("`n`7Du verspÃ¼rst irgendwie kein sonderlich grosses BedÃ¼rfnis, heute noch einmal die Schulbank zu drÃ¼cken, ");

        output("also schlenderst Du zum Dorf zurÃ¼ck.");

        addnav("ZurÃ¼ck ins Dorf","village.php");

    }elseif ($session['user']['reputation']<-5){

        output ("`n`7Doch als du dich nÃ¤herst, scheint dich ein Mann in einem schwarzen Mantel zu erkennen und dir auf jeden Fall den Eintritt verweigern zu wollen. ");

        output(" Dein schlechter Ruf eilt dir voraus. Es wÃ¼rde dir ja nichts ausmachen, den Kerl niederzumetzeln, aber er dÃ¼rfte um einiges besser in ");

        if ($session['user']['specialty'] == 1) output("den `\$Dunklen KÃ¼nsten`7");

        if ($session['user']['specialty'] == 2) output("den `%Mystischen KrÃ¤ften`7");

        if ($session['user']['specialty'] == 3) output("`^DiebeskÃ¼nsten`7");

        if ($session['user']['specialty'] == 4) output("`qKampfkunst`7");

        if ($session['user']['specialty'] == 5) output("den `#Spirituellen KrÃ¤ften`7");

        if ($session['user']['specialty'] == 6) output("`@Naturkraft`7");

        output(" sein als du. So gehst du murrend ins Dorf zurÃ¼ck.");

        addnav("ZurÃ¼ck ins Dorf","village.php");

    }else    // User darf heute noch hier rein

        // Wenn User genug Dragonkills hat, Zutritt erlauben

        if ($session['user']['dragonkills']> $min_dk)

        {

            output("Etwas abseits, fast von BÃ¼schen verdeckt, entdeckst du den Eingang zu einem Tunnel, der wohl erst vor Kurzem wieder freigelegt wurde.");

            output("Ãœber dem Tunnel wurde ein Schild angebracht: `vZur Bibliothek");

            output("`^`n`nWarchild steht in der NÃ¤he des Eingangs zur Akademie und wartet, bis Du den Hof Ã¼berquert hast, um Dich anzureden.`n");

            output("\"`9Ich hÃ¶rte bereits von Deinen grossen Taten. Tritt doch ein...`^\" sagt er und lÃ¤chelt dÃ¼nn.`n");

            output("Dann winkt er Dich herein.`n`n");

            addnav("Eintreten","academy.php?op1=enter&op2=0");

            addnav("Bibliothek","library.php");

            addnav("ZurÃ¼ck ins Dorf","village.php");

        }

        // wenn User nicht ausreichend Dragonkills hat, Zutritt ablehnen

        else

        {

            output("In dem ausladenden Innenhof steht ein Mann in einem schwarzen Mantel, der leicht im Wind flattert. Er starrt Dich so eindringlich an, dass es Dir unertrÃ¤glich wird, ihn weiter anzusehen.");

            output("Als Du den Blick senkst flattert eine einzelne KrÃ¤he vom Dachfirst herunter und landet zwischen");

            output("den FÃ¼ssen des Mannes, wo sie einige Blumensamen aufpickt, die dort hingeweht wurden.`n");

            output("\"`9Komm wieder, wenn Du bereit fÃ¼r meinen Unterricht bist. Bis dahin kannst du dich hÃ¶chstens im Zauberladen umsehen.`^\" sagt Warchild ruhig zu Dir.`n");

            output("EingeschÃ¼chtert schleichst Du zurÃ¼ck ins Dorf.`n`n");

            addnav("Zauberladen betreten","academy.php?op1=bringmetolife");

            addnav("ZurÃ¼ck ins Dorf","village.php");

        }

}



page_footer();

?>

