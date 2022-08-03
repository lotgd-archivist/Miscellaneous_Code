<?php
/* Narjanas Tempel */
require_once "common.php";
addcommentary ();
checkday ();

switch ($_GET ['op']) {
    case "quell" :
        {
            page_header ( "Quell des Wahnsinns" );
            output ( "`c`bTQuell des Wahnsinns`c`b`n
Beschreibung`n" );
            
            viewcommentary ( "quell", "sagt:", 25 );
            // addnav("zu den Gräbern","moor.php?op=wander");
            // addnav("in den Sumpf","moor.php");
            // addnav("Mitte des Teiches","moor_unten.php?op=treppe");
            // addnav("In die Felder (Logout)","login.php?op=logout",true);
            break;
        }
    case "muse" :
        {
            page_header ( "Museum der Kunst des Wahnsinns" );
            output ( "`cMuseum der Kunst des Wahnsinns`n
" );
            
            viewcommentary ( "muse", "sagt:", 25 );
            // addnav("zurück");
            // addnav("Uferrand","moor_unten.php?op=teich");
            // addnav("magische Wendeltreppe runtersteigen","moor_unten.php");
            // addnav("magische Wendeltreppe hochsteigen","elfengarten.php");
            break;
        }
    case "nanz" :
        {
            page_header ( "Narjanas Zimmer" );
            output ( "`c`bNarjanas Zimmer`c`b`n
Beschreibung`n" );
            
            viewcommentary ( "nanz", "sagt:", 25 );
            // addnav("tiefer in den Kristallgarten","moor_unten.php?op=mus");
            // addnav("verziehrtes Seitentoor","moor_unten.php?op=nan");
            // addnav("Fuß der magischen Treppe","moor_unten.php");
            // addnav("In die Felder (Logout)","login.php?op=logout",true);
            break;
        }
    case "seit" :
        {
            page_header ( "Seitenkammern" );
            output ( "`c`bSeitenkammer`c`b`n
Beschreibung`n" );
            
            viewcommentary ( "seit", "sagt:", 25 );
            addnav ( "zum Hauptraum", "nan.php" );
            // addnav("zur großen Hoehle","rui_tempel.php?op=hl");
            // addnav("Mitte des Teiches","moor_unten.php?op=treppe");
            // addnav("In die Felder (Logout)","login.php?op=logout",true);
            break;
        }
    case "lurn" :
        {
            page_header ( "Lurnfälle" );
            output ( "`c`bLurnfälle`c`b`n
Beschreibung`n" );
            
            viewcommentary ( "nan", "sagt:", 25 );
            
            addnav ( "zum Wasserfall", "nan.php?op=fall" );
            addnav ( "zurück in die Berge", "berge.php" );
            // addnav("Mitte des Teiches","moor_unten.php?op=treppe");
            // addnav("In die Felder (Logout)","login.php?op=logout",true);
            break;
        }
    case "fall" :
        {
            page_header ( "Lurnfälle" );
            output ( "`c`bLurnfälle`c`b`n
Todesmutig springst du in den See, der von dem Ständig rauschenden Wasserfall gefüllt wird. Die Strömung ist reißend und du weißt, dass du so unmöglich kämpfen kannst.
Mit einem male Reißt das Wasser auf und ein Riesenhaftes, steinernes Chamäleon entsteigt dem Wasser. Eine lange, dunkle Lavazunge züngelt aus dem Rachen und du glaubst wirklich
gesprochene Laute zu vernehmen. Nur wenige Sekunden später merkst du, es ist keine Einbildung. Das Wesen fragt dich etwas. Etwas über`n" );
            switch (e_rand ( 1, 16 )) {
                case 1 :
                    {
                        output ( "\"Die Farben des Regenbogens\"" );
                        addnav ( "\"Blau?\"", "nan.php?op=falsch" );
                        addnav ( "\"GELB!\"", "nan.php?op=falsch" );
                        addnav ( "\"Ich weiß nicht...\"", "nan.php?op=falsch" );
                        addnav ( "\"Kunterdiebunt!\"", "nan.php?op=richtig" );
                        addnav ( "\"Grün! Jawollja\"", "nan.php?op=falsch" );
                        addnav ( "still und heimlich verdrücken", "berge.php" );
                        break;
                    }
                case 2 :
                    {
                        output ( "Das Tier der Veränderung" );
                        addnav ( "Ein Pferd", "nan.php?op=falsch" );
                        addnav ( "ein Chamäleon - schön bund", "nan.php?op=richtig" );
                        addnav ( "ein Fabeltier wie - ein Greif", "nan.php?op=falsch" );
                        addnav ( "ganz klar ne Chimäre", "nan.php?op=falsch" );
                        addnav ( "still und heimlich verdrücken", "berge.php" );
                        break;
                    }
                case 3 :
                    {
                        output ( "Die drei Mächte der Göttin" );
                        addnav ( "öhm - keine Ahnung...", "nan.php?op=falsch" );
                        addnav ( "Kuchen essen, Feuer machen und Essen kochen", "nan.php?op=falsch" );
                        addnav ( "Wind, Wasser und Blitz", "nan.php?op=falsch" );
                        addnav ( "Chaos, Veränderung und Wahnsinn", "nan.php?op=richtig" );
                        addnav ( "still und heimlich verdrücken", "berge.php" );
                        break;
                    }
                case 5 :
                    {
                        output ( "Name der Manie" );
                        addnav ( "BACCHUS!", "nan.php?op=falsch" );
                        addnav ( "Earendil?", "nan.php?op=falsch" );
                        addnav ( "Sheogorath - kennt doch jeder", "nan.php?op=falsch" );
                        addnav ( "SunSun", "nan.php?op=richtig" );
                        addnav ( "Sanguin", "nan.php?op=falsch" );
                        addnav ( "still und heimlich verkrümmeln", "berge.php" );
                        break;
                    }
                case 6 :
                    {
                        output ( "Name der Demenz" );
                        addnav ( "Darky - ganz klar", "nan.php?op=richtig" );
                        addnav ( "Hades hat es!", "nan.php?op=falsch" );
                        addnav ( "Rui", "nan.php?op=falsch" );
                        addnav ( "Morpheus...", "nan.php?op=falsch" );
                        addnav ( "still und heimlich verkrümeln", "berge.php" );
                        break;
                    }
                case 7 :
                    {
                        ouput ( "Die Kunst des Kristallgartens" );
                        addnav ( "Ähm - eine moderne Kunstaustellung?", "nan.php?op=falsch" );
                        addnav ( "Kunst interessiert mich nicht", "nan.php?op=falsch" );
                        addnav ( "Die Kunst der Toten", "nan.php?op=richtig" );
                        addnav ( "Shirigami - oder so.", "nan.php?op=falsch" );
                        addnav ( "still und heimlich verdrücken", "berge.php" );
                        break;
                    }
                /*
                 * case 8: output ("")
                 */
            }
            
            output ( "Du solltest besser das richtige Antworten oder aber dich auf einen äußerst schmerzvollen, aber immerhin schnellen Tod vorbereiten." );
            
            break;
        }
    case "falsch" :
        {
            page_header ( "Lurnfälle" );
            output ( "Das war eindeutig die FALSCHE antwort. Das Riesenhafte Wesen streckt sich, und verschlingt dich in einem habs. DU bist tot." );
            
            addnews ( "`3" . $session ['user'] ['name'] . " `3konnte die Frage der Sphinx - äh - des Chamäleons nicht beantworten und wurde gefressen`3." );
            $session ['user'] ['alive'] = false;
            $session ['user'] ['hitpoints'] = 0;
            // $session['user']['experience']*=1.3;
            addnav ( "Tägliche News", "news.php" );
            break;
        }
    case "richtig" :
        {
            page_header ( "Lurnfälle" );
            output ( "Scheinbar war dies die richtige Antwort. Auf jedenfall grinst das Chamäleon dich schelmisch an und  trollt sich zur Seite, wo es sich wohlig
in der Sonne brutzelt. Dir steht der Weg zum Wasserfall jetzt frei, aber noch lange rätzelst du, ob Chamäleons wirklich grinsen können." );
            $session ['user'] ['experience'] * 1.3;
            addnav ( "unter dem Wasserfall durch", "nan.php" );
            addnav ( "zurück in die Berge", "berge.php" );
            break;
        }
    case "laby" :
        {
            page_header ( "Labyrinth" );
            output ( "`c`bLabyrinth`c`b`nBeschreibung`n" );
            
            viewcommentary ( "laby", "sagt:", 25 );
            
            // addnav("zur magischen Wendeltreppe","moor_unten.php");
            // addnav("in den Sumpf","moor.php");
            // addnav("Mitte des Teiches","moor_unten.php?op=treppe");
            // addnav("In die Felder (Logout)","login.php?op=logout",true);
            break;
        }
    default :
        {
            
            page_header ( "Tempel des Chaos" );
            
            output ( "`c`b`STempel des Chaos`b`c`n`n" );
            
            viewcommentary ( "narjana", "sagt", 25 );
            
            addnav ( "Seitenräume", "nan.php?op=seit" );
            addnav ( "aus dem Tempel", "nan.php?op=lurn" );
            // addnav("nach Oben","moor_unten.php?op=treppe");
            // addnav("Kristallhöhle","moor_unten.php?op=kristall");
            // addnav("Tunnelsystheme","moor_unten.php?op=tunnel");
            // addnav("nach Arda","dorftor.php");
            // addnav("Umland");
            // addnav("Kornkammern Ardas","kreuzung.php?op=korn");
            // addnav("in die Berge",""); (noch nicht aktiv)
            // addnav("Strand","sanelastrand.php");
            // addnav("friedhofswege","moor.php?op=wander");
            // addnav("In den Wald","forest.php");
            // addnav("Dark Horse Tarverne","forest.php?specialinc=darkhorse.php");
            // addnav("Ebene der Fantasie (Spielerorte)","orte.php");
            break;
        }
}
page_footer ();
?>