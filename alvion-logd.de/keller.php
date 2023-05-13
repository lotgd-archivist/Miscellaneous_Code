
<?php

require_once "common.php";
// checkday();
global $session;
page_header("Der dunkle Keller");

if ($_GET[op]==""){
    output("`t`c`bDer dunkle Keller`b`c`n");
    if($session['user']['orden']>=10){
        output("`tDu betrittst ein dunkles und feuchtes Verlies. Eine schmierige, grobschlächtige Gestalt tritt dir mit kleinen, böse funkelnden Augen entgegen und spricht dich an `EMan nennt mich Gerrit den Schlächter. Zu mir kommen nur die Verzweifelten, die keinen Ausweg mehr sehen. Was kann ich für dich tun mein".($row[sex]?"e":"")." ".($row[sex]?"Schwester":"Bruder")."? Doch bedenke, dass meine Dienste nicht kostenlos sind, sie werden dich `^1000 Gold`E oder `%2 Edelsteine `Ekosten. Aber wage es nicht, sie ohne ausreichende Geldmittel in Anspruch zu nehmen.`n`n`n");
        addnav("Gerrit bezahlen");
        addnav("`^1000 Gold","keller.php?op=gold");
        addnav("`%2 Edelsteine","keller.php?op=gems");
    } else {
        output("`tDu hast noch nicht das Ansehen erworben, das du benötigst, um Gerrit gegenüber treten zu dürfen!`n`n");
    }
    addnav("Ausgang");
    addnav("Z?Zurück","inn.php");
    addnav("u?Zurück zum Dorf","village.php");

} else if ($_GET[op]=="gold" && $session['user']['gold'] >=1000){
    $session['user']['gold']-=1000;
    $session['user']['reputation']--;
    $session['user']['hitpoints']=0;
    $session['user']['alive']=0;
    output("`tGerrit nimmt mit einem schmierigen Grinsen das Gold entgegen. Kurz darauf spürst du einen stechenden Schmerz in deiner Brust. Du blickst an dir hinunter, siehst ein Messer, das bis zum Heft in deiner Brust steckt und du fühlst, wie deine Knie weich werden und dir die Sinne schwinden. Du bist `\$tot`t!`n`n");
    addnews("`\$".$session[user][name]." `ystarb an einer Stichverletzung.");

} else if ($_GET[op]=="gems" && $session['user']['gems'] >=2){
    $session['user']['gems']-=2;
    $session['user']['reputation']--;
    $session['user']['hitpoints']=0;
    $session['user']['alive']=0;
    output("`tGerrit nimmt kichernd die Edelsteine entgegen. Kurz darauf spürst du einen stechenden Schmerz in deiner Brust. Du blickst an dir hinunter, siehst ein Messer, das bis zum Heft in deiner Brust steckt und du fühlst, wie deine Knie weich werden und dir die Sinne schwinden. Du bist `\$tot`t!`n`n");
    addnews("`\$".$session[user][name]." `ystarb an einer Stichverletzung.");

} else if (($_GET[op]=="gold" && $session['user']['gold'] <1000) || ($_GET[op]=="gems" && $session['user']['gems'] <2)){
    output("`tDu greifst in deine Taschen und willst Gerrit die Entlohnung geben. Erschrocken stellst du fest, dass du nicht genug bei dir hast. Gerrit stürzt sich mit vor Hass verzerrter Fratze auf dich `EFür diesen Betrug wirst du langsam und qualvoll sterben `tschreit er dich an, während seine kräftigen Hände langsam deine Kehle zudrücken. Du bist `\$tot`t! Deine entstellten Gesichtszüge kosten dich Charme und der Betrug schädigt dein Ansehen.`n`n");
    $cv=rand(3,6);
    $session['user']['charm']-=$cv;
    if($session['user']['charm']<0) $session['user']['charm']=0;
    $session['user']['reputation']-=5;
    $session['user']['gold']=0;
    $session['user']['hitpoints']=0;
    $session['user']['alive']=0;
    addnews("`\$".$session[user][name]." `ywurde erwürgt aufgefunden. In ".($session['user']['sex']?"ihren":"seinen")." gebrochenen Augen war noch das Grauen zu erkennen.");
    output("`^Du verlierst `\$".$cv."`^ Charmepunkte");
}

if ($session['user']['alive']==0) addnav("Zum Schattenreich","shades.php");

page_footer();
?>

