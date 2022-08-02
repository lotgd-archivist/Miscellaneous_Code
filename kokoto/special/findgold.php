<?php
$gold = mt_rand($session['user']['level']100,$session['user']['level']500);
output("`^Du läufst so durch den Wald als da aufeinmal eine Wurzel eines Baumes ist über die du stolperst und auf den Boden knallst.`^Das Glück lächelt dich mehr oder weniger an. Du findest $gold Gold! Das du dafür hingefallen bist hast du schonwieder vergessen. `0");
$session['user']['gold']+=$gold;
debuglog("found $gold gold in the forest");
?>