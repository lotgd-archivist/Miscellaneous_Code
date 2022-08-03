<?php
require_once "common.php";
// CaptchaImage-Klasse einbinden
require_once 'require/captcha_image.class.php';

// Crypt-Klasse einbinden
require_once 'require/crypt.class.php';

// CaptchaImage-Klasse initialisieren
$GLOBALS['captcha_image_class'] = new captcha_image_class();

// Crypt-Klasse initialisieren
$GLOBALS['crypt_class'] = new crypt_class();

echo "<script type=\"text/javascript\">";
echo "<!-- function popup(){";
echo "window.alert(\"Meldung\",\"Test\"); }//-->";
echo "</script>";

if ($_GET['op']=="primer"){
popup_header("Fibel für neue Spieler");
        output("
<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n
`^Willkommen bei Legend of the Green Dragon - Fibel für neue Spieler`n`n
`^`bDer Dorfplatz`b`n`@
Legend of the Green Dragon (LotGD) wird langsam zu einem ordentlich ausgedehnten Spiel mit einer Menge zu erforschen. Es ist leicht sich bei all dem, was man da draussen tun kann, zu verirren,
deshalb sehe den Dorfplatz am besten als das Zentrum des Spiels an. Dieser Bereich ermöglicht dir den Zugang zu den meisten anderen Gebieten des Spiels - mit einigen Ausnahmen
(wir behandeln diese in wenigen Augenblicken). Wenn du dich jemals irgendwo verlaufen hast, versuch zum Dorfplatz zurück zu kommen und versuche dort, der Lage wieder Herr zu werden.`n
`n
`^`bDein erster Tag`b`n`@
Dein erster Tag in dieser Welt kann sehr verwirrend sein! Du wirst von einer Menge Informationen erschlagen und brauchst dabei fast keine davon. Das ist wahr! Etwas, das du
vielleicht im Auge behalten solltest, sind deine Lebenspunkte (Hitpoints). Diese Information findest du in der \"Vital Info\". Egal, welche Spezialität du gewählt hast, am Ende bist du eine Art Krieger
oder Kämpfer, und musst lernen zu kämpfen. Der beste Weg ist dazu, im Wald Monster zum töten zu suchen. Wenn du einen Gegner gefunden hast, überprüfe ihn gut und stelle
sicher, dass er kein höheres Level als du selbst hat. Denn in diesem Fall überlebst du den Kampf vielleicht nicht. Denke immer daran, dass du jederzeit versuchen kannst, zu fliehen
aber das klappt manchmal nicht auf Anhieb! Um eine bessere Chance gegen die Monster im Wald zu haben , kannst du im Dorf
Rüstungen und Waffen kaufen.`n
`n
Wenn du eine Kreatur besiegt hast, wirst du feststellen, dass du möglicherweise verletzt bist. Gehe in die Hütte des Heilers, dort kannst du innerhalb kürzester Zeit wieder zusammengeflickt werden.
Solang du Level 1 bist, kostet die Heilung nichts, aber wenn du aufsteigst, wird die Heilung teurer und teurer. Bedenke auch, dass es teurer ist, einzelne Lebenspunkte zu heilen, als später
mehrere gleichzeitig. Wenn du also etwas Gold sparen willst und nicht allzu schwer verletzt bist, kannst durchaus mal mehr als 1 Kampf riskieren
bevor du zum Heiler rennst.`n
`n
Nachdem du ein paar Monster gekillt hast, solltest du mal im Dorf in Bluspring's Trainingslager vorbeischauen und mit deinem Meister reden. Er wird dir sagen,
ob du bereit bist, ihn herauszufordern. Und wenn du bereit bist, sorge dafür, dass du ihn auch besiegst (als vorher heilen)! Dein Meister wird dich nicht töten wenn du verlierst,
stattdessen gibt er dir eine komplette Heilung und schickt dich wieder auf den Weg.",true);
        if (getsetting("multimaster",1) == 0) {
                output(" Du kannst deinen Meister nur einmal am Tag herausfordern.");
        }
output("
`n
`n
`^`bTod`b`n`@
Der Tod ist ein natürlicher Teil in jedem Spiel, das irgendwelche Kämpfe enthält. In Legend of the Green Dragon ist der Tod nur ein vorübergehender Zustand. Wenn du stirbst, verlierst
du normalerweise alles Gold, das du dabei hast (Gold auf der Bank ist sicher!) und etwas von deiner gesammelten Erfahrung. Wenn du tot bist, kannst du das Land der Schatten und den Friedhof erforschen.
Auf dem Friedhof wirst du Ramius, den Gott der Toten finden. Er hat einige Dinge, die du für ihn tun kannst, und als Gegenleistung
wird er dir spezielle Kräfte oder Gefallen gewähren. Der Friedhof ist einer der Plätze, die du vom Dorfplatz aus nicht erreichen kannst. Umgekehrt kommst du nicht ins Dorf
solange du tot bist!`n
`n
Solang es dir nicht gelingt, Ramius davon zu überzeugen, dich wieder zu erwecken, bleibst du tot - zumindest bis zum nächsten Spieltag. Es gibt ".getsetting("daysperday",2)." Spieltage pro echtem Tag. Diese Tage fangen an,
sobald die Uhr im Dorf Mitternacht zeigt.`n
`n
`^`bNeue Tage`b`n`@
Wie oben erwähnt, gibt es ".getsetting("daysperday",2)." Spieltage pro echtem Tag. Diese Tage fangen an, sobald die Uhr im Dorf Mitternacht zeigt.  Wenn dein neuer Tag anfängt
werden dir neue Waldkämpfe (Runden), Zinsen bei der Bank (wenn der Bankier mit deiner Leistung zufrieden ist) gewährt, und viele deiner anderen
Werte werden aufgefrischt. Ausserdem wirst du wiederbelebt, falls du tot warst. Wenn du ein paar Spieltage nicht einloggst, bekommst du die verpassten Spieltage
nicht beim nächsten Login zurück. Du bist während deiner Abwesenheit sozusagen nicht am Geschehen dieser Welt beteiligt
Waldkämpfe, PvP-Kämpfe, Spezielle Fähigkeiten und andere Dinge, die sich täglich zurücksetzen, summieren sich
NICHT über mehrere Tage auf.`n
`n",true);
if (getsetting("pvp",1)){
output("
`^`bPvP (Player versus Player - Spieler gegen Spieler)`b`n`@
Legend of the Green Dragon enthält ein PvP-Element (PvP=Player vs. Player = Spieler gegen Spieler), wo Spieler andere Spieler angreifen können. Als neuer Spieler bist du die ersten ".getsetting("pvpimmunity",5) . " Spieltage, oder bis du " . getsetting("pvpminexp",1500) . ", Erfahrungspunkte gesammelt hast immun gegen Angriffe - es sei denn, du
greifst selbst einen anderen Spieler an, dann verfällt deine Immunität. Einige Server haben die PvP-Funktion deaktiviert, dort kannst du also überhaupt nicht angegriffen werden (und auch selbst nicht angreifen). Du
kannst im Dorf erkennen, ob PvP möglich ist, wenn es dort \"Kämpfe gegen andere Spieler\" gibt. Gibt es das nicht, ist PvP deaktiviert.`n
Auf diesem Server hast du ausserdem die Möglichkeit, auch nach der Schonzeit Immunität vor PvP-Angriffen zu erlangen (nicht jeder mag PvP). Näheres dazu erfährst du in der Jägerhütte.`n
`n
Wenn du bei einem PvP-Kampf stirbst, verlierst du alles Gold, das du bei dir hast, und " . getsetting("pvpdeflose", 5) . "% deiner Erfahrungspunkte. Du verlierst keine Waldkämpfe und auch sonst nichts. Wenn du selbst jemanden angreifst,
kannst du " . getsetting("pvpattgain", 10) . "% seiner Erfahrungspunkte und all sein Gold bekommen. Wenn du aber verlierst, verlierst du selbst " . getsetting("pvpattlose", 15) . "% deiner Erfahrung und alles Gold.
Wenn dich jemand angreift und verliert, bekommst du sein Gold und " . getsetting("pvpdefgain", 10) . "% seiner Erfahrungspunkte. Du kannst nur jemanden angreifen, der etwa dein Level hat
also keine Angst, dass dich mit Level 1 ein Level 15 Charakter niedermetzelt. Das geht nicht.`n
Du kannst auch nicht von Spielern angegriffen werden, die zwar dein Level, aber einen wesentlich höheren Titel haben.`n
`n
Wenn du dir in der Kneipe ein Zimmer nimmst, um dich auszuloggen, schützt du dich vor gewöhnlichen Angriffen. Der einzige Weg, jemanden in der Kneipe anzugreifen, ist
den Barkeeper zu bestechen, was eine kostspielige Sache sein kann. Zum Ausloggen \"In die Felder verlassen\" (oder sich überhaupt nicht ausloggen) bedeutet, dass du von jedem angegriffen werden kannst, ohne dass er Gold dafür bezahlen müsste. Du
kannst nicht angegriffen werden, solange du online bist, nur wenn du offline bist. Je länger du also spielst, umso sicherer bist du ;-). Ausserdem kann dich niemand mehr angreifen, wenn du bereits bei einem Angriff getötet worden bist,
also brauchst du nicht zu befürchten, in einer Nacht 30 oder 40 mal niedergemetzelt zu werden. Erst wenn du dich wieder eingeloggt hast, wirst du wieder angreifbar
wenn du getötet wurdest.`n
`n",true);
}
output("
`^`bBereit für die neue Welt!`b`n`@
Du solltest jetzt eine ziemlich gute Vorstellung davon haben, wie dieses Spiel in den Grundzügen funktioniert, wie du weiterkommst und wie du dich selbst schützt. Es gibt aber noch eine Menge mehr in dieser Welt, also erforsche sie!
Hab keine Angst davor zu sterben, besonders dann nicht, wenn du noch jung bist. Selbst wenn du tot bist, gibt es noch eine Menge zu tun!
",true);

}else if($_GET['op']=="faq3"){
popup_header("Spezielle und technische Fragen");
output("
<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n
`c`bSpezielle und technische Fragen`b`c
`^1.a. Wie kann es sein, dass ich von einen anderen Spieler getötet wurde, obwohl ich gerade selbst gespielt habe?`@`n
Der Hauptgrund dafür ist, wenn jemand einen Angriff auf dich angefangen hat, während du offline warst, ihn aber erst beendet hat, als du online warst. Das kann sogar passieren, wenn du
stundenlang ununterbrochen spielst. Wenn jemand einen Kampf anfängt, zwingt das Spiel ihn, diesen Kampf zu beenden. Wenn du also angegriffen wirst, und der Angreifer schliesst den Browser bevor der Kampf zuende ist,
muss er diesen Kampf bei seinem nächsten Login zuende bringen. Du wirst aber immer das wenigere Gold verlieren, wenn du besiegt wirst. Das heisst, wenn du am Anfang des Kampfes 1 Gold hattest, und am Ende 2000,
wird der Angreifer nur 1 Gold bekommen. Ist es andersrum, wird er ebenfalls nur 1 Gold bekommen.`n
`n
`^1.b. Warum wurde ich in den Feldern getötet, obwohl ich in der Kneipe geschlafen habe?`@`n
Das ist im Prinzip das selbe wie oben. Es kann sein, dass ein Kampf angefangen wurde, als du in den Feldern warst, aber erst beendet wurde, als du in der Kneipe warst. Denke immer daran
dass du leicht in den Feldern angegriffen werden kannst, wenn du lange Zeit nichts machst, ohne dich auszuloggen. Nach einer gewissen Zeit der Inaktivität loggt das Spiel dich automatisch in die Felder aus.
Es ist also eine gute Idee, sich ein Zimmer zu nehmen, wenn ein paar Minuten vom Computer weg muss, damit man nicht so leicht angegriffen werden kann.`n
`n
`^2. Das Spiel erzählt mir, ich akzeptiere keine Cookies. Was sind Cookies und was kann ich tun?`@`n
Cookies (Kekse) sind kleine Datenhäppchen, die eine Internetseite auf deinem Computer speichert, damit sie dich von anderen Besuchern unterscheiden kann. Einige Firewalls lassen Cookies nicht durch und viele Browser blockieren Cookies in der Standardeinstellung. Lese im Handbuch oder der Hilfedatei deiner Firewall oder deines Browsers nach, wie du Cookies durchlässt, oder durchsuche mal die Optionen und Einstellungen. Es müssen mindestens \"Session Cookies\" akzeptiert werden, aber alle Cookies wären besser. `n
`n
`^3. Kann mich nicht einloggen! Komme nicht auf den Dorfplatz!`@`n
Wenn du den Internet Explorer 6 verwendest, klicke einfach `iExtras - Internetoptionen - Datenschutz - Bearbeiten`i, trage dort \"`^".(getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])))."`@\" als `iZugelassen`i ein.
Beim Internet Explorer 5 klickst du `iExtras - Internetoptionen - Sicherheit - \"Vertrauenswürdige Sites\" - Sites`i und trägst dort die genannte Adresse ein. Alternativ kannst du unter Sicherheit auch einfach `iStandardstufe`i klicken.
`n
",true);

}else if ($_GET['op']=="faq"){
popup_header("Frequently Asked Questions (FAQ)");
output("
`^Willkommen bei Legend of the Green Dragon. `n
`n`@
Eines Tages wachst du in einem Dorf auf. Du weisst nicht warum. Verwirrt läufst du durch das Dorf, bis du schliesslich auf den Dorfplatz stolperst. Da du nun schonmal da bist, fängst du an, lauter dumme Fragen zu stellen. Die Leute (die aus irgendeinem Grund alle fast nackt sind) werfen dir alles mögliche an den Kopf. Du entkommst in eine Kneipe, wo du in der nähe des Eingangs ein Regal mit Flugblättern findest. Der Titel der Blätter lautet: \"Fragen, die schon immer fragen wolltest, es dich aber nie getraut hast\". Du schaust dich um, um sicherzu stellen, dass dich niemand beobachtet, und fängst an zu lesen:`n
`n
\"Du bist also ein Newbie. Willkommen im Club. Hier findest du Antworten auf Fragen, die dich quälen. Nun, zumindest findest du Antworten auf Fragen, die UNS quälten. So, und jetzt lese und lass uns in Ruhe!\" `n
`n
`bInhalt:`b`n
<a href='petition.php?op=rules'>Regeln dieser Welt</a>`n
<a href='petition.php?op=primer'>Fibel für neue Spieler</a>`n
<a href='petition.php?op=faq1'>Fragen zum Gameplay (generell)</a>`n
<a href='petition.php?op=faq2'>Fragen zum Gameplay (Spoiler!)</a>`n
<a href='petition.php?op=faq3'>Technische Fragen und Probleme</a>`n
<a href='petition.php?op=faq4'>Farben</a>`n
`n
~Danke,`n
das Management.`n
",true);

}else if($_GET['op']=="rules"){
popup_header("Regeln dieser Welt");
output("
<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n
`^1. Namensgebung und Gestaltung`@`n
Gebe deinem Charakter einen Namen, der sich für Rollenspiele und das Mittelalter eignet. Namen aus dem 'Real Life' sind dafür nur bedingt geeignet. Anstößige, obszöne, rassenfeindliche und ähnliche Namen
werden nicht geduldet und der betroffene Charakter sofort gelöscht. Das gilt auch für die Wahl des Avatars!`n
Weiterhin ist es nicht gewünscht, die Chars als Kopie von Roman-, Film- oder anderen Figuren auszugestalten.`n
`n
`^2. Multi-Accounts`@`n
Auf diesem Server darfst du 5 Accounts haben. Bedingung dafür ist aber, dass die Charaktere nichts miteinander zu tun haben, was keinen RP-Hintergrund hat.. Du darfst also zwischen deinen Charakteren
weder Gold oder Edelsteine austauschen, noch sie gegenseitig auf die Kopfgeldliste setzen oder gegeneinander im PvP antreten lassen.
Verstösse gegen diese Regel werden mit dem Löschen der stärksten Charaktere des Spielers bestraft.`n
X-Chars (dauerhafte NPS's oder weiterer Charakter in einer Bio) sind hier nicht erlaubt. Diese dürfen auch nicht in die Bio mit aufgenommen werden. Natürlich sind NPCs erlaubt um das Spiel zu fördern, jedoch sollten die gleichen halt nur ab und an auftreten und nicht dauerhaft zum eigenen Char gemacht werden. Aber wie immer gilt auch hier: Keine Regel ohne Ausnahme. Jedoch müssen diese mit dem Team abgesprochen werden. Also: einfach mal Fragen.`n
Ein Grund für eine Ausnahme wäre zum Beispiel, wenn man einen Char hat, der ab und an mal einen weiteren Char braucht, welcher aber nur ganz selten auftaucht. Bedingung hierfür ist aber auch, daß dieser weitere Char nur selten und unregelmäßig auftritt.`n

Ausnahmen gibt es hier nur für Kinder bis sie nicht mehr vollständig abhängig von ihrer Bezugsperson sind und als eigenständiger Char gespielt werden können. Man beachte hier die 5 Charaktere Regel.`n
`n
`^3. Passwörter weitergeben oder für Freunde spielen`@`n
Es ist verboten Passwörter weiterzugeben. Demzufolge kann auch niemand für einen Freund mitspielen. Spielt jemand trotzdem für einen Freund, werden beide Charaktere als Multi-Account gewertet
 und es gelten die in Punkt 2 genannten Einschränkungen und Strafen. (Siehe dazu auch Regel 8.)`n
`n
`^4. Cheaten, Bugs ausnutzen`@`n
Dieses Spiel befindet sich - speziell auf diesem Server - ständig in der Entwicklung und kann daher Fehler enthalten. Wer eine Schwachstelle oder eine Möglichkeit zu Cheaten findet, ist verpflichtet, diese dem Admin mitzuteilen. Offensichtliche Fehler sind ebenfalls `i`\$sofort`i`@ zu melden, bevor
 durch das Ausnutzen der Fehler größerer Schaden entstanden ist. Das gilt nicht nur, wenn der Charakter durch den Fehler einen Nachteil hat, sondern auch und ganz besonders, wenn der Charakter dadurch einen Vorteil hätte!
 Wenn etwas merkwürdig erscheint, oder zu anderen Bereichen in Widerspruch steht, lieber einmal zu oft nachfragen, als es auszunutzen.`n
