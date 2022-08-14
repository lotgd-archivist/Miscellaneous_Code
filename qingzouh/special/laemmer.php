
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
#      Texte von Brenna Hravani     #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Lämmer");

if (!isset($session)) exit();

if ($_GET[op]==""){
   output("`n`c`@Die `&Schä`^ferin`c");
   output("`n`n`@Du erkundest gerade einen Pfad, als dir eine eingezäunte
   Wiese auffällt. Du könntest sie auch gar nicht verfehlen, da ein
   Ohrenbetäubender Lärm herrscht. Die Wiese ist voller blökender Lämmchen,
   kleiner weißer Fellbündel mit vier Beinen, die von einem wild kläffenden
   Hund zusammen getrieben werden. Am Rande steht eine hübsche Schäferin,
   die ihrem Hund laute Anweisungen zuruft. Du verstehst kein Wort, führst
   es jedoch auf den allgemein herrschenden Geräuschpegel zurück. Interessiert
   an dem ganzen Trara trittst du näher und lehnst dich gegen den Zaun.`n`n");
   addnav("Weiter","forest.php?op=weiter");
   $session[user][specialinc] = "laemmer.php";
}

if ($_GET[op]=="weiter"){
   output("`n`n`@Das Mädchen mit den komischen Zöpfen und dem seltsamen Stab in
   der Hand wird auf dich aufmerksam und schenkt dir ein schmelzendes Lächeln.
   `&\"Seid gegrüßt!\" `@rufst du ihr zu, um den Lärm zu übertönen, und erwiderst
   ihr Lächeln. `&\"Tach ..\" `@entgegnet die Schäferin. `&\"Komma bei mich bei!\"
   `@Bitte was?, denkst du dir und verstehst kein Wort. `&\"Ey, gez komma hea!\"
   `@wiederholt sie, als du nicht reagierst, und winkt dich zu sich. Ach, du
   sollst zu ihr kommen. Warum sagte sie das nicht gleich? Behände springst
   du über den Zaun und gehst zu ihr. Was sie wohl wollen könnte..`n`n");
   addnav("Weiter","forest.php?op=weiter2");
   $session[user][specialinc] = "laemmer.php";
   }

if ($_GET[op]=="weiter2"){
   output("`n`n`&\"Is datt nichn schicket Remmidemmi?\" `@fragt sie dich und zeigt
   auf das Gewusel vor euch. Verwirrt blickst du sie an, verstehst kein Wort
   von dem, was sie da sagt. `&\"Waddema eemt.\" `@meint sie und geht auf den
   Haufen zu. Sie erteilt ihrem Hund ein paar Befehle und dieser trennt eins
   der süßen Lämmchen von der Herde und auf sie zu. Scheinbar ohne große
   Anstrengung hebt sie das Tierchen hoch und kommt wieder zu dir zurück.
   `&\"Na los, mach datt Mäh ma ei.\" `@Du hast nicht die leiseste Ahnung, was
   sie wollen könnte, fragst dich eher, was für eine Sprache sie da spricht.
   Die Schäferin rollt mit den Augen, als du einfach nicht reagierst, sondern
   nur dumm aus der Wäsche schaust. `&\"Du solls datt Mäh ei machen.\" `@wiederholt
   sie und streichelt das kleine Schaf. Ach so, du sollst es streicheln.
   Zumindest glaubst du, dass sie das meint.`n`n
   <a href='forest.php?op=streicheln'>Willst du das Schaf streicheln?</a>`n`n
   <a href='forest.php?op=n_streicheln'>Lieber nicht das Schaf streicheln.</a>`n`n",true);
   addnav("","forest.php?op=streicheln");
   addnav("","forest.php?op=n_streicheln");
   addnav("Schaf streicheln","forest.php?op=streicheln");
   addnav("Schaf nicht streicheln","forest.php?op=n_streicheln");
   $session[user][specialinc] = "laemmer.php";
}


if ($_GET[op]=="streicheln"){
   $zufall=e_rand(1,10);
       switch($zufall) {
       case 1:
       case 2:
       output("`@Zögernd streckst du die Hand aus, stets darauf gefasst, diese
       blitzschnell wieder zurück zu ziehen. Du berührst das weiche Fell des
       Lämmchens, welches dich aus süßen Knopfaugen ansieht und ein leises
       Määäh von sich gibt. Völlig hingerissen versenkst du deine Finger in
       den weißen Locken und nimmst dem Mädchen das kleine Bündel ab. Ein
       ängstlicher Blick gilt jedoch dem Hund, der sich leise knurrend nähert.
       Die Schäferin grinst und meint: `&\"Der tut nix. Der will nur spieln.\"
       `@Einen Satz, den du sogar verstehst, doch siehst du sie eher zweifelnd an.`n`n

       Diese Zweifel sind sogar berechtigt. Scharfe Zähne graben sich in deinen
       Arm und zerren daran. Du jaulst vor Schmerz auf und lässt das Lamm fallen,
       das - glücklicherweise unverletzt - zurück zur Herde trottet. Die Schäferin
       baut sich vor dem Hund auf und schimpft: `&\"Sitz! Sons krisse ein vor die
       Omme!\" `@Der Hund lässt los, kneift den Schwanz ein und setzt sich mit
       hängendem Köpfchen hin. Du reibst dir den schmerzenden Arm, als das Mädchen
       sich an dich wendet: `&\"Tut mich leid.\" `@Sie zuckt mit den Schultern und
       geht mit ihrem Hund davon. Kein sonderlich denkwürdiges, aber dafür
       lehrreiches Zusammentreffen. Dein Ausländisch hat sich auf jeden Fall verbessert.`n`n");
       $session['user']['experience']*=1.05;
       $session['user']['hitpoints']*=.95;
       addnav("Zurück zum Wald","forest.php");
       break;

       case 3:
       case 4:
       case 5:
       case 6:
       case 7:
       case 8:
       case 9:
       case 10:
       output("`@Zögernd streckst du die Hand aus, stets darauf gefasst, diese
       blitzschnell wieder zurück zu ziehen. Du berührst das weiche Fell des
       Lämmchens, welches dich aus süßen Knopfaugen ansieht und ein leises
       Määäh von sich gibt. Völlig hingerissen versenkst du deine Finger in
       den weißen Locken und nimmst dem Mädchen das kleine Bündel ab. Ein
       ängstlicher Blick gilt jedoch dem Hund, der sich leise knurrend nähert.
       Die Schäferin grinst und meint: `&\"Der tut nix. Der will nur spieln.\"
       `@Einen Satz, den du sogar verstehst, doch siehst du sie eher zweifelnd an.`n`n

       Wie sich herausstellt, waren die Zweifel jedoch völlig unberechtigt. Nach
       einem scharfen Blick von ihr, wedelt der Hund einmal kurz mit der Rute
       und lässt sich zu ihren Füßen nieder. Begeistert kraulst du das possierliche
       Tierchen und spielst mit ihm. Schmunzelnd beobachtet dich das Mädchen und
       krault derweil den Schäferhund. Die Stunden dort zaubern ein strahlendes
       Lächeln auf dein Gesicht und du verabschiedest dich glücklich. `&\"Mach gut!\"
       `@meint die Schäferin und wendet sich der Herde zu. `&\"Glück auf!\" `@Ein letzter
       Wink und sie ist weg. Glück was?, denkst du dir, sinnst aber nicht länger
       darüber nach und gehst ebenfalls.");
       $session['user']['charm']+=5;
       $session['user']['turns']-=5;
       addnav("Zurück zum Wald","forest.php");
       break;
       }
}

if ($_GET[op]=="n_streicheln"){
   output("`n`n`@Nicht sicher, was du von dem ganzen Kauderwelsch halten sollst,
   was die Schäferin da von sich gibt, lässt du deine Hände bei dir.
   Kopfschüttelnd trittst du einen Schritt zurück und murmelst etwas
   Unverständliches. `&\"Dann nich..\" `@meint sie Schulterzuckend und geht mit
   dem Lämmchen auf den Armen von dannen. Du schaust ihnen nur kurz nach
   und machst dich ebenfalls auf den Heimweg.`n`n");
   $session['user']['experience']*=.95;
   addnav("Zurück zum Wald","forest.php");
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

