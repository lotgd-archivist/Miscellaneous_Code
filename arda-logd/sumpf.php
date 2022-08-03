<?php
/*
Ort das Moor
geschrieben für Arda 'www.arda-logd.de'
Von Narjana
***********
zum installieren einfach in
Zylyma addnav("Zum Moor","moor.php");
eingeben
************
*/

require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
        case 'kloster':
        {
                page_header("Verfallenes Kloster");
                output("test`n");

                viewcommentary("kloster","sagt:",15);

                addnav("In die Sümpfe","moor.php");
//                addnav("zum Friedhof","moor.php?op=friedhof");

                break;
        }

        case 'lights':
        {
                page_header("Irrlichter");
                output("test`n");
                viewcommentary("lights","sagt:",15);

                addnav("in die Sümpfe","moor.php");

                break;
        }

        case 'friedhof':
        {
                page_header("alter Friedhof");
                output("test`n");
                viewcommentary("friedhof","flüstert:",25);

                addnav("In die Sümpfe","moor.php");

                break;
        }

//if ->GrabPhoenix*/

        default:
        {
                page_header("Sümpfe der verborgenen");
                output("test`n");
                viewcommetary("moor","sagt:",15);

                addnav("verfallenes Kloster","moor.php?op=kloster");
                addnav("folge den Irrlichtern","moor.php?op=lights");
                addnav("zum alten Freidhof","moor.php?op=friedhof");
                addnav("zurück");
                addnav("Zylyma","Necron.php");
                addnav("Zur Wegkreuzung","kreuzung.php");

                break;
        }
}
page_footer();

?> 