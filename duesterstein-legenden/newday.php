
<? 
/* 
 *  date:           01.08.2004
 *  last modifier:  bibir
 */
require_once "common.php"; 

/*************** 
 **  SETTINGS **
 ***************/
$turnsperday = getsetting("turns",10); 
$maxinterest = ((float)getsetting("maxinterest",10)/100) + 1; //1.1; 
$mininterest = ((float)getsetting("mininterest",1)/100) + 1; //1.1; 
$maxzins=10000;
//$mininterest = 1.01; 
$dailypvpfights = getsetting("pvpday",3); 
$feeding = getsetting("feeding",1);
$sql="SELECT stone FROM stones WHERE owner = ".$session[user][acctid]."";
$result=db_query($sql);
if (db_num_rows($result)>0){
    $row=db_fetch_assoc($result);
    $session[user][stone]=$row[stone];
}else{
    $session[user][stone]=0;
}

if ($_GET['resurrection']=="true") { 
    $resline = "&resurrection=true"; 
} else if ($_GET['resurrection']=="egg") { 
    $resline = "&resurrection=egg"; 
} else { 
    $resline = ""; 
} 

/****************** 
** End Settings ** 
******************/ 
// First, find out how many dragonpoint have been spent on standard DP options
// use thisfor compare - instead of count($session[user][dragonpoints]) !!
$points = 0;
reset($session[user][dragonpoints]);
while(list($key,$val)=each($session[user][dragonpoints])){
    if ($val=="at" || $val == "de" || $val == "hp" || $val == "ff" || $val == "fe")
    $points++;
}

