<?

#####################################
#                                   #
#            Osterspezial           #
#            für den Wald           #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#        von Sefan Freihagen        #
#       mit Unterstützung von       #
#     Laserian, Amon Chan und mfs   #
#      Texte von Brenna Hravani     #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################

require_once "common.php";
page_header("Der Vogel");
/*
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
*/

if (!isset($session)) exit();
// bild("vogel.jpg"); 
page_header("Der Vogel");


if ($_GET[op]==""){
        output("`n`c`b`n`@De`&r `^Vo`&g`@el`n`c`b`n");
        output("`@Auf deiner Suche nach Gestalten, an denen du deine Kampfeskunst erproben kannst,
        entdeckst du plötzlich ein riesiges Nest. Nicht einfach nur riesig, nein, eher gigantisch
        die Ausmaße. `&\"Welcher Vogel baut denn solche Nester?\" `@fragst du dich, während dir von
        dem Anblick noch immer die Kinnlade auf der Brust hängt. Wild entschlossen das heraus zu
        finden näherst du dich dem Gebilde aus Reisig und du traust deinen Augen kaum, ganzen
        Ästen. Haushoch ragt es über dir auf und du wirst klettern müssen, wenn du wissen willst,
        was sich darin befindet.`n`n");
        addnav("Ja, ich klettere!","forest.php?op=klettern");
        addnav("Nein, reine Zeitverschwendung!","forest.php?op=weg");
        $session[user][specialinc] = "vogel.php";
        }

if ($_GET[op]=="weg"){
        output("`n`n`@Die Anstrengung, die der Aufstieg wohl kosten würde, und deine Höhenangst lassen
        dich dem Nest den Rücken kehren. `&\"Wer weiß was das ist.. Lieber nicht!\" `@murmelst du vor
        dich hin und entfernst dich wieder. Sollten andere lebensmüde Leute doch nachsehen. Ohne
        einen letzten Blick zurück zu werfen, machst du dich wieder auf die Suche nach neuen
        Opfern.`n`n");
        addnav("Weiter gehen","forest.php");
        }

if ($_GET[op]=="klettern"){
        output("`n`n`@Der Aufstieg gestaltet sich leichter als erwartet, denn überall bieten sich gute
        Haltemöglichkeiten für deine Hände und Füße. Doch kaum schaffst du es einen Blick über
        den Rand zu werfen, da fällst du vor Schreck beinah rückwärts wieder herunter. Im letzten
        Moment schaffst du es noch dich fest zu halten und setzt dich auf den Rand des Nests. Du
        reibst dir die Augen, kannst einfach nicht fassen, was du da siehst. Erneut landet deine
        Kinnlade auf der Brust und du kommst aus dem Staunen gar nicht mehr heraus. Süße, kleine
        Häschen, die geschäftig umher hoppeln und zahllose bunte Eier von einem Ort zum anderen
        tragen, scheinen dich gar nicht zu bemerken.`n`n
        
        Eine Weile hockst du einfach nur da und starrst auf das rege Treiben. Noch immer kannst
        du es nicht fassen, denn eigentlich hattest du den Osterhasen immer für ein Ammenmärchen
        gehalten. `&\"Ob die die Eier wohl selber legen?\" `@sinnierst du vor dich hin. `&\"Oder hier
        ist irgendwo eine Legebatterie versteckt!\" `@Letzteres erscheint dir wahrscheinlicher,
        denn eierlegende Hasen? Haha! Selten so gelacht! Und doch siehst du kein einziges Huhn,
        welches die ganzen Eier hätte legen können. Ob wohl doch die Hasen...?!?`n`n");
        addnav("Nachsehen!","forest.php?op=nachsehen");
        addnav("Bin ich denn verrückt?","forest.php?op=weg2");
        $session[user][specialinc] = "vogel.php";
        }

if ($_GET[op]=="weg2"){
        $runden=e_rand(1,3);
        output("`n`n`@Du bist dir sicher: Wenn du noch länger hier verweilst, würdest du dem Wahnsinn
        anheim fallen, deswegen machst du dich geschwind an den Abstieg. `&\"Nichts wie weg!\"
        `@brummelst du derweil und springst das letzte Stück zu Boden. Dabei verstauchst du dir
        den Knöchel und verlierst ein paar Lebenspunkte. Humpelnd verlässt zu das Gelände und
        suchst schleunigst das Weite. In der Zeit, die du da oben beim Starren vertrödelt hast,
        hättest du locker einige Monster erledigen können. Du verlierst `^".$runden." Waldkämpfe`@.`n`n");
        $session['user']['turns'] -=$runden;
        $session['user']['hitpoints'] -=$runden*15;
        if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
        addnav("Weiter gehen","forest.php");
        }

if ($_GET[op]=="nachsehen"){
        output("`n`n`@Das Rätsel musste doch zu lösen sein! Mutig - oder gar völlig verrückt? - machst
        du dich an den Abstieg gen Nestmulde. Dein Erscheinen löst bei den plüschigen Häschen
        wildes Chaos und Gekreische aus. Kreischende Hasen? Ja, ganz eindeutig: Die süßen Dinger
        kreischen, dass ihr fast das Trommelfell platzt! Um die Lautstärke zu dämpfen, hältst du
        dir die Ohren zu und beobachtest dabei, wie sich die panischen Tierchen auf einen Haufen
        knubbeln. Jeder versucht sich hinter dem anderen zu verstecken. Um den Grund für die
        plötzliche Panik heraus zu finden, schaust du dich genauer um, kannst jedoch nichts
        entdecken. Dich können sie nicht meinen, denn immerhin kommst du ja in friedlicher
        Absicht.`n`n
        
        Dann dringt ein dumpfes Geräusch an deine nach wie vor verschlossenen Ohren. Fast klingt
        es wie das Schlagen von Flügeln, von wirklich großen Flügeln! Du nimmst die Hände von den
        Ohren, um besser hören zu können, da fällt plötzlich ein Schatten über dich. Im ersten
        Moment erstarrst du, willst gar nicht wissen, was da hinter dir steht. Doch nimmt deine
        Neugier überhand und du drehst dich langsam um. Das Erste was du siehst sind riesige
        Brustfedern. Wie in Zeitlupe legst du nun den Kopf in den Nacken, schaust nach oben und
        blinzelst einige Male, weil du deinen Augen einfach nicht trauen willst. Vor dir steht
        ein überdimensionales Huhn und schwarze, punktförmige Augen scheinen dich böse an zu
        starren!`n`n");
        addnav("Panisch versuchen zu fliehen!","forest.php?op=weg3");
        addnav("Ziehe deine Waffe!","forest.php?op=waffe");
        addnav("Erstarre zur Salzsäule!","forest.php?op=salz");
        $session[user][specialinc] = "vogel.php";
        }

if ($_GET[op]=="weg3"){
        output("`n`n`@Nachdem du den ersten Schock überwunden hast, machst du auf dem Absatz kehrt und
        rennst - nun selbst in blinder Panik - vor dem Vieh davon. Doch schon bald merkst du,
        dass du in einer Sackgasse sitzt! Kein Ausweg weit und breit! Hinter dir hörst du schon
        das Scharren der riesigen Krallen von dem Untier und dir bricht der Angstschweiß aus.
        Wohin nur? Das Nest ist massiv, nirgends ein Loch, in dem du dich verstecken könntest.
        Auch die Puschelhäschen sind dir keine große Hilfe, krabbeln noch immer über einander
        hinweg, um außer Reichweite zu bleiben. Ob vor dir oder dem Riesenhuhn ist allerdings
        immer noch nicht ganz ersichtlich. `&\"Nur raus hier!\" `@denkst du und fängst hektisch an
        zu klettern. Doch vergeblich: Das Monster ist schneller als du und verwechselt dich
        anscheinend mit einem Korn. Du bist tod!`n`n");
        $session['user']['hitpoints'] =0;
        addnav("Ramius besuchen","shades.php");
        addnews($session[user][name]." `@ist `&nun `^der `@Grund `&für `^starke `@Verdauungsprobleme `&eines
        `^Huhns!");
        }

if ($_GET[op]=="waffe"){
        output("`n`n`@Nicht lange und du schüttelst den Schock von dir ab. Von so einem Federvieh
        lässt du dir bestimmt keine Angst einjagen! Das Huhn fertig zu machen ist für dich
        doch eine Kleinigkeit! Ruck Zuck hast du dein Schwert gezogen und stellst dich
        kampfbereit vor das gefederte Riesenetwas. Nun ist es an ihm erschrocken zurück zu
        zucken, denn anscheinend hat es nicht mit Gegenwehr gerechnet. Du fuchtelst mit
        deiner Waffe ein bisschen vor dem Schnabel des Huhns herum, doch hat das anscheinend
        das gegenteilige Ergebnis, als geplant. Das Riesenhuhn scheint nun eher wütend, denn
        ängstlich zu sein, und mit einem gezielten Picken reißt es dir dein Schwert aus der
        Hand. Dabei verletzt es dich schwer und verlierst fast all deine Lebenspunkte. Doch
        verschluckt das dumme Ding deine Waffe und erstickt daran. Als es leblos zu Boden
        fällt, kannst du gerade noch ausweichen, damit du nicht von dem massigen Körper
        erschlagen wirst. `n`n
        
        Kurz entschlossen holst du dir deine Waffe zurück und machst diese sauber. Sobald du
        damit fertig bist bemerkst du, dass du plötzlich von den ganzen Flauschhasen, deren
        Anwesenheit dir gänzlich entfallen war, umgeben bist. Süße, kleine Knopfaugen himmeln
        dich an und eins der Häschen zuppelt an deinem Hosenbein. `&\"Du hast uns gerettet!
        Wie können wir dir danken?\" `@piepst es dir von unten entgegen. Bescheiden wie du bist,
        winkst du ab und meinst: `&\"Ihr braucht mir nicht zu danken.\" `@Die kleinen Tierchen
        brechen in ohrenbetäubenden Jubel aus und du strahlst über das ganze Gesicht. Da es
        keinen Fluchtweg gibt, schlägst du kurzerhand ein Loch in die Nestwand, durch die die
        Karnickel auch direkt entschwinden. Nur eins verweilt noch kurz und reicht dir eines
        der Eier, die du anfangs gesehen hast, dann ist auch dieses verschwunden. `n`n
        
        Neugierig öffnest du das Ei und findest `^5 Edelsteine `@darin. Du steckst sie ein und
        machst dich glücklich ebenfalls auf den Heimweg. Für die Rettung der possierlichen
        Tiere erhältst du `^5 Charmepunkte`@. Durch diese ganze Aktion fühlst du dich
        erfahrener. `n`n");
        $session['user']['gems']+=5;
        $session['user']['charm']+=5;
        $session['user']['experience']*=1.05;
        addnews($session[user][name]." `@rettete `&einige `^Nager `@aus `&der `^Gefangenschaft `@eines
        `&Riesenhuhns.");
        addnav("zurück","forest.php");
        }

if ($_GET[op]=="salz"){
        output("`n`n`@Der Schock sitzt dir tief in den Knochen und du fühlst dich unfähig, dich
        zu bewegen. Du kannst nichts anderes tun, als dem Huhn in die Augen zu starren.
        Dieses starrt zurück, dreht den Kopf hin und her und beäugt dich von allen Seiten.
        Minuten, wenn nicht gar Stunden, verharrst du so, kannst nicht mal blinzeln und dir
        tränen die Augen. Ewigkeiten, so erscheint es dir, gehen ins Land, bis dem
        Monstervieh wohl langweilig wird und es davon flattert. Du atmest erleichtert auf
        und bewegst endlich wieder die steifen Glieder. Um möglichst schnell hier zu
        verschwinden, nimmst du dein/e ".$session['user']['weapon']." und schlägst ein Loch
        in die Nestwand. Eigentlich war es ja für dich gedacht, doch noch ehe du einen Schritt
        hindurch machen kannst, huschen die kleinen Häschen, die du völlig vergessen hattest,
        an dir vorbei und hinaus in die Freiheit. Eines der süßen Tierchen lässt ein Ei vor
        deinen Füßen fallen, welches du aufhebst, und dann ebenfalls das Nest verlässt.`n`n

        Als du das Ei öffnest, befinden sich `^5 Edelsteine `@darin, die du sofort einsteckst.
        Durch den Starrwettbewerb mit dem Huhn verlierst du jedoch `^10 Waldkämpfe`@.`n`n");
        $session['user']['gems']+=5;
        $session['user']['turns']-=10;
        addnews($session[user][name]." `@starrte `&ein `^Huhn `@in `&Grund `^und `@Boden!");
        addnav("zurück","forest.php");
        }
/*
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true); */

page_footer();
?>