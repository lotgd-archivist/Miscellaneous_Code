<?php

// 21072004
page_header ( "Die Waldlichtung" );
require_once "common.php";
addcommentary ();
checkday ();

// default:
// {

page_header ( "Die Waldlichtung" );

output ( "`c`bDie Lichtung im Wald`b`c`n
                `n
                " );

viewcommentary ( "lichtung", "sagt", 15 );

// addnav("Lurnfälle","nan.php");
addnav ( "zurück" );
addnav ( "Wegkreuzung", "kreuzung.php" );
// addnav("Zwergenstadt","zwergenstadt.php");

break;
// }
page_footer ();
?> 