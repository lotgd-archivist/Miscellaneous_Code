
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

 $gold = $session[user][level]*(e_rand(1,50)-25);
 if ( $gold == 0 ) $gold = $session[user][level];
 output("`n`3Aufgeregt kommt Dir jemand aus dem Dorf nachgelaufen.
 `#\"".$session[user][name].", ".$session[user][name]."!\"`0 `nDu fragst Dich was los
 ist und schon erfährst Du die Neuigkeit:`n`n
 `%Die Bank hat einen Fehler gemacht.`0");
 if ( $gold > 0 ) {
     output("Deinem Konto wurden aus versehen `^$gold Goldstücke gutgeschrieben.`0");
     $session[user][goldinbank]+=$gold;
 }
 else {
     $gold = $gold * (-1);
     output("Die haben von Deinem Konto irrtümlich `Q$gold Goldstücke abgebucht.`0");
     $session[user][goldinbank]-=$gold;
     if ( $session[user][goldinbank] < 0 ) output("`n`QDu hast jetzt sogar Schulden!`0");
 }

?>


