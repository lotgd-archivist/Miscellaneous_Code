<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";
addcommentary();
page_header("Theatersaal");

output("`v`c`bTheatersaal`b`c`1 Du trittst in einen großen, luxuriös wirkenden Raum. Dies scheint wohl der Theatersaal zu sein! denkst du dir und bekommst große Augen. Viele Runde Tische sind aufgestellt, einige zum stehen und andere mit filigran wirkenden Stühlen davor, die mit rot-goldenen Samttüchern überzogen waren. Langsam schreitest du voran, alles genau betrachtend. Was dir sofort auffällt ist die große, atemberaubende Bühne! Rote, lange Vorhänge die mit goldenen Stickereien verziert waren, hingen fast schon majestätisch zu Boden. Gerne würdest du einen Blick hinter die Vorhänge riskieren aber deine Vernunft macht dir einen Strich durch die Rechnung. Auf leisen Sohlen machst du dich auf den Weg zur Tür und freust dich auf das nächste Theaterstück was du hier zu sehen bekommst!`n`n");
if($session[user]['jobid'] ==27){
if($session[user]['turns'] >=6){
addnav("Arbeiten");
addnav("Melden","theater.php?op=dadrei");
addnav("Kündigen","theater.php?op=go");
addnav("Stehlen","theater.php?op=ste");
}elseif($session[user]['turns'] <=5){
output(" Du hast nicht genug Runden um Heute zu Arbeiten`n");
}
}

