
<?php

/*
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
| Die Lichtung der Legenden.                                       |
| Inspiriert und abgekupfert vom Heldenlager (herocamp)               |
|---------------------------------------------------------------|
| Version 1.1 by Nashyan (nashyan@habmalnefrage.de), 02.03.2011    |
| Vielen Dank für die tollen Texte an Araya.                       |
| Stand: 24.5.2011                                                   |
|---------------------------------------------------------------|
| Features:                                                           |
| - Körperkünstler: Sticht einem Runen als Tattoos, nach denen  |
|                    man wieder bei null beginnen muss, aber man |
|                    bekommt dafür einige Boni.                    |
| - Schmiedmeister: Hier kann man gegen Gold, Gems und DPs        |
|                    Waffen und Rüstungen erstehen, die auch nach|
|                    dem DK erhalten bleiben. Je besser die Werte|
|                    desto mehr kostet der Spaß. Eigene Namen     |
|                    kann man ihnen auch geben.                    |
| - Lagerfeuer:        Ein RP-Ort.                                    |
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
| Changelog:                                                    |
| v1.1:    - permanente Waffen & Rüstungen lassen sich nun auch    |
|        nach dem Kauf upgraden.                                    |
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/


//Einbinden der common.php
require_once("common.php");

//Seitenkopf und Standort festlegen
page_header("Lichtung der Legenden");
$session['user']['standort'] = "Legendenlichtung";

//Kommentare ermöglichen
addcommentary();

//Ein paar Variablen
$dkneed     = 30;         //Wieviele Drachenkills braucht der Spieler, um das Zelt des Körperkünstlers betreten zu dürfen?
$goldcost    = 100000;     //Goldkosten pro Angriffs-/Verteidigungspunkt der Waffe/Rüstung
$gemscost    = 300;        //Gemskosten pro Angriffs-/Verteidigungspunkt der Waffe/Rüstung
$dpcost        = 300;        //DP-Kosten pro Angrifgfs-/Verteidigungspunkt der Waffe/Rüstung        
$tattoos = array(        //Der Array für die Tattoos
0=>("Fehu"),                                    //spezielle Tiere 1
1=>("Uruz"),                                    //ATK +1
2=>("Algis"),                                    //DEF +1
3=>("ein Keltischer Knoten"),                    //ATK +2
4=>("Nauthiz"),                                    //DEF +2
5=>("Loa Ogoun"),                                //ATK +3
6=>("der Drudenfuß"),                            //DEF +3
7=>("das Auge des Horus"),                        //ATK +4
8=>("Ankh"),                                    //DEF +4
9=>("der Schlüssel des Salomon")                //spezielle Tiere 2
);

$tattoosdesc = array(    //Array für die Tattoobeschreibung
//Fehu
0=>("Dieses Runenzeichen symbolisiert Wohlstand und Glück. Doch es verkörpert ebenso das Vieh und dessen Geltung.\"`9`n`n
    Verwirrt von dieser Information und dem nur langsam abklingenden Schmerz verlässt du das Zelt und hörst, als du
    dich dem Wald näherst, ein  lautes, animalisches Gebrüll. Es scheint seinen Ursprung fernab der Lichtung zu haben
    und plötzlich hast du das Gefühl, dass in der Stadt etwas auf dich wartet.`0"),
//Uruz
1=>("Es soll dem Träger Mut, Vitalität und Energie verleihen. So, wie der Regen die Erde stärkt.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt und weißt genau,
    dass Jesco mit seinen Worten Recht behalten wird. Auf sonderbare Weise fühlst du dich belastbarer.`0"),
//Algis
2=>("Ein uraltes Symbol für göttlichen Schutz.\"`9 Mehr sagt er nicht.`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt und weißt genau,
    dass Jesco mit seinen Worten Recht behalten wird.  Auf sonderbare Weise fühlst du dich beschützt und sicher.`0"),
//Keltische Knoten
3=>("Ein altes Symbol für Wiedergeburt und Schutz. Wer es trägt, wird bestens gerüstet sein, für die täglichen Abenteuer
    des Lebens.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt und weißt genau,
    dass Jesco mit seinen Worten Recht behalten wird. Nach einigen Schritten scheinen Tatendrang und Kampfeslust in deiner
    Brust nahezu zu explodieren und du marschierst zielstrebig Richtung Wald.`0"),
//Nauthiz
4=>("Es wird von den Runenkundigen als Notrune bezeichnet. Doch in der Not werden ungeahnte Fähigkeiten geboren. Fähigkeiten,
    die die Seele schützen.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt. Du weißt, dass es eine Weile
    dauern wird, bis du diese philosophischen Worte verstanden haben wirst, aber eines spürst du schon jetzt: eine innere Kraft,
    die dich zu schützen scheint.`0"),
//Loa Ogoun
5=>("Einer der besten Krieger unter den Geisterwesen. Das Symbol soll dem Träger einen Teil seiner Stärke verleihen.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt und weißt genau, dass Jesco
    mit seinen Worten Recht behalten wird. Auf sonderbare Weise fühlst du dich vitaler.`0"),
//Drudenfuß
6=>("Ein Pentagramm, das auf dem Kopf steht. Ein sehr altes Symbol. Soll seinem Träger als Schutzschild gegen böse Kräfte dienen.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt . Auf sonderbare Weise gefällt
    dir der Gedanke, durch das Symbol fortan geschützt zu sein.`0"),
//Auge des Horus
7=>("Schützt vor bösen Blicken und verleiht Kräfte aus alten Zeiten.\"`9`n`n
    Als du wenig später das Zelt verlässt, atmest du die frische Abendluft ein und fühlst dich um einiges vitaler, als du dich
    gefühlt hast, bevor dein Weg dich an diesen Ort verschlagen hat.`0"),
//Ankh
8=>("Ein zeitloses Symbol für Gesundheit und Lebenskraft.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt. Ohne zu wissen wieso oder
    weshalb, erfüllt dich das Wissen um das neue Zeichen auf deinem Körper mit innerer Ruhe. Du fühlst dich sicher und geschützt.`0"),
//Schlüssel des Salomon
9=>("Er symbolisiert den Zutritt zur Weisheit und alten Lehren. In alten Märchen heißt es, dass er dem Träger Zauberkräfte verleiht.\"`9`n`n
    Noch etwas benommen von dem brennenden Gefühl in deinem Arm, verlässt du wenig später das Zelt und dann erinnerst du dich an eine
    Volksweisheit: Wo ein Schlüssel ist, ist auch immer eine Tür, welche sich mit ihm öffnen lässt...`0")
);






switch($_GET['op'])
{
    //Spieler tritt in das Lager ein
    case "":
        if($_GET['newlife'] == 1)
        {
            $session['user']['restorepage'] = 'village.php';
            redirect("newday.php");
        }
        place(1);
        
        addnav("Zum Zelt des Körperkünstlers","legendenlichtung.php?op=zelt");
        //if($session['user']['herotattoo'])
            addnav("Zum Schmied","legendenlichtung.php?op=schmied");
        addnav("Ans Lagerfeuer","legendenlichtung.php?op=feuer");
        addnav("zur Heldengasse","heldengasse.php");
        break;
    
    //Spieler tritt in das Zelt des Körperkünstlers
    case "zelt":
        place(1);
            
        //Hat der Spieler genügend Drachenkills?
        if($session['user']['dragonkills'] >= $dkneed)
        {
            //Spieler hat bereits ein Tattoo
            if($session['user']['herotattoo'])
            {
                addnav("In das Zelt treten","legendenlichtung.php?op=zelt_in");
                output("`n`n`9Behäbig treten die Golems je einen großen Schritt nach hinten, als sie dich
                        wiedererkennen. Wie sie  ohne Augen dazu in der Lage sind ist dir ein Rätsel,
                        über welches du jedoch erst später zu grübeln gedenkst. Der Eingang zum Zelt
                        steht dir offen und Asche und Tinte warten darauf, mit deiner Haut zu verschmelzen.`0"); 
            }
            //Spieler hat noch kein Tattoo
            else 
            {
                addnav("In das Zelt treten","legendenlichtung.php?op=zelt_in");
                output("`n`n`9Die Golems scheinen zu spüren, dass sie es mit einem erfahrenen Krieger zu tun haben. Nach einigen
                    weiteren, zähen Augenblicken treten sie zur Seite und geben den Eingang des Zeltes für dich frei.");
            }
        }
        //Tja, Satz mit X... ;)
        else
        {
            output("`n`n`9Ein tiefes Grollen scheint aus den Körpern der Golems zu treten, bevor sie verneinend den Kopf schütteln und sich Seite
                    an Seite vor dem Eingang des Zeltes postieren. Du tätest gut daran, nun keine Diskussion mit ihnen einzugehen und diesen
                    Ort so schnell wie möglich zu verlassen.");
        }
            
        addnav("Zurück","legendenlichtung.php");    
        break;
    
    //Spieler ist im Inneren des Zeltes    
    case "zelt_in":
        
        //Hat der Spieler schon ein Tattoo? Falls nicht, gibt es einen entsprechenden Text.
        if(!$session['user']['herotattoo'])
            $text = "`9Zum ersten Mal betrittst du das Zelt, in dem der berüchtigte Jesco die Haut der Krieger verziert. Es riecht nach Feuer und Asche,
                    Leder und Flachsfasern, Metall und Schweiß. Vage hängt auch der Geruch nach verbrannter Haut in der Luft. Das Zelt ist voll gestellt
                    mit Kesseln und Holztruhen, über die jemand weiße Felle geworfen hat. Suchend blickst du dich um, bis du auf einmal einen breitschultrigen
                    Mann entdeckst, der auf einem Schemel nahe am Feuer sitzt und sich die Hände wärmt. Als du dich räusperst schaut er auf und schaut dich mit
                    einem durchdringenden Blick aus wässrig blauen Augen an.`n
                    `r\"Ein Erstling\"`9, sagt er mit dunkler Stimme und nickt dir zu. Nachdem du ihm erklärt hast, dass du dich dazu entschlossen hast,
                    deinen Körper mit ewiger Farbe verzieren zu lassen. `r\"Das erste Symbol\"`9, schmunzelt Jesco sogar ein wenig und hält zwei bereits arg in
                    Mitleidenschaft gezogene Karten hoch. `r\"Diese Motiv, oder jenes hier?\"`9, will er wissen. Du kannst aus der Distanz zu verblichenen Bilder
                    nicht erkennen und zeigst einfach hastig auf eines der beiden.`n
                    Jesco besieht sich das Zeichen und nickt schließlich: `r\"Eine gute Wahl, doch...bist du dir wirklich sicher?\"`9 Seine Worte werden von einem
                    rauchigen Lachen begleitet.`n`n`n`n
                    `b`4Achtung, solltest du dich tätowieren lassen, werden all deine bisherigen, weltlichen Errungenschaften verloren sein. Du wirst ganz von vorne, als"
                    .($session['user']['sex'] ? "Milchmädchen" : "Bauernjunge")." beginnen, allerdings mit ein paar Boni starten.`b`0";
                    
        //Andernfalls herrscht natürlich eine komplett andere Ausgangssituation, darum auch ein anderer Text
        else if($session['user']['herotattoo'] <= 10)
            $text = "Kaum hast du den ersten Fuß in das Zelt gesetzt, kommt die Erinnerung an deinen letzten Besuch wieder auf. Der Geruch nach nach Feuer und Asche,
                    Leder und Flachsfasern, Metall und Schweiß, lässt dir den Tag, an dem du dir dein letztes Tintensymbol hast stechen lassen, wie gestern vorkommen.
                    Der vage Geruch nach verbrannter Haut lässt dich wieder ins Hier und Jetzt zurückkehren. Mit großen Schritten gehst du auf Jesco zu und begrüßt
                    ihn mit einem strammen Händedruck.`n
                    `r\"Lässt dich auch mal wieder blicken?!\"`9, richtet er mit einem knappen Nicken das Wort an dich, und nachdem du ihm erläutert hast, welches Symbol
                    fortan deinen Körper zieren soll, bittet er dich, die entsprechende Stelle freizumachen...`n`n`n`n
                    `b`4Achtung, solltest du dich tätowieren lassen, werden all deine bisherigen, weltlichen Errungenschaften verloren sein. Du wirst ganz von vorne, als "
                    .($session['user']['sex'] ? "Milchmädchen" : "Bauernjunge")." beginnen, allerdings mit ein paar Boni starten.`b`0";
                    
        //Zu guter Letzt noch die Möglichkeit, dass der Spieler bereits alle Tattoos bekommen hat
        else
            $text =    "Kaum hast du den ersten Fuß in das Zelt gesetzt, kommt die Erinnerung an deinen letzten Besuch wieder auf. Der Geruch nach nach Feuer und Asche,
                    Leder und Flachsfasern, Metall und Schweiß, lässt dir den Tag, an dem du dir dein letztes Tintensymbol hast stechen lassen, wie gestern vorkommen.
                    Der vage Geruch nach verbrannter Haut lässt dich wieder ins Hier und Jetzt zurückkehren. Mit großen Schritten gehst du auf Jesco zu und begrüßt
                    ihn mit einem strammen Händedruck.`n
                    `r\"Lässt dich auch mal wieder blicken?!\"`9, richtet er mit einem knappen Nicken das Wort an dich. So vefallt ihr in ein kurzes Gespräch, denn viel
                    mehr kann Jesco nicht mehr für dich tun, da deinen Leib bereits sämtliche Motive zieren, welche er anzubieten hat.`0";
                    
        output($text);
        
        if($session['user']['herotattoo'] <= 10)
            addnav("Tätowieren lassen","legendenlichtung.php?op=tattoo");
        
        addnav("Sonstiges");
        addnav("Zelt verlassen","legendenlichtung.php");
        
        break;
        
    /*-------------------------------------------
    * Der Schmied                                *
    * Permanente Waffen & Rüstungen                *
    * Aber für ordentlich Kohle ;)                *
    -------------------------------------------*/
    case "schmied":
        place(1);
        
        //Der Spieler hat sich entschieden, sich eine Waffe machen zu lassen
        if($_GET['action'] == 1)
        {
            output("`rHadrian schaut dich aufmerksam an und nickt schließlich.`nWie stark soll deine Waffe werden?
                    `n`n`n`i`)Pro Angriffspunkt der Waffe musst du `^".$goldcost."`) Gold, `#".$gemscost."`7 Eldesteine und
                    `4".$dpcost."`) Donationpoints bezahlen!`i`n`0");
                    
            //Name und Angriffswert der Waffe können eingegeben werden
            output('<form action="legendenlichtung.php?op=confirm&id=1" method="POST">
                        Angriffswert: <input name="atk"> <br />
                        Name: <input name="name" size="100" maxlength="5000">
                        <input type="submit" value="kaufen">
                    </form>', true);    
                    
            addnav("","legendenlichtung.php?op=confirm&id=1");
            addnav("Zurück","legendenlichtung.php?op=schmied");
        }
        //Der Spieler sich entschieden, sich eine Rüstung machen zu lassen
        else if($_GET['action'] == 2)
        {
            output("`rHadrian schaut dich aufmerksam an und nickt schließlich.`nWie stark soll deine Rüstung werden?
                    `n`n`n`i`)Pro Defensivpunkt der Rüstung musst du `^".$goldcost."`) Gold, `#".$gemscost."`7 Eldesteine und
                    `4".$dpcost."`) Donationpoints bezahlen!`i`n`0");
                    
            //Name und Verteidigungswert der Rüstungen können eingegeben werden
            output('<form action="legendenlichtung.php?op=confirm&id=2" method="POST">
                        Verteidigungswert: <input name="def"> <br />
                        Name: <input name="name" size="100" maxlength="5000">
                        <input type="submit" value="kaufen">
                    </form>', true);    

            addnav("","legendenlichtung.php?op=confirm&id=2");
            addnav("Zurück","legendenlichtung.php?op=schmied");
        }
        //Der Spieler will seine Waffe upgraden
        else if($_GET['action'] == 3)
        {
            output("`rHadrian schaut dich aufmerksam an und nickt schließlich.`nUm wie viel willst du deine Waffe verstärken?
                    `n`n`n`i`)Pro zusätzlichem Angriffspunkt der Waffe musst du `^".$goldcost."`) Gold, `#".$gemscost."`7 Eldesteine und
                    `4".$dpcost."`) Donationpoints bezahlen!`i`n`0");
                    
            output('<form action="legendenlichtung.php?op=confirm&id=3" method="POST">
                    Zusätzliche Angriffspunkte: <input name="upatk"> <br />
                    <input type="submit" value="kaufen">
                    </form>', true);
                    
            addnav("","legendenlichtung.php?op=confirm&id=3");
            addnav("Zurück","legendenlichtung.php?op=schmied");
        }
        //Der Spieler will seine Rüstung upgraden
        else if($_GET['action'] == 4)
        {
            output("`rHadrian schaut dich aufmerksam an und nickt schließlich.`nWie viel stärker soll deine Rüstung werden?
                    `n`n`n`i`)Pro Defensivpunkt der Rüstung musst du `^".$goldcost."`) Gold, `#".$gemscost."`7 Eldesteine und
                    `4".$dpcost."`) Donationpoints bezahlen!`i`n`0");
                    
            //Name und Verteidigungswert der Rüstungen können eingegeben werden
            output('<form action="legendenlichtung.php?op=confirm&id=4" method="POST">
                        Zusätzliche Verteidigungspunkte: <input name="updef"> <br />
                        <input type="submit" value="kaufen">
                    </form>', true);    

            addnav("","legendenlichtung.php?op=confirm&id=4");
            addnav("Zurück","legendenlichtung.php?op=schmied");
        }
        //Der Spieler hat NOCH nichts ausgewählt -> Darum lassen wir ihn das mal tun
        else
        {
            addnav("Neue einzigartige Waffe","legendenlichtung.php?op=schmied&action=1");
            
            if($session['user']['spec_wpn_atk'] != 0)
                addnav("Waffenupgrade","legendenlichtung.php?op=schmied&action=3");
                
            addnav("Neue einzigartige Rüstung","legendenlichtung.php?op=schmied&action=2");
            
            if($session['user']['spec_arm_def'] != 0)
                addnav("Rüstungsupgrade","legendenlichtung.php?op=schmied&action=4");
        }
        
        addnav("Zurück auf die Lichtung","legendenlichtung.php");
        
        break;
        
    /*-------------------------------------------
    * Waffen-/Rüstungskauf abschließen            *
    -------------------------------------------*/
    case "confirm":
    
        //Der Spieler will eine Waffe
        if($_GET['id'] == "1")
        {
            //Fehlerhafte Eingaben abfangen
            if(!is_numeric($_POST['atk']) || $_POST['atk'] <= 0 || $_POST['name'] == "")
                output('`$Fehler bei der Eingabe.`0');
            //Andernfalls geht es weiter    
            else
            {
                //Der Spieler kann sich seine Waffe nicht leisten. Schuft!
                if(($_POST['atk'] * $goldcost) > $session['user']['gold'] || ($_POST['atk'] * $gemscost) > $session['user']['gems'] ||
                    ($_POST['atk'] * $dpcost) > ($session['user']['donation'] - $session['user']['donationspent']))
                    output("`rHadrian sieht dich mit hochgezogenen Augenbrauen an. Ein solch mächtige Waffe kannst du dir noch nicht leisten.");
                //Alles paletti, Waffenstats werden angepasst und Gold, Gems und DPs verringert
                else
                {
                    output("`rHadrian nickt zufrieden und nimmt seine reichliche Bezahlung entgegen. Dann bittet er dich, sein Zelt zu verlassen, um
                            ihm Zeit zu geben, deine neue, einzigartige Waffe anzufertigen.`nEinige Stunden später ruft er dich zu sich herein, auf
                            dass du sein neuestes Meisterwerk bestaunen und entgegennehmen kannst.");
                
                    $session['user']['spec_wpn_atk'] = $_POST['atk'];
                    $session['user']['spec_wpn_name'] = $_POST['name'];
                    $session['user']['weapondmg'] = $_POST['atk'];
                    $session['user']['weapon'] = $_POST['name'];
                    $session['user']['attack'] += $_POST['atk'];
                    $session['user']['gold'] -= ($goldcost * $_POST['atk']);
                    $session['user']['gems'] -= ($gemscost * $_POST['atk']);
                    $session['user']['donationspent'] += ($dpcost * $_POST['atk']);
                    
                    global_log($session['user']['name']." hat sich eine permanente Waffe erstellt: `b".$_POST['atk']."`b ATK", "legendlichtung.php");
                }
            }
        }
        //Der Spieler will eine Rüstung
        else if($_GET['id'] == 2)
        {
            //Fehlerhafte Eingaben abfangen
            if(!is_numeric($_POST['def']) || $_POST['def'] <= 0 || $_POST['name'] == "")
                output('`$Fehler bei der Eingabe.`0');
            //Andernfalls geht es weiter    
            else
            {
                //Zu arm für die Rüstung. Erbärmlich :D
                if(($_POST['def'] * $goldcost) > $session ['user']['gold'] || ($_POST['def'] * $gemscost) > $session ['user']['gems'] ||
                    ($_POST['def'] * $dpcost) > ($session ['user']['donation'] - $session ['user']['donationspent']))
                    output("`rHadrian sieht dich mit hochgezogenen Augenbrauen an. Ein solch mächtige Rüstung kannst du dir noch nicht leisten.");
                //Ging rund, jetzt noch die Werte der Rüstung anpassen und dann die Kosten abziehen
                else
                {
                    output("`rHadrian nickt zufrieden und nimmt seine reichliche Bezahlung entgegen. Dann bittet er dich, sein Zelt zu verlassen, um
                            ihm Zeit zu geben, deine neue, einzigartige Waffe anzufertigen.`nEinige Stunden später ruft er dich zu sich herein, auf
                            dass du sein neuestes Meisterwerk bestaunen und entgegennehmen kannst.");
                
                    $session['user']['spec_arm_def'] = $_POST['def'];
                    $session['user']['spec_arm_name'] = $_POST['name'];
                    $session['user']['armordef'] = $_POST['def'];
                    $session['user']['armor'] = $_POST['name'];
                    $session['user']['defence'] += $_POST['def'];
                    $session['user']['gold'] -= ($goldcost * $_POST['def']);
                    $session['user']['gems'] -= ($gemscost * $_POST['def']);
                    $session['user']['donationspent'] += ($dpcost * $_POST['def']);
                    
                    global_log($session['user']['name']." hat sich eine permanente Rüstung erstellt: `b".$_POST['def']."`b DEF", "legendlichtung.php");
                }
            }
        }
        //Der Spieler will seine Waffe upgraden
        else if($_GET['id'] == 3)
        {
            //Fehlerhafte Eingaben abfangen
            if(!is_numeric($_POST['upatk']) || $_POST['upatk'] <= 0)
                output('`$Fehler bei der Eingabe.`0');
            //Andernfalls geht es weiter    
            else
            {
            //Der Spieler kann sich seine Waffe nicht leisten. Schuft!
                if(($_POST['upatk'] * $goldcost) > $session['user']['gold'] || ($_POST['upatk'] * $gemscost) > $session['user']['gems'] ||
                    ($_POST['upatk'] * $dpcost) > ($session['user']['donation'] - $session['user']['donationspent']))
                    output("`rHadrian sieht dich mit hochgezogenen Augenbrauen an. Ein solch mächtige Waffe kannst du dir noch nicht leisten.");
                //Alles paletti, Waffenstats werden angepasst und Gold, Gems und DPs verringert
                else
                {
                    output("`rHadrian nickt zufrieden und nimmt seine reichliche Bezahlung entgegen. Dann bittet er dich, sein Zelt zu verlassen, um
                            ihm Zeit zu geben, deine Waffe zu verstärken.`nEinige Stunden später ruft er dich zu sich herein, auf
                            dass du deine Waffe entgegennehmen kannst. Prüfend schwingst du sie durch die Luft und kannst förmlich spüren, dass du
                            sie nun noch mächtiger ist, als zuvor.");
                
                    $session['user']['spec_wpn_atk'] += $_POST['upatk'];
                    $session['user']['weapondmg'] += $_POST['upatk'];
                    $session['user']['attack'] += $_POST['upatk'];
                    $session['user']['gold'] -= ($goldcost * $_POST['upatk']);
                    $session['user']['gems'] -= ($gemscost * $_POST['upatk']);
                    $session['user']['donationspent'] += ($dpcost * $_POST['upatk']);
                    
                    global_log($session['user']['name']." hat seine permanente Waffe geupgradet: +`b".$_POST['upatk']."`b ATK", "legendlichtung.php");
                }
            }
        }
        //Der Spieler will seine Rüstung upgraden
        else if($_GET['id'] == 4)
        {
            //Fehlerhafte Eingaben abfangen
            if(!is_numeric($_POST['updef']) || $_POST['updef'] <= 0)
                output('`$Fehler bei der Eingabe.`0');
            //Andernfalls geht es weiter    
            else
            {
            //Der Spieler kann sich seine Waffe nicht leisten. Schuft!
                if(($_POST['updef'] * $goldcost) > $session['user']['gold'] || ($_POST['updef'] * $gemscost) > $session['user']['gems'] ||
                    ($_POST['updef'] * $dpcost) > ($session['user']['donation'] - $session['user']['donationspent']))
                    output("`rHadrian sieht dich mit hochgezogenen Augenbrauen an. Ein solch mächtige Waffe kannst du dir noch nicht leisten.");
                //Alles paletti, Waffenstats werden angepasst und Gold, Gems und DPs verringert
                else
                {
                    output("`rHadrian nickt zufrieden und nimmt seine reichliche Bezahlung entgegen. Dann bittet er dich, sein Zelt zu verlassen, um
                            ihm Zeit zu geben, deine Rüstung zu verstärken.`nEinige Stunden später ruft er dich zu sich herein, auf
                            dass du deine Rüstung entgegennehmen kannst. Als du sie überstreifst, spürst du förmlich, dass sie dich in Zukunft noch
                            besser vor Schäden bewahren wird.");
                
                    $session['user']['spec_arm_def'] += $_POST['updef'];
                    $session['user']['armordef'] += $_POST['updef'];
                    $session['user']['defence'] += $_POST['updef'];
                    $session['user']['gold'] -= ($goldcost * $_POST['updef']);
                    $session['user']['gems'] -= ($gemscost * $_POST['updef']);
                    $session['user']['donationspent'] += ($dpcost * $_POST['updef']);
                    
                    global_log($session['user']['name']." hat seine permanente Waffe geupgradet: +`b".$_POST['updef']."`b DEF", "legendlichtung.php");
                }
            }
        }
        
        addnav("Zurück","legendenlichtung.php?op=schmied");
        addnav("Zurück zur Lichtung","legendenlichtung.php");
        
        break;
        
    /*-------------------------------------------
    * RP is everything!                            *
    -------------------------------------------*/
    case "feuer":
        place(1);
        
        viewcommentary("legendsfire","Rede mit den anderen Legenden:",20);
        
        addnav("Zurück auf die Lichtung","legendenlichtung.php");
        break;
        
    /*-------------------------------------------
    * Das neue Tattoo wird hier vollendet!        *
    -------------------------------------------*/
    case "tattoo":
        
        $text = "`9Als Jesco fertig ist, ist einige Zeit vergangen. Mit einem zufriedenen Murren hält er einen zerkratzten Handspiegel hoch, 
                in dem du sein Werk bewundern kannst.`n`r\"Das ist ".$tattoo[$session['user']['herotattoo']]."\"`9, erklärt er mit dunkler
                Stimme. `r\"".$tattoosdesc[$session['user']['herotattoo']]."";
        
        $session['user']['herotattoo'] ++;
        
        if (!$session['user']['ctitle']){
            $n=$session['user']['name'];
            $session['user']['name']=($session['user']['sex']?"Fremde ":"Fremder ").substr($n,strlen($session['user']['title']));
        }
        $session['user']['title']=($session['user']['sex']?"Fremde":"Fremder");
        $session['user']['level']=1;
        $session['user']['maxhitpoints']=10 * $session['user']['herotattoo'];
        
        /*$session['user']['attack'] = 1;
        $session['user']['defence'] = 1;
    */
        for($i = 1; $i <= floor($session['user']['herotattoo']/2); $i ++)
        {
            if(($i * 2) < count($tattoos))
            {
                $session['user']['attack'] += $i;
                debuglog("Legendenlichtung is adding atk-points".date("Y-m-d H:i:s")."");
            }
            if(($i * 2) < $session['user']['herotattoo'])
            {
                $session['user']['defence'] += $i;
                debuglog("Legendenlichtung is adding def-points".date("Y-m-d H:i:s")."");
            }
        }

        
        $session['user']['gold']=getsetting("newplayerstartgold",0);
        $session['user']['goldinbank']=0;
        $session['user']['experience']=0;
        $session['user']['petid']=0;
        $session['user']['petfeed']="";
        $session['user']['age']=0;
        $session['user']['hashorse']=0;
        $session['user']['dragonpoints']="";
        $session['user']['dragonkills']=0;
        $session['user']['drunkenness']=0;
        $session['user']['specialty']=0;
        $session['user']['darkarts']=0;
        $session['user']['thievery']=0;
        $session['user']['magic']=0;                                       
        $session['user']['fire']=0;                         
        $session['user']['sword']=0;
        $session['user']['nature']=0;
        $session['user']['water']=0;
        $session['user']['wind']=0;
        $session['user']['chaos']=0;
        /*$session['user']['weapon']="Fäuste";
        $session['user']['armor']="Lumpen";
        $session['user']['spec_arm_def'] = 0;
        $session['user']['spec_arm_name'] = '';
        $session['user']['spec_wpn_atk'] = 0;
        $session['user']['spec_wpn_name'] = '';    
        */    
        $session['user']['bufflist']="";
        /*$session['user']['weaponvalue']=0;
        $session['user']['armorvalue']=0;
        */
        $session['user']['resurrections']=0;
        /*$session['user']['weapondmg']=$session['user']['spec_wpn_atk'];
        $session['user']['armordef']=$session['user']['spec_arm_def'];
        $session['user']['attack']=$session['user']['spec_wpn_atk'];
        $session['user']['defence']=$session['user']['spec_arm_def'];
        */
        $session['user']['dragonage']=0;
        $session['user']['deathpower']=0;
        $session['user']['punch']=1;
        $session['user']['bounty']=0;
        //$session['user']['lasthit']=date("Y-m-d H:i:s",strtotime("-".(86500/getsetting("daysperday",4))." seconds"));
        
        debuglog("REBIRTH ".date("Y-m-d H:i:s")."");
        global_log($session['user']['name']." hat sich tätowieren lassen. Neue Anzahl an Tattoos ist: ".$session['user']['herotattoo'], "legendlichtung.php");
        
        output($text);
        
        addnav("Neues Leben beginnen","legendenlichtung.php?op=&newlife=1");
        break;
}

checkday();

page_footer();

?>


