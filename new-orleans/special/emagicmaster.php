
<?php


#######################
# scripted by Hadriel #
#  required addons:   #
#_____________________#
# -tournament addon-  #
#      by hadriel     #
#_____________________#
#######################

require_once "common.php";

if(!isset($session[user][specialinc])) exit();
adddbfield('accounts','emagic_skill','int(11)','name',10);
$skill = $session[user][emagic_skill]*250;
$goldcost = 1000 + $skill - 2500;
if($_GET[op]=="" || $_GET[op]=="search")
{
output("`^Du landest auf einer kleinen Lichtung, auf der ein alter Hof steht. Vor dem Hof sitzt ein Mann mit einem dunklen Stab.");
output("`nEr begrüsst dich.");
output("\"`qHallo! Ich bin der beste Magier des Bösen dieses Landes, ich könnte dich ja unterrichten!`^\"");
$session[user][specialinc]="meleemaster.php";
    output("`n\"`qDu kannst bei mir für $goldcost Gold trainieren! Los, mach schon!`^\"");
      addnav("Trainieren ($goldcost Gold)","forest.php?op=skill&goldcost=$goldcost");
    addnav("Den Hof verlassen","forest.php?op=leave");
}
else if($_GET[op]=="skill")
{

$session[user][specialinc]="";
    if($session[user][gold]>=$_GET[goldcost])
    {
        if($session[user][emagic_skill]<=99){
    output("Der Meister des Bösen unterrichtet dich für ".$_GET[goldcost]." Goldstücke in derr Kunst der Magie des Bösen.");
    $session[user][gold]-=$_GET[goldcost];
    $session[user][emagic_skill]++;
    output("`nDu bist nun in der Magie des Bösen auf Stufe ".$session[user][emagic_skill]." !");
        }
        else
        {
        output("Der Meister des Bösen verscheucht dich. \"`qDu bist schon genug erfahren!`^\"");
        }
    }
    else
    {
    output("Der Meister des Bösen verscheucht dich. \"`qKomm wieder wenn du genug Gold hast!`^\"");
    }
    
}
else if($_GET[op]=="leave")
{

$session[user][specialinc]="";

}


?>

