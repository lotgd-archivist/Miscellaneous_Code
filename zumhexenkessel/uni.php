<?php

/*
Berufe Script (0.3v)
by Savomas & Gimmick (a.k.a.Tiresias)
http://www.ocasia.de
------------------------------
Dies ist die Universität
------------------------------
0.1v 
    -Ausbildung Beginnen möglich
    -Lehrräume betreten möglich
    -Commentary hinzugefügt
    -Bugs gefunden

0.2v     
    -Bugs behoben
    -Lektionen machen möglich
    -Campus (Addon-Schnittstelle eingefügt)

0.3v
    -Texte verbessert
    -Schönheitsfehler ausgemerkelt
    -Grob Kommentiert
------------------------------
THX an Kevz (für seine sql-Hilfe-hotline *lol*)
*/

require_once "common.php";
addcommentary();
page_header("Universität");
if ($HTTP_GET_VARS['op']==""){
addnav("Zurück zum Dorf","village.php");
if (@file_exists("campus.php")) addnav("Campus","campus.php"); 
if ($session['user']['aubid']==0) { addnav("`5Mittlere Reife machen (5000 Gold)","uni.php?op=mit"); }
if ($session['user']['aubid']==1) { addnav("`6Abitur machen (15000 Gold)","uni.php?op=abi"); }
if ($session['user']['aubid']==2) { addnav("`@Studieren (20000 Gold)","uni.php?op=stu"); }
if ($session['user']['aubid']==1 && $session[user][lektion]<400) { addnav("`5Zum Unterricht","uni.php?op=aub1"); }
if ($session['user']['aubid']==2 && $session[user][lektion]<400) { addnav("`6Zur Bibliothek","uni.php?op=aub2"); }
if ($session['user']['aubid']==3 && $session[user][lektion]<400) { addnav("`@In den Hörsaal","uni.php?op=aub3"); }
output("`c<font size='+2'>`8Die Universität</font>`c`n",true);
if ($session['user']['aubid']==3){
output ("`0 Du betrittst das alte Universitäts-Gelände, doch irgendwie scheint es so ausgestorben, schnell beschließt du umzudrehen, hier halten dich keine `$ `b 10 Ochsen `b `0 mehr....");
} else {
output("Du bettritst die `8Universität`0das weitaus grösste Gebäude weitundbreit..`n");
output("sofort als du deinen Fuß über die schwelle des Eingangsbogen der grossen Tür tust`n");    
output("kommt dir ein kahlköpfiger überarbeitet wirkender Proffesor entgengen:ähm...was kann ich für sie tun? ");
output("sagt er und deutet auf eine gigantische Tafel..`n`n`n`n`n`n`n`n");
output("`5Die Mittlere Reife;eine sehr günstige Option besonders für Leute mit etwas leichterer Brieftasche`n ");
output("außerdem können sie nach dem Abschluss dieser Option ihr Abitur machen.......`n`n`n`n`n`n`n`n");
output("`6Das Abitur; Unglaublich wichtig...nach dem abschluss des Abiturs können sie auf jeden Fall `n");
output("einen relativ guten Beruf ausüben.`n`n`n`n`n`n`n`n");
output("`@Das Studium-nachdem sie Studiert haben ist es ihnen gestattet JEDEN Beruf auszuüben..");
output("selbst an hohe Berufe wie Anwalt etc. können sie nach dem Studium ihre Berwerbung schicken!!!!!");
}
}
//Der Spieler wird in die Mittlere Reife eingeschrieben
if ($HTTP_GET_VARS['op']=="mit"){ 
if ($session[user][gold]>=5000){
addnav ("Zurück","uni.php");
addnav ("Weiter","uni.php?op=aub1");
output ("Der kleine Professor führt dich zu einem kleinen Büro in dem ein noch kleinerer Gnom, an einem fast schon winzigen Stuhl, sitzt. Du kommst dir vor, als wärst du im Zwergenreich, doch dann fragt der Gnom: '`& Was kann ich für dich tun?' `0, da antwortest du ihm stolz, du wollest die Mittlere Reife machen,");
$session['user']['lektion']=0;
$session['user']['aubid']=1;
$session['user']['jobname']="Schüler";
$session['user']['gold']-=5000;
} else {
output ("`&'Du brauchst mehr Gold um die Mittlere Reife zu erreichen!'");
addnav("Zurück","uni.php");
}
}
//Der Spieler wird in das Abitur Eingeschrieben
if ($HTTP_GET_VARS['op']=="abi"){
if ($session[user][gold]<15000){
output("Du brauchst mehr Geld um Abi zu machen..");
addnav ("Zurück","uni.php");
} else {
if ($session[user][aubid]==1 && $session[user][lektion]>=400){
output("So nun kannst du das Abi machen geh einfach in die Biblothek und Lerne dort ...sobald du 400 lektionen gelernt hast bist du mit dem Abi fertig..");
$session['user']['lektion']=0;
$session['user']['aubid']=2;
$session['user']['jobname']="Abiturient";
$session['user']['gold']-=15000;
addnav ("Weiter","uni.php?op=aub2");
} else { 
output("Du hast die Mittlere Reife noch nicht abgeschlossen!?");
addnav("Zurück","uni.php");
}
}
}
//Der Spieler wird ins Studium eingeschrieben
if ($HTTP_GET_VARS['op']=="stu"){
if ($session[user][gold]<20000){
output("Du brauchst mehr Geld um zu Studieren");
addnav ("Zurück","uni.php");
} else {
if ($session[user][aubid]==2 && $session[user][lektion]==400){
output("So nun kannst du Studieren geh dazu einfach in den Hörsaal und lerne ... sobald du 400 lektionen gelernt hast bist du mit dem Studieren fertig..");
$session['user']['lektion']=0;
$session['user']['aubid']=3;
$session['user']['jobname']="Student";
$session['user']['gold']-=20000;
addnav ("Weiter","uni.php?op=aub3");
} else { 
output("Du hast noch kein Abi!");
addnav("Zurück","uni.php");
}
}
}
// Der Unterricht für die Mittlere Reife
if ($HTTP_GET_VARS['op']=="aub1"){
if ($session[user]['lektion']>=400){
output("Du hast die Mittlere Reife gemacht!");
addnav("Zurück zur Universität","uni.php");
} else {
addnav ("Den Unterricht verlassen","uni.php");
addnav ("`6 1 Lektion lernen `%( 2 Runden)","uni.php?op=la");
addnav ("`6 2 Lektionen lernen `%( 4 Runden)","uni.php?op=4");
addnav ("`6 5 Lektionen lernen `%( 10 Runden)","uni.php?op=ipa");
output ("Du betrittst ehrfürchtig den kleinen Raum neben der Bibliothek und dem Hörsaal. Du guckst dich kurz um, betrachtest die bunten Bilder an den Wänden und die komischen Gestalten die gerade nach und nach in den Raum kommen, um dem Unterricht zu folgen.");
viewcommentary("uni1","Stören",25);
}
}
//Die Lektions Varianten der Mittleren Reife
//1 Lektion lernen
if ($HTTP_GET_VARS['op']=="la"){
if ($session[user][turns]>=2){
output("Du hast eine Lektion gelernt mein Junge..gute Arbeit!");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=1;
$session[user][turns]-=2;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
//2 Lektionen lernen
if ($HTTP_GET_VARS['op']=="4"){
if ($session[user][turns]>=4){
output("Du hast zwei Lektionen gelernt.. gut,gut..");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=2;
$session[user][turns]-=4;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
//5 Lektionen lernen
if ($HTTP_GET_VARS['op']=="ipa"){
if ($session[user][turns]>=10){
output("5 Lektionen?... jaja ..gute Arbeit mein Sohn..");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=5;
$session[user][turns]-=10;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
// Der Unterricht fürs Abi
if ($HTTP_GET_VARS['op']=="aub2"){
if ($session[user][lektion]>=400) {
output("Du hast das Abi geschafft ,herzlichen Glückwunsch mein Freund");
} else {
addnav ("Den Unterricht verlassen","uni.php");
addnav ("`6 1 Lektion lernen `%( 2 Runden)","uni.php?op=l");
addnav ("`6 2 Lektionen lernen `%( 4 Runden)","uni.php?op=5");
addnav ("`6 5 Lektionen lernen `%( 10 Runden)","uni.php?op=ipe");
output ("Du betrittst ehrfürchtig die Bibliothek, neben dem noch Eindrucksvolleren Hörsaal, und der schäbigen alten Schule.");
viewcommentary("uni2","Flüstern",25);
}
}
//Die Lektionsvariaten fürs Abi
//1 Lektion lernen
if ($HTTP_GET_VARS['op']=="l"){
if ($session[user][turns]>=2){
output("Du hast eine Lektion gelernt mein Junge..gute Arbeit!");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=1;
$session[user][turns]-=2;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
//2 lektionen lernen
if ($HTTP_GET_VARS['op']=="5"){
if ($session[user][turns]>=4){
output("Du hast zwei Lektionen gelernt.. gut,gut..");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=2;
$session[user][turns]-=4;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
//5 Lektionen lernen
if ($HTTP_GET_VARS['op']=="ipe"){
if ($session[user][turns]>=10){
output("5 Lektionen?... jaja ..gute Arbeit mein Sohn..");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=5;
$session[user][turns]-=10;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
// Der Unterricht fürs Studieren
if ($HTTP_GET_VARS['op']=="aub3"){
if ($session[user]['lektion']>=400){
output("Du bist mit dem Studieren fertig..");
addnav("Zurück zur Universität","uni.php");
} else {
addnav ("Den Unterricht verlassen","uni.php");
addnav ("`6 1 Lektion lernen `%( 2 Runden)","uni.php?op=lr");
addnav ("`6 2 Lektionen lernen `%( 4 Runden)","uni.php?op=8");
addnav ("`6 5 Lektionen lernen `%( 10 Runden)","uni.php?op=ipl");
output ("Du bettritst Mutig den Gigantischen Hörsaal neben dem die alte Schule und die Bibliothek geradezu lächerlich aussehen..");
viewcommentary("uni3","Etwas Beitragen",25);
}
}
//Lektionsvarianten des Studiums
//1 Lektion lernen
if ($HTTP_GET_VARS['op']=="lr"){
if ($session[user][turns]>=2){
output("Du hast eine Lektion gelernt mein Junge..gute Arbeit!");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=1;
$session[user][turns]-=2;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
//2 Lektionen lernen
if ($HTTP_GET_VARS['op']=="8"){
if ($session[user][turns]>=4){
output("Du hast zwei Lektionen gelernt.. gut,gut..");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=2;
$session[user][turns]-=4;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
//5 Lektionen lernen
if ($HTTP_GET_VARS['op']=="ipl"){
if ($session[user][turns]>=10){
output("5 Lektionen?... jaja ..gute Arbeit mein Sohn..");
addnav("Zurück zum Unterricht","uni.php?op=aub1");
$session[user][lektion]+=5;
$session[user][turns]-=10;
} else {
output("Du bist zu müde um heute noch zu Lernen..");
addnav("Ins Dorf","village.php");
}
}
page_footer();
?> 