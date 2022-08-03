<?php
/*Die Friedhofskapelle und
von Narjana*/
require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
/*        case '':
        {
                page_header("Kleiner Teich");
                output("`c`bTeich 1`c`b`n
                                Beschreibung`n");

                viewcommentary("teich","sagt:",",15););
                addnav("zu den Gräbern","moor.php?op=wander");
                addnav("in den Sumpf","moor.php");
                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }*/
        case 'krypta':
        {
                page_header("die Krypta");
                output("`c`b`RD`Gi`7e K`&ry`7pt`Ga `R... ?`n`n`b

`&D`7i`Ge Treppe führt vielleicht etwas weiter hinab als man erwartet hät`7t`&e.`n
`&D`7i`Ge Krypta bietet vielleicht auch überraschend viel Pla`7t`&z.`n
`&T`7a`Gtsächlich scheint sie weitläufiger zu sein als die Kapelle darüb`7e`&r.`n
`&G`7e`Gstützt von massiven Säulen verläuft der Raum weit`7e`&r,`n
`&a`7l`Gs du ohne Lichtquelle sehen kann`7s`&t.`n
`&D`7a`Gs einzige, was er wirklich mit einer Krypta gemein hat, ist wo`7h`&l,`n 
`&d`7a`Gss entlang der Wände steinerne Särge aufgereiht si`7n`&d,`n
`&a`7l`Glesammt so verwittert, dass sie Jahrhunderte alt sein müss`7e`&n.`n
`&L`7a`Gngsam und stetig verläuft der Boden in sehr breiten, flachen Stufen abwär`7t`&s.`n
`&I`7m`Gmer weiter ziehen sich die Reih`7e`&n,`n
`&u`7r`Galte Tote bis zur Decke einer hinter dem ander`7e`&n`n
`&u`7n`Gd bis zur Decke gestapelt, bis, nach einigen Mete`7r`&n,`n
`&d`7i`Ge Rückwand der unwahrscheinlich langen Krypta in der Düsternis auftauc`7h`&t.`n
`&S`7o`Gwie sich in dir die Erleichterung breit mac`7h`&t,`n
`&d`7a`Gss in dieser Architektur doch noch irgendwo Logik vorhanden ist, erkenns`7t d`&u:`n`n

`&D`7a`Gs ist keine Wa`7n`&d.`n
`GDas i`7st ein `&riesige`7r Torb`Gogen.`n`c

                ");

                viewcommentary("krypta","sagt:",15);
                addnav("großer Torbogen","rui_tempel.php");
//                addnav("kleine Seitennische","kapell.php?op=nische");
                addnav("zur Kapelle","kapell.php");
//                addnav("magische Wendeltreppe hochsteigen","Elfengarten.php");
                break;
        }
        default:
        {

                page_header("Friedhofskapelle");

                output("`c`b`RDi`Ge Fr`7ied`&hof`7ska`Gpel`Rle`b`n`n


`&M`7i`Gtten auf dem Friedhof findet sich die Kapel`7l`&e.`n
`&H`7i`Ger werden Beerdigungen abgehalten, sowie alle möglichen Abschiedszeremoni`7e`&n,`n
`&G`7e`Gdenkfeiern, jedes nur erdenklichen Rituale des Loslasse`7n`&s.`n
`&M`7a`Gnch ein des Lebens müder, so sagt man, kommt sogar h`7e`&r,`n
`&u`7m `Gan Ort und Stelle um sein Ende zu bitt`7e`&n.`n
`&A`7u`Gch solche die nach dem Leben eines Anderen trachten, beten manchmal hier dar`7u`&m,`n
`&d`7o`Gch ob diesen Bitten nachgegangen wird, davon wird nicht gesproch`7e`&n.`n

`&D`7e`Gr Schein von Kerzen füllt den Raum mit flackernden Schatt`7e`&n,`n
`&u`7n`Gd der Duft von Räucherwerk und welkenden Blumen hängt in der Lu`7f`&t.`n
`&I`7n `Geinigen Winkeln finden sich Statuen und andere Darstellungen des Tod`7e`&s,`n
`&w`7i`Ge sie in diversen Glaubensrichtungen üblich si`7n`&d.`n
`&A`7n `Gmanchen Stellen sind Opfergaben zu seh`7e`&n,`n
`&S`7p`Geisen, Blumen, Knochenwürfel, Wertgegenstän`7d`&e,`n
`&k`7l`Geine Schnitzereien, sogar Haarsträhn`7e`&n.`n
`&N`7i`Gcht selten trifft man auf den einen oder anderen Priest`7e`&r.`n
`$ Die Opfergaben anzurühren könnte eventuell eine schlechte Idee sein.`n`n

`&E`7i`Gne alte, ausgetretene Steintreppe führt nach unten in eine Kryp`7t`&a,`n
`&a`7u`Gch wenn die Inschrift schon längst nicht mehr lesbar i`7s`&t,`n
`&u`7n`Gd so niemand weiß, wen oder was sie beherber`7g`&t.`n

                `n`c
                ");


                viewcommentary("kapell","sagt",15);

                addnav("aus der Kapelle","friedhof.php");
                addnav("Treppe zur Krypta","kapell.php?op=krypta");

                break;
     }

}
page_footer();

?> 