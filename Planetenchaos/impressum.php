
<?php

require_once 'common.php';

if ($_GET['ref'])

    {

    $werber = $_GET['ref'];

    $rest = '&ref='.$werber.'';

    $rest2 = '?ref='.$werber.'';

    }

page_header('Impressum');

output('`c`b`$Impressum`0`b`c`n`n');

rawoutput('<table width="50%" border="0"><tr><td width="25%" valign="top">Verantwortliche:</td><td>Dorit Graumann alias Lori<br><br></td></tr><tr><td valign="top">Email:</td><td>lori_merydia@hotmail.de<br><br></td></tr><tr><td valign="top">Adressse:</td><td>NeustraÃŸe 29<br>47137 Duisburg<br><br></td></tr></table>');

addnav('Tore der Welt','index.php'.$rest.'');

page_footer();

?>

