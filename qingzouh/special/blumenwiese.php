
<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Die Blumenwiese
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){

case "blume":
$session['user']['specialinc']="";
                output("`QFasziniert von der wunderschönen Pflanze wirfst du all deine Bedenken über Bord und gehst zu der Blume herüber. Neben ihr gehst du in die Knie und beugst dich zur Blüte herüber um daran zu schnuppern.");
                switch(e_rand(1,3)){
                case '1':
            output("Ganz begierig darauf welch wunderbaren Duft diese Pflanze wohl haben könnte beugst du dich hinab zur Blüte und schließt die Augen um den sinnlichen Duft auch richtig zu genießen. Dadurch, dass deine Augen geschlossen sind bemerkst du nicht wie sich die Pflanze auf einmal bedrohlich zu bewegen begann. Etwas raschelt.
Neugierig was den Laut verursachen konnte schlägst du die Augen auf und erblickst die große Blume, vor der du kniest und schnupperst, die plötzlich merkwürdige Geräusche von sich gab. Mehr überrascht als erschrocken willst du aufstehen und dich von der Blume entfernen, doch da beugt sich die Blüte auf einmal zu dir und du verschwindest in ihrem Rachen. Panisch versuchst du dich zu befreien, doch: sinnlos. Das Schmatzen der Pflanze ist noch weit zu hören, aber du kriegst davon nichts mehr mit, da du tot bist!");
            $session['user']['hitpoints']=0;
             $session['user']['alive']=false;
                        addnav("Tägliche News","news.php");
            addnews($session['user']['name']." wurde zur Hauptspeise einer Blume.");
            break;
            case '2':
            output("Du beugst dich zur Blüte und tust einen tiefen Atemzug um den verführerischen Duft der Blume in dich aufzunehmen. Ein wahrhaft sinnlicher Geruch, der dich im ersten Moment ganz benommen macht. Aber als du dich wieder aufrichtest und in den Wald verschwindest fühlst du dich deutlich erfahrener. Du erhältst 300 Erfahrung! ");

            $session['user']['experience']+=300;
            addnews($session['user']['name']." gewinnt erfahrung durch das Schnuppern an einer Blume.");
            break;
                        case '3':
            output("Ohne Böses zu ahnen lehnst du sich zur Blüte der Pflanze und schnupperst. Der Duft, der dir entgegenströmt macht dich ganz schläfrig. Plötzlich ahnst du, dass es sich bei der Blume um Schlafmohn handelt und du wohl wirklich einschlafen wirst. Doch tapfer kämpfst du gegen die Müdigkeit an, allerdings nur um den Kampf letzten Endes doch zu verlieren. Du verlierst alle deine verbliebenen Waldkämpfe!");

            $session['user']['turns']=0;
            addnews($session['user']['name']." schläft in einem Schlafmohnfeld im Wald ein.");
            break;
               }
  break;

case "wald":
        $session['user']['specialinc']="";
        output("`3`nDa dir das ganze nicht geheuer ist und du einfach keinen Sinn für wahre Schönheit hast zuckst du nur mit den Schultern und verschwindest im Wald. Du verlierst durch Trödeln einen Waldkampf!");
        if ($session['user']['turns']>1){
            $session['user']['turns']--;
        }else{
            $session['user']['turns']=0;
        }
    break;
   break;
    default:
default:
$spi;
        output("`&`n`nDeine Schritte führen dich auf eine wunderschöne Lichtung mitten im Wald. Ringsrum ein buntes Blumemeer, sodass du nicht so recht weißt, wo du auftreten sollst, da du den Zauber der Blumenwiese nicht zerstören magst.
Doch dann erblickst du eine etwas größere Pflanze in der Mitte der Wiese und vermagst dem Zauber nicht zu entgehen, der die in voller Blüte stehende Blume umgibt. `n`n");
        addnav("Zu den Blumen",$fn."?op=blume");
        addnav("zurück in den Wald",$fn."?op=wald");
    break;

}
?>

