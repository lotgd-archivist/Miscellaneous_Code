
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
adddbfield('accounts','cook_skill','int(11)','name',10);
$skill = $session[user][cook_skill]*250;
$goldcost = 1000 + $skill - 2500;
if($_GET[op]=="" || $_GET[op]=="search")
{
output("`^Du landest auf einer kleinen Lichtung, auf der ein alter Hof steht. Vor dem Hof sitzt ein Mann mit einem Bogen.");
output("`nEr begrüsst dich.");
output("\"`qHallo! Ich bin der beste Koch dieses Landes, ich könnte dich ja unterrichten!`^\"");
$session[user][specialinc]="cookmaster.php";
    output("`n\"`qDa du kein Koch bist, kannst du bei mir für $goldcost Gold trainieren! Los, mach schon!`^\"");
      addnav("Trainieren ($goldcost Gold)","forest.php?op=skill&goldcost=$goldcost");
    addnav("Den Hof verlassen","forest.php?op=leave");
}
else if($_GET[op]=="skill")
{

$session[user][specialinc]="";
    if($session[user][gold]>=$_GET[goldcost])
    {
        if($session[user][cook_skill]<=99){
    output("Der Kochmeister unterrichtet dich für ".$_GET[goldcost]." Goldstücke in derr Kunst des Kochens.");
    $session[user][gold]-=$_GET[goldcost];
    $session[user][cook_skill]++;
    output("`nDu bist nun im Kochen auf Stufe ".$session[user][cook_skill]." !");
        }
        else
        {
        output("Der Kochmeister verscheucht dich. \"`qDu bist schon genug erfahren!`^\"");
        }
    }
    else
    {
    output("Der Kochmeister verscheucht dich. \"`qKomm wieder wenn du genug Gold hast!`^\"");
    }
    
}
else if($_GET[op]=="leave")
{

$session[user][specialinc]="";

}


?>