Gefundene und gemeldete Fehler werden mit Donationpoints belohnt. Cheaten, also das Umgehen von regulierenden Maßnahmen des Spiels durch nicht spielerische Methoden oder das Missachten der Regeln, wird von den Göttern bestraft.`n
`n
`^5. Scripts und Sourcecode`@`n
Der PHP Sourcecode auf diesem Server ist zu einem großen Teil jedem frei zugänglich. Den Source zu lesen, um Schwachstellen zu finden, ist erlaubt und erwünscht.
 Eventuell gefundene Schwachstellen auszunutzen statt sie zu melden, ist allerdings verboten und führt früher oder später zur Löschung der betroffenen Charaktere.
 Es ist nicht erlaubt, Charaktere durch Programme irgendwelcher Art automatisiert zu steuern.`n
`n
`^6. Spam und Werbung`@`n
Spam, Flooding und ähnliches ist natürlich verboten. Wer den Chat 'zumüllt', fliegt raus.`nIch nehme große Mühen und Kosten auf mich, um diesen Server werbefrei zu halten, da will ich natülich nicht,
dass er zur kostenlosen Werbeplattform für andere Seiten verkommt. Links in Chat-Areas werden kommentarlos entfernt.`n
`n
`^7. Umgangston`@`n
Beleidigungen und schlechter Umgangston werden nicht geduldet. Natürlich haben Zwerge und Trolle darüber unterschiedliche Ansichten als Menschen und Elfen, aber alles was
über das Rollenspiel hinaus geht, sollte in angemessenem Ton stattfinden.`nStreitereien gehören in Mails oder ICQ, aber keinesfalls auf den Dorfplatz.`n
`n
`^8. CS und anderes Ü18-Zeug sowie Verifizierung`@`n
CS ist  erlaubt. Bedingung ist jedoch, daß die Chars über 18 sind. Verstöße werden unnachgiebig geahndet! Zu den hier verbotenen Inhalten gehören: Sodomie, Pädophilie und Nekrophilie. Falls jedoch solcher Spielinhalt festgestellt wird, behält sich die Administration vor, das Play sofort zu löschen, den Spieler zu sperren und gegebenfalls rechtliche Schritte einzuleiten.`nDie Verifikation findet mittels einer Face2Face-Verifikation statt. Hierbei wird ein Bild des Ausweises sowie des Ausweisinhabers mit dem Ausweis und einem handschriftlichen Zettel mit der Aufschrift \"Arda-Logd\" erwartet. Diese sollen an veri@arda-logd.com gesendet werden und werden direkt nach der erfolgten Verifikation gelöscht, so daß ein Zugriff von Dritten vermieden wird. Es sollten alle Angaben bis auf Geburtsdatum, Gültigkeitsdatum und Bild geschwärzt werden.`nAusnahmsweise kann die Verifizierung auch über den Paypal-Button durchgeführt werden, hier soll die für die Registrierung verwendete Email auch die bei Paypal verwendete Email sein, damit man erkennen kann, daß es sich um die gleiche Person handelt.`n
`n
`^9. Haftung`@`n
Absolut keine. Betreten des Servers auf eigene Gefahr. ;)`nEs gibt auch keinen Anspruch auf Verfügbarkeit des 'Dienstes'.`n
`bAlle Charaktere und Accounts sind Eigentum des Serverbetreibers!`b Der Verkauf eines Accounts (z.B. bei ebay) ist nicht gestattet und der Kauf eines Accounts berechtigt nicht zu dessen Nutzung! Das Verschenken von Accounts an Freunde ist nur nach Absprache mit den Admins erlaubt. (Siehe Regel 2).`n`n
`^10. Avatare und Bilder`@`n
Für die Avatare gelten folgende Regeln:`n
- Die Avatare werden direkt bei uns auf dem Server gespeichert, ein entsprechender Link dazu findet sich in den Bioeinstellungen im Profil. Sobald der Avatar freigegeben ist, wird dieser dann auch in der Bio angezeigt. `n
- Jedes Bild im Netz ist rechtlich geschützt. Dies gilt für Künstler/Communities ebenso wie für Funde bei Google. Achtet darauf dass Ihr nur Bilder verwendet, deren Nutzung ausdrücklich z.B. durch eine Lizenz (Creative Commons) gestattet wird. Die Spieler sind für ihre Inhalte haftbar.`n`n

