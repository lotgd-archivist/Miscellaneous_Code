<?php
 
/**
 *    Ein Überlebenskampf. Bekämpfe relativ starke Engel und staube ein paar Gewinne ab.
 *    Anlehnung an das Spiel "Tales of Symphonia" von Namco Tales Studios.
 *
 *    Original aus dem Jahre 2005, Überarbeitet 2007 vom gleichen Autor
 *  Copyright: 2005-2007 Basilius Sauter (aka "Lord ELiwood")
 *    Texte: 2007 by Basilius Sauter
 *    Version 2.0-RC2 vom 6. Juli 2007
 *
 *
 * Einbau:
 *    Öffne village.php und füge an einer beliebigen Stelle der Navigation folgenden Navigationspunkt ein:
 *        if ($session['user']['level'] == 15)  { 
            addnav("Seltsame Lichtung","cruxis.php");
        }
 *
 */

Require_once 'common.php';
page_header("Seltsame Lichtung");

// Der erste Engel, eher schwacher Natur.
define('CRUXIS_ANGELx01_NAME', '`6Engelsspäher');
define('CRUXIS_ANGELx01_WEAPON', 'Kurzbogen mit goldener Sehne');
define('CRUXIS_ANGELx01_LEVEL', 16);
define('CRUXIS_ANGELx01_ATTACK', 31);
define('CRUXIS_ANGELx01_DEFENSE', 22);
define('CRUXIS_ANGELx01_HEALTH', 166);

// Der Engelskommandant. Stärker als der erste Engel.
define('CRUXIS_ANGELx02_NAME', '`6Engelskommandant');
define('CRUXIS_ANGELx02_WEAPON', 'Engelsschwert aus Zwergenschmiede');
define('CRUXIS_ANGELx02_LEVEL', 18);
define('CRUXIS_ANGELx02_ATTACK', 35);
define('CRUXIS_ANGELx02_DEFENSE', 24);
define('CRUXIS_ANGELx02_HEALTH', 205);

// Die Engelschar. Offensichtlich hat der User einfach kein Glück...
define('CRUXIS_ANGELx03_NAME', '`6Engelsschar');
define('CRUXIS_ANGELx03_WEAPON', 'Verschiedenste Waffen');
define('CRUXIS_ANGELx03_LEVEL', 25);
define('CRUXIS_ANGELx03_ATTACK', 49);
define('CRUXIS_ANGELx03_DEFENSE', 32);
define('CRUXIS_ANGELx03_HEALTH', 470);

// Der faule Engel.
define('CRUXIS_ANGELx04_NAME', '`6Fauler Engel');
define('CRUXIS_ANGELx04_WEAPON', 'Dolch aus Zwergenschmiede');
define('CRUXIS_ANGELx04_LEVEL', 14);
define('CRUXIS_ANGELx04_ATTACK', 27);
define('CRUXIS_ANGELx04_DEFENSE', 20);
define('CRUXIS_ANGELx04_HEALTH', 145);

// Ihre Lordschaft, Lord Yggdrasil
define('CRUXIS_ANGELx05_NAME', '`^Lord Yggdrasil`0');
define('CRUXIS_ANGELx05_WEAPON', '`^Judgement.`0');
define('CRUXIS_ANGELx05_LEVEL', 30);
define('CRUXIS_ANGELx05_ATTACK', 54);
define('CRUXIS_ANGELx05_DEFENSE', 40);
define('CRUXIS_ANGELx05_HEALTH', 5000);

// Sonstige Vorbereitungen
$session['user']['specialmisc'] = unserialize($session['user']['specialmisc']);

if(isset($_GET['op']) AND $_GET['op'] == 'fight') {
    $battle = true;
    $_GET['q'] = 'fight';
}

