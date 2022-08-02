<?php
// Tabellen in accounts schule und schulef                                  //
require_once "common.php";

page_header("Berufsvermittler Büro");


output("`v`c`bBerufsvermittler Büro`b`c`5Du betrittst das große Büro. Überall stehen Stapel von Papier, so dass der Raum einen alten Eindruck hinterlässt.`n
Nachdem du dich durch die Schränke, Stapel von Papier durchgezwängt hast, kommst du zu einem Schreibtisch mit einer Angestelltin.`n
`&Guten Tag! Was kann ich für sie tun?`5 bevor du überhaupt antworten kannst, geschweige deinen Namen zu nennen reicht dir die Frau einen Stapel voll Papier. `&Durchlesen und ausfüllen! `5Du erkennst das Namensschild an ihrer Brust. Frau Tippfinger. Das Passt zu ihr denkst du dir und setzt dich in einer freien Ecke.
Nach guten drei Stunden bekommst du Kopfschmerzen. Du wirfst die Hälfte der Zettel weg und siehst dir die Stellenangebote an. Deswegen bist du ja gekommen.`n`n

`8`b`cBerufe:
Golgas Schmiede: Radmacher, Schmied, Kunstschmied`n
Haus der Kunst: Gaukler, Barde, Tänzer, Geschichtenerzähler, Schauspieler, Redner,Philosoph`n
Schule: Lehrer`n
Weiterbildung: Ausbilder`n
Krankenhaus: Heiler, Pfleger, Zahnausreisser`n
Aeris Blumenladen: Gärtner, Floristin/Florist`n
Santos Krämerladen: Krämer(Händler), Kerzenzieher`n
Bar: Wirt, Schankmaid/Schankknecht`n
Rathaus: Buchbinder, Schreiber, Rechtsgelehrter, Bote, Richter, Kartograph`n`n`b`5

Du überlegt lange und gehst dann wieder zur Frau Tippfinger.`c
");
if($session['user']['jobid'] ==0){
if($session['user']['schulef'] ==1){                                                                                  addnav("`&Schmied","ausbildung.php?op=eins");
addnav("`&Pfleger","ausbildung.php?op=zwei");
addnav("`&Hebamme","ausbildung.php?op=drei");
addnav("`&Schankknecht","ausbildung.php?op=vier");
addnav("`&Schankmaid","ausbildung.php?op=fuenf");
addnav("`&Radmacher","ausbildung.php?op=sechs");
addnav("`&Florist","ausbildung.php?op=sieben");
addnav("`&Floristin","ausbildung.php?op=acht");
addnav("`&Krämer","ausbildung.php?op=neun");
addnav("`&Kerzenzieher","ausbildung.php?op=zehn");
addnav("`9Weiterbilden","weiterbildung.php");
}elseif ($session['user']['schulef'] ==2){
addnav("`&Schmied","ausbildung.php?op=eins");
addnav("`&Pfleger","ausbildung.php?op=zwei");
addnav("`&Hebamme","ausbildung.php?op=drei");
addnav("`&Schankknecht","ausbildung.php?op=vier");
addnav("`&Schankmaid","ausbildung.php?op=fuenf");
addnav("`&Radmacher","ausbildung.php?op=sechs");
addnav("`&Florist","ausbildung.php?op=sieben");
addnav("`&Floristin","ausbildung.php?op=acht");
addnav("`&Krämer","ausbildung.php?op=neun");
addnav("`&Kerzenzieher","ausbildung.php?op=zehn");
addnav("`6Gaukler","ausbildung.php?op=elf");
addnav("`6Barde","ausbildung.php?op=zwoelf");
addnav("`6Redner","ausbildung.php?op=dreizehn");
addnav("`6Wirt","ausbildung.php?op=vierzehn");
addnav("`6Bote","ausbildung.php?op=fuenfzehn");
addnav("`6Kunstschmied","ausbildung.php?op=sechzehn");
addnav("`6Heiler","ausbildung.php?op=siebzehn");
addnav("`6Gärtner","ausbildung.php?op=achtzehn");
addnav("`6Lehrer","ausbildung.php?op=neunzehn");
addnav("`6Zahnreisser","ausbildung.php?op=zwanzig");
addnav("`9Weiterbilden","weiterbildung.php");
}elseif ($session['user']['schulef'] ==3){
    addnav("`&Schmied","ausbildung.php?op=eins");
    addnav("`&Pfleger","ausbildung.php?op=zwei");
    addnav("`&Hebamme","ausbildung.php?op=drei");
    addnav("`&Schankknecht","ausbildung.php?op=vier");
    addnav("`&Schankmaid","ausbildung.php?op=fuenf");
    addnav("`&Radmacher","ausbildung.php?op=sechs");
    addnav("`&Florist","ausbildung.php?op=sieben");
    addnav("`&Floristin","ausbildung.php?op=acht");
    addnav("`&Krämer","ausbildung.php?op=neun");
    addnav("`&Kerzenzieher","ausbildung.php?op=zehn");
    addnav("`6Gaukler","ausbildung.php?op=elf");
    addnav("`6Barde","ausbildung.php?op=zwoelf");
    addnav("`6Redner","ausbildung.php?op=dreizehn");
    addnav("`6Wirt","ausbildung.php?op=vierzehn");
    addnav("`6Bote","ausbildung.php?op=fuenfzehn");
    addnav("`6Kunstschmied","ausbildung.php?op=sechzehn");
    addnav("`6Heiler","ausbildung.php?op=siebzehn");
    addnav("`6Gärtner","ausbildung.php?op=achtzehn");
    addnav("`6Lehrer","ausbildung.php?op=neunzehn");
    addnav("`6Zahnreisser","ausbildung.php?op=zwanzig");
    addnav("`2Tänzer","ausbildung.php?op=einundzwanzig");
    addnav("`2Geschichtenerzähler","ausbildung.php?op=zweiundzwanzig");
    addnav("`2Schreiber","ausbildung.php?op=dreiundzwanzig");
    addnav("`2Buchbinder","ausbildung.php?op=vierundzwanzig");
    addnav("`2Rechtsgelehrter","ausbildung.php?op=fünfundzwanzig");
    addnav("`2Philosoph","ausbildung.php?op=sechsundzwanzig");
    addnav("`2Schauspieler","ausbildung.php?op=siebenundzwanzig");
    addnav("`2Richter","ausbildung.php?op=achtundzwanzig");
    addnav("`2Kartograph","ausbildung.php?op=neunundzwanzig");
    addnav("`2Ausbilder","ausbildung.php?op=dreizig");
}
}elseif($session['user']['jobid'] >=1){
output("`9`n`n `c`bDu hast schon einen Job Kündige diesen erst`c`b`n");

}elseif($session['user']['schulef'] ==0){
output("`9`n`n `c`bDu hast noch keine Schulbildung melde dich erst in der Dorfschule`c`b`n");
}
if($session['user']['jobid'] ==30){
addnav("Arbeiten");
addnav("Schulungsraum","weiterbildung.php");
}

