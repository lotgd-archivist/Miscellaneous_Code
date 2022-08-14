
ï»¿<?php



// 07092005



$gold = e_rand($session['user']['level']*10,$session['user']['level']*50);

output("`^Das GlÃ¼ck lÃ¤chelt dich an. Du findest $gold Gold!`0");

$session['user']['gold']+=$gold;

//debuglog("found $gold gold in the forest");

?>

