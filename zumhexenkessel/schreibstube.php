<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Schreibstube");

output("`v`c`bSchreibstube`b`c`& `7Eine alte, massive Eichentür öffnet sich schwerfällig vor euch und gibt nur langsam den Blick auf ein ebenso klassisch eingerichtetes Zimmer frei.`n Als ihr eintretet schleicht sich sofort der Geruch von Papier und Tinte in eure Nase. `nEin verzierter, starker Schreibtisch steht mittig im Raum und macht sofort klar, dass es hier um die Kunst des Schreibens geht.`n Hinter diesem befindet sich ebenfalls eine Tür, genauso wie zu seinen Seiten, die allerdings weniger auffällig sind.`n`n");

addnav("Buchbinderrei","buch.php");
addnav("Kartograph","kartograph.php");

if($session['user']['jobid'] ==15){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","schreibstube.php?op=dazwei");
addnav("Kündigen","schreibstube.php?op=go");
addnav("Stehlen","schreibstube.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}
if($session['user']['jobid'] ==23){
if($session['user']['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","schreibstube.php?op=dadrei");
addnav("Kündigen","schreibstube.php?op=go");
addnav("Stehlen","schreibstube.php?op=ste");
}elseif($session['user']['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}


if ($_GET['op']=="dadrei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`tStaub schlägt dir entgegen als du die Tür zur Schreibstube aufdrückst, der dich in der Lunge kratzte und heftig zum husten brachte. `n
Dabei trittst du ungeschickterweise gegen einen Stapel von alten, vergilbten Pergamenten, die sich sogleich weit auf dem Boden verstreuten. `n
Inmitten noch weiterer Bücher und Zettel sahst du eine faltige Greisin namens Madame Toullere  sitzen, die dich mit bohrendem Blick musterte und dabei mit geübter Bewegung über ihre Lesebrille starrte. `n
`&„Nun das wird aber auch langsam Zeit, dass ihr erscheint.“ `tmeint sie mit tadelnder Stimme und rümpft die Nase als sie auf das angerichtete Chaos schaut. `&„Ich hoffe ihr stellt euch bei der Arbeit geschickter an und nun hopp hopp.“`n`n");

switch(e_rand(1,5)){

       case '1':
output("`tDu wirst bereits von Madame Toullere erwartet, die dir 2000 Gold in die Hand drückt. `&“Ihr müsst für heute nur 2 Stunden arbeiten, aber keine Pausen dazwischen verstanden?“`n`n");
$session['user']['turn']-=2;
$session['user']['gold']+=2000;
$session['user']['jobda']+=1;

break;

case '2':
output("`tMit ihrem typisch nüchternem Blick schaut deine Chefin über den Rand ihrer Lesebrille heraus und deutet auf deinen Arbeitsplatz. `&“3 Stunden für heute. Und, dass ihr mir für 3000 Gold nicht schlampig arbeitet verstanden?“`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '3':
output("`tDu wusstest bereits, dass Madame Toulleres Blick auf dir ruhte noch bevor du die Schwelle der Tür überschritten hattest. `&“Für heute werden 4 Stunden Arbeit nötig sein. Keine Widerrede, doch werden 4000 Gold als Lohn wohl angemessen sein.“`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=4000;
$session['user']['jobda']+=1;

break;
case '4':
output("`tSeufzend erscheinst du zur Arbeit und stelltest dich erst einmal vor Madame Toulleres Schreibtisch um deine heutigen Aufgaben zu erfahren. Nüchtern meint sie nur: `&“Los, los es gibt viel zu erledigen. 5 Stunden werdet ihr dafür benötigen. Euren Lohn von 5000 Gold erhaltet ihr im Anschluss.“`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=5000;
$session['user']['jobda']+=1;

break;
case '5':
output("`tMit abgenommener Lesebrille stand dir auf einmal deine Chefin Madame Toullere gegenüber und du wusstest, dass dies ein Zeichen dafür war, dass wirklich sehr viel Arbeit auf dich zukommen würde. `n
`&“Nun so leid es mir tut, doch werdet ihr für heute ganze 6 Stunden arbeiten müssen.“ `n
`tMan hörte deutlich, dass es sie in Wahrheit freute, doch im Gedanken an die 6000 Gold Lohn musstest du wohl auch das über dich ergehen lassen.`n`n");
$session['user']['turns']-=6;
$session['user']['gold']+=6000;
$session['user']['jobda']+=1;

break;
}
}
if($session['user']['jobf'] >=1){
$session['user']['jobf']-=1;
}
}

if ($_GET['op']=="dazwei"){

if ($session['user']['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session['user']['jobda'] ==0){

output("`tStaub schlägt dir entgegen als du die Tür zur Schreibstube aufdrückst, der dich in der Lunge kratzte und heftig zum husten brachte. `n
Dabei trittst du ungeschickterweise gegen einen Stapel von alten, vergilbten Pergamenten, die sich sogleich weit auf dem Boden verstreuten. `n
Inmitten noch weiterer Bücher und Zettel sahst du eine faltige Greisin namens Madame Toullere  sitzen, die dich mit bohrendem Blick musterte und dabei mit geübter Bewegung über ihre Lesebrille starrte. `n
`&„Nun das wird aber auch langsam Zeit, dass ihr erscheint.“ `tmeint sie mit tadelnder Stimme und rümpft die Nase als sie auf das angerichtete Chaos schaut. `&„Ich hoffe ihr stellt euch bei der Arbeit geschickter an und nun hopp hopp.“`n`n");

switch(e_rand(1,5)){

       case '1':
output("`tDu wirst bereits von Madame Toullere erwartet, die dir 1500 Gold in die Hand drückt. `&“Ihr müsst für heute nur 2 Stunden arbeiten, aber keine Pausen dazwischen verstanden?“`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1500;
$session['user']['jobda']+=1;

break;

case '2':
output("`tMit ihrem typisch nüchternem Blick schaut deine Chefin über den Rand ihrer Lesebrille heraus und deutet auf deinen Arbeitsplatz. `&“3 Stunden für heute. Und, dass ihr mir für 2250 Gold nicht schlampig arbeitet verstanden?“`n`n");
$session['user']['turns']-=3;
$session['user']['gold']+=2250;
$session['user']['jobda']+=1;

break;
case '3':
output("`tDu wusstest bereits, dass Madame Toulleres Blick auf dir ruhte noch bevor du die Schwelle der Tür überschritten hattest. `&“Für heute werden 4 Stunden Arbeit nötig sein. Keine Widerrede, doch werden 3000 Gold als Lohn wohl angemessen sein.“`n`n");
$session['user']['turns']-=4;
$session['user']['gold']+=3000;
$session['user']['jobda']+=1;

break;
case '4':
output("`tSeufzend erscheinst du zur Arbeit und stelltest dich erst einmal vor Madame Toulleres Schreibtisch um deine heutigen Aufgaben zu erfahren. Nüchtern meint sie nur: `&“Los, los es gibt viel zu erledigen. 5 Stunden werdet ihr dafür benötigen. Euren Lohn von 3750 Gold erhaltet ihr im Anschluss.“`n`n");
$session['user']['turns']-=5;
$session['user']['gold']+=3750;
$session['user']['jobda']+=1;

break;
case '5':
output("`tMit abgenommener Lesebrille stand dir auf einmal deine Chefin Madame Toullere gegenüber und du wusstest, dass dies ein Zeichen dafür war, dass wirklich sehr viel Arbeit auf dich zukommen würde. `n
`&“Nun so leid es mir tut, doch werdet ihr für heute ganze 6 Stunden arbeiten müssen.“ `n
`tMan hörte deutlich, dass es sie in Wahrheit freute, doch im Gedanken an die 4500 Gold Lohn musstest du wohl auch das über dich ergehen lassen.`n`n");
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
output("Es ist dunkel und alle Lichter sind gelöscht als du dich auf leisen Füßen durch die Pergamentberge arbeitest. Du weißt genau, dass Madame Toullere die ganzen Schätze im Hinterzimmer aufbewahrte.
Langsam drückst du die Klinke nach unten und riskierst einen vorsichtigen Blick. `n
Vor dir breitet sich ein Goldberg aus, von dem du geschwind etwas Gold mitgehen lässt. `n
So schnell und leise dich deine Füße nur tragen rennst du nach draußen und atmest erleichtert auf, dass dich keiner gesehen hat. Du zählst deine Beute und kommst auf insgesamt 5.000 Gold.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=5000;
$session['user']['dieb']+=1;
break;
       case '2':
output("`7Auf leisen Sohlen schleichst du zum Hinterzimmer und drückst vorsichtig die Tür auf hinter der sich die ganzen Einnahmen befanden. `n
Blitzschnell greifst du dir einen Teil davon und rennst ohne Rücksicht auf Verluste aus dem Gebäude. Schnaufend bleibst du hinter einer Ecke stehen und zählst die Goldstücke, die du erbeutet hast. Es waren insgesamt ganzen 3000 Stück.`n`n");
$session['user']['turns']-=1;
$session['user']['gold']+=3000;
$session['user']['dieb']+=1;
break;
       case '3':
output("Innerlich konntest du schon das Funkeln der Edelsteine erblicken, die dir bald gehören sollten als du leise durch die Gänge liefst und das Edlesteinlager bald erreichen würdest. `n
Langsam schiebst du den uralten Vorhang zur Seite und dir bot sich ein wunderbarer Anblick. So viele Edelsteine, wie du sie noch nie zuvor gesehen hast. `n
Da konntest du einfach nicht widerstehen und steckst dir so viele davon in die Taschen, wie es nur ging. Insgesamt waren es 6 Edelsteine, die du ohne Schwierigkeiten hinaus schaffst und die nun dir gehören sollten.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=6;
$session['user']['dieb']+=1;
break;
       case '4':
output("Heute sollte es endlich soweit sein, dass diese alte Greisin ihr Fett weg bekam. `n
Mürrisch schleichst du hinter ins Edelsteinlager und vergewisserst dich erst einmal, ob auch wirklich niemand in der Nähe war. Dessen sicher, greifst du einfach hinein und erbeutest ganze 4 Edelsteine mit einem mal. `n Als du jedoch plötzlich ein Geräusch hinter dir hörtest, sahst du schnell zu, dass du davon kommst und warst augenblicklich mit deiner beute von der Bildfläche verschwunden.`n`n");
$session['user']['turns']-=1;
$session['user']['gems']+=4;
$session['user']['dieb']+=1;
break;
       case '5':
output("`7Dein Herz machte bereits Freudensprünge als die alte Tür zur Goldkammer beinah lautlos aufging und dir das Glitzern der einzelnen Stücke förmlich in die Augen sprang. `n
So schnell du nur konntest schnappst du dir einen großen Goldsack als auf einmal Schritte zu vernehmen waren. Knarrend ging die Tür hinter dir auf und Madame Toullere trat ein. „Ist hier jemand?“ konntest du sie fragen hören und rennst zu einer Seitentür hinaus. Bei der Flucht merkst du, wie dein Goldsack leichter wird und beim späteren Nachzählen musst du feststellen, dass lediglich 1000 Goldstücke übrig blieben.`n`n");
$session['user']['turns']-=2;
$session['user']['gold']+=1000;
$session['user']['dieb']+=1;
break;
       case '6':
output("`7Zitternd standest du hinter ein Regal gepresst und hoffst nicht entdeckt zu werden. Dabei hatte alles so gut angefangen… niemand war da und du hast dich einfach am Edelsteinlager bedienen können. Aber auf einmal tauchte deine Chefin auf und ließ ihre eisigen Blicke musternd durch den Raum schweifen. `n
Als sie einen Moment nicht aufpasste, nutzt du die Gelegenheit zur Flucht, merkst aber, dass dir einige Edelsteine wieder aus der Tasche fallen. Einzig und allein 2 Edelsteine konntest du noch retten, zusammen mit deinem Wissen unentdeckt geblieben zu sein.`n`n");
$session['user']['turns']-=2;
$session['user']['gems']+=2;
$session['user']['dieb']+=1;
break;
       case '7':
output("`7Du warst bereits sehr weit gekommen und siehst schon, wie sich das Gold und die Edelsteine vor dir auftürmen. Diese alte Vettel war aber auch wirklich reicher als sie verdiente, wenn du daran dachtest, wie sie die Angestellten behandelt. Ohne zu zögern greifst du dir soviel du tragen kannst und drehst dich herum um zu verschwinden. `n
Doch du hattest dich zu früh gefreut. Mit Zornesfalten auf der Stirn stand Madame Toullere hinter dir und schaute außer sich auf deine Beute. `n
Für dich stand in diesem Augenblick die Zeit still und noch bevor du wieder begreifen konntest, was geschah, saßest du schon mit einer fristlosen Kündigung auf der Straße.
`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '8':
output("`7Es war doch so leicht gewesen am Anfang, dachtest du so für dich als du die stundenlange Strafpredigt deiner Chefin anhören musstest. `n
Alles zum greifen nahe… das Gold und die Edelsteine… `n
Und auf einmal musste sie ja hinter dir stehen. Du hattest bereits Angst, dass ihr die Augen heraus fielen, so sehr, wie sie dich anstarrte. Natürlich wusste sie, was du vorhattest. Die Beweise lagen ja direkt noch in deine Hand. `n
Eigentlich dachtest du ja, dass sie dich direkt heraus wirft, aber sie beließ es bei einer deftigen Verwarnung.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '9':
output("`7Du warst bereits sehr weit gekommen und siehst schon, wie sich das Gold und die Edelsteine vor dir auftürmen. Diese alte Vettel war aber auch wirklich reicher als sie verdiente, wenn du daran dachtest, wie sie die Angestellten behandelt. Ohne zu zögern greifst du dir soviel du tragen kannst und drehst dich herum um zu verschwinden. `n
Doch du hattest dich zu früh gefreut. Mit Zornesfalten auf der Stirn stand Madame Toullere hinter dir und schaute außer sich auf deine Beute. `n
Für dich stand in diesem Augenblick die Zeit still und noch bevor du wieder begreifen konntest, was geschah, saßest du schon mit einer fristlosen Kündigung auf der Straße.
`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '10':
output("`7Es war doch so leicht gewesen am Anfang, dachtest du so für dich als du die stundenlange Strafpredigt deiner Chefin anhören musstest. `n
Alles zum greifen nahe… das Gold und die Edelsteine… `n
Und auf einmal musste sie ja hinter dir stehen. Du hattest bereits Angst, dass ihr die Augen heraus fielen, so sehr, wie sie dich anstarrte. Natürlich wusste sie, was du vorhattest. Die Beweise lagen ja direkt noch in deine Hand. `n
Eigentlich dachtest du ja, dass sie dich direkt heraus wirft, aber sie beließ es bei einer deftigen Verwarnung.`n`n");
$session['user']['turns']-=2;
$session['user']['jobf']+=1;
$session['user']['dieb']+=1;
break;
       case '11':
output("`7Du warst bereits sehr weit gekommen und siehst schon, wie sich das Gold und die Edelsteine vor dir auftürmen. Diese alte Vettel war aber auch wirklich reicher als sie verdiente, wenn du daran dachtest, wie sie die Angestellten behandelt. Ohne zu zögern greifst du dir soviel du tragen kannst und drehst dich herum um zu verschwinden. `n
Doch du hattest dich zu früh gefreut. Mit Zornesfalten auf der Stirn stand Madame Toullere hinter dir und schaute außer sich auf deine Beute. `n
Für dich stand in diesem Augenblick die Zeit still und noch bevor du wieder begreifen konntest, was geschah, saßest du schon mit einer fristlosen Kündigung auf der Straße.
`n`n");
$session['user']['turns']-=2;
$session['user']['jobid']=0;
$session['user']['dieb']+=1;
break;
       case '12':
output("`7Es war doch so leicht gewesen am Anfang, dachtest du so für dich als du die stundenlange Strafpredigt deiner Chefin anhören musstest. `n
Alles zum greifen nahe… das Gold und die Edelsteine… `n
Und auf einmal musste sie ja hinter dir stehen. Du hattest bereits Angst, dass ihr die Augen heraus fielen, so sehr, wie sie dich anstarrte. Natürlich wusste sie, was du vorhattest. Die Beweise lagen ja direkt noch in deine Hand. `n
Eigentlich dachtest du ja, dass sie dich direkt heraus wirft, aber sie beließ es bei einer deftigen Verwarnung.`n`n");
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
output("`tZittrig gehst du zum Arbeitsplatz von Madame Toullere und ziehst den Kopf ein klein wenig ein. `n
Deine wohl bisher schwerste Aufgabe stand bevor: dich dem Schreiberdrachen stellen. `n
Deine Glieder erstarrten als dich ihr eisiger Blick traf und nur mit Mühe brachtest du dein Anliegen wegen der Kündigung heraus. `n
Noch bevor du dich versehen kannst streckt sie wortlos den dürren Finger in Richtung der Tür und du beschließt, dass es wohl besser währe hier nie mehr aufzutauchen.`n");
$session['user']['jobid']=0;
}

addnav("Zurück");
addnav("Zum Dorf","village.php");
addnav("Zum Rathaus","rathausmenue.php");
page_footer();
?>