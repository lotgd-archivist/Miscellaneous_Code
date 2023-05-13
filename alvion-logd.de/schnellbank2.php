
<?php

// 22062004

require_once "common.php";
page_header("Wichtelhain");
output("`^`c`bDer Wichtelhain`b`c`n");
if ($_GET[op]==""){
  checkday();
$_POST[amount]=$session[user][gold];
        output("`&Völlig erschöpft schlenderst du durch den Wald und bemerkst dabei, dass dein Geldbeutel immer schwerer und schwerer wird. Plötzlich erkennst du im Moos ein kleines Zipfelmützchen, welches flink hin und her huscht. Natürlich kannst du es nicht unterlassen und gehst deiner Neugier anch um heraus zu finden was es war. Eiligen Schrittest gehst du in die Richtung in der es verschwunden ist und erkennst auf einmal viele kleine Männlein vor dir, die flink Goldstücke in Richtung der Stadt tragen. Plötzlich spricht dich auch eines der Männlein an `^Grüße, Grüße `&fiepst es leise `^Einmal den Geldbeutel zur Bank schaffen? Wird sofort erledigt `&noch ehe du dich versehen kannst, kommt auch schon ein ganzer Trupp der kleinen Wichtel auf dich zugerennt und macht sich sogleich mit deinem Geldbeutel in Richtung Alvions davon, dabei trägt jedes der Wichtelchen gerade einmal ein Goldstück. Es muss lustig aussehen, wenn auf einmal eine ganze Schlange der kleinen Wesen durch die Stadt läuft`n`n`n");
        $session[user][goldinbank]+=$_POST[amount];
        $session[user][gold]-=$_POST[amount];
        output("`&Von der Ferne vernimmst Du noch ein: `9Du hast damit ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." `t".abs($session[user][goldinbank])." Gold `9auf deinem Konto und `t".$session[user][gold]." Gold `9hast du bei dir.\"");
    }


addnav("Zurück in die Berge","berge.php");
page_footer();

?>

