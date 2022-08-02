<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Golgas Schmiede");

output("`v`c`bGolgas Schmiede`b`c`& `7Heiße Luft schlägt dir entgegen als du dir rostige, alt Tür zu Golgas Schmiede aufdrückst und die Flammen nur so in den Öfen hin und hertanzen. `n
Schlagen von Hämmern auf rot glühendem Metall erklingt in deinen Ohren und das Brodeln des kalten Wassers, worin die Werkstücke gekühlt werden. `n
Zwar roch es teilweise modrig aber die Schmiede war fein säuberlich in drei Abschnitte unterteilt: `n
Im ersten arbeiten die normalen Schmiede, im zweiten Kunstschmiede und im dritten Abschnitt werden Räder hergestellt. `n
`n");
addnav("Waffen");
addnav("`q`c12000 Gold`n `8Schwert der Hoffnung`c","gschmiede.php?op=w1");
addnav("`q`c14000 Gold`n `8Schwert des Donners`c","gschmiede.php?op=w2");
addnav("`q`c16000 Gold`n `8Schwert des Feuers`c","gschmiede.php?op=w3");
addnav("`q`c18000 Gold`n `8Schwert des Eises`c","gschmiede.php?op=w4");
addnav("Rüstungen");
addnav("`q`c12000 Gold`n `8Rüstung der Hoffnung`c","gschmiede.php?op=a1");
addnav("`q`c14000 Gold`n `8Rüstung des Donners`c","gschmiede.php?op=a2");
addnav("`q`c16000 Gold`n `8Rüstung des Feuers`c","gschmiede.php?op=a3");
addnav("`q`c18000 Gold`n `8Rüstung des Eises`c","gschmiede.php?op=a4");
if($session['user']['jobid'] ==1){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","gschmiede.php?op=da");
addnav("Kündigen","gschmiede.php?op=go");
addnav("Stehlen","gschmiede.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==6){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","gschmiede.php?op=da");
addnav("Kündigen","gschmiede.php?op=go");
addnav("Stehlen","gschmiede.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==16){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","gschmiede.php?op=dazwei");
addnav("Kündigen","gschmiede.php?op=go");
addnav("Stehlen","gschmiede.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if ($_GET['op']=="da"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("Stickige und heiße Luft droht dir den Atem zu rauben als dich plötzlich ein kleiner, stämmiger Mann anspricht:  `n
`&„Donner und Doria ihr seid spät dran. Die Arbeit wartet nicht! Ich hoffe doch ich muss euch nicht noch zu eurem Arbeitsplatz geleiten oder?“  `n
`7 Erschrocken schüttelst du bedächtig den Kopf und drängst dich vorsichtig an ihm vorbei in den jeweiligen Teil der Schmiede in dem du dein Werk verrichten musstest.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7Brummelnd schaut dich der kleine, stämmige Mann an und meint nur: `&„Los, los steh hier nicht so faul herum! Ich brauche dich heute für 2 Stunden. Dein Lohn soll dafür allerdings auch gut ausfallen und du bekommst ganze 1000 Gold von mir. Aber nun beweg dich!“`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['jobda']+=1;

break;

case '2':
output("`7Mit den Händen in die Hüften gestemmt erwartet dich der kleine, stämmige Mann bereits: `& „Wo warst du so lange? Die Kunden warten nicht gerne! Ich brauche dich heute für 3 Stunden. Der Lohn von 1500 Gold sollte dafür wohl angemessen sein. Und nun los!“`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;
case '3':
output("`7Grummelnd wirst du bereits an der Tür von deinem Chef empfangen, der dich gleich mit grummelnder Stimme anspricht: `&„Da bist du ja endlich! Die 4 Stunden arbeiten sich nicht von alleine ab! Deinen Lohn von 2000 Gold erhältst du erst, wenn du fertig bist. Und nun fang an!“`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;
case '4':
output("`7Mit bereits mulmigem Gefühl betrittst du die Schmiede. Irgendwo würde er wohl wieder auf dich lauern und ehe du dich versiehst, steht er schon hinter dir. `&“Das wurde auch langsam Zeit, dass du kommst! Heute wird 5 Stunden lang gearbeitet zum Lohn von insgesamt 2500 Gold. Und nun mach dich an die Arbeit!“`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=2500;
$session['user']['jobda']+=1;

break;
case '5':
output("`7Zornig erwartet dich der kleine Mann mit den breiten Schultern und starrt dich an, noch bevor du die ersten Schritte zur Tür herein tratst. `&“Ich hoffe doch, dass du heute genügend geschlafen hast. Ganze 6 Stunden Arbeit wirst du heute haben und keine Minute weniger!“ `7schreit er dich an. `&“3000 Gold werden dir dafür wohl Genüge tun. Aber nur, wenn du sofort deinen Hintern bewegst!“`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
}

if ($_GET['op']=="ste"){
if(($session['user']['turns'] <=2)||($session['user']['dieb'] <=1)){
switch(e_rand(1,12)){

       case '1':
output("`7Du wirst zu Meister Golga gerufen der mit dir über die Qualität deiner Arbeit reden will. Gerade als er zum Sprechen ansetzt wird er von einem anderen Gehilfen angesprochen und dreht sich zu diesem um.`n Du siehst du vor dir auf dem Tisch einen Goldbeutel liegen. Du wirst ihn wohl beim Zählen seiner Einnahmen gestört haben.`n Kurz siehst du dich um und wirfst noch einen zweifelnden Blick auf den Meister bevor du dir den Beutel greifst und ihn in deinem Umhang verbirgst.`n Golga dreht sich dir gemächlich wieder zu, wegen seines Leibesumfanges kann er nicht auf den Tisch sehen um zu kontrollieren ob sein Gold noch da ist.`n Er sagt dir, dass die Qualität deiner Arbeit mittelmäßig ist und du dich steigern solltest.`n Du nickst unterwürfig und versprichst dich zu bessern. Als er dich daraufhin mit einem Nicken entlässt, wendest du dich ab und öffnest hinter der nächsten Ecke den Beutel. Du findest 5.000 Goldstücke darin.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`7Du arbeitest wie immer fleißig an deinem Tisch als neben dir Meister Golga versucht seinen Mantel anzuziehen. Dies war etwas umständlich aufgrund seiner Leibesfülle und kurz bevor er es geschafft hatte auch den 2. Arm in den Ärmel zu stecken hörst du ein leises Klirren.`n Der Meister nickt dir schwer atmend zu und verlässt die Schmiede. Du suchst mit den Augen den Boden ab und siehst etwas blitzen.`nAm Boden liegt ein Beutel der sich im Fall geöffnet hat und aus dem Goldstücke hervorblitzen.Dieser muss dem Meister aus der Tasche gefallen sein.Du hebst ihn auf und zählst 3000 Goldstücke`n.Eigentlich viel zu schade ihm diese wiederzugeben denkst du und lässt den Beutel heimlich in deiner Tasche verschwinden. `n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("`7Meister Golga und seine rechte Hand gehen gemächlichen Schrittes durch die Schmiede. Sie diskutieren über die Arbeitseinstellung der Gehilfen und sehen einige von ihnen strafend an. Als sie an deinem Arbeitstisch vorbeigehen, sind sie in einen heftigen Streit verwickelt.`n Der Meister stellt eine Schatulle die er eben noch in den Händen hielt auf der Kante deines Tisches ab um wild mit den Händen zu gestikulieren. Dann ruft ein Gehilfe aus der gegenüberliegenden Ecke der Schmiede nach dem Meister. Dieser setzt sich mit seinem Gesprächspartner in Bewegung und vergisst die Schatulle.`n Du hebst sie hoch und willst schon dem Meister nacheilen um ihm sein Eigentum zurückzugeben als du zögerst. Eigentlich war dieser doch reich genug und vielleicht war es Schicksal, dass die Schatulle genau auf deinem Tisch gelandet ist. Ein verschlagenes Grinsen huscht über dein Gesicht als du die Schatulle schnell unter dem Tisch verschwinden lässt und sie dort öffnest.`n Du findest 6 Edelsteine die du klammheimlich in deiner Tasche verstaust während dich umsiehst ob dich jemand beobachtet hat.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`7Meister Golga hat alle Gehilfen am Feuer zusammengerufen um ihnen eine neue Schmiedetechnik zu erläutern. Jeder will besonders eifrig wirken und drängt sich näher an die feuerstelle. Schließlich rempelt jemand den Meister so stark an dass dieser mit einem Arm dem Feuer gefährlich nahe kommt.`n Sein Mantel fängt Feuer und du springst geistesgegenwärtig vor um ihm diesen auszuziehen. Es entsteht ein wildes Gerangel da auch die anderen ihrem Meister zu Hilfe eilen.`n Du wirst brutal zur Seite gedrängt mit dem schwelenden Mantel im Arm. Als du diesen in einen Wasserbottich sinken lässt um ihn zu löschen bemerkst du etwas Schweres in der linken Seitentasche. Du siehst dich um und förderst eine kleine Schatulle zutage.`n In ihrem Inneren findest du 4 Edelsteine. Du wirfst einen Blick zu Meister Golga, der sich nach diesem Schock auf einen Stuhl sinken ließ und nun von seinen Gehilfen umringt den Schwerverletzten spielt.`n Er sieht nicht so aus als hätte er jetzt Verwendung dafür also beschließt du sie für ihn aufzubewahren.
`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`7Du wirst von Meister Golga zur Lohnauszahlung gerufen. Gerade als er dir vor sich auf dem Tisch deine Goldstücke abzählt wird er von einem Gehilfen zu sich gerufen und entfernt sich einige Schritte.`n Vor dir auf dem Tisch steht immer noch die große und vor allem geöffnete Goldkassette und die Goldstücke lächeln dich förmlich an. Du kannst nicht widerstehen, siehst dich kurz um und greifst dann blitzschnell mit einen Hand hinein.`n Dies schaffst du einige Male unbemerkt bis du Schritte hinter dir hörst, als du gerade wieder die Hand ausstreckst. Schnell ziehst du diese zurück und siehst betreten nach unten.`n Meister Golga sieht dich abschätzend an, schüttelt dann den Kopf und gibt dir deinen Lohn. Du bedankst dich und kehrst zurück an deinen Arbeitstisch.`n In deinen Taschen zählst du 2000 Goldstücke.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`7Wieder einmal arbeitest du schweißtriefend an deinem Tisch als sich Meister Golga neben dich stelllt und dir beim Arbeiten über die Schulter sieht. Er gibt einige missbilligende Geräusche von sich und versucht dir zu erklären wie du deine Arbeit verbessern könntest.`n Schließlich gibt er mit einem entnervten Seufzen auf, zieht sich seinen Mantel aus, krempelt die Ärmel seines Hemdes hoch, schiebt dich einfach zur Seite und macht sich selbst ans Werk. Du kannst ihm nur bewundernd zusehen während du dich mit einer Hand auf seinem Mantel abstützt.`n Deine Finger spielen etwas mit den Knöpfen des Mantels und ihre Form kommt dir nur zu bekannt vor. Du riskierst einen Blick darauf und siehst dass es Edelsteine sind. Schnell machst du dich daran die Knoten zu öffnen mit denen sie befestigt sind und lässt sie vorsichtig in deiner Tasche verschwinden.`n Endlich tritt der Meister zufrieden zurück, nickt dir zu, nimmt seinen Mantel und geht weiter. Du atmest erleichtert auf und befühlst glücklich die 2 Edelsteine in deiner Tasche bevor du dich wieder an die Arbeit machst.
`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`7Du sollst 6 Schwerter zur Abnahme zu Meister Golga bringen. Es kommt dir vor als müsstest du ewig an seinem Tisch stehen und auf ihn warten und schließlich wird es dir zuviel und du legst die schweren Schwerter einfach auf den Tisch. Von diesem Gewicht befreit atmest du auf und sieht dich etwas am Arbeitsplatz des Meisters um.`n Du entdeckst eine kleine Kiste Gold auf dem Tisch. Verstohlen blickst du dich um. Niemand scheint dich zu beachten. Du näherst dich der Kiste langsam und streckst die Hand aus um sie zu öffnen. Sie ist nicht verschlossen also kannst du ihren Deckel leicht aufklappen.`n Du machst dies jedoch mit zuviel Schwung und der Inhalt der Kiste verteilt sich mit ohrenbetäubendem Lärm auf dem gesamten Tisch. Ertappt siehst du dich um und begegnest dem flammenden Blick des vor Zorn bebenden Meister Golgas.`n Er packt dich am Kragen und zerrt dich zur Tür. Du fliegst in hohem Bogen auf die Straße und er brüllt dir hinterher: „ Diebesgesindel können wir hier nicht brauchen. Lass dich hier nie wieder blicken!“

`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
break;
       case '8':
output("`7Meister Golga schickt dich in die kleine Schatzkammer um ihm 2000 Goldstücke zu holen die er benötigt um einen Händler zu bezahlen. Eigentlich ist das die Aufgabe des Zahlmeisters doch der ist gerade unauffindbar. Du begibst dich also in die kleine, fensterlose Kammer in der 3 riesige Goldkisten stehen.`n Du zählst gewissenhaft die Goldstücke ab und willst die Kisten dann abschließen. Du bemerkst jedoch dass soviel Gold in den Kisten ist dass diese nicht zugehen. Du denkst kurz nach und entscheidest dann dir soviel Gold zu nehmen dass du sie schließen kannst.`n Gerade ist deine Hand mit Goldstücken beladen auf dem Weg in Richtung deiner Tasche da legt sich dir plötzlich eine zentnerschwere Hand auf die Schulter. Du siehst dich um und erblickst einen alten Mann der doch drohend ansieht:`& „ Was tust du denn da mein Junge? Ich bin Georgie der Zahlmeister und nur ich darf dieses Gold berühren!“ `7`nDu legst das Gold vorsichtig wieder in die Kiste und sagst ihm dass Meister Golga ihm aufgetragen hatte 2000 Goldstücke zu holen.`n Georgie wirft einen Blick auf den Goldbeutel in deiner Hand und meint versöhnlicher `?„ Nun ja dann will ich dir mal glauben.`7`n Doch dass da in dem Beutel ist schon genug…du hättest nicht noch mehr gebraucht…wenn ich dich noch einmal in der Nähe dieses Goldes sehe lasse ich dich rauswerfen!`n`& Und nun geh!“ `7Du machst dich ganz klein und verlässt schnell die Kammer.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
break;
       case '9':
output("`7Du sollst 6 Schwerter zur Abnahme zu Meister Golga bringen. Es kommt dir vor als müsstest du ewig an seinem Tisch stehen und auf ihn warten und schließlich wird es dir zuviel und du legst die schweren Schwerter einfach auf den Tisch. Von diesem Gewicht befreit atmest du auf und sieht dich etwas am Arbeitsplatz des Meisters um.`n Du entdeckst eine kleine Kiste Gold auf dem Tisch. Verstohlen blickst du dich um. Niemand scheint dich zu beachten. Du näherst dich der Kiste langsam und streckst die Hand aus um sie zu öffnen. Sie ist nicht verschlossen also kannst du ihren Deckel leicht aufklappen.`n Du machst dies jedoch mit zuviel Schwung und der Inhalt der Kiste verteilt sich mit ohrenbetäubendem Lärm auf dem gesamten Tisch. Ertappt siehst du dich um und begegnest dem flammenden Blick des vor Zorn bebenden Meister Golgas.`n Er packt dich am Kragen und zerrt dich zur Tür. Du fliegst in hohem Bogen auf die Straße und er brüllt dir hinterher: „ Diebesgesindel können wir hier nicht brauchen. Lass dich hier nie wieder blicken!“

`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
break;
       case '10':
output("`7Meister Golga schickt dich in die kleine Schatzkammer um ihm 2000 Goldstücke zu holen die er benötigt um einen Händler zu bezahlen. Eigentlich ist das die Aufgabe des Zahlmeisters doch der ist gerade unauffindbar. Du begibst dich also in die kleine, fensterlose Kammer in der 3 riesige Goldkisten stehen.`n Du zählst gewissenhaft die Goldstücke ab und willst die Kisten dann abschließen. Du bemerkst jedoch dass soviel Gold in den Kisten ist dass diese nicht zugehen. Du denkst kurz nach und entscheidest dann dir soviel Gold zu nehmen dass du sie schließen kannst.`n Gerade ist deine Hand mit Goldstücken beladen auf dem Weg in Richtung deiner Tasche da legt sich dir plötzlich eine zentnerschwere Hand auf die Schulter. Du siehst dich um und erblickst einen alten Mann der doch drohend ansieht:`& „ Was tust du denn da mein Junge? Ich bin Georgie der Zahlmeister und nur ich darf dieses Gold berühren!“ `7`nDu legst das Gold vorsichtig wieder in die Kiste und sagst ihm dass Meister Golga ihm aufgetragen hatte 2000 Goldstücke zu holen.`n Georgie wirft einen Blick auf den Goldbeutel in deiner Hand und meint versöhnlicher `?„ Nun ja dann will ich dir mal glauben.`7`n Doch dass da in dem Beutel ist schon genug…du hättest nicht noch mehr gebraucht…wenn ich dich noch einmal in der Nähe dieses Goldes sehe lasse ich dich rauswerfen!`n`& Und nun geh!“ `7Du machst dich ganz klein und verlässt schnell die Kammer.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
break;
       case '11':
output("`7Du sollst 6 Schwerter zur Abnahme zu Meister Golga bringen. Es kommt dir vor als müsstest du ewig an seinem Tisch stehen und auf ihn warten und schließlich wird es dir zuviel und du legst die schweren Schwerter einfach auf den Tisch. Von diesem Gewicht befreit atmest du auf und sieht dich etwas am Arbeitsplatz des Meisters um.`n Du entdeckst eine kleine Kiste Gold auf dem Tisch. Verstohlen blickst du dich um. Niemand scheint dich zu beachten. Du näherst dich der Kiste langsam und streckst die Hand aus um sie zu öffnen. Sie ist nicht verschlossen also kannst du ihren Deckel leicht aufklappen.`n Du machst dies jedoch mit zuviel Schwung und der Inhalt der Kiste verteilt sich mit ohrenbetäubendem Lärm auf dem gesamten Tisch. Ertappt siehst du dich um und begegnest dem flammenden Blick des vor Zorn bebenden Meister Golgas.`n Er packt dich am Kragen und zerrt dich zur Tür. Du fliegst in hohem Bogen auf die Straße und er brüllt dir hinterher: „ Diebesgesindel können wir hier nicht brauchen. Lass dich hier nie wieder blicken!“

`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
break;
       case '12':
output("`7Meister Golga schickt dich in die kleine Schatzkammer um ihm 2000 Goldstücke zu holen die er benötigt um einen Händler zu bezahlen. Eigentlich ist das die Aufgabe des Zahlmeisters doch der ist gerade unauffindbar. Du begibst dich also in die kleine, fensterlose Kammer in der 3 riesige Goldkisten stehen.`n Du zählst gewissenhaft die Goldstücke ab und willst die Kisten dann abschließen. Du bemerkst jedoch dass soviel Gold in den Kisten ist dass diese nicht zugehen. Du denkst kurz nach und entscheidest dann dir soviel Gold zu nehmen dass du sie schließen kannst.`n Gerade ist deine Hand mit Goldstücken beladen auf dem Weg in Richtung deiner Tasche da legt sich dir plötzlich eine zentnerschwere Hand auf die Schulter. Du siehst dich um und erblickst einen alten Mann der doch drohend ansieht:`& „ Was tust du denn da mein Junge? Ich bin Georgie der Zahlmeister und nur ich darf dieses Gold berühren!“ `7`nDu legst das Gold vorsichtig wieder in die Kiste und sagst ihm dass Meister Golga ihm aufgetragen hatte 2000 Goldstücke zu holen.`n Georgie wirft einen Blick auf den Goldbeutel in deiner Hand und meint versöhnlicher `?„ Nun ja dann will ich dir mal glauben.`7`n Doch dass da in dem Beutel ist schon genug…du hättest nicht noch mehr gebraucht…wenn ich dich noch einmal in der Nähe dieses Goldes sehe lasse ich dich rauswerfen!`n`& Und nun geh!“ `7Du machst dich ganz klein und verlässt schnell die Kammer.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
break;
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`7Mit leisem Seufzen betrittst du die Schmiede und hältst Ausschau nach diesem kleinen, nervigen Etwas, welcher dein Chef war. `n
Noch ehe du Zeit hattest einmal tief durchzuatmen, stand er schon hinter dir und schaute dich mit Zornesfalten im Gesicht an, so als wüsste er bereits, was du wolltest. `n
Du fühltest, wie sich deine Kehle unter seinen Blicken mehr und mehr zusammenschnürte und nur mit Mühe brachtest du heraus, dass du kündigen willst. `n
Die Brauen des Mannes verschmolzen beinahe zu einem einzigen Strich, so fest zog er sie zusammen und packte dich ohne Vorwarnung am Kragen. `n
Noch ehe du ein Widerwort sprechen konntest, landest du bereits vor der Schmiede im Dreck. `n
`&“Komm mir bloß nie wieder unter die Augen, sonst wirst du deines Lebens nicht mehr froh!“ `7 schrie er und du siehst zu, dass du so schnell wie möglich Land gewinnst.`n");
$session['user']['jobid']=0;
}

if ($_GET['op']=="dazwei"){
if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){
output("Stickige und heiße Luft droht dir den Atem zu rauben als dich plötzlich ein kleiner, stämmiger Mann anspricht:  `n
`&„Donner und Doria ihr seid spät dran. Die Arbeit wartet nicht! Ich hoffe doch ich muss euch nicht noch zu eurem Arbeitsplatz geleiten oder?“  `n
`7 Erschrocken schüttelst du bedächtig den Kopf und drängst dich vorsichtig an ihm vorbei in den jeweiligen Teil der Schmiede in dem du dein Werk verrichten musstest.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7Brummelnd schaut dich der kleine, stämmige Mann an und meint nur: `&„Los, los steh hier nicht so faul herum! Ich brauche dich heute für 2 Stunden. Dein Lohn soll dafür allerdings auch gut ausfallen und du bekommst ganze 1500 Gold von mir. Aber nun beweg dich!“
`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;

case '2':
output("`7Mit den Händen in die Hüften gestemmt erwartet dich der kleine, stämmige Mann bereits: `& „Wo warst du so lange? Die Kunden warten nicht gerne! Ich brauche dich heute für 3 Stunden. Der Lohn von 2250 Gold sollte dafür wohl angemessen sein. Und nun los!“`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=2250;
$session['user']['jobda']+=1;

break;
case '3':
output("`7Grummelnd wirst du bereits an der Tür von deinem Chef empfangen, der dich gleich mit grummelnder Stimme anspricht: `&„Da bist du ja endlich! Die 4 Stunden arbeiten sich nicht von alleine ab! Deinen Lohn von 3000 Gold erhältst du erst, wenn du fertig bist. Und nun fang an!“`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '4':
output("`7Mit bereits mulmigem Gefühl betrittst du die Schmiede. Irgendwo würde er wohl wieder auf dich lauern und ehe du dich versiehst, steht er schon hinter dir. `&“Das wurde auch langsam Zeit, dass du kommst! Heute wird 5 Stunden lang gearbeitet zum Lohn von insgesamt 3750 Gold. Und nun mach dich an die Arbeit!“`n");
$session['user']['turns']-=5;
$session['user']['gold']+=3750;
$session['user']['jobda']+=1;

break;
case '5':
output("`7Zornig erwartet dich der kleine Mann mit den breiten Schultern und starrt dich an, noch bevor du die ersten Schritte zur Tür herein tratst. `&“Ich hoffe doch, dass du heute genügend geschlafen hast. Ganze 6 Stunden Arbeit wirst du heute haben und keine Minute weniger!“ `7schreit er dich an. `&“4500 Gold werden dir dafür wohl Genüge tun. Aber nur, wenn du sofort deinen Hintern bewegst!“`n");
$session['user']['turns']-=6;
$session['user']['gold']+=4500;
$session['user']['jobda']+=1;

break;
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
}
// Waffen kauf beginn
if($_GET['op']=="w1")
{
    $cost=12000;
    if ($session['user']['gold']>=$cost)
    {
        output("Du wählst das Schwert der Hoffnung aus, ein Schwert mit einer sanften Klinge. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe Schwert der Hoffnung");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tSchwert der Hoffnung','Waffe','".$session[user][acctid]."','16','12000','Ein Schwert mit der Kraft der Hoffnung.')";
        db_query($sql);
        $session['user']['gold']-=$cost;;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
if($_GET['op']=="w2")
{
    $cost=14000;
    if ($session['user']['gold']>=$cost)
    {
        output("`3Du wählst das `7Schwert des Donners `3aus, ein Schwert mit einer Klinge doppelt so breit wie normal. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe `7Schwert des Donners");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`7Schwert des Donners','Waffe','".$session[user][acctid]."','17','14000','Ein Schwert mit der Kraft des Donners.')";
        db_query($sql);
        $session['user']['gold']-=$cost;;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
if($_GET['op']=="w3")
{
    $cost=16000;
    if ($session['user']['gold']>=$cost)
    {
        output("`&Du wählst das `4Schwert des Feuers `&aus, ein Schwert mit einer Klinge die den anschein hat das sie noch rot Glüht. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe `4Schwert des Feuers");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`7Schwert des Feuers','Waffe','".$session[user][acctid]."','18','16000','Ein Schwert mit der Kraft des Donners.')";
        db_query($sql);
        $session['user']['gold']-=$cost;;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
if($_GET['op']=="w4")
{
    $cost=18000;
    if ($session['user']['gold']>=$cost)
    {
        output("`8Du wählst das `&Schwert des Eises `8aus, ein Schwert mit einer Klinge die den anschein hat das sie aus Eis besteht. Als du das Schwert packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Waffe `4Schwert des Eises");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`&Schwert des Eises','Waffe','".$session[user][acctid]."','19','18000','Ein Schwert mit der Kraft des Eises.')";
        db_query($sql);
        $session['user']['gold']-=$cost;;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Waffen sind ja nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
// Waffen kauf ende

// Rüstung kauf beginn
if($_GET['op']=="a1")
{
    $cost=12000;
    if ($session['user']['gold']>=$cost)
    {
        output("`8Du wählst die `tRüstung der Hoffnung`8 aus, eine Rüstung, welche eine schützende Aura ausstrahlt. Als du die Rüstung packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der Rüstung der Hoffnung");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tRüstung der Hoffnung','Rüstung','".$session[user][acctid]."','16','12000','Rüstung der Hoffnung.')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=15000;
        $session['user']['gold']-=$cost;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Rüstungen sind nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
if($_GET['op']=="a2")
{
    $cost=14000;
    if ($session['user']['gold']>=$cost)
    {
        output("`8Du wählst die `7Rüstung des Donners `8aus, eine Rüstung, welche eine schützende Aura ausstrahlt. Als du die Rüstung packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der `7Rüstung der Hoffnung");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`7Rüstung des Donners','Rüstung','".$session[user][acctid]."','17','14000','Rüstung des Donners.')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=15000;
        $session['user']['gold']-=$cost;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Rüstungen sind nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
if($_GET['op']=="a3")
{
    $cost=16000;
    if ($session['user']['gold']>=$cost)
    {
        output("`8Du wählst die `4Rüstung des Feuers`8 aus, eine Rüstung, welche eine rote schützende Aura ausstrahlt. Als du die Rüstung packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der `4Rüstung der Hoffnung");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`4Rüstung des Feuers','Rüstung','".$session[user][acctid]."','18','16000','Rüstung des Feuers.')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=15000;
        $session['user']['gold']-=$cost;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Rüstungen sind nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
if($_GET['op']=="a4")
{
    $cost=18000;
    if ($session['user']['gold']>=$cost)
    {
        output("`8Du wählst die `&Rüstung des Eises`8 aus, eine Rüstung, welche eine weiße schützende Aura ausstrahlt. Als du die Rüstung packst, strömt Energie durch");
        output("deinen Körper. Du bist nun stolzer Besitzer der `&Rüstung der Hoffnung");
        $sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`&Rüstung des Feuers','Rüstung','".$session[user][acctid]."','19','18000','Rüstung des Feuers.')";
        db_query($sql);
        //$session['user']['gold']+=$tradeinvalue-=15000;
        $session['user']['gold']-=$cost;
    }
    else
    {
        output("`8Golga sieht dich an und fragt dich mit ärgerlicher Stimme, ob du ihn veräppeln willst.");
        output("`&Meine Rüstungen sind nicht gratis. Verschwinde von hier und komm erst wieder, wenn du genug Geld hast!`8 schreit er dich an und schmeist dich aus seiner Schmiede");
    }
}
// Rüstung kauf Ende
addnav("zurück zum Dorf","village.php");
page_footer();
?>