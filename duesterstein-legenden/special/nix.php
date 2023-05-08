
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();
output("`nAuf Deinem Streifzug durch den Wald erreichst Du eine kleine versteckte
Lichtung. Du schaust Dich um, kannst das Besondere hier aber nicht finden.`0");
output("`nDu ärgerst Dich und willst schon weiter ziehen, als Du Dich entscheidest
nun doch nochmal `8ganz genau`0 zu suchen....`0");
output("`n`nLeider kannst Du wirklich `%keine Besonderheit`0 finden. Die intensive Suche
hat Dich `61 Waldkampf gekostet!`0");
output("`nVerärgert machst Du Dich wieder auf den Weg.`0");
$session['user']['turns']--;
?>


