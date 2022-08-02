<?php
if (!isset($session)) exit();
output('`n`@Während du durch den Wald wanderst, begegnest du einer Gruppe fröhlicher Reisender.');
if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
output('`n`nDu trinkst ein paar Becher Ale mit ihnen und regenerierst deinen Körper!');
$session['user']['hitpoints']=$session['user']['maxhitpoints'];
}else{
$session['user']['gems']++;
output('`n`nNach ein paar Stunden des Feierns und Geschichtenerzählens müssen die Reisenden leider weiterziehen. Zur Erinnerung an die schönen Stunden schenken sie dir einen `^Edelstein`@!');
}
?>