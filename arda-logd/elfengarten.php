<?php

require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
        case 'heiligergarten':
        {
                page_header("Die heiligen Gärten");
                output("`c`b`PDie `she`Sili`sgen `2Gä`@rt`2en`c`b`n`n
                                `c`3Du `Fbe`#trittst nun `Pdie `she`Sili`sgen `2Gä`@rt`2en`#. Schnell wird dir klar, woher dieses seltsame Licht st`Fam`3mt.`n
`3Es Fsc`#heint aus dem Inneren eines Baumes zu kommen, der in der Mitte des Gartens steht. Der Ort w`Fir`3kt`n
`3vo`Fll`#er Leben und dennoch ist es seltsam ruhig hier. Es kommt dir so vor, als würde kein Gerä`Fus`3ch,`n
`3ob `FVo`#gelgezwitscher oder das Rascheln der Blätter im Wind zu diesem Ort gela`Fng`3en.`n`n

`3Du `Füb`#erlegst, ob du zu dem seltsamen Baum gehen solltest oder doch lieber wieder umke`Fhr`3st.`n`n`n`c");

                viewcommentary("heiligergarten","sagt:",15);
                addnav("zu den Gärten","elfengarten.php");
                addnav("`oBa`@um `Odes `@Le`obe`2ns","elfengarten.php?op=leben");
                addnav("Elfenbehausungen","elfengarten.php?op=haus");
                break;
        }
        case 'leben':
        {
                page_header("Im Baum des Lebens");
                output("`c`b`2Im `oBa`@um `Odes `@Le`obe`2ns`b`n`n`c
               `c`3Al`Fs d`#u das Innere des `2Ba`@um`2es`# betrittst, denkst du erst einmal, daß du dich wieder im Freien befin`Fde`3st,`n
`3de`Fnn`# der Boden ist unter den `jWo`mlk`ven`# verborgen. Auch hier findest du alle möglichen Arten von Kräu`Fte`3rn,`n
`3un`Fd T`#ieren, sowohl gefährliche als auch ungefährliche. Zu deiner Rechten sprudelt eine Quelle aus dem B`Fod`3en,`n
`3de`Fss`#en klares`kWa`9ss`xer`# munter über einige Steine gluckert. Hoch oben in den Ästen hörst du einige V`Fög`3el`n
`3zw`Fit`#schern und Insekten summen. Auch einige verletzte Tiere sind hier zu finden, die durch die Lebensk`Fra`3ft,`n
`3di`Fe h`#ier überall zu spüren ist, genesen. In der Mitte des `2Ba`@um`2es`# findest du eine Wendeltr`Fep`3pe,`n
`3di`Fe h`#och in den Baum f`Füh`3rt.`n`n`n`c");

                viewcommentary("leben","sagt:",15);
                addnav("Wendeltreppe hoch","elfengarten.php?op=treppe2");
                addnav("zurück");
                addnav("In den heiligen Garten","elfengarten.php?op=heiligergarten");
//früchte des Baums des Lebens?
                break;
        }

                case 'haus':
        {
                page_header("Elfenbehausungen");
                output("`c`b`PEl`sfe`Snb`de`Dh`dau`Ssu`sng`Pen`c`b`n`n
                              `c`3Du`F ha`#st dich dazu entschieden, dir die Behausungen genauer anzusehen. Also wander`Fst `3du`n
`3an `Fde`#n einzelnen `âBe`éet`âen`# vorbei, bis du endlich zu dem gewünschten Ort kommst. Dort angeko`Fmm`3en`n
`3fä`Fll`#t dir sofort auf, daß diese Bauten von `jEl`Df`jen`# stammen müssen. Diese filigrane Arbeit kann n`Fic`3ht`n
`3vo`Fn e`#inem Menschen oder Zwerg stammen. Die Behausungen sind in einer einzigartigen `FFo`3rm`n
`3mi`Ft d`#em `2Ba`@um`# verbunden, bestehen sie aus dem `2Ba`@um`#, ohne ihn zu verle`Ftz`3en.`n`3Es `Fsi`#nd lebende Wohnungen, die im Einklang mit der Natur st`Feh`3en.`n`n`c");

                viewcommentary("haus","sagt:",15);
                addnav("zu den Gärten","elfengarten.php");
                addnav("Die heiligen Gärten","elfengarten.php?op=heiligergarten");
                addnav("Strickleiter","sanela.php");
//später vll noch möglichkeiten
                break;
        }

                case 'treppe2':
        {
                page_header("Auf der Wendeltreppe");
                output("`c`b`3Au`Ff d`#er Wendeltr`Fep`3pe`c`b`n`n
`c`3We`Fnn `#du die Wendeltreppe hochgehst, siehst du an den Wänden Bilder von alten Ti`Fer`3en.`n
`3Ti`Fer`#e die einst existiert haben und die noch existieren werden. Nach langer `FZe`3it`n
`3er`Fre`#ichst du eine weitere Plattform, von der du ganz `íA`Kr`wd`Qa`# überblicken ka`Fnn`3st,`n
`3so`Ffe`#rn der Himmel wolkenfre`Fi i`3st.`n`n`c");

                viewcommentary("treppe2","sagt:",15);
                addnav("nach unten","elfengarten.php?op=leben");
                addnav("Seitenäste","elfengarten.php?op=seite");
                break;
        }

        case'seite':
        {
                page_header("Seitenäste");
                output("`c`bSeitenäste`b`c`n
                beschreibung");

                viewcommentary("seite","sagt:",15);
                addnav("In den Baum","elfengarten.php?op=treppe2");
                //später noch möglichkeiten
                break;
        }
        default:
        {

                page_header("Die hängenden Gärten");

                output("`c`b`DDie `Shän`dgen`Sden `2Gä`@rt`2en`b`c`n`n

`c`3Üb`Fer `#eine Strickleiter, die an einem hohen Baum befestigt ist, kommst `Fdu `3zu`n
`Dden `Shän`dgen`Sden `2Gä`@rt`2en.`n
`3Ho`Fch `#oben, scheinbar nur von den Ästen der Bäume getragen, sind sie zu fi`Fnd`3en.`n
`3Si`Fe b`#ieten einen atemberaubenden Anblick. Das `^So`Znn`qen`Zli`^cht`#, welches durch die Blätter sch`Fei`3nt,`n
`3wi`Frf`#t sich im Wind bewegende `QLi`qch`^tsä`qul`Qen`#, die fortwährend über die angelegten `âBe`ée`âte`# zu wandern sche`Fin`3en`n
`3un`Fd j`#ede einzelne Blüte immer wieder in einen anderen Licht hervor hebt. Du lässt deinen Blick wan`Fde`3rn`n
`3un`Fd ü`#berlegst, ob du an diesem Ort verweilen oder doch weiter gehen solltest. Nicht weit vo`Fn d`3ir`n
`3en`Ftd`#eckst du einen kleineren Garten, der von einem heiligen Licht umgeben scheint. Vor di`Fes`3em`n
`3Ga`Frt`#en liegt eine Wendeltreppe, die nach unten f`Füh`3rt.`n
`3Et`Fwa`#s weiter entfernt denkst du, Behausungen zu erke`Fnn`3en`n`n`n`c
                ");


                viewcommentary("elfengarten","sagt",15);

                addnav("der heilige Garten","elfengarten.php?op=heiligergarten");
                addnav("zurück zum Boden","sanela.php");
                addnav("magische Wendeltreppe nach unten","moor_unten.php?op=teich");
                addnav("Elfenbehausungen","elfengarten.php?op=haus");

                break;
     }

}
page_footer();

?> 