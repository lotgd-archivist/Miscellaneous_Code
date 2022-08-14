
<?php 
$gold = e_rand($session[user][level]*10,$session[user][level]*50); 
output("`&Als du durch die Berge wanderst, kommt ein Mann auf dich zu. Er ist ein Angestellter deiner Bank. `0"); 
$was = e_rand(1,2);
if ($was==1)
{
output("`&Er sagt, dass sie einen Fehler gemacht haben und deinem Konto`^ $gold Gold`0 gutgeschrieben werden!`n`n`^Du Glücklicher! `0"); 
$session[user][goldinbank]+=$gold; 
debuglog("$gold Gold dazu von der Bank"); 
}
else
{
output("`&Er sagt, dass sie einen Fehler gemacht haben und von deinem Konto`^ $gold Gold`0 abgezogen werden müssen!`n`n`^Pech Pech ...! `0"); 
$session[user][goldinbank]-=$gold; 
debuglog("$gold Gold weg von der Bank"); 
}

?>

