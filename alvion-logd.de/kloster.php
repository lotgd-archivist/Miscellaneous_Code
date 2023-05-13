
<?php
// Idee und Umsetzung
// Morpheus aka Apollon 
// 2006 für Morpheus.Lotgd(LoGD 0.9.7 +jt ext (GER) 3)
// Mail to Morpheus@magic.ms or Apollon@magic.ms
// gewidmet meiner über alles geliebten Blume
require_once "common.php";
addcommentary();
checkday();
if ($session['user']['alive']){ 
}else{
    redirect("shades.php");
}
addnav("Wege");
addnav("Z?Zurück zu den Klippen","klippen.php");
addnav("u?Zurück nach Alvion","village.php");
if ($session['user']['dragonkills']>=10 && !$session['user']['rp_only']){
    addnav("G?Ins Gebirge","berge.php");
}
addnav("Klosterhof");
// addnav("N?Norden - Der Tempel des Morpheus","morpheustempel.php");
// addnav("O?Osten - Der Tempel des Aeskulap","tempelderheilung1.php");
// addnav("S?Süden - Der Tempel der Artemis","artemistempel.php");
// addnav("W?Westen - Der Tempel des Apollon","apolltempel.php");
addnav("A?Der Tempel des Aeskulap","tempelderheilung1.php");
addnav("r?Der Tempel der Artemis","artemistempel.php");
addnav("p?Der Tempel des Apollon","apolltempel.php");
addnav("K?Zum Klostergarten","klostergarten.php");
addnav("I?Ins Klostergebäude","klosterhaus.php");
addnav("t?In den Glockenturm","klosterturm.php");
page_header("Das Kloster");
$session['user']['standort']="Das Kloster";
output("`^`c`bDer Klosterhof`b`c`n`@Du stehst im Hof eines Klosters, das von frommen Männern bewohnt wird, die ihr Leben und Wirken dem Dienst an den Göttern verschrieben haben. Das ist ihr Lehen, welches sie von den Göttern übertragen bekommen haben.`n");
output("Das Kloster wurde in exakter Ausrichtung nach den Himmelsrichtungen erbaut und sein Innenhof ist ein Atrium, das von einem Säulengang umrahmt wird. In jede Himmelsrichtung kannst du einen Durchgang entdecken, der in den Schrein eines Gottes führt, während der Eingang in den Hof, das Gebäude, sowie der Turm und der Garten in den Ecken innerhalb des Säulenganges liegen.`n`n In der Mitte des Atriums steht ein uralter Obelisk, an dem auf jeder Seite am unteren Rand eine helle Fläche zu sehen ist.");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("Auf ihr stehen die neusten Meldungen aus unserer Welt:`0`n`n`c`i$row[newstext]`i`c`n");
if (getsetting('activategamedate','0')==1) output("`qWir schreiben den `^".getgamedate()."`q im Zeitalter des Drachen.`n");
output("Die Uhr über dem Klostereingang zeigt `^".getgametime()."`q Uhr.`n");
output("Das heutige Wetter ist `^".$settings['weather']."`q.`0");
output("`n`n`%`@In der Nähe des Tores stehen einige Abenteurer und unterhalten sich:`n`n");
viewcommentary("klosterhof","Hinzufügen:",25,"sagt",1,1);
page_footer();
?>

