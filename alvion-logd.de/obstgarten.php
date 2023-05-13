
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Der Obstgarten");
$session['user']['standort']="Obstgarten";

addnav("Eine Frucht pflücken","pilzfee.php");
if($session['user']['kristalle']>=10) addnav("Vor den Stein treten","steintroll.php");

addnav("Zurück");
addnav("Zurück in die Allee","allee.php");
addnav("Zurück zur Träumergasse","traumer.php");
addnav("Zurück zum Dorf","village.php");


output("`c`b`GDer Obstgarten`b`c`n`n");
output("`8Du ko`emmst `Gaus d`ger Al`@lee h`ginaus `Gauf e`ein Fe`8ld, d`eas eh`Ger ei`gnem s`@chmuc`gken O`Gbstga`erten `8gleic`eht. Ü`eberal`gl ste`Ghen p`@rall `Gvoll `glecke`erster `8Früch`ete hä`Gngend`ge Bäu`@me, d`gie ma`Gn je `ein di`8esem `eDorfe `Ggeseh`gen ha`@t. Se`gi es `Gein P`eflaum`8enbau`em, ei`Gn Apf`gel-, `@Kirsc`gh- od`Ger ga`er ein `8Birnb`eaum, `Gso al`glerha`@nd i`gst hi`Ger ve`ertret`8en. Z`ewisch`Gendur`gch er`@blick`gst du `Gnoch `eHolun`8derbä`eume, `GHimbe`ger- u`@nd Br`gombee`Grsträ`eucher`8; sel`ebst d`Gie kl`geinen `@Walde`grdbee`Gren s`eind h`8ier z`eu fin`Gden. `gUnd m`gitten `@darin `gist e`Gine s`eaftig `8grüne `eWiese`G, die `geinen `@einlä`gdt, s`Gich d`eort `8niede`erzula`Gssen, `gum di`@e spi`geleri`Gsch u`emher `8flieg`eenden `GSchme`gtterl`@inge `gzu be`Gobach`eten. `eHier `8und d`ea fli`Gegt a`guch e`@ine F`gee vo`Grbei, `eum a`8n dem `esüßen `GNekta`gr zu `@nasch`gen. ");

output("`n`n`@Süßer Duft liegt in der Luft:`n");
viewcommentary("Obstgarten","Hinzufügen",25,"haucht",1,1);

page_footer();
?>

