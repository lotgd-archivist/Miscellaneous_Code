
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
#          Texte von Charina        #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Wildes Osterei");
if (!isset($session)) exit();

if ($_GET[op]==""){
   output("`n`n `@Plötzlich bleibst du stehen, mitten auf der Wiese: Da war doch etwas.. ein Geräusch?
   Suchend blickst du umher in dem eigentlich ungefährlichen Teil der Wiese, du bist ja eigentlich
   schon auf dem Heimweg, und hier kann dir nichts mehr passieren - denkst du!`n`n

   Und doch, da war ein Geräusch, es klang beinahe wie ein Wispern, eine leise, piepsige Stimme.
   Sehen kannst du nichts außer Blumen, mehr Blumen und immer weiter nur Wiese.`n`n

   Doch da... da ist es wieder, und du machst die Richtung aus, wo es her kommt... und siehst einen
   winzig scheinenden, dunkleren Punkt in den Blumen. Was ist das? Neugierig gehst du näher und
   tatsächlich, deine erschöpften Sinne, immerhin hast du den ganzen Tag den gefährlichsten Monstern
   nach gejagt, haben dich nicht getrogen!`n`n

   Ein Hase hockt da, klein, traurig anzusehen und... mitten auf der Wiese? Das kann nicht sein,
   denkst du dir, und beugst dich hinab zu dem Wesen, das dich ängstlich anschaut. \"Ich hab mich
   verlaufen..\" wispert es in dein Ohr, und das Schnäuzchen des Hasen mümmelt dabei, als hätte es
   selbst gesprochen. Redende Hasen? Ungläubig schüttelst du den Kopf, so etwas ist dir ja noch nie
   unter gekommen!`n`n

   Doch dann spricht es weiter: `&\"Wenn du mich nach Hause trägst, soll es dein Schaden nicht sein!`@\"
   Noch immer misstrauisch streckst du eine Hand nach dem Häschen aus, das dir sogleich vertrauensvoll
   entgegen kommt.`n`n

   Was tust du nun?`n
   Hältst du das Ganze für eine Fata Morgana, erschienen in deinem erschöpften Geist, und gehst weiter
   deines Weges?`n
   Oder nimmst du das Häschen mit und bringst es nach Hause, wofür du ja belohnt werden sollst?`n`n");
   addnav("Weiter gehen","forest.php?op=weiter");
   addnav("Nach Hause bringen","forest.php?op=nachhaus");
   $session[user][specialinc] = "haseiw.php";
}

if ($_GET[op]=="weiter"){
   output("`n`n `@Dafür habe ich keine Zeit, das ist nur eine Fantasie!`n`n
   Das Häschen beißt sich in deinem Bein fest, und du verlierst wegen der
   Wunde fast alle deine Lebenspunkte.`n`n");
   $session['user']['hitpoints']=1;
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="nachhaus"){
   output("`n`n `@Das arme Häschen! Ich werde es nach Hause bringen!`n`n
   Das Häschen ist dankbar, dass du es gerettet hast.`n`n
   Du bekommst 1000 Gold und 5 Edelsteine.`n`n");
   $session['user']['gold']+=1000;
   $session['user']['gems']+=5;
   addnav("Zurück zum Wald","forest.php");
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

