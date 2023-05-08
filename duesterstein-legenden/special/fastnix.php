
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();
output("`nAuf Deinem Streifzug durch den Wald wirst Du auf etwas besonderes
Aufmerksam gemacht. Nur leider fällt Dir nicht auf, wo oder was es sein soll.`n
Du ärgerst Dich und willst schon weiter ziehen, als Du Dich entscheidest
nun doch nochmal `8ganz genau`0 zu suchen.... Und plötzlich verstehst Du: `n
`gDas Besondere ist das Besondere selbst.`0");
output("`n`nDu kassierst `^1 Gold Prämie`0 für die Erkenntnis und gehst schnell weiter,
bevor Du nochmal drüber nachdenkst...`0");
$session['user']['gold']++;
?>


