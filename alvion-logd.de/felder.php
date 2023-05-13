
<?php
require_once "common.php";
addcommentary();
checkday();


page_header("Die Felder");
$session['user']['standort']="Die Felder";

if (getsetting("pvp",1)){
   if (!$session['user']['rp_only'])addnav("Spieler töten","pvp.php");
}
addnav("In die Felder (Logout)","login.php?op=logout",true);
addnav("Zurück ins Dorf","village.php");

output("`c`b`eD`Gi`ge`o Fel`gd`Ge`er`b`c");
output("`eLa`Gng`ge we`oite Wiesen und F`gel`Gde`er, erstreck`Gen `gsic`oh vor dir. De`gr Ge`Gru`ech von Blume`Gn u`gnd `ovon Gras ste`gig`Gt di`er in die Nase `gun`Gd lä`esst dich ent`Gzü`gcke`on. Überall ha`gbe`Gn ein`eige Dorfbewohner ih`Gre`g Zel`ote aufgeschlagen. Au`gs m`Ganc`ehen von ihnen k`Gan`gn ma`on ein lautes Sch`gnar`Gche`on hören, andere K`gri`Gege`er sitzen vor ihre`Gn Z`gelt`oen und erzählen si`gch e`Gtwa`es oder sortieren ih`Gre`g Ha`obseligkeiten! Ab u`gnd `Gan `ehörst du ein laute`Gs S`gch`oreien und siehst wie j`gem`Gan`ed sich von ein`Gem `gder`o Zelte wegschleicht, wa`grsc`Ghei`enlich hat dieser J`Gem`gan`od gerade einen anderen Be`gwoh`Gne`er getötet! Leise mu`Grm`gel`ond überlegst du di`gr, w`Gas `edu nun tust.`n`n");
output("`GJem`gan`oden töten? Od`ger`G di`ech lieber sch`gla`Gfe`en legen?`n`n`n");
viewcommentary("felder","Hinzufügen",25,"sagt",1,1);

page_footer();
?>

