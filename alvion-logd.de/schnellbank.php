
<?php

// 22062004

require_once "common.php";
page_header("Wichtelhain");
output("`^`c`bDer Wichtelhain`b`c`n");
if ($_GET[op]==""){
  checkday();
$_POST[amount]=$session[user][gold];
        output("`&Völlig erschöpft schlenderst du durch den Wald und bemerkst dabei, dass dein Geldbeutel immer schwerer und schwerer wird. Plötzlich erkennst du im Moos ein kleines Zipfelmützchen, welches flink hin und her huscht. Natürlich kannst du es nicht unterlassen und gehst deiner Neugier nach, um heraus zu finden, was es war. Eiligen Schrittes gehst du in die Richtung, in die es verschwunden ist und erkennst auf einmal viele kleine Männlein vor dir, die flink Goldstücke in Richtung der Stadt tragen. Plötzlich spricht dich auch eines der Wesen an `^Grüße, Grüße `&fiepst es leise `^Einmal den Geldbeutel zur Bank schaffen? Wird sofort erledigt! `&Noch ehe du dich versehen kannst, kommt auch schon ein ganzer Trupp der kleinen Wichtel auf dich zu gerannt und macht sich sogleich mit deinem Säckel in Richtung Alvion davon, dabei trägt jedes der Wichtelchen gerade mal ein Goldstück. Es muss lustig aussehen, wenn plötzlich  eine ganze Truppe der kleinen Wesen durch die Stadt flitzt!`n`n`n");
        $session[user][goldinbank]+=$_POST[amount];
        $session[user][gold]-=$_POST[amount];
        output("`&Von der Ferne vernimmst Du noch ein: `9Du hast damit ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." `t".abs($session[user][goldinbank])." Gold `9auf deinem Konto und `t".$session[user][gold]." Gold `9hast du bei dir.\"");
    }


addnav("Zurück in den Wald","forest.php");
page_footer();

?>

