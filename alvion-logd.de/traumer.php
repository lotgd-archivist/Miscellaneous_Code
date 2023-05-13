
<?php

require_once "common.php";
addcommentary();

page_header("Träumergasse");
$session['user']['standort']="Träumergasse";


if (@file_exists("brunnen.php")) addnav("B?verzauberter Brunnen","brunnen.php");
addnav("G?Die Gärten","gardens.php");

addnav("Die Rosengärten","gartenrp.php");
addnav("Allee zum Obstgarten","allee.php");
addnav("Z?Zurück zum Dorf","village.php");


output("`c`b`*T`3r`Wäu`#merg`Was`3s`*e`b`c`n
`3In Gedan`#ken betr`3ittst du d`#ie Träum`3ergasse `#und schau`3st dich i`#m ersten `3Moment v`#erwirrt u`3m, du fra`#gst dich, w`3ie du hie`#r her gel`3angt bist. D`#ein Blick b`3leibt an e`#inem Türc`3hen hänge`#n, an dem o`3berhalb e`#in Schild b`3efestigt i`#st, leider k`3annst du d`#ie Worte, d`3ie darauf ge`#schriebe`3n sind, nich`#t erkenne`3n. Um das z`#u können, m`3üsstest du n`#äher gehe`3n. Kurz übe`#rmannt dic`3h die Neugie`#rde, ehe du d`3einen Blick w`#eiter wand`3ern lässt. D`#u entdeck`3st noch an`#dere intere`3ssante Ding`#e...`n Meinst d`3u nicht, d`#ass es mal a`3n der Zeit w`#äre, diese G`3egend genau`#er zu begut`3achten`#?");

output("`n`n`%`@In deiner Nähe reden einige Dorfbewohner:`n");
viewcommentary("Traeumergasse","Hinzufügen",25,"träumt",1,1);

page_footer();
?>

