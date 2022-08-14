
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
page_header("Osterfuchs");

if (!isset($session)) exit();



if ($_GET[op]==""){
output("`n`n`@De`&r O`^st`@er`&fu`^chs");
   output("`n`n `@Du wanderst, wieder einmal über die Wiese. Denn
   irgendwie will dir wieder nichts vor die Füße laufen. Deine
   Geduld hält nicht mehr lange. Doch du versuchst weiter dich
   zu beherrschen. Deswegen gehst du einfach weiter und weiter.
   Einen kurzen Moment schwanken deine Gedanken zu dem Osterfest
   im Dorfe. Deine Stimmung bessert sich kurz. Danach schaust du
   wieder nach einem Monster. Plötzlich bewegt sich etwas hinter
   einem Busch, du bist dir nicht sicher was es ist. Das führt
   auch zur Unsicherheit deiner Reaktion.`n`n
   <a href='forest.php?op=weiter'>Du gehst und schaust was
   dort ist.</a>`n`n
   <a href='forest.php?op=suchen'>Du drehst dich lieber um
   und suchst das Weite.</a>`n`n",true);
   addnav("","forest.php?op=weiter");
   addnav("","forest.php?op=suchen");
   addnav("Nachsehen","forest.php?op=weiter");
   addnav("Weiter gehen","forest.php?op=suchen");
   $session[user][specialinc] = "ofuchs.php";
}

if ($_GET[op]=="weiter"){
   output("`n`n `@Du bist verwundert und denkst: `&\"Super, endlich
   wieder etwas zum schlachten.\" `@Du schaust nicht lang, sondern
   springst einfach drauf zu. Das seltsame Wesen mustert dich aus
   seinen gelblichen müden Augen. Du musterst es weiter, mit
   gehobener Waffe. Plötzlich fängt das Wesen wie verrückt an zu
   kreischen. Du kippst vor Schreck um und liegst mit deinem Hintern
   im Dreck. Das Wesen schreit weiter wie am Spies und läuft im
   Kreise umher. Dann erst erkennst du die rötliche und weiße Färbung.
   Du stammelst: `&\"Ein Fuchs!?\" `@Das Wesen bleibt ruckartig stehen
   und antwortet dir: `&\"Na, klar bin ich ein Fuchs. Was hast du
   erwartet, einen Hasen?\" `@Du zwickst dich in den Arm und schreist
   kurz auf. Es ist kein Traum! Ein sprechender, gehender Fuchs mit
   einem Sack auf dem Rücken.`n`n
   <a href='forest.php?op=fragen'>Du fragst den Fuchs was er da
   tut.</a>`n`n<a href='forest.php?op=weg'>Du stehst auf und
   verschwindest.</a>`n`n",true);
   addnav("","forest.php?op=fragen");
   addnav("","forest.php?op=weg");
   addnav("Den Fuchs fragen","forest.php?op=fragen");
   addnav("Einfach weggehen","forest.php?op=weg");
   $session[user][specialinc] = "ofuchs.php";
}

if ($_GET[op]=="suchen"){
   output("`n`n `@Du willst nicht wissen, was das ist. Wahrscheinlich
   wieder so ein verrücktes Wesen. Oder schlimmer, der grüne Drache.
   Deswegen läufst du der Sonne entgegen.`n`n");
   addnav("Zurück zur Osterwiese","forest.php");
}

if ($_GET[op]=="fragen"){
   $sack=e_rand(1,3);
   $sack2=($sack*500);
   output("`n`n `&\"Ähm, was machst du, bitte, da?\"`@ fragst du immer
   noch irgendwie nicht ganz bei der Sache. Der Fuchs schaut dich
   schräg an dann hörst du wie einige Worte aus der Schnauze kommen:
   `&\"Hallo? Kann es sein, dass du mich nicht kennst? Ich bin der
   Osterfuchs. Heute verstecke ich die ganzen Eier.\" `@Plötzlich fängst
   du an zu lachen. Du findest es herrlich; Ein Fuchs der Eier versteckt.
   Du wolltest erst eben wieder aufstehen, doch du bleibst lieber liegen
   und rollst über den Dreck und lachst weiter. Der Fuchs meint du wärest
   verrückt und lässt dir einige Eier liegen. Und verschwindet, weil er
   nichts mit verrückten Leuten zu tun haben will.`n`n
   Nach einiger Zeit stehst du auf, hebst die Eier auf und führst deinen
   Weg weiter fort. Dein Lachen schallt Meilen weit weiter. Aber du
   merkst nicht, dass die Eier, eigentlich Geldsäcke sind. Und so gehst
   du fröhlich lachend und mit mehr Vermögen weiter.`n`n");
   $session['user']['gold']+=$sack2;
   addnav("Zurück zur Osterwiese","forest.php");
}

if ($_GET[op]=="weg"){
   output("`n`n `@Du denkst nur an deine Mutter: `&\"Sprich nie mit Fremden,
   mein Kind.\" `@Diesen Rat befolgst du artig und verschwindest eilig. Du
   denkst immer nur `&\"Seltsames Wesen!\" `@und mit diesem Gedanken gehst du
   rasch weiter. Alles andere interessiert dich nicht. Du kannst diesen
   Zwischenfall nicht mehr vergessen. Deswegen gehst du lieber sofort zum
   nächsten Arzt, damit er dich untersucht. Es kann ja sein, das dir deine
   Fantasie einen Streich gespielt hat.`n`n");
   addnav("Zurück zur Osterwiese","forest.php");
}

$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);

page_footer();
?>

