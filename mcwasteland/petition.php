
<?php

require_once("common.php");

if ($_GET['op']=="primer"){

popup_header("Fibel fÃ¼r neue Spieler");

    output("

<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n

`^Willkommen bei Legend of the Green Dragon - Fibel fÃ¼r neue Spieler`n`n

`^`bDer Dorfplatz`b`n`@

Legend of the Green Dragon (LotGD) wird langsam zu einem ordentlich ausgedehnten Spiel mit einer Menge zu erforschen. Es ist leicht sich bei all dem, was man da draussen tun kann, zu verirren,

deshalb sehe den Dorfplatz am besten als das Zentrum des Spiels an. Dieser Bereich ermÃ¶glicht dir den Zugang zu den meisten anderen Gebieten des Spiels - mit einigen Ausnahmen

(wir behandeln diese in wenigen Augenblicken). Wenn du dich jemals irgendwo verlaufen hast, versuch zum Dorfplatz zurÃ¼ck zu kommen und versuche dort, der Lage wieder Herr zu werden.`n

`n

`^`bDein erster Tag`b`n`@

Dein erster Tag in dieser Welt kann sehr verwirrend sein! Du wirst von einer Menge Informationen erschlagen und brauchst dabei fast keine davon. Das ist wahr! Etwas, das du

vielleicht im Auge behalten solltest, sind deine Lebenspunkte (Hitpoints). Diese Information findest du in der \"Vital Info\". Egal, welche SpezialitÃ¤t du gewÃ¤hlt hast, am Ende bist du eine Art Krieger

oder KÃ¤mpfer, und musst lernen zu kÃ¤mpfen. Der beste Weg ist dazu, im Wald Monster zum tÃ¶ten zu suchen. Wenn du einen Gegner gefunden hast, Ã¼berprÃ¼fe ihn gut und stelle

sicher, dass er kein hÃ¶heres Level als du selbst hat. Denn in diesem Fall Ã¼berlebst du den Kampf vielleicht nicht. Denke immer daran, dass du jederzeit versuchen kannst, zu fliehen

aber das klappt manchmal nicht auf Anhieb! Um eine bessere Chance gegen die Monster im Wald zu haben , kannst du im Dorf

RÃ¼stungen und Waffen kaufen.`n

`n

Wenn du eine Kreatur besiegt hast, wirst du feststellen, dass du mÃ¶glicherweise verletzt bist. Gehe in die HÃ¼tte des Heilers, dort kannst du innerhalb kÃ¼rzester Zeit wieder zusammengeflickt werden.

Solang du Level 1 bist, kostet die Heilung nichts, aber wenn du aufsteigst, wird die Heilung teurer und teurer. Bedenke auch, dass es teurer ist, einzelne Lebenspunkte zu heilen, als spÃ¤ter

mehrere gleichzeitig. Wenn du also etwas Gold sparen willst und nicht allzu schwer verletzt bist, kannst durchaus mal mehr als 1 Kampf riskieren

bevor du zum Heiler rennst.`n

`n

Nachdem du ein paar Monster gekillt hast, solltest du mal im Dorf in Bluspring's Trainingslager vorbeischauen und mit deinem Meister reden. Er wird dir sagen,

ob du bereit bist, ihn herauszufordern. Und wenn du bereit bist, sorge dafÃ¼r, dass du ihn auch besiegst (als vorher heilen)! Dein Meister wird dich nicht tÃ¶ten wenn du verlierst,

stattdessen gibt er dir eine komplette Heilung und schickt dich wieder auf den Weg.",true);

    if (getsetting("multimaster",1) == 0) {

        output(" Du kannst deinen Meister nur einmal am Tag herausfordern.");

    }

output("

`n

`n

`^`bTod`b`n`@

Der Tod ist ein natÃ¼rlicher Teil in jedem Spiel, das irgendwelche KÃ¤mpfe enthÃ¤lt. In Legend of the Green Dragon ist der Tod nur ein vorÃ¼bergehender Zustand. Wenn du stirbst, verlierst

du normalerweise alles Gold, das du dabei hast (Gold auf der Bank ist sicher!) und etwas von deiner gesammelten Erfahrung. Wenn du tot bist, kannst du das Land der Schatten und den Friedhof erforschen.

Auf dem Friedhof wirst du Ramius, den Gott der Toten finden. Er hat einige Dinge, die du fÃ¼r ihn tun kannst, und als Gegenleistung

wird er dir spezielle KrÃ¤fte oder Gefallen gewÃ¤hren. Der Friedhof ist einer der PlÃ¤tze, die du vom Dorfplatz aus nicht erreichen kannst. Umgekehrt kommst du nicht ins Dorf

solange du tot bist!`n

`n

Solang es dir nicht gelingt, Ramius davon zu Ã¼berzeugen, dich wieder zu erwecken, bleibst du tot - zumindest bis zum nÃ¤chsten Spieltag. Es gibt ".getsetting("daysperday",2)." Spieltage pro echtem Tag. Diese Tage fangen an,

sobald die Uhr im Dorf Mitternacht zeigt.`n

`n

`^`bNeue Tage`b`n`@

Wie oben erwÃ¤hnt, gibt es ".getsetting("daysperday",2)." Spieltage pro echtem Tag. Diese Tage fangen an, sobald die Uhr im Dorf Mitternacht zeigt.  Wenn dein neuer Tag anfÃ¤ngt

werden dir neue WaldkÃ¤mpfe (Runden), Zinsen bei der Bank (wenn der Bankier mit deiner Leistung zufrieden ist) gewÃ¤hrt, und viele deiner anderen

Werte werden aufgefrischt. Ausserdem wirst du wiederbelebt, falls du tot warst. Wenn du ein paar Spieltage nicht einloggst, bekommst du die verpassten Spieltage

nicht beim nÃ¤chsten Login zurÃ¼ck. Du bist wÃ¤hrend deiner Abwesenheit sozusagen nicht am Geschehen dieser Welt beteiligt

WaldkÃ¤mpfe, PvP-KÃ¤mpfe, Spezielle FÃ¤higkeiten und andere Dinge, die sich tÃ¤glich zurÃ¼cksetzen, summieren sich

NICHT Ã¼ber mehrere Tage auf.`n

`n",true);

if (getsetting("pvp",1)){

output("

`^`bPvP (Player versus Player - Spieler gegen Spieler)`b`n`@

Legend of the Green Dragon enthÃ¤lt ein PvP-Element (PvP=Player vs. Player = Spieler gegen Spieler), wo Spieler andere Spieler angreifen kÃ¶nnen. Als neuer Spieler bist du die ersten ".getsetting("pvpimmunity",5) . " Spieltage, oder bis du " . getsetting("pvpminexp",1500) . ", Erfahrungspunkte gesammelt hast immun gegen Angriffe - es sei denn, du

greifst selbst einen anderen Spieler an, dann verfÃ¤llt deine ImmunitÃ¤t. Einige Server haben die PvP-Funktion deaktiviert, dort kannst du also Ã¼berhaupt nicht angegriffen werden (und auch selbst nicht angreifen). Du

kannst im Dorf erkennen, ob PvP mÃ¶glich ist, wenn es dort \"KÃ¤mpfe gegen andere Spieler\" gibt. Gibt es das nicht, ist PvP deaktiviert.`n

Auf diesem Server hast du ausserdem die MÃ¶glichkeit, auch nach der Schonzeit ImmunitÃ¤t vor PvP-Angriffen zu erlangen (nicht jeder mag PvP). NÃ¤heres dazu erfÃ¤hrst du in der JÃ¤gerhÃ¼tte.`n

`n

Wenn du bei einem PvP-Kampf stirbst, verlierst du alles Gold, das du bei dir hast, und " . getsetting("pvpdeflose", 5) . "% deiner Erfahrungspunkte. Du verlierst keine WaldkÃ¤mpfe und auch sonst nichts. Wenn du selbst jemanden angreifst,

kannst du " . getsetting("pvpattgain", 10) . "% seiner Erfahrungspunkte und all sein Gold bekommen. Wenn du aber verlierst, verlierst du selbst " . getsetting("pvpattlose", 15) . "% deiner Erfahrung und alles Gold.

Wenn dich jemand angreift und verliert, bekommst du sein Gold und " . getsetting("pvpdefgain", 10) . "% seiner Erfahrungspunkte. Du kannst nur jemanden angreifen, der etwa dein Level hat

also keine Angst, dass dich mit Level 1 ein Level 15 Charakter niedermetzelt. Das geht nicht.`n

Du kannst auch nicht von Spielern angegriffen werden, die zwar dein Level, aber einen wesentlich hÃ¶heren Titel haben.`n

`n

Wenn du dir in der Kneipe ein Zimmer nimmst, um dich auszuloggen, schÃ¼tzt du dich vor gewÃ¶hnlichen Angriffen. Der einzige Weg, jemanden in der Kneipe anzugreifen, ist

den Barkeeper zu bestechen, was eine kostspielige Sache sein kann. Zum Ausloggen \"In die Felder verlassen\" (oder sich Ã¼berhaupt nicht ausloggen) bedeutet, dass du von jedem angegriffen werden kannst, ohne dass er Gold dafÃ¼r bezahlen mÃ¼sste. Du

kannst nicht angegriffen werden, solange du online bist, nur wenn du offline bist. Je lÃ¤nger du also spielst, umso sicherer bist du ;-). Ausserdem kann dich niemand mehr angreifen, wenn du bereits bei einem Angriff getÃ¶tet worden bist,

also brauchst du nicht zu befÃ¼rchten, in einer Nacht 30 oder 40 mal niedergemetzelt zu werden. Erst wenn du dich wieder eingeloggt hast, wirst du wieder angreifbar

wenn du getÃ¶tet wurdest.`n

`n",true);

}

output("

`^`bBereit fÃ¼r die neue Welt!`b`n`@

Du solltest jetzt eine ziemlich gute Vorstellung davon haben, wie dieses Spiel in den GrundzÃ¼gen funktioniert, wie du weiterkommst und wie du dich selbst schÃ¼tzt. Es gibt aber noch eine Menge mehr in dieser Welt, also erforsche sie!

Hab keine Angst davor zu sterben, besonders dann nicht, wenn du noch jung bist. Selbst wenn du tot bist, gibt es noch eine Menge zu tun!

",true);



}else if($_GET['op']=="faq3"){

popup_header("Spezielle und technische Fragen");

output("

<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n

`c`bSpezielle und technische Fragen`b`c

`^1.a. Wie kann es sein, dass ich von einen anderen Spieler getÃ¶tet wurde, obwohl ich gerade selbst gespielt habe?`@`n

Der Hauptgrund dafÃ¼r ist, wenn jemand einen Angriff auf dich angefangen hat, wÃ¤hrend du offline warst, ihn aber erst beendet hat, als du online warst. Das kann sogar passieren, wenn du

stundenlang ununterbrochen spielst. Wenn jemand einen Kampf anfÃ¤ngt, zwingt das Spiel ihn, diesen Kampf zu beenden. Wenn du also angegriffen wirst, und der Angreifer schliesst den Browser bevor der Kampf zuende ist,

muss er diesen Kampf bei seinem nÃ¤chsten Login zuende bringen. Du wirst aber immer das wenigere Gold verlieren, wenn du besiegt wirst. Das heisst, wenn du am Anfang des Kampfes 1 Gold hattest, und am Ende 2000,

wird der Angreifer nur 1 Gold bekommen. Ist es andersrum, wird er ebenfalls nur 1 Gold bekommen.`n

`n

`^1.b. Warum wurde ich in den Feldern getÃ¶tet, obwohl ich in der Kneipe geschlafen habe?`@`n

Das ist im Prinzip das selbe wie oben. Es kann sein, dass ein Kampf angefangen wurde, als du in den Feldern warst, aber erst beendet wurde, als du in der Kneipe warst. Denke immer daran

dass du leicht in den Feldern angegriffen werden kannst, wenn du lange Zeit nichts machst, ohne dich auszuloggen. Nach einer gewissen Zeit der InaktivitÃ¤t loggt das Spiel dich automatisch in die Felder aus.

Es ist also eine gute Idee, sich ein Zimmer zu nehmen, wenn ein paar Minuten vom Computer weg muss, damit man nicht so leicht angegriffen werden kann.`n

`n

`^2. Das Spiel erzÃ¤hlt mir, ich akzeptiere keine Cookies. Was sind Cookies und was kann ich tun?`@`n

Cookies (Kekse) sind kleine DatenhÃ¤ppchen, die eine Internetseite auf deinem Computer speichert, damit sie dich von anderen Besuchern unterscheiden kann. Einige Firewalls lassen Cookies nicht durch und viele Browser blockieren Cookies in der Standardeinstellung. Lese im Handbuch oder der Hilfedatei deiner Firewall oder deines Browsers nach, wie du Cookies durchlÃ¤sst, oder durchsuche mal die Optionen und Einstellungen. Es mÃ¼ssen mindestens \"Session Cookies\" akzeptiert werden, aber alle Cookies wÃ¤ren besser. `n

`n

`^3. Kann mich nicht einloggen! Komme nicht auf den Dorfplatz!`@`n

Das liegt vermutlich an den Cookies aus Punkt 2.`n

Wenn du den Internet Explorer 6 verwendest, klicke einfach `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i, trage dort \"`^".(getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])))."`@\" als `iZugelassen`i ein.

Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"VertrauenswÃ¼rdige Sites\" - Sites`i und trÃ¤gst dort die genannte Adresse ein. Alternativ kannst du unter Sicherheit auch einfach `iStandardstufe`i klicken.`n

