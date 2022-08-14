
<?

#####################################
#                                   #
#            Osterspezial           #
#            für den Wald           #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#        von Sefan Freihagen        #
#       mit Unterstützung von       #
#     Laserian, Amon Chan und mfs   #
#          Texte von Kerma          #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Osterschläge");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
if (!isset($session)) exit();
bild("osterschlage.jpg");
if ($_GET[op]==""){
   output("`n`c`@Di`&e O`^st`@ers`^ch`&lä`@ge`c");
   output("`n`n`@Du gehst, gehst und gehst deinen Weg bis dir so elend
   langweilig ist, dass du anfängst zu schreien. Deine Füße tragen dich
   weiter. Plötzlich kommst du in einem seltsamen Dorf an. Du hast dieses
   Dorf nie gesehen; du kratzt dich am Kopf. Plötzlich siehst du wie Leute
   aus den Gassen springen. Nur Männer! Du schaust entsetzt hinüber. Du
   denkst: `&\"Verdammt, was wollen die? Mit den Ruten?\" `@Die Männer mustern
   dich. Sie wissen nicht Recht, ob sie dich mit ihren Ruten schlagen sollen
   oder nicht, denn sie kennen dich nicht. Mit einem male kommen aus der
   nächsten Schenke einige Frauen heraus. Die Männer stürmen auf die Frauen
   zu und schlagen diese mit den Ruten. Zu deiner Verwunderung bekommen
   die Männer einige Goldstücke.`n
   Was tust du?`n`n
   <a href='forest.php?op=rute'>Eine Rute schnappen und Frauen hauen.</a>`n`n
   <a href='forest.php?op=abhauen'>Lieber abhauen bevor du geschlagen wirst.</a>`n`n",true);
   addnav("","forest.php?op=rute");
   addnav("","forest.php?op=abhauen");
   addnav("Mitmachen","forest.php?op=rute");
   addnav("Lieber abhauen","forest.php?op=abhauen");
   $session[user][specialinc] = "osterschlage.php";
}

if ($_GET[op]=="abhauen"){
   output("`n`n`@Du drehst dich um und läufst um dein Leben. Plötzlich stolperst
   du über eine Klippe und fällst hunderte von Metern tief. Man hört nur noch ein
   `^\"Platsch!\" `@und deine Innereien liegen verteilt am Grund. Im nächsten Moment
   bist du bei Ramius und bettelst um Gnade doch er erhört dich nicht.`n`n");
   $session['user']['alive']=0;
   $session['user']['hitpoints']=0;
   addnav("Ramius besuchen","shades.php");
   addnews($session[user][name]." `@landet `&als `^Brei `@bei `&Ramius.
   `^Nachdem `@er `&vor `^Bauern `@flüchten `&wollte.");
   }

if ($_GET[op]=="rute"){
   $goldstuecke=e_rand(50,100);
   $goldstuecke2=$goldstuecke*10;
   output("`n`n`@Du schnappst dir die nächste Rute, die an einem Haus angelehnt ist
   läufst auf eine Frau zu und schlägst sie. Plötzlich, anstatt Gold zu bekommen
   bekommst du eine Ohrfeige. Du schreist auf: `&\"Auu…! Was soll denn das?\" `@Die
   Frau erklärt dir, es soll symbolisch geschlagen werden und nicht Schläge mit
   Schmerzen. Denn so ein Schlag zeigt der Frau, dass ihre Gesundheit und Schönheit
   erhalten bleibt. Frauen die nicht geschlagen werden, sind dann meist beleidigt.
   Auf einmal verstehst du alles. Du läufst den Frauen hinter her und schlägst sie
   geschmeidig. Für jeden Schlag bekommst du einige Goldstücke. Am Schluss hast du `^"
   .$goldstuecke2." Goldstücke `@bekommen. Fröhlich gehst du wieder deinen gewohnten Weg,
   doch diesmal ohne Langeweile.`n`n");
   $session['user']['gold']+=$goldstuecke2;
   addnav("Zurück zum Wald","forest.php");
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

