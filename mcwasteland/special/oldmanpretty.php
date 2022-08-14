
<?php
if (!isset($session)) exit();
output("`^Ein alter Mann schlÃ¤gt dich mit einem schÃ¶nen Stock, kichert und rennt davon!`n`nDu `%bekommst einen`^ Charmepunkt!`0");
$session['user']['charm']++;
?>

