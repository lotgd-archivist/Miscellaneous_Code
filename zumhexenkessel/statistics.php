<?php
#########################################
#                                       #
#  Alchemieinformationen f. Spieler     #
#  Übersicht über die Fähigkeit         #
#  Alchemie sowie Auflistung aller      #
#  alchemistischen Gegenstände und      #
#  Zutaten                              #
#  by Laserian                          #
#  v 1.0                                #
#                                       #
#########################################
require_once "common.php";
popup_header ("Alchemie");
$acctid=$session['user']['acctid'];
loadtable("*","alchemie");
if($_GET['op']==""){
$req = $session['alchemie']['level']*(1000+100*($session['alchemie']['level']+1));
$exp = $session['alchemie']['progress'];
    output("`^<font size=4>Alchemie:</font>`n`n
    <font size=3>Fortschritt:</font>`n`n
    Stufe: ".$session['alchemie']['level']."`n
    Fortschritt: ".grafbar($req,$exp,100,10)."`n`n
    <font size=3>Fertigkeitspunkte:</font>`n`n
    Verfügbar: ".$session['alchemie']['fertpkt']."`n`n
    Kräuterkunde: ".$session['alchemie']['krautkunde'],true);
    if($session['alchemie']['fertpkt']>0){
    rawoutput("<a href='statistics.php?op=add&act=kraut'>+</a>");
    }
    output("`nTränke brauen: ".$session['alchemie']['trankbrau']);
    if($session['alchemie']['fertpkt']>0){
    rawoutput("<a href='statistics.php?op=add&act=trank'>+</a>");
    }
    output("`nUmgang mit den Geräten: ".$session['alchemie']['umgang']);
    if($session['alchemie']['fertpkt']>0){
    rawoutput("<a href='statistics.php?op=add&act=umgang'>+</a>");
    }
    rawoutput("`n`n<font size=3>Häufige Kräuter:</font>`n`n
    Giftige Kräuter:`n`n
    Alraunenwurzeln: ".$session['alchemie']['zutat1']." Skorpionstachel: ".$session['alchemie']['zutat2']."`n
    Dämonenhörner: ".$session['alchemie']['zutat3']." Weißer Lotos: ".$session['alchemie']['zutat4']."`n`n
    Heilkräuter:`n`n
    Tigerlilien: ".$session['alchemie']['zutat5']." Feenstaub: ".$session['alchemie']['zutat6']."`n
    Einhornhaare: ".$session['alchemie']['zutat7']." Engelsfedern: ".$session['alchemie']['zutat8']."`n`n
    <font size=3>Besondere Kräuter:</font>`n`n
    Drachenblut: ".$session['alchemie']['zutat1s']." Phönixfedern: ".$session['alchemie']['zutat2s']."`n`n
    `b<font size=3>Rezepte:</font>`b`n`n
    ");
    if($session['alchemie']['recipe']>=1){
    output("Leichter Heiltrank:`nTigerlilien: ".$session['alchemie']['zutat5heal']." Feenstaub: ".$session['alchemie']['zutat6heal']."`n`n");
    }
    if($session['alchemie']['recipe']>=2){
    output("Heiltrank:`nEinhornhaare: ".$session['alchemie']['zutat7heal']." Engelsfedern: ".$session['alchemie']['zutat8heal']."`n`n");
    }
    if($session['alchemie']['recipe']>=3){
    output("Schlafgift:`nAlraunenwurzeln: ".$session['alchemie']['zutat1poison']." Skorpionstachel: ".$session['alchemie']['zutat2poison']."`n`n");
    }
    if($session['alchemie']['recipe']>=4){
    output("Lähmungsgift:`nDämonenhörner: ".$session['alchemie']['zutat3poison']." Weißer Lotos: ".$session['alchemie']['zutat4poison']."`n`n");
    }
    if($session['alchemie']['recipe']>=5){
    output("Steinhauttrank:`nSkorpionstachel: ".$session['alchemie']['zutat2defpush']." Tigerlilien: ".$session['alchemie']['zutat5defpush']."`n`n");
    }
    if($session['alchemie']['recipe']>=6){
    output("Krafttrank:`nAlraunenwurzeln: ".$session['alchemie']['zutat1attpush']." Feenstaub: ".$session['alchemie']['zutat6attpush']."`n`n");
    }
    if($session['alchemie']['recipe']>=7){
    output("Lebenstrank:`nAlraunenwurzeln: ".$session['alchemie']['zutat1permhp']." Feenstaub: ".$session['alchemie']['zutat6permhp']."`n
    Einhornhaare: ".$session['alchemie']['zutat7permhp']." Phönixfedern: ".$session['alchemie']['zutat2spermhp']."`n`n");
    }
    if($session['alchemie']['recipe']>=8){
    output("Schönheitstrank:`nSkorpionstachel: ".$session['alchemie']['zutat2charme']." Tigerlilien: ".$session['alchemie']['zutat5charme']."`n
    Feenstaub: ".$session['alchemie']['zutat6charme']." Drachenblut: ".$session['alchemie']['zutat1scharme']."`n`n");
    }
    if($session['alchemie']['recipe']>=9){
    output("Elixier der Stärke:`nDämonenhörner: ".$$session['alchemie']['zutat3permatt']." Einhornhaare: ".$$session['alchemie']['zutat7permatt']."`n
    Drachenblut: ".$$session['alchemie']['zutat1spermatt']." Phönixfedern: ".$$session['alchemie']['zutat2spermatt']."`n`n");
    }
    if($session['alchemie']['recipe']>=10){
    output("Elixier des Geschicks:`nWeißer Lotos: ".$$session['alchemie']['zutat4permdef']." Engelsfedern: ".$$session['alchemie']['zutat8permdef']."`n
    Drachenblut: ".$$session['alchemie']['zutat1spermdef']." Phönixfedern: ".$$session['alchemie']['zutat2spermdef']."`n`n");
    }
addnav("","statistics.php?op=add&act=kraut");
addnav("","statistics.php?op=add&act=trank");
addnav("","statistics.php?op=add&act=umgang");
}
if($_GET['op']=="add"){
$session['alchemie']['fertpkt']-=1;
switch($_GET['act']){
case "kraut":
    rawoutput("Die Fähigkeit Krautkunde wurde um einen Punkt erhöht.`n`n`n
    <a href='statistics.php'>Zurück</a>");
    $session['alchemie']['krautkunde']+=1;
    addnav("","statistics.php");
break;
case "trank":
    rawoutput("Die Fähigkeit Tränke brauen wurde um einen Punkt erhöht.`n`n`n
    <a href='statistics.php'>Zurück</a>");
    $session['alchemie']['trankbrau']+=1;
    addnav("","statistics.php");
break;
case "umgang":
    rawoutput("Die Fähigkeit Umgang mit Geräten wurde um einen Punkt erhöht.`n`n`n
    <a href='statistics.php'>Zurück</a>");
    $session['alchemie']['umgang']+=1;
    addnav("","statistics.php");
break;
}
savetable("alchemie");
}
$copyright ="<div align='center'><a href=http://www.lotgd-midgar.de/index.php target='_blank'>&copy;`#Laserian`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
popup_footer();
?> 