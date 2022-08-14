
<?php
require_once "common.php";
$user =& $session[user];
// Armdrücken
// By Hadriel
$infos=array(
"owner"=>"Donols Haus der Qualen", 
"file" => "".basename(__FILE__),
"creator" => "Hadriel",
"homepage" => "http://www.hadrielnet.ch",
"version" => "2.2"
);
page_header($infos['owner']);
if($_GET[op]==""){
output("`2Donol begrüsst dich in seinem sogenannten 'Haus der Qualen'. Hier kannst du gegen seine Jungs, Jirok, Kolop und Faler armdrücken. Jeder der drei hat andere stärken und schwächen. `nEr macht dich darauf aufmerksam, dass du erst mit 500 Goldstücken hier kämpfen kannst.");
if($user[gold]>=500){
addnav("Jirok","".$infos['file']."?op=1");
addnav("Kolop","".$infos['file']."?op=2");
addnav("Faler","".$infos['file']."?op=3");
}
addnav("Kryphton Stadtplatz","kryphton.php?op=platz");
}
if($_GET[op]==1){
output("`2Du wählst Jirok aus. Ihr setzt euch an einen Tisch, legt einen eurer Arme auf den Tisch und beginnt.");
output("`n`n`2Aktueller Status:");
output("`n".grafbar(100,50,100,20)."",true);
addnav("Weiter","".$infos['file']."?op=11");
}
if($_GET[op]==11){
$rand=e_rand(1,100);
$session['try']++;
$try=$session['try'];
output("`2Du versuchst wie Wild den Arm von Jirok auf den Tisch zu schmettern.");

if(e_rand(1,30)==3){
output("`n`n`2Einer der umherstehenden Leute lenkt dich mit Grimassen ab!");
$rand=e_rand(1,50);
$abl=1;
}
else if(e_rand(1,30)==6){
output("`n`n`2Einer der umherstehenden Leute lenkt Jirok mit Grimassen ab!");
$abl2=1;
$rand=e_rand(50,100);
}
if($abl==0){
if($rand<33) $rand=e_rand(1,50);
if($rand>66) $rand=e_rand(50,100);
}else{
if($rand<33) $rand=e_rand(1,35);
if($rand>66) $rand=e_rand(35,80);
}
if($abl2==0){
if($rand<33) $rand=e_rand(1,50);
if($rand>66) $rand=e_rand(50,100);
}else{
if($rand<33) $rand=e_rand(50,75);
if($rand>66) $rand=e_rand(75,100);
}
if($rand<45){
output("`2`n`nDie umherstehenden Leute feuern Jirok an!");
}
if($rand>55){
output("`2`n`nDie umherstehenden Leute feuern dich an!");
}
if($rand>=45 && $rand<=55) output("`2`n`nEs herrscht Kraftgleichheit!");
output("`n`n`2Aktueller Status:`nDu: ".$rand."%, dein Gegner: ".round(100-$rand)."% (Wenn du unter 10% fällst, verlierst du, umgekehrt gewinnst du)");
output("`n".grafbar(100,$rand,100,20)."",true);
if($rand<=10){
$gold=round($user['gold']/10);
output("`n`2Du hast in ".$try." Zügen `4VERLOREN!`2 Jirok schnappt sich ".$gold." Gold von dir.");
$user[gold]-=$gold;
addnav("Zurück",$infos['file']);
$session['try']=0;
}else if($rand>=90){
$gold=500;
output("`n`2Du hast in ".$try." Zügen `4GEWONNEN!`2 Du schnappst dir ".$gold." Gold von Jirok.");
$user[gold]+=$gold;
addnav("Zurück",$infos['file']);
$session['try']=0;
}else{
addnav("Weiter","".$infos['file']."?op=11");
}
}

