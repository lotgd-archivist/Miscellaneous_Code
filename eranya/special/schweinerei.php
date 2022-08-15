
<?php
// ein Waldspecial angelehnt an das Würfelspiel "Schweinerei" (u.a. (; )
// http://eranya.de
// Autor: Silva

if(!isset($session)) {
        exit();
}

$str_specname = basename(__FILE__);
$session['user']['specialinc'] = $str_specname;
page_header('Wildschweinerei');
output("`n`c`bWildschweinerei!`b`c`n");      # Überschrift wird immer angezeigt
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
if($str_op == 'leave') {
    output("`°Wie bitte, Wildschweine werfen?!? Du zeigst den beiden Männern den Vogel und gehst weiter. Die spinnen, die Jäger!");
    addnav("Weiter","forest.php");
    $session['user']['specialinc'] = '';
} elseif($str_op == 'throw') {
    $row_s = user_get_aei('schweinerei');
    output("`°Wildschweine durch die Gegend werfen? Klingt lustig! Sofort bist du Feuer und Flamme und fackelst nicht lang, sondern pirschst dich an die beiden Schweine vor dir heran und schnappst sie dir, eins mit dem linken, eins mit dem rechten Arm. Die Tiere wissen gar nicht, wie ihnen geschieht, und sind vor Schreck wie erstarrt. Das nutzt du aus, um sie mit Schwung hochzustemmen. Im hohen Bogen fliegen sie durch die Luft und...`n`n");
    $int_rand = e_rand(1,100);
    switch($int_rand) {
        // (Wild)Schweinerei (extrem selten) -> Spieler stirbt
        case 1:
            output("...landen beide wieder auf allen Vieren, aufgetürmt wie die `bBremer Stadtmusikanten`b! Begeistert klatschst du in die Hände. Wenn das nicht der Wurf des Jahrhunderts gewesen ist! Doch als du dich den beiden Jägern zuwendest, erwarten dich zwei misstrauische Blicke. `E\"Denkst du, was ich denke?\"`°, murmelt der kleine, und der andere nickt: `w\"Oh, ja. .. Wildschweinerei!\" `°Ehe du dich versiehst, holt der dicke Jäger aus - und verpasst dir eine Ohrfeige, die du sogar dann noch spürst, als du unsanft in Jarcaths Totenreich landest.");
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = 0;
            addnews($session['user']['name']." `wwurde von einem dicken Jäger erschlagen. Das kam unerwartet...");
            addnav('Och, nö...');
            addnav('Zu den Schatten','shades.php');
        break;
        // Doppelbacke (sehr selten) -> Spieler bekommt 6000 Gold und 6 ES
        case 2:
        case 3:
            output("...landen beide so, dass sie auf ihren Schnauzen und je einem Ohr balancieren. Der kleine Jäger stößt einen anerkennenden Pfiff aus und ruft: `E\"Alle Achtung, Doppelbacke!\" `°Du verstehst nur Bahnhof, doch die `^6000 Gold `°und die `#6 Edelsteine`°, die dir der dicke Jäger in die Hand drückt, nimmst du natürlich sofort.");
            $session['user']['gems'] += 6;
            $session['user']['gold'] += 6000;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 60;
            user_set_aei($row_s);
        break;
        // Doppelschnauze (sehr selten) -> Spieler bekommt 4000 Gold und 4 ES
        case 4:
        case 5:
        case 6:
            output("...landen beide so, dass sie geradezu auf ihren Schnauzen balancieren. Der dicke Jäger stößt einen anerkennenden Pfiff aus und ruft: `w\"Alle Achtung, Doppelschnauze!\" `°Du verstehst nur Bahnhof, doch die `^4000 Gold `° und die `#4 Edelsteine`°, die er dir in die Hand drückt, nimmst du natürlich sofort.");
            $session['user']['gems'] += 4;
            $session['user']['gold'] += 4000;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 40;
            user_set_aei($row_s);
        break;
        // Doppelhaxe (selten) -> Spieler bekommt 2000 Gold
        case 7:
        case 8:
        case 9:
        case 10:
            output("...landen beide wieder nebeneinander auf allen Vieren! Ist das zu fassen?`n
            `w\"Doppelhaxe!\"`°, brüllt der dicke Jäger, klopft dir beeindruckt auf die Schulter und drückt dir einen Beutel mit `^2000 Gold `°in die Hand. Na, das hat sich doch für dich gelohnt. Grinsend ziehst du weiter.");
            $session['user']['gold'] += 2000;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 20;
            user_set_aei($row_s);
        break;
        // Doppelsuhle (selten) -> Spieler bekommt 2 Edelsteine
        case 11:
        case 12:
        case 13:
        case 14:
            output("...landen beide auf dem Rücken! Ist das zu fassen?`n
            `w\"Doppelsuhle!\"`°, brüllt der dicke Jäger, klopft dir beeindruckt auf die Schulter und drückt dir `#2 Edelsteine `°in die Hand. Na, das hat sich doch für dich gelohnt. Grinsend ziehst du weiter.");
            $session['user']['gems'] += 2;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 20;
            user_set_aei($row_s);
        break;
        // Gulasch (recht oft) -> Spieler bekommt ES oder Gold, je nach Schweine-Kombination
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
            $arr_combi = array(array("Suhle und Haxe",1,10)
                              ,array("Suhle und Schnauze",1500,15)
                              ,array("Suhle und Backe",2,20)
                              ,array("Haxe und Schnauze",1500,15)
                              ,array("Haxe und Backe",2,20)
                              ,array("Schnauze und Backe",2500,25)
                              );
            $int_key = e_rand(0,5);
            output("...landen so dicht nebeneinander wieder im Gras, dass du nur schlecht sehen kannst, wie . Sofort flitzt der kleine Jäger los und läuft einmal um die Wildschweine herum. Neben dir beobachtet der dicke Jäger seinen Kollegen - und grinst, als dieser ihm ein erfreutes: `E\"Gulasch! ".$arr_combi[$int_key][0]."!\" `°zuruft. `w\"Nicht schlecht\"`°, brummt er und gibt dir ");
            if($arr_combi[$int_key][1] == 1) {
                output("`#einen Edelstein`°, den ");
                $session['user']['gems']++;
            } elseif($arr_combi[$int_key][1] == 2) {
                output("`#2 Edelsteine`°, die ");
                $session['user']['gems'] += 2;
            } else {
                output("`^".$arr_combi[$int_key][1]." Gold`°, das ");
                $session['user']['gold'] += $arr_combi[$int_key][1];
            }
            output("du natürlich sofort einsteckst.");
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += $arr_combi[$int_key][2];
            user_set_aei($row_s);
        break;        
        // Backe (oft) -> Spieler bekommt 1500 Gold
        case 20:
        case 21:
        case 22:
        case 23:
        case 24:
        case 25:
            output("...landen dicht nebeneinander wieder im Gras, eins auf der Seite, eins merkwürdig auf einer Schulter und einem Ohr balancierend. Erfreut klatscht der kleine Jäger in die Hände und stößt seinen Kollegen dann breit grinsend an: `E\"Au, Backe!\" `°Die beiden beömmeln sich ausgiebig über diesen Scherz, während du daneben stehst und nicht so recht weißt, was du nun machen sollst. Dieser Moment ist jedoch schnell vergessen, als dir der dicke Jäger einen Beutel mit `^1500 Gold `°in die Hand drückt. `w\"Gut gemacht\"`°, lobt er dich. Du weißt überhaupt nicht, wovon der Mann eigentlich spricht, doch das Gold nimmst du natürlich gerne dankend an, ehe du weiterziehst.");
            $session['user']['gold'] += 1500;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 15;
            user_set_aei($row_s);
        break;
        // Schnauze (oft) -> Spieler bekommt 1 ES
        case 26:
        case 27:
        case 28:
        case 29:
        case 30:
        case 31:
            output("...landen dicht nebeneinander wieder im Gras, eins auf der Seite, eins auf der Schnauze balancierend. Das Bild, das sie abgeben, sieht so skurril aus, dass du erst einmal damit beschäftigt bist, das Gesehene zu verarbeiten.`n
            Ein lautes: `w\"Schnauze!\" `°lässt dich dann plötzlich zusammenfahren und herumwirbeln. Neben dir kringelt sich der dicke Krieger vor Lachen und zeigt dabei mit dem Finger auf dich. `E\"Kleiner Scherz\"`°, grinst dich auch der kleine Jäger an und drückt dir kurzerhand `#einen Edelstein `°in die Hand. Du brummelst kurz vor dich hin, doch dann steckst du den Edelstein einfach ein und ziehst weiter.");
            $session['user']['gems']++;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 10;
            user_set_aei($row_s);
        break;
        // Haxe (oft) -> Spieler bekommt 500 Gold
        case 32:
        case 33:
        case 34:
        case 35:
        case 36:
        case 37:
        case 38:
            output("...landen dicht nebeneinander wieder im Gras, eins auf der Seite, eins auf den Füßen. Erfreut klatscht der kleine Jäger in die Hände und ruft: `E\"Haxe!\"`°, und der dicke Jäger nickt dir anerkennend zu, ehe er dir `^500 Gold `°zusteckt. So ganz durchschaust du das Ganze nicht, doch zu geschenktem Gold sagst du natürlich nicht Nein. Du bedankst dich und ziehst dann weiter.");
            $session['user']['gold'] += 500;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 5;
            user_set_aei($row_s);
        break;
        // Suhle (oft) -> Spieler bekommt 500 Gold
        case 39:
        case 40:
        case 41:
        case 42:
        case 43:
        case 44:
        case 45:
            output("...landen dicht nebeneinander wieder im Gras, eins auf der Seite, eins auf dem Rücken. Erfreut klatscht der kleine Jäger einmal in die Hände und ruft: `E\"Suhle!\"`°, und der dicke Jäger nickt dir anerkennend zu, ehe er dir `^500 Gold `°zusteckt. So ganz durchschaust du das Ganze nicht, doch zu geschenktem Gold sagst du natürlich nicht Nein. Du bedankst dich und ziehst dann weiter.");
            $session['user']['gold'] += 500;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei'] += 5;
            user_set_aei($row_s);
        break;
        // Sau (häufig) -> Spieler bekommt 100 Gold
        case 46:
        case 47:
        case 48:
        case 49:
        case 50:
        case 51:
        case 52:
        case 53:
        case 54:
        case 55:
        case 56:
        case 57:
        case 58:
        case 59:
        case 60:
            output("...landen dicht nebeneinander wieder im Gras. Sofort flitzt der kleine Jäger los und läuft einmal um die Wildschweine herum, um sie genauestens unter die Lupe zu nehmen. Dann plötzlich breitet sich ein Grinsen auf seinem Gesicht aus. `E\"Sau!\"`°, brüllt er so laut, dass man ihn wahrscheinlich in ganz Lyneros hören kann, und gestikuliert dabei überschwänglich in Richtung der beiden Wildschweine. Neben dir grinst nun auch der dicke Jäger und nickt dir anerkennend zu, ehe er dir `^100 Gold `°in die Hand drückt. Die nimmst du natürlich sofort dankend an, auch wenn du eigentlich so überhaupt gar nicht verstehst, was hier gerade vor sich geht...");
            $session['user']['gold'] += 100;
            // Punkte für Ruhmeshalle vergeben
            $row_s['schweinerei']++;
            user_set_aei($row_s);
        break;
        // faule Sau (sehr häufig) -> Spieler geht leer aus
        case 61:
        case 62:
        case 63:
        case 64:
        case 65:
        case 66:
        case 67:
        case 68:
        case 69:
        case 70:
        case 71:
        case 72:
        case 73:
        case 74:
        case 75:
        case 76:
        case 77:
        case 78:
        case 79:
        case 80:
            output("...landen dicht nebeneinander wieder im Gras, das eine auf der rechten, das andere auf der linken Seite. Sofort fängt der dicke Jäger an zu kichern, und der kleine Jäger legt dir mitfühlend seine Hand auf die Schulter. `E\"Das war wohl nichts... Faule Sau!\" `°..Moment mal, hat er dich gerade wirklich als faule Sau bezeichnet?!? Entrüstet willst du dich vor ihm aufbauen, doch die beiden Jäger haben sich schon von dir abgewandt und unterhalten sich wieder gestikulierend über die Wildschweine. Kopfschüttelnd ziehst du weiter.");
        break;
        // Sauhaufen (sehr häufig) -> Spieler geht leer aus
        case 81:
        case 82:
        case 83:
        case 84:
        case 85:
        case 86:
        case 87:
        case 88:
        case 89:
        case 90:
        case 91:
        case 92:
        case 93:
        case 94:
        case 95:
        case 96:
        case 97:
        case 98:
        case 99:
        case 100:
            output("...landen so dicht nebeneinander wieder im Gras, dass sie sich berühren. Neben dir werfen sich die beiden Jäger einen Blick zu und schütteln dann gleichzeitig bedauernd den Kopf. `E\"Sauhaufen\"`°, hörst du den kleinen Jäger sagen, und im nächsten Moment legt dir der dicke Jäger seine Hand auf die Schulter. `w\"Ist nicht schlimm, kann jedem passieren\"`°, meint er und schenkt dir ein aufmunterndes Lächeln. Da du nicht so ganz verstehst, was hier eigentlich gerade passiert, nickst du einfach nur und siehst dann zu, dass du die Lichtung samt Jägern schnell hinter dir lässt.");
        break;
        // Debug
        default:
            output("...lösen sich plötzlich in Luft auf. Eh... Da kann nun aber irgend etwas nicht stimmen. Als die Wildschweine auch nach intensivem Augengerubbel verschwunden bleiben, beschließt du, den Admins dieses Landflecks via Anfrage Bescheid zu geben, damit sie mal nach dem Rechten sehen.`n
            `n
            `cFehlercode: fehlender case in op ".$str_op." in ".$str_specname);
        break;
    }
    output("`n`n");     # nur wichtig für Admins
    $session['user']['specialinc'] = '';
    if($int_rand > 1) {
        addnav("Weiter","forest.php");
    }
} else {
    output("Auf einer Waldlichtung triffst du einen dicken und einen kleinen Jäger, die sich angeregt miteinander unterhalten. Immer wieder gehen ihre Blicke dabei zu zwei gefleckten Wildschweinen, die etwas entfernt gemütlich den Boden durchwühlen. Als du die Jäger grüßt, winken sie dich zu sich herbei und fragen dich, ob du bei ihrem Spiel, dem Wildschweine-Werfen, mitmachen möchtest.`n
    Willst du einen Wurf wagen?");
    addnav("Spielen");
    addnav("S?Yay, Schweine werfen","forest.php?op=throw");
    addnav("Zurück");
    addnav("k?Nee, keine Lust","forest.php?op=leave");
}
page_footer();
?>

