<?php
/* Warchild's Magic Academy of the Dark Arts
* Die Akademie der geheimen Künste
* coded by Kriegskind/Warchild
* Email: warchild@gmx.org
* February/March 2004
* v0.961dt
*
* Modifikationen von LotGD nötig: 
* DB - Neues Feld seenAcademy(TINYINT(3)) in accounts, default 0
* newday.php - Zurücksetzen von $session[user][seenAcademy] = 0 an jedem Tag
* village.php - Link auf die Akademie einbauen
*
* letzte Modifikation
* 18.3.2004, 17:35 Bibliothekserfolgswahrscheinlichkeit wieder auf 33% erhöht (Warchild)
* Adminzugang entfernt
*
* SONDERMOD by ANGEL
* SKILLS MODIFIKATION
*/

require_once("common.php");
addcommentary();
// Entscheidungsvariablen op1: Akademie betreten oder nicht, op2: Bezahlungsart und Studienart

page_header("Warchilds Akademie der geheimen Künste");

// Kosten: gestaffelt nach Skillevel
$skills = array(1=>"darkarts","magic","thievery");
$akt_magiclevel = $session[user][$skills[$session[user][specialty]]] + 1; // man faengt bei 0 an ;o)

$act2_magiclevel = $session[user][skilllevel]+1;

$cost_low = ($akt_magiclevel + 1) * 50;
$cost_medium = ($akt_magiclevel + 1)* 75; //plus ein Edelstein
$cost_high = ($akt_magiclevel + 1) * 100; //plus 2 Edelsteine

$cost_low2 = ($akt_magiclevel2 + 1) * 50;
$cost_medium2 = ($akt_magiclevel2 + 1)* 75; //plus ein Edelstein
$cost_high2 = ($akt_magiclevel2 + 1) * 100; //plus 2 Edelsteine

$min_dk = 1; // wieviele DKs muss ein User haben um eintreten zu dürfen?

