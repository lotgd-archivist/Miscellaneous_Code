<?php

//Idee von Zinsis
//Umsetzung von Trillian
// überarbeitet von Tidus www.kokoto.de
if (!isset($session)) exit();

output('`b`cDas Hündchen`c`b`n`n');
if ($_GET['op']=='feed'){
    switch(mt_rand(1,3)){
    case '1':
        output('`8Du ignorierst die Angst vor der Tollwut und näherst dich selbstsicher dem Hund. Du willst gerade über den Rücken des Tieres streichen, als dir etwas merkwürdig vorkommt. Jetzt erst erkennst du das der Hund nicht einen, nicht zwei sondern drei Köpfe hat. Als der Hund sich mit seinen Köpfen zu dir umdreht, fällt dir auch mit erschrecken der Name des Tieres wieder ein `$ZERBERUS`8, der Höllenhund. Nun hälst du ihm mit etwas zittrigerer Hand den Knochen hin und ärgerst dich, das du nur einen und nicht drei hast. Nun ja, du wolltest das Hündchen zu seinem Herrchen bringen und wer anderes als `$Luzifer`8 könnte das Herrchen von Zerberus sein ? `n Du machst dich auf den Weg zu `$Luzifer`8. Weil du nur gutes wolltest, behälst du dein Gold und bekommst 2 extra Folterrunden.');
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gravefights']+=2;
        addnews("`8".$session['user']['name'].'`8 brachte `$Luzifer`8 sein Hündchen wieder!');
        $session['user']['specialinc']='';
        addnav('Zu den News','news.php');
        break;
    case '2':
        output('`8Du ignorierst die Angst vor der Tollwut und näherst dich selbstsicher dem Hund. Du willst gerade über den Rücken des Tieres streichen, als dir etwas merkwürdig vorkommt. Jetzt erst erkennst du das der Hund nicht einen, nicht zwei sondern drei Köpfe hat. Als der Hund sich mit seinen Köpfen zu dir umdreht, fällt dir auch mit erschrecken der Name des Tieres wieder ein `$ZERBERUS`8, der Höllenhund. Einer der Köpfe wendet sich dir zu und spricht:"`$ Hey, wir sind ausgerissen, bitte verrat uns nicht. Ich sag dir was, du hälst dein eines Mäulchen und wir setzen, wenn wir wieder zu hause sind, deine Gefallen ein bisschen hoch!`4" `8 Du bist sprachlos und nickst einfach nur. Zerberus trollt sich weiter und du drehst dich wie in Zeitlupe um und gehst zurück woher du kamst. `n`nDu hast 25 Gefallen erhalten.');
        $session['user']['deathpower']+=25;
        $session['user']['specialinc']='';
        addnews("`8".$session['user']['name'].'`8 hört die Tiere sprechen!`0');
        break;

    case '3':
        output('`8Du ignorierst die Angst vor der Tollwut und näherst dich selbstsicher dem Hund. Du willst gerade über den Rücken des Tieres streichen, als dir etwas merkwürdig vorkommt. Jetzt erst erkennst du das der Hund nicht einen, nicht zwei sondern drei Köpfe hat. Als der Hund sich mit seinen Köpfen zu dir umdreht, fällt dir auch mit erschrecken der Name des Tieres wieder ein `$ZERBERUS`8, der Höllenhund. Vor Entsetzen erstarrst du. Zerberus dreht sich langsam zu dir um und knurrt dabei, dass das Blut in deinen Adern gefriert. Zähnefletschend setzt er zum Sprung an und sieht dich wohl schon als einen schmackhaften Imbiss in seinem Magen verschwinden. Wie durch ein Wunder erinnerst du dich an das , was du einst gelernt hast und weichst ihm geschickt aus und spurtest direkt zurück ins Dorf.`n`n');
        $exp=$session['user']['experience']0.05;
        output("Dieses Erlebnis hat dir `@".$exp."`8 Erfahrungspunkte eingebracht. Setz dich aber ");
        output('lieber erst mal auf eine Bank auf dem Dorfplatz.');
        $session['user']['experience']+=$exp;
        $session['user']['specialinc']='';
        addnews("`8".$session['user']['name'].'`8 spurtete durch den Wald und erzählt auf dem Dorfplatz Hundegeschichten.`0');
        break;
    }
}else if ($_GET['op']=='leave'){
    output('`8Die Angst vor der Tollwut ergreift dein Herz, du lässt den Knochen fallen und hüpfst so schnell du kannst zurück auf den Weg. Vielleicht wäre mal wieder eine Impfung fällig.`0');
    $session['user']['specialinc']='';
}else{

    output('`8Während du fröhlich durch den Wald hüpfst , siehst du im Augenwinkel einen Hund durch das Dickicht streifen. Dein mit tierliebe gefülltes Herz schlägt sofort Luftsprünge und du willst das arme Tier zurück zu seinem Herrchen bringen. Sofort nimmst du die Verfolgung auf und näherst dich Schritt für Schritt dem Hund.  Unterwegs findest du wie zufällig einen Hundeknochen, den du dem armen Hund geben willst. Als du nah genug dran bist, überkommen dich Zweifel, ob nicht vielleicht doch die Tollwut hier im Wald herrrscht und du nicht lieber umkehren solltest.`0');
    addnav('Das Hündchen füttern','forest.php?op=feed');
    addnav('Umkehren','forest.php?op=leave');
    $session['user']['specialinc']='zerberus.php';

}
?>