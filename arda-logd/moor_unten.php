<?php
/*Ort unterm moor*/

require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
        case 'teich':
        {
                page_header("Kleiner Teich");
                output("`c`bTeich`c`b`n
                                Du findest dich am Rand eines stillen Gewässers wieder. Kein glucksender Tümpel, doch kein Fluss. Ein Teich womöglich, oder auch ein See, doch der vom ungewöhnlich
                                klaren Wasser aufsteigende Dunst lässt die Ausmaße nicht erkennen. Sichtbar ist jedoch ein Leuchten, diffus durch den Nebel, und eine Barke die am Ufer ruht.
                                Stößt du dich vom Ufer ab und gehst dem Schein auf den Grund, oder kehrst du dem unwirklichen Phänomen den Rücken?`n");

                viewcommentary("teich","sagt:",15);
                addnav("zu den Gräbern","moor.php?op=friedhof");
                addnav("in den Sumpf","moor.php");
                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        case 'treppe':
        {
                page_header("Mitte des Teiches");
                output("`cMitte des Teiches`n
                Im Zentrum des Gewässers befindet sich, mitten aus dem Wasser ragend, eine Wendeltreppe, durchscheinend als bestünde sie selbst aus Wasser, und doch stabil genug für
                das Gewicht eines jeden Abenteurers. Nach oben führt sie bis sie im hell leuchtenden Nebel verschwindet, nach unten verbirgt sie schließlich die Dunkelheit vor eines
                Jeden Blick. Auf halber höhe fließt ein seltsam bunter Seitenstrang in die Treppe und mann glaubt kaum dass man darau laufen kann. Aber du könntest es versuchen`n`n
                `nSteigst du hinauf oder hinab? Oder führt dich dein Weg lieber zum Rand des Teichs, aus den Nebeln heraus?`n");

//                viewcommentary("treppe","sagt:",15);
                addnav("zurück");
                addnav("Uferrand","moor_unten.php?op=teich");
        addnav("magische Wege");
                addnav("magische Wendeltreppe runtersteigen","moor_unten.php");
                addnav("magische Wendeltreppe hochsteigen","elfengarten.php");
                addnav("seltsamer Seitenstrang benutzen","narjan.php?op=lurn");
                break;
        }
 /*       case 'kristall':
        {
                page_header("Kristallhöhle");
                output("`c`bKristallhöhle`c`b`n
                             .`n");

                viewcommentary("kristall","sagt:",15);
                addnav("tiefer in den Kristallgarten","moor_unten.php?op=mus");

                addnav("Fuß der magischen Treppe","moor_unten.php");

                break;
        }*/
        case 'mus':
        {
                page_header("Kristallgarten");
                output("`c`bKristallgarten`c`b`n
                       In dem langen, breiten, verzweigten Korridor finden sich in regelmäßigen Abständen Nischen an den Wänden, verschieden in Größe und in Form: Einander gegenüber
                          je ein männliches und ein weibliches Exemplar einer jeden Spezies, Insekten, Fische, Echsen, Vögel, an Land lebende Tiere, denkende und sprechende Wesen, alle
                          erdenklichen Mischlinge, alles was seit Anbeginn der Welt lebte ist hier aufbewahrt, auf ewig in Kristall geschlossen. Nichts kann diesen Stein auch nur ankratzen
                          was nicht von göttlicher Macht ist, und so oder so möchte niemand auch nur halbwegs vernünftiges an die Sammlung der Totengöttin Hand anlegen.`n
            Licht scheint von den Kristallen auszugehen, was das alles etwas weicher aussehen lässt, dennoch bleibt es eine Beängstigende Autmosphäre. An Manchen stellen                 scheint es regelrechte Wege zu geben, die vom Hauptpfad abweichen, zu Torbögen, Gängen und anderen Höhlen.");

                viewcommentary("mus","sagt:",15);
                addnav("zurück zur Wendeltreppe","moor_unten.php");
                addnav("zur großen Hoehle","rui_tempel.php?op=hl");
                addnav("Grab der Götter","moor_unten.php?op=grab");
                addnav("Narjanas Grab","moor_unten.php?op=nan");
        addnav("rote Abzweigung","katakomp.php?op=Feuer");
                break;
        }
        case 'grab':
        {
                page_header("Grab der Götter");
                output("`cGrab der Götter`n`n
                Nicht anders als in den anderen Korridoren finden sich hier in Kristall bewahrte Körper, und doch erscheinen dir diese Körper anders. Kaum ein Sterblicher wird sich der
                verstorbenen Götter entsinnen, die seit der Entstehung von allem angesammelt haben, aber niemandem entgeht das Gefühl der Macht dass noch immer von ihren toten
                Körpern ausgeht.`c`n");

                viewcommentary("grab","flüstert:",15);
                addnav("zurück","moor_unten.php?op=mus");
                break;
                }
        case 'nan':
        {
                page_header("Narjanas Grab");
                output("`c`bNarjanas Grab`c`b`n
                                Einen Raum für sich allein nimmt eine ganz besondere Sorte Toter ein: Hier sind, aufgereiht, benannt und datiert, alle Körper die das Chaos für sich                     beansprucht
                                hat. Sie sind alle weiblich, und die meisten noch sehr jung, viele zeigen Narben oder Nähte. Sterblichen wird diese Sammlung wohl kaum etwas bedeuten. Am                     Kopfende
                                der Sammlung ist eine Nische zu sehen die weder Kristall noch Körper enthält, lediglich ein türkis glühendes Symbol in der Erde, das von durchscheinend                     buntem,
                                undurchdringlichem, ständig sich veränderndem Material umschlossen ist.`n");

                viewcommentary("nan","sagt:",15);
        addnav("zurück zum Kristallgarten","moor_unten.php?op=mus");

                break;
        }
/*        case 'Tunnel':
        {
                page_header("Tunnelsystheme");
                output("`c`bTunnelsystheme`c`b`n
                                Beschreibung`n");

                viewcommentary("teich","sagt:",15);
                addnav("zur magischen Wendeltreppe","moor_unten.php");

                break;
        }*/
        default:
        {

                page_header("Unter dem Moor");

                output("`c`b`SUnter dem Moor`b`c`n
                `cDu findest dich in einer Höhle wieder. Ein sanftes Glühen und Funkeln begrüßt dich als du dich näherst. Auch die Wendeltreppe erscheint in weichem Licht zu strahlen. Sie             scheint zurück an die Erdoberfläche zu führen - direkt durch die wie Wasser schimmernde Decke der Höhle. An den Wänden sind Bilder,
                oder vielleicht Markierungen in Form von Schlangenlinien zu sehen, die den rauhen, dunklen Stein durchziehen. Fast meinst du, sie bewegen sich.   
        Kühle, schwebende Lichter erhellen diese scheinbar natürliche Höhle, und Kristalle sprießen
                förmlich aus dem Fels, in allen Farben und diversen Formationen, manche von ihnen so klar und bunt dass sie von sich aus zu leuchten scheinen. An einigen Stellen
                kann man sich sogar auf den Edelsteinen niederlassen die Säulen bilden oder Pilzförmig aus dem Boden wachsen. Dennoch scheinst du gerade erst am Anfang dieses seltsamen             Kristallgartens zu sein.`c`n`n
        ");


                viewcommentary("moor_unten","sagt",15);


                addnav("nach Oben","moor_unten.php?op=treppe");
                addnav("Kristalgarten","moor_unten.php?op=mus");
 //               if ($session[user][superuser]>1){ addnav("Tunnelsystheme","moor_unten.php?op=tunnel");
                }

                break;
     

}
page_footer();

?> 