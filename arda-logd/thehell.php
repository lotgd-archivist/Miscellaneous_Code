<?

//Idee,Texte und Umsetzung © von Hecki
//Erstmals erschienen auf http://www.cop-logd.de
//Anleitung:
//In der shades.php folgenden Link an beliebiger stelle einfügen:
//   addnav("Die Hölle","thehell.php");
//
// Da der Kiosk des Todes nich von mir ist,werde ich ihn auch nicht hier mitreinstellen.
// Demnächst kommen noch ein paar weitere Gebäude rein!
// Die Einbauanleitung für den Baum des Todes, steht in selbiger.
require_once "common.php";
page_header("Die Hölle");
if (e_rand(1,15)==1) redirect("thehell.php?op=magma");
output("`qDu verlässt das Schattenreich durch einen dunklen Tunnel, am Ende des Tunnel erkennst du ein helles Licht... Das wird doch nicht etwa??...`n`n");
output("Doch deine Euphorie verfliegt als du bemerkst das das Licht nicht weiß ist, sondern gelb, rötlich. Du stehst jetzt mitten in der Hölle und die Hitze ist schier unerträglich.");
output("Du siehst einen reissenden Fluss aus Magma, von der Decke tropft ebenfalls Magma, du solltest aufpassen wo du hintrittst.");
output("Aber all das hällt dich nicht ab, dich hier umzuschauen.`n`n");

addnav("Die Hölle");
addnav("Baum des Todes","treeofdeath.php");

//addnav("Kiosk des Todes","dink.php");

addnav("Tunnelwege");
addnav("`@Zurück zu den Schatten","shades.php");
addnav("Feuerfluss","katakomp.php?op=Feuer");

if ($HTTP_GET_VARS[op]=="magma"){
      output("`$ Abgelenkt und unvorsichtig wie du bist siehst du nicht das sich ein Tropfen heisser Magma von der Decke löst und auf dich zu rast.`n Erst als es zuspät ist fühlst du die Hitze die durch deinen toten Körper wandert.`n`n`8 Du verlierst 5 Seelepunkte und solltest dich schleunigst vom Fleck bewegen!`n`n");
        $session[user][soulpoints] -= 5;
        //addnav("Zurück in die Hölle","thehell.php");
   if ( $session[user][soulpoints] <= 0 ) {
        output("`$ Dieser Tropfen hat dir den Rest gegeben!`n`8 Du verlierst alle restlichen Grabkämpfe!`n`n`0");
        $session[user][gravefights] = 0;
        //addnav("Zurück in die Hölle","thehell.php");
        }
        }
addcommentary();
                viewcommentary("thehell","Gequälte Laute",10,"spricht gequält",true);



if ($session[user][superuser]>=2){
addnav("Adminbereich");
addnav("@?Admin Grotte","superuser.php");
}
//if ($session[user][superuser]>=2) addnav("$?Neuer Tag","newday.php");


page_footer();
?>