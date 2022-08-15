
<?php
require_once('common.php');

page_header();

addnav('Zur Startseite','index.php');

output('
        `d`c`bHerzlich Willkommen!`b`c`d`n
        `n
        `=Auf dieser Seite möchten wir dir Eranya vorstellen. Du siehst unter anderem einen kurzen Überblick über einige Regeln, aber wir empfehlen dir diese noch einmal genau durchzulesen.`n
        Es besteht später kein Anspruch auf Ersatz, wegen Regelverstößen gelöschte Charaktere werden nicht wiederhergestellt.`n
        `n
        Der Name, den du wählen sollst, darf keinen Titel enthalten (Beispiele: Lord, Graf, Meister, ... etc.) und genauso wenig eine Beschreibung sein (Scharfes Schwert, grüner Hund, ...).`n
        Wir empfehlen Namen, die ein wenig nach `bMittelalter, Mythen oder Sagen`b klingen - jedoch gibt es auch hier Einschränkungen: Namen von Prominenten, Film-/Buchhelden oder Personen aus der Zeitgeschichte sind nicht erwünscht (Legolas, Eragon, Jack Sparrow, etc.)`n
        `n
        Wir freuen uns über jeden aktiven Spieler.`n
        Du kannst `zfünf `=Charaktere haben, die jedoch nicht miteinander agieren dürfen. Wenn du dir mit jemanden einen Internatanschluss teilst, gib uns bitte Bescheid - wir notieren uns das und wissen dann, dass kein Regelverstoß vorliegt.`n
        `n
        Wenn du Fragen hast, schau einmal in die FAQ oder die Drachenbibliothek (die findet man auf dem Stadtplatz). Falls keins von beidem dir weiterhilft, kannst du eine Hörnchenpost (wahlweise auch eine Anfrage) an das Team schreiben - bedenke aber, dass du in beiden Fällen ein wenig Geduld mitbringen solltest.`n
        `n
        `dIm Folgenden eine Kurzfassung der `bRegeln`b:`d`n
        `i`z1. `=Der Name des Charakters muss als Name zu erkennen sein.`n
        `z2. `=Zu eurer eigenen Sicherheit und besonders die eures Charakters ist die Weitergabe von Passwörtern verboten.`n
        `z3. `=Spielfehler ("Bugs") bitten wir zu melden; jegliche Ausnutzung zieht sofortige Konsequenzen nach sich.`n
        `z4.`= Werbung für andere Server ist nicht erwünscht - solltet ihr euren Server für gut halten, empfehlt ihn über die Anfrage als `iPartnerserver.`i`n
        `z5.`= Fragen sowie alles andere, das "OOC" (= Out of Character) ist, gehören in den OOC-Raum, den ihr in der Vital-Info findet.`n
        `z6. `=Selbstverständlich gehören Belästigungen und Ähnliches nicht hierher. Denkt einfach daran: "Was ihr nicht wollt, dass man euch tut, das fügt auch keinem anderen zu."`n
        `z7. `=Vergesst nicht, dass dieser Server jugendfrei zu halten ist - explizite Darstellung von Sex und Gewalt ist unerwünscht.`n
        `z8.`= Wir, Administratoren, Entwickler & Moderatoren, geben uns alle Mühe, einen reibungslosen Serverablauf zu gewährleisten. Sollte es dennoch zu Fehlern kommen, könnt ihr diese melden - wir kümmern uns dann schnellstmöglich darum. Nölen und Quengeln ist dagegen kein sicherer Weg zum Ziel.`n
        `z9. `=Sogenanntes Powerplay, Autoplay oder Ähnliches ist wirklich unerwünscht, es verdirbt anderen den Spaß am Spiel. Falls ihr mit diesen Begriffen nichts anfangen könnt, schaut in der Drachenbibliothek nach einem passenden Buch oder fragt das Admin-Team.`n
        `z10. `=Das Lesen der Regeln wird definitiv empfohlen. "Unwissenheit schützt vor Strafe nicht."`n`i
        `n
         Außerdem bitten wir dich noch, regelmäßig die MotDs zu lesen - dort werden Neuerungen und Ankündigungen öffentlich gemacht.`n
         `n
         Zum Schluss möchten wir dich darauf hinweisen, dass du mit Registrierung unseren <a href="/about.php?op=datenschutzerklaerung" target="_blank">Datenschutzbestimmungen</a> und den hier geltenden Regeln zustimmst. 
        `n`n
        Ein spannendes Leben und viel Spaß in Eranya wünscht Dir`n
        `d`bDas Eranya-Team!`b`d`n
        `n
        `n
        `c<input id="ok_button" type="button" value="Ich habe die Regeln gelesen und akzeptiere sie, Weiter!" onclick=\'document.location="create.php?r='.$_GET['r'].'"\'>`c

                <script type="text/javascript" language="JavaScript">
                        var count = 20;
                        counter();
                        function counter () {
                                if(count == 0) {
                                        document.getElementById("ok_button").value = "Ich habe die Regeln gelesen und akzeptiere sie, Weiter!";
                                        document.getElementById("ok_button").disabled = false;
                                }
                                else {
                                        document.getElementById("ok_button").value = "Weiter! (noch "+count+" Sekunden)";
                                        document.getElementById("ok_button").disabled = true;
                                        count--;
                                        setTimeout("counter()",1000);
                                }
                        }
                </script>
        ',true);
        
page_footer();
        
?>

