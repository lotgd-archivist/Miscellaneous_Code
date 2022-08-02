<?php
// Tabellen in accounts schule und schulef , jobid, jobda ,                                 //
require_once "common.php";

page_header("Dorfschule");


output("`v`c`bDorfschule`b`c`&Neugierig steckst du deinen Kopf zum Klassenzimmer herein und schaust dich aufmerksam aber zögerlich um. `n

Im vorderen Bereich befand sich ein kleines, erhöhtes Podest, wo ganz offensichtlich der Lehrer stand, wenn er den Schülern etwas beibringen wollte. `n

Direkt davor standen alte und auch teilweise bereits sehr abgenutzte Holzbänke, die genügend Platz für alle boten, die sich der Tortour des Lernens unterwerfen wollten.`n

Als dein Blick auf einen massiv aussehenden Holzstock fällt bekommst du ein mulmiges Gefühl in der Magengegend und hoffst, dass der wirklich nur als Zeigestock benutzt wird und nicht zu einem anderen Zwecke da ist.`n

Eigentlich sollte es ja bald auch mit deinem Unterricht losgehen als du plötzlich Schritte hinter dir vernehmen konntest und ehe du dich versahst, tippte dir jemand auf die Schulter.`n

Erschrocken wendest du dich herum und schaust unmittelbar in ein faltiges, fahles Gesicht. „Seid mir gegrüßt Fremder“ murmelte der alte Mann in seinen Bart hinein. „Ihr seid also der Neue… sagt mir bitte welche Klassenstufe ihr bestreiten wollt“.`n `n



`c
1. Klasse: 10000 Gold +   2 Waldkämpfe`n

2. Klasse: 20000 Gold +   4 Waldkämpfe`n

3. Klasse: 30000 Gold +   6 Waldkämpfe`n

4. Klasse: 40000 Gold +   8 Waldkämpfe`n

5. Klasse: 50000 Gold + 10 Waldkämpfe`n

6. Klasse: 60000 Gold + 12 Waldkämpfe`n

`c
");
if($session['user']['jobid'] ==19){
if($session['user']['turns'] >=6){


}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if($session['user']['schule'] ==0){                                                                                  addnav("1.Klasse","schule.php?op=eins");

}elseif ($session['user']['schule'] ==1){
addnav("2.Klasse","schule.php?op=zwei");

}elseif ($session['user']['schule'] ==2){
addnav("3.Klasse","schule.php?op=drei");

}elseif ($session['user']['schule'] ==3){
addnav("4.Klasse","schule.php?op=vier");

}elseif ($session['user']['schule'] ==4){
addnav("5.Klasse","schule.php?op=fuenf");

}elseif ($session['user']['schule'] ==5){
addnav("6.Klasse","schule.php?op=sechs");

}elseif ($session['user']['schule'] ==6){
output("`9`n`n Du hast schon alle 6 Klassen bestanden `n");
}
if ($_GET['op']=="eins"){
if($session['user']['gold'] >=10000){
if($session['user']['turns'] >=2){
output("`9`n`n Etwas eingeschüchtert von seiner strengen Erscheinung überreichst du ihm 10000 Gold und setzt dich für 2 Runden auf eine der Bänke um seinen Erzählungen zu lauschen und anschließend die Prüfung fehlerlos zu meistern.`n
Du darfst nun eine Klasse höher steigen.`n");
$session['user']['turns']-=2;
$session['user']['gold']-=10000;
$session['user']['schule']+=1;
}
}elseif ($session['user']['turns'] <=2){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe über hast `n");
}elseif ($session['user']['gold'] <=9999){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Gold bei dir hast `n");
}
}
if ($_GET['op']=="zwei"){
if($session['user']['gold'] >=20000){
if($session['user']['turns'] >=4){
output("`9`n`n Du überreichst dem Lehrer die 20000 Gold, die für die 2. Klasse nötig sind und begibst dich für 4 Runden in den Unterricht.`n
Völlig problemlos schaffst du den Sprung in die 3. Klasse.`n");
$session['user']['turns']-=4;
$session['user']['gold']-=20000;
$session['user']['schule']+=1;
}
}elseif ($session['user']['turns'] <=3){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe über hast `n");
}elseif ($session['user']['gold'] <=19999){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Gold bei dir hast `n");

}
}
if ($_GET['op']=="drei"){
if($session['user']['gold'] >=30000){
if($session['user']['turns'] >=6){
output("`9`n`n Zuversichtlich drückst du dem Mann die nötigen 30000 Gold in die Hand und lässt für 6 Runden die Vorlesungen über dich ergehen.`n
Ohne große Mühe bestehst du den Test und darfst somit in die nächste Klasse gehen.`n");
$session['user']['turns']-=6;
$session['user']['gold']-=30000;
$session['user']['schule']+=1;
}
}elseif ($session['user']['turns'] <=5){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe über hast `n");
}elseif ($session['user']['gold'] <=29999){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Gold bei dir hast `n");
}
}
if ($_GET['op']=="vier"){
if($session['user']['gold'] >=40000){
if($session['user']['turns'] >=8){
output("`9`n`n Zwar taten dir die 40000 Gold etwas weh, als du sie dem Mann gegeben hattest, doch war alles nur für deine Zukunft.`n
Stillschweigend setzt du dich für 8 Runden mit in die Klasse und bist am Ende sogar gut genug um die Prüfung zu bestehen und die Zulassung für die nächsthöhere Klasse zu erhalten.`n");
$session['user']['turns']-=8;
$session['user']['gold']-=40000;
$session['user']['schule']+=1;
}
}elseif ($session['user']['turns'] <=7){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe über hast `n");
}elseif ($session['user']['gold'] <=39999){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Gold bei dir hast `n");
}
}
if ($_GET['op']=="fuenf"){
if($session['user']['gold'] >=50000){
if($session['user']['turns'] >=10){
output("`9`n`n Lächelnd gibst du deine 50000 Gold in die Hände des Mannes und setzt dich sogleich für 10 Runden auf die knirschenden, alten Bänke.`n
Du hast keine Schwierigkeiten dem Stoff zu folgen und kannst anschließend den Test mit guter Note abschließen um in die nächste Klasse aufsteigen zu dürfen.
`n");
$session['user']['turns']-=10;
$session['user']['gold']-=50000;
$session['user']['schule']+=1;
}
}elseif ($session['user']['turns'] <=9){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe über hast `n");
}elseif ($session['user']['gold'] <=49999){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Gold bei dir hast `n");
}
}
if ($_GET['op']=="sechs"){
if($session['user']['gold'] >=60000){
if($session['user']['turns'] >=12){
output("`9`n`n Mit großem Selbstvertrauen überreichst du dem Lehrer ein Goldsäckchen mit 60000 Gold. Da es bald geschafft war, konntest du auch diese 12 Runden noch überstehen und dem Reden des alten Mannes zuhören.`n
Nachdem das alles mit Bravour überstanden war, überreicht dir der Direktor eine Urkunde und verkündet vor allen, die noch nicht soweit waren, dass du es geschafft und nun endlich einen Beruf ausüben darfst. `n");
$session['user']['turns']-=12;
$session['user']['schule']+=1;
$session['user']['gold']-=60000;
$session['user']['schulef']+=1;
}
}elseif ($session['user']['turns'] <=11){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Waldkämpfe über hast `n");
}elseif ($session['user']['gold'] <=59999){
output("`9`n`n Du kannst dein Schulgeld nicht bezahlen weil du zu wenig Gold bei dir hast `n");
}
}

if ($_GET['op']=="dazwei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`5Nachdem du dem alten Mann trotz seiner einsetzenden Taubheit begreiflich gemacht hast, dass du nichts lernen sondern lieber unterrichten willst, glaubst du dass er lächelt, da sich die Falten um seine Augen vertiefen. Er nickt bedächtig und macht mit einem Arm eine ausholende Geste:`& Ich bin Nolan und dies ist mein Reich und nun auch bald das Eure. Wie ihr seht bin ich nicht mehr der jüngste und es tut meinen alten Knochen nicht gut tagein tagaus vor den Schülern zu stehen. Außerdem siehst du so aus als könntest du dich durchsetzen. Ich werde es also mit dir versuchen. Meine Gratulation du bist hiermit Lehrer.`5 Du bedankst dich lächelnd bei Nolan und lässt dir vom ihm die Sachen zeigen die man für den Unterricht braucht.`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7Nolan sieht dich nun doch etwas zweifelnd an. `& Ich erinnere mich noch gut als ich in deinem Alter war. Ich sollte zum ersten Mal eine Schulklasse allein unterrichten.`7 Er lacht in seinen dichten weißen Bart. `& Was glaubt ihr was ich für Angst hatte. Nun ja vielleicht sollte ich euch erst einmal klein anfangen lassen. Ich überlasse euch meine Schützlinge für 2 Stunden und ihr bekommt 1500 Goldstücke. In Ordnung?`7 Er streckt dir seine Hand entgegen und du ergreifst sie freudestrahlend.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;

case '2':
output("`7Als du Nolan nach Arbeit fragst, fällt dir seine etwas undeutliche Sprechweise auf. Du fragst ihn on alles in Ordnung ist. Er sieht dich missmutig an und meint:`& Nischts ischt in Ordnung. Isch habe geschtern auf ein Schtück Knochen gebischen und nun habe isch fürschterlische Zahnschmerzen.`7 Du wirfst ihm einen mitfühlenden Blick zu und erzählst ihm dass es im Krankenlager einen Zahnausreisser gibt der ihn von diesen Schmerzen befreien kann. Nolan sieht dich an und sagt dann schulterzuckend:`6 Nun schlimmer kann esch kaum werden alscho werde isch dieschen Herrn einmal aufschuchen.`7 Du sagst dass du auf dem Weg hierher eine lange Schlange vor dem Krankenlager bemerkt hast und meinst dass er wohl warten muss. Du schlägst ihm vor seine Klasse für 3 Stunden zu beaufsichtigen damit er sich Zeit lassen kann. Nolan stimmt freudig zu und ruft dir noch über die Schulter zu :`6 Ich danke dir. Ich bin in 3 Stunden zurück und gebe dir 2250 Goldstücke für deine Mühe`7. Du siehst ihm lächelnd hinterher und wappnest dich für den Unterricht.`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=2250;
$session['user']['jobda']+=1;

break;
case 3:
output("`7Als du die Dorfschule durch eine kleine Tür betrittst kommt dir Nolan bereits freudestrahlend entgegen. `&Einen wunderschönen guten Tag wünsche ich dir. Da es ein so wunderschöner Tag ist dachte ich wir machen mit den Schülern einen Ausflug an den See mit Picknick und schwimmen. Ich bin jedoch auf meine alten Tage nicht mehr so belastbar deswegen wird es deine Aufgabe sein mit den Kindern herumzutollen, zu schwimmen und sonstige anstrengende Aktivitäten auszuüben. Ich werde nur ab und zu ein Machtwort sprechen.`7 Er lächelt dich fragend an `& Bist du einverstanden? Es soll dein Schaden nicht sein. Du bekommst von mir 3000 Goldstücke wenn wir wieder wohlbehalten hier angelangt sind.`7
So macht ihr euch auf den Weg und du kannst kaum erwarten dass dieser Ausflug vorbei ist und du dein Gold in den Händen hälst.
`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case 4:
output("`7Du siehst Nolan mit dem Rücken zu dir an einen Schülertisch gelehnt und fragst ihn nach Arbeit. Er dreht sich zu dir um und du trittst erschrocken einen Schritt zurück. Er sah aus wie ein Gespenst. Die Augen tief in den Höhlen und rot umrandet mit tiefschwarzen Augenringen darunter. Die Farbe seines Gesichtes konnte man nur als leichenblass bezeichnen und eine leuchtend rote Nase lugte aus seinem Bart hervor. Der Schweiß stand ihm auf der Stirn und er konnte sich kaum noch auf den Beinen halten. Du führst in zu einem Stuhl und lässt ihn Platz nehmen.`& Oh hab vielen Dank…ich muss mir wohl irgendwo eine Grippe eingefangen haben. Nun gut das du hier bist. Du suchst also Arbeit? Die erste Stunde habe ich mit Mühe und Not überstanden doch nun bin ich am Ende meiner Kräfte. Wenn du mich die nächsten 5 Stunden vertrittst winkt dir eine reiche Belohnung. Ich gebe dir 3750 Goldstücke da du es mir ermöglichst mich nach hause zu begeben und mich auszukurieren.`7 Du hilfst ihm nach Hause da die Schüler Pause haben und nimmst dir vor am Abend noch einmal nach Nolan zu sehen.`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=3750;
$session['user']['jobda']+=1;

break;
case 5:
output("`7Du findest Nolan an seinem Pult stehend den Kopf in die Hände gestützt. Als er dich nach Arbeit fragen hört dreht er sich blitzschnell um und fällt dir um den Hals. `& Oh welche Freude. Ich danke dir für dein Erscheinen. Ich dachte schon ich könnte nicht zur Hochzeit meiner eigenen Tochter gehen. Ich hatte heute schon einen Stellvertreter für mich doch dieser ist leider erkrankt. Nun stand ich hier und wartete auf ein Wunder und jetzt kommst du und willst Arbeit.`7 Sein Gesicht strahlt vor Freude und er beginnt hastig seine Sachen zusammenzupacken.`& Also die Trauung wird wohl um die 6 Stunden dauern. Du wirst die Schüler also den ganzen Tag beschäftigen müssen. Doch ich denke das schaffst du schon. Vorallem wenn ich dir sage dass ich dich reich dafür belohnen werde. Ich gebe dir 4500 Goldstücke dafür. Also streng dich gefälligst an.`7 Mit diesen Worten schließt er die Tür hinter sich und du träumst davon was du alles mit soviel Gold anfangen kannst.`n`n");
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

if ($_GET['op']=="ste"){
if(($session['user']['turns'] <=2)||($session['user']['dieb'] <=1)){
switch(e_rand(1,12)){

       case '1':
output("`8Nolan bittet dich zu einer Unterredung in sein Hinterzimmer. Gerade als er ansetzt zu sprechen wird er von einem Elternpaar gerufen, dass sich über die schulischen Fortschritte ihres Sohnes sorgt.`& Entschuldige mich kurz ich bin gleich wieder bei dir.`8 meint Nolan und schließt die Tür hinter sich. Du hast nun die Gelegenheit dich genau im Zimmer umzusehen. Die rechte und die linke Seite des Zimmers werden fast gänzlich von Bücherregalen eingenommen. In der Mitte des Raumes steht ein massiver Schreibtisch aus Mahagoniholz. Eine Ritterrüstung die zwischen  dem Schreibtisch und der Fensterfront steht erregt sofort deine Aufmerksamkeit. Du erhebst dich aus dem Stuhl und untersuchst sie etwas genauer. Du kannst nicht widerstehen und klappst das Visier des Helms hoch um hineinzusehen. Auf einmal hörst du hinter dir ein kratzendes Geräusch und in dem Bücherregal zu deiner Linken hat sich etwa in deiner Augenhöhe eine kleine Schublade heraus geschoben. Ihre Außenseite ist mit Buchrücken beklebt so dass sie gut versteckt scheint. Du siehst hinein und findest eine Unmenge von Goldstücken darin. Schnell stopfst du dir soviel in die Taschen wie du kannst, achtest aber darauf dass man die Ausbuchtungen nicht allzu deutlich wahrnimmt. Dann schließt du die Schublade wieder, klappst das Visier herunter und setzt dich auf den Stuhl um auf Nolan zu warten.  Dieser ist nach dem Gespräch mit diesen überbesorgten Eltern so entnervt dass er dich augenblicklich nach hause schickt. Als du aus dem Schulhaus hinaustrittst, biegst du schnell um die nächste Ecke und zählst das Gold in deinen Taschen. Du erbeutest 5 000 Goldstücke und gehst pfeifend nach hause.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`8Die Kinder benötigen neue Mathematikbücher. Du sollst die Mathestunde dazu nutzen jeden einzelnen Schüler nach vorn zu bitten dir das Gold für das Buch geben zu lassen und dem Schüler das Buch aushändigen. Nolan schärft dir ein wie viel gold du für jedes Buch bekommst und macht sich dann auf den Weg zur Bibliothek wo er mit einem befreundeten Gelehrten ein Treffen vereinbart hatte.
Als du den ersten Schüler ach vorn rufst und er dir mitteilt dass alle Eltern ihren Kindern 5000 Gold für die Bücher mitgegeben haben, klappt dir die Kinnlade herunter. Dir kommt eine gute Idee und du setzt sie sogleich in die Tat um. Alle Kinder geben dir ihr Geld und bekommen ihre Bücher. Du gibst ihnen für den Rest der Stunde genug Aufgaben und machst dich daran das Geld für Nolan abzuzählen. Als du damit fertig bist hast du immer noch einen stattlichen Haufen Gold, um genau zu sein 3000 Goldstücke vor dir und du verstaust sie schnell in einem Goldbeutel den du in deinen Gewändern verbirgst. Nolan ahnt nichts von deinen Extraeinnahmen und so machst du dich nachdem du ihm sein Gold übergeben hast, frohen Mutes auf nach hause.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case 3:
output("`8Du hältst deinen Schülern mal wieder einen Vortrag über die verschiedenen Heilpflanzarten und achtest dabei nicht so recht darauf wohin du gehst. Du stößt mit einem Fuß gegen das Podest auf dem das Lehrerpult steht und fällst fast der Länge nach hin. Die ganze Klasse brüllt vor Lachen doch schnell wirfst du ihnen eine strafenden Blick zu der sogleich maßregelnd wirkt. Du gibst ihnen die Anweisung in ihren Büchern das Gesagte nachzulesen und erbittest dir Ruhe. Als alle beschäftigt die Köpfe in die Bücher stecken ordnest du deine Kleidung und bemerkst dass sich ein Brett des Podestes gelöst hat. Du gehst in die Knie um nachzusehen ob du es wieder befestigen kannst und bemerkst dabei ein Glitzern unter dem Podest. Du streckst zögernd die Hand aus und findest einen Edelstein. Du lässt ihn in deiner Tasche verschwinden und greifst erneut unter das Podest. Du findest noch weitere Edelstein und zählst so schließlich 6 Edelsteine in deinen Taschen. Du befestigst das Brett wieder und widmest dich erneut deiner Klasse.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("`8Am heutigen Tage sollst du deinen Schülern etwas über die Edelsteine beibringen. Um den Unterricht etwas anschaulicher zu gestalten, gibt dir Nolan 20 Edelsteine damit jeder Schüler während der Stunde das Gesagte selbst sehen kann. Die Stunden wird ein voller Erfolg und du sammelst gegen Ende alle Edelsteine wieder ein. Als du die vielen Edelsteine auf einem Haufen siehst, meinst du das Nolan diese eigentlich gar nicht alle braucht. Die Kinder könnten sie sich paarweise betrachten, also benötigt man nur die Hälfte der Edelsteine. Kurzerhand schnappst du dir die anderen 10 Edelsteine und steckst sie in deine Tasche. Als du Nolan die Schatulle mit den Edelsteinen wiederbringst runzelt er kurz die Stirn. `& Ich hätte schwören können dass ich dir 20 Edelsteine gegeben hätte.`8 Er zuckt ratlos mit den Schultern.`& Nun ja ich werde eben langsam alt und muss mich wohl geirrt haben. Bis morgen.`8 Du verlässt die Schule mit deinem Lohn für heute und den zusätzlichen 4 Edelsteinen in deiner Tasche.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`8Du hattest dir von Nolan ein Buch über die Geschichte Aladrions ausgeliehen und er hat dir erlaubt es nachdem du es fertig gelesen hast einfach wieder in sein Bücherregal zu stellen. Du betrittst also das Hinterzimmer und musst dich sehr strecken um das Buch zurückzustellen. Dabei verlierst du das Gleichgewicht und musst dich am Rand des schweren Schreibtisches abstützen. Auf dessen Rand stand eine kleine Kiste, welche durch die Erschütterung zu Boden fällt wobei sich ihr ganzer Inhalt auf dem Boden verteilt. Ein wahrer Goldregen. Du sammelst die Goldstücke ein jedoch mit der Methode 100 in die Kiste 50 für meine Mühen. Als du nach einer ganzen Weile Schritte hörst, gibst du dies auf und schaufelst das restliche Gold mit beiden Händen zurück in die Kiste um diese dann wieder auf ihren Platz zu stellen. Als Nolan den Raum betritt erinnert nichts mehr an dein Missgeschick und du fragst ihn gleich nach dem 2. Band des Buchs das du gelesen hast. Mit dem Buch in der Hand und  1000 Goldstücken mehr in der Tasche verlässt du schließlich den Raum.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`8Es ist Winter geworden in Aladrion und alle Kinder erscheinen nun eingepackt in dicke Wintermäntel zum Unterricht. Natürlich lässt die Ordnungsliebe der Schüler zu wünschen übrig und so ist es jedes Mal an dir, wenn die Schüler mit einer Aufgabe länger beschäftigt sind die heruntergefallenen Mäntel wiederaufzuhängen. Du machst dich also an die Arbeit und bemerkst plötzlich, dass es neuerdings Mode ist die Mäntel statt mit Knöpfen mit Edelsteinen zu schließen. In deinen Augen blitzt es raffgierig auf und du beginnst jeweils den untersten Edelstein eines Mantels zu entfernen. Nach einer Weile werfen dir die Schüler merkwürdige Blicke zu und du unterbrichst deine Arbeit lieber. Dann betritt Nolan den Raum nickt dir zu und bedeutet dir die Kinder mehr zu beschäftigen. Du hast leider keine Gelegenheit mehr dich noch einmal den Mänteln zu nähern, verlässt die Schule jedoch am Ende des Tages mit 2 Edelsteinen in der Tasche.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`8Als du eines Morgens die Schule betrittst bist du voller Tatendrang, doch statt einer aufgeweckten Schülerschar findest du eine Kompanie Zinnsoldaten in deinem Klassenzimmer vor. Um den Kindern eine Freude zu machen, willst du ihnen etwas wirklich Lebenswichtiges beibringen: wie man eine  Papierflieger herstellt. Nachdem du den Kindern vorgeschlagen hast als Ausgangsmaterial eine Seite des verhassten Geschichtsbuches zu benutzen sind alle hellauf begeistert von dieser Idee und schon bald segeln munter bis zu 20 Papierflieger durch die Luft. Genau diesen Moment wählt Nolan um den Raum zu betreten und kann sich nur benommen umschauen. Schließlich kannst du mitverfolgen wie sich sein Gesichtsausdruck von entgeistert in überaus wütend wandelt und er kommt drohend auf dich zu :`& Wie kann man nur so verantwortungslos mit dem Eigentum der Schule und den Schülern umgehen. Du sollst ihnen Wissen vermitteln und nicht irgendwelchen Unsinn beibringen. Nein so etwas wie dich brauchen wir hier nicht. Hinaus aber schnell!`8 Und mit diesen Worten greift er nach dem Rohrstock der an der Wand lehnte und dir bleibt nichts anderes übrig als schnellstmöglich die Beine in die Hand zu nehmen und zu vesrchwinden`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`7Du wärst heut fast zu spät zum Unterricht gekommen und Nolan wirft dir einen bösen Blick zu als du abgehetzt doch noch pünktlich die Tür zur Dorfschule aufreißt. Du entschuldigst dich und beginnst sogleich mit dem Unterricht. Nachdem du etwa die Hälfte der Stunde ohne Komplikationen geschafft hast, beginnt dein Magen laut zu knurren und du spürst bereits leichte Magenkrämpfe. Du hattest natürlich keine Zeit mehr gehabt etwas zu essen und dies teilt dir dein Magen nun mit. Ein Schüler hatte dir vor der Stunde eine Apfel aufs Pult gelegt um sich beliebt zu machen und diesen betrachtest du nun sehnsüchtig. Schnelle gibst du den Kindern die Aufgabe etwas zu lesen, gehst zum Pult, nimmst den Apfel und beißt genüsslich hinein. Als du gerade verzückt kaust, räuspert sich hinter dir jemand vernehmlich. Du drehst dich langsam um und Nolan betrachtet dich mit finsterer Miene. `& Im Klassenzimmer wird nicht gegessen und so bist du den Schülern ein schlechtes Vorbild.`8 Er reißt dir den Apfel aus der Hand und meint:`& Ich werde das noch einmal durchgehen  lassen weil du deine Arbeit bis jetzt sehr gut machst doch sehe ich dich noch einmal wie du gegen die Schulregeln verstößt fliegst du in hohem Bogen raus hast du mich verstanden!`8 Du kannst nur zaghaft nicken und wendest dich dann wieder den Schülern zu. `n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
  case '9':
output("`8Als du eines Morgens die Schule betrittst bist du voller Tatendrang, doch statt einer aufgeweckten Schülerschar findest du eine Kompanie Zinnsoldaten in deinem Klassenzimmer vor. Um den Kindern eine Freude zu machen, willst du ihnen etwas wirklich Lebenswichtiges beibringen: wie man eine  Papierflieger herstellt. Nachdem du den Kindern vorgeschlagen hast als Ausgangsmaterial eine Seite des verhassten Geschichtsbuches zu benutzen sind alle hellauf begeistert von dieser Idee und schon bald segeln munter bis zu 20 Papierflieger durch die Luft. Genau diesen Moment wählt Nolan um den Raum zu betreten und kann sich nur benommen umschauen. Schließlich kannst du mitverfolgen wie sich sein Gesichtsausdruck von entgeistert in überaus wütend wandelt und er kommt drohend auf dich zu :`& Wie kann man nur so verantwortungslos mit dem Eigentum der Schule und den Schülern umgehen. Du sollst ihnen Wissen vermitteln und nicht irgendwelchen Unsinn beibringen. Nein so etwas wie dich brauchen wir hier nicht. Hinaus aber schnell!`8 Und mit diesen Worten greift er nach dem Rohrstock der an der Wand lehnte und dir bleibt nichts anderes übrig als schnellstmöglich die Beine in die Hand zu nehmen und zu vesrchwinden`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`7Du wärst heut fast zu spät zum Unterricht gekommen und Nolan wirft dir einen bösen Blick zu als du abgehetzt doch noch pünktlich die Tür zur Dorfschule aufreißt. Du entschuldigst dich und beginnst sogleich mit dem Unterricht. Nachdem du etwa die Hälfte der Stunde ohne Komplikationen geschafft hast, beginnt dein Magen laut zu knurren und du spürst bereits leichte Magenkrämpfe. Du hattest natürlich keine Zeit mehr gehabt etwas zu essen und dies teilt dir dein Magen nun mit. Ein Schüler hatte dir vor der Stunde eine Apfel aufs Pult gelegt um sich beliebt zu machen und diesen betrachtest du nun sehnsüchtig. Schnelle gibst du den Kindern die Aufgabe etwas zu lesen, gehst zum Pult, nimmst den Apfel und beißt genüsslich hinein. Als du gerade verzückt kaust, räuspert sich hinter dir jemand vernehmlich. Du drehst dich langsam um und Nolan betrachtet dich mit finsterer Miene. `& Im Klassenzimmer wird nicht gegessen und so bist du den Schülern ein schlechtes Vorbild.`8 Er reißt dir den Apfel aus der Hand und meint:`& Ich werde das noch einmal durchgehen  lassen weil du deine Arbeit bis jetzt sehr gut machst doch sehe ich dich noch einmal wie du gegen die Schulregeln verstößt fliegst du in hohem Bogen raus hast du mich verstanden!`8 Du kannst nur zaghaft nicken und wendest dich dann wieder den Schülern zu. `n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
  case '11':
output("`8Als du eines Morgens die Schule betrittst bist du voller Tatendrang, doch statt einer aufgeweckten Schülerschar findest du eine Kompanie Zinnsoldaten in deinem Klassenzimmer vor. Um den Kindern eine Freude zu machen, willst du ihnen etwas wirklich Lebenswichtiges beibringen: wie man eine  Papierflieger herstellt. Nachdem du den Kindern vorgeschlagen hast als Ausgangsmaterial eine Seite des verhassten Geschichtsbuches zu benutzen sind alle hellauf begeistert von dieser Idee und schon bald segeln munter bis zu 20 Papierflieger durch die Luft. Genau diesen Moment wählt Nolan um den Raum zu betreten und kann sich nur benommen umschauen. Schließlich kannst du mitverfolgen wie sich sein Gesichtsausdruck von entgeistert in überaus wütend wandelt und er kommt drohend auf dich zu :`& Wie kann man nur so verantwortungslos mit dem Eigentum der Schule und den Schülern umgehen. Du sollst ihnen Wissen vermitteln und nicht irgendwelchen Unsinn beibringen. Nein so etwas wie dich brauchen wir hier nicht. Hinaus aber schnell!`8 Und mit diesen Worten greift er nach dem Rohrstock der an der Wand lehnte und dir bleibt nichts anderes übrig als schnellstmöglich die Beine in die Hand zu nehmen und zu vesrchwinden`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`7Du wärst heut fast zu spät zum Unterricht gekommen und Nolan wirft dir einen bösen Blick zu als du abgehetzt doch noch pünktlich die Tür zur Dorfschule aufreißt. Du entschuldigst dich und beginnst sogleich mit dem Unterricht. Nachdem du etwa die Hälfte der Stunde ohne Komplikationen geschafft hast, beginnt dein Magen laut zu knurren und du spürst bereits leichte Magenkrämpfe. Du hattest natürlich keine Zeit mehr gehabt etwas zu essen und dies teilt dir dein Magen nun mit. Ein Schüler hatte dir vor der Stunde eine Apfel aufs Pult gelegt um sich beliebt zu machen und diesen betrachtest du nun sehnsüchtig. Schnelle gibst du den Kindern die Aufgabe etwas zu lesen, gehst zum Pult, nimmst den Apfel und beißt genüsslich hinein. Als du gerade verzückt kaust, räuspert sich hinter dir jemand vernehmlich. Du drehst dich langsam um und Nolan betrachtet dich mit finsterer Miene. `& Im Klassenzimmer wird nicht gegessen und so bist du den Schülern ein schlechtes Vorbild.`8 Er reißt dir den Apfel aus der Hand und meint:`& Ich werde das noch einmal durchgehen  lassen weil du deine Arbeit bis jetzt sehr gut machst doch sehe ich dich noch einmal wie du gegen die Schulregeln verstößt fliegst du in hohem Bogen raus hast du mich verstanden!`8 Du kannst nur zaghaft nicken und wendest dich dann wieder den Schülern zu. `n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
}
}
if($session['user']['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`5Es war einfach zuviel für dich. Ständig diese unwilligen Bälger, die lieber Unfug machten als etwas zu lernen und nichts anderes im Kopf hatten als dich zu ärgern. `n
Dir stand es einfach bis oben hin und das machtest du Nolan auch mit heftigem Gestikulieren deutlich, da er dich ja so schlecht verstand. Sollte er dieses Reich von dir aus alleine weiter führen, dir reichte es einfach. `n
Zwar tat dir dieser alte Mann doch irgendwo leid aber was zu viel war, war eben zuviel. Ohne ein weiteres Wort verlässt du das Zimmer und machst dir schon mal Gedanken, welcher Arbeit du nun nachgehen wolltest.
`n");
$session['user']['jobid']=0;
}

addnav("Zurück","village.php");
page_footer();
?>