// Beginn Berufswahl
if ($_GET['op']=="eins"){
output("`&`n`n`bSie wollen Schmied werden?Sodann gehen sie zu Golga in die Schmiede . Der Freut sich über jede Hilfe die er bekommen kann.`3 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=1;
}
if ($_GET['op']=="zwei"){
output("`&`n`n`bEin Pfleger?Nun es gibt genug Verwundete im Krankenlager. Geht dort hin und helft diesen Armen Leuten `5 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=2;
$session['user']['pfleger']=1;
}
if ($_GET['op']=="drei"){
output("`&`n`n`bEine Hebamme?Nun es gibt genug Verwundete im Krankenlager. Geht dort hin und helft diesen Armen Leuten `5 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=3;
$session['user']['pfleger']=2;
}
if ($_GET['op']=="vier"){
output("`&`n`n`b Ihr wollt euch als Schankknecht Gold hinzuverdienen?Na dann arbeitet doch in der hiesigen Aloha Bar. Dort werden immer Hände benötigt.`3 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=4;
}
if ($_GET['op']=="fuenf"){
output("`&`n`n`b Ihr wollt euch als Schankmaid Gold hinzuverdienen?Na dann arbeitet doch in der hiesigen Aloha Bar. Dort werden immer Hände benötigt.`4 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=5;
}
if ($_GET['op']=="sechs"){
output("`&`n`n `bSie wollen also Radmacher werden?Nun dann melden Sie sich bitte beim Golga er hat Arbeit für sie.`2 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=6;
}
if ($_GET['op']=="sieben"){
output("`?`n`n`b Ach ein Florist?Nun Aeris ist gut, aber sie kommt mit der Arbeit nicht nach.Melden sie sich also bitte im Blumenladen den Ein zwei helfende Hände würden ihr gut tun`8 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=7;
}
if ($_GET['op']=="acht"){
output("`&`n`n`b Ach eine Floristin?Nun Aeris ist gut, aber sie kommt mit der Arbeit nicht nach.Melden sie sich also bitte im Blumenladen den Ein zwei helfende Hände würden ihr gut tun`9 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=8;
}
if ($_GET['op']=="neun"){
output("`&`n`n`b Ach noch so ein Krämer?Geht nach Milos Krimskrams. Noch so ein Halsabschneider…`1sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=9;
}
if ($_GET['op']=="zehn"){
output("`&`n`n`b So ihr versteht euch als Kerzenzieher?Nun es wird immer dunkel und Kerzen kann man nie genug haben. Milos verkauft Kerzen und stellt sie auch her. Bitte meldet euch bei ihm.`5 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=10;
}
if ($_GET['op']=="elf"){
output("`&`n`n `bSie wollen Gaukler werden?Gehen sie bitte ins Haus der Kunst in den Unterhaltungsraum und erfreuen sie die Allgemeinheit.`5sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=11;
}
if ($_GET['op']=="zwoelf"){
output("`&`n`n `bSie wollen Barde sein?Dann gehen sie ins Haus der Kunst in den Unterhaltungsraum und zeigen ihre himmlische Stimme der Menschheit.`6sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=12;
}
if ($_GET['op']=="dreizehn"){
output("`&`n`n`bIhr seid also ein Redner? Nun das Haus der Kunst stellt Redner diese treffen sich im Raum der Unterhaltung. Vielleicht meldet ihr euch da.`2sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=13;
}
if ($_GET['op']=="vierzehn"){
output("`&`n`n`b Ihr wollt also als Wirt abreiten?Nun es ist Hochsaison in der Aloha Bar. Man braucht einen hinter der Schankbar.`2 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=14;
}
if ($_GET['op']=="fuenfzehn"){
output("`&`n`n`bIhr seit Schnell zu Fuß und kennt euch gut in der Stadt aus?Ja Boten werden Hauptsächlich in der Schreibstube bei Madame Toullere gebraucht,diese findet ihr im Rathaus.`2  sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=15;
}
if ($_GET['op']=="sechzehn"){
output("`&`n`n`b Sie wollen Kunstschmied sein? Nun das wird wohl Golga entscheiden. Gegen sie bitte zu ihm.`4sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=16;
}
if ($_GET['op']=="siebzehn"){
output("`&`n`n`b Ihr seid ein Heiler?So was können wir gut gebrauchen. Der Wald wird immer gefährlicher. Bitte meldet euch im Krankenlager`4 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=17;
}
if ($_GET['op']=="achtzehn"){
output("`&`n`n`b Ihr habt also den Grünen Daumen?Die Gärten sind hier meist überwuchert. Ist zwar Knochenarbeit aber bei Aeris werdet ihr Aufgaben finden.`7 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=18;
}
if ($_GET['op']=="neunzehn"){
output("`&`n`n`bIhr unterrichtet also?Die Schule kann jeden Lehrer gebrauchen. Bei diesen Kinder heutzutage.`3sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=19;
}
if ($_GET['op']=="zwanzig"){
output("`&`n`n`b Zahnausreisser sagt ihr seid ihr?Ich hörte hier sollten die Leute sich nicht gut um ihre Zähne kümmern. Ich glaube im Krankenlager findet ihr arbeit .`6 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=20;
}
if ($_GET['op']=="einundzwanzig"){
output("`&`n`n`b Ihr seid also ein Tänzer?So tanz von nun an im Haus der Kunst im Tanzsaal.`7sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=21;
}
if ($_GET['op']=="zweiundzwanzig"){
output("`&`n`n`b Geschichtenerzähler interessiert euch?Es wird nach spannenden Geschichten verlangt. Geht sofort ins Haus der Kunst in den Raum der Unterhaltung.`8sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=22;
}
if ($_GET['op']=="dreiundzwanzig"){
output("`&`n`n`b Ihr seid in der Schrift gelehrt?Dann arbeitet doch im Ratshaus. Dort werden eifrige Schreiber benötigt Meldet euch bei Madame Toullere in der Schreibstube .`8 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=23;
}
if ($_GET['op']=="vierundzwanzig"){
output("`&`n`n`b Ihr versteht euch in der Kunst des Buchbindens?In dem Ratshaus gibt es alte Bücher die ein neues Band dringend benötigen.`3 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=24;
}
if ($_GET['op']=="fünfundzwanzig"){
output("`&`n`n`b Ihr kennt euch in Rechtssprechung aus?Rechtsgelehrte werden jeden Tag im Gericht benötigt. Versucht da euer Verhandlungsgeschick.`9 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=25;
}
if ($_GET['op']=="sechsundzwanzig"){
output("`&`n`n`b Ihr redet gerne über die Götter und die Welt?Wieso seid ihr dann nicht als Philosoph im Haus der Kunst? Dort wird nur philosophiert.`1 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=26;
}
if ($_GET['op']=="siebenundzwanzig"){
output("`&`n`n`b Ihr seid also Schauspieler?Ihm Haus der Kunst werden Theaterstücke geprobt. Wieso versucht ihr euch nicht da?`9sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=27;
}
if ($_GET['op']=="achtundzwanzig"){
output("`&`n`n`b Ihr habt Jura studiert und sprecht gerne Gesetze aus?Der Richter ist immer im Gericht zu finden. Sprecht dort über Recht und Unrecht.`5 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=28;
}
if ($_GET['op']=="neunundzwanzig"){
output("`&`n`n`b Ihr könnt gut euch was merken und fein Arbeiten?Kartographen werden im Ratshaus benötig. Das Dorf wächst so schnell. Es gibt keine Karten mehr fürs Dorf.`6 sagt Frau Tippfinger zu dir und reicht dir einen Zettel mit der Wegbeschreibung`b`n");
$session['user']['jobid']=29;
}
if ($_GET['op']=="dreizig"){
output("`9`n`n`b Ihr könnt gut mit Leuten umgehen und sie weiterbilden ?Ausbilder suchen wir meldet euch im Ausbildungsbereich.`7 sagt Frau Tippfinger zu dir und zeigt dir den Weg`b`n");
$session['user']['jobid']=30;
}

addnav("`&Schule","schule.php");
addnav("`9Weiterbilden","weiterbildung.php");
//addnav("`&Kuenstler","ausbildung.php?op=eins");

addnav("`9Zurück","village.php");
page_footer();
?> 