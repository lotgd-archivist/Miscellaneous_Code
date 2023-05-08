
<?
$gold = e_rand($session[user][level]*10,$session[user][level]*50);
output("`^Das Glück lächelt dich an. Du findest $gold Gold!`0");
// addnav("Weiter","forest.php");
$session[user][gold]+=$gold;
debuglog("found $gold gold in the forest");
?>


