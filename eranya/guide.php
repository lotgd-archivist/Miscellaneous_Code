
<?php
/* * *
 * Wegweiser für Eranya (http://eranya.de)
 * * */
require_once 'common.php';
page_header('Wegweiser');
// Orte auflisten
liste_ingameorte();
// Nav
addnav('S?Zurück zum Stadtplatz','village.php');
addnav('M?Zurück zum Marktplatz','market.php');
addnav('H?Zurück zum Hafen','harbor.php');
// footer
page_footer();
?>

