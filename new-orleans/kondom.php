
<?php 
/*
********************************************************************************
*                                                                              *
*Shanas Kondomladen für Midgars Freudenhaus --> www.logd-midgar.de             *
*Idee: Shana hat ne ganze weile im Ot von Midgar Kondome per Bauchladen        *
*verkauft. Das ist fast ein Jahr schon her und Shana hat sich weiter           *
*entwickelt. Nun hat sie keinen Bauchladen sondern ein Ladenlokal in           *
*Midgars Freudenhaus. Viel Spass beim einkaufen ;) Ach ja, ihr könnt den       *
*Namen  des Dorfes ändern aber lasst bitte den Namen der Verkäuferin drin.     *
*Vielen Dank.                                                                  *
*Verbrochen von: mfs                                                           *
*Texte von: Shana                                                              *
*Repariert von:  Taikun                                                              *
*SQL:                                                                          *
*ALTER TABLE `accounts` ADD `kondom` tinyint(4) unsigned NOT NULL default '0';
*Newday.php
*$session['user']['kondom'] = 0;
*                                                                              *
********************************************************************************
*/
 
require_once "common.php";
page_header("Shanas Kondomladen");
place();
$maxhp=$session[user][maxhitpoints];
if($_GET['op']==""){
output("`rDu betrittst das Freudenhaus Midgars und gleich zu deiner rechten Seite fällt dir ein goldenes glänzendes
Schild ins Auge, auf dem in geschwungenen Lettern geschrieben steht: '`%Shanas Kondomlädchen`r'
Neugierig näherst du dich dem kleinen putzigen Geschäft. Ist das die selbe Shana, die früher mit ihrem Bauchladen durch
Midgar streifte und laut schreiend ihre Waren feilbot? Neugierig gehst du näher und musst feststellen, das sie es
wirklich ist. Noch immer hast du ihr fröhliches Gebrüll in den Ohren: '`%Du wolle Kondome kaufe? Preiswert aber nich
billisch! Kondome aus Jute für de kleine Goldbeutel. Mäusedarmkondome für de winzig Schnibbel. Schafsdarmkondome mit de
Löcher für de kinderwilligen Pärchen. Rinddarmkondome gefühlsecht. Pferdedarmkondome für den schnellen Galopp.
Lammdarmkondome mit de Hasenköttel wegen de Noppen. Dinodarmkondome für de Saferscheksch. Hähnchenknochenverstärkte
Kondome für de Knickschnibbel. Zitteraalkondom für de prickelnde Scheksch. Drachendarmkondom wenn's feurig werden soll.'
`rDu bist noch ganz in deinen Gedanken versunken, als du eine melodische Stimme wahrnimmst, die deinen Ohren zu schmeicheln
scheint. Aus einem kleinen Hinterraum kommt Shana mit sanft wiegenden Hüften näher. Dich freundlich anlächelnd fragt sie: 'Wie kann ich Euch behilflich sein? Schaut Euch nur in Ruhe um. Es sollte genügend Auswahl für Euch vorhanden
sein.' Dann wendet sie sich diskret wieder ab, damit du dich in Ruhe umschauen kannst. Willst du dir hier ein paar
Kondome einkaufen oder lieber nicht?");

addnav("Jutesackkondom - 100 Gold","kondom.php?op=jute");
addnav("Mäusedarmkondom - 150 Gold","kondom.php?op=maus");
addnav("Schafsdarmkondom - 350 Gold","kondom.php?op=schaf");
addnav("Rindsdarmkondom - 800 Gold","kondom.php?op=rind");
addnav("Hengstdarmkondom - 1000 Gold","kondom.php?op=hengst");
addnav("Lammdarmkondom - 1300 Gold","kondom.php?op=lamm");
addnav("Dinodarmkondom - 1350 Gold","kondom.php?op=dino");
addnav("Knochenkondom - 1700 Gold","kondom.php?op=knochen");
addnav("Zitteraalkondom - 2500 Gold","kondom.php?op=aal");
if ($session[user][level]==15){
addnav("Drachendarmkondom - 3500 Gold","kondom.php?op=drache");
   }

addnav("Zurück ins Freudenhaus","frdnhaus.php?op=alter");
addnav("Zurück zur Gasse","frdnhaus.php");
addnav("zum Jackson Square","village.php");
}


if ($_GET['op']=="jute"){
if ($session[user][gold]<100){
output("`rDu glaubst doch nicht etwa, dass du hier etwas geschenkt bekommst? Das kannst du dir das nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=100;
switch(e_rand(1,3)){
case 1:
output("`rShana reicht dir im Austausch gegen das Gold ein in bunten Stoff eingewickeltes Päckchen. Du schaust neugierig
hinein und entdeckst Nichts!. Da du dich nicht traust, diese Frau des Betruges zu bezichtigen, sagst du nichts dazu.");
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 2:
output("`rShana reicht dir im Austausch gegen das Gold ein in bunten Stoff gewickeltes Päckchen. Du blickst hinein und
entdeckst ein brandneues Jutesackkondom. Du regenerierst um ein paar Lebenspunkte.");
$session[user][hitpoints]+=15;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rShana reicht dir im Austausch gegen das Gold ein in bunten Stoff gewickeltes Päckchen. Du schaust hinein und
dir wird schlecht. Das Kondom war schon benutzt worden. BÄH' Es ist feucht und glitschig glänzend. Du verlierst ein paar
Lebenspunkte.");
$session[user][hitpoints]*=0.90;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }}}
if($_GET['op']=="maus"){
if($session[user][gold]<150){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=150;
switch(e_rand(1,3)){
case 1:
output("`rDu gibst Shana dein Gold, woraufhin sie dir ein in bunten Stoff gewickeltes Päckchen aushändigt. Du drehst es
einmal in deiner Hand und linst vorsichtig hinein. Deine Miene hellt sich bei dem Anblick des Mäusedarmkondoms auf, du
wirkst ein wenig fitter - deine Lebenspunkte sind vollständig
aufgefüllt.");
$session[user][hitpoints]=$maxhp;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 2:
output("`rDu gibst Shana dein Gold, woraufhin sie  dir ein in bunten Stoff gewickeltes Päckchen aushändigt. Du greifst
hinein und spürst etwas feuchtes, glitschiges an deinen Fingern. Ob das Kondom schon benutzt wurde? Dir ist nun so
schlecht, dass du einen Großteil deiner Lebenspunkte verlierst.");
$session[user][hitpoints]*=0.25;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rDu gibst Shana dein Gold, woraufhin sie dir ein in bunten Stoff gewickeltes Päckchen aushändigt. Du blickst in
das Päckchen und findest kein Kondom. Du grummelst zwar ein wenig, aber das bezaubernde Lächeln Shanas lässt dich
vergessen, dass du wütend bist. Dass du trotzdem wütend wurdest, verursacht, dass du dich so elend fühlst, dass du Waldkämpfe
verlierst.");
$session[user][turns]-=4;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
if($_GET['op']=="schaf"){
if($session[user][gold]<350){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=350;
switch(e_rand(1,4)){
case 1:
output("`rDu übergibst Shana die das gewünschte Gold und sie schnippt dir ein in bunten Stoff gewickeltes Päckchen zu,
welches du geschickt auffängst. Du nutzt deinen
Dolch, um den Stoff von dem Päckchen zu lösen, um dir das brandneue Schafsdarmkondom anzuschauen. Als du es 'erste' Mal
benutzt, steigt ein tolles Gefühl in dir hoch, du fühlst dich gut!");
$session['bufflist']['kondom'] =
     array("name"=>"`rGutes Gefühl",
     "rounds"=>15,
     "wearoff"=>"`rDein gutes Gefühl verschwindet.",
     "atkmod"=>1.2,
     "roundmsg"=>"`rDu fühlst dich gut!",
     "activate"=>"offense");
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 2:
output("`rDu übergibst Shana die 350 Gold und sie schnippt dir ein in bunten Stoff gewickeltes Päckchen zu, welches du
geschickt auffängst. Du nutzt deinen Dolch, um den Stoff zu zweiteilen. Als du anfängst das Schafsdarmkondom zu
bestaunen, prickelt dein ganzer Körper in freudiger Erwartung und du spürst die Kraft, die nötig ist, um drei
weitere Monster zu erschlagen.");
$session[user][turns]+=3;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rDu übergibst Shana die 350 Gold und sie schnippt dir ein in bunten Stoff gewickeltes Päckchen zu, welches dir
zweimal knapp aus der Hand flutscht, bevor du es richtig zu fassen bekommst. Die Sukkubus betrachtet dich spöttisch,
während du den Stoff mit deinem Dolch nervös zwei Teile teilst. Du murrst ein wenig, als du das Schmunzeln Shanas
bemerkst. Dann drehst du dich auf dem Absatz um und verschwindest aus dem Geschäft. Aber da du so ungeschickt bist,
rutschst du auf einer offenen Gleitcremedose aus und blamierst dich bis auf die Knochen. Du wirst wohl mit dieser
Peinlichkeit leben müssen, zumindest für eine gewisse Zeit. Aufgrund deines Missgeschickes verlierst
du außerdem einen Charmepunkt.");
$session['bufflist']['kondom'] =
     array("name"=>"`rPeinlichkeit",
     "rounds"=>10,
     "wearoff"=>"`rDas peinliche Gefühl verschwindet.",
     "defmod"=>0.8,
     "roundmsg"=>"`rDu fühlst dich etwas stärker.",
     "activate"=>"offense");
$session[user][charm]--;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 4:
output("`rDu übergibst Shana die 350 Gold und sie schnippt  dir ein in bunten Stoff gewickeltes Päckchen zu, welches du
auch geschickt fängst. Allerdings ist der Stoff nur lose um das Päckchen befestigt und dein Schafdarmkondom fällt auf
den Boden. Dröhnendes Gelächter hallt durch das Freudenhaus, weil deine Blamage sofort die Runde macht und du
verschwindest recht kleinlaut. Du hast durch dein tollpatschiges Verhalten ein wenig an Ansehen verloren.");
$session[user][reputation]-=20;
$session[user][kondom]+=1;
break;
  }
 }
}
if($_GET['op']=="rind"){
if($session[user][gold]<800){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=800;
switch(e_rand(1,3)){
case 1:
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie dir ein großes in bunten Stoff gewickeltes
Päckchen herüberreicht.Du betrachtest das Päckchen neugierig und öffnest es. Eine grünliche Gaswolke steigt aus dem
Päckchen auf. Nicht lange dauert es, dann kippst du um. Ein leises melodisches, aber auch höhnisches Lachen ist das
Letzte, was du vernimmst, bevor du ohnmächtig wirst. Du wachst nach Stunden wieder auf und bemerkst, dass dir 10
Edelsteine gestohlen wurden! Du fühlst dich darüber hinaus noch sehr schwach und solltest einen Heiler aufsuchen, bevor
dich ein Monster tötet.");
$session[user][gems]-=10;
$session[user][hitpoints]=1;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
addnews("`r{$session['user']['name']} wäre beinahe an einer grünen Gaswolke gestorben.");
break;

case 2:
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie dir ein großes in bunten Stoff gewickeltes
Päckchen herüberreicht. Du betrachtest das Päckchen neugierig, öffnest es aber eher zaghaft. Sofort durchströmt dich ein
tolles Gefühl, weil du in freudiger Erwartung  bist, das Rinddarmkondom anzuwenden. Du fühlst dich unbesiegbar STARK!
Zudem spürst du die Kraft für einen weiteren Waldkampf.");
$session['bufflist']['kondom'] =
     array("name"=>"`rTolles Gefühl",
     "rounds"=>20,
     "wearoff"=>"`rDu fühlst dich wieder normal.",
     "atkmod"=>1.2,
     "roundmsg"=>"`rDu fühlst dich etwas stärker.",
     "activate"=>"offense");

$session[user][turns]+=1;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
$turns = e_rand(2,7);
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie  dir ein grosses in bunten Stoff gewickeltes
Päckchen herüberreicht.Du öffnest erst nach sorgfältiger Untersuchung das Päckchen, schmeisst es allerdings sofort 
wieder auf die Ladentheke. Dieses Rinddarmkondom hat so ekelhaft gerochen, dass du dich jetzt erstmal eine ganze Weile 
ausruhen musst.");
$session[user][turns]-=$turns;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
if($_GET['op']=="hengst"){
if($session[user][gold]<1000){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=1000;

switch(e_rand(1,4)){

case 1:
$turns = e_rand(1,3);
output("`rDu überreichst Shana das Gold, welches sie von dir verlangt, bevor sie dir ein in bunten Stoff gewickeltes
Päckchen über den Tisch schiebt. Du reisst neugierig den Stoff von dem Kästchen und als du es öffnest, bekommst du ganz
grosse Augen. Deine Wangen röten sich, aber du fühlst dich stark genug, um noch ein paar Monster zu töten.");
$session[user][turns]+=$turns;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;

case 2:
$gold = e_rand(250,500);
output("`rDu überreichst Shana das Gold, welches sie von dir verlangt, bevor sie dir ein in bunten Stoff gewickeltes
Päckchen über den Tisch schiebt. Dich auf den süßen Moment, wo du das Hengstdarmkondom ausprobieren wirst, freuend, packst
du das Kondom sofort aus. Doch der unerwartet extrem säuerliche Geruch überwältigt dich und lässt dich zurücktaumeln.
Noch während du darum kämpfst, deine Besinnung nicht zu verlieren, spürst du, wie sich jemand an deinem Geldbeutel zu
schaffen macht. Als du wieder klar sehen kannst, ist jedoch niemand mehr anwesend. Als du später dein Gold zählst,
bemerkst du, dass dir jemand $gold Gold gestohlen hat!");
$session['bufflist']['kondom'] =
     array("name"=>"`rSehr säuerlicher Geruch",
     "rounds"=>25,
     "wearoff"=>"`rDer Geruch verschwindet.",
     "defmod"=>0.75,
     "roundmsg"=>"`rDer Geruch lässt deine Augen brennen.",
     "activate"=>"offense");

$session[user][gold]-=$gold;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;

case 3:
output("`rDu überreichst Shana das Gold, welches sie von dir verlangt, bevor sie dir ein großes in bunten Stoff
gewickeltes Päckchen herüberreicht. In freudiger Erwartung reisst du den Stoff von dem Kästchen und genießt die
Vorfreude auf die Benutzung des Hengstdarmkondomes. Du spürst, wie dich der Adrenalinschub der Erwartung  restistenter
gegen Angriffe macht. Außerdem erhälst du genug Energie für einen weiteren Waldkampf. ");
$session['bufflist']['kondom'] =
     array("name"=>"`rAdrenalinschub",
     "rounds"=>25,
     "wearoff"=>"`rDein Adrenalinschub verschwindet.",
     "defmod"=>1.25,
     "roundmsg"=>"`rDu bist resistenter gegen Angriffe, da dein Adrenalin dich voran treibt.",
     "activate"=>"offense");

$session[user][turns]+=1;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;

case 4:
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie  dir eine ein in bunten Stoff gewickeltes
Päckchen herüberreicht. Skeptisch nimmst du das Päckchen entgegen und betrachtest das Hengstdarmkondom, nachdem du es
ausgepackt hast. Da dir die ungewöhnlich schimmelig, grünliche  Farbe des Kondoms sonderbar vorkommt, gibst du es wieder
zurück. Durch den Streit, den du dadurch mit Shana anzettelst, verlierst du die Zeit für einen Waldkampf.");
$session[user][turns]--;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
if($_GET['op']=="lamm"){
if($session[user][gold]<1300){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=1300;
switch(e_rand(1,3)){
case 1:
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie ein in bunten Stoff gewickeltes Päckchen auf
den Tresen legt. In dem Moment, als du das Päckchen öffnest, springt dich eine durchgedrehte Springmaus an und beißt
dich in die Nase. Reflexartig schnaufst du wie eine Dampflok und schüttelst das Untier wieder ab, jedoch kostet dich der
Biss einige deiner Lebenspunkte.");
$session[user][hitpoints]-=30;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 2:
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie ein in bunten Stoff gewickeltes Päckchen auf
den Tresen legt. Freudig nimmst du das Päckchen und machst dich auf den Weg zu deiner Verabredung. Dank der köstlichen
Vorfreude wirst du gleich munterer und erhälst mehr Energie zum 'Kämpfen'. ");
$session[user][hitpoints]+=30;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rDu überreichst Shana das Gold, welches sie verlangt, bevor sie dir ein in bunten Stoff gewickeltes Päckchen
auf den Tresen legt. Etwas enttäuscht darüber, dass Shana nur ein Lammdarmkondom in das Päckchen gepackt hat obwohl du
fünf bestellt hast. Trotzdem nimmst du dieses und packst es missmutig aus. Ob deiner Enttäuschung bemerkst du nicht, dass
es schon gebraucht ist. Erst als du es anwenden willst, merkst du dies und kommst wieder zurück, um es umzutauschen. Der
Laden hat aber schon geschlossen. Dies kostet dich einige Waldkämpfe.");
$session[user][turns]-=5;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
if($_GET['op']=="dino"){
if($session[user][gold]<1350){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=1350;
switch(e_rand(1,3)){
case 1:
output("`rDu überreichst Shana die Goldsumme, welches sie dir nannte, bevor sie unter Anstrengung ein wirklich riesiges
Dinokondom auf den Tresen legt. Neugierig beobachtest du sie, wie sie das fremdartig und wirklich monströs grosse
Kondom ausbreitet. Als du dann jedoch den widerwärtigen Gestank des schimmeligen Kondoms wahrnimmst, wird dir schwarz
vor Augen. Erst mehrere Stunden später erwachst du wieder und... hörst Ramius dich auslachen. Du bist TOT!");
$session[user][alive]=false;
$session[user][hitpoints]=0;
addnews("`rDa hat sich wohl einer überschätzt. {$session['user']['name']} ist der tote Beweis dafür, dass
es nicht immer bei allem auf die Grösse ankommt!!!");
addnav("News","news.php");
break;
case 2:
output("`rDu überreichst Shana die Goldsumme, welche sie dir nannte, bevor sie unter grösster Anstrengung ein wirklich
riesiges Dinokondom auf den Tresen legt. Mit einer geschickten Bewegung öffnet der Händler die Schale. Leicht
angewiedert, ob des Gestankes der Kondom, wagst du es aber trotzdem, ein Stück davon zu kosten. Überraschenderweise
nimmst du einen karamellartigen Geschmack wahr.Außerdem werden in dir neue Kräfte erweckt, die dir einen
Lebenspunkteschub verleihen.");
$session[user][hitpoints]+=100;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rDu überreichst Shana die Goldsumme, welche sie dir nannte, bevor unter grösster Anstrengung ein wirklich
riesiges Dinokondom auf den Tresen legt. Die Sukubus rollt das monströse Kondom zusammen und reicht es dir. Knallrot
nimmst du es entgeben, stockst aber, als du merkst, dass es fast schon zu schwer für dich ist. Nur mit grösster Mühe trägst
du es davon. Mit schmerzverzerrtem Gesicht ziehst du dich zurück. Wegen deiner Schwäche kannst du heute nicht mehr
kämpfen. Du hast glatt die Zwerge hinter dem Tresen übersehen, die Shana bei der schweren Last geholfen haben.");
$session[user][turns]=0;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
if($_GET['op']=="knochen"){
if($session[user][gold]<1700){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=1700;
switch(e_rand(1,3)){
case 1:
output("`rDu überreichst Shana das abgezählte Gold, welches sie haben möchte, bevor sie ein kunstvolles Gebilde von
einem Hähnchenknochen verstärkten Kondom für Knickschnibbel den Tresen legt. Das seltsame Kondom, dass dich an eine
Nachbildung des Turm der Elemente zu erinnern scheint, betrachtend, nimmst du dieses vom Tisch und verlässt den Laden
wieder. Als du dann auf der Straße mit einem nackten Phönix, der verwirrt über den Dorfplatz wetzt, zusammen stösst,
schaust du wütend auf das Kondom in deiner Hand hinab und zerdrückst dieses aus Versehen. Jedoch der Phönix nimmt dir
übel, dass du ein Knochengerüst von einem Vogel herum trägst und pickt dir so dolle in deinen Po, dass du
ein paar Waldkämpfe verschwndest, um zu dem Heiler zu kommen.");
$session[user][turns]-=10;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;

case 2:
output("`rDu überreichst Shana das abgezählte Gold, welches sie haben möchte, bevor sie ein kunstvolles Gebilde von
einem Hähnchenknochen verstärkten Kondom für Knickschnibbel den Tresen legt. Der Adrenalinkick, welcher die Aussicht auf
das Verwenden des Kondoms bei dir ausgelöst wird, verleiht dir sofort mehr Kraft. 'Kampfeslustig' schulterst du dein
Päckchen mit dem bunten Stoff drumherum und verlässt das Kondomlädchen mit einem glücklichen Lächeln.");
$session['bufflist']['kondom'] =
     array("name"=>"`rAdrenalinkick",
     "rounds"=>35,
     "wearoff"=>"`rDeine Energie schwindet.",
     "atkmod"=>1.50,
     "roundmsg"=>"`rDein Adrenalinspiegel ist erhöht und du kannst fester zuschlagen.",
     "activate"=>"offense");

$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rDu überreichst Shana das abgezählte Gold, welches sie haben möchte, bevor sie ein kunstvolles Gebilde von
einem Hähnchenknochen verstärkten Kondom für Knickschnibbel den Tresen legt. Die eigenartige Optik des Kondoms macht dich
neugierig, so dass du ohne großartig zu überlegen das Stück gleich ganz in deine Tasche stopfst. In dem selben Moment
stürmt eine betrunkene Horde Orks in den Laden und erschreckt dich so sehr, dass du fast an deinem Speichel, der dir im
Mund zusammen gelaufen war, erstickst. Im letzten Moment kannst du den Hustenanfall beenden, bevor die Orks sich
beleidigt fühlen, jedoch bist dadurch so geschwächt, dass du fast deine gesamten Lebenspunkte verlierst.");
$session[user][hitpoints]=1;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
if($_GET['op']=="aal"){
if($session[user][gold]<2500){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=2500;
switch(e_rand(1,4)){
case 1:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein Zitteraalkondom auf den Tresen
legt. Alleine bei dem Anblick des zitternd vibrierenden Kondoms läuft dir schon das Wasser im Munde zusammen und
dementsprechend schnell schnappst du dir das in bunte Stoffe gewickelte Kondom. Durch die freudige Erwartung fühlst du
dich gleich fitter und könntest heute ein paar 'Monster' mehr erlegen.");
$session[user][turns]+=13;
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 2:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein Zitteraalkondom auf den Tresen
legt. Das innovative Kondom, welches auf dem Tresen zitternd vibriert, schnappst du dir so schnell wie es von Shana auf
den Tresen gelegt wurde. Die Freude über das neu erstandene Kondom macht dich sofort munterer und wachsamer.");
$session['bufflist']['kondom'] =
     array("name"=>"`rWachsamkeit",
     "rounds"=>40,
     "wearoff"=>"`rDeine Wachsamkeit lässt nach.",
     "defmod"=>1.60,
     "roundmsg"=>"`rDeine Freude macht dich wachsamer; dich kann nicht mehr überraschen.",
     "activate"=>"offense");
$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 3:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein Zitteraalkondom auf den Tresen
legt. Verdutzt schaust du das zitternde, vibrierende auf dem Tresen zappelnde Kondom an, das vor dir liegt.
Letztendlich, nachdem du das Drängen Shanas nicht mehr ertragen kannst, nimmst du deinen ganzen Mut zusammen und das
Zitteraalkondom an dich. Jedoch ist dieses so flutschig und schwer zu halten, dass es wie ein neuer Tanz aussieht, wie du
mit dem Kondom heim gehst und dir deine Kräfte raubt.");
$session['bufflist']['kondom'] =
     array("name"=>"`rUngeschicklichkeit",
     "rounds"=>40,
     "wearoff"=>"`rDu lässt das Zitteraalkondom fallen und es zappelt zitternd auf dem Boden weiter.",
     "defmod"=>0.40,
     "atkmod"=>0.40,
     "roundmsg"=>"`rDas Zitteraalkondom ist zu schwer für dich zu halten!",
     "activate"=>"offense");

$session[user][kondom]+=1;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 4:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein Zitteraalkondom auf den Tresen
legt. Als du das zitternd vibrierende Kondom erblickt, fliehst du panikartig aus dem Laden. Das Ding, was Shana dir auf
den Tresen gelegt hat, sieht dir zu lebendig für seinen Zweck aus. Außer Atem bleibst du hinter der nächsten Häuserecke
stehen und beobachtest die Straße. Du hast dergleichen noch nie gesehen und bist nun so verängstigt, dass du heute
sicherlich nicht mehr kämpfen wirst.");
$session[user][turns]-=13;
$session[user][kondom]+=1;
addnav("Blos weg hier","village.php");
addnews("{$session['user']['name']} hat Angst vor zappelnden Zitteraalen.");
break;
  }
 }
}
if($_GET['op']=="drache"){
if($session[user][gold]<3800){
output("`rDu glaubst doch nicht etwa, dass du hier was geschenkt bekommst? Das kannst du dir nicht leisten!");
addnav("Kondomladen","kondom.php");
}else{
$session[user][gold]-=3800;
switch(e_rand(1,4)){
case 1:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein rotglühendes Drachendarmkondom
auf den Tresen legt. Als du das feurige Kondom erblickt, fliehst du panikartig aus dem Laden. Das Ding, was Shana dir
auf den Tresen gelegt hat, sieht dir zu heiss für seinen Zweck aus. Außer Atem bleibst du hinter einem dicken Baum im
Wald erst stehen und versuchst, keuchend zu Atem zu kommen. Du hast dergleichen noch nie gesehen und bist nun so
verängstigt, dass du heute sicherlich nicht mehr kämpfen wirst.");
$session[user][turns]=0;
$session[user][kondom]=5;
addnav("Blos weg hier","forest.php");
addnews(" `r {$session['user']['name']} hat Angst vor feurigen Dingen.");
break;
case 2:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein rotglühendes Drachendarmkondom auf den Tresen legt und dieses dann gekonnt präsentiert. Neugierig betrachtest du dieses feurige Kondom. Das grosse
glühende Kondom kommt dir zwar seltsam vor, jedoch willst du es trotzdem probieren. Seltsamerweise scheint das Kondom
nicht wirklich heiss zu sein und du hebst es hoch. Als du dann nach einigen Augenblicken deine Haut betrachtest, hat
diese die Farbe der Drachenkondom angenommen. Panikartig schaust du zu Shana auf, welche dann auch die Letzte ist, die
du für heute siehst.");
$session[user][alive]=false;
$session[user][hitpoints]=0;
$session[user][kondom]=5;
addnav("News","news.php");
addnews("{$session['user']['name']} ist an einem feurigen Drachending gestorben.");
break;
case 3:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein rotglühendes Drachendarmkondom auf den Tresen legt. Fasziniert starrst du das feurige Kondom an. Mit jedem Augenblick, wo du es anschaust, scheinst du
kräftiger zu werden und immer, wenn du es mit deinem Zeigefinger anstupst, wird deine Aufmerksamkeit gesteigert. Als du
dich entschieden hast, es zu kaufen, fühlst du dich so energiegeladen, dass dir heute nichts etwas anhaben kann. ");
$session['bufflist']['frhandel'] =
     array("name"=>"`rUnglaubliche Energie",
     "rounds"=>40,
     "wearoff"=>"`rDu fühlst dich wieder normal.",
     "defmod"=>3.00,
     "atkmod"=>3.00,
     "roundmsg"=>"`rDu fühlst dich unbesiegbar!",
     "activate"=>"offense");
$session[user][hitpoints]+=400;
$session[user][kondom]=5;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
case 4:
output("`rDu überreichst Shana das gewünschte Gold, welches sie verlangt, bevor sie ein rotglühendes Drachendarmkondom auf den Tresen legt. Da du solch ein besonderes Kondom zuvor noch nie gesehen hast, bist du dir nicht sicher, ob du dieses nutzen sollst. Wagemutig nimmst du das Kondom in die Hand und blickst es noch einmal erwartungsvoll an. Nun nimmst du jedoch einen bitteren Geruch wahr, der dir die Nasenschleimhaut zu verätzen scheint. Der bittere Geruch legt sich über deine Geruchsnerven und verwirrt dein Denken. Bevor du wieder in den Wald gehst, besorgst du dir in der Schenke noch ein Ale, um den ekligen Geruch loszuwerden, was jedoch kaum etwas bringt.");
$session['bufflist']['kondom'] =
     array("name"=>"`rEkelhafter Geruch",
     "rounds"=>40,
     "wearoff"=>"`rDer Geruch verschwindet.",
     "defmod"=>0.20,
     "atkmod"=>0.20,
     "roundmsg"=>"`rDu musst fast brechen ob des ekelhaftes Geruchs in deiner Nase!",
     "activate"=>"offense");
$session[user][turns]-=20;
addnav("Zurück ins Freudenhaus","frdnhaus.php");
break;
  }
 }
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`#Midgar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