// zwei op-Variablen gesetzt und User erfuellt Bedingungen
if (($_GET[op1] <> "" && $_GET[op2] <> "") && $session['user']['dragonkills']>= $min_dk && $session[user][seenAcademy] == 0 && $session[user][turns]>0)
{
        // op1='enter' und op2=0 dann eintreten
        if ($_GET[op1] == "enter" && $_GET[op2] == "0")
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`^Im Inneren ist es recht kühl und ihr schreitet einen dicken schwarz/roten Teppich");
                output("mit seltsamen magischen Symbolen entlang.`n");
                output("\"Du kannst hier versuchen, Deine`7");
                if ($session[user][specialty] == 1)
                output(" `iDunklen Künste`i");
                else if ($session[user][specialty] == 2)
                output(" `iMystischen Kräfte`i");
                else
                output(" `iDiebeskunst`i");
                output(" `^allein zu verbessern, oder Du nimmst eine Stunde bei mir.`n");
                output("Ich werde sicherstellen, dass Du nicht versagst...\"`n");
                output("Warchild führt Dich zu einem kleinen Tischchen, auf dem ein dickes ledernes Buch liegt und");
                output("öffnet es für Dich. Es enthält eine Preisliste:`n`n<ul>",true);
                if ($session[user][specialty] == 1)
                {
                        output("`3Selbststudium der Dunklen Künste: ");
                        output("`$".$cost_low ."`^ Gold`n");
                        output("`3Praktischer Unterricht im Tiere quälen: ");
                        output("`$".$cost_medium ."`^ Gold und `$1 Edelstein`^`n");
                        output("`3Eine Lehrstunde beim Meister der dunklen Künste, `$ Warchild `3selbst, nehmen: ");
                        output("`$".$cost_high ."`^ Gold und `$2 Edelsteine`^`n");
                }
                else if ($session[user][specialty] == 2)
                {
                        output("`3Selbststudium in der Bibliothek: ");
                        output("`$".$cost_low ."`^ Gold`n");
                        output("`3Praktische Übung in der Magiekammer: ");
                        output("`$".$cost_medium ."`^ Gold und `$1 Edelstein`^`n");
                        output("`$ Warchilds `3Mystikstunde: ");
                        output("`$".$cost_high ."`^ Gold und `$2 Edelsteine`^`n");
                }
                else
                {
                        output("`3Selbststudium mit Büchern über das stille Handwerk: ");
                        output("`$".$cost_low ."`^ Gold`n");
                        output("`3Praktische Übung im Diebeslabyrinth: ");
                        output("`$".$cost_medium ."`^ Gold und `$1 Edelstein`^`n");
                        output("`$ Warchilds `3Lehrstunde für Nachwuchsdiebe: ");
                        output("`$".$cost_high ."`^ Gold und `$2 Edelsteine`^`n");
                }
                if ($session[user][skill]!=0){
                   $sql="SELECT * FROM skills WHERE id=".$session[user][skill]."";
                   $result = db_query($sql) or die(db_error(LINK));
                   $row = db_fetch_assoc($result);
                   output("`n`@BETA!`n");
                   output("`3Selbststudium in $row[color] $row[name]: ");
                   output("`$".$cost_low2 ."`^ Gold`n");
                   output("`3Praktischer Unterricht in $row[color] $row[name]: ");
                   output("`$".$cost_medium2 ."`^ Gold und `$1 Edelstein`^`n");
                   output("`3Eine Lehrstunde beim Meister des $row[color] $row[name]s, `$ Warchild `3selbst, nehmen: ");
                   output("`$".$cost_high2 ."`^ Gold und `$2 Edelsteine`^`n");
                }
                output("</ul>`nDirekt unter den Preisen steht sehr klein geschrieben, ein wenig verwischt und kaum lesbar:`n",true);
                output("`3Da Magie `bunberechenbar`b ist, handelt jeder Schüler auf eigene Gefahr und die Akademie erstattet keine Kosten im Falle von Lernversagen oder anderen Unglücken!");
                output("`^Dir ist klar, dass du während des Lernens natürlich nicht im Wald kämpfen kannst.");
                addnav("Selbststudium","academy.php?op1=enter&op2=study");
                addnav("Praktische Übung","academy.php?op1=enter&op2=practice");
                addnav("Stunde bei Warchild`n","academy.php?op1=enter&op2=warchild");
                if ($session[user][skill]!=0){
                   addnav("`@Beta");
                   addnav("Selbststudium","academy.php?op1=enter&op2=study2");
                   addnav("Praktische Übung","academy.php?op1=enter&op2=practice2");
                   addnav("Stunde bei Warchild`n","academy.php?op1=enter&op2=warchild2");
                }
                addnav("`0Sonstiges");
                addnav("Mit anderen Studenten reden`n","academy.php?op1=enter&op2=chat");
                addnav("Zurück ins Dorf","village.php");
        }
        // nächster Fall: Chat in der Akademie
        /*
        * DEBUG: FUNZT?
        */
        if ($_GET[op1] == "enter" && $_GET[op2] == "chat")
        {
                output("Du gesellst Dich zu einer Gruppe Studenten, die um ein Pentagramm herumstehen.`n");
                output("Sie erörtern die fiesen Konsequenzen einer misslungenen Dämonenbeschwörung...");
                output("`n`nZuletzt sagten sie:");
                output("`n`n");
                addnav("Wieder hineingehen","academy.php?op1=enter&op2=0");
                viewcommentary("academy","Sprich",25);
        }
        
        //ANGEL MOD START
        // check if User has enough gems/gold if he wants to learn
        // 1st Case: STUDY
        if ($_GET[op1] == "enter" &&
        $_GET[op2] == "study2" &&
        $session[user][gold] < $cost_low2)
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`n`$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");
                addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
        }
        else if ($_GET[op1] == "enter" &&
        $_GET[op2] == "study2" &&
        $session[user][gold] >= $cost_low2)
        {
                // subtract costs
                $session[user][gold] = $session[user][gold] - $cost_low2;
                $goldpaid = $cost_low2;
                //debuglog("paid $goldpaid to the academy");

                // war heute schonmal hier...
                $session[user][seenAcademy] = 1;
                $session[user][turns]--;

                if ($session['user']['drunkenness'] > 0) // too drunk to learn
                {
                        output("`$`b`c Bibliothek der Akademie`c`b`n`n");
                        output("`^Ver*hic*dammt! Du hättest Dich mit dem...`$ ale`^... zurückhalten sollen! Du kannst Dich einfach");
                        output("nicht genug konzentrieren um irgendetwas zu lernen.`n");
                        output("Frustriert verlässt Du die Akademie nach einiger Zeit und stapfst ins Dorf zurück.");
                        addnav("Zurück ins Dorf","village.php");
                }
                else // hier geht das Train los
                {
                        output("`$`b`c Bibliothek der Akademie`c`b`n`n");
                        $rand = e_rand(1,3);
                        switch ($rand)
                        {
                                case 1:
                                output("`^Du sitzt in der Bibliothek mit dem Buch in der Hand, als es plötzlich");
                                output("nach Dir schnappt und Dir in die Hand `4beisst! `6Der Schmerz ist furchtbar!`^`n");
                                output("Du versuchst verzweifelt das Buch wieder abzuschütteln während einige andere ");
                                output("Studenten einen kleinen Kreis um Dich bilden und sich schlapplachen.`n");
                                output("Frustiert und fluchend verlässt Du die Akademie.`n`n");
                                output("`5Du verlierst einige Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints] - $session[user][hitpoints] * 0.2;
                                break;
                                case 2:
                                output("`^Du verbringst einige Zeit in der Akademie und liest intensiv, doch schon bald ergeben");
                                output("die Wörter irgendwie keinen Sinn mehr. Schliesslich gibst Du auf.`n");
                                output("Frustiert verlässt Du die Akademie.");
                                break;
                                case 3:
                                output("`7Du nimmst Dir einen grossen, ledergebundenen Folianten und öffnest ihn.");
                                output("Zunächst geschieht nichts, doch plötzlich `2redet das Buch mit Dir!`7`n");
                                output("Fasziniert lauschst Du den geheimen Worten und lernst wirklich etwas über deine besondere Fähigkeit");
                                output(". Breit grinsend und stolz auf Dein neues Wissen verlässt Du die Akademie.`n`n");
                                increment_specialty();
                                break;
                        }
                        addnav("Zurück ins Dorf","village.php");
                }
        }

        // 2nd Case: PRACTICE
        if ($_GET[op1] == "enter" &&
        $_GET[op2] == "practice2" &&
        ($session[user][gold] < $cost_medium2 ||
        $session[user][gems] < 1
        ))
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`n`$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");
                addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
        }
        else if ($_GET[op1] == "enter" &&
        $_GET[op2] == "practice2" &&
        ($session[user][gold] >= $cost_medium2 ||
        $session[user][gems] >= 1))
        {
                // subtract costs
                $session[user][gold] = $session[user][gold] - $cost_medium2;
                $session[user][gems]--;
                $goldpaid = $cost_medium2;
                //debuglog("paid $goldpaid and 1 gem to the academy");

                // war heute schonmal hier...
                $session[user][seenAcademy] = 1;
                $session[user][turns]--;

                if ($session['user']['drunkenness'] > 0) // too drunk to learn
                {
                        output("`$`b`c Das Innere der Akademie`c`b`n`n");
                        if ($session[user][specialty] == 1)
                        output("`^Du betrisst einen dunklen Raum`^!`n");
                        output("Plötzlich hörst du von irgendwoher ein Geräusch und machst dich kampfbereit.");
                        output("Von außen ist nur gelegentlich ein Umpf oder ein Autsch zu hören, ständig begleitet von");
                        output("irgendeinem Gepoltere das nicht eindeutig zugeordnet werden kann.");
                        output("Nach einigen Stunden in dieser Dunkelkammer kommst du wieder aus der Kammer übersäht mit Blessuren");
                        output("und Wunden. Glücklicherweise bist du noch zu betrunken um den Schmerz zu fühlen...`n");
                        output("Mit Verbänden am ganzen Körper verlässt du den Ort.`n`n");
                        output("`5Du verlierst ein paar Lebenspunkte!");
                        $session[user][hitpoints] = $session[user][hitpoints] - $session[user][hitpoints] * 0.2;
                        addnav("Zurück ins Dorf","village.php");
                }
                else // hier geht das Train los
                {
                        output("`$`b`c Bibliothek der Akademie`c`b`n`n");
                        $rand = e_rand(1,3);
                        switch ($rand)
                        {
                                case 1:
                                output("`^Du verlässt den Trainingsbereich geschlagen und mit einigen blutenden Wunden.`n");
                                output("Gesenkten Hauptes gehst Du ins Dorf zurück.`n`n");
                                output("`5Du verlierst ein paar Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints]  * 0.9;
                                break;
                                case 2:
                                case 3:
                                output("`7Nach einer forderndern Trainingsstunde, die Du souverän meisterst, machst Du Dich auf den Heimweg.`n");
                                output("Bevor Du gehst, gratuliert Dir Warchild zu dem erfolgreichen Training.`n`n");
                                increment_specialty();
                                break;
                        }
                        addnav("Zurück ins Dorf","village.php");
                }
        }

        // 3rd Case: WARCHILD
        if ($_GET[op1] == "enter" &&
        $_GET[op2] == "warchild2" &&
        ($session[user][gold] < $cost_high2 ||
        $session[user][gems] < 2))
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`n`$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");
                addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
        }
        else if ($_GET[op1] == "enter" &&
        $_GET[op2] == "warchild2" &&
        ($session[user][gold] >= $cost_high2 ||
        $session[user][gems] >= 2))
        {
                // subtract costs
                $session[user][gold] = $session[user][gold] - $cost_high2;
                $session[user][gems] = $session[user][gems] - 2;
                $goldpaid = $cost_high2;

                // war heute schonmal hier...
                $session[user][seenAcademy] = 1;
                $session[user][turns]--;

                //debuglog("paid $goldpaid and 2 gems to the academy");
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                if ($session['user']['drunkenness'] > 0) // too drunk to learn
                {
                        output("`^Als Warchild Deine Fahne riecht schaut er Dich angewidert an.`n");
                        output("`7`i\"Betrunkene Kreatur! Von mir wirst Du nichts lernen!\"`i`^`n");
                        output("Er wirft Dich hinaus und Dein Geld und Deine Edelsteine hinter Dir her.`n");
                        output("Bemüht, die kullernden Edelsteine aufzusammeln, kannst Du am Ende einige Münzen nicht mehr finden.`n`n");
                        output("`5Du verlierst etwas Gold des Lehrgelds!");
                        $session[user][gold] +=  $cost_high2 * 0.67;
                        $session[user][gems] = $session[user][gems] + 2;
                }
                else // hier geht das Train los
                {
                        output("`7Du verbringst einige Zeit im schwarzen Turm der Akademie in der höchsten Kammer");
                        output("und `4Warchild`7 eröffnet Dir eine neue Dimension Deiner Fähigkeiten.`n");
                        output("Du verlässt den Ort zufrieden und wissender als zuvor!`n`n");
                        increment_specialty();
                }
                addnav("Zurück ins Dorf","village.php");
        }
        //ANGEL MOD ENDE
        
        // check if User has enough gems/gold if he wants to learn
        // 1st Case: STUDY
        if ($_GET[op1] == "enter" &&
        $_GET[op2] == "study" &&
        $session[user][gold] < $cost_low)
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`n`$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");
                addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
        }
        else if ($_GET[op1] == "enter" &&
        $_GET[op2] == "study" &&
        $session[user][gold] >= $cost_low)
        {
                // subtract costs
                $session[user][gold] = $session[user][gold] - $cost_low;
                $goldpaid = $cost_low;
                //debuglog("paid $goldpaid to the academy");

                // war heute schonmal hier...
                $session[user][seenAcademy] = 1;
                $session[user][turns]--;

                if ($session['user']['drunkenness'] > 0) // too drunk to learn
                {
                        output("`$`b`c Bibliothek der Akademie`c`b`n`n");
                        output("`^Ver*hic*dammt! Du hättest Dich mit dem...`$ ale`^... zurückhalten sollen! Du kannst Dich einfach");
                        output("nicht genug konzentrieren um irgendetwas zu lernen.`n");
                        output("Frustriert verlässt Du die Akademie nach einiger Zeit und stapfst ins Dorf zurück.");
                        addnav("Zurück ins Dorf","village.php");
                }
                else // hier geht das Train los
                {
                        output("`$`b`c Bibliothek der Akademie`c`b`n`n");
                        $rand = e_rand(1,3);
                        switch ($rand)
                        {
                                case 1:
                                output("`^Du sitzt in der Bibliothek mit dem Buch in der Hand, als es plötzlich");
                                output("nach Dir schnappt und Dir in die Hand `4beisst! `6Der Schmerz ist furchtbar!`^`n");
                                output("Du versuchst verzweifelt das Buch wieder abzuschütteln während einige andere ");
                                output("Studenten einen kleinen Kreis um Dich bilden und sich schlapplachen.`n");
                                output("Frustiert und fluchend verlässt Du die Akademie.`n`n");
                                output("`5Du verlierst einige Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints] - $session[user][hitpoints] * 0.2;
                                break;
                                case 2:
                                output("`^Du verbringst einige Zeit in der Akademie und liest intensiv, doch schon bald ergeben");
                                output("die Wörter irgendwie keinen Sinn mehr. Schliesslich gibst Du auf.`n");
                                output("Frustiert verlässt Du die Akademie.");
                                break;
                                case 3:
                                output("`7Du nimmst Dir einen grossen, ledergebundenen Folianten und öffnest ihn.");
                                output("Zunächst geschieht nichts, doch plötzlich `2redet das Buch mit Dir!`7`n");
                                output("Fasziniert lauschst Du den geheimen Worten und lernst wirklich etwas über");
                                if ($session[user][specialty] == 1)
                                output(" `iDunkle Künste`i");
                                else if ($session[user][specialty] == 2)
                                output(" `iMystische Kräfte`i");
                                else
                                output(" `iDiebeskunst`i");
                                output(". Breit grinsend und stolz auf Dein neues Wissen verlässt Du die Akademie.`n`n");
                                increment_specialty();
                                break;
                        }
                        addnav("Zurück ins Dorf","village.php");
                }
        }
        
        // 2nd Case: PRACTICE
        if ($_GET[op1] == "enter" &&
        $_GET[op2] == "practice" &&
        ($session[user][gold] < $cost_medium ||
        $session[user][gems] < 1
        ))
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`n`$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");
                addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
        }
        else if ($_GET[op1] == "enter" &&
        $_GET[op2] == "practice" &&
        ($session[user][gold] >= $cost_medium ||
        $session[user][gems] >= 1))
        {
                // subtract costs
                $session[user][gold] = $session[user][gold] - $cost_medium;
                $session[user][gems]--;
                $goldpaid = $cost_medium;
                //debuglog("paid $goldpaid and 1 gem to the academy");

                // war heute schonmal hier...
                $session[user][seenAcademy] = 1;
                $session[user][turns]--;

                if ($session['user']['drunkenness'] > 0) // too drunk to learn
                {
                        output("`$`b`c Das Innere der Akademie`c`b`n`n");
                        if ($session[user][specialty] == 1)
                        {
                                output("`^Du betrittst den `7Tierkäfig`^!`n");
                                output("Ein niedlich aussehendes, weisses Kaninchen sitzt in der Mitte des Käfigs und glotzt");
                                output("Dich an. Du holst zum Schlag aus, doch auf einmal springt es auf Dich zu und");
                                output("`$ gräbt seine Zähne in Deine Hand!`^ Glücklicherweise bist Du noch zu betrunken um den Schmerz zu fühlen...`n");
                                output("aber dafür wird Deine Hand morgen höllisch weh tun!`n");
                                output("Mit einer bandagierten Hand verlässt Du den Ort.`n`n");
                                output("`5Du verlierst ein paar Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints] - $session[user][hitpoints] * 0.2;
                        }
                        else if ($session[user][specialty] == 2)
                        {
                                output("`^Du betrittst die `7Magiekammer`^!`n");
                                output("Ein Golem marschiert auf Dich zu, doch Deine Sicht ist vom Alkohol noch so verschwommen, dass Dein Spruch ihn verfehlt!`n");
                                output("Statt dessen trifft er Dich mit einer grossen Keule und Du verlierst das Bewusstsein.`n");
                                output("Nach ein paar Minuten wachst Du vor der Akademie mit fiesen Kopfschmerzen wieder auf und torkelst zurück in die Stadt.`n`n");
                                output("`5Du verlierst ein paar Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints] - $session[user][hitpoints] * 0.2;
                        }
                        else
                        {
                                output("`^Du betrittst das `7Labyrinth der Fallen`^!`n");
                                output("Während Du, immer langsam an der Wand lang wegen des Alkohols, Dich in Richtung des Eingangs bewegst (oh Mann Du bist betrunken!), kann Warchild ein grausames Lächeln nicht unterdrücken.`n");
                                output("Um es kurz zu machen: Du wirst dreimal von einer vergifteten Nadel gestochen, schneidest Dich zweimal an einem");
                                output(" versteckten Draht und einmal übersiehst Du die grosse Falltür, durch die man direkt in den Müllkübel fällt,");
                                output(" der vor der Akademie steht.`n");
                                output("Halbtot sammelst Du die Reste von Dir wieder zusammen und wankst zurück ins Dorf.`n`n");
                                output("`5Du verlierst einige Menge Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints]  * 0.1;
                        }
                        addnav("Zurück ins Dorf","village.php");
                }
                else // hier geht das Train los
                {
                        output("`$`b`c Bibliothek der Akademie`c`b`n`n");
                        $rand = e_rand(1,3);
                        switch ($rand)
                        {
                                case 1:
                                output("`^Du verlässt den Trainingsbereich geschlagen und mit einigen blutenden Wunden.`n");
                                output("Gesenkten Hauptes gehst Du ins Dorf zurück.`n`n");
                                output("`5Du verlierst ein paar Lebenspunkte!");
                                $session[user][hitpoints] = $session[user][hitpoints]  * 0.9;
                                break;
                                case 2:
                                case 3:
                                output("`7Nach einer forderndern Trainingsstunde, die Du souverän meisterst, machst Du Dich auf den Heimweg.`n");
                                output("Bevor Du gehst, gratuliert Dir Warchild zu dem erfolgreichen Training.`n`n");
                                increment_specialty();
                                break;
                        }
                        addnav("Zurück ins Dorf","village.php");
                }
        }
        
        // 3rd Case: WARCHILD
        if ($_GET[op1] == "enter" &&
        $_GET[op2] == "warchild" &&
        ($session[user][gold] < $cost_high ||
        $session[user][gems] < 2))
        {
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                output("`n`$ Leider kannst Du den geforderten Preis nicht bezahlen.`^");
                addnav("Nochmal nachschauen","academy.php?op1=enter&op2=0");
        }
        else if ($_GET[op1] == "enter" &&
        $_GET[op2] == "warchild" &&
        ($session[user][gold] >= $cost_high ||
        $session[user][gems] >= 2))
        {
                // subtract costs
                $session[user][gold] = $session[user][gold] - $cost_high;
                $session[user][gems] = $session[user][gems] - 2;
                $goldpaid = $cost_high;

                // war heute schonmal hier...
                $session[user][seenAcademy] = 1;
                $session[user][turns]--;
                
                //debuglog("paid $goldpaid and 2 gems to the academy");                
                output("`$`b`c Das Innere der Akademie`c`b`n`n");
                if ($session['user']['drunkenness'] > 0) // too drunk to learn
                {
                        output("`^Als Warchild Deine Fahne riecht schaut er Dich angewidert an.`n");
                        output("`7`i\"Betrunkene Kreatur! Von mir wirst Du nichts lernen!\"`i`^`n");
                        output("Er wirft Dich hinaus und Dein Geld und Deine Edelsteine hinter Dir her.`n");
                        output("Bemüht, die kullernden Edelsteine aufzusammeln, kannst Du am Ende einige Münzen nicht mehr finden.`n`n");
                        output("`5Du verlierst etwas Gold des Lehrgelds!");
                        $session[user][gold] +=  $cost_high * 0.67;
                        $session[user][gems] = $session[user][gems] + 2;
                }
                else // hier geht das Train los
                {
                        output("`7Du verbringst einige Zeit im schwarzen Turm der Akademie in der höchsten Kammer");
                        output("und `4Warchild`7 eröffnet Dir eine neue Dimension Deiner Fähigkeiten.`n");
                        output("Du verlässt den Ort zufrieden und wissender als zuvor!`n`n");
                        increment_specialty();
                }
                addnav("Zurück ins Dorf","village.php");
        }
}
// auf jeden Fall Begrüßung und Einleitung wenn keine Params gesetzt
else
{
        output("`$`b`c Warchilds Akademie der geheimen Künste`c`b`n`n");
        output("`^Vorsichtig näherst Du Dich dem riesigen Tor der Akademie und verharrst einen Augenblick,");
        output("um die Inschrift über dem Torbogen zu betrachten.`n");
        output(" \"`8`iAuch diese Worte werden vergehen`i`^\" steht dort für die Ewigkeit in geschwungenen goldenen Lettern.`n");
        output("Das zweiflügelige dunkelgraue Gemäuer mit vergitterten Fenstern und dem drohend in den Himmel ragenden");
        output("schwarzen Turm scheint die Worte über Deinem Kopf noch zu unterstreichen.");
        output("Ein kleines Schild neben dem Eingang warnt vor den üblen Konsequenzen von Magie und Alkohol.`n");

        // Heute schonmal hier gewesen? Dann wird's wohl nix :P
        if ($session[user][seenAcademy] == 1 || $session[user][turns]<1)
        {
                output ("`n`7Du verspürst irgendwie kein sonderlich grosses Bedürfnis, heute noch einmal die Schulbank zu drücken, ");
                output("also schlenderst Du zum Dorf zurück.");
                addnav("Zurück ins Dorf","village.php");
        }
        else        // User darf heute noch hier rein
                // Wenn User genug Dragonkills hat, Zutritt erlauben
                if ($session['user']['dragonkills']> $min_dk)
                {
                        output("`^Warchild steht in der Nähe des Eingangs zur Akademie und wartet, bis Du den Hof überquert hast, um Dich anzureden.`n");
                        output("\"`9Ich hörte bereits von Deinen grossen Taten. Tritt doch ein...`^\" sagt er und lächelt dünn.`n");
                        output("Dann winkt er Dich herein.`n`n");
                        addnav("Eintreten","academy.php?op1=enter&op2=0");
                        addnav("Zurück ins Dorf","village.php");
                }
                // wenn User nicht ausreichend Dragonkills hat, Zutritt ablehnen
                else
                {
                        output("In dem ausladenden Innenhof steht ein Mann in einem schwarzen Mantel, der leicht im Wind flattert. Er starrt Dich so eindringlich an, dass es Dir unerträglich wird, ihn weiter anzusehen.");
                        output("Als Du den Blick senkst flattert eine einzelne Krähe vom Dachfirst herunter und landet zwischen");
                        output("den Füssen des Mannes, wo sie einige Blumensamen aufpickt, die dort hingeweht wurden.`n");
                        output("\"`9Komm wieder, wenn Du bereit dazu bist,`^\" sagt Warchild ruhig zu Dir.`n");
                        output("Eingeschüchtert schleichst Du zurück ins Dorf.`n`n");
                        addnav("Zurück ins Dorf","village.php");
                }
}

page_footer();
?> 