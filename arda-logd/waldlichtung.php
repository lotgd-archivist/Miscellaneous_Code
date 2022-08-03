<?php

/*Waldlichtung für www.arda-logd.de von Narjana*/
require_once "common.php";
addcommentary();
checkday();


   switch($_GET['op'])
{
/*        case 'korn':
        {
                page_header("Kornkammern Ardas");
                output("`c`bKornkammern Ardas 1`c`b`n
                                Beschreibung`n");

                viewcommentary("korn","sagt:",15);
                addnav("zur Wegkreuzung","kreuzung.php");
                addnav("nach Arda","dorftor.php");
                addnav("zu den Bauernhöfen","kreuzung.php?op=hof");
                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }*/
        case 'versteck':
        {
                page_header("Versteckte Höhle");
                output("`c`b`KDi`le `Lve`vrsteckt`Le H`löh`Kle`c`b`n`n
           `c`KMi`ltt`Len im `2Wa`ol`2d`L findest du einen kleinen, versteckten Höhleneingang. Fast wärst du an dem unscheinb`lar`Ken`n
`KEi`lng`Lang vorbeigegangen. Unschlüssig stehst du davor, sollst du es wagen und hineingehen? Du ni`lmm`Kst`n
`Kal`ll d`Leinen Mut zusammen und gehst festen Schrittes in die Höhle. Erstaunt bleibst du mitt`len `Kim`n
`KEi`lng`Lang stehen, mit so etwas schönem hast du einfach nicht gerechnet. Die Wände sche`lin`Ken`n
`Kri`lch`Ltig zu `Zfu`^nke`Zln`L und in der Mitte der Höhle befindet sich ein wunders`lch`Kön`n
`Ksc`lhi`Lmmernder `9S`ye`9e`L, der von heißen Quellen gespeist wird. Dies ist ein perfekte`lr O`Krt,`n
`Kum `lzu`Lr Ruhe zu kommen und zu entspa`lnn`Ken.`n`n`c");

                viewcommentary("ue18versteck","flüstert:",15);
                addnav("zurück");
                addnav("Waldlichtung","waldlichtung.php");
                break;
        }
        default:
        {

                page_header("Waldlichtung");

                output("`c`b`fDi`3e W`Faldlich`3tu`fng`c`b`n`n
`c`fNa`3ch `Feiner längeren Wanderung durch den `3Wa`fld`n
`fbe`3tr`Fittst du eine große Lich`3tu`fng.`n
`fDa`3s G`Fras hat eine seltsam `Pdu`snk`Ple `FF`3ar`fbe.`n
`fDi`3e B`Fäume tragen unzählige `PBl`sätt`Per`F und doch wirken sie `3ka`flt.`n
`fDu `3sp`Fürst ein `Pdu`snkl`Pes `FEcho vergangener `3Ta`fge,`n
`fda`3s d`Fir tief in deine Glieder kriechen `3wi`fll.`n
`fBl`3ei`Fbst du und ergründest das Geheimnis der Lich`3tu`fng`n
`fod`3er `Fflüchte`3st `fdu?`n`n`c");


                viewcommentary("waldlichtung","sagt",15);
                addnav("Symia","sanela.php");
                addnav("Wald","forest.php");
                addnav("Heilerhütte","healer.php");
                if ($session['user']['veri']==1)
                {
                //addnav("~ Ü18 ~");
                addnav("versteckte Höhle","waldlichtung.php?op=versteck");
                }
                break;
     }

}
page_footer();



?>