`n

`^4. Ich habe eine YoM bekommen, aber das versprochene Ereignis (Gold/Edelstein) trat nicht ein.`@`n

Tja, da hat der YoM-Bote wohl den Brief aufgeschlitzt und den wertvollen Inhalt fÃ¼r sich behalten.`nOkok, hier handelt es sich tatsÃ¤chlich um ein Problem im Code. Leider ist dieses Problem frÃ¼hestens in der nÃ¤chsten Version behoben, sodass wir alle wohl damit leben mÃ¼ssen.

Aber es gibt eine MÃ¶glichkeit, das Auftreten dieses Problems zu verhindern: Wenn eine Ãœberweisung/Hochzeit erwartet wird, einfach fÃ¼r ein paar Momente nichts anklicken, oder am besten vorher ausloggen, bis die Daten sicher in der Datenbank stehen.

`n",true);



}else if ($_GET['op']=="faq"){

popup_header("Frequently Asked Questions (FAQ)");

output("

`^Willkommen bei Legend of the Green Dragon. `n

`n`@

Eines Tages wachst du in einem Dorf auf. Du weisst nicht warum. Verwirrt lÃ¤ufst du durch das Dorf, bis du schliesslich auf den Dorfplatz stolperst. Da du nun schonmal da bist, fÃ¤ngst du an, lauter dumme Fragen zu stellen. Die Leute (die aus irgendeinem Grund alle fast nackt sind) werfen dir alles mÃ¶gliche an den Kopf. Du entkommst in eine Kneipe, wo du in der nÃ¤he des Eingangs ein Regal mit FlugblÃ¤ttern findest. Der Titel der BlÃ¤tter lautet: \"Fragen, die schon immer fragen wolltest, es dich aber nie getraut hast\". Du schaust dich um, um sicherzu stellen, dass dich niemand beobachtet, und fÃ¤ngst an zu lesen:`n

`n

\"Du bist also ein Newbie. Willkommen im Club. Hier findest du Antworten auf Fragen, die dich quÃ¤len. Nun, zumindest findest du Antworten auf Fragen, die UNS quÃ¤lten. So, und jetzt lese und lass uns in Ruhe!\" `n

`n

`bInhalt:`b`n

<a href='petition.php?op=rules'>Regeln dieser Welt</a>`n

<a href='petition.php?op=primer'>Fibel fÃ¼r neue Spieler</a>`n

<a href='petition.php?op=faq1'>Fragen zum Gameplay (generell)</a>`n

<a href='petition.php?op=faq2'>Fragen zum Gameplay (Spoiler!)</a>`n

<a href='petition.php?op=faq3'>Technische Fragen und Probleme</a>`n

`n

~Danke,`n

das Management.`n

",true);



}else if($_GET['op']=="rules"){

popup_header("Regeln dieser Welt");

output("

<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n

`^1. Namensgebung`@`n

Gebe deinem Charakter einen Namen, der sich fÃ¼r Rollenspiele eignet. Namen aus dem 'Real Life' sind dafÃ¼r nur bedingt geeignet. AnstÃ¶ÃŸige, obszÃ¶ne, rassenfeindliche und Ã¤hnliche Namen

werden nicht geduldet und der betroffene Charakter sofort gelÃ¶scht. Das gilt auch fÃ¼r die Wahl des Avatars!`n

`n

`^2. Multi-Accounts`@`n

Auf diesem Server darfst du nur 1 Account haben. Da wir nur prÃ¼fen kÃ¶nnen, wieviele Charaktere pro Computer gespielt werden, wird jeder, der auÃŸer dir noch von deinem PC aus spielt, als Multiaccount erkannt.

Bitte beachte, dass wir nicht unterscheiden kÃ¶nnen, ob du 'nur mal' einen Freund an deinem Computer spielen lÃ¤sst! Das kÃ¶nnte sowohl fÃ¼r deinen Freund, wie auch fÃ¼r dich bittere Folgen haben.

Falls mehr als 1 Person von deinem Computer aus spielt, oder du mehr als 1 Account haben willst, (was von unserer Seite aus gesehen das selbe ist,) schicke bitte eine Hilfsanfrage an die Admins.

Wir werden dir fÃ¼r eine kleine Geldspende so viele Accounts erlauben, wie du brauchst. Unbezahlte Multi-Accounts werden ohne weitere Warnung gelÃ¶scht.`n

Bedingung fÃ¼r Multis ist aber nach wie vor, dass die Charaktere nichts miteinander zu tun haben. Du darfst also zwischen deinen Charakteren

weder Gold oder Edelsteine austauschen, noch sie gegenseitig auf die Kopfgeldliste setzen, im selben Haus wohnen lassen oder gegeneinander im PvP antreten lassen.

VerstÃ¶sse gegen diese Regel werden mit dem ZurÃ¼cksetzen des stÃ¤rksten Charakters des Spielers bestraft.`n

FÃ¼r Accounts, die bis zum 15.12.2004 erstellt wurden, gilt die alte Regelung.`n

`n

`^2.b) Warum soll ich fÃ¼r mehr Accounts Geld bezahlen?`@`n

Ganz einfach: Wir bieten dieses Spiel fÃ¼r jeden kostenlos an, und das wird auch so bleiben. Allerdings ist der Betrieb des Servers fÃ¼r uns keineswegs kostenlos. Wenn du viele Charaktere spielst, beanspruchst du mehr Resourcen, die wir bezahlen mÃ¼ssen, als andere Spieler.

Wir wollen, dass mÃ¶glichst viele Spieler etwas von diesem Spiel haben, aber wir wollen auch niemandem grundsÃ¤tzlich verbieten, mehrere Charaktere zu spielen. Die vielzitierten GroÃŸfamilien mit nur einem PC sollen ebenfalls eine Chance haben, hier unterzukommen.

Darum haben wir uns fÃ¼r diese LÃ¶sung entschieden.`n

`n

`^3. PasswÃ¶rter weitergeben oder fÃ¼r Freunde spielen`@`n

Es ist verboten PasswÃ¶rter weiterzugeben. Demzufolge kann auch niemand fÃ¼r einen Freund mitspielen. Spielt jemand trotzdem fÃ¼r einen Freund, werden beide Charaktere als Multi-Account gewertet

 und es gelten die in Punkt 2 genannten EinschrÃ¤nkungen und Strafen. (Siehe dazu auch Regel 8.)`n

`n

`^4. Cheaten, Bugs ausnutzen`@`n

Dieses Spiel befindet sich - speziell auf diesem Server - stÃ¤ndig in der Entwicklung und kann daher Fehler enthalten. Wer eine Schwachstelle oder eine MÃ¶glichkeit zu Cheaten findet, ist verpflichtet, diese dem Admin mitzuteilen. Offensichtliche Fehler sind ebenfalls `isofort`i zu melden, bevor

 durch das Ausnutzen der Fehler grÃ¶ÃŸerer Schaden entstanden ist. Das gilt nicht nur, wenn der Charakter durch den Fehler einen Nachteil hat, sondern auch und ganz besonders, wenn der Charakter dadurch einen Vorteil hÃ¤tte!

 Wenn etwas merkwÃ¼rdig erscheint, oder zu anderen Bereichen in Widerspruch steht, lieber einmal zu oft nachfragen, als es auszunutzen.`n

Gefundene und gemeldete Fehler werden mit Donationpoints belohnt. Cheaten, also das Umgehen von regulierenden MaÃŸnahmen des Spiels durch nicht spielerische Methoden oder das Missachten der Regeln, wird von den GÃ¶ttern bestraft.`n

`n

`^5. Scripts und Sourcecode`@`n

Der PHP Sourcecode auf diesem Server ist zu einem groÃŸen Teil jedem frei zugÃ¤nglich. Den Source zu lesen, um Schwachstellen zu finden, ist erlaubt und erwÃ¼nscht.

 Eventuell gefundene Schwachstellen auszunutzen statt sie zu melden, ist allerdings verboten und fÃ¼hrt frÃ¼her oder spÃ¤ter zur LÃ¶schung der betroffenen Charaktere.

 Es ist nicht erlaubt, Charaktere durch Programme irgendwelcher Art automatisiert zu steuern.`n

`n

`^6. Spam und Werbung`@`n

Spam, Flooding und Ã¤hnliches ist natÃ¼rlich verboten. Wer den Chat 'zumÃ¼llt', fliegt raus.`nIch nehme groÃŸe MÃ¼hen und Kosten auf mich, um diesen Server werbefrei zu halten, da will ich natÃ¼lich nicht,

dass er zur kostenlosen Werbeplattform fÃ¼r andere Seiten verkommt. Links in Chat-Areas werden kommentarlos entfernt. Wenn ihr einen Link unbedingt bekannt geben wollt, nutzt dazu

 bitte die <a href='http://www.anpera.net/forum/links.php' target='_blank'>Links Sektion</a> im Forum.`n

`n

`^7. Umgangston`@`n

Beleidigungen und schlechter Umgangston werden nicht geduldet. NatÃ¼rlich haben Zwerge und Trolle darÃ¼ber unterschiedliche Ansichten als Menschen und Elfen, aber alles was

Ã¼ber das Rollenspiel hinaus geht, sollte in angemessenem Ton stattfinden.`nStreitereien gehÃ¶ren in Mails oder ICQ, aber keinesfalls auf den Dorfplatz.`n

`n

`^8. Haftung`@`n

Absolut keine. Betreten des Servers auf eigene Gefahr. ;)`nDies ist ein privat finanzierter non-profit Dienst! Es gibt keinen Anspruch auf VerfÃ¼gbarkeit des 'Dienstes'.`n

`bAlle Charaktere und Accounts sind Eigentum des Serverbetreibers!`b Der Verkauf eines Accounts (z.B. bei ebay) ist nicht gestattet und der Kauf eines Accounts von Dritten berechtigt nicht zu dessen Nutzung! Das Verschenken von Accounts an Freunde ist nur nach Absprache mit den Admins erlaubt. (Siehe Regel 2 und 3).`n

`n

",true);



}else if($_GET['op']=="faq1"){

popup_header("Allgemeine Fragen");

output("

<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n



`c`bAllgemeine Fragen`b`c

`^1.  Was ist das Ziel des Spiels?`@`n

MÃ¤dls aufreissen.`n

Nein, im ernst. Ziel des Spiels ist es, den grÃ¼nen Drachen zu besiegen.`n

`n

`^2.  Wie finde ich den grÃ¼nen Drachen?`@`n

Gar nicht.`n

Ok, sowas in der Art. Du kannst ihn erst finden, wenn du ein bestimmtes Level erreicht hast. Sobald du soweit bist, wird es offensichtlich sein.`n

`n

`^3.  Wie steigere ich mein Level?`@`n

Sende uns Geld.`n

Nein, sende uns kein Geld - du steigerst dein Level, indem du gegen Monster im Wald kÃ¤mpfst. Sobald du genug Erfahrungspunkte gesammelt hast, kannst du deinen Meister im Dorf herausfordern.`n

`n

Nun, du kannst uns trotzdem Geld schicken (PayPal Link fÃ¼r Programmierer, Anfrage fÃ¼r Server-Admin)`n

`n

`^4.  Warum kann ich meinen Meister nicht besiegen?`@`n

Er ist viel zu gerissen fÃ¼r Deinesgleichen.`n

Hast du ihn gefragt, ob du schon genug Erfahrung hast?`n

Hast du mal versucht, dir eine RÃ¼stung oder bessere Waffen im Dorf zu kaufen?`n

`n

`^5.  Ich habe alle meine ZÃ¼ge aufgebracht. Wie krieg ich mehr?`@`n

Schicke Geld.`n

Nein, leg deinen Geldbeutel weg. Es *gibt* einige Wege, eine oder zwei Extrarunden zu bekommen, aber sonst musst du einfach bis morgen warten. Am nÃ¤chsten Tag wirst du wieder Energie haben.`n

Frag uns nicht, was diese MÃ¶glichkeiten sind - einige Dinge machen Spass, sie selber herauszufinden.`n

`n

`^6.  Wann beginnt ein neuer Tag?`@`n

Gleich nachdem der alte aufhÃ¶rt.`n

`n

`^7.  Arghhh, ihr Typen bringt mich mit euren schlauen Antworten noch um - kÃ¶nnt ihr mir keine direkten Antworten geben?`@`n

Nop.`n

Naja, gut. Neue Tage hÃ¤ngen mit der Uhr im Dorf (und in der Kneipe) zusammen. Wenn die Uhr Mitternacht anzeigt, erwarte den neuen Tag. Wie oft am Tag die Uhr in LoGD Mitternacht zeigt, variiert von Server zu Server. Der BETA-Server hat 4, der SourceForge Server 2 Spieltage pro Kalendertag. Die Anzahl der Tage bestimmt der Admin. Auf diesem Server gibt es ".getsetting("daysperday",2) . " Spieltage pro Kalendertag.`n

`n

`^8.  Irgendwas ist schiefgegangen!!! Wie kann ich euch informieren?`@`n

Schicke Geld.  Noch besser, sende eine Anfrage. In einer Anfrage nach Hilfe sollte aber nicht 'Das geht nicht', 'Ich bin kaputt', oder 'Jo, was geht?' stehen. In einer Hilfeanfrage *sollte* mÃ¶glichst genau und komplett beschrieben werden, *was* nicht funktioniert. Bitte teile uns mit, was passiert ist, wie die Fehlermeldung war (kopiere sie rein), wann sie erschien und alles, was hilfreich sein kÃ¶nnte. \"Das is kaputt\" ist nicht hilfreich. \"Da fliegt immer ein Lachs aus meinem Monitor, wenn ich mich einlogge\" ist wesentlich genauer. Und witziger. Auch wenn wir daran nicht viel Ã¤ndern kÃ¶nnten.`nBitte habe Geduld. Viele Leute spielen das Spiel und wenn die Admins mit 'Jo - was geht?'-Nachrichten ausgelastet sind, dauert es mit der Antwort manchmal eine Weile. `n

`n

`^9.  Was, wenn ich nur 'yo - was geht?' zu sagen habe?`@`n

Wenn du nichts SchÃ¶nes (oder NÃ¼tzliches oder Interessantes oder Kreatives fÃ¼r die Stimmung des Spiels) zu sagen hast, sag einfach garnichts.`n

Aber wenn du mit einer bestimmten Person quatschen willst, schicke eine Mail mit Ye Olde Post Office.`n

`n

`^10.  Wie mache ich 'Aktionen' (emotes)?`@`n

Gebe :: (oder /me )vor deinem Text ein.`n

`n

`^11.  Was ist eine 'Aktion' (emote)?`@`n

`&Offensichtlicheantwort schlÃ¤gt dir in die Weichteile.`n

`@Das ist eine 'Aktion'. Du kannst im Dorf statt nur zu 'sagen' auch 'Aktionen' darstellen.`n

`n

`^12.  Wie bekommt man Farben in den Namen?`@`n

Iss lustige Pilze.`n

Nein, leg die Pilze weg, Farben in Charakternamen zeigen an, dass jemand fÃ¼r den Betaprozess wichtig war. Also als Belohnung fÃ¼r gefundene Fehler, erschaffene Kreaturen, etc., oder mit dem Admin verheiratet zu sein (*hust*Appleshiner*hust*)`n

Du kannst auch Punkte sammeln und dir so selbst einen farbigen Namen verdienen. Mehr dazu findest du in der JÃ¤gerhÃ¼tte.`n

`n

`^13. Moin 41t3r, is es c00l, 411g3m31ne Ch47 WÃ¶rterz und 1337 5p34k im D0rf zu v3rw3nd3n?`@`n

NEIN! Uns zu liebe verwende Buchstaben zum Schreiben, vollstÃ¤ndige Worte und verstÃ¤ndliche Grammatik. BITTE!`n

`n

",true);

}else if($_GET['op']=="faq2"){

popup_header("Allgemeine Fragen mit Spoiler");

output("

<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n

`&(Warnung! Die folgenden FAQs kÃ¶nnten einige Spoiler enthalten. Wenn du also lieber auf eigene Faust entdecken willst, solltest du hier nicht weiter lesen. Dies ist keine Anleitung. Es ist eine SelbsthilfebroschÃ¼re.)`&

`n

`n

`n

`n

`n

`n

`n

`n

`n

`n

`n

`n

`n

`^1.  Wie bekommt man Edelsteine?`@`n

In die Minen mit dir!!`n

Nein, du kannst nicht danach graben. (Ok, du kannst doch, aber nur wenn du GlÃ¼ck hast und die Mine findest. Aber Achtung! Minen kÃ¶nnen gefÃ¤hrlich sein.) Edelsteine kÃ¶nnen bei zufÃ¤lligen \"Besonderen Ereignissen\" im Wald gefunden werden. Wenn du oft genug spielst, stolperst du an einigen Punkten ganz sicher Ã¼ber welche. Gelegentlich kÃ¶nnen auch Edelsteine bei WaldkÃ¤mpfen gefunden werden.",true);

if (getsetting("topwebid",0) != 0) {

    output("  Zu guterletzt kannst du auch einen kostenlosen Edelstein bekommen, wenn du bei Top Web Games fÃ¼r diesen Server stimmst (siehe Link im Dorf).");

}

output("

`n

`n

`^2.  Warum scheinen manche Spieler mit niedrigem Level so eine Menge Lebenspunkte zu haben?`@`n

Weil sie grÃ¶sser sind als du.`n

Nein, ehrlich, sie *sind* grÃ¶sser als du. Du wirst auch eines Tages gross sein.`n

`n

`^3.  Hat das was mit den Titeln der Leute zu tun?`@`n

Aber klar!`n

Jedesmal, wenn du den Drachen killst, bekommst du einen neuen Titel und wirst wieder Level 1. Also hatten Spieler mit Titel und niedrigem Level die Chance, sich zu steigern. (Siehe Ruhmeshalle)`n

`n

`^4.  Warum schlÃ¤gt mich dieser alte Mann im Wald stÃ¤ndig mit einem hÃ¤sslichen/hÃ¼bschen Stock?`@`n

Du siehst aus wie eine Pinata!`n

Nein, das ist ein besonderes Ereignis, das dir Charme geben oder nehmen kann.`n

`n

`^5.  Wozu ist Charme gut?`@`n

MÃ¤dls aufreissen.`n

Nun, das *ist* tatsÃ¤chlich der Sinn. Besuche einige Personen in der Kneipe und du wirst diesen Punkt verstehen. Je mehr Charme du hast, umso erfolgreicher wird dein Werben beim Volk ankommen.`n

`n

`^6.  Okay, ich hab den alten Mann im Wald gesehen und er hat mich mit seinem hÃ¤sslichen Stock geschlagen, aber es hieÃŸ, ich wÃ¤re hÃ¤sslicher als sein Stock und der Stock hat einen Charmpunkt verloren. Was ist da los?`@`n

Du bist ganz klar, das abstossendste Wesen auf diesem Planeten. Und wenn du die Person bist, die gerade diese Frage gestellt hat, dann bist du auch noch die dÃ¼mmste. Zieh mal deine eigenen RÃ¼ckschlÃ¼sse. Also wirklich.`n

Okay, wir haben gesagt, du wÃ¤rst der DÃ¼mmste, also: Es bedeutet, dass du gerade 0 Charmpunkte hast.`n

`n

`^7.  Wie kann ich meinen Charme sehen?`@`n

Schau ab und zu mal in den Spiegel.`n

Wir scherzen - es gibt keinen Spiegel. Du musst einen Freund fragen, wie du heute aussiehst. Die Antwort kann ungenau sein, aber sie gibt dir einen Anhaltspunkt, wie es mit dir steht.`n

`n

`^8.  Wie kommen wir in andere DÃ¶rfer?`@`n

Nimm den Zug.`n

TatsÃ¤chlich gibt andere DÃ¶rfer erst in der nÃ¤chsten Version. Jede ErwÃ¤hnung anderer DÃ¶rfer oder LÃ¤nder (z.B. Eythgim folks im Wald) dient nur dazu, dem Spiel mehr Tiefe zu geben. `n

`n

`^9. Was ist Ehre oder Ansehen?`@`n

In diesem Spiel kommt es nicht nur darauf an, Punkte zu sammeln, sondern auch darauf, wie man die Punkte bekommt.

Ein ehrenhaftes Vorgehen bringt gewisse Vorteile, allerdings kÃ¶nnte es lÃ¤nger dauern...`n

`n

`^10.  Wie heirate ich?`@`n

Willst du das wirklich?`n

Auf deine Verantwortung. Du kannst hier andere Spieler oder NPCs heiraten. Vorher musst du allerdings das Herz deiner AuserwÃ¤hlten oder deines AuserwÃ¤hlten durch Flirten erobern.

 Verheiratete Spieler haben einen kleinen Vorteil gegenÃ¼ber Singles.`n

`n

`^11.  Wer ist das Management?`@`n

Appleshiner und Foilwench haben die Verantwortung fÃ¼r diese FAQ, aber wenn etwas schiefgeht, schicke eine E-Mail an MightyE. Er ist fÃ¼r alles andere verantwortlich. Oder Frage zuerst den Admin des Servers. `n

`n

`^12.  Wie wird man so verdammt attraktiv?`@`n

Durch ne Menge Gesichtsmasken, mein Lieber!! MightyE bevorzugt speziell eine Maske aus Grapefruit Essenz.`n

",true);

}else{

    popup_header("Anfrage fÃ¼r Hilfe");

    if (count($_POST)>0){

        $p = $session['user']['password'];

        unset($session['user']['password']);

        /*

        mail(getsetting("gameadminemail","niemand@localhost"),"LoGD Anfrage",output_array($_POST,"POST:").output_array($session,"Session:"));

        $sql = "SELECT acctid FROM accounts WHERE emailaddress='".getsetting("gameadminemail","postmaster@localhost")."'";

        //output($sql);

        $result = db_query($sql);

        if (db_num_rows($result)==0){

            $sql = "SELECT acctid FROM accounts WHERE superuser>=3";

            $result = db_query($sql);

        }

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            systemmail($row[acctid],"Petition",output_array($_POST),(int)$session[user][acctid]);

        }

        */

        $sql = "INSERT INTO petitions (author,date,body,pageinfo,lastact) VALUES (".(int)$session['user']['acctid'].",now(),\"".addslashes(output_array($_POST))."\",\"".addslashes(output_array($session,"Session:"))."\",NOW())";

        db_query($sql);

        $session['user']['password']=$p;

        output("Deine Anfrage wurde an die Admins gesendet. Bitte hab etwas Geduld, die meisten Admins

        haben Jobs und Verpflichtungen ausserhalb dieses Spiels. Antworten und Reaktionen kÃ¶nnen eine Weile dauern.");



    }else{

        output("<form action='petition.php?op=submit' method='POST'>

        Name deines Characters: <input name='charname' value='".$session['user']['login']."'>`n

        Deine E-Mail Adresse: <input name='email' value='".$session['user']['emailaddress']."'>`n

        Beschreibe dein Problem:`n

        <textarea name='description' cols='30' rows='5' class='input'></textarea>`n

        <input type='submit' class='button' value='Absenden'>`n

        Bitte beschreibe das Problem so prÃ¤zise wie mÃ¶glich. Wenn du Fragen Ã¼ber das Spiel hast,

        check die <a href='petition.php?op=faq'>FAQ</a>.  `nAnfragen, die das Spielgeschehen betreffen, werden

        nicht bearbeitet - es sei denn, sie haben etwas mit einem Fehler zu tun.

        </form>

        ",true);

    }

}

popup_footer();

?>