if($_GET[op]==2){
output("`2Du wählst Kolop aus. Ihr setzt euch an einen Tisch, legt einen eurer Arme auf den Tisch und beginnt.");
output("`n`n`2Aktueller Status:");
output("`n".grafbar(200,100,100,20)."",true);
addnav("Weiter","".$infos['file']."?op=21");
}
if($_GET[op]==21){
$rand=e_rand(1,200);
$session['try']++;
$try=$session['try'];
output("`2Du versuchst wie Wild den Arm von Kolop auf den Tisch zu schmettern.");
if(e_rand(1,30)==3){
output("`n`n`2Einer der umherstehenden Leute lenkt dich mit Grimassen ab!");
$abl=1;
$rand=e_rand(1,100);
}
else if(e_rand(1,30)==6){
output("`n`n`2Einer der umherstehenden Leute lenkt Kolop mit Grimassen ab!");
$abl2=1;
$rand=e_rand(100,200);
}
if($abl==0){
if($rand<66) $rand=e_rand(1,100);
if($rand>132) $rand=e_rand(100,200);
}else{
if($rand<66) $rand=e_rand(1,70);
if($rand>132) $rand=e_rand(70,160);
}
if($abl2==0){
if($rand<66) $rand=e_rand(1,100);
if($rand>132) $rand=e_rand(100,200);
}else{
if($rand<66) $rand=e_rand(100,150);
if($rand>132) $rand=e_rand(150,200);
}
if($rand<90){
output("`2`n`nDie umherstehenden Leute feuern Kolop an!");
}
if($rand>110){
output("`2`n`nDie umherstehenden Leute feuern dich an!");
}
if($rand>=90 && $rand<=110) output("`2`n`nEs herrscht Kraftgleichheit!");
output("`n`n`2Aktueller Status:`nDu: ".round($rand/2)."%, dein Gegner: ".round(100-($rand/2))."% (Wenn du unter 10% fällst, verlierst du, umgekehrt gewinnst du)");
output("`n".grafbar(200,$rand,100,20)."",true);
if($rand<=20){
$gold=round($user['gold']*0.2);
output("`n`2Du hast in ".$try." Zügen `4VERLOREN!`2 Kolop schnappt sich ".$gold." Gold von dir.");
$user[gold]-=$gold;
addnav("Zurück",$infos['file']);
$session['try']=0;
}else if($rand>=180){
$gold=1250;
$user[gold]+=$gold;
output("`n`2Du hast in ".$try." Zügen `4GEWONNEN!`2 Du schnappst dir ".$gold." Gold von Kolop.");
addnav("Zurück",$infos['file']);
$session['try']=0;
}else{
addnav("Weiter","".$infos['file']."?op=21");
}
}

if($_GET[op]==3){
output("`2Du wählst Faler aus. Ihr setzt euch an einen Tisch, legt einen eurer Arme auf den Tisch und beginnt.");
output("`n`n`2Aktueller Status:");
output("`n".grafbar(300,150,100,20)."",true);
addnav("Weiter","".$infos['file']."?op=31");
}
if($_GET[op]==31){
$rand=e_rand(1,300);
$session['try']++;
$try=$session['try'];
output("`2Du versuchst wie Wild den Arm von Faler auf den Tisch zu schmettern.");
if(e_rand(1,150)==3){
output("`n`n`2Einer der umherstehenden Leute lenkt dich mit Grimassen ab!");
$abl=1;
$rand=e_rand(1,50);
}
else if(e_rand(1,30)==6){
output("`n`n`2Einer der umherstehenden Leute lenkt Faler mit Grimassen ab!");
$abl2=1;
$rand=e_rand(150,300);
}
if($abl==0){
if($rand<99) $rand=e_rand(1,150);
if($rand>198) $rand=e_rand(150,300);
}else{
if($rand<99) $rand=e_rand(1,105);
if($rand>198) $rand=e_rand(105,240);
}
if($abl2==0){
if($rand<99) $rand=e_rand(1,150);
if($rand>198) $rand=e_rand(150,300);
}else{
if($rand<99) $rand=e_rand(150,225);
if($rand>198) $rand=e_rand(225,300);
}
if($rand<135){
output("`2`n`nDie umherstehenden Leute feuern Faler an!");
}
if($rand>175){
output("`2`n`nDie umherstehenden Leute feuern dich an!");
}
if($rand>=135 && $rand<=175) output("`2`n`nEs herrscht Kraftgleichheit!");
output("`n`n`2Aktueller Status:`nDu: ".round($rand/3)."%, dein Gegner: ".round(100-($rand/3))."% (Wenn du unter 10% fällst, verlierst du, umgekehrt gewinnst du)");
output("`n".grafbar(300,$rand,100,20)."",true);
if($rand<=30){
$gold=round($user['gold']*0.3);
output("`n`2Du hast in ".$try." Zügen `4VERLOREN!`2 Faler schnappt sich ".$gold." Gold von dir.");
$user[gold]-=$gold;
$session['try']=0;
addnav("Zurück",$infos['file']);
}else if($rand>=270){
$gold=2500;
$user[gold]+=$gold;
output("`n`2Du hast in ".$try." Zügen `4GEWONNEN!`2 Du schnappst dir ".$gold." Gold von Faler.");
addnav("Zurück",$infos['file']);
$session['try']=0;
}else{
addnav("Weiter","".$infos['file']."?op=31");
}
}
//copyright
output("`n`n`n`n`n `^".$infos['owner']."`2 by <a href='".$infos['homepage']."' target='_blank'>".$infos['creator']."</a> Version ".$infos['version']."`n`n",true);
page_footer();
?>

