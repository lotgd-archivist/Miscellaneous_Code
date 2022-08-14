
<?php
if (!isset($session)) exit();
if ($session['user']['charm']>0){
    output("`^Ein alter Mann schlÃ¤gt dich mit einem hÃ¤sslichen Stock, kichert und rennt davon!`n`nDu `%verlierst einen`^ Charmepunkt!`0");
    $session['user']['charm']--;
}else{
    output("`^Ein alter Mann trifft dich mit einem hÃ¤sslichen Stock und schnappt nach Luft, als der Stock `%einen Charmepunkt verliert`^.  Du bist noch hÃ¤sslicher als dieser hÃ¤ssliche Stock!`0");
}
?>


