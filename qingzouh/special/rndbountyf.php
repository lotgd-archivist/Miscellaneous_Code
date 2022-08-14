
<?php
    require_once "common.php";
    $bount = (e_rand(100,1000)*$session[user][level]);
    $session[user][bounty]+=$bount;
    output("`4Auf Dich wurde ein Kopfgeld von`6 $bount Gold `4ausgesetzt!`n");
    $user = $session[user][name];
    addnews("`4Auf $user `4wurde ein Kopfgeld von `6 $bount Gold `4ausgesetzt.`0");
    $total = $session[user][bounty];
    output("`n`2Insgesamt sind auf Deinen Kopf jetzt `6$total Gold `4ausgesetzt.");
    addnav("Zurück in die Berge","berge.php");
?>

