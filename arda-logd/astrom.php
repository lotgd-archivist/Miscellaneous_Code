<?php
/*Sternenkind Noelanis Tempel
Für Arda von Narjana
Astronomieturm*/

require_once "common.php";
addcommentary();
checkday();

switch ($_GET['op'])
{
        case "stern":
        if ($_GET[op]=="stern")
    {
                if($session['user']['astro']==0){
                        page_header("Astronomieturm");
                        output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`c`b`n
                        `-Du`à ni`vmm`&st dir eines der Fernrohre der Göttin und schaust damit nach den Sternen. Vielleicht findest du ja etwas in den Unbekannten weiten.
                        Ein bisschen Weisheit der Göttin könnte dir ja nicht Schaden`v, o`àde`-r?`n

                        Angestrengt schaust du in die Ferne und siehst ");

                        $session['user']['astro']='1';
                        switch (e_rand(1,8))
                        {
                        case 1:
                                output("Nichts... Egal wie sehr du suchst, das Wissen verbirgt sich dir. Enttäuscht wendest du dich ab
                                und verlierst 2 Waldrunden.");

                                $session['user']['turns']-=2;
                                addnav("zurück aufs Dach","astrom.php?op=himmel");

                                break;
                        case 2:
                                output("Nichts... Zumindest denkst du das erst. Erst langsam merkst du dass sich das nichts irgendwie zu bewegen
                                scheint, anderes zu fressen. Du hast doch tatsächlich das Sagenumwobene schwarze Loch gefunden. Die Entdeckung lässt
                                dich ganz aufgeregt werden und du gewinnst einiges an erfahrung.");

                                   $session['user']['experience']*=1.4;
                                addnav("zurück aufs Dach","astrom.php?op=himmel");

                                break;
                        case 3:
                                output("den Blutmond. Rot und bedrohlich erhebt er sein Haupt über dir und du spührst die Wilde und animalische
                                stärke, die von diesem Mond ausgeht. Du bekommst zwei zusätzliche Waldkämpfe und fühlst dich stark wie ein Werwolf");

                                $session[user][attack]=round($session[user][attack]*1.05);
                                $session['user']['turns']+=2;
                                addnav("zurück aufs Dach","astrom.php?op=himmel");

                                break;
                        case 4:
                                output("das Nixenpaar. Schön und strahlend ergießen die Geschwister ihr sanftes Licht über dich und du beginnst zu träumen.
                                Als du wieder wach wirst, merkst du dass du eine ganze Zeit lang verschafen hast. Aber sie haben dir dafür auch ein Geschenk dagelassen.");

                                //erhöhung der Fähigkeiten
                                increment_specialty();
                                $session['user']['turns']-=2;
                                addnav("zurück aufs Dach","astrom.php?op=himmel");
                                break;

                        case 5:
                                output("das Diamantenei. All die Alten Sagen und Geschichten steigen in dir hoch und du schauderst zusammen. Irgendwie ist
                                dieser kalte, glitzernde Mond doch wirklich beängstigend... Ein Ahnungsvoller Schauder gleitet durch deinen Körper und lässt dich
                                den Rest des Tages nicht mehr los. Die Nervosität lässt dich unaufmerksam sein");

                                $session[user][defence]=round($session[user][defence]*0.90);
                                addnav("zurück aufs Dach","astrom.php?op=himmel");

                                break;
                        case 6:
                                output("den Lichtmond. Innerlich beginnst du bei dem Bild zu kichern und Freude breitet sich in dir aus.
                                Irgendwie hast du grade große Lust zu spielen und herumzutollen. Deine Energie und Gewandheit verstärkt sich um ein Vielfaches
                                Nur irgendwie geht dir jede Lust auf Kämpfen dabei verloren...");

                                   $session[user][attack]=round($session[user][attack]*1.10);
                                $session[user][defence]=round($session[user][defence]*1.10);
                                $session['user']['turns']-=5;
                                addnav("zurück aufs Dach","astrom.php?op=himmel");

                                break;
                        case 7:
                                output("Nirea. Die Rote Sonne scheint das Gesamte Fernrohr auszufüllen. Sie blendet dich, erfüllt dich aber auch mit Kraft und Lebenswillen
                                Du gewinnst 5 permanente Lebenspunkte");

                                $session['user']['maxhitpoints']+=5;
                                $session[user][defence]=round($session[user][defence]*0.98);
                                $session[user][attack]=round($session[user][attack]*0.95);
                                addnav("zurück aufs Dach","astrom.php?op=himmel");
                                break;

                        case 8:
                                output("Laera. Du hast dir wohl die Falsche Tageszeit ausgesucht um nach den Sternen zu schauen.
                                Völlig geblendet taumelst du zurück. So kannst du dich nciht verteidigen, geschweige denn angreifen. Wer schaut denn auch direkt in eine Sonne?");

                                $session[user][defence]=round($session[user][defence]*0.95);
                                $session[user][attack]=round($session[user][attack]*0.90);
                                addnav("zurück aufs Dach","astrom.php?op=himmel");
                                break;

        /*                case 9:
                                output("Satrurn");

                                addnav("zurück aufs Dach","astrom.php?op=himmel");
                                break;
                        case 8:
                                output("Neptun");

                                addnav("zurück aufs Dach","astrom.php?op=himmel");
                                break;*/
                        }
        }
                else
                {        page_header("Astronomieturm");
                        output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`c`b`n
                        `-Me`àin`vst`& du nicht du hast für heute wirklich lang genug in die Ferne gestarrt? Das Leben wartet a`vuf `àdi`-ch`n");

                        addnav("zurück","astrom.php?op=himmel");

                        break;
                        }


        break;
    }
        case "himmel":
        if ($_GET[op]=="himmel")
        {
                page_header("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`c`b`n`n
                `-Da`às f`vla`&che Dach ist von einem silbernen  Geländer umgeben. Kunstvolle Fernrohre und Teleskope bieten die Möglichkeit den
                Himmel abzusuchen und Sterne und Planeten zu betrachten. Ein Platz fällt dir besonders auf. Ein auf den ersten Blick
                einfaches, seltsam lila gefärbtes Teleskop ist in die Ferne Gerichtet, davor steht ein Stuhl, der meist besetzt ist.
                Wenn nicht, liegt dort eine Vielzahl Bücher auf der Sitzfläche. Du weißt nicht wessen Platz es ist. Doch du ahnst,
                er sollte frei bl`vei`àbe`-n.`n`n");

                viewcommentary("himmel","sagt:",15);


                        addnav("sterne ansehen","astrom.php?op=stern");
                        addnav("ins Innere","astrom.php?op=2stock");
                break;
        }

        case "karte":
        if ($_GET[op]=="karte")
        {
                page_header ("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`c`b`n`n
                `-Du`à we`vnd`&est dich einer Ecke des Turms zu, der nahezu vollgepflastert ist mit Karten, Büchern und Zetteln auf denen Notizen nur so wimmeln
                Es scheint als habe sich jemand die Mühe gemacht jede Kleinigkeit des Himmelszeltes zu kartographieren. Die Notizen sind dagegen eher fahrig
                als hätte man gerade eben nur so daran gedacht sie aufzuschreiben. Sortiert war nichts. Dennoch kann man nach einigen Suchen fünf verschiedene
                Kategorieen herausfi`vlt`àer`-n.`n`n");

                viewcommentary("karte","murmelt",25);

                addnav("die Sonnen","astrom.php?op=sonne");
                addnav("die Monde","astrom.php?op=mond");
                addnav("Die Planeten","astrom.php?op=planet");
                addnav("die Sternenbilder","astrom.php?op=bild");
                addnav("das schwarze Loch","astrom.php?op=loch");
                addnav("zurück","astrom.php?op=2stock");

        break;
        }
        case "sonne":
        if ($_GET[op]=="sonne")
        {
                page_header ("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m - Recherchen über die Sonnen über Arda`b`c`n`n
                Zwei Sonnen zieren den Himmel von Arda. Dennoch ist es nicht zu warm.`n`n
                Die eine, Nirea, ist rot und sehr groß. Sie kreist selbst in einer Umlaufbahn, strahlt aber dennoch helles, warmes Licht aus.`n
                Sie taucht das Land förmlich in Blut wenn sie aufgeht, dennoch ist sie nicht die wärmere der beiden Sonnen`n`n

                Der Mittelpunkt des Universums ist die zweite Sonne. Laera wird sie genannt und steht recht fern und klein am Himmel. Dennoch ist ihr
                Licht grell und bläulich weiß. Es reicht viel weiter als das der sanfteren Nirea und lässt im Sommer die Gletscher schmelzen, so dass die
                Ströme Ardas wahre Sturzbäche mit sich tragen.
                `n`n`n

                willst du zurück oder Weiterblättern?`n`n");

                addnav("die Monde","astrom.php?op=mond");
                addnav("die Planeten","astrom.php?op=planet");
                addnav("die Sternenbilder","astrom.php?op=bild");
                addnav("schwarzes Loch","astrom.php?op=loch");
                addnav("zurück","astrom.php?op=2stock");

        break;
        }

        case "mond":
        if ($_GET[op]=="mond")
        {
                page_header ("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m - Recherchen über die Monde über Arda`b`c`n`n
                Es gibt insgesamt 5 Monde die über unserer Welt kreisen. Jeder von ihnen sieht anders aus und hat einen anderen Effekt.`n`n

                -        Blutmond, steht der Welt am nächsten und wird wegen seiner dunkelroten, mit schwarz durchzogenen Färbung
                so genannt. Er beeinflusst Werwölfe, Vampire und ähnliches`n`n

                -        Nixenpaar – zwei Blau-graue Monde, die immer nur zusammen auftauchen, sich gegenseitig beeinflussend.
                Wie der Blutmond haben sie eine halbwegs feste Umlaufbahn und beeinflussen das Wasser, vor allem Ebbe und Flut`n`n

                -        Diamantei – ein sehr kleiner Mond der aussieht wie ein reiner Diamant. Der Legende nach ist es ein Ei aus
                dem eines Tages das Wesen schlüpfen wird, dass dieses Universum vernichten wird um darauf  zu sterben dass aus seinem
                Leichnam ein neues Universum gebildet werden kann.`n`n

                -        Lichtmond – Ein Mond in der genauen Passform eines Balles. Er leuchtet strahlend hell – allerdings kann er sich
                nicht ganz für die Farbe entscheiden. Meistens ist er weiß mit einem leicht farbigen einschlag. Wahlweise Rot, Rosa,
                Blau, Grün, oder Gelb. Selten nur Orange oder gar braun. Schwarz wird er gar nicht. Dieser Mond kann sich auch
                nicht für eine feste Umlaufbahn entscheiden und springt förmlich über Ardeniens Himmel.  Er beeinflusst Werwölfe und
                alle Wesen die gerne spielen, animiert sie zum Spielen und friedlich/freundlich sein. Angeblich ist es ein Werk
                Narjanas, als sie mal wieder irhen grenzenlosen Spieltrieb ausleben wollte.`n`n
                `n`n`n

                willst du zurück oder Weiterblättern?`n`n");

                addnav("die Sonne","astrom.php?op=sonne");
                addnav("die Planeten","astrom.php?op=planet");
                addnav("die Sternenbilder","astrom.php?op=bild");
                addnav("das schwarze Loch","astrom.php?op=loch");
                addnav("zurück","astrom.php?op=2stock");


        break;
        }

        case "planet":
        if ($_GET[op]=="planet")
        {
                page_header ("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m - Recherchen über die Planeten über Arda`b`c`n`n
                Coming soon `n`n`n

                willst du zurück oder Weiterblättern?`n`n");

                addnav("die Sonne","astrom.php?op=sonne");
                addnav("die Monde","astrom.php?op=mond");
                addnav("die Sternenbilder","astrom.php?op=bild");
                addnav("das schwarze Loch","astrom.php?op=loch");
                addnav("zurück","astrom.php?op=2stock");


        break;
        }


                case "bild":
        if ($_GET[op]=="bild")
        {
                page_header ("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m - Recherchen über die Sternbilder Arda`b`c`n`n
                12 Sternzeichen, je aufgeteilt zu den 12 Monaten gibt es`n`n
                1. Phönix`n`n
                2. Einhorn`n`n
                3. Sphinx`n`n
                4. Hydra`n`n
                5. Zentaur`n`n
                6. Yeti`n`n
                7. Drache`n`n
                8. Basilisk`n`n
                9. Mantikor`n`n
                10. Chamäleon`n`n
                11. Nixe`n`n
                12. Minotaurus`n`n`n

                willst du zurück oder Weiterblättern?`n`n");

                addnav("die Sonne","astrom.php?op=sonne");
                addnav("die Monde","astrom.php?op=mond");
                addnav("die Planeten","astrom.php?op=planet");
                addnav("das schwarze Loch","astrom.php?op=loch");
                addnav("zurück","astrom.php?op=2stock");
        break;
        }

        case "loch":
        if ($_GET[op]=="loch")
        {
                page_header("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m - Recherchen über ein seltsames Phänomen`b`c`n`n

                willst du zurück oder weiterblättern?`n`n");

                addnav("die Sonne","astrom.php?op=sonne");
                addnav("die Monde","astrom.php?op=mond");
                addnav("die Planeten","astrom.php?op=planet");
                addnav("die Sternenbilder","astrom.php?op=bild");
                addnav("zurück","astrom.php?op=2stock");
        break;
        }

        case "2stock":
        if ($_GET[op]=="2stock")
        {
                page_header("Astronomieturm");
                output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`c`b`n`n
                 `-Im`à er`vst`&en Stock siehst du einzelne Tische, an denen sich Gelehrte, Schüler und Gäste zusammensetzen und unterhalten
                 können. Allerdings fällt dir auf, dass der Raum zu klein ist. Du lässt den Blick schweifen und erkennst, dass dort
                 versteckt, ja fast unsichtbar eine Tür in die Wand eingelassen ist. Es wird die niemand bestätigen, doch dort befinden
                 sich die Räume Noelanis. Die Tür ist immer verschlossen und nur auserwählte Gäste können eint`vre`àte`-n.");

                viewcommentary("2stock","sagt:",15);


                        addnav("aufs Dach","astrom.php?op=himmel");
                        addnav("Sternenkarten","astrom.php?op=karte");
                        addnav("runter","astrom.php?op=erd");
                break;
        }

        case "erd":
        if ($_GET[op]=="erd")
    {
        page_header("Astronomieturm");
        output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`c`b`n`n
                `-Hi`àer`v kö`&nntest du fast glauben, dass du in einem Leuchtturm stehst. Ein großer, runder Raum in dessen Mitte eine weiße
                Wendeltreppe hinauf führt. An den Wenden erkennst du einzelne Sternenbilder und Planetenkonstellationen, auch die
                verschiedenen Monde und ihre Phasen kannst du an den Hohen Wenden erk`ven`àne`-n.`n`n");

        viewcommentary("erd","sagt:",15);

                        addnav("Weiter hoch","astrom.php?op=2stock");
                        addnav("Raus","astrom.php");

        break;
    }

    default:
if ($_GET[op]=="")    {

        page_header("Astronomieturm");

        output("`c`b`-A`às`vt`&ronomiet`vu`àr`-m`b`c`n`n


                `-In `àmi`vtt`&en des Universitätsviertels ragt ein schneeweißer Turm in die Höhe.  Von hier unten schaust du zwei Stockwerke
                hinauf. Statt eines Daches siehst du eine Plattform, ein Paradies für Astronomen und wie man munkelt auch die Quell
                für deren Einsichten. Doch wenn du mehr erfahren willst, tritt ein und steige `vem`àpo`-r.`n`n");


        viewcommentary("astrom","sagt",15);

                addnav("Hinein","astrom.php?op=erd");
                addnav("Universitätsviertel","univier.php");


        break;
    }

}
page_footer();
?> 