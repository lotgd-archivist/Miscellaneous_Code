
<?php
/*
LoGD - Schulerweiterung
26.01.2009
Michael "Luxx" Graus
Martina "Tarisa" Graus
*/

require_once "common.php";

checkday();
get_special_var();

page_header("Die Schule");

// Gargamel inventory system
$session['user']['blockinventory']=1; // don't use the inventory

if ($session[user][locate]!=37){
    $session[user][locate]=37;
    redirect("school.php");
}

if ($_GET[op]=="") {    
    addcommentary();
    output("Mit langsamen Schritten kommst du auf das Haus zu, dass Du schon aus der Ferne gesehen hast. In der Hoffnung auf ein Gasthaus, in dem du Dich von der langen Reise ausruhen kannst, bist du näher gekommen.`n`n");
    output("Du erblickst ein altes Gebäude das ganz offensichtlich dem Stadttor vorgelagert ist, als du gegen die schwerer Eichentür drückst ,um das Gebäude zu betreten , knarrt diese laut in ihren Angeln. Dir schlägt trockene Luft entgegen die gefühlte Jahrhunderte in dem alten Gemäuer gestanden haben muss. Der Innenraum ist erhellt von drei großen Kronleuchtern auf denen hunderte Kerzen stehen. Nahezu alle Wände sind mit Bücherregalen bedeckt und die Raummitte ist ausgefüllt mit ordentlich in Reihen stehenden Schreibtischen, an denen schon unzählige Studenten gesessen haben müssen. Am Schluss fällt Dein Blick auf eine Tafel, auf der mit großen Lettern folgendes geschrieben steht:`n`n");
    output("Willkommen in der Akademie zu Düsterstein. Die Stadtverwaltung legt großen Wert auf die Bildung ihrer Bürger und stellt hier die Lernmittel für alle zur Verfügung. 4 Semester Studium und 4 Prüfungen liegen vor jedem lernwilligen Wesen. Als Belohnung für die bestandenen Prüfungen wird sich schließlich das große Tor zu Düsterstein öffnen.`n`n");
    output("Wenn du gewillt bist das Studium zu vollziehen so begebe dich ins erste Semester.`n`n"); 
    output("Viel Erfolg!`n`n");

    
    viewcommentary("school","Spreche mit anderen Schülern:",25,"sagt");

    addnav("Schule besuchen");
    addnav("Erstes Semester","school.php?op=sem01");
    addnav("Zweites Semester","school.php?op=sem02");
    addnav("Drittes Semester","school.php?op=sem03");
    addnav("Viertes Semester","school.php?op=sem04");
}else if ($_GET[op]=="sem01"){

    output("`c`b Semester 1 `b`c`n`n");
if ($session[user][school]==1){
    output("`cDu triffst in der Akademie zu Düsterstein ein. Hier wirst du lernen was wichtig 
    für deinen weiteren Weg liegt. Du schaust dich um. Die langen Gänge und die vielen Türen 
    zu einzelnen Lehrräumen sind beeindruckend. Nun stehst du endlich hier. Lange hast du 
    dich auf dem Weg begeben um genau hier zu stehen. Du machst dich ran um die ersten Vorlesungen 
    nicht zu verpassen `c`n`n");

    addnav("Vorlesung lauschen","school.php?op=sem01vorlesung");
    addnav("Zur Prüfung anmelden","school.php?op=sem01pruefung");
}else{
    output("`cDu schaust zurück als du noch im ersten Semester gelernt hast!`c` `n`n");
}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem02"){

    output("`c`b Semester 2 `b`c`n`n");
if ($session[user][school]=="2"){
    output("`cGeschafft! Das erste Semester ist überstanden. Du hast viel gelernt, aber nun ist 
    dein Wissensdurst geweckt. Nun willst du es wissen. Du bist endlich kein Neuling mehr, hast
    dir schon einen Namen gemacht und möchtest nun nur noch dein Wissen erweitern.`c`n`n");


    addnav("Vorlesung lauschen","school.php?op=sem02vorlesung");
    addnav("Zur Prüfung anmelden","school.php?op=sem02pruefung");
}else if ($session[user][school]>"2"){
    output("`cDu schaust zurück als du noch im zweiten Semester gelernt hast! `c`n`n");
}else{
    output("`cDu musst erst das erste Semester abgeschlossen haben `c`n`n");
}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem03"){

    output("`c`b Semester 3 `b`c`n`n");
if ($session[user][school]=="3"){
    output("`c\"Das war nicht leicht\" denkst du dir noch. Aber auch das zweite Semester hast 
    du hinter dir gelassen. Es ist wirklich genial was du hier gelernt hast, was dir passiert 
    ist und viel trennt dich nicht mehr, die höhste Stufe der Akademie, zu erreichen. `c`n`n");


    addnav("Vorlesung lauschen","school.php?op=sem03vorlesung");
    addnav("Zur Prüfung anmelden","school.php?op=sem03pruefung");
}else if ($session[user][school]>"3"){
    output("`cDu schaust zurück als du noch im dritten Semester gelernt hast! `c`n`n");
}else{
    output("`cDu musst erst das zweite Semester abgeschlossen haben `c`n`n");
}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem04"){

    output("`c`b Semester 4 `b`c`n`n");
if ($session[user][school]=="4"){
    output("`cEs ist erreicht. Die Krönung aller Semester. Das vierte Semester der Akademie zu 
    Düsterstein. Du bist sehr stolz auf dich. Zu Recht. Das dritte Semester war kein Spasziergang. 
    Nun ist es nur noch ein kleiner Schritt, jenen Weg fortzusetzen den du begonnen hast. 
    Die Abenteuer zu bestreiten die du schon lange suchtest. Du machst dich, mit erhobenen 
    Hauptes, auf dem Weg zu seinen Schulungsräumen`c`n`n");


    addnav("Vorlesung lauschen","school.php?op=sem04vorlesung");
    //addnav("Zur Prüfung anmelden","school.php?op=sem04pruefung");
}else if ($session[user][school]>"4"){
    output("`cDu schaust zurück als du noch im dritten Semester gelernt hast! `c`n`n");
}else{
    output("`cDu musst erst das dritte Semester abgeschlossen haben `c`n`n");
}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem01vorlesung"){

    output("`c`b Vorlesung des 1. Semesters `b`c`n`n");
    
    output("Willkommen zum ersten Semester. Die Schreiber der Texte wollen eines gleich an dieser Stelle vorwegschicken. Wir erheben keinen Anspruch darauf das alles was wir hier sagen der absoluten Richtigkeit entspricht, der Inhalt der Texte entspricht unserem Wissen und unseren Vorstellungen.`n
(Hinweise auf inhaltliche Fehler werden bestimmt zur Kenntnis genommen aber das muss nicht eine Änderung des Textes nach sich ziehen.)`n`n");

    output("Hier im ersten Semester wollen wir euch einen kurzen Abriss über Mittelalter und Rollenspiel im Allgemeinen nahe bringen.`n`n"); 

    output("Mittelalter`n
Wie sich jeder leicht vorstellen kann gab es im Mittelalter einige Sachen noch nicht, ohne die man sich das heutige Leben nicht mehr vorstellen kann. Dennoch spielt die Geschichte des Ortes Düsterstein im Mittelalter, womit einige Sachen einfach entfallen. Was hier an Hand einiger kleinerer Beispiele klar gemacht werden soll. 
Es gab kein fliessendes Wasser und ein Klo nur in Form eines Plumpsklos ähnlich den heute allseits bekannten und beliebten DIXI Chemietoiletten ,nur das es wirklich schlicht ein Loch im Boden war.
Zigaretten wie es sie heute gibt, gab es noch nicht, auch nicht die zum selbst drehen. Natürlich gab es im Mittelalter schon Tabak dieser wurde aber meist in einer Pfeife geraucht und es war oft auch kein Tabak wie im heutigen Sinne , sondern war eher eine Kräutermischung. Auch die heutige Art Menschen anzusprechen gab es damals nicht, es gab die höfliche und eher auf den Adelskreis eingeschränkte Anrede des IHR / EUCH oder das einfach DU. Daraus erschließt sich auch das man nicht jemanden Begrüßen kann in dem man „Hey yo!“ sagt man nutzt Grußworte wie „Ich grüße Euch“oder „Ich grüße Dich “oder schlicht ohne direkte Ansprache „zum Gruße“ Natürlich erwarten wir nicht, dass ihr lediglich diese drei Varianten nutzt, aber wir würden uns wünschen das der Sprachgebrauch im großen und ganzen frei von heute gebräuchlichen Floskeln und Redensarten ist.
Das Mittelalter hat oft den Beinamen finsteres dies aus dem Rückblick mit heutigen Wissen. Die Medizin war damals allenfalls in den Kinderschuhen und bestand aus Aderlass und Kräuterkunst, ein gebrochenes Bein oder ähnliches bedeutete oft den Tod , sei es wegen der Wunde, oder weil sich der Mensch nicht mehr ernähren konnte. Krankheiten und Seuchen waren oft die Folge der schlechten Hygiene. Wir erwarten nicht das ihr dies im vollen Maß in Düsterstein mit Eurer Rolle ausspielt , zum Beispiel in dem ihr mit Euren Charakter einfach auf den Dorfplatz eure Geschäftchen erledigt, aber das ihr Verletzungen auch nicht als Kleinigkeit seht und Dinge wie saubere Straßen und Kleider nicht als etwas alltägliches voraussetzt.`n`n"); 

    output("Rollenspiel`n 
Rollenspiel hier ist Name gleich Programm. Man spielt eine Rollen, so wie in einem Theaterstück nur das Ihr selbst diese Rolle bestimmt und sie immer wieder ändern könnt, natürlich in einem gegeben Rahmen. Ihr spielt Eine Figur die Ihr erschafft, Ihr könnt dieser Figur Vor- und Nachteile geben, (Wir fänden es schön wenn der Charakter nicht nur Vorteile hätte )Charakterzüge die dem Euren entsprechen oder einen himmelweiten Unterschied zu eurer Person machen. Es ist nahezu alles möglich im Rahmen des Mittelalters, vom heldenhaften Ritter, über einen armen Bauern zur allseits geschätzten Kräuterfrau. Die Geschichte der Figur schreibt sich mit jeder Handlung die Ihr spielt weiter. Die Geschichte der Figur schreibt sich mit jedem gespielten Rollenspiel, das ihr macht weiter, und auch die Geschichten dieser Spiele könnt ihr selbst bestimmen zusammen, mit dem entsprechenden Spielpartner kann ein solches RP eine Art Eigenleben entwickeln. Ihr könnt Euch Abenteuer ausdenken die Euer Char noch bestehen muss, oder schon bestanden hat. Der Fanatsie sind fast keine Grenzen gesetzt,bis auf die Regeln und Gesetzen des Dorfes Düsterstein, die Euch später noch näher gebracht werden. Es gibt textbasierende Online Rollenspiele ,oder solche mit Bildchen und 3dGraphik, wie z.b. WOW. Rollenspiel an sich geht aber auch als PEN&PAPER, das würden dann das zusammen sein mehrerer Personen in einem Raum voraussetzen, jeder erstellt mit Stift und Papier einen Charakter und schildert dann verbal was sein Charakter tut, meist gibt es bei diesen Rollenspielen einen Spielleiter der die Rahmenbedingungen vorgibt und die Grundgeschichte erzählt in dessen Rahmen die Spieler agieren können.`n`n"); 

    addnav("zurück","school.php?op=sem01");

}else if ($_GET[op]=="sem01pruefung"){

    output("`c`b Prüfung des 1. Semesters `b`c`n`n");
    output(" <table width='530' height='707' border='0' cellpadding='0' cellspacing='2' background='images/pergaschool.png'>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td valign='top' class='text9'>",true);
    output("`c`bPrüfungsbogen des 1. Semester`c`b`n");
    output("   </td",true);
    output("  </tr>",true);
    output("  <tr>",true);
    //output("`n`n");
    output("   <td valign='top'>",true);
    output("           <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                   <td width='50'>&nbsp;</td>",true);
    output("                   <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage I.`n`n");
    output("</h4>",true);
    output("Du bist in Duesterstein angelangt. In welcher Epoche spielt Duesterstein?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Im Mittelalter</td><td class='text9'>B: In einem Browserspiel</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: In unserem Zeitalter, nur cooler</td><td class='text9'>D: In meinem PC</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Irgendwo nirgendwo</td></tr></table>",true);
    output("                   </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("       </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("         <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                <td width='35'>&nbsp;</td>",true);
    output("                <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage II.`n`n");
    output("</h4>",true);
    output("Welche Anrede ist in Duesterstein gern gesehen?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Jo Gangsta</td><td class='text9'>B: Tach auch, was geht?</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Ihr/euch oder einfach Du</td><td class='text9'>D: Ich brauche Gold, hast was über?</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Bock auf CSS?</td></tr></table>",true);
    output("                </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='55'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage III.`n`n");
    output("</h4>",true);
    output("Was genau bedeutet Rollenspiel?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Etwas das man Rollen muss</td><td class='text9'>B: Muss ich sowas wirklich wissen?</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Wayne? </td><td class='text9'>D: Eine Art Angelspiel</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Eine Rolle zu spielen, mit Vor und Nachteilen</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);

    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='70'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage IV.`n`n");
    output("</h4>",true);
    output("Benötigt man Fantasie um RP spielen zu können?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Ja, sie entwickelt deinen Charakter</td><td class='text9'>B: Nur wenn man Gold benötigt</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Klar, ohne das gibt es kein CSS </td><td class='text9'>D: Ich kann es auch ohne</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Ist das eine Fangfrage?</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='30'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage V.`n`n");
    output("</h4>",true);
    output("Was für eine Art RP ist Duesterstein?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Eines mit geiler 3D Grafik</td><td class='text9'>B: Ein auf Text basierendes</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Ein pen&paper bei mir zuhause </td><td class='text9'>D: Was war nochmal RP?</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Ich werde mich doch woanders anmelden.</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr height='40'><td width='60'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>",true);
    output("                  <tr>",true);
    output("                    <td width='60'>&nbsp;</td>",true);
    output("                    <td valign='top'>",true);
    output("<form action='school.php?op=add1' method='POST'>",true);
    addnav("","school.php?op=add1");
    
            output("`TLösung Frage 1:<select name='sem01loes01'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 2:<select name='sem01loes02'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 3:<select name='sem01loes03'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 4:<select name='sem01loes04'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 5:<select name='sem01loes05'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
              output("<input type='submit' class='button' value='Prüfung abgeben'>",true);
    output("</form>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);


    output("   </td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output(" </table>",true);

    addnav("zurück","school.php?op=sem01");

}else if ($_GET['op']=="add1"){

// Richtig = ACEAB

if ($_POST['sem01loes01']=="1" && $_POST['sem01loes02']=="3" && $_POST['sem01loes03']=="5" && $_POST['sem01loes04']=="1" && $_POST['sem01loes05']=="2"){

        $session[user][school]+=1;
              output("`c`bHURRA!`b Geschaft. Das Semester ist beendet. Als dir der Menthor dir Unterlagen 
          zum nächsten Semester überreichst, kannst du dein Glück kaum fassen. Ein neuer Weg steht für dich 
          offen.`c`n`n");
              output("`c`bBelohnung:`b `4 Zugang zum nächsten Semester`c`n`n");

}else{
              output("`cWunderbar! Du hast es nicht geschafft dieses Semester zu beenden. Wie kann man 
          so unvermögend sein? War es so schwer? Oder bist du nur so faul? Geh mir aus den Augen und
          beginne deine Pergament nochmals zu studieren.`c`n`n");
          output("`c`bStrafe:`b `4 Glück gehabt, die Menthoren sind dir milde gestimmt`c`n`n");

}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem02vorlesung"){

    output("`c`b Vorlesung des 2. Semesters `b`c`n`n");

    output("`cLogd`n
    Ist ein textbasierendes Online-Rollenspiel, ein Vergleich mit pen&paper Spielen im weitesten Sinne wäre möglich und würde dennoch ziemlich hinken. Schaut Euch einmal um, und Ihr werdet so einiges entdecken.`c`n`n");

    output("`cLeveln`n
    Wie in jedem Rollenspiel kann man auch in LogD Erfahrung sammeln um Level aufzusteigen. 
    Um Erfahrung zu sammeln, betritt man den als \"Wald\" oder ähnlich angeschrieben Teil des Spieles. 
    Man kann hier, abhängig vom Level, entscheiden zwischen herumziehen, etwas zum bekämpfen suchen oder 
    Nervenkitzel suchen. Alle enden normalerweise in einem Kampf, jedoch tauchen je nach Wahl unterschiedlich 
    starke Monster auf. Herumziehen bietet die schwächsten, Nervenkitzel suchen die stärksten. Mit etwas 
    zum bekämpfen suchen bekommt man für gewöhnlich Monster, die dem eigenen Level entsprechen. Wenn man 
    genug Erfahrungspunkte gesammelt hat kann man sich im Dorf befindlichen Trainingslager einem Meister 
    stellen, wenn man ihn besiegt, bekommt man Lebenspunkte hinzu und steigt ein Level auf.`n
    Es empfiehlt sich Waffen und bessere Rüstung zu kaufen um sich das Leveln zu erleichtern.`c`n`n"); 


    output("`cDie Schatten`n
    Die Schatten sind das Todesreich. Wenn man einen Waldkampf verliert, stirbt man, das 
    geht in der Regel auch mit Erfahrungspunkteverlust einher, und man landet hier. Auf dem Friedhof 
    kann man andere Kreaturen quälen, um bei dem Totengott, Gefallen zu verdienen. Mit diesen Gefallen 
    kann man, die Seele wiederherstellen lassen, denn sie nimmt Schaden beim Seelen quälen, oder sich 
    auch wiederbeleben lassen. Ein neuer Tag wird einem geschenkt. `c`n`n");

    output("`cDer Drache`n
    Er erwartet einen, wenn man das 15. Level erreicht hat, dann gibt es keine Meister mehr 
    zu bezwingen sondern den Drachen. Er ist ein starker Gegner und sollte nicht auf die leichte Schulter 
    genommen werden. Verliert man gegen den Drachen landet man in den Schatten, gewinnt man wird man auf 
    Level1 zurückgesetzt, erhält einige Boni und kann sich dann von Level 1 wieder hoch arbeiten zu Level 15.  
    Je mehr Drachen man besiegt hat, um so stärker wird ein Charakter was diesen Teil des Spieles angeht. `c`n`n");

    output("`cRollenspiel`n 
    An vielen Spielorten findet man einen Chat, jene Plätze werden nicht als normale Chats gebraucht,
     sondern als Orte für ein Rollenspiel. Man lässt seinen Charakter Aktionen durchführen, interagiert mit 
    anderen Spielern..`n
    Um seinen Charakter zu beschreiben steht eine Biographie zur Verfügung, in der man seinen Charakter
     beschreiben kann. Und so dem Gegenüber eine Möglichkeit gibt sich ein Bild von dem Charakter zu machen. Es
     gibt auch die Option ein Bild in diese Biogrphie einzufügen, wodurch man sich die Beschreibung des Aussehens
     in Teilen sparen kann. Bitte beachtet, dass auf vielen Bildern Copyright ist, und Ihr diese nicht als Avatar
     verwenden dürft Wir behalten uns das Recht vor, auch Bilder, wo es unsicher ist, ob sie ein Copyright haben
     zu entfernen.. Allerdings gilt beim Rollenspiel zu beachten das man als Spieler eines Charakters Sachen die
     sich nicht auf das Aussehen eines anderen Charakters beziehen eben nicht weiß. Um ein solches Rollespiel zu
     spielen braucht ein Spieler ein gutes Vorstellungsvermögen, um sich die beschriebene Situation vorstellen zu
     können und um keine unlogischen Reaktionen zu schildern. Da das Rollenspiel in dieser Form  für gewöhnlich
     nicht moderiert ist, ist feste Disziplin erforderlich. Sogenannte \"PowerGamer\", Spieler, die im Rollenspiel
     mit Superkräften auftauchen, alles kurz und klein schlagen und den Mitspielern nicht einmal die Gelegenheit
     einer Reaktion lässt, sind geächtet und werden auf Servern, die Wert auf Rollenspiel legen, dem entsprechend
     bestraft.`c`n`n");

    output("`cDie Stärke eines Charakters im Rollenspiel muss nicht in Verbindung mit der Stärke des 
    Charakters im Leveln verbunden sein. Beim RP sind der Fantasie wenig Grenzen gesetzt. Man 
    kann der Figur magische Fähigkeiten und ähnliches zuschreiben, sollte dabei jedoch nicht in 
    den Bereich des Power-Gamings verfallen.`c`n`n");

    addnav("zurück","school.php?op=sem02");

}else if ($_GET[op]=="sem02pruefung"){

    output("`c`b Prüfung des 2. Semesters `b`c`n`n");
    output(" <table width='530' height='707' border='0' cellpadding='0' cellspacing='2' background='images/pergaschool.png'>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td valign='top' class='text9'>",true);
    output("`c`bPrüfungsbogen des 2. Semester`c`b");
    output("   </td",true);
    output("  </tr>",true);
    output("  <tr>",true);
    //output("`n`n");
    output("   <td valign='top'>",true);
    output("           <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                   <td width='50'>&nbsp;</td>",true);
    output("                   <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage I.`n`n");
    output("</h4>",true);
    output("Was genau sind die Schatten?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Eine Szene aus Matrix</td><td class='text9'>B: Das Totenreich</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Eine Gilde </td><td class='text9'>D: Das was sich bei Sonne bildet</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Ich nehme den Telefonjoker.</td></tr></table>",true);
    output("                   </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("       </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("         <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                <td width='35'>&nbsp;</td>",true);
    output("                <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage II.`n`n");
    output("</h4>",true);
    output("Was ist zum Empfehlen um leichter zu leveln?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: In die Zukunft zu reisen</td><td class='text9'>B: Waffen und Rüstungen zu kaufen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Mit Freunden gemeinsam zu jagen </td><td class='text9'>D: Zauber zu lernen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Ein paar Bugs auszunutzen</td></tr></table>",true);
    output("                </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='55'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage III.`n`n");
    output("</h4>",true);
    output("Was passiert wenn man Level 15 erreicht hat?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Man darf CS machen</td><td class='text9'>B: Man kann dann gegen andere kämpfen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Man kann sich als Admin bewerben </td><td class='text9'>D: Man wird zum grünen Drachen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Man kann sich mit dem grünen Drachen messen.</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);

    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='70'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage IV.`n`n");
    output("</h4>",true);
    output("Was bedeutet Kreaturen quälen?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Andere Spieler mobben</td><td class='text9'>B: Den Chat vollspamen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Sich bei den Schatten Gefallen verdienen </td><td class='text9'>D: Eine andere Art CS</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Stand das im II. Semester?</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='30'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage V.`n`n");
    output("</h4>",true);
    output("Was kann man im Trainingslager tun?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Meine Talente trainieren</td><td class='text9'>B: Meine Fussballmannschaft trainieren</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Pet's fangen und trainieren </td><td class='text9'>D: Meinen Level steigern</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Andere Spieler die taschen leeren</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output(" <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("  <tr height='40'><td width='60'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>",true);
    output("  <tr>",true);
    output("   <td width='60'>&nbsp;</td>",true);
    output("   <td valign='top'>",true);
    output("<form action='school.php?op=add2' method='POST'>",true);
    addnav("","school.php?op=add2");
    
            output("`TLösung Frage 1:<select name='sem02loes01'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 2:<select name='sem02loes02'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 3:<select name='sem02loes03'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 4:<select name='sem02loes04'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 5:<select name='sem02loes05'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
              output("<input type='submit' class='button' value='Prüfung abgeben'>",true);
    output("</form>",true);
    output("   </td>",true);
    output("  </tr>",true);
    output(" </table>",true);


    output("   </td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output(" </table>",true);

    addnav("zurück","school.php?op=sem02");

}else if ($_GET['op']=="add2"){

// Richtig = BBECD

if ($_POST['sem02loes01']=="2" && $_POST['sem02loes02']=="2" && $_POST['sem02loes03']=="5" && $_POST['sem02loes04']=="3" && $_POST['sem02loes05']=="4"){

        $session[user][school]+=1;
              output("`c`bHURRA!`b Geschaft. Das Semester ist beendet. Als dir der Menthor dir Unterlagen 
          zum nächsten Semester überreichst, kannst du dein Glück kaum fassen. Ein neuer Weg steht für dich 
          offen.`c`n`n");
              output("`c`bBelohnung: `4 Zugang zum nächsten Semester`c`n`n");

}else{
          $session[user][school]-=1;
              output("`cWunderbar! Du hast es nicht geschafft dieses Semester zu beenden. Wie kann man 
          so unvermögend sein? War es so schwer? Oder bist du nur so faul? Geh mir aus den Augen und
          beginne deine Pergament nochmals zu studieren.`c`n`n");
          output("`c`bStrafe:`b `4 Du wirst ein Semester zurückgestuft`c`n`n");

}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem03vorlesung"){

    output("`c`b Vorlesung des 3. Semesters `b`c`n`n");
    
    output("`cWillkommen im Dritten Semester. Nun wird es etwas staubig denn es geht als Regelnpauken.
            Hier kannst du die wichtigsten Gesetzte und Regeln des Dorfes lesen und du solltest sie dir Merken den die Prüfungen werden nicht leichter.`c`n`n");

    output("`c §1) Namensgebung`n
            Da das spiel von der Grundstruktur her eine Mittelalter- Fantasy -Rollenspiel ist, möchten wir darum bitten das die Namen angepasst mittelalterlich und mystisch sind, wir Lassen auch normal heutige Vornamen zu, da diese evtl. auch zu diese Zeit genutzt wurden die das Spiel darstellt, bei den normalen heutigen Namen entscheiden wir allerdings von Fall zu fall ob wir den Namen zu lassen, je nachdem ob wir ihn passend finden oder nicht.
            Die Namen dürfen auch keine Titel wie zum Beispiel: Lady Mord oder Mr. Beinhalten
            Ob eine Name letztendlich zugelassen wird ist Entscheidungssache des Serverbetreibers und des Admin-Teams, d.h. das die Argumentation das man auf anderen Servern aber mit diesem Namen spielen darf nichts bringt.
            Nicht gültig sind Namen wie: Blade, Mr.Dragen , Bloodman usw.`c`n`n");

    output("`c §2) Charaktere und Interaktion`n
            Auf diesem Server ist es Erlaubt zwei Charaktere zu haben, jedoch ist es strengstens verboten das eine Interaktion zwischen den Charakteren stattfindet, als Interaktion zählt das weiter geben von Gold oder Edelsteinen zwischen den eigenen Charakteren das angreifen eines eigenen Charakters.`n`n`
            §2a). Passwörter weitergeben oder Für Freunde spielen`c`n`n");

    output("`cEs ist verboten Passwörter weiterzugeben. Demzufolge kann auch niemand für einen Freund mitspielen. Spielt jemand trotzdem für einen Freund, werden beide Charaktere als Multi-Account gewertet und es gelten die in Punkt 2 genannten Einschränkungen und Strafen.`c`n`n");

    output("`c §3) Cheaten, Bugs ausnutzen`n
            Dieses Spiel befindet sich - speziell auf diesem Server - ständig in der Entwicklung und kann daher Fehler enthalten. Wer eine Schwachstelle oder eine Möglichkeit zu Cheaten findet, ist verpflichtet, diese dem Admin mitzuteilen. Offensichtliche Fehler sind ebenfalls sofort zu melden, bevor durch das Ausnutzen der Fehler größerer Schaden entstanden ist. Das gilt nicht nur, wenn der Charakter durch den Fehler einen Nachteil hat, sondern auch und ganz besonders, wenn der Charakter dadurch einen Vorteil hätte! Wenn etwas merkwürdig erscheint, oder zu anderen Bereichen in Widerspruch steht, lieber einmal zu oft nachfragen, als es auszunutzen.`c`n`n");

    output("`c §4)Versenden von Links und Werbung`n
              Im spiel gibt es die Möglichkeit persönliche Nachrichten (Yoms) zu versenden, es ist jedoch nicht erwünscht das diese Möglichkeit zum weiter verschicken von Werbung und Links benutzt wird. Ebenso ist das Abwerben der Spieler für andere Server nicht erwünscht.`c`n`n");

    output("`c §5)Smilies`n
              Smilies sind generell verboten im Rp anzuwenden. Auch in Yoms sind Smilies nicht gern gesehen, ausser es handelt sich um eine Off-Topic, oder OOC Yom. Off-Topic Yoms müssen als solche im Betreff gekennzeichnet sein, z.B. Hallo (Off-Topic). Yoms, die nicht als Off-Topic gekennzeichnet sind, werden auch nicht als solche gewertet. Ansonsten sind generell Smilies wie z.B. oO, ^^ , ;), :) auf dem Server nicht gern gesehen und der Char wird bei nicht Einhaltung der Regeln bestraft`c`n`n");

    output("`c §6) Umgangston`n
              Beleidigungen und schlechter Umgangston werden nicht geduldet. Natürlich haben Zwerge und Trolle darüber unterschiedliche Ansichten als Menschen und Elfen, aber alles was über das Rollenspiel hinaus geht, sollte in angemessenem Ton stattfinden.
              Streitereien gehören in Mails oder ICQ, aber keinesfalls auf den Dorfplatz.
              Desweiteren solltet ihr euch immer bewusst sein dass dieses Spiel im Mittelalter statt findet und dementsprechend muss auch eure Sprache sein. Natürlich wissen wir nicht alle wie man zu der Zeit gesprochen hat, doch sollte es doch nicht zuviel verlangt sein gewisse Umgangssprache weg zu lassen. Sachen wie Wow oder Outfit oder sonstige Wörter, die es definitiv zu der Zeit nicht gab, sind nicht gern gesehen und die Kommentare werden dementsprechend gelöscht.`c`n`n");
    output("`c §6a) Beleidigungen`n
              Beleidigungen gegen anderer Spieler oder die Admins, die in einen Bereich außerhalb des Spieles fallen (OOC) sind verboten.
              Auch ist das Beleidigen von Spielern im Bereich des Spielen nicht erlaubt.`c`n`n");

    output("`c §7)Verhalten an Öffentlichen Plätzen`n
              Als öffentliche Plätze gelten alle bereiche die für alle Spieler zugängig sind wie, z.B. der Dorfplatz, die Kneipe, die Kapelle usw., ebenfalls als öffentlicher Platz kann einer Haus gelten zu dem mehr als 2 Personen Zugang haben.
              An allen öffentlichen Plätzen ist es nicht erwünscht:`n
              - Das Fäkalsprache benutzt wird (das Schließt Beschimpfungen wie ihr Arschloch etc ein)`n
              - Das sexuelle Handlungen zwischen Charakteren stattfinden`n
              - Das Unterhaltungen stattfinden die sich auf das Weltliche leben beziehen(ooc Gespräch)`n
              - Spamen`n
              - Kleine Gefechte, Kämpfe gehören nun mal auch zum Rp aber wir bitten euch niemanden zu zerstückeln oder die Szenen als zu Blutig zu gestalten.`c`n`n");

    output("`c §7a) CS-Verhalten`n
              Für alle die nicht wissen was dieses Kürzel bedeutet CS= Cyber Sex zu gut Deutsch`c`n`n");

    output("`cZu aller erst solltet ihr euch vor Augenführen das auch minderjährigen sich auf diesem Server befinden und das wir alle rechtlich dazu verpflichtet sich minderjährige vor Sexuellen Handlungen, sind sie nun virtuell oder nicht, schützen müssen. Deshalb ist wie bereits oben erwähnt ist CS an öffentlichen Plätzen verboten. Aber wir bitten euch auch an Privaten Plätzen darauf zu achten das euer Gegenüber volljährig ist. Wir nehmen euch damit in die Verantwort dafür.
              Auch wenn wir es nicht gerne tun so wollen wir gleich an dieser Stelle auch für den CS einige Regeln bzw, Grenzen aufzeigen die nach unserer Meinung nicht überschritten werden sollten.
              Wir wollen auf keinen Fall RP-Sehen in der ein Charakter, sei er männlcih oder weiblich, vergewaltigt werden, das ist schlicht und ergreifend eine Herabwürdigung der Qual die ein Mensch durchmacht dem so was wirklich wieder fahren ist.
              Sklavenspielchen, das bezieht sich nicht nur auf den Bereich des CS, wir wollen das SM spiel an sich nicht verbieten aber es muss zu jederzeit klar ersichtlich sein das das was geschieht von beiden seiten gewollt sind. Das verstümmeln und quälen von Chars fällt dabei aus dem Rahmen und wird von uns nicht geduldet. Ja es mag sein das es im Mittelaltersklave gab und das sie auch so mies behandelt wurden, doch in diesem Punkt würde wir wert drauflegen das ihr etwas mehr in heutigen Maßstäben denkt`c`n`n");


    output("`c §8) Darstellung der Rassen`n
              Wir erheben keinen Anspruch darauf das wir mit Bestimmtheit wissen wie eine bestimmte Rasse richtig gespielt wird, also solltet ihr das auch nicht tun und dadurch untereinander Zoff anfangen.
              Es gibt verschiedene arten eine Rasse darzustellen und dabei ist keine richtiger als die andere.
              Nehmen wir als Beispiel den Vampir, einige spielen ihn mit aufjedenfall ansteckenden Biss einige halten es eher klassisch, da muss der gute Vampir schon dreimal beißen bis er wen infiziert. In dem Punkt möchten wir euch die Artenvielfalt nahe legen die schon Darwin entdeckt hat auch wenn wir nicht darum bitten das überleben des Stärksten nach zu stellen.
              Zeigt bitte einfach etwas Akzeptanz wenn jemand eine Rasse nicht so darstellt wir ihr sie euch vorstellt. Vergesst nicht es ist ein Fantasyrollenspiel.
              Sollte eine Rassen Darstellung zu sehr aus dem Rahmen fallen werden wir Admins und schon darum kümmern. Ein Beispiel für eine Darstellung der wir uns annehmen würde, wäre wenn ein Vampir in der Prallen Sonne auf dem Dorfplatz zu Höchstformen auf läuft.`c`n`n");

    output("`c §9)MotD`n
              Von zeit zu zeit bringen der Serverbetreiber und das Admin-Team eine „Message of the Day“ raus, es ist Pflicht des Spielers diese Nachricht zu lesen, da dort neue Regeln oder Änderungen am Spiel bekannt gegeben werden.`c`n`n");


    output("`cVerstöße gegen die Regeln werden durch den Betreiber und die Admins geahndet, das Strafmass (Pranger oder Bann) hängt von der schwere des Vergehens und dem allgemeinen Verhalten des Spielers ab.
              Des Weiteren behält sich der Serverbetreiber vor die Regeln zu ändern oder zu erweitern, ebenso bleibt die Auslegung und Deutung der Regeln dem Betreiber und den Admins vorbehalten.`c`n`n");

    addnav("zurück","school.php?op=sem03");

}else if ($_GET[op]=="sem03pruefung"){

    output("`c`b Prüfung des 3. Semesters `b`c`n`n");
    output(" <table width='530' height='707' border='0' cellpadding='0' cellspacing='2' background='images/pergaschool.png'>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td valign='top' class='text9'>",true);
    output("`c`bPrüfungsbogen des 3. Semester`c`b");
    output("   </td",true);
    output("  </tr>",true);
    output("  <tr>",true);
    //output("`n`n");
    output("   <td valign='top'>",true);
    output("           <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                   <td width='50'>&nbsp;</td>",true);
    output("                   <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage I.`n`n");
    output("</h4>",true);
    output("Wieviele Charakter darf man auf diesem Server spielen?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Soviele wie ich will</td><td class='text9'>B: Nur einen einzigen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: ca. 3-5 </td><td class='text9'>D: Das waren 2</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Soviele bis ich genug Gold habe</td></tr></table>",true);
    output("                   </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("       </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("         <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                <td width='35'>&nbsp;</td>",true);
    output("                <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage II.`n`n");
    output("</h4>",true);
    output("Wie müsst ihr eure Rasse darstellen`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Wie es im RP Handbuch steht</td><td class='text9'>B: Nach meinem Ermessen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Wie die Admin es wollen </td><td class='text9'>D: Ich spiele nur CS</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Ich spiele sie so wie es Bücher vorgeben und wehe jemand spielt sie anders</td></tr></table>",true);
    output("                </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='55'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage III.`n`n");
    output("</h4>",true);
    output("Welchen Titel darf ich mir bei der Namenssuche geben?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Lord, König oder so</td><td class='text9'>B: Der Funkyman</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Keine </td><td class='text9'>D: Eines das zur Rasse passt</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Das ich auch RP technisch spielen kann</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);

    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='70'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage IV.`n`n");
    output("</h4>",true);
    output("Was tust du wenn du ein Bug findest?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Ihn einem Admin melden</td><td class='text9'>B: Ordentlich ausnutzen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Der Gilde weitergeben </td><td class='text9'>D: Auf dem Dorfplatz schreiben</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Rundmails verfassen und allen Freunden melden.</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='30'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage III.`n`n");
    output("</h4>",true);
    output("Welche Smilies sind denn erlaubt?`n");
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>A: Nur die Standart-Smilies</td><td class='text9'>B: Keine</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>C: Vom Adminteam ausgesuchte </td><td class='text9'>D: Die zum RP passen</td></tr></table>",true);
    output("<table width='400' border='0' cellpadding='0' cellspacing='0'><tr><td width='50%'  class='text9'>E: Nur die zum Style des Charakters auch passen</td></tr></table>",true);
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output(" <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("  <tr height='40'><td width='60'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>",true);
    output("  <tr>",true);
    output("   <td width='60'>&nbsp;</td>",true);
    output("   <td valign='top'>",true);
    output("<form action='school.php?op=add3' method='POST'>",true);
    addnav("","school.php?op=add3");
    
            output("`TLösung Frage 1:<select name='sem03loes01'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 2:<select name='sem03loes02'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 3:<select name='sem03loes03'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 4:<select name='sem03loes04'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 5:<select name='sem03loes05'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
              output("<input type='submit' class='button' value='Prüfung abgeben'>",true);
    output("</form>",true);
    output("   </td>",true);
    output("  </tr>",true);
    output(" </table>",true);


    output("   </td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output(" </table>",true);

    addnav("zurück","school.php?op=sem03");

}else if ($_GET['op']=="add3"){

// Richtig = DBCAB

if ($_POST['sem03loes01']=="4" && $_POST['sem03loes02']=="2" && $_POST['sem03loes03']=="3" && $_POST['sem03loes04']=="1" && $_POST['sem03loes05']=="2"){

        $session[user][school]+=1;
              output("`c`bHURRA!`b Geschaft. Das Semester ist beendet. Als dir der Menthor dir Unterlagen 
          zum nächsten Semester überreichst, kannst du dein Glück kaum fassen. Ein neuer Weg steht für dich 
          offen.`c`n`n");
              output("`c`bBelohnung: `4 Zugang zum nächsten Semester`c`n`n");

}else{
          $session[user][school]-=1;
              output("`cWunderbar! Du hast es nicht geschafft dieses Semester zu beenden. Wie kann man 
          so unvermögend sein? War es so schwer? Oder bist du nur so faul? Geh mir aus den Augen und
          beginne deine Pergament nochmals zu studieren.`c`n`n");
          output("`c`bStrafe:`b `4 Du wirst ein Semester zurückgestuft`c`n`n");

}
    addnav("Zurück","school.php");
}else if ($_GET[op]=="sem04vorlesung"){

        $sql = "SELECT name,login,race,superuser FROM accounts WHERE superuser>1";
        $result = db_query($sql) or die(sql_error($sql));
        $max = db_num_rows($result);
    output("`c`b Vorlesung des 4. Semesters `b`c`n`n");
    output("`c Leider muss ich dir mitteilen das du dieses Semester nicht auf dem normalen Wege 
    lösen kannst. Dieses Spiel steckt leider immer noch in der Betaphase und beinhaltet zuviele 
    Fehler oder ist noch nicht unseren Vorstellungen angepasst. Die Lösung des letzten Semester 
    kannst du nur durch einen Betreiber dieses Servers erhalten. Du kannst ihnen gern eine YoM 
    zusenden und auf eine Antwort warten. Da dieser Server aber noch nicht fertig gestellt ist, 
    hast du nur die Möglichkeit ein wenig Geduld zu haben.Auch kannst du gern deine Emailadresse 
    hinterlassen um ggf. über Neuerungen und Öffnung von Düsterstein Legenden informiert zu werden. 
    Wer für diesen Server verantwortlich ist, kannst du unten sehen:`c`n`n");
    
    for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("`c");
      output("$row[name]`0");
      output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
        output("`c");
        output("`n"); 
      }

    addnav("zurück","school.php?op=sem04");

}else if ($_GET[op]=="sem04pruefung"){

    output("`c`b Prüfung des 4. Semesters `b`c`n`n");
    output(" <table width='530' height='707' border='0' cellpadding='0' cellspacing='2' background='images/pergaschool.png'>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td valign='top' class='text9'>",true);
    output("`c`bPrüfungsbogen des 4. Semester`c`b");
    output("   </td",true);
    output("  </tr>",true);
    output("  <tr>",true);
    //output("`n`n");
    output("   <td valign='top'>",true);
    output("           <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                   <td width='50'>&nbsp;</td>",true);
    output("                   <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage I.`n`n");
    output("</h4>",true);
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("                   </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("       </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("         <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("              <tr>",true);
    output("                <td width='35'>&nbsp;</td>",true);
    output("                <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage II.`n`n");
    output("</h4>",true);
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("                </td>",true);
    output("              </tr>",true);
    output("         </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='55'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage III.`n`n");
    output("</h4>",true);
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);

    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='480' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='70'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage IV.`n`n");
    output("</h4>",true);
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output("             <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("                  <tr>",true);
    output("                    <td width='30'>&nbsp;</td>",true);
    output("                    <td valign='top' class='text9'>",true);
    output("<h4>",true);
    output("Frage V.`n`n");
    output("</h4>",true);
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test`n");
    output("                    </td>",true);
    output("                  </tr>",true);
    output("             </table>",true);
    output("    </td>",true);
    output("   </tr>",true);
    //output("`n`n");
    output("   <tr>",true);
    output("    <td>",true);
    output(" <table width='440' border='0' align='left' cellpadding='0' cellspacing='2'",true);
    output("  <tr height='40'><td width='60'>&nbsp;</td><td valign='top'>&nbsp;</td></tr>",true);
    output("  <tr>",true);
    output("   <td width='60'>&nbsp;</td>",true);
    output("   <td valign='top'>",true);
    output("<form action='school.php?op=add4' method='POST'>",true);
    addnav("","school.php?op=add4");
    
            output("Lösung Frage 1:<select name='sem04loes01'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 2:<select name='sem04loes02'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 3:<select name='sem04loes03'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("Lösung Frage 4:<select name='sem04loes04'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
            output("`nLösung Frage 5:<select name='sem04loes05'>",true);
               output("<option value='1'>Antwort A</option>",true);
              output("<option value='2'>Antwort B</option>",true);
              output("<option value='3'>Antwort C</option>",true);
              output("<option value='4'>Antwort D</option>",true);
              output("<option value='5'>Antwort E</option>",true);
              output("</select>",true);
              output("<input type='submit' class='button' value='Prüfung abgeben'>",true);
    output("</form>",true);
    output("   </td>",true);
    output("  </tr>",true);
    output(" </table>",true);


    output("   </td>",true);
    output("  </tr>",true);
    output("  <tr>",true);
    output("   <td height='60'>&nbsp;</td>",true);
    output("  </tr>",true);
    output(" </table>",true);

    addnav("zurück","school.php?op=sem04");

}else if ($_GET['op']=="add4"){

// Richtig = ADBAE

if ($_POST['sem04loes01']=="1" && $_POST['sem04loes02']=="4" && $_POST['sem04loes03']=="2" && $_POST['sem04loes04']=="1" && $_POST['sem04loes05']=="5"){

        $session[user][school]=0;
              output("`c`bHURRA!`b Geschaft. Das letzte Semester ist beendet. Als dir der Menthor dir Unterlagen 
          zum Abschluss der Akademie überreichst, kannst du dein Glück kaum fassen. Ein neuer Weg steht für dich 
          offen.`c`n`n");
              output("`c`bBelohnung: `4 Zugang zum Dorf`c`n`n");

}else{
          $session[user][school]-=1;
              output("`cWunderbar! Du hast es nicht geschafft dieses Semester zu beenden. Wie kann man 
          so unvermögend sein? War es so schwer? Oder bist du nur so faul? Geh mir aus den Augen und
          beginne deine Pergament nochmals zu studieren.`c`n`n");
          output("`c`bStrafe:`b `4 Du wirst ein Semester zurückgestuft`c`n`n");

}
    addnav("Zurück","school.php");
}


    addnav("Ausserhalb der Schule");    

    if ($session[user][school]=="0") addnav("Ab nach Düsterstein","village.php");
    addnav("Profil","prefs.php");
    addnav("In die Felder (Logout)","login.php?op=mainlogout&wo=0",true);

page_footer();
?> 