switch(isset($_GET['q']) ? $_GET['q'] : '') {
    // Prolog
    case '': {
        rawoutput('<h2>Prolog</h2>
        <p>Etwas abseits der Stadt findest du einen Weg, dem du intressiert folgst. Nachdem du um eine Kurve gegangen bist, erkennen deine Augen eine Lichtung, die, schätzungsweise, etwa 30 Meter breit ist. Ein Lichtkegel, ein Strahl eher, durchbricht die merkwürdige, dunkle Wolkendecke, die sich über diesen Abschnitt des Waldes ausgebreitet hat. Dir ist das ganze nicht geheuer, doch deine Neugierde erwachst.</p>
        
        <p>Was wirst du nun tun?');
        
        addnav('Ansehen', 'cruxis.php?q=seeitandgodie');
        addnav('Davon schleichten', 'village.php');
        }
        break;
    
    // Erstes Kapitel: Licht
    case 'seeitandgodie': {
        output('<h2>Erstes Kapitel</h2>
        <h3>Licht</h3>
        
        <p>Du hast dich entschlossen, dir die Lichtung näher anzusehen. Langsam schreitest du über den steinigen Weg, der aussieht, als ob er künstlich erschaffen wurde. Zumindest ist er merkwürdig gerade, und die Bäume sehen an manchen Stellen zurecht gestutzt aus.</p>
        
        <p>Auf der Lichtung, die etwas kleiner ist, als du gedacht hast, bemerkst du nichts. Ausser den Lichtstrahl, der wohl genau die Mitte der Lichtung markiert. Du überprüfst zuerst die Umgebung, ob wirklich nichts hier ist, das dir Schaden könnte. Dann trittst du in den Lichtstrahl. „`6Licht kann ja nicht weh tun`0“, denkst du, wie es deine naive Art nun mal ist. Und wie man sich irren kann, erfahst du nur Millisekunden später.</p>
        
        <p>Ein Pfeil, aus schönstem Holz, reich verziert mit Adlerfedern und Gold, zischt durch die Luft und bleibt vor deinen Füssen im Boden stecken. Ängstlich blickst du in die Richtung, aus der er zu kommen schien: Hoch in den Himmel. Und was du dort siehst, verschlägt dir die Sprache.</p>
        
        <p>Ein Wesen mit riesigen Flügeln stürzt sich vom Himmel geradewegs auf dich zu. Es sind weisse Flügel. In der linken hält dieses Wesen einen Bogen. Und du weisst im selben Augenblick, wer den Pfeil geworfen hatte. Und du weisst auch, dass dir das Wesen nicht freundlich gesinnt ist.', true);
        
        $badguy = array(
            'creaturename' => CRUXIS_ANGELx01_NAME,
            'creatureweapon' => CRUXIS_ANGELx01_WEAPON,
            'creaturelevel' => CRUXIS_ANGELx01_LEVEL,
            'creatureattack' => CRUXIS_ANGELx01_ATTACK,
            'creaturedefense' => CRUXIS_ANGELx01_DEFENSE,
            'creaturehealth' => CRUXIS_ANGELx01_HEALTH,
        );
        
        // Modifiziere die Werte der Kreatur, um auch Phoenixkillreicheren Wesen eine Herausforderung zu geben.
        $badguy['creatureattack'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturedefense'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturehealth'] += ($session['user']['dragonkills'] / 3) * 7;
        
        $badguy['creatureattack'] = round($badguy['creatureattack']);
        $badguy['creaturedefense'] = round($badguy['creaturedefense']);
        $badguy['creaturehealth'] = round($badguy['creaturehealth']);
        
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialmisc']['angel'] = 1;
        $battle = true;
        }
        break;
    
    // Zweites Kapitel: Die Festung
    case 'hasdefeatedthefirstangelandcannotgoawaybecauseiwillitso': {
        output('<h2>Zweites Kapitel</h2>
        <h3>Die Festung</h3>
        
        <p>Die schwarze Wolkendecke, die das Stück Wald übergeben hat, stellt sich als begehbare Plattform heraus. Du bist gerade eben angekommen, die Wendeltreppe hinauf, und betrachtest nun das Stück Land, dessen Boden aus Wolken ist. Eine riesige Festung erhebt sich unweit von dir entfernt. Schwarze Mauern. Unheilverkündete Zinnen, welche die Spitze der vier Türme bildet. Mordgänge rund um die Festung. Doch es ist niemand zu sehen. Kein Engel. Weit und breit.</p>
        
        <p>Vorsichtig schleichst du dich, abseits des Weges, an die Festung heran. Du weisst, dass du nicht durch den Haupteingang eintreten kannst. Oder willst du von tausenden Engeln erschlagen werden? Schon nach kurzer Zeit werden deine Augen schliesslich fündig. Auf einer Seite der Festung scheint eine Türe offen zu sein, die wohl entweder als Botenein-, oder als Notausgang dient. Du atmtest tief durch, und öffnest die Türe. Trittst ein.', true);
        
        addnav('Weiter...', 'cruxis.php?q=herunsindeathbuthedontknowit');
        }
        break;
        
    case 'herunsindeathbuthedontknowit': {
        output("Ein Gang führt von der Türe hinweg, weiter, gen Herz der Festung. An den Wänden sind in gewissen Abständen, etwa 10 Schritte, Fackeln montiert, welche wohl als Lichtquelle dienen. „`6Der Arme, der die wechseln muss`0“, denkst du. Und ein gewisses Grinsen auf deinem Gesicht kannst du nicht leugnen.</p>
        
        <p>Dich bereits tot wissend schleichst du den Gang entlang. Wann immer du ein Geräusch hörst, haltest du inne und starrst nach vorne. Nach einer Weile kommst du an einer Türe an, welche einen Spalt breit offen steht. Und zu deinem Glück hörst du auch gleich eine Stimme, die gerade angefangen hat, zu sprechen. Hättest du die Stimme nicht gehört, hätte wohl der Sprechende dich gesehen. Oder zumindest der Zuhörer. Den es ja fast geben muss.</p>
        
        <p>„`2Hast du schon gehört? Ihre Lordschaft Lord Yggdrasil will ein Dorf zerstören`0“, spricht eine Stimme. Und spätestens hier wird dir klar, dass es zwei sein müssen.<br />
        „`5Ja, ich habe es bereits vernommen`0“, erklingt eine zweite, weibliche Stimme. „Thors Hammer soll jenen Fleck Erde zu nichts verwandeln. Damit er selbst residieren kann.“<br />
        „`2Schade, dass wir hier in der Küche sitzen und kochen müssen. Ich würde zu gerne wieder einmal mein Schwert durch einen schwachen Körper bohren lassen!`0“<br />
        „`5Schhht!`0“, macht die weibliche Stimme darauf hin. Gerade denkst du dich erwischt, und bereitest dich auf das schlimmste vor. Jedoch scheint sich nur die Türe zu schliessen. Du gehst weiter. Mit einem Gefühl im Magen, das man nicht als Übelkeit bezeichnen kann. Bedeutungsschwanger waren jene Worte, gewiss. Und du ahnst, dass diese Engel hier bestimmt nichts gutes im Sinn haben.", true);
        
        addnav('Weiter...', 'cruxis.php?q=gonowtothethreedoorsandhavealookwhatthisfoolisdoing');
        }
        break;
    
    // Drittes Kapitel: Die drei Türen
    case 'gonowtothethreedoorsandhavealookwhatthisfoolisdoing': {
        output('<h2>Zweites Kapitel</h2>
        <h3>Die drei Türen</h3>
        
        <p>Du schreitest den Weg entlang. Du hast inzwischen fest den Entschluss gefasst, dieses etwas, das sich Yggdrasil nennt, zu töten. Um dein Dorf zu beschützen. Jedoch hast du keine Ahnung, wohin du musst. Und schlussendlich bleibst du vor drei Türen stehen. Die drei Türen unterscheiden sich lediglich in ihrer Verzierung. Sie sind etwa zwei Mann hoch, ein Mann breit und aus geschwärztem Holz. Die Rahmen der Türe sind offensichtlich aus Eisen. Jede Türe hat an der gleichen Seite einen Türgriff, der zu deinem verwundern trotzdem auf der normalen Höhe zu sehen ist. Doch bemerkst du auch jeweils weitere Türgriffe, die sich im oberen Drittel befinden. „`6Für was die wohl gebraucht werden`0“, denkst du still. Und entscheidest dich, durch welche Türe du schreiten möchtest.</p>
        
        <p>Auf der Türe zu deiner Linken ist eine Schlacht zu sehen. Ein wahrer Künstler, der das in die Türe geschnitzt hat. Es sind offenbar zwei verschiedene Völker, die Gegeneinander kämpfen. Und vom Himmel herab steigen vier Engel.</p>
        
        <p>Die Türe, die sich in der Mitte befindet, wird von einem stolzen Engel geziert. Er ist fast so gross wie die Türe selbst, und sieht aus, als bewache er etwas. Er trägt einen Schild und einen Speer.</p>
        
        <p>Die dritte Türe, zu deiner Rechten, zeigt einen Baum. Die Krone ist um ein vielfacher grösser als der Stamm, und die Figuren, die auf dem Boden zu sehen sind, lassen die Dimensionen jenes riesigen Baumes vermuten.', true);
        
        addnav('Die linke Türe', 'cruxis.php?q=okayhechoosetheleftdoor');
        addnav('Die Türe geradeaus', 'cruxis.php?q=damnhechoosethemiddledoor');
        addnav('Die rechte Türe', 'cruxis.php?q=hechoostherightdoorandweillsee');
        }
        break;
    
    // Linke Türe
    case 'okayhechoosetheleftdoor': {
        output("Vorsichtig öffnest du die linke Türe. Gerade, als du durchschlüpfst, bereust du deine Tat. Du findest dich nämlich im Hof der Festung wieder. Und wie es dein Glück will, findet hier gerade eine Übungsstunde statt. Und, um den ganzen noch ein Sahnehäubchen aufzusetzen, bemerkt dich der offensichtliche Anführer.</p>
        
        <p>„`4Ein Eindringling!`0“, schreit er. Und sagt dann, im Anschluss: „`4Lasst mich zu Übungszwecken zeigen, wie man am einfachsten einen Feind tötet“.</p>
        
        <p>„`6Das kann nicht sein Ernst sein!`0“, denkst du. Verzweifelt. Und wie ernst es ihm ist, bemerkst du, als er, mit gezogenem Schwert, auf dich zugeht.", true);
        
        $badguy = array(
            'creaturename' => CRUXIS_ANGELx02_NAME,
            'creatureweapon' => CRUXIS_ANGELx02_WEAPON,
            'creaturelevel' => CRUXIS_ANGELx02_LEVEL,
            'creatureattack' => CRUXIS_ANGELx02_ATTACK,
            'creaturedefense' => CRUXIS_ANGELx02_DEFENSE,
            'creaturehealth' => CRUXIS_ANGELx02_HEALTH,
        );
        
        // Modifiziere die Werte der Kreatur, um auch Phoenixkillreicheren Wesen eine Herausforderung zu geben.
        $badguy['creatureattack'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturedefense'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturehealth'] += ($session['user']['dragonkills'] / 3) * 7;
        
        $badguy['creatureattack'] = round($badguy['creatureattack']);
        $badguy['creaturedefense'] = round($badguy['creaturedefense']);
        $badguy['creaturehealth'] = round($badguy['creaturehealth']);
        
        $session['user']['badguy'] = createstring($badguy);
        $session['user']['specialmisc']['angel'] = 2;
        $battle = true;
        }
        break;
        
    case 'wellhehasdefeatedthesecondangelbutwhathappensatnext': {
            $hasluckoderhaventluck = e_rand(0, 255);
            $hasluckoderhaventluck = $hasluckoderhaventluck%2;
            
            if($hasluckoderhaventluck === 1 OR $_GET['force'] == 'true') {
                // Has luck :(
                output("Da du dich beim Tot deines Gegners gerade bei der Türe befandest, kannst du ohne Probleme durch die Türe flüchten. Das machst du auch. Du schlüpfst erneut durch die Türe. Und befindest dich wieder in diesem Raum. Rechts scheint der Weg zu sein, wo du hergekommen bist. Und da eine Flucht ohnehin nicht sinnvoll scheint, überlegst du, durch welche Türe du als nächstes gehen willst...", true);
                
                addnav('Die linke Türe', 'cruxis.php?q=damnhechoosethemiddledoor');
                addnav('Die Türe geradeaus', 'cruxis.php?q=hechoostherightdoorandweillsee');
                $session['user']['specialmisc']['hasvisitedtheleftdoor'] = true;
            }
            else {
                // Has no luck :)
                output("Doch dein Glück meint es nicht gut mit dir. Die Schar an Engel, nun einem Mob nicht unähnlich, greift plötzlich zu den Waffen. Es gibt keine Ordnung in diesem Haufen, und sie wollen nur eines: Deinen Tod.", true);
                
                $badguy = array(
                    'creaturename' => CRUXIS_ANGELx03_NAME,
                    'creatureweapon' => CRUXIS_ANGELx03_WEAPON,
                    'creaturelevel' => CRUXIS_ANGELx03_LEVEL,
                    'creatureattack' => CRUXIS_ANGELx03_ATTACK,
                    'creaturedefense' => CRUXIS_ANGELx03_DEFENSE,
                    'creaturehealth' => CRUXIS_ANGELx03_HEALTH,
                );
                
                // Modifiziere die Werte der Kreatur, um auch Phoenixkillreicheren Wesen eine Herausforderung zu geben.
                $badguy['creatureattack'] += $session['user']['dragonkills'] / 3;
                $badguy['creaturedefense'] += $session['user']['dragonkills'] / 3;
                $badguy['creaturehealth'] += ($session['user']['dragonkills'] / 3) * 7;
                
                $badguy['creatureattack'] = round($badguy['creatureattack']);
                $badguy['creaturedefense'] = round($badguy['creaturedefense']);
                $badguy['creaturehealth'] = round($badguy['creaturehealth']);
                
                $session['user']['badguy'] = createstring($badguy);
                $session['user']['specialmisc']['angel'] = 3;
                $battle = true;
            }
        }
        break;
    
    // Mittlere Türe
    case 'damnhechoosethemiddledoor': {
        output("Vorsichtig öffnest du die Türe, die gerade aus lag. Und du findest dich in einem Gemach wieder. Du hörst Schnachgeräusche aus einer Ecke. Vorsichtig schleichst du durch den Raum. Und siehst die Quelle der Geräusche.</p>
        
        <p>Ein Engel, der nicht gerade fit aussieht, schläft seelenruhig auf seinem Bett. „`6Den könnte ich töten!`0“, denkst du. Doch bevor du dies tun willst, siehst du dich hier noch ein wenig mehr um.</p>
        
        <p>Auf einem Tischchen steht eine Teekanne, aus der wollig warmer Tee dampft. Daneben scheint eine Notiz zu legen, die du aber, aufgrund der fremden Sprache, nicht lesen kannst. Ist es eine Warnung? Oder ein bedeutungsloser Zettel? Eine Inhaltsangabe? Ein Becher, der neben der Kanne steht, lädt auf jeden Fall zum trinken ein...</p>
        
        <p>Was wirst du tun?", true);
        
        if(empty($session['user']['specialmisc']['hasdrunkentee'])) {
            addnav('Tee trinken', 'cruxis.php?q=hewilldrinkingacupofftee');
        }
        if(empty($session['user']['specialmisc']['haskilledthefoolangel'])) {
            addnav('Engel töten', 'cruxis.php?q=heisoverstrangeandwillkillthisangel');
        }
        addnav('Gar nichts tun', 'cruxis.php?q=gobacktotheroomwithdedoors');
        }
        break;
    #<!-- „“ -->
    case 'hewilldrinkingacupofftee': {
        output("Du schenkst dir in aller Ruhe etwas Tee ein. Dann legst du die Tasse an deinen Mund und beginnst, den Tee in vollen Zügen zu geniessen. Und du verspürst auch sofort eine Wirkung: Du fühlst dich wieder vollkommen fit für einen neuen Kampf.</p>
        
        <p>Ob der Tee wohl Nebenwirkungen hat?</p>
        
        <p>Deine Lebenspunkte wurden aufgefüllt!", true);
        
        if( (e_rand(0, 99) % 3) === 0) {
            $session['user']['specialmisc']['teehasunknownsideeffects'] = true;
            $session['user']['specialmisc']['hasdrunkentee'] = true;
        }
        else {
            $session['user']['specialmisc']['teehasunknownsideeffects'] = false;
            $session['user']['specialmisc']['hasdrunkentee'] = true;
        }
        
        if(empty($session['user']['specialmisc']['haskilledthefoolangel'])) {
            addnav('Engel töten', 'cruxis.php?q=heisoverstrangeandwillkillthisangel');
        }
        addnav('Gar nichts tun', 'cruxis.php?q=gobacktotheroomwithdedoors');
        }
        break;
        
    case 'heisoverstrangeandwillkillthisangel': {
        output("Übermütig, wie du bist, willst du deine Waffe in den Leib deines Gegners bohren. Doch jener schlägt sofort die Augen auf, als er die Gefahr spürt, und springt aus dem Bett. Hast du wirklich gedacht, dass das so einfach geht?</p>
        
        <p>Die Blutlust steht ihm geradewegs in das Gesicht geschrieben. Er will dich töten. Dein Blut sehen.", true);
        
        $badguy = array(
            'creaturename' => CRUXIS_ANGELx04_NAME,
            'creatureweapon' => CRUXIS_ANGELx04_WEAPON,
            'creaturelevel' => CRUXIS_ANGELx04_LEVEL,
            'creatureattack' => CRUXIS_ANGELx04_ATTACK,
            'creaturedefense' => CRUXIS_ANGELx04_DEFENSE,
            'creaturehealth' => CRUXIS_ANGELx04_HEALTH,
        );
        
        // Modifiziere die Werte der Kreatur, um auch Phoenixkillreicheren Wesen eine Herausforderung zu geben.
        $badguy['creatureattack'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturedefense'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturehealth'] += ($session['user']['dragonkills'] / 3) * 7;
        
        $badguy['creatureattack'] = round($badguy['creatureattack']);
        $badguy['creaturedefense'] = round($badguy['creaturedefense']);
        $badguy['creaturehealth'] = round($badguy['creaturehealth']);
        
        $session['user']['badguy'] = createstring($badguy);
        $session['user']['specialmisc']['angel'] = 4;
        
        $battle = true;
        }
        break;
        
    case 'gobacktotheroomwithdedoors': {
        output("Und du befindest dich erneut im Raum mit den drei Türen. Gegenüber von dir bemerkst du den Gang, den du schon einmal entlang gegangen warst.", true);
        
        if(empty($session['user']['specialmisc']['hasvisitedtheleftdoor'])) {
            addnav('Die rechte Tür', 'cruxis.php?q=okayhechoosetheleftdoor');
        }
        addnav('Zurück gehen', 'cruxis.php?q=damnhechoosethemiddledoor');
        addnav('Die linke Tür', 'cruxis.php?q=hechoostherightdoorandweillsee');
        }
        break;
        
    // Die rechte Türe, und die einzige, richtige Wahl
    case 'hechoostherightdoorandweillsee': {
        output("Mutig, wie du bist, öffnest du die Türe mit dem Baum darauf. Vorsichtig schlüpfst du durch. Erneut befindest du dich in einem Gang. Der jedoch etwas geräumiger wirkt, als der vorige. Mutig schreitest du weiter.", true);
        
        if(empty($session['user']['specialmisc']['haskilledthefoolangel'])) {
            // Der Diamant, der man beim vierten Engel findet, schützt einem vor dieser Falle. Aber im jetzigen Fall hat der User keinen. Glück?
            $userwilldieinthetrap = e_rand(0, 2300) % 3;
            if($userwilldieinthetrap === 0) {
                // Ja, der liebe User ist am Sterben. Sorry :)
                output("Du merkst jedoch zu spät, dass der Gang nur für Engel gemacht zu sein scheint. Denn du trittst auf eine lose Bodenplatte und löst damit eine Falle aus. Pfeile schiessen plötzlich als allen Richtungen durch den Gang. Und du hast absolut keine Überlebenschance.</p>
                
                <p>Du bist gestorben. Du verlierst all dein Gold. Auch deine Edelsteine wirst du los. Selbst Schuld, wenn du dich mit Engel anlegen möchtest. Doch immerhin verlierst du nicht an Erfahrung. Denn das, was du gelernt hast, relativiert den Verlust wieder.");
                
                $session['user']['hitpoints'] = 0;
                $session['user']['alive'] = false;
                $session['user']['gold'] = 0;
                $session['user']['gems'] = 0;
                $session['user']['experience'] = 0;
                
                addnav('Tägliche News', 'news.php');
            }
            else {
                // Glück gehabt. Oder doch nicht? Lassen wir immerhin die Nebenwirkungen vom Tee erscheinen. Falls er welche gehabt hatte.
                
                output("Du schreitest durch die Gänge. Du bemerkst auch manche Bodenplatten, die merkwürdig hervorgehoben erscheinen. Glück für dich, dass die Architekten so schwach geplant haben. Auf diese Fallen fällst du auf jeden Fall nicht rein.", true);
                
                if(!empty($session['user']['specialmisc']['teehasunknownsideeffects'])) {
                    // Nebenwirkungen. Gibt verschiedene... Aber auch keine
                    $sideeffects = e_rand(0, 2300) % 63;
                    switch($sideeffects) {
                        case 59:
                        case 45:
                        case 31:
                        case 17:
                        case 3:
                            // Maximale Lebenspunkte -1
                            output("Allerdings verspürst du ein leichtes Unwohlsein. Sollte das eine Nebenwirkkung des Tees sein?</p>
                            
                            <p>Du verlierst einen maximalen Lebenspunkt.", true);
                            
                            $session['user']['maxhitpoints']--;
                            
                            $sideeffect = true;
                            break;
                        
                        case 44:
                        case 43:
                        case 42:
                            // Lebenspunkte -10
                            output("Allerdings verspürst du ein leichtes Unwohlsein. Sollte das eine Nebenwirkkung des Tees sein?</p>
                                
                                <p>Du verlierst Lebenspunkte.", true);
                                
                            if($session['user']['hitpoints'] > 10) {                                
                                $session['user']['hitpoints']-=10;
                            }
                            else {
                                $session['user']['hitpoints'] = 1;
                            }
                            
                            $sideeffect = true;
                            break;
                            
                        case 0:
                            // Tod.
                            output("Plötzlich verschwimmt deine Sicht. Krämpfe breiten sich aus deiner Magengegend aus, und du merkst, wie du deine Extremitäten als wie weniger spürst. Blut tritt aus deiner Nase, aus deinem Mund. Schlussendlich verschwindet auch dein Bewusstsein. Und dein Leben.</p>
                            <p>Du bist gestorben. Du verlierst an die raffgierigen Engel all den Gold und deine Edelsteine.");
                            
                            $session['user']['hitpoints'] = 0;
                            $session['user']['alive'] = false;
                            $session['user']['gold'] = 0;
                            $session['user']['experience'] = 0;
                            $session['user']['gems'] = 0;
                            
                            addnav('Tägliche News', 'news.php');
                            
                            $sideeffect = true;
                            break;
                    }
                    
                    
                }
                
                if($session['user']['hitpoints'] > 0) {
                    // Nichts passiert.
                    output("Schliesslich kommst du an einem Tor an. Es ist identisch mit dem, das du zu Begin des Ganges durchschritten hast. Aber sie steht offen. Voller Abenteuerlust, und auch mit ein wenig Wut im Bauch, schreitest du durch das Tor...", true);
                    
                    addnav('Weiter...', 'cruxis.php?q=finaldestinationorhowstupidcanaplayerbe');
                }
            }
        }
        else {
            output("Du schreitest durch die Gänge. Plötzlich beginnt dein Diamant zu schimmern, und erleuchtet den dunklen Gang. So bemerkst du ohne Probleme die Bodenplatten, die Auslöser für Fallen sind, und schreitest, darauf achtend, keine der Platten zu treffen, durch den Gang. </p>
            <p>Schliesslich kommst du an einem Tor an. Es ist identisch mit dem, das du zu Begin des Ganges durchschritten hast. Aber sie steht offen. Voller Abenteuerlust, und auch mit ein wenig Wut im Bauch, schreitest du durch das Tor...", true);
            
            addnav('Weiter...', 'cruxis.php?q=finaldestinationorhowstupidcanaplayerbe');
        }
        }
        break;
    
    // Letztes Kapitel: Lord Yggdrasil
    case 'finaldestinationorhowstupidcanaplayerbe': {
        output('<h2>Letztes Kapitel</h2>
        <h3>Lord Yggdrasil</h3>
        
        <p>Schliesslich findest du dich in einem geräumigen Saal wieder. Auf einem reich verzierten Stuhl, der selbst auf einem Podium steht (soll wohl einen Thron symbolisieren), sitzt ein Engel. Gekleidet in weisser Kleidung. Die blonden Haare fallen über seine Schultern, hinab zum Kreuz. Sein Kopf liegt aufgestützt auf seiner rechten Hand. Gelangweilt starrt er dich an.</p>
        
        <p>„`3Ich habe dich erwartet`0“, spricht der Engel. Er steht auf, und geht langsam auf dich zu. Und während den Schritten spricht er mit dir. „`3Ich bin Lord Yggdrasil. Anführer dieser Organisation`0“. Er bleibt stehen. Etwa 10 Meter von dir entfernt. „`3Hast du wirklich gedacht, du könntest unbemerkt in meine Festung eindringen? Hast du dir nie die Frage gestellt, warum jene Treppe auf der Lichtung erschien? Ich brauche deinen Körper. Als Puppe. Du bist bekannt. Und wer kann besser ins Dorf gelangen, als jemand, der wie ein Dorfbewohner aussieht?<br />
        Genau. Du. Wir werden aus dem Dorf hinaus den Weg des Lichtes leuchten, und Thors Hammer los lassen. Die stärkste Magie.`0“</p>
        
        <p>Der Mann, Yggdrasil, schliesst schliesslich die Augen. Ergeben schaut er mit geschlossenen Augen gen Decke, dorthin, wo ein Bild einer wunderschönen Frau gezeichnet ist. Seine Geliebte? Du wirst es wohl nie erfahren. Sein Körper beginnt zu zittern. Um seinen Körper herum erscheint ein weisses Licht, kreisförmig. Seine Haare werden hochgewirbelt. Und in seinen Händen bündelt sich Energie.</p>
        
        <p>Dann, auf einmal, verschwindet die Energie. Und der Mann, der Yggdrasil genannt und einen Lord geschimpft wird, wirft eine Kugel aus reinstem Licht gegen dich. Der Kampf hat begonnen.', true);
        
        $badguy = array(
            'creaturename' => CRUXIS_ANGELx05_NAME,
            'creatureweapon' => CRUXIS_ANGELx05_WEAPON,
            'creaturelevel' => CRUXIS_ANGELx05_LEVEL,
            'creatureattack' => CRUXIS_ANGELx05_ATTACK,
            'creaturedefense' => CRUXIS_ANGELx05_DEFENSE,
            'creaturehealth' => CRUXIS_ANGELx05_HEALTH,
        );
        
        // Modifiziere die Werte der Kreatur, um auch Phoenixkillreicheren Wesen eine Herausforderung zu geben.
        $badguy['creatureattack'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturedefense'] += $session['user']['dragonkills'] / 3;
        $badguy['creaturehealth'] += ($session['user']['dragonkills'] / 3) * 10;
        
        $badguy['creatureattack'] = round($badguy['creatureattack']);
        $badguy['creaturedefense'] = round($badguy['creaturedefense']);
        $badguy['creaturehealth'] = round($badguy['creaturehealth']);
        
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialmisc']['angel'] = 5;
        $battle = true;
        }
        break;
}




if ($battle === true) {
    Include 'battle.php';
    if($victory) {
        switch($session['user']['specialmisc']['angel']) {
            case 1:
                output('Das Wesen ist tot. Du entreisst ihm ein Amulett, das ihm um den Hals hängt. Darauf abgebildet siehst du den grossen Buchstaben "A" in einem Kreis abgebildet, welcher Engelsflügel hat. "`6Engel?`0", fragst du dich still, denn als du etwas nachdenkst, fallen dir Geschichten ein, die von Wesen mit riesigen, weissen Flügeln berichten und Engel genannt werden. Ob dies hier so einer war?</p>
                
                <p>Plötzlich, wie von Zauberhand, erscheint eine Wendeltreppe rund um jenen Lichtstrahl, oder Lichtkegel, was es schlussendlich auch sein mag. Die Lichttreppe scheint nicht natürlich zu sein, denn sie besteht aus Lichtstrahlen. Vorsichtig setzt du deinen Fuss auf den ersten Tritt. Zu deinem Verwundern scheint die Treppe dein Gewicht tatsächlich tragen zu können... Was das wohl bedeutet?</p>
                
                <p>Voller Tatendrang erklimmst du die Wendeltreppe. Denn ein Zurück gibt es nicht mehr. Du hast einen Engel getötet, und das bedeutet die Todesstrafe. Das Judgement. Das heilige Urteil. Und wer weiss. Vielleicht findest du auch Reichtum?', true);
                
                addnav('Weiter...', 'cruxis.php?q=hasdefeatedthefirstangelandcannotgoawaybecauseiwillitso');
                break;
                
            case 2:
                output("Und auch der Engelskommandant ist tot. Aber ob das eine gute Idee war? Denn dort stehen noch mehr Engel. Und sie sehen nicht gerade freundlich aus. Nein, wirklich nicht.", true);
                
                addnav('Weiter...', 'cruxis.php?q=wellhehasdefeatedthesecondangelbutwhathappensatnext');
                break;
                
            case 3:
                output("Du hast auch die Engelsschar nieder geschlagen. Erschöpft schwankst du zur Türe, von der du gekommen bist. Oder willst du im Ernst warten, bis noch mehr Engel kommen und dein Blut wollen? Du bist bereits ihr Todfeind.", true);
                
                addnav('Weiter...', 'cruxis.php?q=wellhehasdefeatedthesecondangelbutwhathappensatnext&force=true');
                break;
                
            case 4:
                output("Auch den faulen Engel hast du getötet. Beim durchsuchen jenes Gegners findest du einen merkwürdig schimmernden Diamanten. „`6Den nimm ich wohl besser mit`0“, denkst du. Und tust es auch.");
                
                $session['user']['specialmisc']['haskilledthefoolangel'] = true;
                
                if(empty($session['user']['specialmisc']['hasdrunkentee'])) {
                    addnav('Tee trinken', 'cruxis.php?q=hewilldrinkingacupofftee');
                }
                addnav('Gar nichts tun', 'cruxis.php?q=gobacktotheroomwithdedoors');
                break;
                
            case 5:
                output("Die erstarrten Augen des Engels blicken dich überrascht an. Er hat nicht damit gerechnet, dass du ihn tötest. Röchelnd sinkt er auf die Knie. Und dann fällt er ganz um. Erschöpft wischst du dir das Blut aus dem Gesicht. Dann durchsuchst du den Engel, und findest ein Säckchen. Du öffnest es, und findest zehn Edelsteine.</p>
                
                <p>Dann, auf einmal, verschwimmen die Farben. Es wird schwarz, und in der nächsten Sekunde wachst du auf der Lichtung wieder auf. Hast du etwa alles nur geträumt? Hat dir das Licht die Sinne geraubt?</p>
                
                <p>Vielleicht. Wahrscheinlich. Aber woher sonst das Säckchen mit den Edelsteinen kommt, weisst du nicht. Aber wer weiss. Vielleicht hat sich auch dein Ruf verbessert.", true);
                
                $session['user']['gems']+=10;
                $session['user']['reputation']+=50;
                
                addnav('Zum Dorf', 'village.php');
                break;
        }
    }
    elseif($defeat) {
        if($session['user']['specialmisc']['angel'] == 5) {
            output("Mit letzter Kraft schneidest du dir selbst in deinen Bauch. Wenn er deinen Körper bekommen soll, dann nur zerstört. Denn die Art, wie er dich angreift, mit Licht in reinster Form, zeugt davon, dass er deinen Körper unverletzt braucht. Mit einem letzten Grinsen ziehst du schliesslich die Klinge aus deinem Bauch, und legst es an deinem Hals an. Du hörst nur noch, wie der Engel, Lord Yggdrasil, schreit. Was er schreit, verstehst du nicht mehr. Schwärze überkommt dich...</p>
            
            <p>Du bist gestorben. Du verlierst all dein Gold. Auch deine Edelsteine wirst du los. Selbst Schuld. Wenn du dich mit Engel anlegen möchtest. Doch immerhin verlierst du nicht an Erfahrung. Denn das, was du gelernt hast, relativiert den Verlust wieder.");
                
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            $session['user']['gold'] = 0;
            $session['user']['gems'] = 0;
            $session['user']['experience'] = 0;
            
            addnav('Tägliche News', 'news.php');
        }
        else {
            output("Du bist gestorben. Kläglich, ohne Zeugen. In einer Welt, die merkwürdiger nicht sein kann. Du verlierst all dein Gold. Auch deine Edelsteine wirst du los. Selbst Schuld. Wenn du dich mit Engel anlegen möchtest. Doch immerhin verlierst du nicht an Erfahrung. Denn das, was du gelernt hast, relativiert den Verlust wieder.");
            
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            $session['user']['gold'] = 0;
            $session['user']['gems'] = 0;
            $session['user']['experience'] = 0;
            
            addnav('Tägliche News', 'news.php');
        }
    }
    else {
        fightnav(true, false);
    }
}

page_footer();
?>