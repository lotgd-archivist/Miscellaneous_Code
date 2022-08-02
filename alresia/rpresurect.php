<?php
// Idee & Umsetzung
// Morpheus für www.morpheus-lotgd.de.vu
// Mail an morpheus@magic.ms
// Gewidmet meiner über alles geliebten Blume
require_once("common.php");
page_header("RP Auferstehung");
if($_GET['op']==""){
    output("`7Niva sieht Dich grinsend an:\"`4So so, wartet wohl noch jemand in der Oberwelt auf Dich, hmmm?");
    output("Ich kann Dich auferstehen lassen, das kostet Dich dann nur 10 Gefallen`7\" sagt sie grinsend\"`4 aber Du wirst keine Zeit mehr für Waldkämpfe haben und kein Zinsen bekommen, nur Deinem normalen Leben nachgehen können, bist Du Dir sicher, daß Du das willst?`7\"fragt sie Dich.");
    addnav("J?Ja","rpresurect.php?op=ja");
    addnav("N?Nein","graveyard.php?op=enter");
}
if($_GET['op']=="ja"){
    if ($session['user']['deathpower']>=10){
        output("`7Niva schüttelt lachend den Kopf:\"`4Na, das scheint Dir ja wirklich am Herzen zu liegen, dann will ich mal nicht so sein.`7\"");
        $session['user']['deathpower']-=10;
        $session['user']['turns']=0;
        $session['user']['alive']=1;
        $session['user']['hitpoints']=1;
        $session['user']['auferstanden']=1;
    
        addnav("W?Weiter","rpresurect.php?op=hoch");
    }
    if ($session['user']['deathpower']<10){
        output("`7Wütend hebt Niva die Faust:\"`4Du willst mich wohl für dumm verkaufen?!? Verschwinde, Du elender Wurm, bevor ich dich zerquetsche und nie mehr hier weg lasse!!!`7\"");
        $session['user']['gravefights']=0;
        if ($session['user']['deadtreepick']=0){
            $session['user']['deadtreepick']=1;
        }
        $session['user']['reputation']-=10;
        addnav("W?Weiter","graveyard.php?op=enter");
    }
}
if($_GET['op']=="hoch"){
    output("`7Niva spricht eine Beschwörungsformel, in Deinen Ohren dröhnt es, Dir wird schwarz vor Augen und ein Schwindel erfaßt Dich, alles dreht sich um Dich!`n");
    output("`7Du schließt die Augen, hörst ein Rauschen und als Du die Augen wieder öffnest, stehst Du auf dem Brunnenplatz, in Deinen Ohren dröhnen noch Nivas Worte nach:\"`4Pass ja gut auf, denn sterben kannst Du trotz allem wieder...und wenn nicht jetzt, dann ein andermal, aber wir sehen uns wieder!`7\"");
    addnews($session[user][name]." `3durfte aus `4Nivas Reich `3auferstehen, um der täglichen Beschäftigung nach zu gehen.");
    addnav("W?Weiter","village.php");
}
page_footer();
?> 