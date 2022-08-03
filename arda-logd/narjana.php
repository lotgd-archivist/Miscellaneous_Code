<?php
/*Narjanas Tempel*/

require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
        case 'quell':
        {
                page_header("Quell des Wahnsinns");
                output("`c`bTQuell des Wahnsinns`c`b`n
                                Beschreibung`n");

                viewcommentary("quell","sagt:",15);
//                addnav("zu den Gräbern","moor.php?op=wander");
//                addnav("in den Sumpf","moor.php");
//                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        case 'muse':
        {
                page_header("Museum der Kunst des Wahnsinns");
                output("`cMuseum der Kunst des Wahnsinns`n
                ");

                viewcommentary("muse","sagt:",15);
//                addnav("zurück");
//                addnav("Uferrand","moor_unten.php?op=teich");
//                addnav("magische Wendeltreppe runtersteigen","moor_unten.php");
//                addnav("magische Wendeltreppe hochsteigen","elfengarten.php");
                break;
        }
        case 'nanz':
        {
                page_header("Narjanas Zimmer");
                output("`c`bNarjanas Zimmer`c`b`n
                                Beschreibung`n");

                viewcommentary("nanz","sagt:",15);
//                addnav("tiefer in den Kristallgarten","moor_unten.php?op=mus");
//                addnav("verziehrtes Seitentoor","moor_unten.php?op=nan");
//                addnav("Fuß der magischen Treppe","moor_unten.php");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        case 'seit':
        {
                page_header("Seitenkammern");
                output("`c`bSeitenkammer`c`b`n
                                Beschreibung`n");

                viewcommentary("seit","sagt:",15);
                addnav("zum Hauptraum","narjana.php");
//                addnav("zur großen Hoehle","rui_tempel.php?op=hl");
//                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        case 'lurn':
        {
                page_header("Lurnfälle");
                output("`c`bLurnfälle`c`b`n
                                Beschreibung`n");

                viewcommentary("nan","sagt:",15);

                addnav("zum Wasserfall","narjana.php?op=fall");
                addnav("zurück in die Berge","berge.php");
//                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
                case 'fall':
        {
                page_header("Lurnfälle");
                output("`c`bLurnfälle`c`b`n
                                Todesmutig springst du in den See, der von dem Ständig rauschenden Wasserfall gefüllt wird. Die Strömung ist reißend und du weißt, dass du so unmöglich kämpfen kannst.
                                Mit einem male Reißt das Wasser auf und ein Riesenhaftes, steinernes Chamäleon entsteigt dem Wasser. Eine lange, dunkle Lavazunge züngelt aus dem Rachen und du glaubst wirklich
                                gesprochene Laute zu vernehmen. Nur wenige Sekunden später merkst du, es ist keine Einbildung. Das Wesen fragt dich etwas. Etwas über`n");
        switch (e_rand(1,16)){
                case 1:
                        output ("\"Die Farben des Regenbogens\"");
                        addnav ("\"Blau?\"","narjana.php?op=falsch");
                        addnav ("\"GELB!\"","narjana.php?op=falsch");
                        addnav ("\"Ich weiß nicht...\"","narjana.php?op=falsch");
                        addnav ("\"Kunterdiebunt!\"","narjana.php?op=richtig");
                        addnav ("\"Grün! Jawollja\"","narjana.php?op=falsch");
                        addnav ("still und heimlich verdrücken","berge.php");
                        break;
                case 2:
                        output ("Das Tier der Veränderung");
                        addnav ("Ein Pferd","narjana.php?op=falsch");
                        addnav ("ein Chamäleon - schön bund","narjana.php?op=richtig");
                        addnav ("ein Fabeltier wie - ein Greif","narjana.php?op=falsch");
                        addnav ("ganz klar ne Chimäre","narjana.php?op=falsch");
                        addnav ("still und heimlich verdrücken","berge.php");
                        break;
                case 3:
                        output ("Die drei Mächte der Göttin");
                        addnav ("öhm - keine Ahnung...","narjana.php?op=falsch");
                        addnav ("Kuchen essen, Feuer machen und Essen kochen","narjana.php?op=falsch");
                        addnav ("Wind, Wasser und Blitz","narjana.php?op=falsch");
                        addnav ("Chaos, Veränderung und Wahnsinn","narjana.php?op=richtig");
                        addnav ("still und heimlich verdrücken","berge.php");
                        break;
                case 5:
                        output ("Name der Manie");
                        addnav ("BACCHUS!","narjana.php?op=falsch");
                        addnav ("Earendil?","narjana.php?op=falsch");
                        addnav ("Sheogorath - kennt doch jeder","narjana.php?op=falsch");
                        addnav ("SunSun","narjana.php?op=richtig");
                        addnav ("Sanguin","narjana.php?op=falsch");
                        addnav ("still und heimlich verkrümmeln","berge.php");
                        break;
                case 6:
                        output ("Name der Demenz");
                        addnav ("Darky - ganz klar","narjana.php?op=richtig");
                        addnav ("Hades hat es!","narjana.php?op=falsch");
                        addnav ("Rui","narjana.php?op=falsch");
                        addnav ("Morpheus...","narjana.php?op=falsch");
                        addnav ("still und heimlich verkrümeln","berge.php");
                        break;
                case 7:
                        ouput ("Die Kunst des Kristallgartens");
                        addnav ("Ähm - eine moderne Kunstaustellung?","narjana.php?op=falsch");
                        addnav ("Kunst interessiert mich nicht","narjana.php?op=falsch");
                        addnav ("Die Kunst der Toten","narjana.php?op=richtig");
                        addnav ("Shirigami - oder so.","narjana.php?op=falsch");
                        addnav ("still und heimlich verdrücken","berge.php");
                        break;
/*                case 8:
                        output ("")*/
                }

                output("Du solltest besser das richtige Antworten oder aber dich auf einen äußerst schmerzvollen, aber immerhin schnellen Tod vorbereiten.");

                break;
        }
        case 'falsch':
        {
                page_header ("Lurnfälle");
                output ("Das war eindeutig die FALSCHE antwort. Das Riesenhafte Wesen streckt sich, und verschlingt dich in einem habs. DU bist tot.");

                addnews("`3".$session['user']['name']." `3konnte die Frage der Sphinx - äh - des Chamäleons nicht beantworten und wurde gefressen`3.");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
//                $session['user']['experience']*=1.3;
            addnav("Tägliche News","news.php");
                break;
        }
        case 'richtig':
        {
                page_header("Lurnfälle");
                output("Scheinbar war dies die richtige Antwort. Auf jedenfall grinst das Chamäleon dich schelmisch an und  trollt sich zur Seite, wo es sich wohlig
                in der Sonne brutzelt. Dir steht der Weg zum Wasserfall jetzt frei, aber noch lange rätzelst du, ob Chamäleons wirklich grinsen können.");
                $session['user']['experience']*1.3;
                addnav("unter dem Wasserfall durch","narjana.php");
                addnav("zurück in die Berge","berge.php");
                break;
        }
        case 'laby':
        {
                page_header("Labyrinth");
                output("`c`bLabyrinth`c`b`nBeschreibung`n");

                viewcommentary("laby","sagt:",15);

//                addnav("zur magischen Wendeltreppe","moor_unten.php");
        //        addnav("in den Sumpf","moor.php");
//                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        default:
        {

                page_header("Tempel des Chaos");

                output("`c`b`STempel des Chaos`b`c`n`n");


                viewcommentary("narjana","sagt",15);

                addnav("Seitenräume","narjana.php?op=seit");
                addnav("aus dem Tempel","narjana.php?op=lurn");
//                addnav("nach Oben","moor_unten.php?op=treppe");
//                addnav("Kristallhöhle","moor_unten.php?op=kristall");
//                addnav("Tunnelsystheme","moor_unten.php?op=tunnel");
//                addnav("nach Arda","dorftor.php");
//                addnav("Umland");
//                addnav("Kornkammern Ardas","kreuzung.php?op=korn");
//                addnav("in die Berge",""); (noch nicht aktiv)
//                addnav("Strand","sanelastrand.php");
//                addnav("friedhofswege","moor.php?op=wander");
//                addnav("In den Wald","forest.php");
//                addnav("Dark Horse Tarverne","forest.php?specialinc=darkhorse.php");
//                addnav("Ebene der Fantasie (Spielerorte)","orte.php");
                break;
     }

}
page_footer();

?> 