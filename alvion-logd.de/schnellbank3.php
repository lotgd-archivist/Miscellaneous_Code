
<?php

// 22062004

require_once "common.php";
page_header("Der Fledermausexpress");
output("`^`c`bDer Fledermausexpress`b`c`n");
if ($_GET['op']==""){
    checkday();
    $_POST['amount']=$session['user']['gold'];
    output("`&Dein Beutel voller Gold wird immer schwerer, die Last scheint dich mehr und mehr zu ermüden als dich deine Schritte weiter durch die Tropfsteinhöhle führen. Auf einmal gehst du in Deckung als eine ganze Schar Fledermäuse über deinen Kopf hinweg saust. Ihre Flügel schlagen wild umher und als du dann deinen Blick hinauf richtest entdeckst du, dass jeder dieser Fledermäuse ein kleines Säckchen voller Gold mit sich trägt. Eine der Fledermäuse landet gekonnt direkt neben dir und gibt in hohen Tönen von sich „Das Gold soll ins Dorf geschafft werden? Direkt zu deiner Bank?“ Ein Nicken der Fledermaus erfolgt an seine Genossen und ehe du sich versiehst wenden die bereits vorbeigeflogenen Mäuschen ihre Richtung, sie kommen geradewegs auf dich zu. Noch bevor du irgendetwas sagen kannst schnappen sie sich deinen Beutel voller Gold und fliegen damit hinfort. Der Beutel ist so schwer, dass sie diesen mit mehreren zugleich schleppen müssen, aber sie scheinen es irgendwie zu schaffen.`n`n`n");
    $session['user']['goldinbank']+=$_POST['amount'];
    $session['user']['gold']-=$_POST['amount'];
    output("`&Die kleinste Fledermaus kommt zurück geflogen und berichtet dir: `9\"Du lagerst derzeit `^".abs($session['user']['goldinbank'])." Gold `9auf der Bank und `^".$session['user']['gold']." Gold `9trägst du bei dir.\"");
}

addnav("Zurück zur Höhle","hoehle.php");
page_footer();


