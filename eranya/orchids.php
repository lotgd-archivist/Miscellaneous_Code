
<?php
/*
*   _____________________________________________________________
*  |
*  | RP-Ort für den Garten: Orchideenwiese
*  | Zusatz: Wellness-Einrichtung, Rossos Ställe (extern)
*  | Autor: Silva
*  | Texte: Silva, Sefira
*  | Erstellt für Eranya (http://eranya.de/)
*  |_____________________________________________________________
*
*/
require_once 'common.php';
// Konstanten für Farbcodes festlegen
define('ORCHIDSCOLORTEXT','`}');
define('ORCHIDSCOLORBOLD','`[');
define('O_SPACOLORTEXT','`=');
define('O_SPACOLORTALK','`&');
define('O_SPACOLORAZUBI','`q');
define('O_SPACOLORGESELLE','`w');
define('O_SPACOLORMEISTER','`G');
define('O_LOUNGECOLORTEXT','`ò');
define('O_LOUNGECOLORBOLD','`Q');
define('O_GEYSERCOLORTEXT','`P');
define('O_GEYSERCOLORBOLD','`h');
define('O_GEYSERCOLORSIGN','`F');
define('O_STONEGARDENCOLORTEXT','`r');
define('O_STONEGARDENCOLORBOLD','`s');
// Sonstige Einstellungen
$goldcosts = 2000;
$gemscosts = 2;
$spa_name = '`jH`+a`pu`(s `hd`Fe`qr `dF`qü`Fn`hf `(S`pin`+n`je';
$spa_owner = '`jL`+i`pn M`(e`hi';
// Das Übliche (;
checkday();
addcommentary();
page_header('Orchideenwiese');
// Orte
$filename = basename(__FILE__);
switch($_GET['op'])
{
        // Orchideenwiese
        case '':
                $out = ORCHIDSCOLORBOLD."`c`bDie Orchideenwiese`b`c`n".
                       ORCHIDSCOLORTEXT."Es ist eine wahre Blumenpracht, die dich empfängt, als du dich der Wiese
                       näherst. Orchideen in allen Formen und Varianten verwandeln das Fleckchen Erde in ein Meer aus
                       tausend Farben, dessen Oberfläche je nach Windstärke sogar leichte Wellen schlägt. Gleichzeitig
                       steigt dir ein dezenter Blumenduft in die Nase, süßlich und erdig zugleich, der dich auf angenehme
                       Weise in Beschlag nimmt.`n".
                       ORCHIDSCOLORTEXT."Auch wenn es hier keine wirklichen Wege gibt, kannst du doch genau erkennen,
                       dass bestimmte Pfade schon mehrfach begangen worden sind. Einer dieser Pfade macht dich auf ein
                       großes Gebäude mit Koppeln aufmerksam (wohl ein Stall), ein anderer führt um einen kleinen
                       Hügel herum, hinter dem das Dach eines weiteren Hauses hervorlugt. Der letzte Pfad
                       schließlich läuft direkt auf den nahen Waldrand zu und verschwindet zwischen dem dortigen
                       Buschwerk.`n`n";
                $commentary_section = 'orchids';
                addnav('Entdecke diesen Ort');
                //addnav('Q?Zu den heißen Quellen',$filename.'?op=geyser'); #in Küstenregion ziemlich sinnfrei
                //addnav('S?Zum Steingarten',$filename.'?op=stonegarden');
                addnav('R?Zu Rossos Ställen','orchids_stables.php');
                addnav('H?Zum Haus der Fünf Sinne',$filename.'?op=spa');
                /*addnav('Privater Ort');
                addnav('Die heiße Quelle','privplaces.php?rport=spring');*/    
                addnav('Zurück');
                addnav('G?Zurück in den Garten','gardens.php');
        break;
        // Geysir
        case 'geyser':
                $out = O_GEYSERCOLORBOLD."`c`bDie heißen Quellen`b`c`n".
                       O_GEYSERCOLORTEXT."Je weiter du dem Pfad folgst, umso wärmer und feuchter wird die Luft, die dich
                       umgibt. Bald schon weichen die hohen Bäume ganz zurück und geben den Platz frei für Farngewächse
                       aller Art, die ihre fächergleichen Blätter unterschiedlich hoch in den Himmel strecken. Wie ein
                       dichtes Buschwerk umgeben sie dich und machen es geradezu unmöglich, mehr als ein paar Meter
                       voraus zu schauen`n".
                       O_GEYSERCOLORTEXT."Schließlich jedoch, als du ein besonders großes Farnblatt zur Seite drückst,
                       lichtet sich plötzlich der Farnwald und gibt die Sicht frei auf eine Ansammlung von vier
                       natürlichen Becken, allesamt bis zum Rand gefüllt mit heißem, weiß gefärbtem Wasser. Dicke
                       Dampfschwaden wabern über der Wasseroberflächen und machen es fast unmöglich, durch sie hindurch
                       zu sehen. Ein schmaler Pfad verbindet die drei Becken miteinander, ansonsten ist der Boden bedeckt
                       mit weichem Moos oder kleineren Farnen.`n".
                       O_GEYSERCOLORTEXT."Am größten der Becken entdeckst du auf dem zweiten Blick ein Schild, das in die
                       Erde gepflockt worden ist. Darauf steht geschrieben: \"".O_GEYSERCOLORSIGN."Achtung! Regelmäßige
                       Eruptionen in Form eines Geysirs!".O_GEYSERCOLORTEXT."\" Du schließt daraus, dass sich dieses
                       Becken wohl nicht zum Baden eignet - doch bleiben dir ja noch zwei weitere, die geradewegs dazu
                       einladen, sich ein wenig im heißen Wasser zu entspannen.`n`n";
                $commentary_section = 'orchids_geyser';
                addnav('Zurück');
                addnav('O?Zurück zur Orchideenwiese',$filename);
                addnav('S?Zurück in die Stadt','village.php');
        break;
        // Steingarten
        case 'stonegarden':
                $out = O_STONEGARDENCOLORBOLD."`c`bDer Steingarten`b`c`n".
                       O_STONEGARDENCOLORTEXT."Gedankenversunken schlenderst du einen etwas versteckten Pfad entlang.
                       Der Boden besteht nur aus lockerer Erde, doch selbst bei Regen würde es hier trocken sein, denn
                       die Bäume und Büsche umranken den Weg vollständig, sodass du beinahe das Gefühl hast, durch einen
                       dunkelgrünen Tunnel zu wandern.`n".
                       O_STONEGARDENCOLORTEXT."Schließlich lichtet sich das dichte Gestrüpp und du musst dir mehrfach die Augen reiben, so
                       überraschend ist die Aussicht, die sich dir bietet. Niedrige hellgraue Steinmauern umrahmen
                       Beete, die gefüllt sind mit dunkler, frisch riechender Erde. Rosensträucher mit wohlriechenden
                       Blüten in allen Farben, von dem zartesten rosa bis dunkelblau, bedecken die Erde und ranken sich
                       schlängelnd um die Stämme kleinerer Bäume. Kreisförmig ist dieser Steingarten angelegt und
                       bietet sicher einen wunderbaren Ort für stille Stunden.`n`n";
                $commentary_section = 'orchids_stonegarden';
                addnav('Zurück');
                addnav('O?Zurück zur Orchideenwiese',$filename);
                addnav('S?Zurück in die Stadt','village.php');
        break;
        // Haus der fünf Sinne
        case 'spa':
                if ($_GET['act'] == 'massage')
                {
                        switch($_GET['what'])
                        {
                                // Massagenauswahl
                                case '':
                                        if ($session['user']['turns'] <= 0)
                                        {
                                                $out = O_SPACOLORTEXT."Jetzt noch eine Massage?! Nein, also dafür bist du nun wirklich
                                                       schon zu müde - und morgen ist schließlich auch noch ein Tag.";
                                        }
                                        else
                                        {
                                                $out = "{$spa_owner} ".O_SPACOLORTEXT."nickt lächelnd und legt dir eine kleine Karte vor, auf der vier
                                                        verschiedene Massagentypen aufgelistet sind. \"".O_SPACOLORTALK."Dies sind die Massagen, die ich
                                                        Euch anbieten kann. Welche darf es sein?".O_SPACOLORTEXT."\"";
                                                addnav('Massagen');
                                                addnav('Klassische Massage ('.$goldcosts.' Gold)',$filename.'?op=spa&act=massage&what=m_one&gold='.$goldcosts);
                                                addnav('Akupunktur ('.($goldcosts*2).' Gold)',$filename.'?op=spa&act=massage&what=m_two&gold='.($goldcosts*2));
                                                addnav('Thai-Massage ('.($goldcosts*3).' Gold)',$filename.'?op=spa&act=massage&what=m_three&gold='.($goldcosts*3));
                                                addnav('Tantra-Massage ('.($goldcosts*4).' Gold und '.$gemscosts.' Edelsteine)',$filename.'?op=spa&act=massage&what=m_four&gold='.($goldcosts*4));
                                        }
                                break;
                                // Klassische Massage
                                case 'm_one':
                                        if ($session['user']['gold'] < $_GET['gold'])
                                        {
                                                $out = O_SPACOLORTEXT."Mit einem schon fast traurigen Lächeln deutet {$spa_owner} ".O_SPACOLORTEXT."auf deinen Goldbeutel
                                                       und sagt: \"".O_SPACOLORTALK."Ich fürchte, dass das, was Ihr bei Euch tragt, nicht ausreicht, um
                                                       meine Masseure bezahlen zu können.".O_SPACOLORTEXT."\" Beschämt stellst du fest, dass sie Recht hat.";
                                        }
                                        elseif ($session['user']['hitpoints'] == $session['user']['maxhitpoints'])
                                        {
                                                $out = "{$spa_owner} ".O_SPACOLORTEXT."betrachtet dich einen Moment abschätzend, ehe sie sacht den Kopf
                                                        schüttelt. \"".O_SPACOLORTALK."Ihr seht nicht so aus, als würdet Ihr die Massage wirklich
                                                        brauchen. Wollt Ihr nicht lieber eine andere Wahl treffen?".O_SPACOLORTEXT."\"";
                                        }
                                        else
                                        {
                                                $session['user']['gold'] -= $_GET['gold'];
                                                $out = O_SPACOLORTEXT."Mit einem sachten Nicken deutet {$spa_owner} ".O_SPACOLORTEXT."an, dass sie dich verstanden hat:
                                                       \"".O_SPACOLORTALK."Eine Massage im klassischen Stil also - eine gute Wahl.".O_SPACOLORTEXT."\" Dann führt sie
                                                       dich in einen der vielen hellen Zimmer, bittet dich, es dir auf der Liege in der Mitte des Raums
                                                       gemütlich zu machen, ehe sie dich allein lässt und zum Empfang zurückkehrt. Du nutzt die
                                                       Zeit und betrachtest eine Weile die vielen, in harmonischen Farben gehaltenen Bilder an
                                                       den Wänden, ehe du dich auf die Liege legst.`n
                                                       `n
                                                       Lange brauchst du nicht zu warten, da kommt auch schon ";
                                                switch(e_rand(1,6))
                                                {
                                                        // Azubi
                                                        case 1:
                                                        case 2:
                                                        case 3:
                                                                $out .= O_SPACOLORTEXT."ein schmächtiges Kerlchen in den Raum und begrüßt dich mit den
                                                                        Worten: \"".O_SPACOLORAZUBI."Hallo! Ich bin noch recht neu hier, aber seid
                                                                        unbesorgt - ich werde mir die größte Mühe geben.".O_SPACOLORTEXT."\"`n
                                                                        Ohje, du hast den Lehrling zugeteilt bekommen. Dir schwant nichts Gutes - und
                                                                        tatsächlich: Anstatt dir die verdiente Erholung zu bringen, scheinen die ungeübten
                                                                        Finger des Jünglings deine Leiden fast noch zu verschlimmern.
                                                                        Schließlich wird es dir zu bunt: Mit einem Ruck richtest du dich wieder auf, stößt
                                                                        den Jungen beiseite und verlässt den Raum. Genug ist genug!";
                                                                $session['user']['turns']--;
                                                        break;
                                                        // Geselle
                                                        case 4:
                                                        case 5:
                                                                $out .= O_SPACOLORTEXT."ein stattlicher, junger Mann in den Raum, verneigt sich lächelnd
                                                                       und sagt: \"".O_SPACOLORGESELLE."Seid gegrüßt. Ihr wünscht eine klassische Massage? Dann
                                                                       entspannt Euch nun und vertraut auf meine geübten Hände.".O_SPACOLORTEXT."\"`n
                                                                       Du nickst zustimmend und begibst dich vertrauensvoll in die kreisenden Hände des Gesellen.
                                                                       Schon nach kurzer Zeit spürst du, wie sich dein Körper merklich entspannt und alle
                                                                       in der letzten Zeit durchlebten Strapazen ablegt. Du fühlst dich merklich besser.`n
                                                                       `n
                                                                       `^Deine Lebenspunkte wurden vollständig aufgefüllt.";
                                                                $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                                                                $session['user']['turns']--;
                                                        break;
                                                        // Meister
                                                        case 6:
                                                                $out .= O_SPACOLORTEXT."ein älterer Herr mit bereits gräulich schimmerndem Haar. Er nickt dir
                                                                       zur Begrüßung zu, tritt dann an die Liege heran und beginnt mit der Massage.`n
                                                                       Du brauchst keine 10 Sekunden, um festzustellen, dass hier gerade ein wahrer Meister
                                                                       am Werk ist. Kaum angerührt, fangen all deine Muskeln im Körper an, sich zu entspannen
                                                                       und dein Körper neue Kraft zu schöpfen. Mit einem Seufzer gibst du dich der Massage
                                                                       hin - und bist schon fast versucht zu protestieren, als der Masseur einige Zeit
                                                                       später seine Hände wieder von deinem Rücken löst.`n
                                                                       `n".
                                                                       O_SPACOLORMEISTER."Deine Lebenspunkte wurden vollständig aufgefüllt.`n
                                                                       Du hast heute zwei Runden mehr.";
                                                                $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                                                                $session['user']['turns'] += 2;
                                                        break;
                                                }
                                        }
                                break;
                                // Akupunktur
                                case 'm_two':
                                        if ($session['user']['gold'] < $_GET['gold'])
                                        {
                                                $out = O_SPACOLORTEXT."Mit einem schon fast traurigen Lächeln deutet {$spa_owner} ".O_SPACOLORTEXT."auf deinen Goldbeutel
                                                       und sagt: \"".O_SPACOLORTALK."Ich fürchte, dass das, was Ihr bei Euch tragt, nicht ausreicht, um
                                                       meine Masseure bezahlen zu können.".O_SPACOLORTEXT."\" Beschämt stellst du fest, dass sie Recht hat.";
                                        }
                                        else
                                        {
                                                $session['user']['gold'] -= $_GET['gold'];
                                                $out = O_SPACOLORTEXT."Mit einem sachten Nicken deutet {$spa_owner} ".O_SPACOLORTEXT."an, dass sie dich verstanden hat:
                                                       \"".O_SPACOLORTALK."Eine Akkupunktur-Behandlung also - eine gute Wahl.".O_SPACOLORTEXT."\" Dann führt sie
                                                       dich in einen der vielen hellen Zimmer, bittet dich, es dir auf der Liege in der Mitte des Raums
                                                       gemütlich zu machen, ehe sie dich allein lässt und zum Empfang zurückkehrt. Du nutzt die
                                                       Zeit und betrachtest eine Weile die vielen, in harmonischen Farben gehaltenen Bilder an
                                                       den Wänden, ehe du dich auf die Liege legst.`n
                                                       `n
                                                       Lange brauchst du nicht zu warten, da kommt auch schon ";
                                                switch(e_rand(1,6))
                                                {
                                                        // Azubi
                                                        case 1:
                                                        case 2:
                                                        case 3:
                                                                $out .= O_SPACOLORTEXT."ein schmächtiges Kerlchen in den Raum und begrüßt dich mit den
                                                                        Worten: \"".O_SPACOLORAZUBI."Hallo! Ich bin noch recht neu hier, aber seid
                                                                        unbesorgt - ich werde mir die größte Mühe geben.".O_SPACOLORTEXT."\"`n
                                                                        Ohje, du hast den Lehrling zugeteilt bekommen. Dir schwant nichts Gutes - und
                                                                        tatsächlich: Anstatt dir die verdiente Entspannung zu bringen, fügt dir der Jüngling
                                                                        mit jeder neuen Nadel nur noch mehr Schmerzen zu.
                                                                        Schließlich wird es dir zu bunt: Mit einem Ruck richtest du dich wieder auf, stößt
                                                                        den Jungen beiseite und verlässt den Raum. Genug ist genug!";
                                                        break;
                                                        // Geselle
                                                        case 4:
                                                        case 5:
                                                                $out .= O_SPACOLORTEXT."ein stattlicher, junger Mann in den Raum, verneigt sich lächelnd
                                                                       und sagt: \"".O_SPACOLORGESELLE."Seid gegrüßt. Ihr wünscht eine Akupunktur? Dann
                                                                       entspannt Euch nun und vertraut auf meine geübten Hände.".O_SPACOLORTEXT."\"`n
                                                                       Du nickst zustimmend und lässt dir vertrauensvoll vom Gesellen stetig neue Nadeln an verschiedene
                                                                       Stellen deines Körpers setzen. Schon nach kurzer Zeit spürst du, wie sich dein Körper
                                                                       merklich entspannt und alle in der letzten Zeit durchlebten Strapazen ablegt. Auch fühlst
                                                                       du, wie er plötzlich stärker und widerstandsfähiger wird - als könne ihn nun so schnell
                                                                       nichts mehr umhauen.`n
                                                                       `n
                                                                       `^Du erhältst einen permanenten Lebenspunkt.";
                                                                $session['user']['maxhitpoints']++;
                                                        break;
                                                        // Meister
                                                        case 6:
                                                                $out .= O_SPACOLORTEXT."ein älterer Herr mit bereits gräulich schimmerndem Haar. Er nickt dir
                                                                       zur Begrüßung zu, tritt dann an die Liege heran und beginnt mit der Massage.`n
                                                                       Du brauchst keine 10 Sekunden, um festzustellen, dass hier gerade ein wahrer Meister
                                                                       am Werk ist. Kaum ist die erste Nadel gesetzt, fangen all deine Muskeln im Körper an,
                                                                       sich zu entspannen und von dir ungeahnte Kräfte freizusetzen. Mit einem Seufzer gibst
                                                                       du dich der Massage hin - und bist schon fast versucht zu protestieren, als der
                                                                       Masseur einige Zeit später seine Hände wieder von dir löst.`n
                                                                       `n".
                                                                       O_SPACOLORMEISTER."Du erhältst zwei permanente Lebenspunkte.";
                                                                $session['user']['maxhitpoints'] += 2;
                                                        break;
                                                }
                                                $session['user']['turns']--;
                                        }
                                break;
                                // Thai
                                case 'm_three':
                                        if ($session['user']['gold'] < $_GET['gold'])
                                        {
                                                $out = O_SPACOLORTEXT."Mit einem schon fast traurigen Lächeln deutet {$spa_owner} ".O_SPACOLORTEXT."auf deinen Goldbeutel
                                                       und sagt: \"".O_SPACOLORTALK."Ich fürchte, dass das, was Ihr bei Euch tragt, nicht ausreicht, um
                                                       meine Masseure bezahlen zu können.".O_SPACOLORTEXT."\" Beschämt stellst du fest, dass sie Recht hat.";
                                        }
                                        else
                                        {
                                                $session['user']['gold'] -= $_GET['gold'];
                                                $out = O_SPACOLORTEXT."Mit einem sachten Nicken deutet {$spa_owner} ".O_SPACOLORTEXT."an, dass sie dich verstanden hat:
                                                       \"".O_SPACOLORTALK."Die Thai-Massage also - eine gute Wahl.".O_SPACOLORTEXT."\" Dann führt sie
                                                       dich in einen der vielen hellen Zimmer, bittet dich, es dir auf der Liege in der Mitte des Raums
                                                       gemütlich zu machen, ehe sie dich allein lässt und zum Empfang zurückkehrt. Du nutzt die
                                                       Zeit und betrachtest eine Weile die vielen, in harmonischen Farben gehaltenen Bilder an
                                                       den Wänden, ehe du dich auf die Liege legst.`n
                                                       `n
                                                       Lange brauchst du nicht zu warten, da kommt auch schon ";
                                                switch(e_rand(1,6))
                                                {
                                                        // Azubi
                                                        case 1:
                                                        case 2:
                                                        case 3:
                                                                $out .= O_SPACOLORTEXT."ein schmächtiges Kerlchen in den Raum und begrüßt dich mit den
                                                                        Worten: \"".O_SPACOLORAZUBI."Hallo! Ich bin noch recht neu hier, aber seid
                                                                        unbesorgt - ich werde mir die größte Mühe geben.".O_SPACOLORTEXT."\"`n
                                                                        Ohje, du hast den Lehrling zugeteilt bekommen. Dir schwant nichts Gutes - und
                                                                        tatsächlich: Anstatt dir die verdiente Entspannung zu bringen, fordern die
                                                                        ungeübten Finger des Jünglings lediglich deine Geduld und Schmerzensgrenze heraus.
                                                                        Schließlich wird es dir zu bunt: Mit einem Ruck richtest du dich wieder auf, stößt
                                                                        den Jungen beiseite und verlässt den Raum. Genug ist genug!";
                                                        break;
                                                        // Geselle
                                                        case 4:
                                                        case 5:
                                                               $out .= O_SPACOLORTEXT."ein stattlicher, junger Mann in den Raum, verneigt sich lächelnd
                                                                       und sagt: \"".O_SPACOLORGESELLE."Seid gegrüßt. Ihr wünscht eine Thai-Massage? Dann
                                                                       entspannt Euch nun und vertraut auf meine geübten Hände.".O_SPACOLORTEXT."\"`n
                                                                       Du nickst zustimmend und begibst dich vertrauensvoll in die kreisenden Hände des Gesellen.
                                                                       Schon nach kurzer Zeit spürst du, wie sich dein Körper merklich entspannt und alle
                                                                       in der letzten Zeit durchlebten Strapazen ablegt. Augenblicklich fühlst du dich
                                                                       um 10 Jahre jünger - ein großartiges Gefühl.`n
                                                                       `n
                                                                       `^Dein Charme steigt an.";
                                                               $session['user']['charm']++;
                                                        break;
                                                        // Meister
                                                        case 6:
                                                               $out .= O_SPACOLORTEXT."ein älterer Herr mit bereits gräulich schimmerndem Haar. Er nickt dir
                                                                       zur Begrüßung zu, tritt dann an die Liege heran und beginnt mit der Massage.`n
                                                                       Du brauchst keine 10 Sekunden, um festzustellen, dass hier gerade ein wahrer Meister
                                                                       am Werk ist. Kaum angerührt, fangen all deine Muskeln im Körper an, sich zu entspannen
                                                                       und dein Körper zu regenerieren. Mit einem Seufzer gibst du dich der Massage hin - und
                                                                       bist schon fast versucht zu protestieren, als der Masseur einige Zeit später seine
                                                                       Hände wieder von deinem Rücken löst.`n
                                                                       `n".
                                                                       O_SPACOLORMEISTER."Dein Charme steigt deutlich an.";
                                                               $session['user']['charm'] += 2;
                                                        break;
                                                }
                                                $session['user']['turns']--;
                                        }
                                break;
                                // Tantra-Massage
                                case 'm_four':
                                        if ($session['user']['gold'] < $_GET['gold']
                                            || $session['user']['gems'] < $gemscosts)
                                        {
                                                $out = O_SPACOLORTEXT."Mit einem schon fast traurigen Lächeln deutet {$spa_owner} ".O_SPACOLORTEXT."auf deinen Goldbeutel
                                                       und sagt: \"".O_SPACOLORTALK."Ich fürchte, dass das, was Ihr bei Euch tragt, nicht ausreicht, um
                                                       meine Masseure bezahlen zu können.".O_SPACOLORTEXT."\" Beschämt stellst du fest, dass sie Recht hat.";
                                        }
                                        else
                                        {
                                                $session['user']['gold'] -= $_GET['gold'];
                                                $session['user']['gems'] -= $gemscosts;
                                                $out = O_SPACOLORTEXT."Mit einem sachten Nicken und einem wissenden Lächeln deutet {$spa_owner} ".O_SPACOLORTEXT."an, dass
                                                       sie dich verstanden hat: \"".O_SPACOLORTALK."Eine Tantra-Massage? Wie Ihr wünscht.".O_SPACOLORTEXT."\" Dann führt sie
                                                       dich in einen der vielen hellen Zimmer, bittet dich, es dir auf der Liege in der Mitte des Raums
                                                       gemütlich zu machen, ehe sie dich allein lässt und zum Empfang zurückkehrt. Du nutzt die
                                                       Zeit und betrachtest eine Weile die vielen, in harmonischen Farben gehaltenen Bilder an
                                                       den Wänden, ehe du dich auf die Liege legst.`n
                                                       `n
                                                       Lange brauchst du nicht zu warten, da kommt auch schon ";
                                                switch(e_rand(1,6))
                                                {
                                                        // Azubi
                                                        case 1:
                                                        case 2:
                                                        case 3:
                                                                $out .= O_SPACOLORTEXT."ein schmächtiges Kerlchen in den Raum und begrüßt dich mit den
                                                                        Worten: \"".O_SPACOLORAZUBI."Hallo! Ich bin noch recht neu hier, aber seid
                                                                        unbesorgt - ich werde mir die größte Mühe geben.".O_SPACOLORTEXT."\"`n
                                                                        Ohje, du hast den Lehrling zugeteilt bekommen. Dir schwant nichts Gutes - und
                                                                        tatsächlich: Anstatt dir die verdiente Entspannung zu bringen, sorgen die
                                                                        tollpatschigen Hände des Jünglings nur dafür, dass du mit jeder weiteren
                                                                        Berührung mehr und mehr
                                                                        ".($session['user']['sex']?"zu einer Ohrfeige tendierst":"dazu tendierst, ihm einen Schlag
                                                                        ins Gesicht zu verpassen").". Schließlich wird es dir zu bunt: Mit einem Ruck richtest du
                                                                        dich wieder auf, stößt den Jungen beiseite und verlässt den Raum. Genug ist genug!";
                                                        break;
                                                        // Geselle
                                                        case 4:
                                                        case 5:
                                                                $out .= O_SPACOLORTEXT."ein stattlicher, junger Mann in den Raum, verneigt sich lächelnd
                                                                       und sagt: \"".O_SPACOLORGESELLE."Seid gegrüßt. Ihr wünscht eine Thai-Massage? Dann
                                                                       entspannt Euch nun und vertraut auf meine geübten Hände.".O_SPACOLORTEXT."\"`n
                                                                       Du nickst zustimmend und gibst dich vertrauensvoll seiner Massage hin. Schon bald
                                                                       sorgen die Berührungen des Gesellen dafür, dass du dich merklich lebendiger fühlst -
                                                                       und auch, als er seine Massage beendet, kannst du noch den vitalisierenden Effekt spüren,
                                                                       der dich durchströmt.`n
                                                                       `n
                                                                       `^Deine Lebenspunkte steigen an.";
                                                                $session['user']['hitpoints'] = $session['user']['maxhitpoints']+($session['user']['level']*50);
                                                        break;
                                                        // Meister
                                                        case 6:
                                                                $out .= O_SPACOLORTEXT."ein älterer Herr mit bereits gräulich schimmerndem Haar. Er nickt dir
                                                                       zur Begrüßung zu, tritt dann an die Liege heran und beginnt mit der Massage.`n
                                                                       Du brauchst keine 10 Sekunden, um festzustellen, dass hier gerade ein wahrer Meister
                                                                       am Werk ist. Kaum angerührt, fangen all deine Muskeln im Körper an, sich zu entspannen,
                                                                       während der Rest deines Körpers sich immer lebendiger anfühlt. Mit einem Seufzer gibst
                                                                       du dich der Massage hin - und bist schon fast versucht zu protestieren, als der Masseur
                                                                       einige Zeit später seine Hände wieder von dir löst.`n
                                                                       `n".
                                                                       O_SPACOLORMEISTER."Du bist rundum mit dir im Einklang.";
                                                                 $arr_balance_buff = array('name'=>'`jSeelische Ausgeglichenheit`0',
                                                                                            'defmod'=>1.2,
                                                                                            'atkmod'=>1.4,
                                                                                            'wearoff'=>'`jDeine seelische Ausgeglichenheit lässt nach.',
                                                                                            'roundmsg'=>'`jDu bist rundum mit dir im Einklang - keiner kann dir etwas anhaben!',
                                                                                            'rounds'=>15,
                                                                                            'activate'=>'roundstart,offense,defense'
                                                                                           );
                                                                 $session['bufflist']['balance'] = $arr_balance_buff;
                                                        break;
                                                }
                                                $session['user']['turns']--;
                                        }
                                break;
                                default:
                                        $out = "`^Hm, dieses What: ".$_GET['what']." gibt es wohl nicht. Schicke diesen
                                                Satz bitte mit Angabe des Links oben in deiner Adressleiste an einen
                                                Admin.";
                                break;
                        }
                        addnav('Zurück');
                        addnav('Zurück zum Empfang',$filename.'?op=spa');
                }
                elseif($_GET['act'] == 'lounge')
                {
                        $out = O_LOUNGECOLORBOLD."`c`bIn der Wandelhalle`b`c`n".
                               O_LOUNGECOLORTEXT."Gedämpftes Licht empfängt dich, als du die Tür zur Wandelhalle öffnest.
                               Waren es eben noch hauptsächlich helle Farben, die die Eingangshalle
                               lebendig haben wirken lassen, ist dieser Raum großteils in Orange- und Brauntönen
                               gehalten. Alle Fenster sind mit dicken Stoffen verhangen, sodass das Tageslicht kaum
                               hereindringen kann; dafür flackern überall kleine Flammen hinter dunkel getönten Gläsern
                               diverser Öllampen. Sie werfen ihr Licht auf mehrere Sesselgruppen, die um drei kniehohe
                               Tische stehen, allesamt in dunkelbraun, fast schwarz, gehalten und mit kleinen Kerzen
                               versehen, die munter vor sich hin flackern. Auch Halterungen für Räucherstäbchen gibt es,
                               doch scheinen noch keine angesteckt worden zu sein. Stattdessen wurden sie auf jeden der
                               drei Tische verteilt - so bleibt es dem Gast selbst überlassen, ob er sich eins anstecken
                               möchte oder nicht.`n`n";
                        $commentary_section = 'spa_lounge';
                        addnav('Zurück');
                        addnav('Zurück zum Empfang',$filename.'?op=spa');
                }
                else
                {
                        $out = O_SPACOLORTEXT."`c`bDas {$spa_name}`b`c`n".
                               O_SPACOLORTEXT."Halb hinter dem Hügel entdeckst du ein kleines, einstöckiges Haus. Sofort
                               fällt dir der fremdartige Baustil ins Auge, der sich mit seinen vielen komplizierten
                               Holzverzierungen und roten Seidenlampions doch sehr von den Häusern unterscheidet, die du
                               bisher gesehen hast. Auch die schnörkeligen Ornamente auf den beiden mannshohen Bannern,
                               die neben der geöffneten Eingangstür in die Erde gepflockt worden sind, sagen dir nichts,
                               doch meinst du in einem zwei Drachen erkennen zu können, die sich gegenseitig wie im
                               Kampf umschlingen und den Schwanz des jeweils anderen im Maul gefangen halten.`n".
                               O_SPACOLORTEXT."Der Duft nach ätherischen Ölen lockt dich schließlich ins Innere des Hauses.
                               Auch dort ist alles reich verziert und in kräftigen hellen Farben gehalten; nur die Möbel
                               sind als einziges aus dunklem Teakbaumholz. Eines dieser Möbel - ein schmaler, hoher
                               Tisch - wurde direkt gegenüber der Eingangstür aufgestellt und dient dort als Empfang.
                               Auf ihm befindet sich ein großes aufgeschlagenes Buch, in das gerade eine Frau mittleren
                               Alters konzentriert hineinschaut. Als sie dich jedoch bemerkt, lässt sie sofort davon ab,
                               um dir anschließend ein entschuldigendes Lächeln zu schenken. \"".O_SPACOLORTALK."Oh, verzeiht,
                               ich habe Euch gar nicht hereinkommen hören. Seid willkommen im
                               {$spa_name}".O_SPACOLORTALK.". Mein Name ist {$spa_owner}".O_SPACOLORTALK.". Was kann ich
                               für Euch tun?".O_SPACOLORTEXT."\"";
                        $commentary_section = 'spa_entrance';
                        addnav('Haus der fünf Sinne');
                        addnav('m?Sich massieren lassen',$filename.'?op=spa&act=massage');
                        addnav('W?Zur Wandelhalle',$filename.'?op=spa&act=lounge');
                        addnav('Zurück');
                        addnav('v?Haus verlassen',$filename);
                        addnav('S?Zurück in die Stadt','village.php');
                }
        break;
        // Debug
        default:
                $out = "`^Hm, diese Op: ".$_GET['op']." gibt es wohl nicht. Schicke diesen Satz bitte mit Angabe
                        des Links oben in deiner Adressleiste an einen Admin.";
                addnav('Zurück',$filename);
        break;
}
// Text- und Schreibfeldausgabe
output($out);
if (!empty($commentary_section)) {viewcommentary($commentary_section,'Sagen',20,'sagt');}
// page footer
page_footer();
?>

