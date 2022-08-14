
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
#           Texte von Kerma         #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Das Osterrad");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("osterrad.jpg");

if (!isset($session)) exit();

if ($_GET[op]==""){
   output("`n`c`@Das `&Oster`^rad`c");
   output("`n`n`@Du wanderst entzückt umher. Denn dir ist nicht ganz Lust nach jagen.
   Plötzlich rollt dir vor den Füßen etwas großes Brennendes vorbei. Du schreckst
   zurück. Dann bemerkst du, dass jede Sekunde so ein Rad herrunter rollt.`n
   Was sollst du tun?`n`n
   <a href='forest.php?op=fortsetzen'>Versuchen den Weg fortzusetzen.</a>`n`n
   <a href='forest.php?op=suchen'>Nach den Verursachern suchen.</a>`n`n",true);
   addnav("","forest.php?op=fortsetzen");
   addnav("","forest.php?op=suchen");
   addnav("Den Weg fortsetzen","forest.php?op=fortsetzen");
   addnav("Nach Verursachern suchen","forest.php?op=suchen");
   $session[user][specialinc] = "osterrad.php";
}

if ($_GET[op]=="fortsetzen"){
switch(e_rand(1,5)){
case 1:
   output("`n`n`@Du versuchst durch die Lücken zwischen 2 Rädern zu springen.
   Auf einmal spürst du das dich etwas heißes erfasst hat. Und im nächsten
   Moment bist du wegen deiner Rüstung an dem Rad hängen geblieben und wirst
   runter gezogen. Danach verbrennst du wie ein Häufchen Elend. Und nun bist
   du bei Ramius. Und du hoffst, dass er verkohlte Tote nett findet. Weil
   sonst kommst du da nicht mehr raus.`n`n");
   $session['user']['alive']=0;
   $session['user']['hitpoints']=0;
   $session['user']['donation']+=5;
   addnav("Ramius besuchen","shades.php");
   addnews($session[user][name]." findet sich verkohlt bei Ramius wieder!");
break;
case 2:
case 3:
case 4:
case 5:
   output("`@Du versuchst durch die Lücken zwischen den Rädern zu springen um weiterzukommen.
   Trotz einigen Stolperern und Ausrutschern gelingt es dir an dem gefährlichen Stück vorbeizukommen und du kannst deinen Weg fortsetzen.");
   addnav("Wald","forest.php");
break;
   }
}
if ($_GET[op]=="suchen"){
   $eier=e_rand(5,10);
   output("`n`n`@Du gehst den Hang hoch und dann siehst du eine Gruppe von
   elendigem Bauernvolk. Du sprichst mit ihnen und fragst was sie da machen.
   Sie erklären dir, dass es ein Brauch ist. Um von den Göttern zu erfahren
   ob deren Ernte nach Ostern besser wird als die vorherige. Du fragst dich
   dann, warum die nicht etwas tun was für die Familie lustiger ist. Danach
   erklärst du ihnen Ostern. Mit dem Hasen der die Eier versteckt. Natürlich
   gefärbte Eier. Die Bauern halten dich für verrückt aber sie versuchen es.
   Und dann kommt heraus, dass deine Art von Fest viel besser ist als ihr
   altertümlicher Brauch. Deswegen bekommst du sofort `^".$eier." Eier `@geschenkt,
   die du dankend annimmst. Später erst merkst du, dass es sich um Edelsteine
   handelt. Dann denkst du. `&\"Edelhühner? Neee…\" `@Du glaubst das nicht und
   setzt deinen Weg langsam fort.`n`n");
   $session['user']['gems']+=$eier;
   addnav("Zurück zum Wald","forest.php");
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

