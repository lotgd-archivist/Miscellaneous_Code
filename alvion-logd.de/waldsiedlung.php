
<?php
require_once "common.php";
addcommentary();
checkday();

page_header("Waldsiedlung");
$session['user']['standort']="Waldsiedlung";

addnav("Zu den Baumhäusern hinaufklettern","houses.php?location=2");
addnav("Zurück zum Dorf","village.php");

output("`c`b`2Di`@e W`2al`@ds`2ie`@dl`2un`@g`b`n`n`n");
output("<img src='./images/waldsiedlung.gif'>`n`n`n`c",true);
output("`2Frisc`@h und`2 munt`@er er`2reich`@st du`2 eine`@n rec`2ht g`@roßen`2 Plat`@z mit`2ten i`@m Wal`2d, de`@r von`2 ural`@ten u`2nd mäc`@htige`2n Eic`@henbä`2umen `@umsäu`2mt wi`@rd. E`2s bl`@ühen `2Waldb`@lumen`2 an a`@llen `2mögli`@chen `2Stell`@en un`2d hin`@ und `2wiede`@r kan`2nst d`@u auc`2h ein`@ige d`2er Be`@wohne`2r Alv`@ions `2aus i`@hren `2Häuse`@rn hö`2ren. `@Ein k`2leine`@r Bli`2ck na`@ch ob`2en ve`@rrät `2auch `@wieso`2. `n Di`@rekt `2auf d`@en Ei`2chen `@wurde`2n ge`@mütli`2che B`@aumhä`2user `@erbau`2t, di`@e von`2 den
`@dicke`2n Äst`@en ge`2trage`@n wer`2den u`@nd so`2 sich`@erlic`2h etl`@iche `2Jahrz`@ehnte`2 über`@stehe`2n we`@rden. `2Errei`@chbar`2 sind`@ sie `2im üb`@rigen`2 auch`@ nur `2mit S`@trick`2leite`@rn, s`2odass`@ sie `2recht`@ sich`2er vo`@r Dieb`2esges`@indel`2 sind`@. `n`2
Ergri`@ffen `2von d`@ieser`2 natu`@rnah`2en Ro`@manti`2k üb`@erleg`2st du`@ dir `2doch `@glatt`2 ob e`@s nic`2ht be`@sser w`2äre h`@ier d`2ein e`@igene`2s kle`@ines `2Baumh`@äusch`2en zu`@ habe`2n ans`@telle`2 eine`@s mas`2siven`@ Haus`2es in`@ der `2Dorfs`@iedlu`2ng. `n`n`n");

viewcommentary("Waldsiedlung","Hinzufügen",25,"sagt",1,1);

page_footer();
?>

