<?php

//Idee von Zinsis
//Umsetzung von Trillian
//modified by Morpheus for www.Morpheus-lotgd.de

if (!isset($session)) exit();

output("`b`cDas Hündchen`c`b`n`n");
if ($HTTP_GET_VARS[op]=="feed"){
    switch(e_rand(1,3)){
    case 1:
        output("`8Du ignorierst die Angst vor der Tollwut und näherst dich selbstsicher dem ");
        output("Hund. Du willst gerade über den Rücken des Tieres streichen, als dir etwas ");
        output("merkwürdig vorkommt. Jetzt erst erkennst du das der Hund nicht einen, ");
        output("nicht zwei sondern drei Köpfe hat. Als der Hund sich mit seinen Köpfen zu ");
        output("dir umdreht, fällt dir auch mit erschrecken der Name des Tieres wieder ein ");
        output("`\$ZERBERUS`8, der Höllenhund. Nun hälst du ihm mit etwas zittrigerer Hand den ");
        output("Knochen hin und ärgerst dich, das du nur einen und nicht drei hast. Nun ja, ");
        output("du wolltest das Hündchen zu seinem Herrchen bringen und wer anderes als ");
        output("`\$Ramius`8 könnte das Herrchen von Zerberus sein ? `n");
    $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schmuck' AND name='Seltsamer Goldring'";
    $result = db_query($sql);
    if (db_num_rows($result)==0){
            output("Du machst dich auf den Weg zu `\$Ramius`8. Weil du nur gutes wolltest, behälst ");
            output("du dein Gold und bekommst 2 extra Folterrunden.");
            $session[user][alive]=0;
            $session[user][hitpoints]=0;
            $session[user][gravefights]+=2;
            addnews("`8".$session[user][name]."`8 brachte `\$Ramius`8 sein Hündchen wieder!`0");
            $session[user][specialinc]="";
            addnav("Zu den News","news.php");
    }else{
            output("Du machst dich auf den Weg zu `\$Ramius`8, und als dieser sieht, daß Du nicht nur `\$ZERBERUS`8 sondern auch seinen geliebten `^Goldring `8gefunden hast, den er just, auf der Suche nach `\$ZERBERUS`8 verloren hat, ist er so erfreut, daß er Dir wieder Leben einhaucht und Dich gehen läßt!`n");
            output("Durch die ganze Aktion gewinnst Du an Erfahrung!");
            addnews("`8".$session[user][name]."`8 brachte `\$Ramius`8 sein Hündchen und mehr wieder!`0");
            $session[user][experience]*=1.1;
        $SQL = "DELETE FROM items WHERE owner=".$session['user']['acctid']." AND name='Seltsamer Goldring'";
        db_query($sql);
    }    
        break;

    case 2:
        output("`8Du ignorierst die Angst vor der Tollwut und näherst dich selbstsicher dem ");
        output("Hund. Du willst gerade über den Rücken des Tieres streichen, als dir etwas ");
        output("merkwürdig vorkommt. Jetzt erst erkennst du das der Hund nicht einen, ");
        output("nicht zwei sondern drei Köpfe hat. Als der Hund sich mit seinen Köpfen zu ");
        output("dir umdreht, fällt dir auch mit erschrecken der Name des Tieres wieder ein ");
        output("`\$ZERBERUS`8, der Höllenhund. Einer der Köpfe wendet sich dir zu und spricht :`4“`$ ");
        output("Hey, wir sind ausgerissen, bitte verrat uns nicht. Ich sag dir was, du ");
        output("hälst dein eines Mäulchen und wir setzen, wenn wir wieder zu hause sind, ");
        output("deine Gefallen ein bisschen hoch!`4“`8 Du bist sprachlos und nickst einfach ");
        output("nur. Zerberus trollt sich weiter und du drehst dich wie in Zeitlupe um und ");
        output("gehst zurück woher du kamst. `n`n");
        output("Du hast 25 Gefallen erhalten.");
        $session[user][deathpower]+=25;
        $session[user][specialinc]="";
        addnews("`8".$session[user][name]."`8 hört die Tiere sprechen!`0");
        break;

    case 3:
        output("`8Du ignorierst die Angst vor der Tollwut und näherst dich selbstsicher dem ");
        output("Hund. Du willst gerade über den Rücken des Tieres streichen, als dir etwas ");
        output("merkwürdig vorkommt. Jetzt erst erkennst du das der Hund nicht einen, ");
        output("nicht zwei sondern drei Köpfe hat. Als der Hund sich mit seinen Köpfen zu ");
        output("dir umdreht, fällt dir auch mit erschrecken der Name des Tieres wieder ein ");
        output("`\$ZERBERUS`8, der Höllenhund. Vor Entsetzen erstarrst du. Zerberus dreht sich ");
        output("langsam zu dir um und knurrt dabei, dass das Blut in deinen Adern gefriert. ");
        output("Zähnefletschend setzt er zum Sprung an und sieht dich wohl schon als einen ");
        output("schmackhaften Imbiss in seinem Magen verschwinden. Wie durch ein Wunder ");
        output("erinnerst du dich an das , was du einst gelernt hast und weichst ihm ");
        output("geschickt aus und spurtest direkt zurück in die Stadt.`n`n");
        $exp=$session[user][experience]*0.05;
        output("Dieses Erlebnis hat dir `@".$exp."`8 Erfahrungspunkte eingebracht. Setz dich aber ");
        output("lieber erst mal auf eine Bank auf dem Stadtplatz.");
        $session[user][experience]+=$exp;
        $session[user][specialinc]="";
        addnews("`8".$session[user][name]."`8 spurtete durch den Wald und erzählt auf dem Stadtplatz Hundegeschichten.`0");
        break;
    }
}else if ($HTTP_GET_VARS[op]=="leave"){
    output("`8Die Angst vor der Tollwut ergreift dein Herz, du lässt den Knochen fallen und hüpfst so schnell du kannst zurück auf den Weg. Vielleicht wäre mal wieder eine Impfung fällig.`0");
    $session[user][specialinc]="";
}else{

    output("`8Während du fröhlich durch den Wald hüpfst , siehst du im Augenwinkel einen Hund durch ");
    output("das Dickicht streifen. Dein mit tierliebe gefülltes Herz schlägt sofort Luftsprünge und du");
    output("willst das arme Tier zurück zu seinem Herrchen bringen. Sofort nimmst du die Verfolgung auf und näherst dich Schritt für ");
    output("Schritt dem Hund.  Unterwegs findest du wie zufällig einen Hundeknochen, ");
    output("den du dem armen Hund geben willst. Als du nah genug dran bist, überkommen ");
    output("dich Zweifel, ob nicht vielleicht doch die Tollwut hier im Wald herrrscht und du nicht lieber umkehren solltest.`0");
    addnav("Das Hündchen füttern","forest.php?op=feed");
    addnav("Umkehren","forest.php?op=leave");
    $session[user][specialinc]="zerberus.php";

}
?> 