if ($_GET['op']=="dadrei"){

if ($session[user]['jobda'] ==1){
output(" Du hast heute schon gearbeitet`n");
}elseif ($session[user]['jobda'] ==0){

output("`2Leicht nach Luft ringend öffnest du die Tür zum Theatersaal. Gerade noch rechtzeitig hattest du es, zu deinem Vorstellungsgespräch geschafft! Ein kleiner, bärtiger Mann steht vor dir und mustert dich ausgiebig, stemmt seine kleinen Hände in die Hüften `&Da sind sie aber gerade noch rechtzeitig gekommen!`2 murmelt der kleine Mann und tipt dir kurz auf die Schulter. Du nickst ihm eifrig zu und reichst ihm deine Hand um dich vorzustellen. Mit einem kräftigen Händedruck schüttelt er dir die Hand `&Mein Name ist Alestro! Ich bin der Regisseur in diesem Theater.`2 er hält kurz inne und betrachtet dich leicht `&Ich habe große Pläne mit ihnen, müssen sie wissen! Gute Schauspieler oder Tänzer findet man leider nicht an jeder Strassenecke`2 er lächelt dich leicht an. `&Ich werde mein bestes geben, werter Alestro`2 sprichst du zu ihm mit zuversichtlicher Stimme. `&Da bin ich mir sicher, auf gute Zusammenarbeit`2 murmelt er und deutet auf die Tür den er hat Arbeit für dich`n`n`n");

switch(e_rand(1,5)){

       case '1':
output("`7Zeitig betrittst du den Theatersaal um dein Tagewerk anzubrechen, Alestro hatte dich schon erwartet. `&Ich brauche dich heute für 2 std. , dafür bekommst du einen satten Lohn von 2000 Goldstücken! Dann geh dich mal umziehen, die Proben beginnen gleich!`n`n");
$session[user]['turns']-=2;
$session[user]['gold']+=2000;
$session[user]['jobda']+=1;

break;

case '2':
output("`7Etwas verspätet tauchst du auf der Arbeit auf. Der kleine Ressigeur steht leicht grummelnd und keifend vor dir : `&Das das nicht zu oft vorkommt!`7 murmelt er etwas harsch `&Du musst heute 3 Std. für mich arbeiten und bekommst dafür 3000 Gold. Eigentlich hättest du nur 1500 verdient, wegen deiner Verspätung aber ich werde nochmal ein Auge zudrücken! Könntest du dann bitte kurz die Tische abwischen, das habe ich bis jetzt noch nicht geschafft.`n`n");
$session[user]['turns']-=3;
$session[user]['gold']+=3000;
$session[user]['jobda']+=1;

break;
case '3':
output("`7Ausgeschlafen und gut gelaunt kommst du zur Arbeit. Du öffnest die Tür und rufst nach Alestro, der gerade hinter der Bühne ein paar Requisiten neu bemalte. `&Ah, pünktlich auf die Minute! Könntest du heute für 4 std. arbeiten? das macht 4000 Gold für dich.`7 stammelt er leise`n`n");
$session[user]['turns']-=4;
$session[user]['gold']+=4000;
$session[user]['jobda']+=1;

break;
case '4':
output("`7Du öffnest langsam die verzierte Holztür und trittst hinein, setzt einen großen schritt nach vorne und schließt vorsichtig die Tür hinter dir. Alestro geht auf dich zu und reicht dir freundlich die Hand. `&Gut das du da bist, du kannst mir gleich mal helfen! Ich brauche dich heut für 5 Std. , du weisst ja, das macht einen Lohn von ganzen 5000 Goldstücken!`n`n");
$session[user]['turns']-=5;
$session[user]['gold']+=5000;
$session[user]['jobda']+=1;

break;
case '5':
output("`&Los , beweg dich du Faulpelz`7 raunt der kleine Mann dich an. Er hatte heute sichtlich schlechte Laune und du wärst am liebsten Zuhause geblieben!
`&6 std. musst du heute für mich arbeiten, keine Minute weniger! Dir stehen 6000 Gold zu und nun mach dich an die Arbeit aber hurtig!`n`n");
$session[user]['turns']-=6;
$session[user]['gold']+=6000;
$session[user]['jobda']+=1;

break;
}
}
if($session[user]['jobf'] >=1){
$session[user]['jobf']-=1;
}
}

if ($_GET['op']=="ste"){
if(($session[user]['turns'] <=2)||($session[user]['dieb'] <=1)){
switch(e_rand(1,12)){

       case '1':
output("`8Du wischt gerade fleissig den Boden, als der Regisseur gedankenversunken
und leise summend an dir vorbei schlendert und nebenbei einige Statuen
die verteilt im Theatersaal stehen, abstaubt. Scheinbar schien er nicht
zu bemerken, das dabei sein Geldbeutel klirrend zu Boden fällt! Du wunderst
dich etwas, eigentlich hätte er es doch bei dem aufprall bemerken müssen,
  doch stattdessen singt der kleine, etwas hutzelige Mann vergnügt weiter
und verschwindet in einem der Nebenräume. Ganz vorsichtig wagst du dich an
  den Goldbeutel heran, schaust dich nochmal schnell um, ob Alestro auch
nirgendswo mehr zu sehen war und öffnest ihn gekonnt, mit einem mal.
Zum Vorschein kommen 5.000 Gold, die du dir leise jubelnd in die Tasche
  steckst!`n`n");
$session[user]['turns']-=1;
$session[user]['gold']+=5000;
$session[user]['dieb']+=1;
break;
       case '2':
output("`8Du hast dich in eine hintere Ecke des Saales verzogen um einen deiner
Texte zu lernen. Immer wieder liest du dir den Text genau durch und sagst
ihn immer wieder leise auswendig auf um ihn zu lernen! Alestro kommt zu dir
und klopft dir grinsend auf die Schulter `6Ja lerne schön deinen Text, so
ist es gut`8 flüstert er dir leise zu und wendete sich von dir ab um zur
Bühne zu gehen. Dabei fielen ihm 3000 Gold aus der Tasche. Ihn seiner
aufkommenden Hektik, schien er es wohl nicht bemerkt zu haben, das ihm das
Gold aus der Tasche fiel. Du blickst abwechselnd zu dem Gold und ihm
hinterher. In einem günstigen Augenblick, schnappst du dir schnell das
Gold und tust so als sei nichts gewesen.`n`n");
$session[user]['turns']-=1;
$session[user]['gold']+=3000;
$session[user]['dieb']+=1;
break;
       case '3':
output("`8Der alte, etwas klein geratene Mann steht mit dir zusammen auf der Bühne.
Er wuselt leicht um dich herum, erklärt dir immer wieder wie man die
Kunstbäume für das nächste Theaterstück richtig in Position bringt und
einige Tücher am besten , der optik wegen, aufhängt. Du beobachtest die
ganze Zeit schon seine voll gefüllte Tasche mit Edelsteinen. Einen nach
den anderen ziehst du langsam heraus , bis keiner mehr vorzufinden ist.
Du atmest einmal aus erleichterung tief durch und bemerkst, das du 6
Edelsteine von ihm ergattern konntest!`n`n");
$session[user]['turns']-=1;
$session[user]['gems']+=6;
$session[user]['dieb']+=1;
break;
       case '4':
output("`&Ich habe mir meine Pause wirklich verdient`8 denkst du dir, als du gemütlich
auf einem der Stühle platz nimmst. Du hattest heute schon hart gearbeitet
und freust dich nun, dich etwas ausruhen zu können. Alestro kommt mit einer
Tasse Tee zu dir hinüber und bietet dir den Tee an, dankend nimmst du ihn
an. Während er sich umdreht ziehst du grinsend an seinem Beutel, der etwas
locker an seinem Gürtel sitzt und sich somit mühelos abmachen lässt. Du
blickst ihm noch kurz hinterher und schüttest dann die edelsteine auf deinem
schoss aus. 4 leuchtend, funkelnde Edelsteine kommen zum Vorschein. Dir
entweicht ein kleiner Freudenschrei und so schnell wie sie aufgetaucht
waren , verschwinden sie auch schonwieder in deiner Tasche.`n`n");
$session[user]['turns']-=1;
$session[user]['gems']+=4;
$session[user]['dieb']+=1;
break;
       case '5':
output("`8Du kommst gerade zur Arbeit herein, als du den kleinen Mann, namens
Alestro dabei siehst, wie er die Stühe mit samtigen Blauen Überzügen
verseht. `&Seid gegrüsst , Alestro`8 sprichst du ihn leise an um ihn nicht
zu erschrecken. `&Ich grüße sie`8 spricht Alestro, wendet sich dabei aber
nicht von den Stühlen ab. Du beobachtest seine Brieftasche die Halb aus
seiner ledernden Hose herausguckt und ziehst leicht daran, Alestro murmelt
leise und fässt sich unbewusst an seine Brieftasche. Du wusstest das du
es nicht schaffen würdest ihm die komplette Tasche herauszuziehen und
beisst dir seufzend auf die Lippe. Da bekommst du eine Idee, du ziehst ein
kleines Messer aus deiner Tasche und schlitzt die Brieftasche von der
einen Seite auf, so das einiges Gold frei liegt. Schnell greifst du dir etwas
Gold und steckst es schnell ein. Gerade als du dir den Rest auch noch
stibitzen wolltest, dreht sich Alestro fragend um `&War was?`8 murmelt er
gedankenversunken, du schüttelst nur mit dem Kopf, dein Herz schlug dir
vor Aufregung bis zum Halse, dennoch bemerkte er es nicht und wandte sich
wieder seinen Stühlen zu. Erleichtert atmest du auf, wenigstens konntest
du 1000 Gold von ihm stehlen.`n`n");
$session[user]['turns']-=2;
$session[user]['gold']+=1000;
$session[user]['dieb']+=1;
break;
       case '6':
output("`8Der Schweiss stand dir dick auf der Stirn. Die Arbeit war heute besonders
hart, du hattest keine Lust, dazu auch noch schlechte Laune aber was soll
man tun? gearbeitet werden musste nunmal! Zudem war der kleine, hutzelige
Mann angetrunken. Er hatte von einigen Gästen eine Flasche Met geschenkt
bekommen und schien sie wohl mit einem Male geleert zu haben, zumindest
führte er sich so auf! Lallend kam er zu dir rüber und brabbelte etwas
unverständliches, so das du seine stinkende Fahne riechen konntest und
angewiedert das Gesicht verziehst. Aus Frust greifst du schnell in seinen
Beutel und ziehst einen Edelstein hervor. Eigentlich wolltest du gleich
mehrere auf einmal nehmen, doch es war schier unmöglich, bei seinem
hin und her gelaufe und seinem unentwegten gebrabbele. Wenigstens schafftest
du es noch 2 weitere Edelsteine , nach und nach, aus seiner Tasche zu
ziehen. Dennoch warst du innerlich am fluchen, die Tasche wahr reich gefüllt an
Edelsteinen, doch es ging beim besten Willen nicht, noch mehr heraus zu
holen!`n`n");
$session[user]['turns']-=2;
$session[user]['gems']+=2;
$session[user]['dieb']+=1;
break;
       case '7':
output("`8An einem schönen Tag sitzt du in deiner wohl verdienten Pause mit Alestro
zusammen in einem der Hinterzimmer. Der alte Mann stand auf und beschrieb
mit vielen wilden Armbewegungen, die nächste Szene die du in einem Theaterstück
spielen solltest. Dabei purzelte ihm seine Brieftasche aus dem silbernen
Umhang den er trug. Schnell griffst du nach ihr und wolltest sie gerade
einstecken , als er dich erschrocken und mit wuterfüllten Augen ansah!
`&Was fällt dir ein, mich zu beklauen, du Dieb!`8 Er zeigt mit dem Finger
auf dich und fing an laut zu fluchen `&Und du willst ein ehrenwerter Schauspieler
sein, ein Schauspieler der seinen Chef beklaut, was sind denn das hier für
manieren!`8 Er war wutentbrannt, kleine Äderchen zeichneten sich auf seiner
Stirn ab und noch immer richtete er seinen Zeigefinger drohend auf dich.
`&Scher dich hinfort aber plötzlich, ich will dich hier nie wieder sehen,
du bist gekündigt!!`8 Diese Worte waren klar und deutlich, er verlange
noch sein gestohlenes Hab und Gut zurück, bevor er dich rausschmeisste.`n`n");
$session[user]['turns']-=2;
$session[user]['jobid']=0;
break;
       case '8':
output("`8Du bist pünktlich auf die Minute bei der Arbeit angekommen. Alestro
begrüßte dich kurz , bevor er sich eine lange Holzleiter holte um ein paar
heruntergebrannte Kerzen zu erneuern und bittet dich , die Leiter zu
halten, damit er nicht herunterfällt! Du nickst ihm bejahend zu und stellst
dich an die Leiter um sie fest zu halten, dabei siehst du ihm zu , wie
er sich an den Kerzen zu schaffen machte und bemerkst sein blaues, kleines
Beutelchen, was an seinem Gürtel befestigt war. Du wusstest das er dort
immer seine Edelsteine aufbewahrte und zogst langsam an dem schwarzen Bändchen.
Doch zu spät, er hatte dich auf frischer Tat ertappt! `&Was tust du da?`8 keifte
er dich an `&Du wolltest mich doch nicht etwa bestehlen?`8 Du brachtest keinen
Ton heraus, ein dicker Klos steckte nun in deinem Hals `&Das darf ja wohl nicht wahr
sein`8 schrie er und stieg von der Leiter ab, sah ihn eindringlich an. `&Bemerke
ich es noch einmal , das du mich bestehlen willst, bist du deinen Job los
hast du das verstanden?`8 du konntest keinen Ton sagen, sondern nickst einfach
nur mit dem Kopf `&Und nun scher dich fort, geh an deine Arbeit`8 kopfschüttelnd
stieg der alte Mann wieder auf die Leiter.`n`n");
$session[user]['turns']-=2;
$session[user]['jobf']+=1;
break;
       case '9':
output("`8An einem schönen Tag sitzt du in deiner wohl verdienten Pause mit Alestro
zusammen in einem der Hinterzimmer. Der alte Mann stand auf und beschrieb
mit vielen wilden Armbewegungen, die nächste Szene die du in einem Theaterstück
spielen solltest. Dabei purzelte ihm seine Brieftasche aus dem silbernen
Umhang den er trug. Schnell griffst du nach ihr und wolltest sie gerade
einstecken , als er dich erschrocken und mit wuterfüllten Augen ansah!
`&Was fällt dir ein, mich zu beklauen, du Dieb!`8 Er zeigt mit dem Finger
auf dich und fing an laut zu fluchen `&Und du willst ein ehrenwerter Schauspieler
sein, ein Schauspieler der seinen Chef beklaut, was sind denn das hier für
manieren!`8 Er war wutentbrannt, kleine Äderchen zeichneten sich auf seiner
Stirn ab und noch immer richtete er seinen Zeigefinger drohend auf dich.
`&Scher dich hinfort aber plötzlich, ich will dich hier nie wieder sehen,
du bist gekündigt!!`8 Diese Worte waren klar und deutlich, er verlange
noch sein gestohlenes Hab und Gut zurück, bevor er dich rausschmeisste.`n`n");
$session[user]['turns']-=2;
$session[user]['jobid']=0;
break;
       case '10':
output("`8Du bist pünktlich auf die Minute bei der Arbeit angekommen. Alestro
begrüßte dich kurz , bevor er sich eine lange Holzleiter holte um ein paar
heruntergebrannte Kerzen zu erneuern und bittet dich , die Leiter zu
halten, damit er nicht herunterfällt! Du nickst ihm bejahend zu und stellst
dich an die Leiter um sie fest zu halten, dabei siehst du ihm zu , wie
er sich an den Kerzen zu schaffen machte und bemerkst sein blaues, kleines
Beutelchen, was an seinem Gürtel befestigt war. Du wusstest das er dort
immer seine Edelsteine aufbewahrte und zogst langsam an dem schwarzen Bändchen.
Doch zu spät, er hatte dich auf frischer Tat ertappt! `&Was tust du da?`8 keifte
er dich an `&Du wolltest mich doch nicht etwa bestehlen?`8 Du brachtest keinen
Ton heraus, ein dicker Klos steckte nun in deinem Hals `&Das darf ja wohl nicht wahr
sein`8 schrie er und stieg von der Leiter ab, sah ihn eindringlich an. `&Bemerke
ich es noch einmal , das du mich bestehlen willst, bist du deinen Job los
hast du das verstanden?`8 du konntest keinen Ton sagen, sondern nickst einfach
nur mit dem Kopf `&Und nun scher dich fort, geh an deine Arbeit`8 kopfschüttelnd
stieg der alte Mann wieder auf die Leiter.`n`n");
$session[user]['turns']-=2;
$session[user]['jobf']+=1;
break;
       case '11':
output("`8An einem schönen Tag sitzt du in deiner wohl verdienten Pause mit Alestro
zusammen in einem der Hinterzimmer. Der alte Mann stand auf und beschrieb
mit vielen wilden Armbewegungen, die nächste Szene die du in einem Theaterstück
spielen solltest. Dabei purzelte ihm seine Brieftasche aus dem silbernen
Umhang den er trug. Schnell griffst du nach ihr und wolltest sie gerade
einstecken , als er dich erschrocken und mit wuterfüllten Augen ansah!
`&Was fällt dir ein, mich zu beklauen, du Dieb!`8 Er zeigt mit dem Finger
auf dich und fing an laut zu fluchen `&Und du willst ein ehrenwerter Schauspieler
sein, ein Schauspieler der seinen Chef beklaut, was sind denn das hier für
manieren!`8 Er war wutentbrannt, kleine Äderchen zeichneten sich auf seiner
Stirn ab und noch immer richtete er seinen Zeigefinger drohend auf dich.
`&Scher dich hinfort aber plötzlich, ich will dich hier nie wieder sehen,
du bist gekündigt!!`8 Diese Worte waren klar und deutlich, er verlange
noch sein gestohlenes Hab und Gut zurück, bevor er dich rausschmeisste.`n`n");
$session[user]['turns']-=2;
$session[user]['jobid']=0;
break;
       case '12':
output("`8Du bist pünktlich auf die Minute bei der Arbeit angekommen. Alestro
begrüßte dich kurz , bevor er sich eine lange Holzleiter holte um ein paar
heruntergebrannte Kerzen zu erneuern und bittet dich , die Leiter zu
halten, damit er nicht herunterfällt! Du nickst ihm bejahend zu und stellst
dich an die Leiter um sie fest zu halten, dabei siehst du ihm zu , wie
er sich an den Kerzen zu schaffen machte und bemerkst sein blaues, kleines
Beutelchen, was an seinem Gürtel befestigt war. Du wusstest das er dort
immer seine Edelsteine aufbewahrte und zogst langsam an dem schwarzen Bändchen.
Doch zu spät, er hatte dich auf frischer Tat ertappt! `&Was tust du da?`8 keifte
er dich an `&Du wolltest mich doch nicht etwa bestehlen?`8 Du brachtest keinen
Ton heraus, ein dicker Klos steckte nun in deinem Hals `&Das darf ja wohl nicht wahr
sein`8 schrie er und stieg von der Leiter ab, sah ihn eindringlich an. `&Bemerke
ich es noch einmal , das du mich bestehlen willst, bist du deinen Job los
hast du das verstanden?`8 du konntest keinen Ton sagen, sondern nickst einfach
nur mit dem Kopf `&Und nun scher dich fort, geh an deine Arbeit`8 kopfschüttelnd
stieg der alte Mann wieder auf die Leiter.`n`n");
$session[user]['turns']-=2;
$session[user]['jobf']+=1;
break;
}
}
if($session[user]['dieb']>=2){
output("`b`n`8Du hast Heute schon 2 Mal gestohlen warte bis der neue Tag anbricht `b`n`n");
}
}
if ($_GET['op']=="go"){
output("`7traurig stehst du vor der schweren Holztür des Theaters. Du hattest schon
seid einiger Zeit darüber nachgedacht zu kündigen, heute sollte es soweit sein.
Langsam drückst du die Türklinge nach unten und trittst in den großen Saal
ein. Alestro hatte dich schon erwartet und sah dich etwas besorgt an,
warscheinlich bemerkte er, deinen traurigen Gesichtsausdruck! `&Was ist mit
dir?`7 fragte er. Du schaust ihn an und holst einmal tief Luft `&Ich werde
mir einen anderen Job suchen Alestro, ich wollte dir nurnoch sagen, das ich ab
heute kündige, es tut mir sehr leid, ich habe immer gerne hier gearbeitet.
Aber es geht nicht anders`7 stammelst du leise. Der kleine Mann sieht dich
etwas betrübt an `&Das ist wirklich schade, ich habe immer gerne mit dir gearbeitet
und du hast deine Arbeit immer gut gemacht. Aber ich kann dich schlecht
zwingen hier zu bleiben, es ist ganz alleine deine Entscheidung, was für dch
das beste ist!`7 er hält kurz inne und schaut zu Boden, bevor er dir wieder
ins Gesicht blickt `&Ich wünsche dir alles Gute, für die Zukunft und deinen
weiteren Beruflichen Werdegang, ich hoffe das du mich mal besuchen kommst`7
fügte er noch hinzu und lächelte leicht. Du grinst ihn nun ebenfalls an
und versprichst ihm ihn auf jeden fall mal besuchen zu kommen, bedankst
dich noch für die schöne Zeit und machst dich langsam wieder auf den Weg.
Immernoch etwas traurig aber irgendwo auch erleichtert, freust du dich
schon auf deinen nächsten Beruf, den du ausüben wirst.`n");
$session[user]['jobid']=0;
}
output('`@Bühne`n`n');
        if ($session['user']['jobid']==27)
            {
            viewcommentary('Bühne','',15);
            }
        else
            {
            viewcommentary('Bühne','Y',15);
            }

output("`n`n`%`@In der Nähe reden einige Zuschauer:`n");
viewcommentary("Zuschauersaal","Hinzufügen",25);
/*if($session[user]['jobid'] ==27){
addcommentary();
output("`n`n`n`$ Bühne:`n");
viewcommentary("Bühne","Vorführung",25);
}*/

addnav("zurück","village.php");
page_footer();
?>