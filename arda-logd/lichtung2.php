<?php

require_once "common.php";

addcommentary();
checkday();
addnav("Wegkreuzung","kreuzung.php");
output("`2`cDu betrittst eine doch recht große Waldlichtung. Als du dich umsiehst, `nentdeckst du einige Baumstümpfe, die so aussehen, als ob sie als Stuhlersatz dienen könnten. `nIrgendwie ist hier eine seltsame Stimmung zu spüren.`nOb hier wohl Hexen und Druiden ihre Versammlungen abhalten?`n`n");
viewcommentary("lichtung","verschwörend flüstern",15);

page_footer();

?> 