- Werke die wir rechtlich von vornherein verbieten müssen sind:`n
Screenshots von`n
 - Videospielen`n
 - offizielle Renderartworks`n
 - Bezahlbilder`n
 - Illustrationen`n
 - Filme`n`n

 (Fan)Artworks können genutzt werden, sofern dies nicht einer Kopie der darzustellenden Figur dient.`n
 Generell sollten die Quellen der Bilder im OOC direkt angegeben werden.`n
 Hierbei zählt nur die komplette Quellenangabe, Google oder Deviantart oder ähnliches wird nicht akzeptiert. `n
 Die Administration behält sich das Recht vor, Bilder im Zweifelsfall zu verbieten und zu entfernen.`n`n

Folgende Künstler haben die Nutzung ihrer Werke ausdrücklich verboten:`n
 Dorian Cleavenger, Gaia Online, Heise, Kaori Yuki, Kayaga, Lara Jade, Linda Bergkvist, Luis Royo, www.model-kartei.de, STARLET-Modelagentur, Twiggx, Valentina Kallias, Victoria Frances`n`n
`n
`^11. RP-Regeln und Richtlinien`@`n
Hier sind noch ein paar Richtlinien und Regeln, an die man sich halten sollte.`n
`n
Powerplay:`n
Als Powerplay bezeichnet man eine Spielweise, bei der der Playpartner in eine vorbestimmte Handlung hineingepresst wird, ohne eine wirkliche Möglichkeit zu haben, anders zu reagieren. Zum Beispiel so: \"Max haut dem Moritz so auf die Nase, daß diese sofort dick wird und anfängt  zu bluten.\" Besser, man schreibt das so: \"Max holt aus und zielt bei dem Schlag auf die Nase von Moritz.\" Was wir damit sagen wollen: lasst dem Gegenüber bitte immer die Möglichkeit, selbst zu bestimmen, wie er oder sie reagiert. Natürlich gibt es auch Situationen, wo sich Powerplay nicht vermeiden lässt oder es einfach dazu gehört. Aber bitte sprecht das vorher mit dem Partner ab. Ebenso ist es auch Powerplay, die Gedanken des anderen Chars lesen zu können oder auch alles zu wissen. Wenn ihr jemanden auf der Straße trefft, den ihr nicht kennt, wisst ihr ja auch nicht sofort, was er denkt oder fühlt. Genauso verhält es sich mit den Chars hier auf Arda.`n
Ebenso sollten eure Charas nur das wissen, was sie eben erfahren haben und nicht das, was ihr als Spieler wisst.`n
`n
Götter:`n
Ja, wir haben auf Arda auch Götter rumlaufen. Und ja, diese haben auch bestimmte Fähigkeiten. Doch gelten die gleichen Richtlinien auch für die Götterchars. Der einzige Unterschied: sie können Emotionen und ähnliches teilweise erahnen, aber selbst sie können sich da nicht 100% sicher sein. Ansonsten müssen auch sie wie alle anderen Spieler bestimmende Aktionen absprechen.`n
Ebenso sollten bestimmte Aktionen wie Tote wiedererwecken oder Berge o.a. erschaffen eben den Göttern vorbehalten sein. Diese haben schließlich ihre Funktion hier in dieser Welt.`n
`n
Breaks:`n
Falls ihr ein Play nicht zuende spielen könnt oder wollt oder einfach eine Pause machen wollt, so könnt ihr einfach das Play unterbrechen. Hierzu schreibt einfach Break und das jeweilige Datum unter oder hinter den letzten Post. Ihr habt dann den Platz für drei Tage reserviert. Allerdings ist dies nicht an den folgenden Orten zulässig:`n
- Dorfplatz von Arda`n
- Dorfplatz von Symia `n
- Dorfplatz der Zwergenstadt und`n
- Dorfplatz von Zylyma.`n
",true);

}else if($_GET['op']=="faq1"){
popup_header("Allgemeine Fragen");
output("
<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n

`c`bAllgemeine Fragen`b`c
`^1.  Was ist das Ziel des Spiels?`@`n
Mädls aufreissen.`n
Nein, im ernst. Ziel des Spiels ist es, den grünen Drachen zu besiegen.`n
`n
`^2.  Wie finde ich den grünen Drachen?`@`n
Gar nicht.`n
Ok, sowas in der Art. Du kannst ihn erst finden, wenn du ein bestimmtes Level erreicht hast. Sobald du soweit bist, wird es offensichtlich sein.`n
`n
`^3.  Wie steigere ich mein Level?`@`n
Sende uns Geld.`n
Nein, sende uns kein Geld - du steigerst dein Level, indem du gegen Monster im Wald kämpfst. Sobald du genug Erfahrungspunkte gesammelt hast, kannst du deinen Meister im Dorf herausfordern.`n
`n
Nun, du kannst uns trotzdem Geld schicken (PayPal Link für Programmierer, Anfrage für Server-Admin)`n
`n
`^4.  Warum kann ich meinen Meister nicht besiegen?`@`n
Er ist viel zu gerissen für Deinesgleichen.`n
Hast du ihn gefragt, ob du schon genug Erfahrung hast?`n
Hast du mal versucht, dir eine Rüstung oder bessere Waffen im Dorf zu kaufen?`n
`n
`^5.  Ich habe alle meine Züge aufgebracht. Wie krieg ich mehr?`@`n
Schicke Geld.`n
Nein, leg deinen Geldbeutel weg. Es *gibt* einige Wege, eine oder zwei Extrarunden zu bekommen, aber sonst musst du einfach bis morgen warten. Am nächsten Tag wirst du wieder Energie haben.`n
Frag uns nicht, was diese Möglichkeiten sind - einige Dinge machen Spass, sie selber herauszufinden.`n
`n
`^6.  Wann beginnt ein neuer Tag?`@`n
Gleich nachdem der alte aufhört.`n
`n
`^7.  Arghhh, ihr Typen bringt mich mit euren schlauen Antworten noch um - könnt ihr mir keine direkten Antworten geben?`@`n
Nop.`n
Naja, gut. Neue Tage hängen mit der Uhr im Dorf (und in der Kneipe) zusammen. Wenn die Uhr Mitternacht anzeigt, erwarte den neuen Tag. Wie oft am Tag die Uhr in LoGD Mitternacht zeigt, variiert von Server zu Server. Der BETA-Server hat 4, der SourceForge Server 2 Spieltage pro Kalendertag. Die Anzahl der Tage bestimmt der Admin. Auf diesem Server gibt es ".getsetting("daysperday",2) . " Spieltage pro Kalendertag.`n
`n
`^8.  Irgendwas ist schiefgegangen!!! Wie kann ich euch informieren?`@`n
Schicke Geld.  Noch besser, sende eine Anfrage. In einer Anfrage nach Hilfe sollte aber nicht 'Das geht nicht', 'Ich bin kaputt', oder 'Jo, was geht?' stehen. In einer Hilfeanfrage *sollte* möglichst genau und komplett beschrieben werden, *was* nicht funktioniert. Bitte teile uns mit, was passiert ist, wie die Fehlermeldung war (kopiere sie rein), wann sie erschien und alles, was hilfreich sein könnte. \"Das is kaputt\" ist nicht hilfreich. \"Da fliegt immer ein Lachs aus meinem Monitor, wenn ich mich einlogge\" ist wesentlich genauer. Und witziger. Auch wenn wir daran nicht viel ändern könnten.`nBitte habe Geduld. Viele Leute spielen das Spiel und wenn die Admins mit 'Jo - was geht?'-Nachrichten ausgelastet sind, dauert es mit der Antwort manchmal eine Weile. `n
`n
`^9.  Was, wenn ich nur 'yo - was geht?' zu sagen habe?`@`n
Wenn du nichts Schönes (oder Nützliches oder Interessantes oder Kreatives für die Stimmung des Spiels) zu sagen hast, sag einfach garnichts.`n
Aber wenn du mit einer bestimmten Person quatschen willst, schicke eine Mail mit Ye Olde Post Office.`n
`n
`^10.  Wie mache ich 'Aktionen' (emotes)?`@`n
Gebe :: (oder /me )vor deinem Text ein.`n
`n
`^11.  Was ist eine 'Aktion' (emote)?`@`n
`&Offensichtlicheantwort schlägt dir in die Weichteile.`n
`@Das ist eine 'Aktion'. Du kannst im Dorf statt nur zu 'sagen' auch 'Aktionen' darstellen.`n
`n
`^12.  Wie bekommt man Farben in den Namen?`@`n
Iss lustige Pilze.`n
Nein, leg die Pilze weg, Farben in Charakternamen zeigen an, dass jemand für den Betaprozess wichtig war. Also als Belohnung für gefundene Fehler, erschaffene Kreaturen, etc., oder mit dem Admin verheiratet zu sein (*hust*Appleshiner*hust*)`n
Du kannst auch Punkte sammeln und dir so selbst einen farbigen Namen verdienen. Mehr dazu findest du in der Jägerhütte.`n
`n
`^13. Moin 41t3r, is es c00l, 411g3m31ne Ch47 Wörterz und 1337 5p34k im D0rf zu v3rw3nd3n?`@`n
NEIN! Uns zu liebe verwende Buchstaben zum Schreiben, vollständige Worte und verständliche Grammatik. BITTE!`n
`n
",true);
}else if($_GET['op']=="faq4"){
popup_header("Farben");
$sql = "SELECT `code` FROM appoencode WHERE allowed = '1' AND color IS NOT NULL ORDER BY id";
$result = db_query($sql);
output("<table bgcolor='#000000' cellspacing='1' cellpadding='2' align='center'><tr class='trhead'><td>Code</td><td>Farbe</td></tr>",true);
$i = 0;
while ($row = db_fetch_assoc($result)) {
$i++;
$code = "$row[code]";
$color = "`$row[code]"."So sieht diese Farbe aus";
$bgcolor = ($i % 2 == 1 ? "trdark" : "trdark");
output("<tr bgcolor='#000000'><td>$code</td><td>$color</td>",true);
}
output("</table>",true);
}else if($_GET['op']=="faq2"){
popup_header("Allgemeine Fragen mit Spoiler");
output("
<a href='petition.php?op=faq'>Inhaltsverzeichnis</a>`n`n
`&(Warnung! Die folgenden FAQs könnten einige Spoiler enthalten. Wenn du also lieber auf eigene Faust entdecken willst, solltest du hier nicht weiter lesen. Dies ist keine Anleitung. Es ist eine Selbsthilfebroschüre.)`&
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
`^1.  Wie bekommt man Edelsteine?`@`n
In die Minen mit dir!!`n
Nein, du kannst nicht danach graben. (Ok, du kannst doch, aber nur wenn du Glück hast und die Mine findest. Aber Achtung! Minen können gefährlich sein.) Edelsteine können bei zufälligen \"Besonderen Ereignissen\" im Wald gefunden werden. Wenn du oft genug spielst, stolperst du an einigen Punkten ganz sicher über welche. Gelegentlich können auch Edelsteine bei Waldkämpfen gefunden werden.",true);
if (getsetting("topwebid",0) != 0) {
        output("  Zu guterletzt kannst du auch einen kostenlosen Edelstein bekommen, wenn du bei Top Web Games für diesen Server stimmst (siehe Link im Dorf).");
}
output("
`n
`n
`^2.  Warum scheinen manche Spieler mit niedrigem Level so eine Menge Lebenspunkte zu haben?`@`n
Weil sie grösser sind als du.`n
Nein, ehrlich, sie *sind* grösser als du. Du wirst auch eines Tages gross sein.`n
`n
`^3.  Hat das was mit den Titeln der Leute zu tun?`@`n
Aber klar!`n
Jedesmal, wenn du den Drachen killst, bekommst du einen neuen Titel und wirst wieder Level 1. Also hatten Spieler mit Titel und niedrigem Level die Chance, sich zu steigern. (Siehe Ruhmeshalle)`n
`n
`^4.  Warum schlägt mich dieser alte Mann im Wald ständig mit einem hässlichen/hübschen Stock?`@`n
Du siehst aus wie eine Pinata!`n
Nein, das ist ein besonderes Ereignis, das dir Charme geben oder nehmen kann.`n
`n
`^5.  Wozu ist Charme gut?`@`n
Mädls aufreissen.`n
Nun, das *ist* tatsächlich der Sinn. Besuche einige Personen in der Kneipe und du wirst diesen Punkt verstehen. Je mehr Charme du hast, umso erfolgreicher wird dein Werben beim Volk ankommen.`n
`n
`^6.  Okay, ich hab den alten Mann im Wald gesehen und er hat mich mit seinem hässlichen Stock geschlagen, aber es hieß, ich wäre hässlicher als sein Stock und der Stock hat einen Charmpunkt verloren. Was ist da los?`@`n
Du bist ganz klar, das abstossendste Wesen auf diesem Planeten. Und wenn du die Person bist, die gerade diese Frage gestellt hat, dann bist du auch noch die dümmste. Zieh mal deine eigenen Rückschlüsse. Also wirklich.`n
Okay, wir haben gesagt, du wärst der Dümmste, also: Es bedeutet, dass du gerade 0 Charmpunkte hast.`n
`n
`^7.  Wie kann ich meinen Charme sehen?`@`n
Schau ab und zu mal in den Spiegel.`n
Wir scherzen - es gibt keinen Spiegel. Du musst einen Freund fragen, wie du heute aussiehst. Die Antwort kann ungenau sein, aber sie gibt dir einen Anhaltspunkt, wie es mit dir steht.`n
`n
`^8.  Wie kommen wir in andere Dörfer?`@`n
Nimm den Zug.`n
Tatsächlich gibt es keine anderen Dörfer. Jede Erwähnung anderer Dörfer oder Länder (z.B. Eythgim folks im Wald) dient nur dazu, dem Spiel mehr Tiefe zu geben. `n
`n
`^9. Was ist Ehre?`@`n
In diesem Spiel kommt es nicht nur darauf an, Punkte zu sammeln, sondern auch darauf, wie man die Punkte bekommt.
Ein ehrenhaftes Vorgehen bringt gewisse Vorteile, allerdings könnte es länger dauern...`n
`n
`^10.  Wie heirate ich?`@`n
Willst du das wirklich?`n
Auf deine Verantwortung. Du kannst hier andere Spieler oder NPCs heiraten. Vorher musst du allerdings das Herz deiner Auserwählten oder deines Auserwählten durch Flirten erobern.
 Verheiratete Spieler haben einen kleinen Vorteil gegenüber Singles.`n
`n
`^11.  Wer ist das Management?`@`n
Appleshiner und Foilwench haben die Verantwortung für diese FAQ, aber wenn etwas schiefgeht, schicke eine E-Mail an MightyE. Er ist für alles andere verantwortlich. Oder Frage zuerst den Admin des Servers. `n
`n
`^12.  Wie wird man so verdammt attraktiv?`@`n
Durch ne Menge Gesichtsmasken, mein Lieber!! MightyE bevorzugt speziell eine Maske aus Grapefruit Essenz.`n
",true);
}else{
        popup_header("Anfrage für Hilfe");
        if (count($_POST)>0){

    if(($_POST['pet_user_captcha_string'] == $GLOBALS['captcha_image_class']->get_decode_captcha_string($_SESSION['captcha_string']))
       && (strlen($_POST['description']) > 1) && ((strlen($_POST['charname']) > 1) || (strlen($_POST['email']) > 5)) )
    {
                  $p = $session[user][password];
                  unset($session[user][password]);
                  /*
                  mail(getsetting("gameadminemail","niemand@localhost"),"LoGD Anfrage",output_array($_POST,"POST:").output_array($session,"Session:"));
                  $sql = "SELECT acctid FROM accounts WHERE emailaddress='".getsetting("gameadminemail","postmaster@localhost")."'";
                  //output($sql);
                  $result = db_query($sql);
                  if (db_num_rows($result)==0){
                          $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
                          $result = db_query($sql);
                  }
                  for ($i=0;$i<db_num_rows($result);$i++){
                          $row = db_fetch_assoc($result);
                          systemmail($row[acctid],"Petition",output_array($_POST),(int)$session[user][acctid]);
                  }
                  */
                  $sql = "INSERT INTO petitions (author,date,body,pageinfo) VALUES (".(int)$session[user][acctid].",now(),\"".addslashes(output_array($_POST))."\",\"".addslashes(output_array($session,"Session:"))."\")";
                  db_query($sql);
                  $session[user][password]=$p;
                  output("Deine Anfrage wurde an die Admins gesendet. Bitte hab etwas Geduld, die meisten Admins
                  haben Jobs und Verpflichtungen ausserhalb dieses Spiels. Antworten und Reaktionen können eine Weile dauern.");
    }
    else
    {
      output("<form action='petition.php?op='>
      `4`cDeine Anfrage war entweder leer, der Sicherheitscode fehlte oder es wurde weder Charname noch Mailadresse angegeben!!!`n`n`n`c
              `c<input type='submit' class='button' value='Zurück'>`n", true);
    }

  }
  else{
                output("<form action='petition.php?op=submit' method='POST'>
                Name deines Characters: <input name='charname'>`n
                Deine E-Mail Adresse: <input name='email'>`n
                Beschreibe dein Problem:`n
                <textarea name='description' cols='30' rows='5' class='input'></textarea>`n`n
                `c<img src='require/captcha_image.php?<? echo md5(uniqid(rand(), true)) ? >'>`n`n
    Bestätigungscode:
                <input type='text' name='pet_user_captcha_string'>`n`n
                <input type='submit' class='button' value='Absenden'>`n
                Bitte beschreibe das Problem so präzise wie möglich. Wenn du Fragen über das Spiel hast,
                check die <a href='petition.php?op=faq'>FAQ</a>.  `nAnfragen, die das Spielgeschehen betreffen, werden
                nicht bearbeitet - es sei denn, sie haben etwas mit einem Fehler zu tun.
                </form>
                ",true);
        }
}
popup_footer();
?>