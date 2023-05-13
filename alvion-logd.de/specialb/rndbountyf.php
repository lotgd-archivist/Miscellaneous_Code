
<?php
require_once "common.php";
if ( $session[user][bounty] > 0 ) {
        output("Du triffst auf einen Gesandten der Stadtverwaltung`0, der Dich sehr
        misstrauisch beäugt. \"Bist Du nicht.....\" spricht Dich der Gesandte an.
        `nLeider warst Du kurz abgelenkt, so dass Du nur noch den Schluß hörst.
        `^\"Aber auf Dich ist ja schon, `9übrigens völlig zu Recht, `^ein Kopfgeld ausgesetzt!\"`0
        `n`nDir ist eigentlich egal, was er wollte und Du ziehst weiter.`0");
} else {
    $bount = (e_rand(100,1000)*$session[user][level]);
    $session[user][bounty]=$bount;
    output("`4Auf Dich wurde ein Kopfgeld von`6 $bount Gold `4ausgesetzt!`n");
    $user = $session[user][name];
    addnews("`4Auf $user `4wurde ein Kopfgeld von `6 $bount Gold `4ausgesetzt.`0");
}
addnav("Zurück in die Berge","berge.php");

?>

