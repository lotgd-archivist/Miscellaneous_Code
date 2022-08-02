<?php

require_once("common.php");
checkday();

page_header("Pandea Island unterstützen");

output("`^Falls du uns unterstützen möchtest und daran interessiert bist Pandea Island den ein oder anderen Bewohnter mehr zu bringen,
würden wir uns freuen wenn du auf den folgenden Seiten für uns voten würdest. Durch deine Mithilfe wird Pandea Island bekannter und mit
mehr Usern noch abwechslungsreicher. Im Namen des gesammten Teams und anderen an Pandea Island interessierten Usern: Danke für deine Mithilfe!`n`n`n");

output("<script language=\"Javascript\" type=\"text/javascript\" src=\"http://game-toplist.de/image1.php?id=schnefels\"></script>",true);
output("<a href='http://www.eigene-topliste.de/cgi-bin/listen/in.cgi?6A513D353137267545763D34313732' target='_blank'><img src='http://www.eigene-topliste.de/listen/muster/88x31blue.gif' width='88' height='31' border='0' alt=''></a>",true);
output("`n");

addnav("Zurück zum Dorfplatz","village.php");

page_footer();
?> 