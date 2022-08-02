<?php
//Fary 3
//Neppar
//modi by Squall
//nighti215@hotmail.de
output('Als du durch den Wald läufts ,gelangst du plötzlich auf eine Wiese voller stinkender Blumen? Plötzlich kommt eine hässliche und fette Fee auf dich zu ...O tapferer Krieger! Du bist hergekommen, um mich zu unterhalten? Plötzlich lacht die Fee schelmisch und meint nur...Du bist sooo hässlich Danach schlägt sie dich ko und du fällst wie ein Stück Brot um. In der Zeit wo du genüßlich im Land der Träume bist vergreift sie dich an dir,wer weiß wat die alte mit dir angestellt hat!!. `nDu verlierst einen Waldkämpf beim Schnarchen! Als du aufwachst, bemerkst du jedoch, dass du `Qhässlichher geworden bist und du fühlst dich sehr `Qschwach!');
if ($session['user']['hitpoints']>5) $session['user']['hitpoints']=5;
$session['user']['charm'] -= 3;
$session['user']['turns']-=1;
addnav('Zurück in den Wald','forest.php');
addnews($session['user']['name'].' `^wurde im Wald von einer Fetten Fee ins Land der Träume befördert und Gott weiß nur,was dort noch passiert ist .');
?>