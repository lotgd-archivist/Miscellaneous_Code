
<?php
require_once "common.php";

// Eine weiße Mauer zum Beschmieren, hier können Sprüche und Ähnliches angebracht werden.
// Erfordert : Textabschnitt aus "market.php" (gekennzeichnet durch "Mauer")
//
// by Maris (Maraxxus@gmx.de)

$wallchangetime=getsetting("wallchangetime","300");
$time=getsetting("wall_chgtime","0");
$oldtime=(strtotime($time));
$acttime=(strtotime(date("H:i:s")));
$newtime=$acttime-$oldtime;

if ($newtime>$wallchangetime) {
if ($_GET['op']=='write')
{
savesetting("wall_author",addslashes($session['user']['login']));
savesetting("wall_chgtime",date("Y-m-d H:i:s"));

$number=e_rand(1,37);

switch($number)
{
  case 1 :
  $message=$session['user']['login']." ist ".($session['user']['sex']?"die":"der")." Größte!";
  break;
  case 2 :
  $message=$session['user']['login']." zeigts Euch allen!";
  break;
  case 3 :
  $message=$session['user']['login']." herrscht hier!";
  break;
  case 4 :
  $message=$session['user']['login']." war hier!";
  break;
  case 5 :
  $message=$session['user']['login']." lässt ".($session['user']['sex']?"Männer":"Frauen")."herzen höher schlagen!";
  break;
  case 6 :
  $message=$session['user']['login']." hat hier das Sagen!";
  break;
  case 7 :
  $message=$session['user']['login']." tritt Euch allen in den Hintern!";
  break;
  case 8 :
  $message=$session['user']['login']." - von Freunden begehrt, von Feinden gefürchtet!";
  break;
  case 9 :
  $message=$session['user']['login']." ist ".($session['user']['sex']?"die":"der")." Schönste in der ganzen Stadt!";
  break;
  case 10 :
  $message=$session['user']['login']." ist jedermans Liebling!";
  break;
  case 11 :
  $message=$session['user']['login']." ist gottgleich!";
  break;
  case 12 :
  $message=$session['user']['login']." lässt die Erde erbeben!";
  break;
  case 13 :
  $message=$session['user']['login']." lehrt Euch das Fürchten!";
  break;
  case 14 :
  $message=$session['user']['login']." ist Euch überlegen!";
  break;
  case 15 :
  $message=$session['user']['login']." tötet Drachen wie kein Anderer!";
  break;
  case 16 :
  $message=$session['user']['login']." ist der Inbegriff von Eleganz und Schönheit!";
  break;
  case 17 :
  $message=$session['user']['login']." lässt andere ".($session['user']['sex']?"Frauen":"Männer")." vor Neid erblassen!";
  break;
  case 18 :
  $message=$session['user']['login']." ist schön und klug!";
  break;
  case 19 :
  $message=$session['user']['login']." kennt keine Furcht!";
  break;
  case 20 :
  $message=$session['user']['login']." überzeugt durch Witz und Charme!";
  break;
  case 21 :
  $message=$session['user']['login']." ist ein kleiner Sonnenschein!";
  break;
  case 22 :
  $message=$session['user']['login']." hilft alten Damen über die Straße!";
  break;
  case 23 :
  $message=$session['user']['login']." hat diese Mauer bemalt!";
  break;
  case 24 :
  $message=$session['user']['login']." ist zu schön für diese Welt!";
  break;
  case 25 :
  $message=$session['user']['login']." steckt man nicht in eine Schublade, sondern ins Schmuckkästchen!";
  break;
  case 26 :
  $message=$session['user']['login']." ist kein Vampir und glitzert trotzdem!";
  break;
  case 27 :
  $message=$session['user']['login']." ist heißer als ein Vulkan!";
  break;
  case 28 :
  $message=$session['user']['login']." liebt euch!";
  break;
  case 29 :
  $message=$session['user']['login']." ist ein Held, den nichts entstellt!";
  break;
  case 30 :
  $message=$session['user']['login']." verdient einen Schrein!";
  break;
  case 31 :
  $message=$session['user']['login']."'s Oma fährt im Hühnerstall Motorrad!";
  break;
  case 32 :
  $message=$session['user']['login']." besiegt euch alle beim Armdrücken!";
  break;
  case 33 :
  $message=$session['user']['login']." gründet eine Bar!";
  break;
  case 34 :
  $message=$session['user']['login']." hat eine wunderschöne Singstimme!";
  break;  
  case 35 :
  $message=$session['user']['login']." verschenkt Umarmungen!";
  break;  
  case 36 :
  $message=$session['user']['login']." ist ein Quell der Freude!";
  break;  
  case 37 :
  $message=$session['user']['login']." ist Eranyas Nr. 1!";
  break;    
}
savesetting("wall_msg",$message);
}

if ($_GET['op']=='change')
{
savesetting("wall_chgtime",date("Y-m-d H:i:s"));
$author = getsetting("wall_author","0");

$number=e_rand(1,37);

$sql = "SELECT sex FROM accounts WHERE login='".addslashes($author)."'";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);

switch($number)
{
  case 1 :
  $message=" ist ".($row['sex']?"die":"der")." Schwächste!";
  break;
  case 2 :
  $message=" kann nix!";
  break;
  case 3 :
  $message=" isst aus der Mülltonne!";
  break;
  case 4 :
  $message=" nervt gewaltig!";
  break;
  case 5 :
  $message=" bringt ".($row['sex']?"Männer":"Frauen")." zum Flüchten!";
  break;
  case 6 :
  $message=" hat hier nichts zu sagen!";
  break;
  case 7 :
  $message=" leckt Euch allen den Hintern!";
  break;
  case 8 :
  $message=" stinkt wie ein Iltis!";
  break;
  case 9 :
  $message=" ist ".($row['sex']?"die":"der")." Hässlichste in der ganzen Stadt!";
  break;
  case 10 :
  $message=" ist dumm wie ein Stück Brot!";
  break;
  case 11 :
  $message=" verträgt kein Ale!";
  break;
  case 12 :
  $message=" ist ein Bauerntrampel!";
  break;
  case 13 :
  $message=" ist der Witz der Stadt!";
  break;
  case 14 :
  $message=" kämpft wie ein ".($row['sex']?"Kind":"Mädchen")."!";
  break;
  case 15 :
  $message=" ist dick und faul!";
  break;
  case 16 :
  $message=" - am liebsten sehen wir ".($row['sex']?"sie":"ihn")." von hinten!";
  break;
  case 17 :
  $message=" hat ja gar keine Ahnung!";
  break;
  case 18 :
  $message=" bedeutet: großer Kopf und wenig Verstand!";
  break;
  case 19 :
  $message=" - was will man bei solch einem Namen auch schon erwarten?";
  break;
  case 20 :
  $message=" liegt gerade besoffen hinter dieser Mauer!";
  break;
  case 21 :
  $message=" hat eine furchtbar kurze... oh, ein Vögelchen!";
  break;
  case 22 :
  $message=" nascht heimlich alle Kekse auf, wenn niemand guckt!";
  break;
  case 23 :
  $message=" hat hier rumgeschmiert und muss die Mauer nun neu streichen!";
  break;
  case 24 :
  $message=" ist ab heute ein Einhorn. In rosa!";
  break;
  case 25 :
  $message=" verirrt sich ständig im Wald!";
  break;
  case 26 :
  $message=" ist vom Donnerbalken gefallen!";
  break;
  case 27 :
  $message=" hat heimlich Liebesbriefe verschickt!";
  break;
  case 28 :
  $message=" trägt gerne rosa Plüschunterwäsche!";
  break;
  case 29 :
  $message=" spielt noch mit Puppen!";
  break;
  case 30 :
  $message=" hat Angst vor weißen Mäusen!";
  break;
  case 31 :
  $message=" verträgt keinen Schnaps!";
  break;
  case 32 :
  $message=" rollt sich gerne in Bettdecken ein und spielt Raupe!";
  break;
  case 33 :
  $message=" schummelt beim Mau-Mau!";
  break;
  case 34 :
  $message=" sucht ".($row['sex']?"ihre":"seine")." Würde!";
  break;  
  case 35 :
  $message=" hat ein Problem mit hüfthohen Ziegelmauern in Weiß!";
  break;  
  case 36 :
  $message=" klappt die Bürgersteige hoch!";
  break;  
  case 37 :
  $message=" hat die Erdbeertörtchen gestohlen!";
  break;      
}
$message=$author.$message;
savesetting("wall_msg",$message);
}
redirect("market.php");
}
else redirect("market.php?op=toolate");
?>