if ($points <$session['user']['dragonkills']&&$_GET['dk']!=""){
    array_push($session['user']['dragonpoints'],$_GET[dk]); 
    switch($_GET['dk']){
    case "hp":
        $session['user']['maxhitpoints']+=5;
        break;
    case "at":
    case "A+":
        $session['user']['attack']++;
        break;
    case "de":
    case "D+":
        $session['user']['defence']++;
        break;
    } 
    $points++;
} 
if ($points<$session['user']['dragonkills'] && $_GET['dk']!="ignore"){
    page_header("Drachenpunkte"); 
    addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline"); 
    addnav("Waldkämpfe +1","newday.php?dk=ff$resline"); 
    addnav("Angriff + 1","newday.php?dk=at$resline"); 
    addnav("Verteidigung + 1","newday.php?dk=de$resline"); 
    addnav("Fütterung + 1","newday.php?dk=fe$resline");
    //addnav("Ignore (Dragon Points are bugged atm)","newday.php?dk=ignore$resline"); 
    output("`@Du hast noch `^".($session['user']['dragonkills']-$points)."`@  Drachenpunkte übrig. Wie willst du sie einsetzen?`n`n");
    output("Du bekommst 1 Drachenpunkt pro getötetem Drachen. Die Änderungen der Eigenschaften durch Drachenpunkte sind permanent."); 
}else if ((int)$session['user']['race']==0){ 
    page_header("Ein wenig über deine Vorgeschichte"); 
    if ($_GET['setrace']!=""){ 
        $session['user']['race']=(int)($_GET['setrace']); 
        switch($_GET['setrace']){ 
        case "1": 
            $session['user']['attack']++; 
            output("`i`cTrolle`c`i`n`n");             
            output("<img src='images/wappen/wappen_trolle.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tNun, Marolaka war eine große Stadt und die Herrschaft wurde ständig zwischen den 
            Völkern gewechselt… bis sich die Trolle dazu aufbäumten und dieses Plateau in Anspruch 
            nahmen. Ein gewaltiger Kampf der Rassen war entstanden weil dieses Gebiet genau in der Mitte 
            dieser Welt liegt. Das Plateau war reich an Schätzen und gut geschützt durch die Höhe und den 
            Klimaverhältnissen die es vielen Rassen unmöglich machte hier zu überleben. Die Trolle 
            hingegen, in ihren dicken Bärenfellen eingewickelt, und ihrer großen Kampfeslust, konnte 
            dies nicht aufhalten. Gut geschützt von strengen Winterverhältnissen wurde es vielen Wesen 
            unmöglich die Trolle noch einmal in der Plateaustadt Marolaka zu bezwingen. Selbst wenn es von 
            aussen so aussieht als wäre das Plateau von Schnee und Eis bedeckt... die Stadt selber - ein 
            blühendes Paradies... falls man es lebend zu Gesicht bekommt!`n`n");
            output("`2Vorteile:`t 1 Angriffspunkt mehr `0` `n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "2": 
            $session['user']['defence']++; 
            output("`i`cElfen`c`i`n`n");             
            output("<img src='images/wappen/wappen_elfen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tElfen, wandeln geräuschlos durch Wald und Feld - Wiesen und Weiden der Blumeninseln. 
            Einige besiedeln die Nymphenbucht, können mit ihrer Verbindung zur Natur alles in sich aufnehmen 
            was ihnen beliebt. Streifen durchs Land um mehr über ihre Umwelt zu erfahren - streifen durchs 
            Land um Gefährten zu suchen. Die Blumeninsel, der Hauptsitz, prächtiger als jeder Urwalddschungel 
            - übersäht mit Pflanzen und Blumen die das Herz öffnen und die Augen weiten.`n`n");
            output("`n`2Vorteile:`t 1 Verteidigungspunkt mehr`0` `n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "3":
            output("`i`cMenschen`c`i`n`n");             
            output("<img src='images/wappen/wappen_menschen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tMenschen bilden den grössten Teil der Lebewesen des Landes und sind überall auf der Welt anzutreffen. Sie haben durch ihre Technologie 
            und ihre Anpassungsfähigkeit gelernt viele Landstücke zu besiedeln. Sie sind nur mässig belastbar und ihre Kampfkraft ist Durchschnitt. 
            Aber durch ihren Lebenswillen und den Sinn für Erfindungen hat sich ihre Rasse gut in Düsterstein entwickelt.`n`n");
            output("`2Vorteile:`t 2 Waldkämpfe mehr pro Spieltag `0` `n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "4":
            output("`i`cZwerge`c`i`n`n");             
            output("<img src='images/wappen/wappen_zwerge.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tIn der Bergstadt Threstal wohnen die Zwerge. In den Jahren des Zorns, als der Krieg 
            der Echsen gegen die Halblinge tobte, wurde die Bergstadt zu einem Unterschlupf der 
            Halblingflüchtlinge. Die Bergstadt wurde stark befestigt um die grösseren Angriffe der Echsen 
            abzuwehren und ist auch heute noch ein starker Bestandteil der Verteidungslinie der Allianz. 
            Zwerge sind sehr goldgierig und haben grosse Teile ihrer Technologie im suchen und abbauen von 
            Gold und Edelsteinen gesteckt. Zwerge erreichen keine grosse Körpergrösse aber ihre Kampfkraft 
            ist überdurchschnittlich.`n`n");
            output("`2Vorteile:`t Mehr Gold in Waldkämpfen; Stirbt seltener in der Goldmine `0` `n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
          case "5":
              $session['user']['defence']--;
              $session['user']['attack']+=2;
            output("`i`cOrks`c`i`n`n");             
            output("<img src='images/wappen/wappen_orks.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
              output("`tDie Rasse der Orks ist in vielen kleinen Clans aufgesplittet die, ähnlich wie bei 
            Menschen, Familien darstellen. Orks sind eine sehr kriegerische Rasse deren Hass sich vorallem 
            gegen die Elfe richtet und somit auch gegen dennen die den Elfen helfen, die Allianz. Die Orks 
            bewohnen das Orkmoor. Ein eher feindlicher Lebensraum. Doch die Orks haben sich der Umgebung 
            angepasst und gelernt dort zu leben. Ihr Kampfkraft ist überdurchnittlich und für sie ist der 
            Kampf eine Ehre. Die stärksten Krieger werden zu Anführern der verschiedenen Clans.");
            output("`2Vorteile:`t 2 Angriffspunkte mehr `0` `n");
            output("`4Nachteile: `t 1 Verteidigungspunkt weniger `0`n");
            break;
        case "6":
            output("`i`cHalblinge`c`i`n`n");             
            output("<img src='images/wappen/wappen_halblinge.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tHalblinge sind ein Nomadenvolk. Zwar haben sich die Halblinge in Logateso 
            angesiedelt und dort auch ihr Dorf errichtet aber die grösste zeit ihres Lebens 
            wandern die Halblinge durch das Land auf der Suche nach Wissen und neuer Technologie. 
            Vor einige Jahren erschütterte der Krieg der Echsen gegen den Halblingen die Welt 
            Düstersteins. Durch eine List und durch Hilfe der Allianz gelang es aber den 
            Halblingen die Invasion der Echsen zurück zu schlagen. Halblinge sind in ihrer 
            Technologie sehr fortgeschritten. Ihre Kampfkraft ist nur durschnitt was sie aber mit 
            ihren Wissen und Erfindungen wieder weg machen.`n`n");
            output("`2Vorteile:`t Mehr Erfahrungspunkte in Waldkämpfe `0` `n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "7":
            $session['user']['maxhitpoints']+=1;
            output("`i`cEchsen`c`i`n`n");             
            output("<img src='images/wappen/wappen_echsen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tIn den Tiefen der Logatesso Ebene haben sich die Echsen angesiedelt. Über Echsen ist 
            nur wenig bekannt. Sie sind mysteriöse Wesen die stark ihr Leben an ihre Götter und Mythen 
            anpassen. Das höhste Ziel der meisten Echsen ist es einst mal ein Echsenschamane zu werden und 
            die Worte der Götter zu verbreiten. Vor nicht allzulanger Zeit zog ein mächtiges Heer gegen die 
            Stadt Logatesso und deren Bewohner die Halblinge. Dem riesigen Heer der Echsen stand der 
            Intelekt der Halblinge gegenüber. Einzig eine List entschied den Krieg und die Echsen wurden 
            wieder zurück ihn ihre Wüstenlandschaft zurück getrieben`n`n");
            output("`2Vorteile:`t 1 permanter Lebenspunkt `0` `n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "8":
            $session['user']['defence']+=2; 
            $session['user']['attack']-=1; 
            output("`i`cGoblins`c`i`n`n");             
            output("<img src='images/wappen/wappen_goblins.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tDie Goblins leben als Alleinherrscher in der Stadt Nuo, in den unterweltlichen Gräben. 
            Die einer Sage nach direkt zu Ramius führen sollen. Doch die kleinen Goblins geben nichts davon 
            Preis, vielleicht weil sie es nicht wissen, oder vielleicht weil die Wahrheit über dieses Gebiet 
            viel zu grausam ist. Diese Gräben nennen sich nicht umsonst die Unterweltlichen. Sie sind kleine 
            Bastler und ihr Einfallsreichtum ist fast grenzenlos. Ihre geringe Körpergröße haben sie daher 
            zu brauchbaren Sklaven werden lassen. Ein Aggressiver Sklave kann besser auf ein Heim aufpassen, 
            aber so ein hitziger Goblin versteht es auch sich zu wehren und setzt seine vielen nadelspitzen 
            Zähne gekonnt ein. Deshalb wäre es Ratsam sich zuerst überlegen was man sich ins Haus holt!`n`n");
            output("`2Vorteile:`t 2 Verteidigungspunkte mehr`0`n");
            output("`4Nachteile:`t 1 Angriffspunkt weniger `0`n"); 
            break; 
        case "9":
            $session[user][thievery]+=3; 
            $session[user][thieveryuses]++;
            output("`i`cKilrath`c`i`n`n");             
            output("<img src='images/wappen/wappen_kilraths.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tKillraths, menschengroße Katzenwesen, leben in der Steppe der Katzenwüste. In kleinen 
            Familiengruppen oder alleine ziehen sie durch das Land wie Beutejäger. Durch ihre Verbindung als 
            Katzen zur Unterwelt werden viele von ihnen als Nekromanten ausgebildet. In der Hauptstadt 
            Kichischath, eine Oase in dieser kargen Steppenlandschaft, wird einem bewusst wie gut sie auch 
            in den Diebeskünsten ausgebildet sind. Jeder Killrath kann von Kindespfoten an einem Ork die Axt 
            vom Rücken stehlen. Leg dich bloß nicht mit ihnen an, sie haben scharfe Krallen!`n`n");
            output("`2Vorteile:`t zusätliche Diebesfähigkeiten `0`n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "10":
            $session[user][magic]+=3;
                  $session[user][magicuses]++;
            output("`i`cArachnien`c`i`n`n");             
            output("<img src='images/wappen/wappen_arachnen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tTarisas Part kommt noch`n`n");
            output("`2Vorteile:`t zusätliche Mysticfähigkeiten `0`n");
            output("`4Nachteile: `t keine `0`n"); 
            break; 
        case "11":
            $session[user][deathpower]+=15;
            output("`i`cWerwolf`c`i`n`n");             
            output("<img src='images/wappen/wappen_werwoelfe.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tEin kleines Dorf nahe Düsterstein, versteckt in den dichten des Nordwaldes 
            - dort leben die Werwölfe. Diese bodenständige Siedlung, die sich Thebeto nennt, 
            haust geschützt vor anderen Wesen, der Natur verbunden und reich an Wissen, in vielen 
            Zelten und Höhlen. In Vollmondnächten machen sich die stärksten Wolfkrieger auf und 
            jagen in den Wald um für ihren Rudelältesten ein Opfer zu bringen - dabei ist es 
            ihnen egal welcher Rasse dieses Opfer angehört. Nicht einmal vor der Unterwelt machen 
            sie halt.`n`n");
            output("`2Vorteile:`t zusätliche Gefallen bei Raminius `0`n");
            output("`4Nachteile: `t keine `0`n"); 
            break;             
        case "12":
            $session[user][darkartuses]++; 
            $session[user][darkarts]+=3;
            output("`i`cVampire`c`i`n`n");             
            output("<img src='images/wappen/wappen_vampire.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>",true);
            output("`tDie unterweltliche Ebene, genauer gesagt Nuo, ist der Ursprung der Rasse 
            Vampir. Zwar wird Vampiren nachgesagt, sie könnten weder über fließende Gewässer 
            schreiten, noch sich selbst im Spiegel sehen doch ist die Annahme weitgehend falsch. 
            Viele Unterrassen haben sich im Laufe der Jahre gebildet, von denen es manchen sogar 
            möglich ist, am helllichten Tage zu wandeln. Direkte Sonnenstrahlen vertragen sie 
            weitgehend trotzdem nicht, weswegen sie sich meist mit Roben oder Umhängen, sowie 
            Hüten oder Kapuzen davor schützen. Des Nachts, wenn die schützenden Schatten sich 
            ausbreiten, ziehen sie meist im Alleingang auf die Jagd. Die Gier nach menschlichem, 
            oder manchmal sogar elfischen Blut macht sie zu gerissenen, heimtückischen Jägern, 
            die ihre Opfer dennoch meist am Leben lassen, wenn sie ihm nicht alles an Blut nehmen. 
            Nicht der erste Biss verwandelt ein Opfer in ein Kind der Nacht. Erst nach der so genannten 
            Bluttaufe, wenn das Blut von beiden ausgetauscht wurde, wird sich der oder die Gebissene im 
            Laufe der nächsten Tage wandeln. Vampire sind unsterblich und treiben so meist viele Tausende 
            Jahre ihr Unwesen. Einzig ein Pflock durch deren Herz, oder gar die direkte Sonne auf dessen 
            Haut würde ihn zu Staub zerfallen lassen.`n`n");
            output("`2Vorteile:`tzusätliche Anwendungen in Dunklen Künsten`0`n");
            output("`4Nachteile: `t keine `0`n"); 
            } 
        addnav("Weiter","newday.php?continue=1$resline"); 
        if ($session[user][dragonkills]==0 && $session[user][level]==1) addnews("`#{$session[user][name]} `#hat unsere Welt betreten. Willkommen!"); 
    }else{
          if ($_GET[op]=="") {    

                output("Grüsse dich Wanderer. Besser gesagt: `@Hallo Spieler`0. Hier gebe ich dir nochmals die Chance, deine letzte *grinst*, 
                als Spieler hinter deinem Charakter zu wählen. Folgende Möglichkeiten gibt es:`n`n`
                
                Den Rollenspielweg: `n
                Wählst du diese Option, so wählt der grosse Geist von Düsterstein, also kurz: Der Server, deine Rasse. Du musst
                dann die Rasse spielen die dir zugeteilt wird. Nachteil hierbei: Du hast nicht die Möglichkeit dein Rollenspiel
                an deine Rassenwahl anzupassen, sondern musst dich mit den Gegebenheiten anpassen die dir zugeteilt wurden. Dies 
                kann aber auch sehr interessant werden. Der Vorteil hierbei. Nicht alle Rassen in Düsterstein sind wählbar. Einige
                Rassen kannst du nur durch diesen Weg erhalten.`n`n
                Den konversionellen Weg:`n` 
                Hierbei wählst du nach deinem Ermessen eine Rasse aus. Dir stehen eine Vielzahl von Rassen zur Verfügung und ihre
                Vor-und Nachteile werden dir vorab erklärt. Hierbei kannst du dir schon deinen Rollenspiel Weg vorab vorstellen und
                die Rasse wählen die am besten dazu passt. Einige Rassen sind aber über diesen Weg nicht wahlbar. So kannst du nur 
                die Hauptrassen wählen und musst auf andere Rassen verzichten.`n`n");

                output("`n`n`i`cÜbersicht der Rassen:`c`i`n`n");
                output("Vereinigtes Königreich:`n`n");
                output("   <table width='100%' border='0' cellpadding='0' cellspacing='2'>",true);
                output("     <tr>",true);
                output("       <td width='50%'><img src='images/wappen/wappen_menschen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tMenschen`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("       <td><img src='images/wappen/wappen_elfen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tElfen`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("     </tr>",true);
                output("   </table>",true);
                output("   <table width='100%' border='0' cellpadding='0' cellspacing='2'>",true);
                output("     <tr>",true);
                output("       <td width='50%'><img src='images/wappen/wappen_zwerge.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tZwerge`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("       <td><img src='images/wappen/wappen_halblinge.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tHalblinge`n`tTyp: `KRollenspielweg`0`n</td>",true);
                output("     </tr>",true);
                output("   </table>",true);
                output("`n`nHeer der Finsternis:`n`n");
                output("   <table width='100%' border='0' cellpadding='0' cellspacing='2'>",true);
                output("     <tr>",true);
                output("       <td width='50%'><img src='images/wappen/wappen_trolle.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tTrolle`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("       <td><img src='images/wappen/wappen_orks.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tOrks`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("     </tr>",true);
                output("   </table>",true);
                output("   <table width='100%' border='0' cellpadding='0' cellspacing='2'>",true);
                output("     <tr>",true);
                output("       <td width='50%'><img src='images/wappen/wappen_echsen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tEchsen`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("       <td><img src='images/wappen/wappen_goblins.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tGoblins`n`tTyp: `KRollenspielweg`0`n</td>",true);
                output("     </tr>",true);
                output("   </table>",true);
                 output("`n`nNeutrale Wesen:`n`n");
                output("   <table width='100%' border='0' cellpadding='0' cellspacing='2'>",true);
                output("     <tr>",true);
                output("       <td width='50%'><img src='images/wappen/wappen_kilraths.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tKilraths`n`tTyp: `@Frei wählbar`n`0</td>",true);
                output("       <td><img src='images/wappen/wappen_arachnen.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tArachnien`n`tTyp: `KRollenspielweg`0`n</td>",true);
                output("     </tr>",true);
                output("   </table>",true);
                output("   <table width='100%' border='0' cellpadding='0' cellspacing='2'>",true);
                output("     <tr>",true);
                output("       <td width='50%'><img src='images/wappen/wappen_werwoelfe.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tWerwölfe`n`tTyp: `4Subklasse`n`0</td>",true);
                output("       <td><img src='images/wappen/wappen_vampire.jpg' alt='' border='0' width='100' height='100' align='left' vspace='0' hspace='20'>`tVampire`n`tTyp: `4Subklasse`0`n</td>",true);
                output("     </tr>",true);
                output("   </table>",true);

                addnav("Wähle deinen Weg");
                addnav("Rasse vom System","newday.php?op=race01");
                addnav("Rasse selbst wählen","newday.php?op=race02");
          }else if ($_GET[op]=="race01"){
                $welcherace = e_rand(1,10);
                    switch ($welcherace) {
                      case 1:
                        redirect("newday.php?setrace=1$resline");
                        break;
                      case 2:
                        redirect("newday.php?setrace=2$resline");
                        break;
                      case 3:
                        redirect("newday.php?setrace=3$resline");
                        break;
                      case 4:
                        redirect("newday.php?setrace=4$resline");
                        break;
                      case 5:
                        redirect("newday.php?setrace=5$resline");
                        break;
                      case 6:
                        redirect("newday.php?setrace=6$resline");
                        break;
                      case 7:
                        redirect("newday.php?setrace=7$resline");
                        break;
                      case 8:
                        redirect("newday.php?setrace=8$resline");
                        break;
                      case 9:
                        redirect("newday.php?setrace=9$resline");
                        break;
                      case 10:
                        redirect("newday.php?setrace=10$resline");
                      }
          
          }else if ($_GET[op]=="race02"){ 
                output("`cWähle deine Herkunft?`c`n`n`n");

output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output("<tr>",true);
output("<td width='150'><img src='images/wappen/wappen_menschen.jpg' alt='' border='0' width='100' height='100'></td>",true);
output("<td width='*' align='left' colspan='2'>",true);
output("<table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("<tr>",true);
output("<td width='40' ><img src='images/font_m.gif' alt='' border='0' width='40' height='40'></td>",true);
output("<td>`2enschen</td>",true);
output("</tr>",true);
output("</table>",true);
output("</td>",true);
output("</tr>",true);
output("<tr>",true);
output("<td colspan='3' height='50' ><a href='newday.php?setrace=3$resline'>Im Flachland in der Stadt Romar</a>, der Stadt der `&Menschen`0. Du hast immer nur zu deinem Vater aufgesehen und bist jedem seiner Schritte gefolgt, bis er auszog den `@Grünen Drachen`0 zu vernichten und nie wieder gesehen wurde.</td>",true);
output("</tr>",true);
output("</table>",true);
output("`c`n");
output("<img src='images/images/homepage11.jpg' alt='' border='0' width='501' height='5'>",true);
output("`c`n`n");
output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output(" <tr>",true);
output("  <td width='*' align='right' colspan='2'>",true);
output("         <table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("                 <tr>",true);
output("                         <td align='right' ><img src='images/font_e.gif' alt='' border='0' width='40' height='40'></td>",true);
output("                         <td width='10'>`2lfen</td>",true);
output("                 </tr>",true);
output("         </table>",true);
output("  </td>",true);
output("    <td width='150'><img src='images/wappen/wappen_elfen.jpg' alt='' border='0' width='100' height='100' align='right' ></td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td colspan='3' height='50' ><a href='newday.php?setrace=2$resline'>Hoch über den Bäumen</a> des Waldes Glorfindal, in zerbrechlich wirkenden, kunstvoll verzierten Bauten der `^Elfen`0, die so aussehen, als ob sie beim leisesten Windhauch zusammenstürzen würden und doch schon Jahrhunderte überdauern.",true);
output("</td>",true);
output(" </tr>",true);
output("</table>",true);
output("`c`n");
output("<img src='images/images/homepage11.jpg' alt='' border='0' width='501' height='5'>",true);
output("`c`n`n");
output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output(" <tr>",true);
output("  <td width='150'><img src='images/wappen/wappen_zwerge.jpg' alt='' border='0' width='100' height='100'></td>",true);
output("  <td width='*' align='left' colspan='2'>",true);
output("         <table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("                 <tr>",true);
output("                         <td width='40' ><img src='images/font_z.gif' alt='' border='0' width='40' height='40'></td>",true);
output("                         <td>`2werge</td>",true);
output("                 </tr>",true);
output("         </table>",true);
output("  </td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td colspan='3' height='50' ><a href='newday.php?setrace=4$resline'>Tief in der Unterirdischen Festung Qexelcrag</a>, der Heimat der edlen und starken `#Zwerge`0, deren Verlangen nach Besitz und Reichtum in keinem Verhältnis zu ihrer Körpergrösse steht.",true);
output("  </td>",true);
output(" </tr>",true);
output("</table>",true);
output("`c`n");
output("<img src='images/images/homepage11.jpg' alt='' border='1' width='501' height='5'>",true);
output("`c`n`n"); 
output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output(" <tr>",true);
output("  <td width='*' align='right' colspan='2'>",true);
output("         <table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("                 <tr>",true);
output("                         <td align='right' ><img src='images/font_t.gif' alt='' border='0' width='40' height='40'></td>",true);
output("                         <td width='10'>`2rolle</td>",true);
output("                 </tr>",true);
output("         </table>",true);
output("  </td>",true);
output("    <td width='150'><img src='images/wappen/wappen_trolle.jpg' alt='' border='0' width='100' height='100' align='right' ></td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td colspan='3' height='50' ><a href='newday.php?setrace=1$resline'>In den Sümpfen von Glukmoore</a> als `2Troll`0, auf dich alleine gestellt seit dem Moment, als du aus der lederartigen Hülle deines Eis geschlüpft bist und aus den Knochen deiner ungeschlüpften Geschwister ein erstes Festmahl gemacht hast.",true);
output("</td>",true);
output(" </tr>",true);
output("</table>",true);
output("`c`n");
output("<img src='images/images/homepage11.jpg' alt='' border='0' width='501' height='5'>",true);
output("`c`n`n");
output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output(" <tr>",true);
output("  <td width='150'><img src='images/wappen/wappen_orks.jpg' alt='' border='0' width='100' height='100'></td>",true);
output("  <td width='*' align='left' colspan='2'>",true);
output("         <table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("                 <tr>",true);
output("                         <td width='40' ><img src='images/font_o.gif' alt='' border='0' width='40' height='40'></td>",true);
output("                         <td>`2rks</td>",true);
output("                 </tr>",true);
output("         </table>",true);
output("  </td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td colspan='3' height='50' ><a href='newday.php?setrace=5$resline'>In den zerklüfteten Gebirgen in Höhlen</a>, der Heimat der `#Orks`0 bist Du geboren und vom Stamme der Uruk. Deine Kampfeslust ist legendär...",true);
output("  </td>",true);
output(" </tr>",true);
output("</table>",true);
output("`c`n");
output("<img src='images/images/homepage11.jpg' alt='' border='1' width='501' height='5'>",true);
output("`c`n`n"); 
output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output(" <tr>",true);
output("  <td width='*' align='right' colspan='2'>",true);
output("         <table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("                 <tr>",true);
output("                         <td align='right' ><img src='images/font_e.gif' alt='' border='0' width='40' height='40'></td>",true);
output("                         <td width='10'>`2chsen</td>",true);
output("                 </tr>",true);
output("         </table>",true);
output("  </td>",true);
output("    <td width='150'><img src='images/wappen/wappen_echsen.jpg' alt='' border='0' width='100' height='100' align='right' ></td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td colspan='3' height='50' ><a href='newday.php?setrace=1$resline'>In den Sümpfen von Glukmoore</a> als `2Troll`0, auf dich alleine gestellt seit dem Moment, als du aus der lederartigen Hülle deines Eis geschlüpft bist und aus den Knochen deiner ungeschlüpften Geschwister ein erstes Festmahl gemacht hast.",true);
output("</td>",true);
output(" </tr>",true);
output("</table>",true);
output("`c`n");
output("<img src='images/images/homepage11.jpg' alt='' border='0' width='501' height='5'>",true);
output("`c`n`n");
output("<table width='480' align='center'  border='0' cellpadding='0' cellspacing='0'>",true);
output(" <tr>",true);
output("  <td width='150'><img src='images/wappen/wappen_kilraths.jpg' alt='' border='0' width='100' height='100'></td>",true);
output("  <td width='*' align='left' colspan='2'>",true);
output("         <table width='100%' border='0' cellpadding='0' cellspacing='0'>",true);
output("                 <tr>",true);
output("                         <td width='40' ><img src='images/font_k.gif' alt='' border='0' width='40' height='40'></td>",true);
output("                         <td>`2ilraths</td>",true);
output("                 </tr>",true);
output("         </table>",true);
output("  </td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td colspan='3' height='50' ><a href='newday.php?setrace=5$resline'>In den zerklüfteten Gebirgen in Höhlen</a>, der Heimat der `#Orks`0 bist Du geboren und vom Stamme der Uruk. Deine Kampfeslust ist legendär...",true);
output("  </td>",true);
output(" </tr>",true);
output("</table>",true); 

        addnav("Vereinigtes Königreich"); 
        addnav("`&Mensch`0","newday.php?setrace=3$resline"); 
        addnav("`^Elf`0","newday.php?setrace=2$resline"); 
        addnav("`#Zwerg`0","newday.php?setrace=4$resline");
        //addnav("`&Halbling`0","newday.php?setrace=6$resline");
        addnav("Heer der Finsternis");  
        addnav("`2Troll`0","newday.php?setrace=1$resline");  
        addnav("`@Ork`0","newday.php?setrace=5$resline");
        addnav("`&Echse`0","newday.php?setrace=7$resline");
        //addnav("`&Goblin`0","newday.php?setrace=8$resline");
        addnav("Neutrale Rassen");  
        addnav("`&Kilrath`0","newday.php?setrace=9$resline");
        //addnav("`&Arachnie`0","newday.php?setrace=10$resline");
        //addnav("`&Werwolf`0","newday.php?setrace=11$resline");
        //addnav("`&Vampir`0","newday.php?setrace=12$resline");     
        addnav("","newday.php?setrace=3$resline"); 
        addnav("","newday.php?setrace=2$resline"); 
        addnav("","newday.php?setrace=4$resline");
        addnav("","newday.php?setrace=6$resline");
        addnav("","newday.php?setrace=1$resline"); 
        addnav("","newday.php?setrace=5$resline");
        addnav("","newday.php?setrace=7$resline"); 
        addnav("","newday.php?setrace=8$resline");
        addnav("","newday.php?setrace=9$resline");
        addnav("","newday.php?setrace=10$resline");
        addnav("","newday.php?setrace=11$resline");
        addnav("","newday.php?setrace=12$resline");
        }
    } 
}else if ((int)$session['user']['specialty']==0){ 
  if ($HTTP_GET_VARS['setspecialty']===NULL){ 
        addnav("","newday.php?setspecialty=1$resline"); 
        addnav("","newday.php?setspecialty=2$resline"); 
        addnav("","newday.php?setspecialty=3$resline"); 
        page_header("Ein wenig über deine Vorgeschichte"); 
         
        output("Du erinnerst dich, dass du als Kind:`n`n"); 
        output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getötet hast (`\$Dunkle Künste`0)</a>`n",true); 
        output("<a href='newday.php?setspecialty=2$resline'>mit mystischen Kräften experimentiert hast (`%Mystische Kräfte`0)</a>`n",true); 
        output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`^Diebeskunst`0)</a>`n",true); 
        addnav("`\$Dunkle Künste","newday.php?setspecialty=1$resline"); 
        addnav("`%Mystische Kräfte","newday.php?setspecialty=2$resline"); 
        addnav("`^Diebeskünste","newday.php?setspecialty=3$resline"); 
  }else{ 
      addnav("Weiter","newday.php?continue=1$resline"); 
        switch($HTTP_GET_VARS['setspecialty']){ 
          case 1: 
              page_header("Dunkle Künste"); 
                output("`5Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu töten, weil du davon überzeugt warst, sie haben sich gegen dich verschworen. "); 
                output("Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darüber waren, dass du die Kreaturen des Waldes mit bloßen Händen töten musst. "); 
                output("Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzuführen, wobei du am Ende oft tagelang im Wald verschwunden bist. "); 
                output("Niemand außer dir wusste damals wirklich, was die Ursache für die seltsamen Geräusche aus dem Wald war..."); 
                break; 
            case 2: 
              page_header("Mystische Kräfte"); 
                output("`3Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. "); 
                output("Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. "); 
                output("Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. "); 
                output("Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst."); 
                break; 
            case 3: 
              page_header("Diebeskünste"); 
                output("`6Du hast schon sehr früh bemerkt, dass ein gewöhnlicher Rempler im Gedränge dir das Gold eines vom Glück bevorzugteren Menschen einbringen kann. "); 
                output("Außerdem hast du entdeckt, dass der Rücken deiner Feinde anfälliger gegen kleine Klingen ist, als deren Vorderseite gegen mächtige Waffen."); 
                break; 
        } 
        $session['user']['specialty']=$HTTP_GET_VARS['setspecialty']; 
    } 
}else{ 
  if ($session['user']['slainby']!=""){ 
        page_header("Du wurdest umgebracht!"); 
        output("`\$Im ".$session['user']['killedin']." hat dich `%".$session['user']['slainby']."`\$ getötet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit für Rache?"); 
        addnav("Weiter","newday.php?continue=1$resline"); 
      $session['user']['slainby']=""; 
    }else{ 
        page_header("Es ist ein neuer Tag!"); 
        $interestrate = e_rand($mininterest*100,$maxinterest*100)/(float)100; 
        output("`c<font size='+1'>`b`#Es ist ein neuer Tag!`0`b</font>`c",true); 

        if ($session['user']['alive']!=true){ 
            $session['user']['resurrections']++; 
            output("`@Du bist wiedererweckt worden! Dies ist der Tag deiner ".ordinal($session['user']['resurrections'])." Wiederauferstehung.`0`n");
            $session['user']['alive']=true; 
        } 
        $session[user][age]++; 
        $session[user][seenmaster]=0; 
        output("Du öffnest deine Augen und stellst fest, dass dir ein neuer Tag geschenkt wurde. Die Sonne blinzelt dich an, an deinem `^".ordinal($session['user']['age'])."`0 Tag in diesem Land. ");
        output("Du fühlst dich frisch und bereit für die Welt!`n"); 
        output("`2Runden für den heutigen Tag: `^$turnsperday`n"); 
        if ($session['user']['turns']>getsetting("fightsforinterest",4) && $session['user']['goldinbank']>=0) { 
            $interestrate=1; 
            //output("`2Today's interest rate: `^0% (Bankers in this village only give interest to those who work for it)`n");
            output("`2Heutiger Zinssatz: `^0% (Die Bank zahlt nur denen Zinsen, die auch dafür arbeiten)`n");
        }else{
            output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
            //guilds  by gargamel
            if ($session['user']['guildID']!=0) {
                $MyGuild=&$session['guilds'][$session['user']['guildID']];
                if (isset($MyGuild)) {
                    $guildinterestrate = $MyGuild['PercentOfFightsEarned']['Bank'];
                } else {
                    // Error
                    // Their guildID is set but the information cannot be retrieved
                    $debug=print_r($session['user']['guildID'],true);
                    debuglog("MyGuild isn't set: ".$debug);
                }
                if ( (($interestrate-1)*100) < $guildinterestrate ) {
                    output("`3Durch Deine Gilde ist Dir jedoch ein Mindestzinssatz von
                    `^$guildinterestrate% `3garantiert.`n");
                    $interestrate =  (($guildinterestrate/100)+1);
                }
            } elseif ($session['user']['clanID']!=0) {
                $MyClan=&$session['guilds'][$session['user']['clanID']];
                if (isset($MyClan)) {
                    $claninterestrate = $MyClan['PercentOfFightsEarned']['Bank'];
                } else {
                    // Error
                    // Their guildID is set but the information cannot be retrieved
                    $debug=print_r($session['user']['clanID'],true);
                    debuglog("MyGuild isn't set: ".$debug);
                }
                if ( (($interestrate-1)*100) < $claninterestrate ) {
                    output("`3Durch Deinen Clan ist Dir jedoch ein Mindestzinssatz von
                    `^$claninterestrate% `3garantiert.`n");
                    $interestrate =  (($claninterestrate/100)+1);
                }
            }
            //guilds end
            if ($session['user']['goldinbank']>=0){ 
                //output("`2Gold earned from interest: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
                if ( $session['user']['stone']==12 ) {
                    output("`2Weil Du den $stone[12] `2besitzt, bekommst Du keine Zinsen!`n`0");
                } else if ( $session['user']['stone']==14 ) {
                    output("`2Weil Du $stone[14] besitzt, bekommst Du doppelte Zinsen: `^".(int)($session['user']['goldinbank']*(($interestrate-1)*2))."`2 Gold.`n");
            $zinsen=(int)($session['user']['goldinbank']*(($interestrate-1)*2));
                } else {
                    output("`2Deine Zinsen in Gold: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
            $zinsen=(int)($session['user']['goldinbank']*($interestrate-1));
                }
        if ($zinsen>$maxzins){
            output("`4Die Geschäftsbedingungen der Bank erlauben keine Tageszinsen größer ".$maxzins." Gold.
                Die Zinszahlung wird entsprechend angepaßt.`0`n");
            $zinsen=$maxzins;
        }
            }else{ 
                output("`2Zinsen für Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 Gold.`n");
        $zinsen=-(int)($session['user']['goldinbank']*($interestrate-1));
            } 
        } 
        output("`2Deine Gesundheit wurde wiederhergestellt auf `^".$session['user']['maxhitpoints']."`n"); 
        $skills = array(1=>"Dunkle Künste","Mystische Kräfte","Diebeskünste"); 
        $sb = getsetting("specialtybonus",1); 
        output("`2Für dein Spezialgebiet `&".$skills[$session['user']['specialty']]."`2, erhältst du zusätzlich $sb Anwendung(en) in `&".$skills[$session['user']['specialty']]."`2 für heute.`n"); 
        $session['user']['darkartuses'] = (int)($session['user']['darkarts']/3) + ($session['user']['specialty']==1?$sb:0); 
        $session['user']['magicuses'] = (int)($session['user']['magic']/3) + ($session['user']['specialty']==2?$sb:0); 
        $session['user']['thieveryuses'] = (int)($session['user']['thievery']/3) + ($session['user']['specialty']==3?$sb:0); 
        //$session['user']['bufflist']=array(); // with this here, buffs are always wiped, so the preserve stuff fails! 
        if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295){ 
            output("`n`%Du bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lässt dich heute ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n"); 
            $session['user']['charm']--; 
            if ($session['user']['charm']<=0){ 
                output("`n`bAls du heute aufwachst, findest du folgende Notiz neben dir im Bett:`n`5".($session[user][sex]?"Liebste":"Liebster").""); 
                output("".$session['user']['name']."`5."); 
                output("`nTrotz vieler großartiger Küsse, fühle ich mich einfach nicht mehr so zu dir hingezogen wie es früher war.`n`n"); 
                output("Nenne mich wankelmütig, aber ich muss weiterziehen. Es gibt andere Krieger".($session[user][sex]?"innen":"")." in diesem Dorf und ich glaube, "); 
                output("einige davon sind wirklich heiss. Es liegt also nicht an dir, sondern an mir, usw. usw."); 
                  $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto].""; 
                  $result = db_query($sql) or die(db_error(LINK)); 
                $row = db_fetch_assoc($result); 
                $partner=$row[name]; 
                if ($partner=="") $partner = $session[user][sex]?"Seth":"Violet"; 
                output("`n`nSei nicht traurig!`nIn Liebe, $partner`b`n"); 
                addnews("`\$$partner `\$hat {$session['user']['name']}`\$ für \"andere Interessen\" verlassen!"); 
                if ($session['user']['marriedto']==4294967295) $session['user']['marriedto']=0; 
                if ($session['user']['charisma']==4294967295){ 
                     $session['user']['charisma']=0; 
                    $session['user']['marriedto']=0; 
                    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid='$row[acctid]'"; 
                    db_query($sql); 
                    systemmail($row['acctid'],"`\$Wieder solo!`0","`6Du hast `&{$session['user']['name']}`6 verlassen. ".($session[user][sex]?"Sie":"Er")." war einfach widerlich in letzter Zeit."); 
                } 
            } 
        } 

        //clear all standard buffs 
        $tempbuf = unserialize($session['user']['bufflist']); 
        $session['user']['bufflist']=""; 
        $session['bufflist']=array(); 
        while(list($key,$val)=@each($tempbuff)){ 
            if ($val['survivenewday']==1){ 
                $session['bufflist'][$key]=$val; 
                output("{$val['newdaymessage']}`n"); 
            } 
        } 

        reset($session['user']['dragonpoints']); 
        $dkff=0;
        $feed=0;
        while(list($key,$val)=each($session['user']['dragonpoints'])){ 
            if ($val=="ff"){ 
                $dkff++; 
            } 
            if ($val=="fe"){
                $feed++;
            }
        }
        if ($session[user][hashorse]){ 
            $session['bufflist']['mount']=unserialize($playermount['mountbuff']); 
        } 
        if ($dkff>0) output("`n`2Du erhöhst deine Waldkämpfe um `^$dkff`2 durch verteilte Drachenpunkte!"); 
        if ($feed>0) output("`n`2Durch verteilte Drachenpunkte darfst Du Dein Tier heute `^$feed`2 mal extra füttern!");
        $r1 = e_rand(-1,1);
        $r2 = e_rand(-1,1); 
        $spirits = $r1+$r2; 
        if ($_GET['resurrection']=="true"){ 
            addnews("`&{$session['user']['name']}`& wurde von `\$Ramius`& wiedererweckt."); 
            $spirits=-6; 
            $session['user']['deathpower']-=100; 
            $session['user']['restorepage']="village.php?c=1"; 
        } 
        if ($_GET['resurrection']=="egg"){ 
            addnews("`&{$session['user']['name']}`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich."); 
            $spirits=-6; 
            //$session['user']['deathpower']-=100; 
            $session['user']['restorepage']="village.php?c=1"; 
            savesetting("hasegg",stripslashes(0)); 
        } 
        $sp = array((-6)=>"Auferstanden",(-2)=>"Sehr schlecht",(-1)=>"Schlecht","0"=>"Normal",1=>"Gut",2=>"Sehr gut"); 
        output("`n`2Dein Geist und deine Stimmung ist heute `^".$sp[$spirits]."`2!`n"); 
        if (abs($spirits)>0){ 
            output("`2Deswegen `^"); 
            if($spirits>0){ 
                output("bekommst du zusätzlich "); 
            }else{ 
                output("verlierst du "); 
            } 
            output(abs($spirits)." Runden`2 für heute.`n"); 
        } 
        $rp = $session['user']['restorepage']; 
        $x = max(strrpos("&",$rp),strrpos("?",$rp)); 
        if ($x>0) $rp = substr($rp,0,$x); 
        if (substr($rp,0,10)=="badnav.php"){ 
            addnav("Weiter","news.php"); 
        }else{ 
            addnav("Weiter",preg_replace("'[?&][c][=].+'","",$rp)); 
        } 
         
        $session['user']['laston'] = date("Y-m-d H:i:s"); 
        $bgold = $session['user']['goldinbank']; 
        if ( $session['user']['stone'] != 12 ) {
        $session['user']['goldinbank']+=$zinsen; 
        }
        $nbgold = $session['user']['goldinbank'] - $bgold; 
        if ( $session['user']['stone'] == 14 && $bgold > 0 ) {
            $session['user']['goldinbank']+=$nbgold;
        }

        if ($nbgold != 0) { 
            debuglog(($nbgold >= 0 ? "earned " : "paid ") . abs($nbgold) . " gold in interest"); 
        } 
        $config = unserialize($session['user']['donationconfig']); 
            $config['goldmineday']=0; 
        $session['user']['donationconfig'] = serialize($config); 
        $session['user']['turns']=$turnsperday+$spirits+$dkff; 
        if ($session[user][maxhitpoints]<6) $session[user][maxhitpoints]=6; 
        $session['user']['hitpoints'] = $session[user][maxhitpoints]; 
        $session['user']['spirits'] = $spirits; 
        $session['user']['playerfights'] = $dailypvpfights;
    $session['user']['feeding'] = $feeding+$feed;
        $session['user']['transferredtoday'] = 0; 
        $session['user']['amountouttoday'] = 0; 
        $session['user']['seendragon'] = 0; 
        $session['user']['seenmaster'] = 0; 
        $session['user']['seenlover'] = 0; 
    $session['user']['seenolddrawl'] = 0;
        $session['user']['witch'] = 0; 
        $session['user']['usedouthouse'] = 0; 
        $session['user']['gotfreeale'] = 0; 
        $session['user']['thefttoday'] = 0; 
        $session['user']['mirror'] = 0;
        if ($_GET['resurrection']!="true"){ 
            $session['user']['soulpoints']=50 + 5 * $session['user']['level']; 
            $session['user']['gravefights']=getsetting("gravefightsperday",10); 

        } 
        $session['user']['seenbard'] = 0; 
        $session['user']['boughtroomtoday'] = 0; 
        $session['user']['lottery'] = 0; 
        $session['user']['recentcomments']=$session['user']['lasthit']; 
        $session['user']['lasthit'] = date("Y-m-d H:i:s"); 
        if ($session['user']['drunkenness']>66){ 
          output("`&Wegen deines schrecklichen Katers wird dir 1 Runde für heute abgezogen."); 
            $session['user']['turns']--; 
        }
        $tag=getdayofweek();
        if ( $tag == "Sonntag" ) {
            output("`n`^Weil heute Sonntag ist, bekommst Du 1 Runde zusätzlich, um ausreichend
            Zeit für einen Waldspaziergang zu haben.`0");
            $session['user']['turns']++;
        }


// following by talisman & JT 
// Set global newdaysemaphore 

       $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00"))); 
       $gametoday = gametime(); 
         
        if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){ 
            //$sql = "LOCK TABLES settings WRITE"; 
            //db_query($sql); 

           $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00"))); 
                                                                                 
            $gametoday = gametime(); 
            if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){ 
        $sql="SELECT value FROM settings WHERE setting='daylock'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row[value]==0){
            $sql="UPDATE settings SET value='1' WHERE setting='daylock'";
            db_query($sql);
        
                    //we need to run the hook, update the setting, and unlock. 
                    savesetting("newdaysemaphore",date("Y-m-d H:i:s")); 
                    //$sql = "UNLOCK TABLES"; 
                    //db_query($sql); 
                                                                                 
                    require_once "setnewday.php"; 
        }    

            }else{ 
                //someone else beat us to it, unlock. 
                //$sql = "UNLOCK TABLES"; 
                //db_query($sql); 
                output("Somebody beat us to it"); 
            } 
        } 

    output("`nDer Schmerz in deinen wetterfühligen Knochen sagt dir das heutige Wetter: `6".$settings['weather']."`@.`n"); 
    if ($_GET['resurrection']==""){ 
        if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){ 
            output("`^`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n"); 
            $session[user][darkartuses]++; 
            }     
        if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){ 
            output("`^`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n"); 
            $session[user][magicuses]++; 
            }     
        if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){ 
            output("`^`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n"); 
            $session[user][thieveryuses]++; 
            }         
    } 
//End global newdaysemaphore code and weather mod. 

        if ($session['user']['hashorse']){ 
            //$horses=array(1=>"pony","gelding","stallion"); 
            //output("`n`&You strap your `%".$session['user']['weapon']."`& to your ".$horses[$session['user']['hashorse']]."'s saddlebags and head out for some adventure.`0"); 
            //output("`n`&Because you have a ".$horses[$session['user']['hashorse']].", you gain ".((int)$session['user']['hashorse'])." forest fights for today!`n`0"); 
            //$session['user']['turns']+=((int)$session['user']['hashorse']); 
            output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0")); 
            if ($playermount['mountforestfights']>0){ 
                output("`n`&Weil du ein(e/n) {$playermount['mountname']} besitzt, bekommst du `^".((int)$playermount['mountforestfights'])."`& Runden zusätzlich.`n`0"); 
                $session['user']['turns']+=(int)$playermount['mountforestfights']; 
            } 
        }else{ 
            output("`n`&Du schnallst dein(e/n) `%".$session['user']['weapon']."`& auf den Rücken und ziehst los ins Abenteuer.`0"); 
        } 
        if ($session['user']['race']==3) { 
            $session['user']['turns']++; 
            output("`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusätzlich!`n`0"); 
        } 
        $config = unserialize($session['user']['donationconfig']); 
        if (!is_array($config['forestfights'])) $config['forestfights']=array(); 
        reset($config['forestfights']); 
        while (list($key,$val)=each($config['forestfights'])){ 
            $config['forestfights'][$key]['left']--; 
            output("`@Du bekommst eine Extrarunde für die Punkte auf `^{$val['bought']}`@."); 
            $session['user']['turns']++; 
            if ($val['left']>1){ 
                output(" Du hast `^".($val['left']-1)."`@ Tage von diesem Kauf übrig.`n"); 
            }else{ 
                unset($config['forestfights'][$key]); 
                output(" Dieser Kauf ist damit abgelaufen.`n"); 
            } 
        } 
        if ($config['healer'] > 0) { 
            $config['healer']--; 
            if ($config['healer'] > 0) { 
                output("`n`@Golinda ist bereit, dich noch {$config['healer']} weitere Tage zu behandeln."); 
            } else { 
                output("`n`@Golinda wird dich nicht länger behandeln."); 
                unset($config['healer']); 
            } 
        } 
        $session['user']['donationconfig']=serialize($config); 
        if ($session['user']['hauntedby']>""){ 
            output("`n`n`)Du wurdest von {$session['user']['hauntedby']}`) heimgesucht und verlierst eine Runde!"); 
            $session['user']['turns']--; 
            $session['user']['hauntedby']=""; 
        } 
        $session['user']['drunkenness']=0; 
        $session['user']['bounties']=0; 
        // Buffs from items
        $sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk') AND owner=".$session[user][acctid]." ORDER BY id";
        $result=db_query($sql);
        for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
            if (strlen($row[buff])>8){
                $row[buff]=unserialize($row[buff]);
                $session[bufflist][$row[buff][name]]=$row[buff];
                if ($row['class']=='Fluch') output("`n`G$row[name]`G nagt an dir.");
                if ($row['class']=='Geschenk') output("`n`1$row[name]`1: $row[description]");
            }
            if ($row[hvalue]>0){
                $row[hvalue]--;
                if ($row[hvalue]<=0){
                    output(" Aber nur noch heute.");
                }
            }
        }
        if (db_num_rows($result)>0) {
            db_query("DELETE FROM items WHERE (class='Fluch' OR class='Geschenk') AND owner=".$session[user][acctid]." AND hvalue <= 1");
            db_query("UPDATE items SET hvalue=hvalue-1 WHERE (class='Fluch' OR class='Geschenk') AND owner=".$session[user][acctid]);
        }
        // die magischen Steine
        $st = $session['user']['stone'];
        switch ( $st ) {
            case 4:
            $st2 = $stone[$st];
            output("`n Weil Du den $st2 besitzt, ist Deine Verteidigung geschwächt.`0");
            $session[bufflist][$st2] = array("name"=>"$st2",
                                        "rounds"=>999,
                                        "wearoff"=>"Du kannst Dich wieder verteidigen.",
                                        "defmod"=>0.85,
                                        "atkmod"=>1,
                                        "roundmsg"=>"Der $st2 macht Dich verletzbar.",
                                        "activate"=>"defense");
            break;
            case 6:
            $st2 = $stone[$st];
            output("`n Weil Du den $st2 besitzt, ist Dein Angriff gehemmt.`0");
            $session[bufflist][$st2] = array("name"=>"$st2",
                                        "rounds"=>999,
                                        "wearoff"=>"Dein Angriff ist wieder mutig.",
                                        "defmod"=>1,
                                        "atkmod"=>0.8,
                                        "roundmsg"=>"Der $st2 nimmt Dir Mut.",
                                        "activate"=>"defense");
            break;
            case 7:
            $st2 = $stone[$st];
            output("`n Weil Du den $st2 besitzt, sind Deine Angriffs- und Verteidigungswerte erhöht.`0");
            $session[bufflist][$st2] = array("name"=>"$st2",
                                        "rounds"=>999,
                                        "wearoff"=>"Deine Kräfte schwinden wieder.",
                                        "defmod"=>1.1,
                                        "atkmod"=>1.1,
                                        "roundmsg"=>"Der $st2 gibt Dir Kraft.",
                                        "activate"=>"defense");
            break;
            case 8:
            $st2 = $stone[$st];
            output("`n Weil Du den $st2 besitzt, ist leidet Dein Angriff und Deine Verteidigung.`0");
            $session[bufflist][$st2] = array("name"=>"$st2",
                                        "rounds"=>999,
                                        "wearoff"=>"Deine Kräfte kehren zurück.",
                                        "defmod"=>0.9,
                                        "atkmod"=>0.9,
                                        "roundmsg"=>"Der $st2 nimmt Dir Kraft.",
                                        "activate"=>"defense");
            break;
            case 9:
            output("`n Weil Du $stone[$st] besitzt, bekommst Du 3 Charmepunkte.`0");
            $session['user']['charm']+=3;
            break;
            case 10:
            output("`n Weil Du $stone[$st] besitzt, verlierst Du 2 Charmepunkte.`0");
            $session['user']['charm']-=2;
            break;
            case 11:
            output("`n Weil Du den $stone[$st] besitzt, erhälst Du einen Edelstein.`0");
            $session['user']['gems']++;
            break;
            case 13:
            output("`n Weil Du den $stone[$st] besitzt, werden Dir 500 Gold gutgeschrieben.`0");
            $session['user']['gold']+=500;
            break;
            case 14:
            $st2 = $stone[$st];
            output("`n Weil Du den $st2 besitzt, ist Dein Angriff und Deine Verteidigung gestärkt.`0");
            $session[bufflist][$st2] = array("name"=>"$st2",
                                        "rounds"=>999,
                                        "wearoff"=>"Deine Kräfte schwinden.",
                                        "defmod"=>1.25,
                                        "atkmod"=>1.25,
                                        "roundmsg"=>"Der $st2 verleiht Dir Kraft.",
                                        "activate"=>"defense");
            break;
            case 15:
            output("`n Weil Du den $stone[$st] besitzt, steigt Deine Erfahrung um 5%.`0");
            $session['user']['experience']=round($session['user']['experience']*1.05);
            break;
            case 16:
            output("`n Weil Du $stone[$st] besitzt, verlierst Du alle Gefallen in der Unterwelt.`0");
            $session['user']['deathpower']=0;
            break;
            case 17:
            output("`n Weil Du den $stone[$st] besitzt, gewährt Dir Ramius 20 Gefallen.`0");
            $session['user']['deathpower']+=20;
            break;
            case 18:
            case 19:
            output("`n Du besitzt den nutzlosen $stone[$st].`0");
            break;
            case 21:
            output("`n Weil Du den $stone[$st] besitzt, kannst Du 3 zusätzliche Kämpfe bestreiten.`0");
            $session['user']['turns']+=3;
            break;
            case 22:
            output("`n Weil Du den $stone[$st] besitzt, verlierst Du 3 Waldkämpfe.`0");
            $session['user']['turns']-=3;
            break;
            case 23:
            output("`n Weil Du den $stone[$st] besitzt, wirst Du enthaltsam Leben. Du kannst nicht flirten.`0");
            $session['user']['seenlover']=1;
            break;
        }
    }
    if (strtotime(getsetting("lastdboptimize",date("Y-m-d H:i:s",strtotime("-1 day")))) < strtotime("-1 day")){ 
        savesetting("lastdboptimize",date("Y-m-d H:i:s")); 
        $result = db_query("SHOW TABLES"); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            list($key,$val)=each(db_fetch_assoc($result)); 
            db_query("OPTIMIZE TABLE $val"); 
        } 
    } 
} 
page_footer(); 
?> 

